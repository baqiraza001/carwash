<?php
defined('BASEPATH') or exit('No direct script access allowed');
require FCPATH  . 'modules/email_newsletter/vendor/autoload.php';
use Sendpulse\RestApi\ApiClient;
use Sendpulse\RestApi\Storage\FileStorage;
use Sendpulse\RestApi\ApiClientException;

class Email_newsletter extends AdminController
{
    public function __construct()
    {
        parent::__construct();

        $this->load->model('client_groups_model');
        $this->load->model('tickets_model');
        $this->load->model('leads_model');
        $this->load->model('email_newsletter_model');
        $this->load->model('staff_model');
    }

    public function index()
    {
        show_404();
    }

    public function manage()
    {

        if (!has_permission('email_newsletter', '', 'view')) {
            access_denied('email_newsletter');
        }

        $data = [];

        $data['title'] = _l('email_newsletter') . ' ' . _l('Campaign');

        $data['clientGroups'] = $this->client_groups_model->get_groups();
        $data['lead_statuses'] = $this->leads_model->get_status();
        $data['lead_sources'] = $this->leads_model->get_source();
        $data['staff_members'] = $this->staff_model->get('', ['is_not_staff' => 0, 'active' => 1]);

        if ($this->input->is_ajax_request()) {
            $this->app->get_table_data(module_views_path('email_newsletter', 'table'));
        }

        $this->load->view('true_manage', $data);
    }
    
    public function edit($id = '')
    {
        if (!has_permission('email_newsletter', '', 'view')) {
            access_denied('email_newsletter');
        }
        
        $data = $this->db->where('id', $id)->get('tblemail_newsletter_newsletter_history')->row_array();
        if(!$data)
            show_404();
            
        $data['title'] = $data['campaign_name'] ." - Campaign Details";

        $data['title'] = _l('Edit') . ' ' . _l('Campaign');

        $data['clientGroups'] = $this->client_groups_model->get_groups();
        $data['lead_statuses'] = $this->leads_model->get_status();
        $data['lead_sources'] = $this->leads_model->get_source();
        $data['staff_members'] = $this->staff_model->get('', ['is_not_staff' => 0, 'active' => 1]);
        
        $this->load->view('edit', $data);
    }

    public function history()
    {
        if (!has_permission('email_newsletter', '', 'view')) {
            access_denied('email_newsletter');
        }

        $data = [];

        $data['title'] = _l('email_newsletter') . ' - ' . _l('email_newsletter_newsletter_history');

        if ($this->input->is_ajax_request()) {
            $this->app->get_table_data(module_views_path('email_newsletter', 'table'));
        }

        $this->load->view('manage', $data);
    }

    public function view_newsletter($id)
    {
        if (!has_permission('email_newsletter', '', 'view')) {
            access_denied('email_newsletter');
        }

        $data = [];

        $data['title'] = _l('email_newsletter') . ' - ' . _l('email_newsletter_newsletter_history');

        $data['newsletterData'] = $this->email_newsletter_model->get($id);

        $this->load->view('view', $data);
    }

    // send via sendpulse and CRM
    /*public function sendEmails()
    {
        if (!has_permission('email_newsletter', '', 'create')) {
            access_denied('email_newsletter');
        }

        $customerStatus = $this->input->post('customers_status', true);
        $customerGroups = $this->input->post('customer_groups', true);
        $customerCountries = $this->input->post('customers_country', true);

        $leadGroups = $this->input->post('lead_groups', true);
        $leadSources = $this->input->post('leads_source', true);
        $leadAssignedToStaff = $this->input->post('leads_assigned_to_staff', true);
        $leadCountries = $this->input->post('leads_country', true);

       /* $emailSubject = $this->input->post('email_subject');
        $emailContent = $this->input->post('email_content');*/

    //     $leadsEmails = [];
    //     $customerEmails = [];

    //     if (in_array('leads', $sendNewsletterTo)) {
    //         $leadsEmails = $this->email_newsletter_model->searchLeadsEmails($leadSources, $leadAssignedToStaff, $leadCountries, $leadGroups);
    //     }
    //     if (in_array('customers', $sendNewsletterTo)) {
    //         $customerEmails = $this->email_newsletter_model->searchCustomersEmails($customerStatus, $customerGroups, $customerCountries);
    //     }

    //     $usersToSendMail = array_merge(
    //         $leadsEmails,
    //         $customerEmails
    //     );
    //     $usersToSendMail = array_filter($usersToSendMail, 'strlen');
    //     $usersToSendMail = array_unique($usersToSendMail);


    //     /*if (empty($emailSubject)) {
    //         set_alert('danger', _l('email_newsletter_please_enter_email_subject'));
    //         redirect(admin_url('email_newsletter/manage'));
    //     }

    //     if (empty($emailContent)) {
    //         set_alert('danger', _l('email_newsletter_please_enter_email_content'));
    //         redirect(admin_url('email_newsletter/manage'));
    //     }*/

    //     if (count($usersToSendMail) === 0 || empty($usersToSendMail)) {
    //         set_alert('danger', _l('email_newsletter_no_emails_found'));
    //         redirect(admin_url('email_newsletter/manage'));
    //     }

    //     $totalEmailsSent = 0;
    //     $totalEmailsFailed = 0;
    //     $emailsToSent = 0;

    //     $this->load->model('emails_model');

    //     foreach ($usersToSendMail as $email) {

    //         ++$emailsToSent;

    //         if ($this->emails_model->send_email_template('bulk-emails-to-client', $email, [])) {

    //             ++$totalEmailsSent;
    //             log_activity('Newsletter Sent [To : ' . $email . ']');

    //         } else {

    //             ++$totalEmailsFailed;
    //             log_activity('Newsletter Failed [To : ' . $email . ']');

    //         }

    //     }

    //     if (($totalEmailsFailed + $totalEmailsSent) === $emailsToSent) {

    //         $this->email_newsletter_model->add([
    //             'sent_by' => get_staff_user_id(),
    //             'email_subject' => 'BXFactory Bulk EMails',
    //             'email_content' => '',
    //             'total_emails_to_send' => $emailsToSent,
    //             'emails_sent' => $totalEmailsSent,
    //             'email_list' => json_encode($usersToSendMail),
    //             'emails_failed' => $totalEmailsFailed,
    //             'created_at' => date('Y-m-d H:i:s'),
    //         ]);

    //         log_activity('Newsletter Sent [Newsletter Subject - ' . $emailSubject . ' - Total Emails: ' . $emailsToSent . ' - Total Emails Sent: ' . $totalEmailsSent . ' - Total Failed Emails: ' . $totalEmailsFailed . ']');

    //         set_alert('success', _l('email_newsletter_newsletter_sent_successfully') . ' ' . _l('email_newsletter_mails_sent') . ' - ' . $totalEmailsSent . ' ' . _l('email_newsletter_mails_failed') . ' -' . $totalEmailsFailed);
    //         redirect(admin_url('email_newsletter/history'));

    //     }

    // }

    // send via mailtrap
    public function sendEmails()
    {
        if (!has_permission('email_newsletter', '', 'create')) {
            access_denied('email_newsletter');
        }
        
        $response_array = array('error' => false, 'message' => '');

        $campaignName = $this->input->post('campaign_name', true);
        $customerStatus = $this->input->post('customers_status', true);
        $customerGroups = $this->input->post('customer_groups', true);
        $customerCountries = $this->input->post('customers_country', true);

        $emailSubject = $this->input->post('email_subject');
        $emailContent = $this->input->post('email_content');

        $customerEmails = $this->email_newsletter_model->searchCustomersEmails($customerStatus, $customerGroups, $customerCountries);
        if (count($customerEmails) === 0 || empty($customerEmails)) {
            $response_array['message'] = _l('email_newsletter_no_emails_found');
            $response_array['error'] = true;
            echo json_encode($response_array);die;
        }

        $mailtrap_emails_list = array_chunk($customerEmails, 1000);
        $email_data = email_newsletter_send_email_template('bulk-emails-to-client');
        
        $endpoint = "https://send.api.mailtrap.io/api/send";
        $api_token = get_option('email_newsletter_api_token');
        $headers = array("Api-Token: $api_token");

        foreach ($mailtrap_emails_list as $toEmails)
        {
            $post_data = [
                'from' => [
                    'name' => $email_data['fromname'],
                    'email' => $email_data['fromemail']
                ],
                'to' => $toEmails,
                'subject' => $email_data['template']->subject,
                'html' => $email_data['template']->message,
            ];
            
            $response = email_newsletter_curl_request($endpoint, $headers, $post_data);
            $response = json_decode($response, true);
            
            if(!empty($response['success']) && $response['success'] == true)
            {
                $message_ids = $response['message_ids'];
                
                /* create campaign first */
                $campaign_id = $this->email_newsletter_model->add([
                    'campaign_name' => $campaignName,
                    'campaign_type' => 'Email',
                    'customer_status' => $customerStatus,
                    'customer_groups' => $customerGroups,
                    'sent_by' => get_staff_user_id(),
                    'email_subject' => 'BXFactory Bulk Emails',
                    'email_content' => '',
                    'total_emails_to_send' => count($toEmails),
                    'emails_sent' => 0,
                    'email_list' => json_encode($toEmails),
                    'emails_failed' => 0,
                    'created_at' => date('Y-m-d H:i:s'),
                ]);
                /* create campaign first */
                
                $data = [];
                foreach ($message_ids as $messageId) {
                    $data[] = array('message_id' => $messageId, 'campaign_id' => $campaign_id);
                }
                
                $this->db->insert_batch('tblemail_newsletter_campaign_details', $data);
                
                $response_array['message'] = 'Bulk emails sent successfully';
                $response_array['error'] = false;
                echo json_encode($response_array);
            }
            else
            {
                $errors = $response['errors'];
                $errors_string = '';
                foreach ($errors as $error)
                {
                    $errors_string .= $error. '<br />';
                }
                $response_array['message'] = $errors_string;
                $response_array['error'] = true;
                echo json_encode($response_array);
            }
        }
    }

    public function totalEmailsFound()
    {
        if (!has_permission('email_newsletter', '', 'create')) {
            access_denied('email_newsletter');
        }

        $customerStatus = $this->input->post('customers_status', true);
        $customerGroups = $this->input->post('customer_groups', true);
        $customerCountries = $this->input->post('customers_country', true);

        $leadGroups = $this->input->post('lead_groups', true);
        $leadSources = $this->input->post('leads_source', true);
        $leadAssignedToStaff = $this->input->post('leads_assigned_to_staff', true);
        $leadCountries = $this->input->post('leads_country', true);

        // $totalLeads = $this->email_newsletter_model->searchLeadsEmails($leadSources, $leadAssignedToStaff, $leadCountries, $leadGroups);
        $totalCustomers = $this->email_newsletter_model->searchCustomersEmails($customerStatus, $customerGroups, $customerCountries);

        echo json_encode([
            'total_leads' => count([]),
            'total_customers' => count($totalCustomers)
        ]);
        die;
    }

    
    public function sendSms()
    {
        if (!has_permission('email_newsletter', '', 'create')) {
            access_denied('email_newsletter');
        }
        
        $response_array = array('error' => false, 'message' => '');

        $sms_body = $this->input->post('sms_body', true);
        $customerStatus = $this->input->post('customers_status', true);
        $customerGroups = $this->input->post('customer_groups', true);
        $customerCountries = $this->input->post('customers_country', true);

        $phonesList = $this->email_newsletter_model->searchCustomersPhoneNumbers($customerStatus, $customerGroups, $customerCountries);
      
        if (count($phonesList) === 0 || empty($phonesList)) {
            $response_array['message'] = _l('No phone numbers found');
            $response_array['error'] = true;
            
            echo json_encode($response_array);die;
        }

        /* send sms */
        
        $sms_from = get_option('email_newsletter_sendpulse_sms_from');
        $sid = get_option('email_newsletter_sendpulse_id');
        $token = get_option('email_newsletter_sendpulse_secret');
        $client = new ApiClient($sid, $token);

        if(!empty($phonesList))
        {
            try {
                $response = $client->post('sms/send', [
                    "sender" => 'BXFactory',
                    "phones" => $phonesList,
                    "body" => strip_tags($sms_body),
                    "emulate" => false
                ]);
                
                if($response['result'] == true)
                {
                    $totalSmsSent = $response['counters']['sends'];
                    $totalSmsNotSent = $response['counters']['exceptions'];
                    
                    $this->email_newsletter_model->add([
                        'sent_by' => get_staff_user_id(),
                        'email_subject' => 'BXFactory Bulk SMS',
                        'campaign_type' => 'SMS',
                        'customer_status' => $customerStatus,
                        'customer_groups' => $customerGroups,
                        'sms_body' => $sms_body,
                        'email_content' => '',
                        'total_emails_to_send' => count($phonesList),
                        'emails_sent' => $totalSmsSent,
                        'email_list' => json_encode($phonesList),
                        'emails_failed' => $totalSmsNotSent,
                        'sendpulse_campaign_id' => $response['campaign_id'],
                        'created_at' => date('Y-m-d H:i:s'),
                    ]);
        
                    log_activity('SMS Sent [SMS Campagin - Total SMS: ' . count($phonesList) . ' - Total SMS Sent: ' . $totalSmsSent . ' - Total Failed SMS: ' . $totalSmsNotSent . ']');
        
                    $response_array['message'] = _l('All sms sent successfully');
                    $response_array['error'] = false;
                    echo json_encode($response_array);die;
                }
                else
                {
                    $response_array['message'] = _l('Error sending messages');
                    $response_array['error'] = true;
                    echo json_encode($response_array);die;
                }
            
            } catch (ApiClientException $e) {
                $response_array['message'] = $e->getResponse()['message'];
                $response_array['error'] = false;
                echo json_encode($response_array);die;
            }
        }
        
        /* send sms */
    }
    
    public function campaign_details($campaign_id = 0)
    {
        $campaign = $this->db->where('id', $campaign_id)->get('tblemail_newsletter_newsletter_history')->row_array();
        if(!$campaign)
            show_404();
            
        $data = [];

        $data['title'] = $campaign['campaign_name'] ." - Campaign Details";
        $data['campaign_id'] = $campaign_id;

        $this->load->view('campaign_details_manage', $data);
    }

    public function campaign_details_table($campaign_id = 0)
    {
        if ($this->input->is_ajax_request()) {
            $this->app->get_table_data(module_views_path('email_newsletter', 'campaign_details_table'));
        }
    }
}
