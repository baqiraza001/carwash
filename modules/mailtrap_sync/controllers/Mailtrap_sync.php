<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Mailtrap_sync extends AdminController {
    public function __construct() {
        set_time_limit(0);
        ini_set('max_execution_time', 0);
        parent::__construct();
        $this->load->library('mailtrap_library');
    }

    /**
     * Settings page
     */
    public function index() {
    }

    public function sync_single_contact($contact_id = 0)
    {
        $CI = &get_instance();
        $CI->db->select('c.email, c.firstname, c.lastname, g.name as group_name');
        $CI->db->from(db_prefix() . 'contacts c');
        $CI->db->join(db_prefix() . 'clients cu', 'cu.userid = c.userid', 'left');
        $CI->db->join(db_prefix() . 'customer_groups cg', 'cg.customer_id = cu.userid', 'left');
        $CI->db->join(db_prefix() . 'customers_groups g', 'g.id = cg.groupid', 'left');
        $CI->db->where('c.id', $contact_id);
        $CI->db->where('c.active', 1);
        $contact = $CI->db->get()->row();

        if ($contact) {
            $data = [[
                'email' => $contact->email,
                'name'  => trim($contact->firstname . ' ' . $contact->lastname),
                'group' => $contact->group_name ?: 'default'
            ]];

            $this->send_to_mailtrap($data);
            update_option('mailtrap_sync_at_datetime', date('Y-m-d H:i:s'));
        }
    }

    public function send_to_mailtrap($contacts)
    {
        $api_token  = get_option('mailtrap_sync_api_key');
        $account_id = get_option('mailtrap_sync_account_id');
        $list_id    = get_option('mailtrap_sync_list_id');

        $url = "https://mailtrap.io/api/accounts/{$account_id}/contacts";

        $results = [];

        foreach ($contacts as $c) {
            $payload = [
                'contact' => [
                    'email'         => $c['email'],
                    'fields' => [
                        'first_name'          => $c['first_name'],
                        'last_name'          => $c['last_name'],
                        'group' => $c['group']
                    ],
                    'list_ids'      => [$list_id],
                ]
            ];

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
        $CI->db->select('email');
        $CI->db->from(db_prefix() . 'contacts');
        $CI->db->where('active', 0);
        $CI->db->where('email IS NOT NULL', null, false);
        $inactive_contacts = $CI->db->get()->result_array();

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
        $CI->db->select('email');
        $CI->db->from(db_prefix() . 'contacts');
        $CI->db->where('id', $contact_id);
        $contact = $CI->db->get()->row_array();
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
        $this->mailtrap_library->bulk_sync_contacts();
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

    public function test()
    {
        $api_token  = get_option('mailtrap_sync_api_key');
        $account_id = get_option('mailtrap_sync_account_id');

        $url = "https://mailtrap.io/api/accounts/{$account_id}/contacts";

        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_TIMEOUT => 10,
            CURLOPT_HTTPHEADER => [
                'Accept: application/json',
                "Api-Token: {$api_token}"
            ],
        ]);
        $response = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        var_dump($http_code, $response);
    }


}