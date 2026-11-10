<?php
defined('BASEPATH') or exit('No direct script access allowed');
$aColumns = [
    'tbl_sm_suppliers.id',
    'tbl_sm_suppliers.company',
    'tbl_sm_suppliers.phone',
    'tbl_sm_suppliers.website',
    'tblcountries.short_name'
];

$sIndexColumn = 'id';
$sTable = db_prefix() . '_sm_suppliers';

$where = ['AND tbl_sm_suppliers.is_deleted = 0'];
$join = [];
$join = ['LEFT JOIN tblcountries ON tblcountries.country_id = tbl_sm_suppliers.country'];


$result = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, ['is_draft']);
$output = $result['output'];
$rResult = $result['rResult'];

foreach ($rResult as $aRow) {

    $row = [];
    $data = ucwords($aRow['tbl_sm_suppliers.company']);
    if ($aRow['is_draft'] == 1) {
        $data .= '<br/><span class="label label inline-block text-warning" style="border:1px solid #ff6f00;font-size:10px;">'._l('draft').'</span>';
    }
    $row[] = $data;
    $row[] = $aRow['tbl_sm_suppliers.phone'];
    $row[] = $aRow['tbl_sm_suppliers.website'];
    $row[] = $aRow['tblcountries.short_name'];

    $options = "";
    if (staff_can('edit', 'stock_management') || is_admin()) {
        $options .= icon_btn('stock_management/suppliers/create/' . $aRow['tbl_sm_suppliers.id'], 'fa-regular fa-pen-to-square');
    }

    if (staff_can('delete', 'stock_management') || is_admin()) {
        $options .= icon_btn('stock_management/suppliers/delete/' . $aRow['tbl_sm_suppliers.id'], 'fa-solid fa-xmark', 'btn-danger _delete');
    }

    $row[] = $options;

    $row['DT_RowClass'] = 'has-row-options';
    $output['aaData'][] = $row;
}
