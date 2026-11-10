<?php

defined('BASEPATH') or exit('No direct script access allowed');

$aColumns = [
    'tbl_sm_checkins.id',
    'tblitems.description',
    'tbl_sm_checkins.reference',
    'tbl_sm_suppliers.company',
    'tbl_sm_checkins.units',
    'tbl_sm_warehouses.name',
    'tbl_sm_checkins.date'
];

$sIndexColumn = 'id';
$sTable       ='tbl_sm_checkins';
$where = [];
$CI = &get_instance();
$filters = $CI->input->post();
$total = (isset($filters['total']) ? $filters['total'] : '' );
if(isset($filters) && count($filters) > 0)
{
    if($total != '') {}
}

if(isset($filters['report_from']) && !empty($filters['report_from'])){
    if(isset($filters['report_to']) && !empty($filters['report_to'])){
        array_push($where," AND tbl_sm_checkins.date BETWEEN '".$filters['report_from']."' AND '".$filters['report_to']."'");
    } else {
        array_push($where," AND tbl_sm_checkins.date = '".$filters['report_from']."'");
    }
}

$join = ['LEFT JOIN tblitems ON tbl_sm_checkins.item = tblitems.id'];
array_push($join , 'LEFT JOIN tbl_sm_suppliers ON tbl_sm_suppliers.id = tbl_sm_checkins.supplier');
array_push($join , 'LEFT JOIN tbl_sm_warehouses ON tbl_sm_warehouses.id = tbl_sm_checkins.warehouse');

$result = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, []);

$output  = $result['output'];

$rResult = $result['rResult'];

foreach ($rResult as $aRow) {

    $row = [];
    $row[] = '<a href="'.admin_url('stock_management/checkin/create/'.$aRow['tbl_sm_checkins.id']).'">'.$aRow['tbl_sm_checkins.reference'].'</a>';
    $product_title = $aRow['tblitems.description'];
    $row[] = $product_title;
    $row[] = $aRow['tbl_sm_suppliers.company'];
    $row[] = $aRow['tbl_sm_checkins.units'];
    $row[] = $aRow['tbl_sm_warehouses.name'];
    $row[] = _d($aRow['tbl_sm_checkins.date']);

    $row['DT_RowClass'] = 'has-row-options';
    $output['aaData'][] = $row;
}