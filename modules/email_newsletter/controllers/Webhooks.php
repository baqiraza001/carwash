<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Webhooks extends ClientsController
{   
    public function __construct()
    {
        parent::__construct();
    }
    
    public function index(){
        /*$endpoint = "https://mailtrap.io/api/accounts/60320/inboxes";
        $api_token = "8dfbdfcefd716da1ad3b10498813ce32";
        $headers = array("Api-Token: $api_token");
        $response = email_newsletter_curl_request($endpoint, $headers, [], "GET");
        $decoded_response = json_decode($response);
        echo '<pre>';var_dump($decoded_response);*/
        
    }
    
    public function mailtrap(){
    
        header('Content-Type: application/json');
        
        $request = file_get_contents('php://input');
        $response_array = json_decode($request, true);
        
        $event_columns_array = array(
            'delivery' => 'delivered',
            'open' => 'opened',
            'click' => 'clicked',
            'bounce' => 'bounced',
            'reject' => 'rejected',
            'unsubscribe' => 'unsubscribed',
        );

        if ($response_array !== null) {
            $events = $response_array['events'];

            foreach ($events as $event) {
                $eventName = $event['event'];
                $message_id = $event['message_id'];
                $email = $event['email'];
                $client = email_newsletter_get_client_by_email($email);
                $client_id = !empty($client) ? $client['userid'] : 0;
                
                $data['client_id'] = $client_id;
                $data['email'] = $email;
                $event_columns_name = $event_columns_array[$eventName];
                $data[$event_columns_name] = 1;
                if($event_columns_name == 'delivered')
                    $data['created_at'] = date('Y-m-d H:i:s', $event['timestamp']);
                if(!empty($event['reason']))
                    $data['reason'] = $event['reason'];
                if(!empty($event['response']))
                    $data['reason'] = $event['response'];

                // Update table
                $exists = $this->db->select('id')->where('message_id', $message_id)->get('tblemail_newsletter_campaign_details')->row_array();
                if(!empty($exists))
                    $this->db->where('message_id', $message_id)->update('tblemail_newsletter_campaign_details', $data);
                else
                {
                    $data['message_id'] = $message_id;
                    $this->db->insert('tblemail_newsletter_campaign_details', $data);
                }
            }
        }
    }
}
?>