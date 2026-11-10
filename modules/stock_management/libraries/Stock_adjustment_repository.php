<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Stock_adjustment_repository
{
    private $CI;
    private $_module = 'stock_adjustment';

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

        if (!$id) { // Insert
            $inserted_id = $this->CI->module_m->save($data);

            if($inserted_id){
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
