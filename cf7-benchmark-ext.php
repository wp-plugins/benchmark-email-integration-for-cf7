<?php

/*
Plugin Name: Contact Form 7 Benchmark Email Extension
Plugin URI: https://bitbucket.org/mattzuba/contact-form-7-benchmark-extension
Description: Integrate Contact Form 7 with Benchmark Email. Automatically add form submissions to your contact lists in Benchmark Email, using its latest API.
Author: Matt Zuba
Author URI: http://www.mattzuba.com
Text Domain: cf7-benchmark-ext
Domain Path: /lang/
Version: 1.0.0
*/

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/Benchmark.php';

class CF7Benchmark
{
    /** @var string */
    private $pluginUrl;

    public function __construct()
    {
        $this->pluginUrl = untrailingslashit(plugins_url('', __FILE__));

        add_action('plugins_loaded', [$this, 'loadTextDomain']);
        add_action('admin_print_scripts', [$this, 'enqueueScripts']);
        add_action('wp_ajax_cf7-benchmark-getlists', [$this, 'getLists']);

        add_filter('wpcf7_editor_panels', [$this, 'addPanel']);
        add_action('wpcf7_after_save', [$this, 'savePanel']);
        add_action('wpcf7_before_send_mail', [$this, 'subscribe']);

    }

    /**
     * Load up the text domain info.  Not really used yet, but here anyway.
     */
    public function loadTextDomain()
    {
        load_plugin_textdomain('cf7-benchmark-ext', FALSE, basename(__DIR__) . '/lang/');
    }

    /**
     * Load up scripts and css used on the admin side
     */
    public function enqueueScripts()
    {
        global $plugin_page;

        if ( ! isset( $plugin_page ) || 'wpcf7' != $plugin_page ) {
            return;
        }

        wp_enqueue_style('cf7-benchmark-admin', $this->pluginUrl . '/assets/css/cf7-benchmark.css', array(), null, 'all' );
        wp_enqueue_script('cf7-benchmark-admin', $this->pluginUrl . '/assets/js/cf7-benchmark.js', array( 'jquery', 'wpcf7-admin' ), null, true );
    }

    /**
     * Adds the extra panel to the CF7 form builder
     *
     * @param $panels
     * @return array
     */
    public function addPanel($panels)
    {
        $panel = array(
            'Benchmark-Extension' => array(
                'title' => __('Benchmark Email'),
                'callback' => [$this, 'displayPanel']
            )
        );

        $panels = array_merge($panels, $panel);

        return $panels;
    }

    /**
     * Displays the panel
     *
     * @param WPCF7_ContactForm $args
     */
    public function displayPanel($args)
    {
        $options = get_option('cf7-benchmark-' . $args->id(), []);
        include __DIR__ . '/views/panel.phtml';
    }

    /**
     * Saves the data from the panel
     *
     * @param WPCF7_ContactForm $args
     */
    public function savePanel($args)
    {
        // Clean up the custom fields
        $customs = [];
        if (!empty($_POST['cf7-benchmark']['custom'])) {
            foreach ($_POST['cf7-benchmark']['custom'] as $custom) {
                $to = trim($custom['to']);
                if (!empty($to)) {
                    $customs[] = $custom;
                }
            }
        }
        $_POST['cf7-benchmark']['custom'] = $customs;

        update_option('cf7-benchmark-' . $args->id(), $_POST['cf7-benchmark']);
    }

    /**
     * Used from the ajax call to get a list of contact forms from the Benchmark API
     */
    public function getLists()
    {
        $apiKey = trim($_GET['apikey']);

        $benchmark = new Benchmark($apiKey);

        $lists = $benchmark->listGet('', 1, 100, '', '');

        $return = [];
        foreach ($lists as $list) {

            // We don't want to list the master unsubscribe list
            if ($list['is_master_unsubscribe']) {
                continue;
            }

            $return[] = [
                'id' => $list['id'],
                'name' => $list['listname'],
            ];
        }

        header('Content-Type: application/json');

        echo json_encode($return);

        wp_die();
    }

    /**
     * Called before mail is sent on CF7 and calls the Benchmark API if needed.
     *
     * @param WPCF7_ContactForm $cf7
     */
    public function subscribe($cf7)
    {
        $options = get_option('cf7-benchmark-' . $cf7->id(), []);

        // If we haven't setup Benchmark on this one, skip
        if (empty($options) || empty($options['apikey']) || empty($options['list'])) {
            return;
        }

        $rawData = WPCF7_Submission::get_instance()->get_posted_data();
        $data = $this->compileData($rawData, $options);
        $benchmark = new Benchmark($options['apikey']);

        // If we don't have an email, we need to bail
        if (empty($data['email'])) {
            return;
        }


        try {
            // Do they already exist on this list?
            $contactSearch = $benchmark->listGetContacts($options['list'], $data['email'], 1, 1, '', '');

            if (!empty($contactSearch)) {
                // They exist, update their info
                $contactId = $contactSearch[0]['id'];
                $benchmark->listUpdateContactDetails($options['list'], $contactId, $data);
            } elseif (!empty($options['optin'])) {
                // Don't exist, but it's opt in
                $benchmark->listAddContactsOptin($options['list'], [$data], '1');
            } else {
                // Don't exist, straight add
                $benchmark->listAddContacts($options['list'], [$data]);
            }
        } catch (Exception $e) {
            error_log($e->getMessage());
        }
    }

    /**
     * Takes all data submitted plus the options from the Benchmark config
     * to create a single array of all data that needs to be used for the
     * Benchmark API.
     *
     * @param $raw
     * @param $options
     * @return array
     */
    private function compileData($raw, $options)
    {
        $data = [
            'email' => $this->getFromArray($raw, $options['email']),
        ];

        if (!empty($options['custom'])) {
            foreach ($options['custom'] as $custom) {
                $data[$custom['to']] = $this->getFromArray($raw, $custom['from']);
            }
        }

        return $data;
    }

    /**
     * Some items from contact form 7 could be an array, or maybe even empty.  We
     * use this to easily capture all of the data from the form.
     *
     * @param $haystack
     * @param $needle
     * @param null $default
     * @return null|string
     */
    private function getFromArray($haystack, $needle, $default = null)
    {
        if (empty($haystack[$needle])) {
            return $default;
        }

        $value = $haystack[$needle];

        if (is_array($value)) {
            $value = implode(', ', $value);
        }

        return $value;
    }

    /**
     * Used to create the select lists in the template from the CF7 form tags
     *
     * @param $tags
     * @param $selected
     * @param $name
     * @param null $id
     * @return string
     */
    private function createSelect($tags, $selected, $name, $id = null)
    {
        $id = $id ?: "cf7-benchmark-$name";
        $select = ["<select name='cf7-benchmark[$name]' id='$id'>"];

        foreach ($tags as $tag) {
            $theone = "$selected" == "$tag" ? ' selected' : '';
            $select[] = "<option{$theone}>$tag</option>";
        }

        $select[] = '</select>';

        return implode('', $select);
    }
}

new CF7Benchmark;
