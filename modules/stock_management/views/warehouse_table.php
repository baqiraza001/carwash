<?php
defined('BASEPATH') or exit('No direct script access allowed');
$aColumns = [
    'id',
    'name',
    

];

$sIndexColumn = 'id';
$sTable = db_prefix() . '_sm_warehouses';

$where = [];
$join = [];

$result = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, ['description','address','isdefault']);
$output = $result['output'];
$rResult = $result['rResult'];

foreach ($rResult as $aRow) {


    $row = [];
    $row[] = $aRow['id'];

    $row[] = $aRow['name'].'<br><div class="row-options"><a href="#" data-warehouseid="'.$aRow['id'].'" data-warehousename="'.$aRow['name'].'" data-warehousedescription="'.$aRow['description'].'"  data-warehouseaddress="'.$aRow['address'].'" data-toggle="modal" data-target="#warehouse_modal_edit" class="warehouse_edit">Edit</a> | <a href="'.admin_url('stock_management/manage_settings/warehouse_delete/').$aRow['id'].'" class="text-danger _delete">Delete</a></div>';
    $options = '';
    if ($aRow['isdefault'] == 0) {
        $options = icon_btn('stock_management/stock_products/make_default_warehouse/' . $aRow['id'], 'fa-regular fa-star', 'btn-info', [
            'data-toggle' => 'tooltip',
            'title'       => _l('stock_management_make_default_warehouse'),
            ]);
    }
    $row[] = $options;
    $row['DT_RowClass'] = 'has-row-options';
    $output['aaData'][] = $row;
}
