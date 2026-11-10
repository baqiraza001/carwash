<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Carwash — Sales "Items" report (admin/reports/items) moved out of the
 * core controller into this module.
 *
 * WHY THIS WAY
 * -------------
 * The stock Reports::items() in application/controllers/admin/Reports.php
 * contains NO hooks()->do_action() / apply_filters() calls at all, and the
 * only datatables filter that fires here ('datatables_sql_query_results')
 * runs AFTER the SQL has already executed — so it cannot add the extra
 * JOIN, the purchase_price / retail_price columns, the extra WHERE
 * conditions or the GROUP BY this report needs. A filter-based move is
 * therefore impossible without touching the core file.
 *
 * Instead this file uses the 'admin_init' action, which Perfex fires in
 * AdminController::__construct() — i.e. AFTER staff authentication but
 * BEFORE the requested controller method (Reports::items()) runs.
 * The intercept callback does ONE cheap check per admin request
 * (router class + method + is_ajax_request, no queries, no loading) and
 * returns immediately for every other page. Only when the request really
 * is admin/reports/items (AJAX) does it run the report code below and
 * exit — the core items() method then never executes.
 *
 * If this module is deactivated, carwash.php is not loaded, this hook is
 * never registered, and the stock core items() runs untouched (graceful
 * fallback: 4-column report, no carwash columns).
 *
 * The report logic below is the NEW stock items() as its base, plus the
 * old customisations:
 *  - JOIN tblitems (for purchase_price / retail_price)
 *  - purchase_price column + 'tblitems.rate as retail_price' select
 *  - GROUP BY description, purchase_price, tblitems.rate
 *  - invoice-status filter from POST 'sales_items_invoice_status'
 *    (the my_sales_items.php view field; statuses 4/5/6 are excluded
 *    in the view itself)
 *  - Retail Price / Purchase Price / Profit-Loss columns + footer sums
 *    (total_amount / total_qty / total_profit_loss) matching the
 *    my_sales_items.php + my_sales_js.php views (7 columns).
 * Output escaping follows the new stock style (e()).
 */

/**
 * Intercept registered on 'admin_init'.
 * Runs on every admin request, but does nothing except a cheap route
 * check unless the request is the items-report AJAX call.
 */
function carwash_reports_items_intercept()
{
    $CI = &get_instance();

    // Cheap gate first: only the carwash report routes, AJAX only.
    // One callback covers both reports handled by this module.
    if (strtolower($CI->router->fetch_class()) !== 'reports'
        || !$CI->input->is_ajax_request()) {
        return;
    }

    $method = strtolower($CI->router->fetch_method());
    if ($method === 'items') {
        carwash_reports_items();
        exit();
    }

    if ($method === 'payments_received') {
        carwash_reports_payments_received();
        exit();
    }
}

/**
 * Full items-report implementation (module copy of Reports::items()
 * + the carwash customisations listed above).
 */
function carwash_reports_items()
{
    $CI = &get_instance();

    $CI->load->model('currencies_model');
    $v = $CI->db->query('SELECT VERSION() as version')->row();
    // 5.6 mysql version don't have the ANY_VALUE function implemented.

    if ($v && strpos($v->version, '5.7') !== false) {
        $aColumns = [
            'ANY_VALUE(tblitemable.description) as description',
            'ANY_VALUE((SUM(' . db_prefix() . 'itemable.qty))) as quantity_sold',
            'ANY_VALUE(SUM(tblitemable.rate*qty)) as rate',
            'ANY_VALUE(AVG(tblitemable.rate*qty)) as avg_price',
            'ANY_VALUE(tblitems.purchase_price) as purchase_price',
        ];
    } else {
        $aColumns = [
            'tblitemable.description as description',
            '(SUM(' . db_prefix() . 'itemable.qty)) as quantity_sold',
            'SUM(tblitemable.rate*qty) as rate',
            'AVG(tblitemable.rate*qty) as avg_price',
            'tblitems.purchase_price as purchase_price',
        ];
    }

    $sIndexColumn = 'id';
    $sTable       = db_prefix() . 'itemable';
    $join         = [
        'JOIN ' . db_prefix() . 'invoices ON ' . db_prefix() . 'invoices.id = ' . db_prefix() . 'itemable.rel_id',
        'JOIN ' . db_prefix() . 'items ON ' . db_prefix() . 'items.id = ' . db_prefix() . 'itemable.item_id',
    ];

    $where = ['AND rel_type="invoice"', 'AND status != 5'];

    // -----------------------------------------------------------------
    // NEW-STOCK FILTERS (default OFF — old report behaviour preserved).
    // The new stock items() also adds 'AND status=2' (paid invoices
    // only) and 'AND is_selected=1' (only items the customer selected —
    // the new "optional items" feature, item_is_selected). The old
    // custom report showed every item of every non-cancelled invoice.
    // Set this to true ONLY if you want the new stock behaviour.
    // -----------------------------------------------------------------
    $carwash_apply_new_stock_filters = false;
    if ($carwash_apply_new_stock_filters) {
        $where[] = 'AND status=2';
        $where[] = 'AND is_selected=1';
    }

    $custom_date_select = carwash_reports_where_period($CI);
    if ($custom_date_select != '') {
        array_push($where, $custom_date_select);
    }

    $by_currency = $CI->input->post('report_currency');
    if ($by_currency) {
        $currency = $CI->currencies_model->get($by_currency);
        array_push($where, 'AND currency=' . $CI->db->escape_str($by_currency));
    } else {
        $currency = $CI->currencies_model->get_base_currency();
    }

    if ($CI->input->post('sale_agent_items')) {
        $agents  = $CI->input->post('sale_agent_items');
        $_agents = [];
        if (is_array($agents)) {
            foreach ($agents as $agent) {
                if ($agent != '') {
                    array_push($_agents, $CI->db->escape_str($agent));
                }
            }
        }
        if (count($_agents) > 0) {
            array_push($where, 'AND sale_agent IN (' . implode(', ', $_agents) . ')');
        }
    }

    if ($CI->input->post('sales_items_invoice_status')) {
        $statuses  = $CI->input->post('sales_items_invoice_status');
        $_statuses = [];
        if (is_array($statuses)) {
            foreach ($statuses as $status) {
                if ($status != '') {
                    array_push($_statuses, $CI->db->escape_str($status));
                }
            }
        }
        if (count($_statuses) > 0) {
            array_push($where, 'AND status IN (' . implode(', ', $_statuses) . ')');
        }
    }

    $result = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, ['tblitems.rate as retail_price'], 'GROUP by tblitems.description, purchase_price, tblitems.rate');

    $output  = $result['output'];
    $rResult = $result['rResult'];

    $footer_data = [
        'total_amount'      => 0,
        'total_qty'         => 0,
        'total_profit_loss' => 0,
    ];

    foreach ($rResult as $aRow) {
        $row = [];

        $row[] = e($aRow['description']);
        $row[] = $aRow['quantity_sold'];
        $row[] = e(app_format_money($aRow['rate'], $currency->name));
        $row[] = e(app_format_money($aRow['avg_price'], $currency->name));
        $row[] = e(app_format_money($aRow['retail_price'], $currency->name));
        $row[] = e(app_format_money($aRow['purchase_price'], $currency->name));
        $profit_loss_value = ($aRow['retail_price'] - $aRow['purchase_price']) * $aRow['quantity_sold'];
        $profit_loss       = e(app_format_money($profit_loss_value, $currency->name));
        $row[]             = $profit_loss;
        $footer_data['total_amount'] += $aRow['rate'];
        $footer_data['total_qty'] += $aRow['quantity_sold'];
        $footer_data['total_profit_loss'] += $profit_loss_value;
        $output['aaData'][] = $row;
    }

    $footer_data['total_amount']      = e(app_format_money($footer_data['total_amount'], $currency->name));
    $footer_data['total_profit_loss'] = e(app_format_money($footer_data['total_profit_loss'], $currency->name));

    $output['sums'] = $footer_data;
    echo json_encode($output);
}

/**
 * Module copy of Reports::get_where_report_period() (private in the
 * core controller, so the module cannot call it — same logic, same
 * POST inputs: report_months / report_from / report_to).
 */
function carwash_reports_where_period($CI, $field = 'date')
{
    $months_report      = $CI->input->post('report_months');
    $custom_date_select = '';
    if ($months_report != '') {
        if (is_numeric($months_report)) {
            // Last month
            if ($months_report == '1') {
                $beginMonth = date('Y-m-01', strtotime('first day of last month'));
                $endMonth   = date('Y-m-t', strtotime('last day of last month'));
            } else {
                $months_report = (int) $months_report;
                $months_report--;
                $beginMonth = date('Y-m-01', strtotime("-{$months_report} MONTH"));
                $endMonth   = date('Y-m-t');
            }

            $custom_date_select = 'AND (' . $field . ' BETWEEN "' . $beginMonth . '" AND "' . $endMonth . '")';
        } elseif ($months_report == 'this_month') {
            $custom_date_select = 'AND (' . $field . ' BETWEEN "' . date('Y-m-01') . '" AND "' . date('Y-m-t') . '")';
        } elseif ($months_report == 'this_year') {
            $custom_date_select = 'AND (' . $field . ' BETWEEN "' .
            date('Y-m-d', strtotime(date('Y-01-01'))) .
            '" AND "' .
            date('Y-m-d', strtotime(date('Y-12-31'))) . '")';
        } elseif ($months_report == 'last_year') {
            $custom_date_select = 'AND (' . $field . ' BETWEEN "' .
            date('Y-m-d', strtotime(date(date('Y', strtotime('last year')) . '-01-01'))) .
            '" AND "' .
            date('Y-m-d', strtotime(date(date('Y', strtotime('last year')) . '-12-31'))) . '")';
        } elseif ($months_report == 'custom') {
            $from_date = to_sql_date($CI->input->post('report_from'));
            $to_date   = to_sql_date($CI->input->post('report_to'));
            if ($from_date == $to_date) {
                $custom_date_select = 'AND ' . $field . ' = "' . $CI->db->escape_str($from_date) . '"';
            } else {
                $custom_date_select = 'AND (' . $field . ' BETWEEN "' . $CI->db->escape_str($from_date) . '" AND "' . $CI->db->escape_str($to_date) . '")';
            }
        }
    }

    return $custom_date_select;
}
