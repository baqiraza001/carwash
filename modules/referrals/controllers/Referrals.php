<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Referrals extends ClientsController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('referrals_model');
    }

    public function index()
    {
        if (is_client_logged_in()) {
            $this->db->where('referred_by_client',$_SESSION['client_user_id']);
            $data['referrals'] =  $this->db->get('tblleads')->result_array();
            $data['title']     = _l('referrals');
            $this->data($data);
            $this->view('referrals/client/manage');
            $this->layout();
        } else {
            redirect('/');
        }
    }
    
    public function create()
    {
        $posted_data = $this->input->post();
        $data['name'] = $posted_data['name'];
        $data['phonenumber'] = $posted_data['phone'];
        $data['email'] = $posted_data['email'];
        $data['city'] = $posted_data['city'];
        $data['referred_by_client'] = $_SESSION['client_user_id'];

        
        
        
        
        if(isset($posted_data['id']) && !empty($posted_data['id'])){
            $this->db->where('id' , $posted_data['id']);
            $this->db->update('tblleads' , $data);
            if($this->db->affected_rows() > 0){
                echo json_encode(array('message' => _l('referrals_updated_successfully'), 'status' => 'success'));
            }else{
                echo json_encode(array('message' => _l('referrals_problem_in_updating'), 'status' => 'danger'));
            }
            
        }else{
            $logged_in_client_source = $this->referrals_model->get_client_source($_SESSION['client_user_id']); 

            $data['source'] = isset($logged_in_client_source) ? $logged_in_client_source : '';

            $assigned = $this->referrals_model->get_client_admin($_SESSION['client_user_id']);

            $data['assigned'] = isset($assigned) ? $assigned : '';
            $data['status'] = 2;
            $data['dateassigned'] = date('Y-m-d');
            $data['dateadded']   = date('Y-m-d H:i:s');
            $this->db->insert('tblleads' , $data);
            $inserted = $this->db->insert_id();
            if ($inserted) {
                echo json_encode(array('message' => _l('referrals_added_successfully'), 'status' => 'success'));
            }else{
                echo json_encode(array('message' => _l('referrals_problem_in_adding'), 'status' => 'danger'));
            }
        }
        exit();
    }

    /* Delete record from database */
    public function delete()
    {   
        $id = $this->input->post('id');
        $response = $this->db->where('id' , $id)->delete('tblleads');
        if ($response == true) {
            echo json_encode(array('message' => _l('referrals_deleted_successfully'), 'status' => 'success'));
        } else {
            echo json_encode(array('message' => _l('referrals_problem_in_deleting'), 'status' => 'danger'));
        }
        exit();
    }

    public function get_referral_data(){
        $id = $this->input->post('id');
        $data = $this->db->where('id' , $id)->get('tblleads')->row_array();
        echo json_encode($data);
        exit();
    }

}
