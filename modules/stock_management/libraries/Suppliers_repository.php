<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Suppliers_repository
{
    private $CI;
    private $IDPrefix = 'SU-';
    private $_module = 'suppliers';

    public function __construct()
    {
        $this->CI =& get_instance();
        $this->CI->load->model($this->_module.'_model','module_m');

    }

    //Generate new id
    public function getNewID()
    {
        $lastRow = $this->CI->module_m->getLastID();
        if ($lastRow == NULL || empty($lastRow)) {
            return $this->IDPrefix . '1';
        }
        return $this->IDPrefix . (intval($lastRow->id) + 1);
    }

    //Get raw data
    public function get_raw_data()
    {
        $data = array();

        $data[$this->_module.'_id'] = $this->getNewID();
        return $data;
    }

    //fectch data from database
    public function fetch_data_from_db($id)
    {
        $data = $this->CI->module_m->get($id, TRUE);
        if (!sizeof($data) > 0 || $data['is_deleted'] == 1) {
            return '';
        }
        $data[$this->_module.'_id'] = $this->IDPrefix . $id;
        return $data;
    }

    //Add edit job_opening
    public function save($data, $id = '')
    {
        $submit = $data['submit'];
        unset($data['submit']);

        $data['is_draft'] = 0;
        if($submit == 'save_as_draft'){
            $data['is_draft'] = 1;
        }

        if (!$id) { // Insert
            $inserted_id = $this->CI->module_m->save($data);
            return $inserted_id;

        } else {  // Update

            return $this->CI->module_m->save($data, $id);

        }
    }

}
