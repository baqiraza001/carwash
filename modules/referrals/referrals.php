<?php

defined('BASEPATH') or exit('No direct script access allowed');

/*
Module Name: Referrals
Version: 1.0.0
*/

define('REFERRALS_MODULE_NAME', 'referrals');


/**
 * Initiate a new instance
 */
$CI = &get_instance();

/**
 * Register language files, must be registered if the module is using languages
 */
register_language_files(REFERRALS_MODULE_NAME, [REFERRALS_MODULE_NAME]);

register_activation_hook(REFERRALS_MODULE_NAME, 'referrals_activation_hook');
function referrals_activation_hook()
{
    $CI = &get_instance();
    require_once(__DIR__ . '/install.php');

    $alreadyInstalled = get_option('referrals_active');
    if( $alreadyInstalled != "" && $alreadyInstalled != NULL ){
        update_option('referrals_active', 1);
    } else {
        add_option('referrals_active', 1);
    }
}

/**
 * Deactivation module hook
 */
register_deactivation_hook(REFERRALS_MODULE_NAME, 'referrals_deactivation_hook');
function referrals_deactivation_hook()
{
    update_option('referrals_active', 0, 1);
}

hooks()->add_action('app_admin_footer', 'referrals_load_js');
function referrals_load_js(){
    if (get_option('referrals_active') == '1') {
        $CI = &get_instance();
        echo '<script src="'.module_dir_url('referrals', 'assets/js/referrals.js').'?v=' . $CI->app_scripts->core_version().'"></script>
            <script src="'.module_dir_url('referrals', 'assets/plugins/daterangepicker/daterangepicker.js').'?v=' . $CI->app_scripts->core_version().'"></script>';
    }
}

hooks()->add_action('app_admin_head', 'referrals_add_head_components');

function referrals_add_head_components(){
    if (get_option('referrals_active') == '1') {
        $CI = &get_instance();
        echo '<link href="' . base_url('modules/referrals/assets/css/referrals.css') .'?v=' . $CI->app_scripts->core_version(). '"  rel="stylesheet" type="text/css" />
            <link href="' . base_url('modules/referrals/assets/plugins/daterangepicker/daterangepicker.css') .'?v=' . $CI->app_scripts->core_version(). '"  rel="stylesheet" type="text/css" />';
    }
}

hooks()->add_action('clients_init', 'referrals_link');
function referrals_link()
{
    if (is_client_logged_in()) {
        add_theme_menu_item('referrals', [
            'name'     => _l('referrals'),
            'href'     => site_url('referrals'),
            'position' => 10,
        ]);
    }
}

hooks()->add_action('admin_init', 'referrals_init_menu_items');
function referrals_init_menu_items()
{
    $CI = &get_instance();

    $CI->app_menu->add_sidebar_menu_item('commissions', [
        'slug' => 'commissions',
        'name' => _l('referrals_commissions'),
        'href' => admin_url('referrals/commissions'),
        'position' => 100,
        'icon' => 'fa fa-money'
    ]);
}

hooks()->add_action('lead_converted_to_customer', 'referrals_lead_converted_to_customer');
function referrals_lead_converted_to_customer($data)
{
    $CI = &get_instance();
    $client_data = $CI->db->where('userid' , $data['customer_id'])->get('tblclients')->row_array();

    $lead_data = $CI->db->where('id' , $data['lead_id'])->get('tblleads')->row_array();

    if(isset($lead_data['referred_by_client']) && !empty($lead_data['referred_by_client'])){
        $client_update_data['source'] = $lead_data['source'];
        $client_update_data['referred_by_client'] = $lead_data['referred_by_client'];
        $CI->db->where('userid' , $data['customer_id'])->update('tblclients' , $client_update_data);
    }
    
}

hooks()->add_action('after_invoice_added', 'referrals_after_invoice_added');
function referrals_after_invoice_added($invoice_id)
{
    $CI = &get_instance();
    $invoice_data = $CI->db->where('id' , $invoice_id)->get('tblinvoices')->row_array();
    $client_data = $CI->db->where('userid' , $invoice_data['clientid'])->get('tblclients')->row_array();
    $referred_by = $client_data['referred_by_client'];

    $data['status'] = 'Unpaid';
    $data['source'] = $client_data['source'];
    $data['amount'] = '';
    $data['created_on'] = date('Y-m-d H:i:s');
    $data['clientid'] = $client_data['referred_by_client'];
    $data['invoice_id'] = $invoice_id;
    $CI->db->insert('tbl_commissions',$data); 

    
}


hooks()->add_action('after_payment_added', 'referrals_after_payment_added');
function referrals_after_payment_added($payment_id)
{
    $CI = &get_instance();
    $payment_data = $CI->db->where('id' , $payment_id)->get('tblinvoicepaymentrecords')->row_array();
    $invoice_data = $CI->db->where('id' , $payment_data['invoiceid'])->get('tblinvoices')->row_array();

    $existing_commission = $CI->db->where('invoice_id',$invoice_data['id'])->get('tbl_commissions')->row_array();

    $commission_percentage = !empty(get_option('referrals_commission_percentage')) ? get_option('referrals_commission_percentage') : 0;

    $commission = ($payment_data['amount'] / 100) * $commission_percentage;

    $CI->db->where('id' , $existing_commission['id'])->set('amount' , $commission)->update('tbl_commissions');

    
}


hooks()->add_action('admin_init', 'referrals_add_settings_tab');
/**
 * Init mail_templates module setting menu items in setup in admin_init hook
 * @return null
 */ 
function referrals_add_settings_tab()
{
    $CI = & get_instance();
    $CI->app->add_settings_section('referrals', [
        'title'     => _l('referrals'),
        'icon'     => 'fa-solid fa-box',
        'position' => 91,
    ]);

    $CI->app->add_settings_section_child(
        'referrals', // parent section id
        'referrals', // child id
        [
            'name'     => _l('referrals'), // tab title
            'view'     => 'referrals/settings', // view file path
            'position' => 91,
            'icon'     => 'fa fa-cog menu-icon',
        ]
    );
}



