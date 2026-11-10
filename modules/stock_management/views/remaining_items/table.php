<?php
defined('BASEPATH') or exit('No direct script access allowed');
$CI =& get_instance();
$aColumns = [
    'name',
    'tblitems.description',
    'quantity',
];

$sIndexColumn = 'id';
$sTable       = db_prefix() . '_sm_warehouse_products';

$where= [];
$join = [
    'LEFT JOIN tbl_sm_warehouses ON tbl_sm_warehouses.id = tbl_sm_warehouse_products.warehouse_id',
    'LEFT JOIN tblitems ON tblitems.id = tbl_sm_warehouse_products.product_id',
];

$result = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, []);

$output  = $result['output'];
$rResult = $result['rResult'];
foreach ($rResult as $aRow) {

    $row = [];
    $row[] = $aRow['name'];
    $row[] = $aRow['tblitems.description'];
    $row[] = $aRow['quantity'];

    $output['aaData'][] = $row;
}
