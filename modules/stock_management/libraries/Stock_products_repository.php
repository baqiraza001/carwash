<?php
defined('BASEPATH') or exit('No direct script access allowed');
/**
 * Created by Naseem Abbas.
 * Date: 06/13/2020
 * Time: 10:45 PM
 */
class Stock_products_repository
{
    private $CI;
    private $IDPrefix = 'I-';
    private $_module = 'stock_products';

    public function __construct() {
        $this->CI =& get_instance();
        $this->CI->load->model($this->_module.'_model','module_m');
    }
    
    public function fetch_data_from_db($id)
    {
        $data = $this->CI->module_m->get($id, TRUE);
        $data['stock_product_id'] = $this->IDPrefix .  $id;

        return $data;
    }

    public function save($data, $id = '')
    {
        
        if (!$id) { // Insert
            $inserted_id =  $this->CI->module_m->save($data);

            return $inserted_id;

        } else {  // Update

            return $this->CI->module_m->save($data , $id);

        }
    }


}
