<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Manage_settings extends AdminController {

    public function __construct(){
        parent::__construct();
        $this->load->model('stock_products_model', 'stock_product_model');
        $this->load->library('Stock_products_repository',NULL,'module_repo');
        $this->load->helper('stock_products');

    }

    public function index(){
        if (!has_permission('stock_management', '', 'view')) {
            access_denied('stock_management');
        }
        $this->load->view('manage_setting');

    }
    public function warehouse(){
        if ($this->input->is_ajax_request()) {
            $this->app->get_table_data(module_views_path('stock_management', 'warehouse_table'));

        }
    }

    public function categories(){
        if ($this->input->is_ajax_request()) {
            $this->app->get_table_data(module_views_path('stock_management', 'categories_table'));

        }
    }

    public function add_category(){
        $is_submit = sizeof($this->input->post()) > 0 ? TRUE : FALSE ;

        if ($is_submit != NULL) {
            $submit = trim($this->input->post('submit', TRUE));

            $data = $this->input->post();

            unset($data['submit']);
            $this->db->insert('tbl_sm_categories',$data);
            $id = $this->db->insert_id();
            if ($id) {
                set_alert('success', _l('created', _l('category')));

            }
            redirect(admin_url('stock_management/manage_settings'));

        }
    }

    public function edit_category(){
        $is_submit = sizeof($this->input->post()) > 0 ? TRUE : FALSE ;

        if ($is_submit != NULL) {
            $submit = trim($this->input->post('submit', TRUE));

            $data = $this->input->post();
            $id = $data['id'];
            unset($data['submit']);
            unset($data['id']);


            $this->db->where('id',$id);
            $this->db->set('name',$data['name']);
            $this->db->set('description',$data['description']);

            $res =  $this->db->update('tbl_sm_categories');

            if ($res) {
                set_alert('success', _l('updated', _l('category')));
            }
            redirect(admin_url('stock_management/manage_settings'));

        }
    }

    public function add_warehouse(){
        $is_submit = sizeof($this->input->post()) > 0 ? TRUE : FALSE ;

        if ($is_submit != NULL) {
            $submit = trim($this->input->post('submit', TRUE));

            $data = $this->input->post();
            unset($data['submit']);


            $this->db->insert('tbl_sm_warehouses',$data);
            $id = $this->db->insert_id();

                if ($id) {
                    set_alert('success', _l('created', _l('warehouse')));


                }
                redirect(admin_url('stock_management/manage_settings'));

        }
    }
    public function edit_warehouse(){
        $is_submit = sizeof($this->input->post()) > 0 ? TRUE : FALSE ;

        if ($is_submit != NULL) {
            $submit = trim($this->input->post('submit', TRUE));

            $data = $this->input->post();
            $id = $data['id'];
            unset($data['submit']);
            unset($data['id']);

            $this->db->where('id',$id);
            $this->db->set('name',$data['name']);
            $this->db->set('description',$data['description']);
            $this->db->set('address',$data['address']);

            $res =  $this->db->update('tbl_sm_warehouses');

            if ($res) {
                set_alert('success', _l('updated', _l('warehouse')));


            }
            redirect(admin_url('stock_management/manage_settings'));

        }
    }

    public function warehouse_delete($id){
        $this->db->where('id',$id);
        $this->db->delete('tbl_sm_warehouses');
        set_alert('success', 'Warehouse deleted successfully');
        redirect(admin_url('stock_management/manage_settings'));
    }

    public function category_delete($id){
        $this->db->where('id',$id);
        $this->db->delete('tbl_sm_categories');
        set_alert('success', 'Category deleted successfully');
        redirect(admin_url('stock_management/manage_settings'));
    }
    
    public function update_low_stock() {
        if($this->input->post('sm_low_stock'))
        {
            update_option('sm_low_stock', $this->input->post('sm_low_stock'));
            set_alert('success', 'Low stock value updated successfully');
            redirect(admin_url('stock_management/manage_settings'));
        }
        
    }

}