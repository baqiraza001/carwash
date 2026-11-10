<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Stock_transfer_repository
{
    private $CI;
    private $_module = 'stock_transfer';

    public function __construct()
    {
        $this->CI =& get_instance();
        $this->CI->load->model($this->_module.'_model','module_m');

    }

    //fetch data from database
    public function fetch_data_from_db($id)
    {
        $data = $this->CI->module_m->get($id, TRUE);
        return $data;
    }

    //Add edit
    public function save($data, $id = '')
    {
        $submit = $data['submit'];
        unset($data['submit']);

        $data['date'] = to_sql_date($data['date']);

        if (!$id) { // Insert
            $inserted_id = $this->CI->module_m->save($data);

            if($inserted_id){
                $to_warehouse_data['warehouse_id'] = $data['to_warehouse'];
                $to_warehouse_data['product_id'] = $data['product'];
                $to_warehouse_data['quantity'] = $data['quantity'];

                $from_warehouse_product_data = $this->CI->db->where('warehouse_id' , $data['from_warehouse'])->where('product_id' , $data['product'])->get('tbl_sm_warehouse_products')->row_array();
                $to_warehouse_product_data = $this->CI->db->where('warehouse_id' , $to_warehouse_data['warehouse_id'])->where('product_id' , $to_warehouse_data['product_id'])->get('tbl_sm_warehouse_products')->row_array();

                if(isset($to_warehouse_product_data) && !empty($to_warehouse_product_data)){
                    $existing_quantity = $to_warehouse_product_data['quantity'];
                    $updated_quantity = $existing_quantity + $to_warehouse_data['quantity'];
                    $this->CI->db->where('warehouse_id' , $to_warehouse_data['warehouse_id'])->where('product_id' , $to_warehouse_data['product_id'])->set('quantity',$updated_quantity)->update('tbl_sm_warehouse_products');
                }else{
                    $this->CI->db->insert('tbl_sm_warehouse_products' , $to_warehouse_data);
                }

                $from_warehouse_existing_quantity = $from_warehouse_product_data['quantity'];
                $from_warehouse_updated_quantity = $from_warehouse_existing_quantity - $data['quantity'];
                $this->CI->db->where('warehouse_id' , $data['from_warehouse'])->where('product_id' , $data['product'])->set('quantity',$from_warehouse_updated_quantity)->update('tbl_sm_warehouse_products');

                return $inserted_id;
            }
            
        } else {  // Update
            $updated = $this->CI->module_m->save($data, $id);
            if($updated){
                return $updated;
            }
            
        }
        return false;
    }

}
