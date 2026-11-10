<?php
defined('BASEPATH') or exit('No direct script access allowed');
$aColumns = [
    'id',
    'name',

];

$sIndexColumn = 'id';
$sTable = db_prefix() . '_sm_categories';

$where = [];
$join = [];

$result = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, ['description']);
$output = $result['output'];
$rResult = $result['rResult'];

foreach ($rResult as $aRow) {


    $row = [];
    $row[] = $aRow['id'];
    $row[] = $aRow['name'].'<br><div class="row-options"><a href="#" data-categoryid="'.$aRow['id'].'" data-categoryname="'.$aRow['name'].'" data-categorydescription="'.$aRow['description'].'" data-toggle="modal" data-target="#category_modal_edit" class="category_edit">Edit</a> | <a href="'.admin_url('stock_management/manage_settings/category_delete/').$aRow['id'].'" class="text-danger _delete">Delete</a></div>';
    $row['DT_RowClass'] = 'has-row-options';
    $output['aaData'][] = $row;
}
