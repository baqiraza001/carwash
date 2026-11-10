<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Commissions extends AdminController {

    private $_module = 'commissions';

    public function __construct(){
        parent::__construct();
    }

    public function index(){
        if (!has_permission($this->_module, '', 'view')) 
        {
            access_denied($this->_module);
        }
        if ($this->input->is_ajax_request()) {
            if(has_permission($this->_module, '', 'view')){
                $this->app->get_table_data(module_views_path($this->_module,'table'));  
            }else{
                access_denied($this->_module); 
            }
            
        }
        $data['title'] = _l($this->_module);
        $data['module'] = $this->_module;
        $this->load->view('referrals/commissions/manage',$data);

    }

    public function table(){
        if ($this->input->is_ajax_request()) {
            $this->app->get_table_data(module_views_path('referrals','commissions/table'));
        }
    }

    public function update_commissions_status(){
        $posted_data = $this->input->post();
        $data['amount'] = $posted_data['amount'];
        $data['status'] = $posted_data['status'];
        $data['method'] = $posted_data['method'];

        $this->db->where('id' , $posted_data['id'])->update('tbl_commissions' , $data);

        $this->load->model('currencies_model');
        $base_currency = $this->currencies_model->get_base_currency();
        $expense_data['amount'] = $posted_data['amount'];
        $expense_data['paymentmode'] = $posted_data['method'];
        $expense_data['date'] = date('Y-m-d');
        $expense_data['clientid'] = $posted_data['clientid'];
        $expense_data['currency'] = $base_currency->id;
        $expense_data['dateadded'] = date('Y-m-d H:i:s');
        $expense_data['addedfrom'] = get_staff_user_id();

        $commission_category = $this->db->where('name' , 'Commission')->order_by('id','asc')->limit(1)->get('tblexpenses_categories')->row_array();

        $expense_data['category'] = $commission_category['id'];
        $this->db->insert('tblexpenses',$expense_data);
        if($this->db->affected_rows() > 0){
            echo json_encode(array('message' => _l('referrals_updated_successfully'), 'status' => 'success'));
        }else{
            echo json_encode(array('message' => _l('referrals_problem_in_updating'), 'status' => 'danger'));
        }
    }
}