<?php
defined('BASEPATH') or exit('No direct script access allowed');

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
        echo '<script src="'.module_dir_url('stock_management', 'assets/js/stock_products_js.js').'?v=' . $CI->app_scripts->core_version().'"></script>';
    }
}


hooks()->add_filter('before_invoice_added', 'stock_management_before_invoiced_added');
function stock_management_before_invoiced_added($data){
	$CI = &get_instance();
	$stock_supplier = $data['data']['stock_supplier'];
	$items = $data['items'];
	
    foreach($items as $item){
        $item_id = $item['item_id'];
        $item_qty = $item['qty'];
        
        $data_exists = $CI->db->select('units')->where('item' , $item_id)->where('supplier' , $stock_supplier)->get('tbl_sm_checkins')->row_array();

        if(isset($data_exists) && !empty($data_exists)){
            $existing_quantity = $data_exists['units'];
            $updated_quantity = $existing_quantity - $item_qty;
            $CI->db->set('units',$updated_quantity)->where('item' , $item_id)->where('supplier' , $stock_supplier)->update('tbl_sm_checkins');
        }
    }
	
	unset($data['data']['sm_product']);
    return $data;
}

hooks()->add_filter('before_invoice_updated', 'commission_filter_invoice_post_data_update');
function commission_filter_invoice_post_data_update($data){
	unset($data['data']['sm_product']);
    return $data;
}
