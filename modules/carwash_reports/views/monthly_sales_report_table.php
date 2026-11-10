<?php

defined('BASEPATH') or exit('No direct script access allowed');

$aColumns = [
    'tblinvoices.id as invoiceid',
    'tblinvoices.total as invoicetotal',
    'tblinvoices.date as invoicedate',
    'tblinvoices.clientid as invoiceclient',
    'tblinvoices.status as invoicestatus',
    'tblinvoices.currency as invoicecurrency',
    'tblinvoices.duedate as invoiceduedate',
    'tblinvoices.clientnote as invoiceclientnote',
];

$sIndexColumn = 'id';
$sTable       = 'tblinvoices';
$where        = [];

$CI = &get_instance();
$filters = $CI->input->post();

$reportFrom = isset($filters['report_from']) ? trim($filters['report_from']) : '';
$reportTo   = isset($filters['report_to']) ? trim($filters['report_to']) : '';

if ($reportFrom !== '') {
    if ($reportTo !== '') {
        $where[] = " AND tblinvoices.date BETWEEN " . $CI->db->escape($reportFrom) . " AND " . $CI->db->escape($reportTo);
    } else {
        $where[] = " AND tblinvoices.date = " . $CI->db->escape($reportFrom);
    }
}

$_statuses = [];
if (isset($filters['monthly_report_invoice_status']) && is_array($filters['monthly_report_invoice_status'])) {
    foreach ($filters['monthly_report_invoice_status'] as $status) {
        if ($status !== '' && $status !== null) {
            $_statuses[] = (int) $status;
        }
    }
}
if (!empty($_statuses)) {
    $where[] = ' AND tblinvoices.status IN (' . implode(',', $_statuses) . ')';
}

$search_payor_client_id = 0;
if (isset($filters['search_payor']) && $filters['search_payor'] !== '') {
    $search_payor_client_id = (int) $filters['search_payor'];

    $where[] = " AND EXISTS (
        SELECT 1
        FROM tbl_carwash_payers cp
        WHERE cp.invoice_id = tblinvoices.id
          AND cp.client_id = {$search_payor_client_id}
    )";
}

// No RIGHT JOIN. Base table is invoices; no GROUP BY needed.
$join = [];

$additionalSelect = [
    'tblinvoices.number as invoicenumber',
    'tblinvoices.prefix as invoiceprefix',
];

$result  = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, $additionalSelect);
$output  = $result['output'];
$rResult = $result['rResult'];

$CI->load->model('currencies_model');
$currency = $CI->currencies_model->get_base_currency();

$total_paid_amount   = 0;
$total_unpaid_amount = 0;

$payors_arr            = [];
$payors_due_amount_arr = [];

/**
 * STEP 1/2: Preload all needed data in bulk
 */
$invoiceIds       = [];
$invoiceClientIds = [];

foreach ($rResult as $aRow) {
    $invoiceIds[]       = (int) $aRow['invoiceid'];
    $invoiceClientIds[] = (int) $aRow['invoiceclient'];
}
$invoiceIds       = array_values(array_unique(array_filter($invoiceIds)));
$invoiceClientIds = array_values(array_unique(array_filter($invoiceClientIds)));

$payorsByInvoice = [];
$paymentSums     = []; // key: invoiceid_clientid => total_paid
$allClientIds    = $invoiceClientIds;

if (!empty($invoiceIds)) {
    // All payors for selected invoices
    $CI->db->select('invoice_id, client_id, amount')->from('tbl_carwash_payers');
    if(!empty($search_payor_client_id))
            $CI->db->where('client_id', $search_payor_client_id);
    $payorRows = $CI->db->where_in('invoice_id', $invoiceIds)->get()->result_array();

    foreach ($payorRows as $pr) {
        $invId = (int) $pr['invoice_id'];
        $cid   = (int) $pr['client_id'];

        if (!isset($payorsByInvoice[$invId])) {
            $payorsByInvoice[$invId] = [];
        }
        $payorsByInvoice[$invId][] = [
            'client_id' => $cid,
            'amount'    => (float) $pr['amount'],
        ];

        $allClientIds[] = $cid;
    }

    // Payment totals grouped by invoice + client
    $paymentRows = $CI->db
        ->select('invoiceid, client_id, SUM(amount) as total_paid', false)
        ->from('tblinvoicepaymentrecords')
        ->where_in('invoiceid', $invoiceIds)
        ->group_by(['invoiceid', 'client_id'])
        ->get()
        ->result_array();

    foreach ($paymentRows as $pm) {
        $key = ((int) $pm['invoiceid']) . '_' . ((int) $pm['client_id']);
        $paymentSums[$key] = (float) $pm['total_paid'];
    }
}

// Optional preload company names to avoid get_company_name DB calls in loop
$clientNameMap = [];
$allClientIds  = array_values(array_unique(array_filter($allClientIds)));
if (!empty($allClientIds)) {
    $clientRows = $CI->db
        ->select('userid, company')
        ->from('tblclients')
        ->where_in('userid', $allClientIds)
        ->get()
        ->result_array();

    foreach ($clientRows as $cr) {
        $clientNameMap[(int) $cr['userid']] = $cr['company'];
    }
}

// Build a stable name resolver so we can sort payors alphabetically.
$resolveClientName = function ($clientId) use (&$clientNameMap) {
    $clientId = (int) $clientId;
    if (isset($clientNameMap[$clientId]) && $clientNameMap[$clientId] !== '') {
        return $clientNameMap[$clientId];
    }

    $name = get_company_name($clientId);
    if (!isset($clientNameMap[$clientId])) {
        $clientNameMap[$clientId] = $name;
    }

    return $name;
};

$normalizeSortName = function ($name) {
    $normalized = html_entity_decode(strip_tags((string) $name), ENT_QUOTES, 'UTF-8');
    $normalized = trim(preg_replace('/\s+/', ' ', $normalized));

    if (function_exists('mb_strtolower')) {
        return mb_strtolower($normalized, 'UTF-8');
    }

    return strtolower($normalized);
};

foreach ($rResult as $aRow) {
    $row = [];

    $invoiceId       = (int) $aRow['invoiceid'];
    $invoiceClientId = (int) $aRow['invoiceclient'];
    $invoiceStatus   = (int) $aRow['invoicestatus'];
    $invoiceTotal    = (float) $aRow['invoicetotal'];

    $invoiceClientName = $resolveClientName($invoiceClientId);

    $row[] = '<a target="_blank" href="' . admin_url('invoices/invoice/' . $invoiceId) . '">' . $aRow['invoiceprefix'] . $aRow['invoicenumber'] . '</a>';
    $row[] = '<a target="_blank" href="' . admin_url('clients/client/' . $invoiceClientId) . '">' . $invoiceClientName . '</a>';
    $row[] = _d($aRow['invoicedate']);
    $row[] = '$' . number_format($invoiceTotal, 2);

    $payorHtmlLines = [];
    $totalPaid  = 0.0;

    $invoicePayors = isset($payorsByInvoice[$invoiceId]) ? $payorsByInvoice[$invoiceId] : [];
    if (!empty($invoicePayors)) {
        usort($invoicePayors, function ($a, $b) use ($resolveClientName, $normalizeSortName) {
            $nameA = $normalizeSortName($resolveClientName($a['client_id']));
            $nameB = $normalizeSortName($resolveClientName($b['client_id']));

            return strcmp($nameA, $nameB);
        });
    }

    foreach ($invoicePayors as $payor) {
        $payorClientId = (int) $payor['client_id'];
        $payorAmount   = (float) $payor['amount'];

        $sumKey            = $invoiceId . '_' . $payorClientId;
        $totalPaymentsMade = isset($paymentSums[$sumKey]) ? (float) $paymentSums[$sumKey] : 0.0;

        if ($totalPaymentsMade < $payorAmount && $invoiceStatus !== 2) {
            $paymentsOwed = $payorAmount - $totalPaymentsMade;
            $payorName    = $resolveClientName($payorClientId);

            $payorHtmlLines[] = '<div class="monthly-payor-line" style="display:block;margin-bottom:4px;line-height:1.4;">'
                . '<span class="label label-info s-status invoice-status-2">'
                . '<a target="_blank" href="' . admin_url('clients/client/' . $payorClientId) . '" style="">'
                . $payorName . ': ' . app_format_money($paymentsOwed, $currency->name)
                . '</a>'
                . '</span>'
                . '</div>';

            if (!in_array($payorClientId, $payors_arr, true)) {
                $payors_arr[] = $payorClientId;
            }

            if (!isset($payors_due_amount_arr[$payorClientId])) {
                $payors_due_amount_arr[$payorClientId] = 0;
            }
            $payors_due_amount_arr[$payorClientId] += $paymentsOwed;
        }

        $totalPaid += $totalPaymentsMade;
    }

    $payorsHtml = '';
    if (!empty($payorHtmlLines)) {
        $payorsHtml = '<div class="monthly-payor-export-lines" style="display:block;word-break:break-word;">' . implode("\n", $payorHtmlLines) . '</div>';
    }

    $remainingPayment = $invoiceTotal - $totalPaid;
    if ($remainingPayment < 0) {
        $remainingPayment = 0;
    }

    $row[] = '$' . number_format($totalPaid, 2);
    $row[] = '$' . number_format($remainingPayment, 2);
    $row[] = $aRow['invoiceduedate'];

    if ($invoiceStatus === 1) {
        $status = '<span class="label label-warning  s-status invoice-status-1">UnPaid</span>';
    } elseif ($invoiceStatus === 2) {
        $status = '<span class="label label-success  s-status invoice-status-2">Paid</span>';
    } elseif ($invoiceStatus === 3) {
        $status = '<span class="label label-warning  s-status invoice-status-2">Partially Paid</span>';
    } else {
        $status = '';
    }

    $row[] = $status;
    $row[] = $payorsHtml;

    $row['DT_RowClass'] = 'has-row-options';
    $output['aaData'][] = $row;

    $total_paid_amount   += $totalPaid;
    $total_unpaid_amount += $remainingPayment;
}

$footer_data['total']    = app_format_money($total_paid_amount, $currency->name);
$footer_data['subtotal'] = app_format_money($total_unpaid_amount, $currency->name);

$payors_dues       = '';
$total_payors_dues = 0;

$payorDueRows = [];
foreach ($payors_arr as $payor_client_id) {
    $payorName = $resolveClientName($payor_client_id);
    $dueAmount = isset($payors_due_amount_arr[$payor_client_id]) ? $payors_due_amount_arr[$payor_client_id] : 0;

    $payorDueRows[] = [
        'name'   => $payorName,
        'amount' => (float) $dueAmount,
    ];

    $total_payors_dues += (float) $dueAmount;
}

if (!empty($payorDueRows)) {
    usort($payorDueRows, function ($a, $b) use ($normalizeSortName) {
        $nameA = $normalizeSortName($a['name']);
        $nameB = $normalizeSortName($b['name']);

        return strcmp($nameA, $nameB);
    });

    $payorLines = [];
    foreach ($payorDueRows as $payorRow) {
        $payorLines[] = $payorRow['name'] . ': ' . app_format_money($payorRow['amount'], $currency->name);
    }

    $payors_dues = '<div class="monthly-payor-dues-list" style="white-space:pre-line;line-height:1.6;word-break:break-word;">' . htmlspecialchars(implode("\n", $payorLines), ENT_QUOTES, 'UTF-8') . '</div>';
    $payors_dues .= '<hr/>';
    $payors_dues .= '<div class="monthly-payor-total"><strong>Total: ' . app_format_money($total_payors_dues, $currency->name) . '</strong></div>';
}

$footer_data['discount_total'] = $payors_dues;
$output['sums'] = $footer_data;