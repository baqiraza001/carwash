<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Stock_adjustment extends AdminController {

    private $_module = 'stock_adjustment';

    public function __construct(){
        parent::__construct();
        $this->load->model($this->_module.'_model','module_model');
        $this->load->library($this->_module.'_repository',NULL,'module_repo');
        $this->load->model('stock_products_model','stock_products_model');
        $this->load->model('checkin_model','checkin_model');
    }

    public function index(){
        if (!has_permission($this->_module, '', 'view') && !has_permission($this->_module, '', 'view_own')) 
        {
            access_denied($this->_module);
        }
        if ($this->input->is_ajax_request()) {
            if(has_permission($this->_module, '', 'view') || is_admin()){
                $this->app->get_table_data(module_views_path('stock_management',$this->_module.'/table'));  
            }else if(has_permission($this->_module, '', 'view_own')){
                $this->app->get_table_data(module_views_path('stock_management',$this->_module.'/table') , ['view_own' => true]);
            }else{
                access_denied($this->_module); 
            }
            
        }
        $data['title'] = _l($this->_module);
        $data['module'] = $this->_module;
        $this->load->view($this->_module.'/manage',$data);

    }

    public function create($id = '')
    {
        if (isset($id) && !empty($id) && !has_permission($this->_module, '', 'edit')) {
          access_denied($this->_module);
        }else if((!isset($id) || empty($id)) && !has_permission($this->_module, '', 'create')){
          access_denied($this->_module);
        }

        $is_submit = sizeof($this->input->post()) > 0 ? TRUE : FALSE ;

        if ($is_submit) {
            $submit = trim($this->input->post('submit', TRUE));
            // posted data

            $data = $this->input->post();
            $data['reason'] = $this->input->post('reason',false);

            //add new record
            if ($id == '') {
                $id = $this->module_repo->save($data);
                if ($id) {
                    set_alert('success', _l('adjustment_added_successfully'));
                }else{
                    set_alert('danger', _l('problem_in_adding_new_adjustment'));
                }
                redirect(admin_url('stock_management/'.$this->_module));
            //update existing record
            } else {
                $updated = $this->module_repo->save($data, $id);
                if($updated){
                    set_alert('success', _l('adjustment_updated_successfully'));
                }else{
                    set_alert('danger', _l('problem_in_updating_adjustment'));
                }
                redirect(admin_url('stock_management/'.$this->_module));

            } // Form submission end here
        }
        
        if ($id == '' || $id == NULL) {
            $data['form_heading'] = _l('add_new');
        }
        else {
            isset($data) || $data = $this->module_repo->fetch_data_from_db($id);
            if(!is_array($data) || $data == ""):
                blank_page(l('blank_page'));
            endif;
            $data['edit_mode'] = TRUE;
            $data['form_heading'] = _l('edit');
        }
        $data['title'] = _l($this->_module);
        $data['module'] = $this->_module;
        $data['products'] = $this->stock_products_model->get();
        $data['warehouses'] = $this->checkin_model->get_warehouses();

        $this->load->view($this->_module.'/create',$data);
    }

    /* Delete record from database */
    public function delete($id)
    {
        if (!has_permission($this->_module, '', 'delete')) {
            access_denied($this->_module);
        }
        if (!$id) {
            redirect(admin_url('stock_management/'.$this->_module));
        }
        $response = $this->module_model->delete($id);
        if ($response == true) {
            set_alert('success', _l('adjustment_deleted_successfully'));
        } else {
            set_alert('warning', _l('problem_in_deleting_adjustment'));
        }
        redirect(admin_url('stock_management/'.$this->_module));
    }
}
