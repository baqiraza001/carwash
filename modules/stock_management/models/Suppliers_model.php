<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Suppliers_model extends App_Model
{
    protected $_table_name = 'tbl_sm_suppliers';
    protected $_primary_key = 'id';
    protected $_primary_filter = 'intval';

    function __construct()
    {
        parent::__construct();
    }

    // Get Last id from latest added record
    public function getLastID(){
        return $this->db->select('id')->order_by('id',"desc")->limit(1)->get($this->_table_name)->row();
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
            $data['created_by'] = get_staff_user_id();
            $this->db->insert($this->_table_name,$data);
            $id = $this->db->insert_id();
        }
      // Update
        else {
            $data['modified_by'] = get_staff_user_id();
            $this->db->where($this->_primary_key, $id);
            $this->db->update($this->_table_name,$data);
        }

        return $id;
    }

    //soft delete job opening
    public function delete($id)
    {
        $this->db->where('id', $id);
        $this->db->set('is_deleted',1);
        $this->db->update($this->_table_name);

        if ($this->db->affected_rows() > 0) {
          return true;
        }
        return false;
    }
}
