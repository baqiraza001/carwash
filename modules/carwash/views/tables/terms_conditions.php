<?php

defined('BASEPATH') or exit('No direct script access allowed');

$aColumns = [
    'id',
    'title',
    'description',
    'created_on',
];

$sIndexColumn = 'id';
$sTable = db_prefix() . '_carwash_terms_conditions';

$where = [];
$join = [];

$result = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, []);
$output = $result['output'];
$rResult = $result['rResult'];

foreach ($rResult as $aRow) {
    $row = [];

    $row[] = $aRow['title'];
    $row[] = $aRow['description'];
    $row[] = !empty($aRow['created_on']) ? _dt($aRow['created_on']) : '-';

    $actions = '';
    $actions .= '<a href="#" class="btn btn-primary btn-icon edit_term_condition" data-id="' . $aRow['id'] . '" type="button"><i class="fa-regular fa-pen-to-square"></i></a>';
    $actions .= '<a href="#" class="btn btn-danger btn-icon delete_term_condition" data-id="' . $aRow['id'] . '" type="button"><i class="fa-solid fa-xmark"></i></a>';

    $row[] = $actions;

    $row['DT_RowClass'] = 'has-row-options';
    $output['aaData'][] = $row;
}
