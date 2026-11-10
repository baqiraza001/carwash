<?php
defined('BASEPATH') or exit('No direct script access allowed');

/*
Module Name: Mailtrap Synchronizer
Description: Module created for mailtrap synchronizer
Version: 2.3.0
Author: PHP Teams
Author URI: http://phpteams.com/
Requires at least: 2.3.*
*/

define('MAILTRAP_SYNC_MODULE_NAME', 'mailtrap_sync');

$CI =& get_instance();
$CI->load->helper(MAILTRAP_SYNC_MODULE_NAME . '/mailtrap_sync');

hooks()->add_action('admin_init', 'mailtrap_sync_module_init_menu_items');
hooks()->add_action('perfex_cronjob', 'mailtrap_sync_cron_job');

/**
 * Register menu in Admin Panel
 */
function mailtrap_sync_module_init_menu_items() {
    $CI = &get_instance();
    if (has_permission('mailtrap_sync_conflicts', '', 'view'))
    {
        /*$CI->app_menu->add_sidebar_children_item('sales', [
            'slug' => 'mailtrap_sync_conflicts',
            'name' => _l('Conflicts') ,
            'href' => admin_url('mailtrap_sync/conflicts') ,
            'position' => 99,
        ]);*/
    }
}

register_language_files(MAILTRAP_SYNC_MODULE_NAME, [MAILTRAP_SYNC_MODULE_NAME]);

/**
 * Cron job handler
 */
function mailtrap_sync_cron_job() {
    $last_sync = get_option('mailtrap_last_sync_time');
    $sync_duration = (int)get_option('mailtrap_sync_duration') * 30; // Convert minutes to seconds
    $current_time = time();

    if (!$last_sync || ($current_time - strtotime($last_sync)) >= $sync_duration) {
        $CI = &get_instance();
        $CI->load->library('mailtrapapi');
        $CI->mailtrapapi->syncLeads();
        // $CI->mailtrapapi->syncItems();
        $CI->mailtrapapi->syncProposals();

        update_option('mailtrap_last_sync_time', date('Y-m-d H:i:s'));
    }
}

/**
 * Module activation hook
 */
register_activation_hook(MAILTRAP_SYNC_MODULE_NAME, 'mailtrap_sync_install');
function mailtrap_sync_install() {
    $CI =& get_instance();
    require_once(__DIR__ . '/install.php');
    
    $alreadyInstalled = get_option('mailtrap_sync_active');

    if ($alreadyInstalled != "" && $alreadyInstalled != NULL)
        update_option('mailtrap_sync_active', 1);
    else
        add_option('mailtrap_sync_active', 1);
}

/**
 * Module deactivation hook
 */
register_deactivation_hook(MAILTRAP_SYNC_MODULE_NAME, 'mailtrap_sync_uninstall');
function mailtrap_sync_uninstall() {
    update_option('mailtrap_sync_active', 0, 1);
}

hooks()->add_action('admin_init', 'mailtrap_sync_permissions');

function mailtrap_sync_permissions()
{
    $capabilities = [];
    $capabilities['capabilities'] = [
        'edit' => _l('permission_edit') , 
    ];
    register_staff_capabilities('mailtrap_sync', $capabilities, _l('mailtrap_sync_settings'));

    $capabilities['capabilities'] = [
        'view'   => _l('permission_view'),
    ];

    register_staff_capabilities('mailtrap_sync_conflicts', $capabilities, _l('mailtrap_sync_conflicts'));
}

if (has_permission('mailtrap_sync', '', 'edit'))
    hooks()->add_action('admin_init', 'mailtrap_sync_add_settings_tab');

function mailtrap_sync_add_settings_tab()
{
    $CI =& get_instance();

    $CI->app_menu->add_sidebar_menu_item('mailtrap_sync', [
        'slug' => 'mailtrap_sync',
        'name' => _l('mailtrap_sync') ,
        'href' => admin_url('settings?group=mailtrap_sync') ,
        'icon' => 'fa fa-exchange',
        'position' => 1,
    ]);

    $CI->app->add_settings_section('mailtrap_sync', [
        'title'     => _l('mailtrap_sync'),
        'icon'     => 'fa-solid fa-box',
        'position' => 91,
    ]);

    $CI->app->add_settings_section_child(
        'mailtrap_sync', // parent section id
        'mailtrap_sync', // child id
        [
            'name'     => _l('mailtrap_sync'), // tab title
            'view'     => 'mailtrap_sync/settings', // view file path
            'position' => 91,
            'icon'     => 'fa fa-exchange menu-icon',
        ]
    );
}


hooks()->add_action('app_admin_footer', 'mailtrap_sync_load_js');

function mailtrap_sync_load_js(){
    if (get_option('mailtrap_sync_active') == '1') {
        $CI = &get_instance();
        echo '<script src="'.module_dir_url('mailtrap_sync', 'assets/js/custom.js').'?v=' . $CI->app_scripts->core_version().'"></script>';
    }
}

hooks()->add_action('before_settings_updated', 'mailtrap_sync_init_update_settings');

function mailtrap_sync_init_update_settings($data='')
{
    if(!empty($data['mailtrap_sync_module']))
    {
        update_option('mailtrap_sync_account_id', $data['mailtrap_sync_account_id']);
        update_option('mailtrap_sync_project_id', $data['mailtrap_sync_project_id']);
        update_option('mailtrap_sync_api_key', $data['mailtrap_sync_api_key']);
        update_option('mailtrap_sync_list_id', $data['mailtrap_sync_list_id']);

        unset($data['mailtrap_sync_module']);
        unset($data['mailtrap_sync_account_id']);
        unset($data['mailtrap_sync_project_id']);
        unset($data['mailtrap_sync_api_key']);
        unset($data['mailtrap_sync_list_id']);

        set_alert('success', _l('settings_updated'));
    }

    return $data;
}



hooks()->add_action('contact_created', 'mailtrap_sync_contact_created_updated');
hooks()->add_action('contact_updated', 'mailtrap_sync_contact_created_updated');
function mailtrap_sync_contact_created_updated($id='')
{
    $CI =& get_instance();
    $CI->load->library('mailtrap_sync/mailtrap_library');
    $CI->mailtrap_library->sync_single_contact($id);
    return $id;
}

hooks()->add_action('before_delete_contact', 'mailtrap_sync_before_delete_contact');
function mailtrap_sync_before_delete_contact($id='')
{
     $CI =& get_instance();
    $CI->load->library('mailtrap_sync/mailtrap_library');
    $CI->mailtrap_library->delete_contact_from_mailtrap($id);
    return $id;
}