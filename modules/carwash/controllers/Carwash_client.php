<?php
defined('BASEPATH') or exit('No direct script access allowed');
require APP_MODULES_PATH.'carwash/vendor/autoload.php';

use Sunrise\Vin\Vin;

class Carwash_client extends ClientsController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('carwash_model');
    }

    public function index()
    {
        if (is_client_logged_in()) {
            $this->db->where('clientid',$_SESSION['client_user_id']);
            $data['vehicles'] =  $this->db->get('tbl_client_vehicles')->result_array();
            $data['title']     = _l('carwash_vehicles');
            $this->data($data);
            $this->view('carwash/client/manage');
            $this->layout();
        } else {
            redirect('/');
        }
    }
    
    public function create()
    {
        $posted_data = $this->input->post();
        $data['vehicle'] = $posted_data['vehicle'];
        $data['vin'] = $posted_data['vin'];
        $data['plate_no'] = $posted_data['plate_no'];
        $data['clientid'] = $posted_data['clientid'];
        // $data['clientid'] = $_SESSION['client_user_id'];

        if(isset($posted_data['id']) && !empty($posted_data['id'])){
            $this->db->where('id' , $posted_data['id']);
            $this->db->update('tbl_client_vehicles' , $data);
            if($this->db->affected_rows() > 0){
                echo json_encode(array('message' => _l('carwash_updated_successfully'), 'status' => 'success'));
            }else{
                echo json_encode(array('message' => _l('carwash_problem_in_updating'), 'status' => 'danger'));
            }
            
        }else{
            $this->db->insert('tbl_client_vehicles' , $data);
            $inserted = $this->db->insert_id();
            $vehicle = $this->db->where('id', $inserted)->get('tbl_client_vehicles')->row();
            if ($inserted) {
                echo json_encode(array('message' => _l('carwash_added_successfully'), 'status' => 'success', 'vehicle' => $vehicle));
            }else{
                echo json_encode(array('message' => _l('carwash_problem_in_adding'), 'status' => 'danger'));
            }
        }
        exit();
    }

    /* Delete record from database */
    public function delete()
    {   
        $id = $this->input->post('id');
        $response = $this->db->where('id' , $id)->delete('tbl_client_vehicles');
        if ($response == true) {
            echo json_encode(array('message' => _l('carwash_deleted_successfully'), 'status' => 'success'));
        } else {
            echo json_encode(array('message' => _l('carwash_problem_in_deleting'), 'status' => 'danger'));
        }
        exit();
    }

    public function get_vehicle_data(){
        $id = $this->input->post('id');
        $data = $this->db->where('id' , $id)->get('tbl_client_vehicles')->row_array();
        echo json_encode($data);
        exit();
    }

    public function search_vin_data(){
        $search_vin = $this->input->post('search_vin');
        $data['error'] = false;

        try {
            $vin = new Vin($search_vin);
           $data['vin'] = $vin->getVin();
           $data['wmi'] = $vin->getWmi();
           $data['vds'] = $vin->getVds();
           $data['vis'] = $vin->getVis();
           $data['region'] = $vin->getRegion();
           $data['country'] = $vin->getCountry();
           $data['manufacturer'] = $vin->getManufacturer();
           $data['model_year'] = $vin->getModelYear();
        } catch (Exception $e) {
           $data['error'] = $e->getMessage();
       }

       echo json_encode($data);
       exit();
   }

}
