<?php

defined('BASEPATH') or exit('No direct script access allowed');
set_time_limit(0);

class Sage extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('SageAPIWrapper');
    }


   public function sageAuthorization(){
       $sageWrapper = new SageAPIWrapper();
       session_start();
       $sageWrapper->getAuthorizationRequestCode();
       exit();
   }

    public function sagecallback(){
        $code = $_REQUEST['code'];
        $sageWrapper = new SageAPIWrapper();
        $sageWrapper->getSageConfiguration();
        $postData = "client_id=".$sageWrapper->getClientId()."&client_secret=".$sageWrapper->getClientsecret()."&code=".$code."&grant_type=authorization_code&redirect_uri=".$sageWrapper->getRedirectUri();

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,$sageWrapper->exchangeAuthorizationCodeForAccessTokenUrl);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);

        // Receive server response ...
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $server_output = curl_exec($ch);

        curl_close ($ch);
        $parsed_result = json_decode($server_output, true);

        $_SESSION['access_token'] = $parsed_result['access_token'];
        $_SESSION['refresh_token'] = $parsed_result['refresh_token'];
        $sageWrapper->setAccessToken($parsed_result['access_token']);
        $sageWrapper->setRefreshToken($parsed_result['refresh_token']);
        $sageWrapper->updateAccessAndRefreshToken($parsed_result['access_token'], $parsed_result['refresh_token']);

        set_alert('success', _l('sage_configuration_saved_and_session_started', _l('sage')));
        redirect(admin_url('sage'));
        exit;
    }

    public function index()
    {
        $data['title']= _l('sage_configuration');
        $configuration = $this->db->get(db_prefix() . 'sage')->row();
        if(isset($configuration)){
            $data['client_id'] = $configuration->client_id;
            $data['client_secret'] = $configuration->client_secret;
            $data['redirect_uri'] = $configuration->redirect_uri;
        }
        $this->load->view('sage_configuration', $data);
       
    }

    public function save_configuration(){
        $client_id = html_purify($this->input->post('client_id', false));
        $client_secret   = html_purify($this->input->post('client_secret', false));
        $redirect_uri   = html_purify($this->input->post('redirect_uri', false));

        $data = array(
            'client_id'=>$client_id,
            'client_secret'=>$client_secret,
            'redirect_uri'=>$redirect_uri,
        );


        $configuration = $this->db->get(db_prefix() . 'sage')->row();
        if(isset($configuration)){
            $this->db->update( db_prefix().'sage',$data);
        }else{
            $this->db->insert( db_prefix().'sage',$data);
        }
        redirect(admin_url('sage/sageAuthorization'));
    }
	
}
