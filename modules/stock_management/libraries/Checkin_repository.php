<?php
defined('BASEPATH') or exit('No direct script access allowed');
/**
 * Created by Naseem Abbas.
 * Date: 06/13/2020
 * Time: 10:45 PM
 */
class Checkin_repository
{
    private $CI;
    private $IDPrefix = 'C-';
    private $_module = 'checkin';


    public function __construct() {
        $this->CI =& get_instance();
        $this->CI->load->model($this->_module.'_model','module_m');
    }
    public function getNewID()
    {
        $lastRow = $this->CI->module_m->getLastID();
        if ($lastRow == NULL || empty($lastRow)) {
            return $this->IDPrefix . '1';
        }
        return $this->IDPrefix . (intval($lastRow->id) + 1);
    }

    public function get_raw_data()
    {
        $data = array();

        $data['checkin_id'] =$this->getNewID();
        $data['id'] = '';
        return $data;

    }

    public function fetch_data_from_db($id)
    {
        $data = $this->CI->module_m->get($id, TRUE);

        $data['checkin_id'] = $this->IDPrefix .  $id;

        return $data;
    }

    public function save($data, $id = '')
    {
        if (!$id) { // Insert
            if (isset($data['date']) && !empty($data['date'])){
                $data['date'] = to_sql_date($data['date'],true);
            } else {
                $data['date'] = NULL;
            }

            $inserted_id =  $this->CI->module_m->save($data);
            if($inserted_id){
                $warehouse_data['warehouse_id'] = $data['warehouse'];
                $warehouse_data['product_id'] = $data['item'];
                $warehouse_data['quantity'] = $data['units'];
                
                //check if warehouse data already exists
                $data_exists = $this->CI->db->where('warehouse_id' , $warehouse_data['warehouse_id'])->where('product_id' , $warehouse_data['product_id'])->get('tbl_sm_warehouse_products')->row_array();
                if(isset($data_exists) && !empty($data_exists)){
                    $existing_quantity = $data_exists['quantity'];
                    $updated_quantity = $existing_quantity + $warehouse_data['quantity'];
                    $this->CI->db->where('warehouse_id' , $warehouse_data['warehouse_id'])->where('product_id' , $warehouse_data['product_id'])->set('quantity',$updated_quantity)->update('tbl_sm_warehouse_products');
                }else{
                    $this->CI->db->insert('tbl_sm_warehouse_products' , $warehouse_data);
                }
            }

            return $inserted_id;

        } else {  // Update

            return $this->CI->module_m->save($data , $id);

        }
    }


}
