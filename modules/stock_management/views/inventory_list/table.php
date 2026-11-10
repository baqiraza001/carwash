<?php
// VERSION: 2026-11-10 09:15 PKT - Hidden checkin ID column first so the inventory list sorts newest first
defined('BASEPATH') or exit('No direct script access allowed');

$CI =& get_instance();

$checkins = db_prefix() . '_sm_checkins';
$items = db_prefix() . 'items';
$suppliers = db_prefix() . '_sm_suppliers';

$aColumns = [
    $checkins . '.id',
    $items . '.description as description',
    $suppliers . '.company as company',
    $checkins . '.purchase_price as glass_price',
    '(' . $checkins . '.purchase_price * ' . $checkins . '.units) as inventory_value',
    $checkins . '.units as quantity',
    $checkins . '.note as note',
];

$sIndexColumn = 'id';
$sTable = $checkins;
$remaining_at_most = $CI->input->post('remaining_items');
$remaining_at_least = $CI->input->post('remaining_items_greater_than');
$minimum_value = $CI->input->post('inventory_value');

$where = [];
$low_stock_filter = $CI->input->post('sm_stock_filter');
if ($low_stock_filter === '2' || $low_stock_filter === 2) {
    $where[] = 'AND ' . $checkins . '.units <= ' . max(0, (int) get_option('sm_low_stock'));
}
if ($remaining_at_most !== null && $remaining_at_most !== '' && preg_match('/^\d+$/', (string) $remaining_at_most)) {
    $where[] = 'AND ' . $checkins . '.units <= ' . (int) $remaining_at_most;
}
if ($remaining_at_least !== null && $remaining_at_least !== '' && preg_match('/^\d+$/', (string) $remaining_at_least)) {
    $where[] = 'AND ' . $checkins . '.units >= ' . (int) $remaining_at_least;
}
if ($minimum_value !== null && $minimum_value !== '' && is_numeric($minimum_value) && (float) $minimum_value >= 0) {
    $where[] = 'AND (' . $checkins . '.purchase_price * ' . $checkins . '.units) >= ' . (float) $minimum_value;
}

$join = [
    'LEFT JOIN ' . $items . ' ON ' . $items . '.id = ' . $checkins . '.item',
    'LEFT JOIN ' . $suppliers . ' ON ' . $suppliers . '.id = ' . $checkins . '.supplier',
];
$extra_columns = [
    $checkins . '.id as checkin_id',
    $checkins . '.item as item_id',
    $checkins . '.supplier as supplier_id',
];

$result = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, $extra_columns);
$output = $result['output'];
$rResult = $result['rResult'];

foreach ($rResult as $aRow) {
    $glass_price = (float) $aRow['glass_price'];
    $quantity = (int) $aRow['quantity'];
    $inventory_value = (float) $aRow['inventory_value'];
    $note = (string) $aRow['note'];
    $row = [];
    $row[] = (int) $aRow[ $checkins . '.id']; // Hidden ID column - newest first (default sort).
    $row[] = html_escape($aRow['description']);
    $row[] = html_escape($aRow['company']);
    $row[] = app_format_money($glass_price, get_base_currency());
    $row[] = app_format_money($inventory_value, get_base_currency());
    $row[] = $quantity;
    $row[] = nl2br(html_escape($note));

    $actions = '';
    if (staff_can('edit', 'stock_management')) {
        $actions .= '<button type="button" class="btn btn-default btn-icon inventory-edit" title="Edit"'
            . ' data-checkin-id="' . (int) $aRow['checkin_id'] . '"'
            . ' data-item-id="' . (int) $aRow['item_id'] . '"'
            . ' data-supplier-id="' . (int) $aRow['supplier_id'] . '"'
            . ' data-glass-price="' . htmlspecialchars((string) $glass_price, ENT_QUOTES, 'UTF-8') . '"'
            . ' data-quantity="' . $quantity . '"'
            . ' data-note="' . htmlspecialchars($note, ENT_QUOTES, 'UTF-8') . '">'
            . '<i class="fa-solid fa-pencil" aria-hidden="true"></i></button> ';
    }
    if (staff_can('delete', 'stock_management')) {
        $actions .= '<button type="button" class="btn btn-danger btn-icon inventory-delete" title="Delete" data-checkin-id="'
            . (int) $aRow['checkin_id'] . '"><i class="fa-regular fa-trash-can" aria-hidden="true"></i></button>';
    }
    $row[] = $actions;
    $output['aaData'][] = $row;
}

