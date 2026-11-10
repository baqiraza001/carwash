<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Suppliers extends AdminController {

    private $_module = 'suppliers';
    private $_permission_module = 'stock_management';

    public function __construct(){
        parent::__construct();
        $this->load->model($this->_module.'_model','module_model');
        $this->load->library($this->_module.'_repository',NULL,'module_repo');
    }

    public function index(){
        if (!has_permission($this->_permission_module, '', 'view')) {
            access_denied($this->_permission_module);
        }
        if ($this->input->is_ajax_request()) {
            $this->app->get_table_data(module_views_path('stock_management', $this->_module.'/table'));
        }
        $this->app_scripts->add('circle-progress-js','assets/plugins/jquery-circle-progress/circle-progress.min.js');

        //breadcrumb
        $data['title'] = _l($this->_module);
        $data['module'] = $this->_module;
        $breadcrumb_data['current_page_title'] = _l($this->_module).' '._l('listing');
        $breadcrumb_data['breadcrumb_array'] = array(
            admin_url('') => _l('dashboard'),
        );
        $data['breadcrumb_data'] = $breadcrumb_data;
        $this->load->view($this->_module.'/manage',$data);

    }

    public function create($id = '')
    {
        if (isset($id) && !has_permission($this->_permission_module, '', 'edit')) {
          access_denied($this->_permission_module);
        }else if(!isset($id) && !has_permission($this->_permission_module, '', 'create')){
          access_denied($this->_permission_module);
        }

        $is_submit = sizeof($this->input->post()) > 0 ? TRUE : FALSE ;

        if ($is_submit) {
            $submit = trim($this->input->post('submit', TRUE));
            // posted data

            $data = $this->input->post();

            //add new record
            if ($id == '') {
                $id = $this->module_repo->save($data);
                if ($id) {
                    set_alert('success', _l('created', _l('supplier')));
                    redirect(admin_url('stock_management/'.$this->_module));
                }
            //update existing record
            } else {
                $updated = $this->module_repo->save($data, $id);
                if($updated){
                  set_alert('success', _l('updated', _l('supplier')));
                  redirect(admin_url('stock_management/'.$this->_module));
                }

              } // Form submission end here
        }


        if ($id == '' || $id == NULL) {
            isset($data) || $data = $this->module_repo->get_raw_data();

            if(!is_array($data) || $data == ""):

                blank_page('Record not found or deleted or wrong ID');
            endif;
            $breadcrumb_data['current_page_title'] = _l('add').' '._l('supplier');
        }
        else {
            isset($data) || $data = $this->module_repo->fetch_data_from_db($id);

            if(!is_array($data) || $data == ""):

                blank_page('Record not found or deleted or wrong ID');
            endif;
            $breadcrumb_data['current_page_title'] = _l('edit').' '._l('supplier');
            $data['edit_mode'] = TRUE;
        }
        //breadcrumbs
        $data['form_heading'] = $breadcrumb_data['current_page_title'];
        $data['title'] = _l($this->_module);
        $data['module'] = $this->_module;
        $breadcrumb_data['breadcrumb_array'] = array(
            admin_url('') => _l('dashboard'),
            admin_url('stock_management/'.$this->_module) => $data['title'],
        );
        $data['breadcrumb_data'] = $breadcrumb_data;

        $this->load->view($this->_module.'/create',$data);
    }

    /* Delete record from database */
    public function delete($id)
    {
        if (!has_permission($this->_permission_module, '', 'delete')) {
            access_denied($this->_permission_module);
        }
        if (!$id) {
            redirect(admin_url('stock_management/'.$this->_module));
        }
        $response = $this->module_model->delete($id);
        if ($response == true) {
            set_alert('success', _l('deleted', _l('supplier')));
        } else {
            set_alert('warning', _l('problem_deleting', _l('supplier')));
        }
        redirect(admin_url('stock_management/'.$this->_module));
    }
}
