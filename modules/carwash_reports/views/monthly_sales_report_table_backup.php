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
$sTable       ='tbl_carwash_payers';
$where = [];
$CI = &get_instance();
$filters = $CI->input->post();
$total = (isset($filters['total']) ? $filters['total'] : '' );
if(isset($filters) && count($filters) > 0)
{
    if($total != '') {}
}

if(isset($filters['report_from']) && !empty($filters['report_from'])){
    if(isset($filters['report_to']) && !empty($filters['report_to'])){
        array_push($where," AND tblinvoices.date BETWEEN '".$filters['report_from']."' AND '".$filters['report_to']."'");
    } else {
        array_push($where," AND tblinvoices.date = '".$filters['report_from']."'");
    }
}

$_statuses = [];
if (is_array($filters['monthly_report_invoice_status'])) {
    foreach ($filters['monthly_report_invoice_status'] as $status) {
        if ($status != '') {
            array_push($_statuses, $CI->db->escape_str($status));
        }
    }
}
if (count($_statuses) > 0) {
    array_push($where, 'AND tblinvoices.status IN (' . implode(', ', $_statuses) . ')');
}

if(isset($filters['search_payor']) && !empty($filters['search_payor']))
    array_push($where," AND tblclients.company Like '%".$filters['search_payor']."%'");
    
$join = ['RIGHT JOIN tblclients ON tblclients.userid = tbl_carwash_payers.client_id '];
array_push($join, 'RIGHT JOIN tblinvoices ON tblinvoices.id = tbl_carwash_payers.invoice_id ');



$additionalSelect = [
    'tblinvoices.number as invoicenumber',
    'tblinvoices.prefix as invoiceprefix',

];
$result = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, $additionalSelect, 'GROUP BY tblinvoices.id');

$output  = $result['output'];

$rResult = $result['rResult'];

$CI = &get_instance();
$CI->load->model('currencies_model');
$currency = $CI->currencies_model->get_base_currency();

$total_paid_amount = 0;
$total_unpaid_amount = 0;

$payors_arr = [];
$payors_due_amount_arr = [];
foreach ($rResult as $aRow) {

    $row = [];

    $row[] = '<a target="_blank" href="'.admin_url('invoices/invoice/'.$aRow['invoiceid']).'">'.$aRow['invoiceprefix'].$aRow['invoicenumber'].'</a>';
    $row[] = '<a target="_blank" href="'.admin_url('clients/client/'.$aRow['invoiceclient']).'">'.get_company_name($aRow['invoiceclient']).'</a>';
    $row[] = _d($aRow['invoicedate']);
    $row[] = '$'.number_format($aRow['invoicetotal'],2);


    $payorsHtml = '';
    $payors = $CI->db->select('*')->where('invoice_id', $aRow['invoiceid'])->get('tbl_carwash_payers')->result_array();
    $totalPaid = 0;
    foreach($payors as $payor){
        $totalPaymentsMade = $CI->db->select_sum('amount')->where('invoiceid', $aRow['invoiceid'])->where('client_id', $payor['client_id'])->get('tblinvoicepaymentrecords')->row('amount');
        if($totalPaymentsMade < $payor['amount'] && $aRow['invoicestatus'] != 2){
            $paymentsOwed = $payor['amount'] - $totalPaymentsMade;
            $payorsHtml .= '<span class="label label-info  s-status invoice-status-2"><a target="_blank" href="'.admin_url('clients/client/'.$payor['client_id']).'">'.get_company_name($payor['client_id']).': '.app_format_money($paymentsOwed, $currency->name).'</a></span>';
        
            /* calculate total amount remaining for each payor for all invoices in time period */
            $payor_client_id = $payor['client_id'];
            if(!in_array($payor_client_id, $payors_arr))
                $payors_arr[] = $payor_client_id;
            
            $payors_due_amount_arr[$payor_client_id] = $payors_due_amount_arr[$payor_client_id] + $paymentsOwed;

            /* calculate total amount remaining for each payor for all invoices in time period */
            
        }
        $totalPaid += $totalPaymentsMade;
    }
    $remainingPayment = $aRow['invoicetotal'] - $totalPaid;
    if($remainingPayment < 0) $remainingPayment = 0;


    $row[] = '$'.number_format($totalPaid,2);
    $row[] = '$'.number_format($remainingPayment,2);
    $row[] = $aRow['invoiceduedate'];

    if($aRow['invoicestatus'] == 1){
        $status = '<span class="label label-warning  s-status invoice-status-1">UnPaid</span>';
    }else if($aRow['invoicestatus'] == 2){
        $status = '<span class="label label-success  s-status invoice-status-2">Paid</span>';
    }else if($aRow['invoicestatus'] == 3){
        $status = '<span class="label label-warning  s-status invoice-status-2">Partially Paid</span>';
    } else {
        $status = '';
    }

    $row[] = $status;
    $row[] = $payorsHtml;


    $row['DT_RowClass'] = 'has-row-options';
    $output['aaData'][] = $row;
    $total_paid_amount += $totalPaid;
    $total_unpaid_amount += $remainingPayment;
}
    
    $footer_data['total'] = app_format_money($total_paid_amount, $currency->name);
    $footer_data['subtotal'] = app_format_money($total_unpaid_amount, $currency->name);
    
    $payors_dues = '';
    $total_payors_dues = 0;
    foreach($payors_arr as $payor_client_id)
    {
        $payors_dues .= get_company_name($payor_client_id).": ".  app_format_money($payors_due_amount_arr[$payor_client_id], $currency->name) . "\n"."<br />";
        $total_payors_dues += $payors_due_amount_arr[$payor_client_id];
        
    }
    
    if($payors_dues){
        $payors_dues .= "<hr/>";
        $payors_dues .= "Total: ".app_format_money($total_payors_dues, $currency->name);
    }

    $footer_data['discount_total'] = $payors_dues;
    
    $output['sums'] = $footer_data;
