<?php
defined('BASEPATH') or exit('No direct script access allowed');
$aColumns = [
    'tbl_sm_products.id',
    'tbl_sm_products.name',
    'tbl_sm_categories.name',
    'tbl_sm_products.price',
    'tbl_sm_products.wholesale_price',
    'tbl_sm_products.image',
];

$sIndexColumn = 'id';
$sTable       ='tbl_sm_products';

$join = [];
$where = [];

array_push($join , 'LEFT JOIN tbl_sm_categories ON tbl_sm_categories.id = tbl_sm_products.category');

$result = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, ['tbl_sm_products.image_name']);
$output  = $result['output'];
$rResult = $result['rResult'];

$CI = &get_instance();
$CI->load->model('currencies_model');
$currency = $CI->currencies_model->get_base_currency();
$currency_symbol = $currency->symbol;
foreach ($rResult as $aRow) {

    $row = [];
    $row[] = $aRow['tbl_sm_products.name'];
    $row[] = $aRow['tbl_sm_categories.name'];
    $row[] = $currency_symbol.$aRow['tbl_sm_products.price'];
    $row[] = $currency_symbol.$aRow['tbl_sm_products.wholesale_price'];
    $image = '';
    if(isset($aRow['tbl_sm_products.image']) && !empty($aRow['tbl_sm_products.image'])){
       $image = '<a href="'.$aRow['tbl_sm_products.image'].'" data-lightbox="image_preview" target="_blank"><i class="fa-regular fa-image fa-3x" aria-hidden="true"></i></a>'; 
    }
    $row[] = $image;
    $options = "";
    if ( staff_can('edit', 'stock_management') || is_admin() ) {
        $options .= icon_btn('stock_management/stock_products/create/'.$aRow['tbl_sm_products.id'], 'fa-regular fa-pen-to-square');
    }

    if ( staff_can('delete', 'stock_management')  || is_admin() ) {
        $options .= icon_btn('stock_management/stock_products/delete/'.$aRow['tbl_sm_products.id'] , 'fa-solid fa-xmark', 'btn-danger _delete');
    }

    $row[] = $options;

    $row['DT_RowClass'] = 'has-row-options';
    $output['aaData'][] = $row;
}
