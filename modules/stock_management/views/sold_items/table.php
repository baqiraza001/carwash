<?php
defined('BASEPATH') or exit('No direct script access allowed');
$CI =& get_instance();
$aColumns = [
    'description',
    'qty',
    'rate',
    'tblinvoices.date',
];

$sIndexColumn = 'id';
$sTable       = db_prefix() . 'itemable';

$where= [];
$join = [
    'LEFT JOIN tblinvoices ON tblinvoices.id = tblitemable.rel_id',
    'LEFT JOIN ' . db_prefix() . 'currencies ON ' . db_prefix() . 'currencies.id = ' . db_prefix() . 'invoices.currency',
];
array_push($where, ' where rel_type = "invoice"');

$result = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, ['tblinvoices.id as invoice_id', db_prefix(). 'currencies.name as currency_name']);

$output  = $result['output'];
$rResult = $result['rResult'];
foreach ($rResult as $aRow) {

    $row = [];
    $row[] = '<a target="_blank" href="' . admin_url('invoices#' . $aRow['invoice_id']) . '">' . format_invoice_number($aRow['invoice_id']) . '</a>';
    $row[] = $aRow['description'];
    $row[] = $aRow['qty'];
    $row[] = app_format_money($aRow['rate'], $aRow['currency_name']);
    $row[] = _d($aRow['tblinvoices.date']);


    $output['aaData'][] = $row;
}
