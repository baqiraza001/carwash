<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Mailtrap_sync_model extends App_Model {
    private $api;

    public function __construct() {
        parent::__construct();
    }

    public function save($data, $id = NULL){
        $now = date('Y-m-d H:i:s');
        // Insert
        if ($id === NULL) {
            !isset($data[$this->_primary_key]) || $data[$this->_primary_key] = NULL;
            $data['created'] = $now;
            $data['created_by'] = get_staff_user_id();
            $this->db->set($data);
            $this->db->insert($this->_table_name);
            $id = $this->db->insert_id();
        } else {
            $filter = $this->_primary_filter;
            $data['modified_at'] = $now;
            $id = $filter($id);
            $data = array_filter($data);
            $this->db->set($data);
            $this->db->where($this->_primary_key, $id);
            $this->db->update($this->_table_name);
        }
        return $id;

    }
}
