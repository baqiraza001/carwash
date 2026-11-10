<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Checkout extends AdminController {

    private $_module = 'checkout';
    private $_permission_module = 'stock_management';

    public function __construct(){
        parent::__construct();

        $this->load->model($this->_module.'_model','module_model');
        $this->load->library($this->_module.'_repository',NULL,'module_repo');
        $this->load->model('suppliers_model','suppliers_model');
        $this->load->model('stock_products_model','stock_products_model');
    }

    public function index(){
        if (!has_permission($this->_permission_module, '', 'view')) {
            access_denied($this->_permission_module);
        }
        if ($this->input->is_ajax_request()) {
            $this->app->get_table_data(module_views_path('stock_management', $this->_module.'/table'));
        }
        $data['title'] = _l($this->_module);
        $data['module'] = $this->_module;
        $data['permission_module'] = $this->_permission_module;
        $this->load->view($this->_module.'/manage',$data);
    }

    public function create($id = '')
    {
        if (isset($id) && !empty($id) && !has_permission($this->_permission_module, '', 'edit')) {
          access_denied($this->_permission_module);
        }else if((!isset($id) || empty($id)) && !has_permission($this->_permission_module, '', 'create')){
          access_denied($this->_permission_module);
        }

        $is_submit = sizeof($this->input->post()) > 0 ? TRUE : FALSE ;

        if ($is_submit != NULL) {
            $submit = trim($this->input->post('submit', TRUE));
            // posted data

            $data = $this->input->post();

            unset($data['submit']);
            if ($id == '') {
                $id = $this->module_repo->save($data);

                if ($id) {
                    set_alert('success', _l('created', _l($this->_module)));
                }
                redirect(admin_url('stock_management/'.$this->_module));
            } else {
                $updated = $this->module_repo->save($data, $id);

                if($updated){
                  set_alert('success', _l('updated', _l($this->_module)));
                }
                redirect(admin_url('stock_management/'.$this->_module));
            } 
        }
        // Form submission end here

        $edit_mode = FALSE;
        $form_heading = _l('add_checkout');
        if (isset($id) && !empty($id))
        {
            isset($data) || $data = $this->module_repo->fetch_data_from_db($id);
            if(isset($data['add_item']) && !empty($data['add_item'])){
                $this->load->model('stock_products_model');
                $data['add_item_name'] = $this->stock_products_model->get($data['add_item'])['product_title'];
            }

            if(!is_array($data) || $data == ""):
                blank_page(_l('blank_page_error'));
            endif;

            $form_heading = _l('edit_checkout');
            $edit_mode = TRUE;
        }

        $data['edit_mode'] = $edit_mode;
        $data['form_heading'] = $form_heading;
        
        $data['suppliers'] = $this->suppliers_model->get();
        // $data['products'] = $this->stock_products_model->get();
        $data['products'] = $this->db->get(db_prefix() . 'items')->result_array();
        $data['warehouses'] = $this->module_model->get_warehouses();
        $data['form_heading'] = $form_heading;
        $data['title'] = _l($this->_module);

        $this->load->view('checkout/create',$data);
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
            set_alert('success', _l('deleted', _l('checkin')));
        } else {
            set_alert('warning', _l('problem_deleting', _l('checkin')));
        }
        redirect(admin_url('stock_management/'.$this->_module));
    }

    public function get_products()
    {
        $products = $this->db->get('tbl_sm_products')->result_array();
        $products_array = array();
        foreach($products as $key=>$val){
            $products_array[$key]['product'] = $val['name'];
            $products_array[$key]['id'] = $val['id'];
        }
        echo json_encode($products_array);
        exit();
    }
}