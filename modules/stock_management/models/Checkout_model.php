<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Checkout_model extends App_Model
{
    protected $_table_name = 'tbl_sm_checkouts';
    protected $_primary_key = 'id';
    protected $_primary_filter = 'intval';

    function __construct()
    {
        parent::__construct();
    }

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

    public function get_by($where, $single = FALSE){
        $this->db->where($where);
        return $this->get(NULL, $single);
    }

    public function save($data, $id = NULL){
        // Insert
        if ($id === NULL) {
            !isset($data[$this->_primary_key]) || $data[$this->_primary_key] = NULL;
            $data['created_by'] = get_staff_user_id();
            $data['created_on'] = date('Y-m-d H:i:s');
            $this->db->set($data);
            $this->db->insert($this->_table_name);
            $id = $this->db->insert_id();
        }
        // Update
        else {
            $filter = $this->_primary_filter;
            $data['modified_by'] = get_staff_user_id();
            $data['modified_on'] = date('Y-m-d H:i:s');
            $id = $filter($id);
            $this->db->set($data);
            $this->db->where($this->_primary_key, $id);
            $this->db->update($this->_table_name);
        }

        return $id;
    }

    //delete 
    public function delete($id)
    {
        $this->db->where('id', $id);
        $deleted = $this->db->delete($this->_table_name);
        
        if ($deleted) {
          return true;
        }
        return false;
    }

    public function get_warehouses($id = NULL){
        if ($id != NULL) {
            $this->db->where('id', $id);
            $method = 'row_array';
        }else {
            $method = 'result_array';
        }
        return $this->db->get('tbl_sm_warehouses')->$method();
    }
}