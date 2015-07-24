<?php

use fXmlRpc\AbstractDecorator;
use fXmlRpc\Client;
use fXmlRpc\Parser\NativeParser;
use fXmlRpc\Serializer\NativeSerializer;
use fXmlRpc\Transport\CurlTransport;

/**
 * Class Benchmark
 * @method string emailCopy(string $emailid) Duplicate an existing Email and return the ID of the newly created Email.
 * @method string emailCreate(array $emailDetails) Create a new Email based on the details provided. Return the ID of the newly created Email.
 * @method string emailRssCreate(array $emailDetails) Create a new Rss Email based on the details provided. Return the ID of the newly created Rss Email.
 * @method boolean emailDelete(string $emailID) Delete the Email for given ID. Returns true if the email was deleted.
 * @method array emailGet( string $filter, integer $pageNumber, integer $pageSize, string $orderBy, string $sortOrder) Get the list of emails using the filter and paging limits, order by the name or date of the email.
 * @method array emailGetDetail( string $emailID) Get all the details for given Email ID.
 * @method array emailRssGet( string $filter, integer $pageNumber, integer $pageSize, string $orderBy, string $sortOrder) Get the list of Rss emails using the filter and paging limits, order by the name or date of the email.
 * @method array emailRssGetDetail(string $emailID) Get all the details for given Rss Email ID.
 * @method array emailGetList(string $emailID) Get the list of contact lists being used in an email.
 * @method boolean emailSchedule(string $emailID, string $scheduleDate) Schedule an email for delivery for the given date time.
 * @method boolean emailRssSchedule(string $emailID, string $scheduleDate, string $interval) Schedule an Rss email for delivery for the given date time and interval.
 * @method boolean emailSendNow(string $emailID) Schedule an email for immediate delivery.
 * @method boolean emailSendTest(string $emailID, string $testEmail) Send a test email for the given Email ID
 * @method boolean emailUnSchedule(string $emailID) Set an email as draft. This would clear its delivery schedule.
 * @method boolean emailUpdate(array $emailDetails) Update an existing email with the given details.
 * @method boolean emailRssUpdate(array $emailDetails) Update an existing email with the given details.
 * @method boolean emailAssignList(string $emailID, array $contacts) Assign the given contact/segments to the email.
 * @method boolean emailReassignList(string $emailID, array $contacts) Reassign the given contact/segments to the email.
 * @method string emailResend(string $emailID,string $scheduleDate) Resend an email campaign to contacts that were added since the campaign was last sent .
 * @method string emailQuickSend(string $emailID, string $ListName, array $emails, string $scheduleDate) Quick send the email campaign to a list of contacts .
 * @method array emailCategoryGetList() Gets all the available email Template Categories.
 * @method string autoresponderCreate(array $Autoresponder) Create an Autoresponder campaign .
 * @method boolean autoresponderUpdate(int $status, array $Autoresponder) Update an Autoresponder campaign .
 * @method boolean autoresponderDelete(string $autoresponderID) Delete an Autoresponder campaign .
 * @method boolean autoresponderDetailDelete(string $autoresponderID, string $autoresponderDetailID) Delete an Autoresponder email .
 * @method array autoresponderGetList( integer $pageNumber, integer $pageSize, string $orderBy, string $filter, string $sortOrder) Get the list of Autoresponders using the filter and paging limits, order by the name or date of the autoresponder.
 * @method array autoresponderGetEmailDetail(string $autoresponderID, string $autoresponderDetailID) Get details of the Autoresponder email .
 * @method array autoresponderGetDetail(string $autoresponderID) Get details of the Autoresponder.
 * @method string autoresponderDetailCreate(array $AutoresponderDetail) Create Autoresponder email template and Returns the ID of the newly created Autoresponder email template.
 * @method integer listAddContacts(string $listID, array $contacts) Add the contact details to the given contact list. Multiple contacts would be added if the details has more than one items.
 * @method array listAddContactsRetID(string $listID, array $contacts) Add the contact details to the given contact list and returns contacts ID's in CSV format.
 * @method integer listAddContactsOptin(string $listID, array $contacts, string $optin) Add the contact details to the given contact list after confirmation. Multiple contacts would be added if the details has more than one items.
 * @method string batchAddContacts(string $listID, array $contacts) Adding Multiple Contacts to a Email List and returns batch ID.
 * @method string batchGetStatus(string $listID, string $batchID) Returns status for the batch upload based on the batch ID.
 * @method string listCreate(string $listName) Create a new contact list with the given name. Returns the ID of the newly created list.
 * @method boolean listUpdate(string $listID, string $listName, string $listDiscription) Update an existing contact list with the given name and discription.
 * @method boolean listDelete(string $listID) Delete an existing contact list.
 * @method array listSearchContacts(string $emailID) Get the contact list Details for the given email ID.
 * @method array listGet(string $filter, integer $pageNumber, integer $pageSize, string $orderBy, string $sortOrder) Get the list of contact lists using the filter and paging limits, ordered by the name or date of the contact list.
 * @method array listGetContactDetails(string $listID, string $email) Get the contact details from the contact list for the given email address.
 * @method array listGetContacts(string $listID, string $filter, integer $pageNumber, integer $pageSize, string $orderBy, string $sortOrder) Get the list of contacts in the given list using the filter and paging limits, ordered by the email or date of the contact.
 * @method array listGetContactsAllFields(string $listID, string $filter, integer $pageNumber, integer $pageSize, string $orderBy, string $sortOrder) Get the list of contacts in the given list using the filter and paging limits, ordered by the email or date of the contact.
 * @method array listGetContactsByType(string $listID, string $filter, integer $pageNumber, integer $pageSize, string $orderBy, string $sortOrder, string $Type) Get the list of contacts in the given list of specific type may be(Optin or NotOptedIn or ConfirmedBounces or Active or Unsubscribe) using the filter and paging limits, ordered by the email or date of the contact.
 * @method array listUpdateContactDetails(string $listID, string $contactID, array $contactDetail) Update the given contact in the list based on the details provided.
 * @method integer listUnsubscribeContacts(string $listID, array $contacts) Unsubscribe the contacts from the given contact list.
 * @method boolean listDeleteContacts(string $listID, string $contactids) Delete contacts from the given contact list.
 * @method boolean listDeleteEmailContact(string $listID, string $email) Delete a contact which matches the email from the given contact list.
 * @method integer listAddContactsForm(string $signupFormID, array $contacts) Add the contact details using the given signup form.
 * @method int contactsGetUniqueActiveCount() Calculates count of total unique active contacts
 * @method string segmentCreate(array $segmentDetail) Create a new segment based on the given details
 * @method boolean segmentDelete(string $segmentID) Delete a segment based on the given ID
 * @method array segmentGet(string $filter, int $pageNumber, int $pageSize, string $orderBy) Get the list of segments using the paging limits, ordered by the name or date of the segment
 * @method array segmentGetDetail(string $segmentID) Get the details of segment based on the segment ID
 * @method array segmentGetCriteriaList(string $segmentID) Get the list of segment criteria
 * @method string segmentCreateCriteria(string $segmentID, array $segmentCriteria) Create a segment criteria
 * @method string segmentGetContacts(string $segmentID, string $filter, int $pageNumber, int $pageSize, string $orderBy, string $sortOrder) Get the contacts for a segment
 * @method int segmentGetCount(string $filter) Get the count of segments
 * @method array reportGet(string $filter, integer $pageNumber, integer $pageSize, string $orderBy, string $sortOrder) Get the list of sent campaign using the filter and paging limits, ordered by the name or date of the campaign.
 * @method array reportGetBounces(string $emailID, integer $pageNumber, integer $pageSize, string $Filter,string $orderBy,string $sortOrder) Get the email addresses which bounced in a given campaign,using the paging limits, ordered by the email or date of the bounced record.
 * @method array reportGetClicks(string $emailID) Get the click URL stats for the given campaign.
 * @method array reportGetEmailLinks(string $emailID) Get the Link URLS for the given campaign.
 * @method array reportGetClickEmails(string $emailID,string $ClickURL, int $PageNumber, int $pageSize, string $orderBy, string $sortOrder) Get the email address which have clicked on URLs for the given campaign.
 * @method array reportGetForwards(string $emailID, integer $pageNumber, integer $pageSize, string $orderBy, string $sortOrder) Get the email addresses to which the given campaign was forwarded,using the paging limits, ordered by the email or date of the forwarded record.
 * @method array reportGetHardBounces(string $emailID, integer $pageNumber, integer $pageSize, string $orderBy, string $sortOrder) Get the email addresses which hard bounced in a given campaign,using the paging limits, ordered by the email or date of the bounced record.
 * @method array reportGetSoftBounces(string $emailID, integer $pageNumber, integer $pageSize, string $orderBy, string $sortOrder) Get the email addresses which soft bounced in a given campaign,using the paging limits, ordered by the email or date of the bounced record.
 * @method array reportGetOpens(string $emailID, integer $pageNumber, integer $pageSize, string $orderBy, string $sortOrder) Get the email addresses which were opened in a given campaign,using the paging limits, ordered by the email or date of the opened record.
 * @method array reportGetUnopens(string $emailID, integer $pageNumber, integer $pageSize, string $orderBy, string $sortOrder) Get the email addresses which neither opened nor bounced in a given campaign,using the paging limits, ordered by the email or date of the opened record.
 * @method array reportGetUnsubscribes(string $emailID, integer $pageNumber, integer $pageSize, string $orderBy, string $sortOrder) Get the email addresses which unsubscribed in a given campaign,using the paging limits, ordered by the email or date of the unsubscribe record.
 * @method array reportGetSummary(string $emailID) Get the summary statistics for a given campaign.
 * @method array reportCompare(string $emailIDs) Get the summary statistics for more than one campaign.
 * @method array reportGetOpenCountry(string $emailID) Get the list of Countries with there region code and opent Count
 * @method array reportGetOpenForCountry(string $emailID,string $CountryCode) Retrives Open Count for each region of the country
 * @method string surveyCreate(SurveyStructure  $surveyData) Create a new Survey based on the details provided. Return the ID of the newly created Survey.
 * @method string surveyUpdate(string $SurveyID, SurveyStructure  $surveyData) Update an existing Survey based on the details provided. Return the ID Updated Survey.
 * @method string surveyDelete(string $SurveyID) Delete an existing Survey based on the details provided. Return the ID of Deleted Survey.
 * @method string surveyQuestionCreate(string $SurveyID, SurveyQuestionStructure  $surveyQuestionData) Create a new Survey Question based on the details provided. Return the ID of the newly created Survey Question.
 * @method string surveyQuestionDelete(string $SurveyID, string $QuestionID) Delete an existing Survey Question based on the details provided. Return the ID of the Deleted Survey Question.
 * @method string surveyQuestionUpdate(string $SurveyID, SurveyQuestionStructure  $surveyQuestionData) Update an existing Survey Question based on the details provided. Return the ID of the updated Survey Question.
 * @method string surveyColorUpdate(string $SurveyID, SurveyColorStructure  $surveyColorData) Update Survey Color based on the details provided. Return the ID of the updated Survey.
 * @method integer surveyStatusUpdate(string $SurveyID, string $Status) Update Survey Status based on the details provided. Returns 1 if the survey is updated else 0.
 * @method array surveyGetList(string $filter, string $status, int $pagenumber, int $pagesize, string $orderBy, string $sortOrder) Get the list of Surveys using the filter and paging limits, order by the name or date of the surveys.
 * @method array surveyGetColor(string $SurveyID) Get the color of Surveys.
 * @method array surveyGetQuestionList(string $SurveyID) Generates the report of all the available survey questions
 * @method array surveyReportList(string $filter, string $status, int $pagenumber, int $pagesize, string $orderBy, string $sortOrder) Get the report of Survey.
 * @method array surveyResponseReport(string $filter, string $status, int $pagenumber, int $pagesize, string $orderBy, string $sortOrder) Get the list of Survey Questions with answers and there responses.
 * @method array surveyTemplateGetList() Provide all available templates for the survey.
 * @method string surveyCopy(string $SurveyID,string $NewSurveyName) Create a new Survey using existing survey. Return the ID of the newly created Survey.
 * @method string surveyCopyTemplate(string $SurveyID,string $NewSurveyName) Create a new Survey using existing survey template. Return the ID of the newly created Survey.
 * @method string pollCreate(PollStructure  $pollData) Create a new Poll based on the details provided. Return the ID of the newly created Poll.
 * @method string pollUpdate(string $PollID, PollStructure  $pollData) Update an existing Poll based on the details provided. Return the ID of the Updated Poll.
 * @method string pollDelete(string $PollID) Delete an existing Poll based on the details provided. Return the ID of the Deleted Poll.
 * @method integer pollStatusUpdate(string $PollID, string $Status) Update Poll Status based on the details provided. Return 1 if the poll is updated else 0.
 * @method array pollGetList(string $filter, string $status, int $pagenumber, int $pagesize, string $orderBy, string $sortOrder) Get the list of Polls using the filter and paging limits, order by the name or date of the polls.
 * @method string pollCopy(string $PollID,string $NewPollName) Create a new Poll using existing poll. Return the ID of the newly created Poll.
 * @method array pollReportList(string $filter, string $status, int $pagenumber, int $pagesize, string $orderBy, string $sortOrder) Generates the report of all the available poll.
 * @method array pollResponseReport(string $PollID) Get the list of Poll Options with their responses.
 * @method boolean videoCreate(VideoGalleryStructure $videoStructure) Add the Video.Returns true if the video is embeded else false.
 * @method string videoDelete(string $VideoID) Delete the Video.Returns the ID of the deleted video.
 * @method array videoGetList(int $pagenumber, int $pagesize) Get the list of Videos using paging limits.
 * @method string signupFormCreate(SignupFormDataStructure  $signupForm) Create a new Signup Form based on the details provided. Return the ID of the newly created SignUpForm.
 * @method boolean signupFormUpdateColor(string $signupFormID, SignupFormColorStructure  $signupForm) Update Colors,background and font of an existing SignUp Form based on the details provided. Return booleanean value true if form updated else false
 * @method boolean signupFormUpdate(string $signupFormID, SignupFormDataStructure  $signupForm) Update an existing SignUp Form based on the details provided. Return booleanean value true if SignupForm updated.
 * @method boolean signupFormUpdateMessage(string $signupFormID, SignupFormDataStructure  $signupForm) Update various custom fields of an existing SignUp Form based on the details provided. Return booleanean value true if form updated else false
 * @method array signupFormGet(string $signupFormID) Retrive an existing SignUp Form based on the details provided. Return SignupFormDataStructure
 * @method string signupFormGetCode(string $signupFormID, string $Codetype) Retrive an existing SignUp Form's Code based on the details provided. Returns code as the string.
 * @method boolean signupFormDelete(string $signupFormID) Delete an existing SignUp Form based on the details provided. Returns booleanean true if deleted else false
 * @method array listGetSignupForms(integer $pageNumber, integer $pageSize, string $orderBy) Get the list of signup forms using the paging limits, ordered by the name or date of the signup form.
 * @method boolean imageAdd(ImageData $imgdata) Add the Image.Returns true if the image is embeded else false.
 * @method boolean imageDelete(string $ImageID) Delete an Image from the client's account.Returns true if image has been deleted or else throws an exception.
 * @method array imageGetList(int $pagenumber, int $pagesize) Get the list of Images in the client's Image Gallery.
 * @method array imageGet(string $imageID) Get details for an image.Returns Image data.
 * @method Integer imageGetCount() Get the count of images in the client's Image Gallery.Returns the count of Images.
 * @method string login(string $username, string $password) Authenticate the user and returns a token.
 * @method boolean tokenAdd(string $username, string $password, string $token) Add a token for the user.
 * @method boolean tokenDelete(string $username, string $password, string $token) Delete an existing token for the user.
 * @method integer subAccountCreate( array $accountstruct) Register a new SubAccount for the user
 * @method integer subAccountUpdate( array $accountstruct) Update SubAccount details for the user
 * @method array subAccountGetList() Get the list of SubAccounts for the user
 * @method boolean subAccountUpdateStatus( string $ID, string $status) Update SubAccount status for the user
 * @method array confirmEmailList() Get the list of emails sent for confirmation by the user
 * @method string confirmEmailAdd( string $targetEmailID) Adding emails for confirmation for the user
 * @method string webhookCreate(array $webhookDetails) Create a new Webhook based on the details provided. Return the ID of the newly created Webhook.
 * @method boolean webhookDelete(string $webhookID) Delete the Webhook for given ID. Returns true if the webhook was deleted.
 * @method array webhookGet( string $listID) Get the list of webhook using the listID of the contact list.
 * @method boolean webhookUpdate(array $webhookDetails) Update an existing webhook with the given details.
 * @method array eventGetList(string $filtertype, string $filterdetail, string $pageNumber, string $pageSize, string $orderBy, string $sortOrder) Get the list of Events using the filter and paging limits, order by the name of the events.
 * @method array eventGet(string $eventid) Get the details of an Event.
 * @method array eventGetTicketType(string $eventid) Get the tickets of the event.
 * @method array eventTemplateGetList(string $categoryID, string $filter, string $lang) Get the list of event templates.
 * @method boolean eventUpdatePage(string $eventID, string $templateID, string $content, string $css, string $csscode) Update the page for the event.
 * @method boolean eventInviteCampaignUpdate(string $eventID, string $emailID, string $content, string $contentRaw, string $address, string $city, string $state, string $zip, string $intlAddress, string $isIntl, string $textVersion, string $templateID, string $senderName, string $replyEmail, string $subject, string $listIDs) Update the invite for the event.
 * @method array ticketGetList(string $eventID, string $filtertype, string $dateID, string $filterdetail, string $pageNumber, string $pageSize, string $orderBy, string $sortOrder) Get the event Ticket List.
 * @method array ticketGetByID(string $eventID, string $ticketID) Get the event Ticket By ID.
 * @method boolean attendeeCheckIn(string $eventID, string $ticketitemID) Attendee Checkin
 * @method boolean attendeeCheckOut(string $eventID, string $ticketitemID) Attendee Checkout
 * @method string eventUpdatePayStatus(string $EventID, string $orderid, int $status) Set the Event Order Pay Status
 * @method string eventUpdateTicketItemPayStatus(string $EventID, string $TicketItemID, int $status) Set the Event Ticket Pay Status
 * @method string eventUpdateUrl(string $EventID, string $EventURL) Update the event Url
 * @method int eventUpdatePaypalEmail(string $EmailID, string $OldEmailID) Update the Paypal Email
 * @method string eventUpdateEventGoogleMerchant(string $GoogleMID, string $GoogleMKEY, string $OldGoogleMID, string $OldGoogleMKEY) Update the Google Merchant
 * @method string eventRefund(string $EventID, string $orderID) Set the order status refund
 * @method string eventTicketItemRefund(string $EventID, string $ticketitemID) Set the ticket status refund
 * @method string eventResendTicket(string $EventID, string $orderID) Resend event Ticket
 * @method string eventCopy(string $eventID, string $eventName) Copy the event
 * @method int eventCreateFBEvent(string $EventID, string $PageID, string $PageName, string $PageToken, string $Eventname, string $StartTime, string $Location, string $Description) Create the event on Facebook
 * @method int eventUpdateFBEvent(string $EventID, string $StartTime, string $Location, string $Description) Update the event on Facebook
 * @method int eventCancelFBEvent(string $EventID) Cancel the event on Facebook
 * @method string eventValidatePayPalEmail(string $Email) Validate the PayPal Email
 */
class Benchmark extends AbstractDecorator
{
    public function __construct($key)
    {
        parent::__construct(new Client(
            'http://api.benchmarkemail.com/1.3/',
            new \fXmlRpc\Transport\HttpAdapterTransport(
                new \Ivory\HttpAdapter\CurlHttpAdapter
            ),
            new NativeParser,
            new NativeSerializer
        ));

        // Creds
        $this->wrapped->prependParams((array) $key);
    }

    public function listGetIdByName($name)
    {
        $lists = $this->listGet($name, 1, 1, '', '');

        return !empty($lists) ? $lists[0]['id'] : null;
    }

    public function listGetById($id)
    {
        $lists = $this->listGet('', 1, 100, '', '');

        foreach ($lists as $list) {
            if ($list['id'] == $id) {
                return $list;
            }
        }

        return null;
    }

    /**
     * @param $method
     * @param $arguments
     * @return mixed
     */
    public function __call($method, $arguments)
    {
        return $this->call($method, $arguments);
    }
}
