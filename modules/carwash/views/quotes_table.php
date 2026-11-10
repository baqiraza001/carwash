<?php
defined('BASEPATH') or exit('No direct script access allowed');
$aColumns = [
    'autokey',
    'company',
    'vehmake',
    'vehmodel',
    'vehyear',
    'vin',
    'mileage',
    'vendor',
    'custtotal',
    'quotedate'
];

$sIndexColumn = 'autokey';
$sTable = 'quotes';

$where = [];
$query = '';

$join = [];
$CI = &get_instance();

$first_name = !empty($CI->input->get('first_name')) ? $CI->input->get('first_name') : '';
$last_name = !empty($CI->input->get('last_name')) ? $CI->input->get('last_name') : '';
$clientid = !empty($CI->input->get('clientid')) ? $CI->input->get('clientid') : '';
$is_estimate = !empty($CI->input->get('is_estimate')) ? $CI->input->get('is_estimate') : '';

if(isset($first_name) && !empty($first_name)){
    array_push($where,"AND insfirst='$first_name'");
} 
if(isset($last_name) && !empty($last_name)){
    array_push($where,"AND inslast='$last_name'");
}
if(empty($first_name) && empty($last_name))
    array_push($where,'AND quote=1230303003030303');


$CI->db = $CI->load->database('carwash_db', TRUE);
$result = carwash_data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, ['quote']);
$output = $result['output'];
$rResult = $result['rResult'];

foreach ($rResult as $aRow) {

    $row = [];
    $row[] = $aRow['company'];
    $row[] = $aRow['vehmake'];
    $row[] = $aRow['vehmodel'];
    $row[] = $aRow['vehyear'];
    $row[] = $aRow['vin'];
    $row[] = $aRow['mileage'];
    $row[] = $aRow['vendor'];
    $row[] = $aRow['custtotal'];
    $row[] = date("jS M Y",strtotime($aRow['quotedate']));
    
    $vehicle_name = $aRow['vehmake'] . " " . $aRow['vehmodel'] . ", " . $aRow['vehyear'];
    $vehicle_vin = $aRow['vin'];
    $vehicle_function = "carwash_create_vehicle_from_quote('$vehicle_vin', '$vehicle_name'); return false";
    
    $actions = '';
    $actions .= '<a href="#" onclick="carwash_view_quote('.$aRow['quote'].'); return false;" class="btn btn-primary btn-icon " data-id = "'.$aRow['quote'].'" type="button"><i class="fa-regular fa-eye"></i></a>';
    $actions .= '<a href="#" onclick="'.$vehicle_function.'" class="btn btn-primary btn-icon" type="button"><i class="fa-solid fa-car"></i></a>';
    $row[] = $actions; 
    $row['DT_RowClass'] = 'has-row-options';
    $output['aaData'][] = $row;
}


/* crm estimates of client */
$CI->db = $CI->load->database('default', TRUE);
if($is_estimate)
    $tbl_name = 'tblestimates';
else
    $tbl_name = 'tblinvoices';
    
$crm_result = $CI->db->select('id, clientid, total as custtotal, DATE(datecreated) as quotedate')->where('clientid', $clientid)->order_by('datecreated', 'desc')->get($tbl_name)->result_array();
$total_crm_result = count($crm_result);
$output['iTotalRecords'] = $output['iTotalRecords']+$total_crm_result;
$output['iTotalDisplayRecords'] = $output['iTotalDisplayRecords']+$total_crm_result;

foreach ($crm_result as $aRow) {
    $row = [];
    $row[] = get_relation_values(get_relation_data('customer',$aRow['clientid']),'customer')['name'];
    $row[] = '';
    $row[] = '';
    $row[] = get_custom_field_value($aRow['id'], 42, 'estimate');
    $row[] = get_custom_field_value($aRow['id'], 19, 'estimate');
    $row[] = get_custom_field_value($aRow['id'], 18, 'estimate');
    $row[] = '';
    $row[] = $aRow['custtotal'];
    $row[] = date("jS M Y",strtotime($aRow['quotedate']));
    
    $vehicle_name = get_custom_field_value($aRow['id'], 42, 'estimate');
    $vehicle_vin = get_custom_field_value($aRow['id'], 19, 'estimate');
    $vehicle_function = "carwash_create_vehicle_from_quote('$vehicle_vin', '$vehicle_name'); return false";
    
    $id = $aRow['id'];
    $carwash_view_quote = "carwash_view_quote($id, 'crm'); return false";
    
    $actions = '';
    $actions .= '<a href="#" onclick="'.$carwash_view_quote.'" class="btn btn-primary btn-icon " data-id = "'.$aRow['id'].'" type="button"><i class="fa-regular fa-eye"></i></a>';
    $actions .= '<a href="#" onclick="'.$vehicle_function.'" class="btn btn-primary btn-icon" type="button"><i class="fa-solid fa-car"></i></a>';
    $row[] = $actions; 
    $row['DT_RowClass'] = 'has-row-options';
    
    array_unshift($output['aaData'],$row);
}
// echo '<pre>'; var_dump($crm_result);die;
/* crm estimates of client */
