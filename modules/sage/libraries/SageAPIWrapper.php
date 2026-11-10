<?php

class SageAPIWrapper {

    private $ci;
    public function __construct() {

        $this->authorizationRequestUrl = 'https://www.sageone.com/oauth2/auth/central?filter=apiv3.1';
        $this->exchangeAuthorizationCodeForAccessTokenUrl = 'https://oauth.accounting.sage.com/token';
        $this->contact_endpoint = 'https://api.accounting.sage.com/v3.1/contacts';
        $this->sales_invoices_endpoint = 'https://api.accounting.sage.com/v3.1/sales_invoices';
        $this->contact_payments_endpoint = 'https://api.accounting.sage.com/v3.1/contact_payments';
        $this->access_token_expires_in = 300; // 5 minutes
        $this->refresh_token_expires_in = 2678400; // 31 days

        $this->ci = &get_instance();
    }

    public function setClientId($client_id){
        $this->client_id = $client_id;
    }

    public function getClientId(){
        return $this->client_id;
    }

    public function setClientsecret($client_secret){
        $this->client_secret = $client_secret;
    }

    public function getClientsecret(){
        return $this->client_secret;
    }

    public function setRedirectUri($redirect_uri){
        $this->redirect_uri = $redirect_uri;
    }

    public function getRedirectUri(){
        return $this->redirect_uri;
    }

    public function setAccessToken($access_token){
        $this->accessToken = $access_token;
    }

    public function getAccessToken(){
        return $this->accessToken;
    }

    public function setRefreshToken($refresh_token){
        $this->refreshToken = $refresh_token;
    }

    public function getRefreshToken(){
        return $this->refreshToken;
    }

    public function setRmd($rmd){
        $this->rmd = $rmd;
    }

    public function getRmd(){
        return $this->rmd;
    }

    public function updateAccessAndRefreshToken($access_token, $refresh_token){
        $this->ci->db->update(db_prefix().'sage', [
            'access_token'         => $access_token,
            'refresh_token'        => $refresh_token,
            'rcd'                  => date('Y-m-d h:i:s'),
            'rmd'                  => date('Y-m-d h:i:s')
        ]);
    }

    public function getSageConfiguration(){
        $configuration = $this->ci->db->get(db_prefix() . 'sage')->row();
        if(isset($configuration)) {
            $this->setClientId($configuration->client_id);
            $this->setClientsecret($configuration->client_secret);
            $this->setRedirectUri($configuration->redirect_uri);
            $this->setAccessToken($configuration->access_token);
            $this->setRefreshToken($configuration->refresh_token);
            $this->setRmd($configuration->rmd);
        }
        return true;
    }

    public function createSageContact($contact_data){
        $this->getSageConfiguration();
        $current_time = strtotime(date('Y-m-d h:i:s'));
        $savedRmd = strtotime($this->getRmd());

        $difference_in_seconds = $current_time - $savedRmd;

        if($difference_in_seconds >= $this->refresh_token_expires_in){
            $location = admin_url('sage/sageAuthorization');
            header('Location: ' . $location);
        } else if($difference_in_seconds >= $this->access_token_expires_in){
            $postData = "client_id=".$this->getClientId()."&client_secret=".$this->getClientsecret()."&grant_type=refresh_token&refresh_token=".$this->getRefreshToken();

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL,$this->exchangeAuthorizationCodeForAccessTokenUrl);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);

            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

            $server_output = curl_exec($ch);

            curl_close ($ch);
            $parsed_result = json_decode($server_output, true);
            $_SESSION['access_token'] = $parsed_result['access_token'];
            $_SESSION['refresh_token'] = $parsed_result['refresh_token'];
            $this->updateAccessAndRefreshToken($parsed_result['access_token'], $parsed_result['refresh_token']);
        }

        extract($contact_data);
        $headers = array(
            'Accept: application/json',
            'Content-Type: application/json',
            'Authorization: Bearer '.$this->getAccessToken()
            );
            $data = '
                {
                  "contact": {
                    "name": "'.$name.'",
                    "contact_type_ids": [
                      "CUSTOMER"
                    ],
                    "main_contact_person": {
                        "name": "'.$name.'",
                        "email": "'.$email_address.'",
                        "telephone": "'.$phone_number.'"
                    }
                  }
                }';

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL,            $this->contact_endpoint );
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1 );
        curl_setopt($ch, CURLOPT_POST,           1 );
        curl_setopt($ch, CURLOPT_POSTFIELDS,     $data );
        curl_setopt($ch, CURLOPT_HTTPHEADER,     $headers);

        $result=curl_exec ($ch);
        $parsed_result = json_decode($result, true);

        return $parsed_result['id'];
    }


    public function createSageInvoice($invoice_data){
        $this->getSageConfiguration();
        $current_time = strtotime(date('Y-m-d h:i:s'));
        $savedRmd = strtotime($this->getRmd());

        $difference_in_seconds = $current_time - $savedRmd;

        if($difference_in_seconds >= $this->refresh_token_expires_in){
            $location = admin_url('sage/sageAuthorization');
            header('Location: ' . $location);
        } else if($difference_in_seconds >= $this->access_token_expires_in){
            $postData = "client_id=".$this->getClientId()."&client_secret=".$this->getClientsecret()."&grant_type=refresh_token&refresh_token=".$this->getRefreshToken();

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL,$this->exchangeAuthorizationCodeForAccessTokenUrl);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);

            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

            $server_output = curl_exec($ch);

            curl_close ($ch);
            $parsed_result = json_decode($server_output, true);
            $_SESSION['access_token'] = $parsed_result['access_token'];
            $_SESSION['refresh_token'] = $parsed_result['refresh_token'];
            $this->updateAccessAndRefreshToken($parsed_result['access_token'], $parsed_result['refresh_token']);
        }
        extract($invoice_data);
        $headers = array(
            'Accept: application/json',
            'Content-Type: application/json',
            'Authorization: Bearer '.$this->getAccessToken()
        );
        $data = '{
              "sales_invoice": {
                "contact_id": "'.$contact_id.'",
                "date": "'.$date.'",
                "due_date": "'.$due_date.'",
                "invoice_lines": [';
                  for ($i = 0; $i < sizeof($invoice_data); $i++){
                      if(is_array($invoice_data[$i])){
                          $description  = $invoice_data[$i]['Description'];
                          $quantity  = $invoice_data[$i]['Quantity'];
                          $unitAmount  = $invoice_data[$i]['UnitAmount'];
                          $data .= '{
                            "description": "'.$description.'",
                            "ledger_account_id": "31e40b4c0df611eb867f0ee73a5c6c6b",
                            "quantity": '.$quantity.',
                            "unit_price": '.$unitAmount.',
                            "tax_rate_id": "3b238b2103de46c6aacadc2fe16c455c"  
                          },';
                      }else{
                          continue;
                      }
                  }
            $data = rtrim($data, ",");

        $data .= ']
              }
           }';

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL,            $this->sales_invoices_endpoint );
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1 );
        curl_setopt($ch, CURLOPT_POST,           1 );
        curl_setopt($ch, CURLOPT_POSTFIELDS,     $data );
        curl_setopt($ch, CURLOPT_HTTPHEADER,     $headers);

        $result=curl_exec ($ch);
        $parsed_result = json_decode($result, true);
        return $parsed_result['id'];

    }

    public function getAuthorizationRequestCode(){
        $configuration_found = $this->getSageConfiguration();
        if($configuration_found){
            $client_id = $this->getClientId();
            $redirect_uri = $this->getRedirectUri();

            $authorizationRequestUrl = $this->authorizationRequestUrl."&response_type=code&client_id=".$client_id."&redirect_uri=".$redirect_uri."&scope=full_access&state=random_string";
            header('Location: ' . $authorizationRequestUrl);
        }
    }

    public function createInvoice($id){

        if (!class_exists('clients_model')) {
            $this->ci->load->model('clients_model');
        }
        if (!class_exists('invoices_model')) {
            $this->ci->load->model('invoices_model');
        }
        $invoice = $this->ci->invoices_model->get($id);
        $invoice_array = json_decode(json_encode($invoice), true);

        $insert_id = $invoice->id;

        $contact_data = array();
        $invoice_data = array();

        $contact = $this->ci->clients_model->get_contacts($invoice->clientid);
        $invoice_data['date'] = $invoice->date;
        $invoice_data['due_date'] = $invoice->duedate;
        foreach ($invoice->items as $key => $value){
            $invoice_data[$key]['Description'] = $value['description'];
            $invoice_data[$key]['Quantity'] = $value['qty'];
            $invoice_data[$key]['UnitAmount'] = $value['rate'];
        }
        if(isset($contact)){
            $contact_data['name'] = $contact[0]['firstname']." ". $contact[0]['lastname'];
            $contact_data['email_address'] = $contact[0]['email'];
            $contact_data['phone_number'] = $contact[0]['phonenumber'];
            $contact_id = $contact[0]['id'];
        }
        if($contact[0]['sage_contactid'] != ""){
            $sage_contact_id = $contact[0]['sage_contactid'];
        }else{
            $sage_contact_id = $this->createSageContact($contact_data);
            $data = array();
            $data['sage_contactid'] = $sage_contact_id;

            $this->ci->clients_model->update_contact($data, $contact_id);
        }

        $invoice_data['contact_id'] = $sage_contact_id;

        $sage_invoice_id = $this->createSageInvoice($invoice_data);
        return array('perfex_invoice_id' => $insert_id, 'sage_invoice_id' => $sage_invoice_id, 'data' => $invoice_array);
        exit();
    }

    public function updateSageInvoiceWithId($id, $sage_invoice_id, $data1) {
        if (!class_exists('invoices_model')) {
            $this->ci->load->model('invoices_model');
        }
        $data2 = array();

        $data2['sage_invoiceid'] = $sage_invoice_id;
        $data2['number'] = $data1['number'];
        $data2['allowed_payment_modes'] = unserialize($data1['allowed_payment_modes']);

        $invoice_update = $this->ci->invoices_model->update($data2, $id);
        return $id;
    }

    public function createSageAccountForTransfer($data){
        $this->getSageConfiguration();
        $current_time = strtotime(date('Y-m-d h:i:s'));
        $savedRmd = strtotime($this->getRmd());

        $difference_in_seconds = $current_time - $savedRmd;

        if($difference_in_seconds >= $this->refresh_token_expires_in){
            $location = admin_url('sage/sageAuthorization');
            header('Location: ' . $location);
        } else if($difference_in_seconds >= $this->access_token_expires_in){
            $postData = "client_id=".$this->getClientId()."&client_secret=".$this->getClientsecret()."&grant_type=refresh_token&refresh_token=".$this->getRefreshToken();

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL,$this->exchangeAuthorizationCodeForAccessTokenUrl);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);

            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

            $server_output = curl_exec($ch);

            curl_close ($ch);
            $parsed_result = json_decode($server_output, true);
            $_SESSION['access_token'] = $parsed_result['access_token'];
            $_SESSION['refresh_token'] = $parsed_result['refresh_token'];
            $this->updateAccessAndRefreshToken($parsed_result['access_token'], $parsed_result['refresh_token']);
        }

        if (!class_exists('payment_modes_model')) {
            $this->ci->load->model('payment_modes_model');
        }

        $invoice_update_record = $this->ci->invoices_model->get($data['invoiceid']);
        $sage_invoiceid = $invoice_update_record->sage_invoiceid;
        $contact = $this->ci->clients_model->get_contacts($invoice_update_record->clientid);
        $sage_contactid = $contact[0]['sage_contactid'];

        $date = $data['date'];
        $amount = $data['amount'];
        $total = $invoice_update_record->total;

        $headers = array(
            'Accept: application/json',
            'Content-Type: application/json',
            'Authorization: Bearer '.$this->getAccessToken()
        );
        $data_payment = '{
                  "contact_payment": {
                    "transaction_type_id": "CUSTOMER_RECEIPT",
                    "payment_method_id": "CREDIT_DEBIT",
                    "contact_id": "'.$sage_contactid.'",
                    "bank_account_id": "6d4661f586794b988c0f3ce9c24386a4",
                    "date": "'.$date.'",
                    "total_amount": "'.$total.'",
                    "allocated_artefacts": [
                      {
                        "artefact_id": "'.$sage_invoiceid.'",
                        "amount": "'.$amount.'"
                      }
                    ]
                  }
                }';

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL,            $this->contact_payments_endpoint );
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1 );
        curl_setopt($ch, CURLOPT_POST,           1 );
        curl_setopt($ch, CURLOPT_POSTFIELDS,     $data_payment );
        curl_setopt($ch, CURLOPT_HTTPHEADER,     $headers);

        $result=curl_exec ($ch);
        $parsed_result = json_decode($result, true);

        return true;
    }

}