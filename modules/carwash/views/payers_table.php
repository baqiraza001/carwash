<?php
defined('BASEPATH') or exit('No direct script access allowed');
$aColumns = [
    'id',
    'client_id',
    'created_on',
];

$sIndexColumn = 'id';
$sTable = db_prefix() . '_carwash_payers';

$where = [];
$query = '';

$join = [];

if(isset($_GET['invoice_id']) && !empty($_GET['invoice_id'])){
    array_push($where,'AND invoice_id='.$_GET['invoice_id']);
} 
else if(isset($_GET['estimate_id']) && !empty($_GET['estimate_id'])){
    array_push($where,'AND estimate_id='.$_GET['estimate_id']);
} 
else if(isset($_GET['payer_ids']) && !empty($_GET['payer_ids'])){
    $payer_ids= json_decode($_GET['payer_ids']);
    if(!empty($payer_ids)){
        array_push($where,'AND id IN (' . implode(',', $payer_ids) .')' );
    }
    else
        array_push($where,'AND invoice_id=1230303003030303');
}
else
    array_push($where,'AND invoice_id=1230303003030303');

$result = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, []);
$output = $result['output'];
$rResult = $result['rResult'];

foreach ($rResult as $aRow) {

    $row = [];

    $row[] = $aRow['id'];
    $row[] = get_relation_values(get_relation_data('customer',$aRow['client_id']),'customer')['name'];
    // $row[] = implode(',', json_decode($aRow['carwash_payer_options']));
    $row[] = date("jS M y",strtotime($aRow['created_on']));

    $actions = '';
    
//    $actions .= '<a href="#" onclick="carwash_edit_payer('.$aRow['id'].'); return false;" class="btn btn-primary btn-icon edit_carwash_payer" data-id = "'.$aRow['id'].'" type="button">';
  //  $actions .= '<a href="#" onclick="delete_carwash_payer('.$aRow['id'].'); return false;" class="btn btn-danger btn-icon delete_carwash_payer" data-id = "'.$aRow['id'].'" type="button">';

    $actions .= '<a href="#" class="btn btn-primary btn-icon edit_carwash_payer" data-id = "'.$aRow['id'].'" type="button">
    <i class="fa-regular fa-pen-to-square"></i></a>';
    $actions .= '<a href="#" class="btn btn-danger btn-icon delete_carwash_payer" data-id = "'.$aRow['id'].'" type="button">

    <i class="fa-solid fa-xmark"></i></a>';
    $row[] = $actions; 
    $row['DT_RowClass'] = 'has-row-options';
    $output['aaData'][] = $row;
}
