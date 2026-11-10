<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Referrals_model extends App_Model
{
    function __construct()
    {
        parent::__construct();
    }

    public function get_client_source($client_id){
        return $this->db->where('userid' , $client_id)->get('tblclients')->row('source');
    }

    public function get_client_admin($client_id){
        $this->db->where('customer_id',$client_id);
        $this->db->order_by('staff_id', 'ASC');
        $this->db->limit(1);
        return $this->db->get(db_prefix() . 'customer_admins')->row('staff_id');
    }
}



