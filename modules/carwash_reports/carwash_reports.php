<?php

defined('BASEPATH') or exit('No direct script access allowed');

/*
Module Name: Carwash reports
Description: Custom reports for 6th avenue carwash
Version: 2.3.0
Requires at least: 2.3.*
*/

define('CARWASH_REPORTS_MODULE_NAME', 'carwash_reports');

$CI = &get_instance();
$CI->load->helper(CARWASH_REPORTS_MODULE_NAME . '/carwash_reports');

hooks()->add_action('admin_init', 'carwash_report_module_init_menu_items');
hooks()->add_action('admin_init', 'carwash_reports_permissions');

function carwash_reports_permissions()
{
    $capabilities = [];

    $capabilities['capabilities'] = [
            'daily_sales_report'   => _l('daily sales report'),
            'weekly_sales_report' => _l('weekly sales report'),
            'items_checkin_report' => _l('items checkin report'),
    ];
    register_staff_capabilities('carwash_reports', $capabilities, _l('carwash-reports'));
}

/**
* Register activation module hook
*/
register_activation_hook(CARWASH_REPORTS_MODULE_NAME, 'carwash_reports_module_activation_hook');

function carwash_reports_module_activation_hook()
{
    $CI = &get_instance();
    require_once(__DIR__ . '/install.php');
}

/**
* Register language files, must be registered if the module is using languages
*/
//register_language_files(CARWASH_REPORTS_MODULE_NAME, [CARWASH_REPORTS_MODULE_NAME]);

/**
 * Init goals module menu items in setup in admin_init hook
 * @return null
 */
function carwash_report_module_init_menu_items()
{
    $CI = &get_instance();

    if (has_permission('carwash_reports', '', 'view')) {

        $CI->app_menu->add_sidebar_children_item('reports', [
            'slug'     => 'daily-sales-report',
            'name'     => _l('Daily sales report'),
            'href'     => admin_url('carwash_reports/daily_sales_report'),
            'icon'     => 'fa fa-calendar',
        ]);

        $CI->app_menu->add_sidebar_children_item('reports', [
            'slug'     => 'monthly-sales-report',
            'name'     => _l('Monthly sales report'),
            'href'     => admin_url('carwash_reports/monthly_sales_report'),
            'icon'     => 'fa fa-calendar-check-o',
        ]);

        $CI->app_menu->add_sidebar_children_item('reports', [
            'slug'     => 'items-checkin-report',
            'name'     => _l('Items Checkin report'),
            'href'     => admin_url('carwash_reports/items_checkin_report'),
            'icon'     => 'fa fa-list',
        ]);
    }
}


