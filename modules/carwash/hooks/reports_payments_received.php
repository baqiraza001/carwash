<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Carwash — Sales "Payments Received" report
 * (admin/reports/payments_received) moved out of the core controller
 * into this module. Same mechanism as hooks/reports_items.php:
 * the 'admin_init' intercept in that file (carwash_reports_items_intercept)
 * routes this AJAX endpoint here and exits before the core
 * Reports::payments_received() runs. The core file is untouched.
 *
 * Report logic below is the NEW stock payments_received() as its base,
 * plus the old customisations:
 *  - extra 'status' select column (invoice status, shown via
 *    format_invoice_status()) — matches the extra status <th> in the
 *    my_sales_payments.php view (9 columns total)
 *  - base table kept as tblinvoices with the OLD joins (NOT the new
 *    stock base tblinvoicepaymentrecords): the custom 'status' column
 *    is tblinvoices.status, and the clients JOIN uses
 *    tblinvoicepaymentrecords.client_id (the carwash-populated payer
 *    client, see my_record_payment_template.php). The new stock base
 *    table has no status column, so switching to it would break this
 *    report.
 *  - per-payment-mode totals accumulated in the loop and rendered in
 *    the footer as "Mode: amount<br/>...Total: amount"
 *    (sums.total_amount), as the old custom code did.
 * Output escaping follows the new stock style (e() on values; the
 * invoice-status label HTML is left unescaped, as format_invoice_status()
 * returns HTML), and the stock 'note' handling
 * (process_text_content_for_display) is kept from the new version.
 */
function carwash_reports_payments_received()
{
    $CI = &get_instance();

    $CI->load->model('currencies_model');
    $CI->load->model('payment_modes_model');
    $payment_gateways = $CI->payment_modes_model->get_payment_gateways(true);
    $select           = [
        db_prefix() . 'invoicepaymentrecords.id',
        db_prefix() . 'invoicepaymentrecords.date',
        'invoiceid',
        get_sql_select_client_company(),
        'paymentmode',
        'transactionid',
        'note',
        'amount',
        'status',
    ];
    $where = [
        'AND status != 5',
    ];

    $custom_date_select = carwash_reports_where_period($CI, db_prefix() . 'invoicepaymentrecords.date');
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

    $aColumns     = $select;
    $sIndexColumn = 'id';
    $sTable       = db_prefix() . 'invoices';
    $join         = [
        'LEFT JOIN ' . db_prefix() . 'invoicepaymentrecords ON ' . db_prefix() . 'invoices.id = ' . db_prefix() . 'invoicepaymentrecords.invoiceid',
        'LEFT JOIN ' . db_prefix() . 'clients ON ' . db_prefix() . 'clients.userid = ' . db_prefix() . 'invoicepaymentrecords.client_id',
        'LEFT JOIN ' . db_prefix() . 'payment_modes ON ' . db_prefix() . 'payment_modes.id = ' . db_prefix() . 'invoicepaymentrecords.paymentmode',
    ];

    $result = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, [
        'number',
        'clientid',
        db_prefix() . 'payment_modes.name',
        db_prefix() . 'payment_modes.id as paymentmodeid',
        'paymentmethod',
        'deleted_customer_name',
    ]);

    $output  = $result['output'];
    $rResult = $result['rResult'];

    $footer_data['total_amount'] = 0;
    $payment_modes_arr = $total_payment_modes_amount = [];

    foreach ($rResult as $aRow) {
        $row = [];
        for ($i = 0; $i < count($aColumns); $i++) {
            if (strpos($aColumns[$i], 'as') !== false && !isset($aRow[$aColumns[$i]])) {
                $_data = $aRow[strafter($aColumns[$i], 'as ')];
            } else {
                $_data = $aRow[$aColumns[$i]];
            }
            if ($aColumns[$i] == 'note') {
                $_data = process_text_content_for_display($aRow['note']);
            } elseif ($aColumns[$i] == 'paymentmode') {
                $_data = $aRow['name'];
                $payment_method_name = $_data;
                if (is_null($aRow['paymentmodeid'])) {
                    foreach ($payment_gateways as $gateway) {
                        if ($aRow['paymentmode'] == $gateway['id']) {
                            $_data = e($gateway['name']);
                            $payment_method_name = $_data;
                        }
                    }
                }

                if (!empty($aRow['paymentmethod'])) {
                    $_data .= ' - ' . e($aRow['paymentmethod']);
                    $payment_method_name = $_data;
                }

                if (!in_array($payment_method_name, $payment_modes_arr)) {
                    $payment_modes_arr[] = $payment_method_name;
                    $total_payment_modes_amount[$payment_method_name] = $aRow['amount'];
                } else {
                    $previous_amount = $total_payment_modes_amount[$payment_method_name];
                    $total_payment_modes_amount[$payment_method_name] = $previous_amount + $aRow['amount'];
                }
            } elseif ($aColumns[$i] == db_prefix() . 'invoicepaymentrecords.id') {
                $_data = '<a href="' . admin_url('payments/payment/' . $_data) . '" target="_blank">' . e($_data) . '</a>';
            } elseif ($aColumns[$i] == db_prefix() . 'invoicepaymentrecords.date') {
                $_data = e(_d($_data));
            } elseif ($aColumns[$i] == 'invoiceid') {
                $_data = '<a href="' . admin_url('invoices/list_invoices/' . $aRow[$aColumns[$i]]) . '" target="_blank">' . e(format_invoice_number($aRow['invoiceid'])) . '</a>';
            } elseif ($i == 3) {
                if (empty($aRow['deleted_customer_name'])) {
                    $_data = '<a href="' . admin_url('clients/client/' . $aRow['clientid']) . '" target="_blank">' . e($aRow['company']) . '</a>';
                } else {
                    $row[] = e($aRow['deleted_customer_name']);
                }
            } elseif ($aColumns[$i] == 'amount') {
                $footer_data['total_amount'] += $_data;
                $_data = e(app_format_money($_data, $currency->name));
            } elseif ($aColumns[$i] == 'status') {
                $_data = format_invoice_status($aRow['status']);
            }

            $row[] = $_data;
        }
        $output['aaData'][] = $row;
    }

    $total_payment_modes_amount_string = '';
    foreach ($total_payment_modes_amount as $key => $value) {
        $total_payment_modes_amount_string .= "$key: " . app_format_money($value, $currency->name) . "<br/>";
    }
    $total_payment_modes_amount_string .= "Total: " . app_format_money($footer_data['total_amount'], $currency->name);
    $footer_data['total_amount'] = $total_payment_modes_amount_string;
    $output['sums']              = $footer_data;
    echo json_encode($output);
}
