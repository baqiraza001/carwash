<?php
defined('BASEPATH') or exit('No direct script access allowed');
// VERSION: 2026-11-11 09:20 PKT - estimate item_id marker hooks (before/after estimate added+updated, estimate converted to invoice)

/*
Module Name: Stock Management
Description: Module created for Stock Management
Version: 2.3.0
Requires at least: 2.3.*
*/

define('STOCK_MANAGMENT_MODULE_NAME', 'stock_management');
$CI = &get_instance();
$CI->load->helper( STOCK_MANAGMENT_MODULE_NAME.'/stock_products');

/**
 * Register activation module hook
 */
register_activation_hook(STOCK_MANAGMENT_MODULE_NAME, 'stock_management_activation_hook');
function stock_management_activation_hook()
{
    $CI = &get_instance();
    require_once(__DIR__ . '/install.php');

    $alreadyInstalled = get_option('stock_management_active');
    if( $alreadyInstalled != "" && $alreadyInstalled != NULL ){
        update_option('stock_management_active', 1);
    } else {
        add_option('stock_management_active', 1);
    }
}
/**
 * Register language files, must be registered if the module is using languages
 */
register_language_files(STOCK_MANAGMENT_MODULE_NAME, [STOCK_MANAGMENT_MODULE_NAME]);
/**
 * Deactivation module hook
 */
register_deactivation_hook(STOCK_MANAGMENT_MODULE_NAME, 'stock_management_deactivation_hook');
function stock_management_deactivation_hook()
{
    update_option('stock_management_active', 0, 1);
}

/**
 * Setup Menu Hook
 */
hooks()->add_action('admin_init', 'stock_management_init_menu_items');
function stock_management_init_menu_items()
{
    $CI = &get_instance();

    /**
     * If the logged in user is administrator, add custom menu in Setup
     */
    if (has_permission('stock_management', '', 'view')) {

        $CI->app_menu->add_sidebar_menu_item('stock_management', [
            'collapse' => true,
            'slug' => 'stock_management',
            'name' => _l('Stock Management'),
            // 'icon' => 'fa fa-archive',
            'href' => admin_url(''),
            'position' => 1,
        ]);
        
        $CI->app_menu->add_sidebar_children_item('stock_management', [
            'slug' => 'invoice_items',
            'name' => _l('Items'),
            'href' => admin_url('invoice_items'),
            'position' => 1,
        ]);
        
        /*$CI->app_menu->add_sidebar_children_item('stock_management', [
            'slug' => 'checkin',
            'name' => _l('Check In'),
            'href' => admin_url('stock_management/checkin'),
            'position' => 12,
        ]);
        $CI->app_menu->add_sidebar_children_item('stock_management', [
            'slug' => 'checkout',
            'name' => _l('Check Out'),
            'href' => admin_url('stock_management/checkout'),
            'position' => 13
        ]);

        $CI->app_menu->add_sidebar_children_item('stock_management', [
            'slug' => 'stock_adjustment',
            'name' => _l('Stock Adjustment'),
            'href' => admin_url('stock_management/stock_adjustment'),
            'position' => 14,
        ]);*/
        
        
        $CI->app_menu->add_sidebar_children_item('stock_management', [
            'slug' => 'suppliers',
            'name' => _l('suppliers'),
            'icon' => '',
            'href' => admin_url('stock_management/suppliers'),
            'position' => 15,
        ]);


        
        /*$CI->app_menu->add_sidebar_children_item('stock_management', [
            'slug' => 'settings',
            'name' => _l('Settings'),
            'href' => admin_url('stock_management/manage_settings'),
            'position' => 16,
        ]);*/
        
         $CI->app_menu->add_sidebar_children_item('stock_management', [
            'slug' => 'inventory_list',
            'name' => _l('Inventory List'),
            'href' => admin_url('stock_management/stock_products/inventory_list'),
            'position' => 17,
        ]);
    }
}

hooks()->add_action('admin_init', 'stock_management_permissions');
function stock_management_permissions()
{
    $capabilities = [];
    $capabilities['capabilities'] = [
        'view'   => _l('permission_view') . '(' . _l('permission_global') . ')',
        'create' => _l('permission_create'),
        'edit'   => _l('permission_edit'),
        'delete' => _l('permission_delete'),
    ];

    register_staff_capabilities('stock_management', $capabilities, _l('stock_management'));
}

hooks()->add_action('app_admin_head', 'stock_management_add_head_components');
hooks()->add_action('app_admin_footer', 'stock_management_load_js');

function stock_management_add_head_components(){
    if (get_option('stock_management_active') == '1') {
        $CI = &get_instance();
        echo '<link href="' . base_url('modules/stock_management/assets/css/stock_management_styles.css') .'?v=' . $CI->app_scripts->core_version(). '"  rel="stylesheet" type="text/css" />';
    }
}

function stock_management_load_js(){
    if (get_option('stock_management_active') == '1') {
        $CI = &get_instance();
        echo '<script src="'.module_dir_url('stock_management', 'assets/js/stock_management_js.js').'?v=' . $CI->app_scripts->core_version().'"></script>';
        echo '<script src="'.module_dir_url('stock_management', 'assets/js/invoice_items_custom.js').'?v=' . $CI->app_scripts->core_version().'"></script>';
        echo '<script src="'.module_dir_url('stock_management', 'assets/js/stock_products_js.js').'?v=' . $CI->app_scripts->core_version().'"></script>';
    }
}


hooks()->add_filter('before_invoice_added', 'stock_management_before_invoice_added');
function stock_management_before_invoice_added($data){
    // Sirf tagging - deduction ab after_invoice_added mein hoti hai.
    $items = $data['items'] ?? [];
    foreach($items as $key => $item){
        $data['items'][$key] = stock_management_tag_item_description($item);
    }

    return $data;
}

hooks()->add_action('after_invoice_added', 'stock_management_after_invoice_added');
function stock_management_after_invoice_added($invoice_id){
    $CI = &get_instance();

    $invoice = $CI->db->select('stock_supplier')->where('id', $invoice_id)->get(db_prefix() . 'invoices')->row_array();
    $stock_supplier = $invoice['stock_supplier'] ?? null;
    if (empty($stock_supplier)) {
        return;
    }

    // Pehle [id_N] marker se item_id wapas layein (core item_id nahi likhta).
    stock_management_fix_item_ids_from_descriptions($invoice_id, 'invoice');

    $items = $CI->db->where('rel_id', $invoice_id)->where('rel_type', 'invoice')->order_by('item_order', 'asc')->order_by('id', 'asc')->get(db_prefix() . 'itemable')->result_array();

    foreach($items as $item){
        $item_id = (int) ($item['item_id'] ?? 0);
        $item_qty = (float) ($item['qty'] ?? 0);
        if ($item_id < 1) {
            continue;
        }

        $data_exists = $CI->db->select('units')->where('item' , $item_id)->where('supplier' , $stock_supplier)->get('tbl_sm_checkins')->row_array();

        if(isset($data_exists) && !empty($data_exists)){
            $existing_quantity = $data_exists['units'];
            $updated_quantity = $existing_quantity - $item_qty;
            $CI->db->set('units',$updated_quantity)->where('item' , $item_id)->where('supplier' , $stock_supplier)->update('tbl_sm_checkins');
        }
    }
}


hooks()->add_filter('before_estimate_added', 'stock_management_before_estimate_added');

function stock_management_before_estimate_added($data){
    $items = $data['items'] ?? [];

    foreach($items as $key => $item){
        $data['items'][$key] = stock_management_tag_item_description($item);
    }

    return $data;
}

hooks()->add_action('after_estimate_added', 'stock_management_after_estimate_added');

function stock_management_after_estimate_added($estimate_id){
    stock_management_fix_item_ids_from_descriptions($estimate_id, 'estimate');
}

hooks()->add_filter('before_estimate_updated', 'stock_management_before_estimate_updated');

function stock_management_before_estimate_updated($data){
    $newitems = $data['newitems'] ?? [];

    foreach($newitems as $key => $item){
        $data['newitems'][$key] = stock_management_tag_item_description($item);
    }

    return $data;
}

hooks()->add_action('after_estimate_updated', 'stock_management_after_estimate_updated');

function stock_management_after_estimate_updated($estimate_id){
    stock_management_fix_item_ids_from_descriptions($estimate_id, 'estimate');
}

hooks()->add_action('estimate_converted_to_invoice', 'stock_management_estimate_converted_to_invoice');

function stock_management_estimate_converted_to_invoice($data){
    $CI = &get_instance();
    $invoice_id = isset($data['invoice_id']) ? (int) $data['invoice_id'] : 0;
    $estimate_id = isset($data['estimate_id']) ? (int) $data['estimate_id'] : 0;
    if ($invoice_id < 1 || $estimate_id < 1) {
        return;
    }

    // Estimate ka stock_supplier nayi invoice mein set karo.
    $estimate = $CI->db->select('stock_supplier')->where('id', $estimate_id)->get(db_prefix() . 'estimates')->row_array();
    $stock_supplier = (int) ($estimate['stock_supplier'] ?? 0);
    if ($stock_supplier > 0) {
        $CI->db->where('id', $invoice_id)->update(db_prefix() . 'invoices', ['stock_supplier' => $stock_supplier]);
    } else {
        // Estimate par supplier nahi hai to invoice par pehle se set supplier
        // (before_invoice_added se) use karo.
        $invoice = $CI->db->select('stock_supplier')->where('id', $invoice_id)->get(db_prefix() . 'invoices')->row_array();
        $stock_supplier = (int) ($invoice['stock_supplier'] ?? 0);
    }

    // Both lists are in item_order sequence, so index N matches index N.
    $estimate_items = $CI->db->where('rel_id', $estimate_id)->where('rel_type', 'estimate')->order_by('item_order', 'asc')->order_by('id', 'asc')->get(db_prefix() . 'itemable')->result_array();
    $invoice_items = $CI->db->where('rel_id', $invoice_id)->where('rel_type', 'invoice')->order_by('item_order', 'asc')->order_by('id', 'asc')->get(db_prefix() . 'itemable')->result_array();

    foreach ($invoice_items as $index => $invoice_item) {
        if (!isset($estimate_items[$index])) {
            break;
        }
        $item_id = (int) ($estimate_items[$index]['item_id'] ?? 0);
        $update = [];
        // Safety net: an old estimate may still carry the marker in its
        // description - take the id from there and clean the description.
        if (isset($invoice_item['description']) && preg_match('/\[id_(\d+)\]/', $invoice_item['description'], $m)) {
            if ($item_id < 1) {
                $item_id = (int) $m[1];
            }
            $update['description'] = trim(preg_replace('/\s*\[id_\d+\]/', '', $invoice_item['description']));
        }
        if ($item_id > 0) {
            $update['item_id'] = $item_id;
        }
        if (!empty($update)) {
            $CI->db->where('id', $invoice_item['id'])->update(db_prefix() . 'itemable', $update);
        }

        /* Adjust stock qty - wahi calculation jo after_invoice_added mein hai.
           (after_invoice_added convert par skip karta hai, warna quantity
           do baar kam ho jati.) */
        $item_qty = (float) ($invoice_item['qty'] ?? 0);
        if ($item_id < 1 || $stock_supplier < 1) {
            continue;
        }

        $data_exists = $CI->db->select('units')->where('item' , $item_id)->where('supplier' , $stock_supplier)->get('tbl_sm_checkins')->row_array();

        if(isset($data_exists) && !empty($data_exists)){
            $existing_quantity = $data_exists['units'];
            $updated_quantity = $existing_quantity - $item_qty;
            $CI->db->set('units',$updated_quantity)->where('item' , $item_id)->where('supplier' , $stock_supplier)->update('tbl_sm_checkins');
        }
    }
}


/* Append "[id_N]" to an item description so the tblitems id survives
   add_new_sales_item_post() - that core helper copies the description
   verbatim into tblitemable but never writes the item_id column. The
   matching after-hook reads the marker back out again. */
function stock_management_tag_item_description($item){
    $item_id = isset($item['item_id']) ? (int) $item['item_id'] : 0;
    if ($item_id < 1 || !isset($item['description'])) {
        return $item;
    }
    if (preg_match('/\[id_\d+\]/', (string) $item['description'])) {
        return $item; // Already tagged - do not tag twice.
    }
    $item['description'] = rtrim((string) $item['description']) . ' [id_' . $item_id . ']';
    return $item;
}

/* Read "[id_N]" markers back out of tblitemable descriptions: set the
   item_id column and restore the clean description. */
function stock_management_fix_item_ids_from_descriptions($rel_id, $rel_type){
    $CI = &get_instance();
    $items = $CI->db->where('rel_id', $rel_id)->where('rel_type', $rel_type)->order_by('item_order', 'asc')->order_by('id', 'asc')->get(db_prefix() . 'itemable')->result_array();

    foreach ($items as $item) {
        if (!isset($item['description']) || !preg_match('/\[id_(\d+)\]/', $item['description'], $m)) {
            continue;
        }
        $CI->db->where('id', $item['id'])->update(db_prefix() . 'itemable', [
            'item_id'     => (int) $m[1],
            'description' => trim(preg_replace('/\s*\[id_\d+\]/', '', $item['description'])),
        ]);
    }
}
