<?php
// VERSION: 2026-11-10 07:47 PKT - Insurance information: ensure tblclients insurance columns on Clients pages
defined('BASEPATH') or exit('No direct script access allowed');

/*
Module Name: 6th avenue carwash
Description: Module for saving Vehicles
Version: 1.0.0
Requires at least: 2.3.*
*/

define('CARWASH_MODULE_NAME', 'carwash');
define('CUSTOM_FIELDS_SMART_TRANSFER', true);

/**
 * Initiate a new instance
 */
$CI = &get_instance();
$CI->load->helper(CARWASH_MODULE_NAME . '/carwash');

/**
 * Activate the carwash module
 */
register_activation_hook(CARWASH_MODULE_NAME, 'carwash_activation_hook');
function carwash_activation_hook()
{
    $CI = &get_instance();
    require_once(__DIR__ . '/install.php');
    $alreadyInstalled = get_option('carwash_active');
    if ($alreadyInstalled != "" && $alreadyInstalled != null) {
        update_option('carwash_active', 1);
    } else {
        add_option('carwash_active', 1);
    }
}

/**
 * Register language files, must be registered if the module is using languages
 */
register_language_files(CARWASH_MODULE_NAME, [CARWASH_MODULE_NAME]);

/**
 * Deactivation module hook
 *
 *
 */
register_deactivation_hook(CARWASH_MODULE_NAME, 'carwash_deactivation_hook');
function carwash_deactivation_hook()
{
    update_option('carwash_active', 0, 1);
}

hooks()->add_filter('before_estimate_added', 'carwash_add_payers_to_session');
hooks()->add_filter('after_estimate_added', 'carwash_add_payers_to_table');
hooks()->add_filter('before_estimate_updated', 'carwash_update_payers_data');
hooks()->add_filter('estimate_converted_to_invoice', 'carwash_add_estimate_custom_fields_to_invoice');
hooks()->add_filter('before_invoice_added', 'carwash_add_payers_to_session');
hooks()->add_filter('after_invoice_added', 'carwash_add_payers_to_table');
hooks()->add_filter('before_invoice_updated', 'carwash_update_payers_data');
hooks()->add_filter('before_payment_recorded', 'carwash_add_payment_client_to_session');
hooks()->add_filter('after_payment_added', 'carwash_update_client_to_payment');


hooks()->add_action('admin_init', 'carwash_client_tab');
hooks()->add_action('admin_init', 'carwash_admin_init');
// Sales items report (admin/reports/items): handled by the module, not
// the core controller - see modules/carwash/hooks/reports_items.php.
// The callback only runs the report on that exact AJAX route; on every
// other admin request it returns after one cheap route check.
require_once(__DIR__ . '/hooks/reports_items.php');
require_once(__DIR__ . '/hooks/reports_payments_received.php');
hooks()->add_action('admin_init', 'carwash_reports_items_intercept');
hooks()->add_action('before_client_updated', 'carwash_init_before_client_updated');
hooks()->add_action('before_client_added', 'carwash_init_before_client_updated');
hooks()->add_action('app_admin_footer', 'carwash_load_js');
hooks()->add_action('app_admin_footer', 'carwash_load_settings_js');
hooks()->add_action('clients_init', 'carwash_vehicles_link');
hooks()->add_action('before_payment_deleted', 'carwash_init_payment_delete');


function carwash_client_tab()
{
    $CI = &get_instance();
    // Insurance information columns (tblclients) - created on demand so an
    // already-installed module gets them without a reinstall.
    if ($CI->uri->segment(2) === 'clients') {
        carwash_ensure_insurance_columns();
    }
    // Add new tab in customers profile
    $CI->app_tabs->add_customer_profile_tab('carwash', [
        'name' => _l('carwash_vehicles'),
        'icon' => 'fa fa-car',
        'view' => CARWASH_MODULE_NAME.'/vehicles',
        'position' => 99,
    ]);
}

function carwash_ensure_insurance_columns()
{
    $CI = &get_instance();
    if (!$CI->db->field_exists('insurance_policy_number', 'clients')) {
        $CI->db->query('ALTER TABLE `' . db_prefix() . 'clients` ADD COLUMN `insurance_policy_number` VARCHAR(255) NULL DEFAULT NULL');
    }
    if (!$CI->db->field_exists('insurance_customer_number', 'clients')) {
        $CI->db->query('ALTER TABLE `' . db_prefix() . 'clients` ADD COLUMN `insurance_customer_number` VARCHAR(255) NULL DEFAULT NULL');
    }
}

function carwash_admin_init()
{
    $CI = &get_instance();
    if (is_admin()) {
        $CI->app->add_settings_section('carwash_settings', [
            'title'    => _l('carwash_terms_and_conditions'),
            'position' => 60,
            'children' => [
                [
                    'name'     => _l('carwash_terms_and_conditions'),
                    'view'     => 'carwash/terms_conditions_settings_tab',
                    'icon'     => 'fa fa-file-text-o menu-icon',
                    'position' => 1,
                ],
            ],
        ]);
    }
}


function carwash_load_js(){
    if (get_option('carwash_active') == '1') {
        $CI = &get_instance();
        echo '<script src="'.module_dir_url('carwash', 'assets/js/carwash.js').'?v=' . uniqid().'"></script>';
    }
}

function carwash_load_settings_js()
{
    $CI = &get_instance();

    if ($CI->uri->segment(1) !== 'admin' || $CI->uri->segment(2) !== 'settings') {
        return;
    }

    if ($CI->input->get('group') !== 'carwash_terms_conditions_settings') {
        return;
    }

    $path = module_dir_path(CARWASH_MODULE_NAME, 'assets/js/terms_conditions_settings.php');
    if (file_exists($path)) {
        include $path;
    }
}

function carwash_vehicles_link()
{
    if (is_client_logged_in()) {
        add_theme_menu_item('carwash_vehicles', [
            'name'     => _l('carwash_vehicles'),
            'href'     => site_url('carwash/carwash_client'),
            'position' => 10,
        ]);
    }
}


function carwash_add_estimate_custom_fields_to_invoice($data){
    $CI = &get_instance();
    $invoice_id = $data['invoice_id'];    
    $estimate_id = $data['estimate_id'];  
    
    $estimate_data =  $CI->db->where('id', $estimate_id)->get('tblestimates')->row();
    
    /**** copy estimate custom fields to invoice ****/
    $CI->db->where('fieldto', 'estimate');
    $cfEstimates = $CI->db->get(db_prefix() . 'customfields')->result_array();

    if(!empty($cfEstimates))
    {
        foreach ($cfEstimates as $field) 
        {
            $estimate_slug = explode('_', $field['slug'], 2);
            $invoice_slug = 'invoice_'.$estimate_slug[1];
            $invoice_field = $CI->db->where('slug', $invoice_slug)->get(db_prefix() . 'customfields')->row();
            $field_value = $CI->db->where('fieldid', $field['id'])->get(db_prefix() . 'customfieldsvalues')->row();

            if(empty($invoice_field))
            {
                $cf_invoice =  array(
                    'fieldto' => 'invoice',
                    'name' => $field['name'],
                    'slug' => $invoice_slug,
                    'required' => $field['required'],
                    'type' => $field['type'],
                    'options' => $field['options'],
                    'display_inline' => $field['display_inline'],
                    'field_order' => $field['field_order'],
                    'active' => $field['active'],
                    'show_on_pdf' => $field['show_on_pdf'],
                    'show_on_ticket_form' => $field['show_on_ticket_form'],
                    'only_admin' => $field['only_admin'],
                    'show_on_table' => $field['show_on_table'],
                    'show_on_client_portal' => $field['show_on_client_portal'],
                    'disalow_client_to_edit' => $field['disalow_client_to_edit'],
                    'bs_column' => $field['bs_column']
                );
                $CI->db->insert('tblcustomfields', $cf_invoice);
                $fieldid = $CI->db->insert_id();

                if(!empty($field_value)){
                    $CI->db->insert(db_prefix() . 'customfieldsvalues', [
                        'relid' => $invoice_id,
                        'fieldid' => $fieldid,
                        'fieldto' => 'invoice',
                        'value' => $field_value->value
                    ]);
                }
                
            } else 
            {
                $fieldid =  $invoice_field->id;
            }

        }
    }
    /**** copy estimate custom fields to invoice ****/

    /**** add estimate payers to invoice also, just update invoice_id in payers table ****/
    $CI->db->set('invoice_id', $invoice_id);
    $CI->db->where('estimate_id', $estimate_id);
    $CI->db->update('tbl_carwash_payers');
    /**** add estimate payers to invoice also, just update invoice_id in payers table ****/
    
    log_activity(json_encode($estimate_data).' new invoice created [Staff Name: ' . get_staff_full_name() . ', IP: ' . $CI->input->ip_address() . ']');

    return TRUE;
}


/*+++++++++++++++++++*/


function carwash_add_payers_to_session($data){
    $CI = &get_instance();
    $is_estimate = isset($data['data']['is_estimate']) ? $data['data']['is_estimate'] : 0;
    $CI->session->set_userdata('carwash_is_estimate', $is_estimate);

    unset($data['data']['is_estimate']);  
    unset($data['data']['carwash_invoice_id']);
    unset($data['data']['carwash_estimate_id']);
    unset($data['data']['DataTables_Table_0_length']);
    unset($data['data']['DataTables_Table_1_length']);
    unset($data['data']['carwash_payer_options']);
    unset($data['data']['cw_nags']);
    unset($data['data']['purchase_price']);
    unset($data['data']['item_profit']);

    if (isset($data['data']['new_payers_list']))
        $GLOBALS['carwash_new_payers_list'] = $data['data']['new_payers_list'];

    unset($data['data']['new_payers_list']);  
    return $data;
}


function carwash_add_payers_to_table($invoice_id){
    $CI = &get_instance();
    $is_estimate = $CI->session->userdata('carwash_is_estimate');
    
    if (empty($GLOBALS['carwash_new_payers_list'])) {
        return;
    }

    $payer_ids = $GLOBALS['carwash_new_payers_list'];
    $payer_ids = json_decode($payer_ids);
    unset($GLOBALS['carwash_new_payers_list']);

    if(!empty($payer_ids)){
        if($is_estimate)
            $CI->db->set('estimate_id', $invoice_id);
        else
            $CI->db->set('invoice_id', $invoice_id);

        $CI->db->where_in('id', $payer_ids);
        $CI->db->update('tbl_carwash_payers');
    }

    return TRUE;
}


function carwash_update_payers_data($data){
    $CI = &get_instance();

    $is_estimate = isset($data['data']['is_estimate']) ? $data['data']['is_estimate'] : 0;

    if($is_estimate){
        unset($data['data']['carwash_estimate_id']);   
        unset($data['data']['is_estimate']);   
    }
    unset($data['data']['carwash_invoice_id']);    
    unset($data['data']['new_payers_list']); 
    unset($data['data']['DataTables_Table_0_length']); 
    unset($data['data']['DataTables_Table_1_length']); 
    unset($data['data']['carwash_payer_options']);  
    unset($data['data']['cw_nags']);
    unset($data['data']['purchase_price']);
    unset($data['data']['item_profit']);
    
    return $data;
}



function carwash_add_payment_client_to_session($data = ''){
    $CI = &get_instance();
    $CI->session->set_userdata('carwash_payment_client_id', $data['client_id']);

    return $data;
}


function carwash_update_client_to_payment($payment_id = 0)
{
    $CI = &get_instance();

    $payment_id = (int) $payment_id;
    if ($payment_id <= 0) {
        return true;
    }

    $session_client_id = (int) $CI->session->userdata('carwash_payment_client_id');

    // Query 1: only update payment row once
    if ($session_client_id > 0) {
        $CI->db->where('id', $payment_id)
               ->update(db_prefix() . 'invoicepaymentrecords', ['client_id' => $session_client_id]);
    }
    return true;
}


function carwash_init_payment_delete($data = [])
{
    $payment_id = $data['paymentid'];
    $CI = &get_instance();

    $payment = $CI->db->where('id', $payment_id)->get(db_prefix() . 'invoicepaymentrecords')->row_array();
    if ($payment) 
    {
        if(!empty($payment['credit_amount_applied']))
        {
            $payment_amount = $payment['amount'];
            $client_id = $payment['client_id'];

            $credit_amount = $CI->db->where('userid', $client_id)->get(db_prefix() . 'clients')->row('credit_amount');

            $new_credit_amount = $credit_amount + $payment_amount;
            if($new_credit_amount < 0) $new_credit_amount = 0;
            
            $CI->db->set('credit_amount', $new_credit_amount)->where('userid', $client_id)->update(db_prefix() . 'clients');

        }
    } 
   

}


function carwash_init_before_client_updated($data = [])
{
    $data['client_taxes'] = json_encode($data['client_taxes']);
   
    return $data;
}



// top header search bar to search carwash payers also
hooks()->add_filter('global_search_result_query', 'carwash_search_invoice_payers', 10, 3);

function carwash_search_invoice_payers($results, $q, $limit)
{
    $CI = &get_instance();

    $q = trim($q);

    if ($q === '' || startsWith($q, '#')) {
        return $results;
    }

    // Same invoice permissions as Perfex invoice search.
    $can_view_invoices = staff_can('view', 'invoices');
    $can_view_own = staff_can('view_own', 'invoices');

    if (
        !$can_view_invoices
        && !$can_view_own
        && get_option('allow_staff_view_invoices_assigned') != '1'
    ) {
        return $results;
    }

    $payer_table = db_prefix() . '_carwash_payers';
    $search = $CI->db->escape_like_str($q);

    $payer_fields = [
        'name',
        'policy_number',
        'authorization_number',
        'purchase',
        'order_number',
        'fleet_or_unit',
        'vehicle_make',
        'model',
        'vin',
        'plate_no',
    ];

    // Build payer matching subquery.
    $payer_conditions = [];

    foreach ($payer_fields as $field) {
        $payer_conditions[] = $field . ' LIKE "%' . $search . '%" ESCAPE \'!\'';
    }

    $payer_subquery = '
        SELECT invoice_id
        FROM ' . $payer_table . '
        WHERE (' . implode(' OR ', $payer_conditions) . ')
    ';

    // Fetch matching invoices using the same structure as Perfex.
    $invoice_fields = prefixed_table_fields_array(db_prefix() . 'invoices');
    $clients_fields = prefixed_table_fields_array(db_prefix() . 'clients');

    $CI->db->select(
        implode(',', $invoice_fields) . ',' .
        implode(',', $clients_fields) . ',' .
        db_prefix() . 'invoices.id as invoiceid,' .
        get_sql_select_client_company()
    );

    $CI->db->from(db_prefix() . 'invoices');

    $CI->db->join(
        db_prefix() . 'clients',
        db_prefix() . 'clients.userid = ' . db_prefix() . 'invoices.clientid',
        'left'
    );

    $CI->db->join(
        db_prefix() . 'currencies',
        db_prefix() . 'currencies.id = ' . db_prefix() . 'invoices.currency'
    );

    $CI->db->join(
        db_prefix() . 'contacts',
        db_prefix() . 'contacts.userid = ' . db_prefix() . 'clients.userid AND is_primary = 1',
        'left'
    );

    $CI->db->where(
        db_prefix() . 'invoices.id IN (' . $payer_subquery . ')',
        null,
        false
    );

    // Respect Perfex invoice visibility restrictions.
    if (!$can_view_invoices) {
        $CI->db->where(
            get_invoices_where_sql_for_staff(get_staff_user_id())
        );
    }

    $CI->db->order_by('number,YEAR(date)', 'desc');

    if ($limit != 0) {
        $CI->db->limit($limit);
    }

    $payer_invoices = $CI->db->get()->result_array();

    if (empty($payer_invoices)) {
        return $results;
    }

    // Find existing invoices search result.
    $invoice_index = null;

    foreach ($results as $index => $item) {
        if (isset($item['type']) && $item['type'] === 'invoices') {
            $invoice_index = $index;
            break;
        }
    }

    // Create invoice result group if normal invoice search had no matches.
    if ($invoice_index === null) {
        $results[] = [
            'result'         => [],
            'type'           => 'invoices',
            'search_heading' => _l('invoices'),
        ];

        $invoice_index = array_key_last($results);
    }

    // Prevent duplicate invoices.
    $existing_ids = array_column(
        $results[$invoice_index]['result'],
        'invoiceid'
    );

    foreach ($payer_invoices as $invoice) {
        if (!in_array($invoice['invoiceid'], $existing_ids)) {
            $results[$invoice_index]['result'][] = $invoice;
            $existing_ids[] = $invoice['invoiceid'];
        }
    }

    return $results;
}