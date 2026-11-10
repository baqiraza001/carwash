<?php
defined('BASEPATH') or exit('No direct script access allowed');
$custom_fields = get_table_custom_fields('assets');
$aColumns = [
    'tbl_sm_adjustments.id',
    'tbl_sm_warehouses.name',
    'tbl_sm_products.name',
    'tbl_sm_adjustments.quantity',
    'tbl_sm_adjustments.date',
];

$sIndexColumn = 'id';
$sTable = db_prefix() . '_sm_adjustments';
$CI = &get_instance();
$where = [];
$join = ['LEFT JOIN tbl_sm_products ON tbl_sm_products.id = tbl_sm_adjustments.product',
               'LEFT JOIN tbl_sm_warehouses ON tbl_sm_warehouses.id = tbl_sm_adjustments.warehouse' ];

$result = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where);
$output = $result['output'];
$rResult = $result['rResult'];

foreach ($rResult as $aRow) {

    $row = [];
    
    $row[] = $aRow['tbl_sm_warehouses.name'];
    $row[] = $aRow['tbl_sm_products.name'];
    $row[] = $aRow['tbl_sm_adjustments.quantity'];
    $row[] = (isset($aRow['tbl_sm_adjustments.date']) && $aRow['tbl_sm_adjustments.date'] != '0000-00-00' ? _d($aRow['tbl_sm_adjustments.date']) : '--/--/----');

    $options = "";
    $options .= icon_btn(admin_url('stock_management/stock_adjustment/create/'.$aRow['tbl_sm_adjustments.id']), 'fa-regular fa-pen-to-square');
    $options .= icon_btn(admin_url('stock_management/stock_adjustment/delete/'.$aRow['tbl_sm_adjustments.id']), 'fa-solid fa-xmark', 'btn-danger _delete');
    $row[] = $options;

    $row['DT_RowClass'] = 'has-row-options';
    $output['aaData'][] = $row;
}
