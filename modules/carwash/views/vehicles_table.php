<?php
defined('BASEPATH') or exit('No direct script access allowed');
$custom_fields = get_table_custom_fields('assets');
$aColumns = [
    'id',
    'vehicle',
    'vin',
    'plate_no'
];

$sIndexColumn = 'id';
$sTable = db_prefix() . '_client_vehicles';
$CI = &get_instance();
$where = [];
$join = [];

if(isset($_GET['clientid']) && !empty($_GET['clientid'])){
    array_push($where , 'AND clientid = '.$_GET['clientid']);
}

$result = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, []);
$output = $result['output'];
$rResult = $result['rResult'];

foreach ($rResult as $aRow) {

    $row = [];
    
    $row[] = $aRow['vehicle'];
    $row[] = $aRow['vin'];
    $row[] = $aRow['plate_no'];

    $options = "";
    $options .= '<button class="btn btn-primary carwash_edit_vehicle  btn-icon" data-id="'.$aRow['id'].'" data-vehicle="'.$aRow['vehicle'].'" data-vin="'.$aRow['vin'].'" data-plate_no="'.$aRow['plate_no'].'"><i class="fa-regular fa-pen-to-square"></i></button>';
    $options .= '<button class="btn btn-danger delete_vehicle  btn-icon" data-id="'.$aRow['id'].'"><i class="fa-solid fa-xmark"></i></button>';
    $row[] = $options;

    $row['DT_RowClass'] = 'has-row-options';
    $output['aaData'][] = $row;
}
