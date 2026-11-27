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

    $options = "";
    if (is_admin()) {
        $options .= icon_btn('#', 'fa-regular fa-pen-to-square', 'btn-default edit_term_condition', ['data-id' => $aRow['id']]);
    }

    if (is_admin()) {
        $options .= icon_btn('#', 'fa-solid fa-xmark', 'btn-danger delete_term_condition', ['data-id' => $aRow['id']]);
    }

    $row[] = $options;

    $row['DT_RowClass'] = 'has-row-options';
    $output['aaData'][] = $row;
}
