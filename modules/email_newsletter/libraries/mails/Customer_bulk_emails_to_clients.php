<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Customer_bulk_emails_to_clients extends App_mail_template
{

    protected $email_data;

    public $slug = 'bulk-email-to-client';

    public function __construct($email_data)
    {
        parent::__construct();
        
        $this->data = $email_data;
        $this->set_merge_fields('client_merge_fields', $this->data);
    }

    public function build()
    {
        $this->to($this->data->client_email);
    }
}
