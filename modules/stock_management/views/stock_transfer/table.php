<?php
defined('BASEPATH') or exit('No direct script access allowed');
$custom_fields = get_table_custom_fields('assets');
$aColumns = [
    'tbl_sm_transfers.id',
    'tw1.name as from_warehouse',
    'tw2.name as to_warehouse',
    'tblitems.description',
    'tbl_sm_transfers.quantity',
    'tbl_sm_transfers.date',
];

$sIndexColumn = 'id';
$sTable = db_prefix() . '_sm_transfers';
$CI = &get_instance();
$where = [];
$join = ['LEFT JOIN tblitems ON tblitems.id = tbl_sm_transfers.product',
               'LEFT JOIN tbl_sm_warehouses tw1 ON tw1.id = tbl_sm_transfers.from_warehouse',
               'LEFT JOIN tbl_sm_warehouses tw2 ON tw2.id = tbl_sm_transfers.to_warehouse' ];

$result = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where);
$output = $result['output'];
$rResult = $result['rResult'];

foreach ($rResult as $aRow) {

    $row = [];
    
    $row[] = $aRow['from_warehouse'];
    $row[] = $aRow['to_warehouse'];
    $row[] = $aRow['tblitems.description'];
    $row[] = $aRow['tbl_sm_transfers.quantity'];
    $row[] = (isset($aRow['tbl_sm_transfers.date']) && $aRow['tbl_sm_transfers.date'] != '0000-00-00' ? _d($aRow['tbl_sm_transfers.date']) : '--/--/----');

    $options = "";
    $options .= icon_btn(admin_url('stock_management/stock_transfer/view/'.$aRow['tbl_sm_transfers.id']), 'fa-regular fa-eye','btn-primary');
    $options .= icon_btn(admin_url('stock_management/stock_transfer/delete/'.$aRow['tbl_sm_transfers.id']), 'fa-solid fa-xmark', 'btn-danger _delete');
    $row[] = $options;

    $row['DT_RowClass'] = 'has-row-options';
    $output['aaData'][] = $row;
}
