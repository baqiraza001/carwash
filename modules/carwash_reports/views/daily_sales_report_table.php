<?php

defined('BASEPATH') or exit('No direct script access allowed');

$aColumns = [
    'tblinvoicepaymentrecords.id as paymentid',
    'tblinvoicepaymentrecords.invoiceid as paymentinvoiceid',
    'tblinvoicepaymentrecords.client_id as paymentclient',
    'tblinvoicepaymentrecords.paymentmode as paymentmode',
    'tblinvoicepaymentrecords.transactionid as paymenttransaction',
    'tblinvoicepaymentrecords.amount as paymentamount',
    'tblinvoicepaymentrecords.date as paymentdate',
    'tblinvoicepaymentrecords.note as paymentnote',
];

$sIndexColumn = 'id';
$sTable       ='tblinvoicepaymentrecords';
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
        array_push($where," AND tblinvoicepaymentrecords.date BETWEEN '".$filters['report_from']."' AND '".$filters['report_to']."'");
    } else {
        array_push($where," AND tblinvoicepaymentrecords.date = '".$filters['report_from']."'");
    }
}


if(isset($filters['invoice_id']) && !empty($filters['invoice_id'])){
    array_push($where," AND tblinvoicepaymentrecords.invoiceid = '".$filters['invoice_id']."'");
}

$join = [
    'JOIN tblinvoices on tblinvoices.id = tblinvoicepaymentrecords.invoiceid',
    'JOIN tblpayment_modes on tblpayment_modes.id = paymentmode',
];
$additionalSelect = [
    'tblinvoices.number as invoicenumber',
    'tblinvoices.prefix as invoiceprefix',
    'tblpayment_modes.name as paymentmodename'
];
$result = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, $additionalSelect);

$output  = $result['output'];

$rResult = $result['rResult'];

foreach ($rResult as $aRow) {

    $row = [];

    $row[] = $aRow['paymentid'];
    $row[] = '<a target="_blank" href="'.admin_url('invoices/invoice/'.$aRow['paymentinvoiceid']).'">'.$aRow['invoiceprefix'].$aRow['invoicenumber'].'</a>';
    $row[] = '<a target="_blank" href="'.admin_url('clients/client/'.$aRow['paymentclient']).'">'.get_company_name($aRow['paymentclient']).'</a>';
    $row[] = $aRow['paymentmodename'];
    $row[] = $aRow['paymenttransaction'];
    $row[] = $aRow['paymentamount'];
    $row[] = $aRow['paymentnote'];
    $row[] = $aRow['paymentdate'];


    $row['DT_RowClass'] = 'has-row-options';
    $output['aaData'][] = $row;

}

