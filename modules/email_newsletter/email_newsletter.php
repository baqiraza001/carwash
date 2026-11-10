<?php

defined('BASEPATH') or exit('No direct script access allowed');

/*
Module Name: Email Newsletter
Description: Effortlessly send emails to customers.
Version: 1.0.0
Author: PHP Teams
Author URI: http://phpteams.com/
Requires at least: 1.0.*
*/

define('EMAIL_NEWSLETTER_MODULE_NAME', 'email_newsletter');

hooks()->add_action('admin_init', 'email_newsletter_module_init_menu_items');
hooks()->add_action('admin_init', 'email_newsletter_permissions');


/**
 * Load the module helper
 */
$CI = & get_instance();
$CI->load->helper(EMAIL_NEWSLETTER_MODULE_NAME . '/email_newsletter'); //on module main file

function email_newsletter_permissions()
{
    $capabilities = [];

    $capabilities['capabilities'] = [
        'view'   => _l('permission_view') . '(' . _l('permission_global') . ')',
        'create' => _l('permission_create'),
        'edit'   => _l('permission_edit'),
        'delete' => _l('permission_delete')
    ];

    register_staff_capabilities('email_newsletter', $capabilities, _l('email_newsletter'));
}

/**
 * Register activation module hook
 */
register_activation_hook(EMAIL_NEWSLETTER_MODULE_NAME, 'email_newsletter_module_activation_hook');

function email_newsletter_module_activation_hook()
{
    $CI = &get_instance();
    require_once(__DIR__ . '/install.php');
}

/**
 * Register language files, must be registered if the module is using languages
 */
register_language_files(EMAIL_NEWSLETTER_MODULE_NAME, [EMAIL_NEWSLETTER_MODULE_NAME]);

/**
 * Init module menu items in setup in admin_init hook
 * @return null
 */
function email_newsletter_module_init_menu_items()
{
    $CI = &get_instance();

    if (has_permission('email_newsletter', '', 'view')) {
        $CI->app_menu->add_sidebar_menu_item('email_newsletter', [
            'slug' => 'email_newsletter',
            'name' => _l('email_newsletter'),
            'position' => 1,
            'href'     => admin_url('email_newsletter'),
            'icon' => 'far fa-envelope'
        ]);
    }

    if (has_permission('email_newsletter', '', 'view')) {
        $CI->app_menu->add_sidebar_children_item('email_newsletter', [
            'slug' => 'email_builder-view',
            'name' => _l('email_builder'),
            'href' => admin_url('email_builder'),
            'position' => 11,
        ]);
    }

    if (has_permission('email_newsletter', '', 'view')) {
        $CI->app_menu->add_sidebar_children_item('email_newsletter', [
            'slug' => 'email_newsletter-view',
            'name' => _l('email_newsletter_newsletter_history'),
            'href' => admin_url('email_newsletter/history'),
            'position' => 11,
        ]);
    }

    $CI->app->add_settings_section('email_newsletter', [
        'title'     => _l('Email Newsletter Settings'),
        'icon'     => 'fa-solid fa-box',
        'position' => 91,
    ]);

    $CI->app->add_settings_section_child(
        'email_newsletter', // parent section id
        'email_newsletter', // child id
        [
            'name'     => _l('Email Newsletter Settings'), // tab title
            'view'     => 'email_newsletter/settings', // view file path
            'position' => 91,
            'icon'     => 'fa fa-cog',
        ]
    );
}


hooks()->add_action('before_settings_updated', 'email_newsletter_init_update_settings');

function email_newsletter_init_update_settings($data='')
{
    update_option('email_newsletter_sendpulse_sms_from', $data['settings']['email_newsletter_sendpulse_sms_from']);
    update_option('email_newsletter_sendpulse_id', $data['settings']['email_newsletter_sendpulse_id']);
    update_option('email_newsletter_sendpulse_secret', $data['settings']['email_newsletter_sendpulse_secret']);
    
    update_option('email_newsletter_account_id', $data['settings']['email_newsletter_account_id']);
    update_option('email_newsletter_api_token', $data['settings']['email_newsletter_api_token']);

    unset($data['settings']['email_newsletter_sendpulse_sms_from']);
    unset($data['settings']['email_newsletter_sendpulse_id']);
    unset($data['settings']['email_newsletter_sendpulse_secret']);
 
    unset($data['settings']['email_newsletter_account_id']);
    unset($data['settings']['email_newsletter_api_token']);
    return $data;
}