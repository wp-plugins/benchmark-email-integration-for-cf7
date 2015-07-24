(function(b,c){var $=b.jQuery||b.Cowboy||(b.Cowboy={}),a;$.throttle=a=function(e,f,j,i){var h,d=0;if(typeof f!=="boolean"){i=j;j=f;f=c}function g(){var o=this,m=+new Date()-d,n=arguments;function l(){d=+new Date();j.apply(o,n)}function k(){h=c}if(i&&!h){l()}h&&clearTimeout(h);if(i===c&&m>e){l()}else{if(f!==true){h=setTimeout(i?k:l,i===c?e-m:e)}}}if($.guid){g.guid=j.guid=j.guid||$.guid++}return g};$.debounce=function(d,e,f){return f===c?a(d,e,false):a(d,f,e!==false)}})(this);
+function($) {
    $(function() {
        var $list = $('#cf7-benchmark-list');

        $('.cf7-benchmark-ext #add').on('click', function() {
            // Add a new custom field from the template
            var $template = $('.cf7-benchmark-ext .template').clone();
            $template.removeClass('template');

            // How many do we already have?
            var index = $('.cf7-benchmark-ext .custom-fields div:not(.template)').length;

            $template.find('select').attr('name', 'cf7-benchmark[custom][' + index + '][from]');
            $template.find('input').attr('name', 'cf7-benchmark[custom][' + index + '][to]');

            $('.cf7-benchmark-ext .custom-fields p.description').before($template);
            return false;
        });

        $('.cf7-benchmark-ext .custom-fields').on('click', '.delete', function() {
            $(this).parent().remove();
        });

        $('#cf7-benchmark-apikey').on('input', $.debounce(250, function() {
            var val = $(this).val();
            var re = /[a-f0-9]{8}-[a-f0-9]{4}-4[a-f0-9]{3}-[89ab][a-f0-9]{3}-[a-f0-9]{12}/i;

            if ((m = re.exec(val)) == null) {
                $list.children().remove();
                $list.append('<option>Enter your API Key</option>');
                $('.cf7-benchmark-ext .refresh').css({display: 'none'});
                return;
            }

            // Get list of lists
            $.ajax({
                url: ajaxurl,
                data: {
                    action: 'cf7-benchmark-getlists',
                    apikey: val
                },
                success: function (data) {
                    setLists(data);

                    // Save the data in local storage
                    window.localStorage.setItem('cf7-benchmark-lists', JSON.stringify(data));

                    // Remove error message
                    $('.cf7-benchmark-ext .error-message').hide();
                },
                error: function () {
                    $list.children().remove();
                    $list.append('<option>Enter your API Key</option>');
                    $('.cf7-benchmark-ext .error-message').show();
                },
                beforeSend: function() {
                    $list.children().remove();
                    $('.cf7-benchmark-ext .spinner').css({visibility: 'visible'});
                    $('.cf7-benchmark-ext .refresh').css({display: 'none'});
                },
                complete: function() {
                    $('.cf7-benchmark-ext .spinner').css({visibility: 'hidden'});
                }
            })
        }));

        $('.cf7-benchmark-ext .refresh').on('click', function() {
            $('#cf7-benchmark-apikey').trigger('input');
        });

        try {
            if ((data = JSON.parse(window.localStorage.getItem('cf7-benchmark-lists')))) {
                setLists(data);
            } else {
                throw Error();
            }
        } catch (error) {
            $('#cf7-benchmark-apikey').trigger('input');
        }

        function setLists(data) {
            $list.children().remove();

            $list.append('<option value="">Select a list...</option>');

            var current = $('#cf7-benchmark-list-id').val(), selected;

            for (var i in data) {
                selected = current == data[i].id ? ' selected' : '';
                $list.append('<option value="' + data[i].id + '"' + selected + '>' + data[i].name + '</option>');
            }

            $('.cf7-benchmark-ext .refresh').css({display: 'inline-block'});
        }
    });
}(jQuery);
