<?php
defined('BASEPATH') or exit('No direct script access allowed');
$CI =& get_instance();
$aColumns = [
    'tbl_sm_checkouts.id',
    'tblitems.description',
    'tbl_sm_checkouts.reference',
    'tbl_sm_suppliers.company',
    'tbl_sm_checkouts.units',
    'tbl_sm_checkouts.date',
];

$sIndexColumn = 'id';
$sTable       ='tbl_sm_checkouts';

$where= [];
$join = ['LEFT JOIN tblitems ON tbl_sm_checkouts.item = tblitems.id'];
array_push($join , 'LEFT JOIN tbl_sm_suppliers ON tbl_sm_suppliers.id = tbl_sm_checkouts.customer');

$result = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, []);
$output  = $result['output'];
$rResult = $result['rResult'];

foreach ($rResult as $aRow) {

    $row = [];
    $row[] = '<a href="'.admin_url('stock_management/checkout/create/'.$aRow['tbl_sm_checkouts.id']).'">'.$aRow['tbl_sm_checkouts.reference'].'</a>';
    $product_title = $aRow['tblitems.description'];
    $row[] = $product_title;
    $row[] = $aRow['tbl_sm_suppliers.company'];
    $row[] = $aRow['tbl_sm_checkouts.units'];
    $row[] = _d($aRow['tbl_sm_checkouts.date']);


    $options = "";
    if ( staff_can('edit', 'stock_management') || is_admin() ) {
        $options .= icon_btn('stock_management/checkout/create/'.$aRow['tbl_sm_checkouts.id'], 'fa-regular fa-pen-to-square');
    }

    if ( staff_can('delete', 'stock_management')  || is_admin() ) {
        $options .= icon_btn('stock_management/checkout/delete/'.$aRow['tbl_sm_checkouts.id'] , 'fa-solid fa-xmark', 'btn-danger _delete');
    }

    $row[] = $options;

    $row['DT_RowClass'] = 'has-row-options';
    $output['aaData'][] = $row;
}
