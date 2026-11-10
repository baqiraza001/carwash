<?php

defined('BASEPATH') or exit('No direct script access allowed');

$aColumns = [
    'id',
    'email',
    'delivered',
    'opened',
    'clicked',
    'bounced',
    'rejected',
    'unsubscribed',
    'created_at'
];

$sIndexColumn = 'id';
$sTable       = db_prefix() . 'email_newsletter_campaign_details';

$CI =& get_instance();

$campaign_id = !empty($CI->input->get('campaign_id')) ? $CI->input->get('campaign_id') : 0;

$join = [];
$where = [];
array_push($where,"AND campaign_id=$campaign_id");


$result = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where);

$output  = $result['output'];
$rResult = $result['rResult'];


foreach ($rResult as $aRow) {
    $row = [];

    $row[] = $aRow['id'];
    $row[] = $aRow['email'];
    $row[] = $aRow['delivered'];
    $row[] = $aRow['opened'];
    $row[] = $aRow['clicked'];
    $row[] = $aRow['bounced'];
    $row[] = $aRow['rejected'];
    $row[] = $aRow['unsubscribed'];
    
    if (isset($aRow['created_at']) && !empty($aRow['created_at']))
        $row[] = date("d M, Y",strtotime($aRow['created_at']));
    else
        $row[] = '';
        
    $output['aaData'][] = $row;
}
