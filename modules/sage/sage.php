<?php

defined('BASEPATH') or exit('No direct script access allowed');

/*
Module Name: Sage Integration Module
Description: Complete Sage integration, to sync all the payments and invoices with Sage
Version: 1.0
Requires at least: 2.3.*
*/

define('SAGE_MODULE_NAME', 'sage');
$CI = &get_instance();

/**
* Register activation module hook
*/
register_activation_hook(SAGE_MODULE_NAME, 'sage_module_activation_hook');

function sage_module_activation_hook()
{
    $CI = &get_instance();
    require_once(__DIR__ . '/install.php');
    require_once(__DIR__ . '/libraries/SageAPIWrapper.php');
}

hooks()->add_action('admin_init', 'sage_module_init_menu_items');

hooks()->add_action('after_invoice_added','sync_invoice_data_with_sage', 10);

hooks()->add_action('before_payment_recorded','sage_create_and_update_payment', 10);//

/**
* Register language files, must be registered if the module is using languages
*/
register_language_files(SAGE_MODULE_NAME, [SAGE_MODULE_NAME]);


function sage_module_init_menu_items()
{

	if(has_permission('sage','','view')){
		$CI = &get_instance();

		$CI->app_menu->add_sidebar_menu_item('sage', [
			'name'     => _l('sage'), // The name if the item
			'collapse' => true, // Indicates that this item will have submitems
			'position' => 11, // The menu position
			'icon'     => 'fa fa-dollar', // Font awesome icon
		]);
		
		$CI->app_menu->add_sidebar_children_item('sage', [
			'slug'     => 'sage-config', // Required ID/slug UNIQUE for the child menu
			'name'     => _l('sage_configuration'), // The name if the item
			'href'     => admin_url('sage'),
			'position' => 5,
		]);

    }
}

function sync_invoice_data_with_sage($id){
    $CI = &get_instance();
    $CI->load->library(SAGE_MODULE_NAME . '/' . 'sageAPIWrapper');
    $sageWrapper = new SageAPIWrapper();
    $val = $sageWrapper->createInvoice($id);
    sage_last_insert_invoice_id($val);
    return true;
}

function sage_last_insert_invoice_id($val) {
    $CI = &get_instance();
    $CI->load->library(SAGE_MODULE_NAME . '/' . 'sageAPIWrapper');
    $sageWrapper = new SageAPIWrapper();
    $invoice_array = $val;
    $value = $sageWrapper->updateSageInvoiceWithId($invoice_array['perfex_invoice_id'], $invoice_array['sage_invoice_id'], $invoice_array['data']);
    return $value;
}



function sage_create_and_update_payment($data) {
    $data_start = $data;
    $CI = &get_instance();
    $CI->load->library(SAGE_MODULE_NAME . '/' . 'sageAPIWrapper');
    $sageWrapper = new SageAPIWrapper();
    $sageWrapper->createSageAccountForTransfer($data);
    return $data_start;

}