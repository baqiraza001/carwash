<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Stock_adjustment_model extends App_Model
{
    protected $_table_name = 'tbl_sm_adjustments';
    protected $_primary_key = 'id';
    protected $_primary_filter = 'intval';

    function __construct()
    {
        parent::__construct();
    }

    //get single or multiple records from db
    public function get($id = NULL, $single = FALSE){

        if ($id != NULL) {
            $filter = $this->_primary_filter;
            $id = $filter($id);
            $this->db->where($this->_primary_key, $id);
            $method = 'row_array';
        }
        elseif($single == TRUE) {
            $method = 'row_array';
        }
        else {
            $method = 'result_array';
        }
        return $this->db->get($this->_table_name)->$method();
    }

    //save or update record
    public function save($data, $id = NULL){
        // Insert
        if ($id === NULL) {
            $data['created_on'] = date('Y-m-d H:i:s');
            $data['created_by'] = get_staff_user_id();
            $this->db->insert($this->_table_name,$data);
            $id = $this->db->insert_id();

            if($id){
                return $id;
            }
            
        }
      // Update
        else {
            $data['modified_on'] = date('Y-m-d H:i:s');
            $data['modified_by'] = get_staff_user_id();
            $this->db->where($this->_primary_key, $id);
            $updated = $this->db->update($this->_table_name,$data);
            if($updated){
                return $updated;
            }
        }

        return false;
    }

    //delete record
    public function delete($id)
    {
        $this->db->where('id', $id);
        $deleted = $this->db->delete($this->_table_name);

        if ($deleted) {
            return true;
        }
        return false;
    }
}



