<?php
defined('BASEPATH') or exit('No direct script access allowed');
$custom_fields = get_table_custom_fields('assets');
$aColumns = [
    db_prefix() . '_commissions.id as id',
    db_prefix() . '_commissions.clientid',
    db_prefix() . 'invoices.clientid as referral',
    'amount',
    db_prefix() . '_commissions.status as status',
    '(SELECT name FROM tblleads_sources WHERE id = source) as source',
    'invoice_id',
];

$sIndexColumn = 'id';
$sTable = db_prefix() . '_commissions';
$CI = &get_instance();
$where = array('AND amount <> "" AND amount IS NOT NULL');
$join = array('LEFT JOIN '.db_prefix() . 'invoices ON '.db_prefix() . 'invoices.id = '.db_prefix() . '_commissions.invoice_id');
if (isset($_GET['date']) && !empty($_GET['date'])){
    $date_parts = explode(' - ',$_GET['date']);
    $date_1 = to_sql_date($date_parts[0]);
    $date_2 = to_sql_date($date_parts[1]);
    array_push($where,'AND DATE('.db_prefix() . 'invoices.date) BETWEEN "'.$date_1.'" AND "'.$date_2.'"');
}


if(isset($_GET['source']) && !empty($_GET['source'])){
    $source = $_GET['source'];
    array_push($where , 'AND source ='.$source);
    
}
$result = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, array(db_prefix() . '_commissions.clientid',db_prefix() . 'invoices.date',db_prefix() . '_commissions.method'));
$output = $result['output'];
$rResult = $result['rResult'];

foreach ($rResult as $aRow) {

    $row = [];
    
    $row[] = '<a href="' . admin_url('clients/client/' . $aRow['clientid']) . '">' . get_company_name($aRow['clientid']) . '</a>';
    $row[] = '<a href="' . admin_url('clients/client/' . $aRow['referral']) . '">' . get_company_name($aRow['referral']) . '</a>';
    $row[] = $aRow['amount'];

    if(isset($aRow['status']) && $aRow['status'] == 'Unpaid'){
        $row[] = '<span class="label label inline-block text-primary" style="color: #d43f3a;border:1px solid #d43f3a;font-size:12px;">'._l('referrals_unpaid').'</span>';
    }else{
        $row[] = '<span class="label label inline-block text-primary" style="color: #84c529;border:1px solid #84c529;font-size:12px;">'._l('referrals_paid').'</span>';
    }
    
    $row[] = $aRow['source'];
    $row[] = isset($aRow['date']) ? _d($aRow['date']) : '';
    $row[] = '<a href="' . admin_url('invoices/list_invoices/' . $aRow['invoice_id']) . '" target="_blank">' . format_invoice_number($aRow['invoice_id']) . '</a>';

    $options = "";
    if(isset($aRow['status']) && $aRow['status'] == 'Unpaid'){
        $options .= '<button class="btn btn-primary pay_commission" data-id="'.$aRow['id'].'" data-amount="'.$aRow['amount'].'" data-status="'.$aRow['status'].'" data-method="'.$aRow['method'].'" data-clientid="'.$aRow['clientid'].'">'._l('referrals_pay').'</button>';
    }else{
        $options .= '<button disabled class="btn btn-default pay_commission">'._l('referrals_paid').'</button>';
    }
    
    $row[] = $options;

    $row['DT_RowClass'] = 'has-row-options';
    $output['aaData'][] = $row;
}
