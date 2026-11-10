<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Email_newsletter_model extends App_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function searchLeadsEmails($leadSource=[], $assignedStaff=[], $leadCountry=[],$leadGroups=[])
    {

        $this->db->from('tblleads');

        if (!empty($leadSource)) {
            $this->db->where_in('source', $leadSource);
        }

        if (!empty($assignedStaff)) {
            $this->db->where_in('assigned', $assignedStaff);
        }

        if (!empty($leadCountry)) {
            $this->db->where_in('country', $leadCountry);
        }

        if (!empty($leadGroups)) {
            $this->db->where_in('status', $leadGroups);
        }

        $query = $this->db->get();

        $emails = array_column($query->result_array(), 'email');
        $emails = array_filter($emails, 'strlen');

        return array_unique($emails);
    }

    public function searchCustomersEmails($customerStatus = 'active', $customerGroups=[], $customerCountries=[])
    {

        $status = '1';

        if ($customerStatus == 'inactive') {
            $status = '0';
        }

        $this->db->select('CONCAT(ct.firstname, " ", ct.lastname) AS name, ct.email')
            ->from('tblclients c')
            ->join('tblcontacts ct', 'c.userid = ct.userid')
            ->join('tblcustomer_groups cg', 'c.userid = cg.customer_id');

        if (!empty($customerStatus)) {
            $this->db->where('c.active', $status);
        }

        if (!empty($customerGroups)) {
            $this->db->where_in('cg.groupid', $customerGroups);
        }

        if (!empty($customerCountries)) {
            $this->db->where_in('c.country', $customerCountries);
        }

        $emails = $this->db->get()->result_array();

        $uniqueEmails = [];
    
        foreach ($emails as $key => $emailData) {
            $email = $emailData['email'];
            // Filter valid email addresses
             if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                // Add to unique emails list if not already present
                if (!in_array($email, $uniqueEmails)) {
                    $uniqueEmails[] = $email;
                } else {
                    // Remove duplicate emails
                    unset($emails[$key]);
                }
            } else {
                // Remove invalid emails
                unset($emails[$key]);
            }
        }
        return $emails;
    }
    
    /* phonenumbers */
    
    // Custom function to filter phone numbers
    private function filterPhoneNumbers($phoneNumber) {
        
         // Check if phone number is empty
        if (empty($phoneNumber)) {
            return false;
        }
        
        // Remove spaces from the phone number
        $phoneNumber = str_replace(' ', '', $phoneNumber);
        
        // If '+' is not at the start, add it
        if (substr($phoneNumber, 0, 1) !== '+') {
            $phoneNumber = '+' . $phoneNumber;
        }
        
        // Return true if the phone number has a length greater than zero after removing spaces
        return strlen($phoneNumber) > 0;
    }
    
    public function searchCustomersPhoneNumbers($customerStatus = 'active', $customerGroups=[], $customerCountries=[])
    {
        $status = '1';

        if ($customerStatus == 'inactive') {
            $status = '0';
        }

        $this->db->select('ct.phonenumber')
            ->from('tblclients c')
            ->join('tblcontacts ct', 'c.userid = ct.userid')
            ->join('tblcustomer_groups cg', 'c.userid = cg.customer_id');

        if (!empty($customerStatus)) {
            $this->db->where('c.active', $status);
        }

        if (!empty($customerGroups)) {
            $this->db->where_in('cg.groupid', $customerGroups);
        }

        if (!empty($customerCountries)) {
            $this->db->where_in('c.country', $customerCountries);
        }

        $query = $this->db->get();
        $phones = array_column($query->result_array(), 'phonenumber');
        
        $phonesList = [];

        foreach ($phones as $phoneNumber) {
            if (!empty($phoneNumber)) {
                $phoneNumber = str_replace(' ', '', $phoneNumber);
        
                // If '+' is not at the start, add it
                if (substr($phoneNumber, 0, 1) !== '+') {
                    $phoneNumber = '+' . $phoneNumber;
                }
        
                // If '33' is not after '+', add it
                if (substr($phoneNumber, 1, 2) !== '33') {
                    $phoneNumber = substr_replace($phoneNumber, '33', 1, 0);
                }
        
                // Add the processed phone number to the list if it has a length greater than zero
                if (strlen($phoneNumber) > 0) {
                    $phonesList[] = $phoneNumber;
                }
            }
        }

        
        // $phones = array_column($query->result_array(), 'phonenumber');
        // $phones = array_filter($phones, array($this, 'filterPhoneNumbers'));

        return array_unique($phonesList);
    }
    
    /* phonenumbers */
    
    
    public function add($data)
    {
        $this->db->insert(db_prefix() . 'email_newsletter_newsletter_history', $data);
        $insert_id = $this->db->insert_id();

        if ($insert_id) {
            return $insert_id;
        }

        return false;
    }

    public function get($id)
    {
        $this->db->where('id', $id);
        return $this->db->get(db_prefix() . 'email_newsletter_newsletter_history')->row();
    }

    public function delete($id)
    {
        $this->db->where('id', $id);
        $this->db->delete(db_prefix() . 'email_newsletter_newsletter_history');

        if ($this->db->affected_rows() > 0) {
            return true;
        }

        return false;
    }
}
