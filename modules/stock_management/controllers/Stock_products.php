<?php
// VERSION: 2026-11-10 09:15 PKT - create_item_ajax() now also saves long_description; inventory list newest first (hidden id column)
defined('BASEPATH') OR exit('No direct script access allowed');

class Stock_products extends AdminController {

    private $_module = 'stock_products';
    private $_permission_module = 'stock_management';

    public function __construct(){
        parent::__construct();

        $this->load->model($this->_module.'_model','module_model');
        $this->load->library($this->_module.'_repository',NULL,'module_repo');
        $this->load->helper($this->_module);
        //$this->load->library('zend');

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
        }else if((!isset($id) || empty($id))  && !has_permission($this->_permission_module, '', 'create')){
          access_denied($this->_permission_module);
        }

        $is_submit = sizeof($this->input->post()) > 0 ? TRUE : FALSE ;

        if ($is_submit != NULL) {
            $submit = trim($this->input->post('submit', TRUE));
            
            // posted data
            $data = $this->input->post();
            unset($data['submit']);
            if (isset($_FILES['image']['name']) && !empty($_FILES['image']['name'])){
                $uploadDir = 'modules/stock_management/assets/images';
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0755);
                }
                $attachmentFolder = $uploadDir.'/product_images';
                if (!is_dir($attachmentFolder)) {
                    mkdir($attachmentFolder, 0755);
                }
                $tmpFile = $_FILES['image']['tmp_name'];
                $extension = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
                $random_name = date('YmdHis').sm_generate_random_string(10).'.'.$extension;

                $filename = $attachmentFolder.'/'.$random_name;

                move_uploaded_file($tmpFile, $filename);
                $data['image'] = base_url($attachmentFolder.'/'.$random_name);
                $data['image_name'] = $_FILES['image']['name'];
            }
            
            if ($id == '') {
                $id = $this->module_repo->save($data);
                if ($id) {
                    set_alert('success', _l('created', _l('stock_product')));
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
        $form_heading = _l('add_product');
        if (isset($id) && !empty($id)){
            isset($data) || $data = $this->module_repo->fetch_data_from_db($id);
            if(!is_array($data) || $data == ""):
                blank_page(_l('blank_page_error'));
            endif;
            $edit_mode = TRUE;
            $form_heading = _l('edit_product');
        }
        $data['edit_mode'] = $edit_mode;
        $data['form_heading'] = $form_heading;
        $data['warehouses'] = $this->module_model->get_warehouses();
        $data['categories'] = $this->module_model->get_categories();

        $this->load->model('taxes_model');
        $data['taxes'] = $this->taxes_model->get();

        $this->load->model('currencies_model');
        $currency = $this->currencies_model->get_base_currency();
        $data['currency_symbol'] = $currency->symbol;

        $data['title'] = _l($this->_module);
        $data['random_product_code'] = $this->generate_random_product_code();

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
            set_alert('success', _l('deleted', _l('product')));
        } else {
            set_alert('warning', _l('problem_deleting', _l('product')));
        }
        redirect(admin_url('stock_management/'.$this->_module));
    }

    public function generate_random_product_code(){
        $unique = FALSE;
        while(!$unique){
            $random = rand(100000000000,999999999999);
            $unique = $this->check_if_code_exists($random);
        }
        return $random;
    }

    public function check_if_code_exists($code = ''){
        if($this->input->post('code')){
            $code = $this->input->post('code');
        }

        $this->db->where('product_code',$code);
        $code_exists = $this->db->get('tbl_sm_products')->row_array();
        if($code_exists){
            if ($this->input->is_ajax_request()) {
                echo json_encode(false);
                die();           
            }
            return false;
        }else{
            if ($this->input->is_ajax_request()) {
                echo json_encode(true);
                die();            
            }
            return true; 
        }
        
    }
    
    public function get_item_by_id($id)
    {
        if ($this->input->is_ajax_request()) {
            $item                     = $this->db->select('t1.name as taxname,t1.taxrate as taxrate,tbl_sm_products.id as id,tbl_sm_products.description as description')->where('tbl_sm_products.id' , $id)->join(db_prefix() . 'taxes t1', 't1.id = ' . db_prefix() . '_sm_products.tax', 'left')->get('tbl_sm_products')->row();

            echo json_encode($item);
        }
    }

    public function make_default_warehouse($id)
    {
        $this->db->where('id', $id);
        $this->db->update(db_prefix() . '_sm_warehouses', [
            'isdefault' => 1,
        ]);
        if ($this->db->affected_rows() > 0) {
            $this->db->where('id !=', $id);
            $this->db->update(db_prefix() . '_sm_warehouses', [
                'isdefault' => 0,
            ]);
            
        }
        redirect(admin_url('stock_management/manage_settings'));
    }
 
    public function sold_items(){
        if (!has_permission($this->_permission_module, '', 'view')) {
            access_denied($this->_permission_module);
        }
        if ($this->input->is_ajax_request()) {
            $this->app->get_table_data(module_views_path('stock_management', 'sold_items/table'));
        }
        $data['title'] = _l('Sold Items');
        $data['module'] = $this->_module;
        $data['permission_module'] = $this->_permission_module;
        $this->load->view('sold_items/manage',$data);
    }

    public function remaining_items(){
        if (!has_permission($this->_permission_module, '', 'view')) {
            access_denied($this->_permission_module);
        }
        if ($this->input->is_ajax_request()) {
            $this->app->get_table_data(module_views_path('stock_management', 'remaining_items/table'));
        }
        $data['title'] = _l('Remaining Items');
        $data['module'] = $this->_module;
        $data['permission_module'] = $this->_permission_module;
        $this->load->view('remaining_items/manage',$data);
    }

    public function quantity_available()
    {
        $warehouse =stock_managment_default_warehouse();
        $item_id = $this->input->post('item_id');
        $count = sm_item_qty_find($item_id, $warehouse);

        if(!isset($count) || empty($count)){
            $count = 0;
        }

        echo json_encode(array('count' => $count));
        exit();
    }
    
    public function inventory_list(){
        if (!has_permission($this->_permission_module, '', 'view')) {
            access_denied($this->_permission_module);
        }
        if ($this->input->is_ajax_request()) {
            $this->app->get_table_data(module_views_path('stock_management', 'inventory_list/table'));
        }

        $data['inventory_items'] = $this->db
            ->select('id, description')
            ->from(db_prefix() . 'items')
            ->order_by('description', 'ASC')
            ->get()
            ->result_array();
        $data['inventory_suppliers'] = $this->db
            ->select('id, company')
            ->from(db_prefix() . '_sm_suppliers')
            ->where('is_deleted', 0)
            ->order_by('company', 'ASC')
            ->get()
            ->result_array();

        $data['title'] = _l('Inventory List');
        $data['module'] = $this->_module;
        $data['permission_module'] = $this->_permission_module;
        $this->load->view('inventory_list/manage',$data);
    }

    public function update_inventory_low_stock()
    {
        if (!$this->input->is_ajax_request()) {
            show_404();
        }
        if (!has_permission($this->_permission_module, '', 'edit')) {
            return $this->inventory_json_response(false, 'You do not have permission to update inventory settings.');
        }

        $low_stock = $this->input->post('sm_low_stock', TRUE);
        if ($low_stock === null || !preg_match('/^\d+$/', (string) $low_stock)) {
            return $this->inventory_json_response(false, 'Low stock threshold must be a non-negative whole number.');
        }

        update_option('sm_low_stock', (int) $low_stock);
        return $this->inventory_json_response(true, 'Low stock threshold saved successfully.', ['sm_low_stock' => (int) $low_stock]);
    }

    public function save_inventory_item()
    {
        if (!$this->input->is_ajax_request()) {
            show_404();
        }

        $checkin_id = (int) $this->input->post('checkin_id');
        $permission = $checkin_id ? 'edit' : 'create';
        if (!has_permission($this->_permission_module, '', $permission)) {
            return $this->inventory_json_response(false, 'You do not have permission to save inventory items.');
        }

        $item_id = (int) $this->input->post('item_id');
        $supplier_id = (int) $this->input->post('supplier_id');
        $quantity_input = $this->input->post('quantity');
        $glass_price_input = $this->input->post('glass_price');
        $quantity = filter_var($quantity_input, FILTER_VALIDATE_INT);

        if ($item_id < 1 || $supplier_id < 1 || $quantity === false || $glass_price_input === '' || !is_numeric($glass_price_input) || (float) $glass_price_input < 0) {
            return $this->inventory_json_response(false, 'Please select an item and supplier and enter a valid quantity and glass price.');
        }

        $item_exists = $this->db->where('id', $item_id)->count_all_results(db_prefix() . 'items') > 0;
        $supplier_exists = $this->db->where('id', $supplier_id)->where('is_deleted', 0)->count_all_results(db_prefix() . '_sm_suppliers') > 0;
        if (!$item_exists || !$supplier_exists) {
            return $this->inventory_json_response(false, 'The selected item or supplier could not be found.');
        }

        $checkins_table = db_prefix() . '_sm_checkins';
        $target = null;
        if ($checkin_id) {
            $target = $this->db->where('id', $checkin_id)->get($checkins_table)->row_array();
            if (!$target) {
                return $this->inventory_json_response(false, 'The inventory item could not be found.');
            }
        }

        $matching_rows = $this->db
            ->where('item', $item_id)
            ->where('supplier', $supplier_id)
            ->order_by('id', 'ASC')
            ->get($checkins_table)
            ->result_array();

        $canonical = null;
        foreach ($matching_rows as $matching_row) {
            if ($checkin_id && (int) $matching_row['id'] === $checkin_id) {
                $canonical = $matching_row;
                break;
            }
        }
        if (!$canonical && count($matching_rows) > 0) {
            $canonical = $matching_rows[0];
        }
        if (!$canonical && $target) {
            $canonical = $target;
        }

        $warehouse_id = $canonical ? (int) $canonical['warehouse'] : 0;
        if (!$warehouse_id) {
            $warehouses_table = db_prefix() . '_sm_warehouses';
            $warehouse_query = $this->db->select('id')->from($warehouses_table);
            if ($this->db->field_exists('isdefault', $warehouses_table)) {
                $warehouse_query->order_by('isdefault', 'DESC');
            }
            $default_warehouse = $warehouse_query->order_by('id', 'ASC')->get()->row_array();
            $warehouse_id = $default_warehouse ? (int) $default_warehouse['id'] : 0;
        }
        if (!$warehouse_id) {
            return $this->inventory_json_response(false, 'Create a warehouse before adding inventory.');
        }

        $data = [
            'item'          => $item_id,
            'supplier'      => $supplier_id,
            'warehouse'     => $warehouse_id,
            'units'         => $quantity,
            'purchase_price'=> number_format((float) $glass_price_input, 2, '.', ''),
            'note'          => $this->input->post('note', TRUE),
            'date'          => date('Y-m-d'),
            'modified_by'   => get_staff_user_id(),
            'modified_on'   => date('Y-m-d H:i:s'),
        ];

        $this->db->trans_begin();
        if ($canonical) {
            $saved_id = (int) $canonical['id'];
            $this->db->where('id', $saved_id)->update($checkins_table, $data);
        } else {
            $data['created_by'] = get_staff_user_id();
            $data['created_on'] = date('Y-m-d H:i:s');
            $this->db->insert($checkins_table, $data);
            $saved_id = (int) $this->db->insert_id();
        }

        $duplicate_ids = [];
        foreach ($matching_rows as $matching_row) {
            if ((int) $matching_row['id'] !== $saved_id) {
                $duplicate_ids[] = (int) $matching_row['id'];
            }
        }
        if ($target && (int) $target['id'] !== $saved_id) {
            $duplicate_ids[] = (int) $target['id'];
        }
        $duplicate_ids = array_unique($duplicate_ids);
        if (count($duplicate_ids) > 0) {
            $this->db->where_in('id', $duplicate_ids)->delete($checkins_table);
        }

        if ($this->db->trans_status() === false) {
            $this->db->trans_rollback();
            return $this->inventory_json_response(false, 'The inventory item could not be saved.');
        }
        $this->db->trans_commit();

        return $this->inventory_json_response(true, 'Inventory item saved successfully.', ['id' => $saved_id]);
    }

    public function delete_inventory_item()
    {
        if (!$this->input->is_ajax_request()) {
            show_404();
        }
        if (!has_permission($this->_permission_module, '', 'delete')) {
            return $this->inventory_json_response(false, 'You do not have permission to delete inventory items.');
        }

        $checkin_id = (int) $this->input->post('checkin_id');
        $checkin = $this->db->where('id', $checkin_id)->get(db_prefix() . '_sm_checkins')->row_array();
        if (!$checkin) {
            return $this->inventory_json_response(false, 'The inventory item could not be found.');
        }

        $this->db->where('item', $checkin['item'])->where('supplier', $checkin['supplier'])->delete(db_prefix() . '_sm_checkins');
        if ($this->db->affected_rows() < 1) {
            return $this->inventory_json_response(false, 'The inventory item could not be deleted.');
        }

        return $this->inventory_json_response(true, 'Inventory item deleted successfully.');
    }

    /* Add a new core item (tblitems) from the Inventory page, without leaving it.
       Returns JSON so the new item can be selected straight in the inventory modal. */
    public function create_item_ajax()
    {
        if (!$this->input->is_ajax_request()) {
            show_404();
        }
        if (!has_permission($this->_permission_module, '', 'create')) {
            return $this->inventory_json_response(false, 'You do not have permission to add items.');
        }

        $description = trim((string) $this->input->post('description'));
        if ($description === '') {
            return $this->inventory_json_response(false, 'Enter an item name.');
        }

        // Same item already exists - select it instead of creating a duplicate.
        $existing = $this->db->select('id, description')->where('description', $description)->get(db_prefix() . 'items')->row_array();
        if ($existing) {
            return $this->inventory_json_response(true, 'This item already exists - selected it.', [
                'id'          => (int) $existing['id'],
                'description' => $existing['description'],
                'existed'     => true,
            ]);
        }

        $data = [
            'description' => $description,
            'group_id'    => 0,
        ];

        $rate = $this->input->post('rate');
        if ($rate !== null && $rate !== '' && is_numeric($rate)) {
            $data['rate'] = (float) $rate;
        }

        $long_description = trim((string) $this->input->post('long_description', TRUE));
        if ($long_description !== '') {
            $data['long_description'] = $long_description;
        }

        $this->load->model('invoice_items_model');
        $id = $this->invoice_items_model->add($data);

        if (!$id) {
            return $this->inventory_json_response(false, 'The item could not be added.');
        }

        return $this->inventory_json_response(true, 'Item added.', [
            'id'          => (int) $id,
            'description' => $description,
            'existed'     => false,
        ]);
    }

    private function inventory_json_response($success, $message, $data = [])
    {
        return $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode([
                'success' => (bool) $success,
                'message' => $message,
                'data'    => $data,
            ]));
    }
    
    public function adjust_stock()
    {
        $item_id = $this->input->post('item_id');
        $checkin_id = $this->input->post('checkin_id');
        $warehouse_id = $this->input->post('warehouse_id');
        $qty     = $this->input->post('qty');
    
        if (!$item_id || $qty === '') {
            echo json_encode([
                'success' => false,
                'message' => 'Invalid request'
            ]);
            return;
        }
    
        $data = [
            'item_id' => $item_id,
            'checkin_id' => $checkin_id,
            'warehouse_id' => $warehouse_id,
            'qty'     => $qty,
        ];
    
        $result = sm_adjust_inventory_stock($data);
    
        if ($result) {
            echo json_encode([
                'success' => true,
                'data' => $result,
                'message' => 'Stock adjusted successfully'
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'data' => $result,
                'message' => 'Something went wrong'
            ]);
        }
    }


}