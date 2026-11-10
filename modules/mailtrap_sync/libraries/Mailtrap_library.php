<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Mailtrap_library {
    
    private $CI;
    public function __construct() {
        set_time_limit(0);
        ini_set('max_execution_time', 0);
        
        $this->CI =& get_instance();
    }

    public function sync_single_contact($contact_id = 0)
    {
        $this->CI->db->select('c.email, c.firstname, c.lastname, g.name as group_name, company');
        $this->CI->db->from(db_prefix() . 'contacts c');
        $this->CI->db->join(db_prefix() . 'clients cu', 'cu.userid = c.userid', 'left');
        $this->CI->db->join(db_prefix() . 'customer_groups cg', 'cg.customer_id = cu.userid', 'left');
        $this->CI->db->join(db_prefix() . 'customers_groups g', 'g.id = cg.groupid', 'left');
        $this->CI->db->where('c.id', $contact_id);
        $this->CI->db->where('c.active', 1);
        $this->CI->db->where('c.is_primary', 1);
        $contact = $this->CI->db->get()->row_array();

        if (!empty($contact)) {
            $this->send_to_mailtrap([$contact]);
            update_option('mailtrap_sync_at_datetime', date('Y-m-d H:i:s'));
        }
    }

    public function send_to_mailtrap($contacts)
    {
        $api_token  = get_option('mailtrap_sync_api_key');
        $account_id = get_option('mailtrap_sync_account_id');
        $list_id    = get_option('mailtrap_sync_list_id');

        $results = [];

        foreach ($contacts as $c) {
            $payload = [
                'contact' => [
                    'email'         => $c['email'],
                    'fields' => [
                        'first_name'          => $c['firstname'],
                        'last_name'          => $c['lastname'],
                        'group' => $c['group_name'],
                        'company'          => $c['company'],
                    ],
                    'list_ids_included'      => [$list_id],
                ]
            ];

            $url = "https://mailtrap.io/api/accounts/{$account_id}/contacts/" . urlencode($c['email']);

            $ch = curl_init();
            curl_setopt_array($ch, [
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_CUSTOMREQUEST => "PUT",
                CURLOPT_POSTFIELDS => json_encode($payload),
                CURLOPT_HTTPHEADER => [
                    "Accept: application/json",
                    "Api-Token: {$api_token}",
                    "Content-Type: application/json"
                ],
            ]);

            $response = curl_exec($ch);
            $error = curl_error($ch);
            curl_close($ch);

            $output_response = [
                'success' => false,
                'message' => 'Error in sync. Try again.', 
                'data' => ''
            ];

            if (!$error){
                $decoded = json_decode($response, true);
                $decoded_error = isset($decoded['errors']);
                $output_response['success'] = $decoded_error ? false : true;
                $output_response['data'] = $decoded_error ? $decoded : '';
                if(!$decoded_error)
                    $output_response['message'] = 'Sync completed successfully';
            }
        }

        return $output_response;
    }

    public function delete_inactive_contacts_from_mailtrap()
    {
        $api_token  = get_option('mailtrap_sync_api_key');
        $account_id = get_option('mailtrap_sync_account_id');

        $CI =& get_instance();
        $this->CI->db->select('email');
        $this->CI->db->from(db_prefix() . 'contacts');
        $this->CI->db->where('active', 0);
        $this->CI->db->where('email IS NOT NULL', null, false);
        $inactive_contacts = $this->CI->db->get()->result_array();

        foreach ($inactive_contacts as $c) {
            $email = trim($c['email']);
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) continue;

            $url = "https://mailtrap.io/api/accounts/{$account_id}/contacts/" . urlencode($email);

            $ch = curl_init($url);
            curl_setopt_array($ch, [
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_TIMEOUT => 10,
                CURLOPT_CUSTOMREQUEST => "DELETE",
                CURLOPT_HTTPHEADER => [
                    "Accept: application/json",
                    "Api-Token: {$api_token}"
                ],
            ]);
            curl_exec($ch);
            curl_close($ch);
        }

        return true;
    }


    public function delete_contact_from_mailtrap($contact_id = 0)
    {
        $api_token  = get_option('mailtrap_sync_api_key');
        $account_id = get_option('mailtrap_sync_account_id');

        $CI =& get_instance();
        $this->CI->db->select('email');
        $this->CI->db->from(db_prefix() . 'contacts');
        $this->CI->db->where('id', $contact_id);
        $contact = $this->CI->db->get()->row_array();
        if(empty($contact)) return true;

        $email = trim($contact['email']);

        $url = "https://mailtrap.io/api/accounts/{$account_id}/contacts/" . urlencode($email);

        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 10,
            CURLOPT_CUSTOMREQUEST => "DELETE",
            CURLOPT_HTTPHEADER => [
                "Accept: application/json",
                "Api-Token: {$api_token}"
            ],
        ]);
        curl_exec($ch);
        curl_close($ch);

        return true;
    }


    public function bulk_sync_contacts()
    {
        $CI =& get_instance();

        // Step 1: Get existing Mailtrap emails
        // $mailtrap_emails = $this->get_all_mailtrap_emails();

        // Step 2: Get only local active contacts not in Mailtrap
        $this->CI->db->select('c.email, c.firstname, c.lastname, g.name as group_name, company');
        $this->CI->db->from(db_prefix() . 'contacts c');
        $this->CI->db->join(db_prefix() . 'clients cu', 'cu.userid = c.userid', 'left');
        $this->CI->db->join(db_prefix() . 'customer_groups cg', 'cg.customer_id = cu.userid', 'left');
        $this->CI->db->join(db_prefix() . 'customers_groups g', 'g.id = cg.groupid', 'left');
        $this->CI->db->where('c.active', 1);
        $this->CI->db->where('c.is_primary', 1);
        // if (!empty($mailtrap_emails)) {
        //     $this->CI->db->where_not_in('c.email', $mailtrap_emails);
        // }
        $contacts = $this->CI->db->get()->result_array();

        // Step 4: Sync to Mailtrap
        $result = $this->send_to_mailtrap($contacts);
        update_option('mailtrap_sync_at_datetime', date('Y-m-d H:i:s'));
        echo json_encode($result);
    }



    private function get_all_mailtrap_emails()
    {
        $api_token  = get_option('mailtrap_sync_api_key');
        $account_id = get_option('mailtrap_sync_account_id');
        $list_id    = get_option('mailtrap_sync_list_id');

        $emails = [];
        $page = 1;

        do {
            $url = "https://mailtrap.io/api/accounts/{$account_id}/contacts?list_id={$list_id}&page={$page}&per_page=100";

            $ch = curl_init($url);
            curl_setopt_array($ch, [
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_TIMEOUT => 10,
                CURLOPT_HTTPHEADER => [
                    "Accept: application/json",
                    "Api-Token: {$api_token}"
                ],
            ]);

            $response = curl_exec($ch);
            curl_close($ch);

            $data = json_decode($response, true);
            if (isset($data['data'])) {
                foreach ($data['data'] as $contact) {
                    if (!empty($contact['email'])) {
                        $emails[] = $contact['email'];
                    }
                }
                $has_more = count($data['data']) === 100;
                $page++;
            } else {
                $has_more = false;
            }

        } while ($has_more);

        return $emails;
    }

}