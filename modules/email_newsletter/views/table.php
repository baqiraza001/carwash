<?php

defined('BASEPATH') or exit('No direct script access allowed');

$aColumns = [
    'id',
    'campaign_name',
    'campaign_type',
    'total_emails_to_send',
    'emails_sent',
    'emails_failed',
    'created_at'
];

$sIndexColumn = 'id';
$sTable       = db_prefix() . 'email_newsletter_newsletter_history';

$join = [];
$where = [];

$result = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, [
    'id'
]);

$output  = $result['output'];
$rResult = $result['rResult'];


foreach ($rResult as $aRow) {
    $row = [];
    for ($i = 0; $i < count($aColumns); $i++) {

        $campaign_url = admin_url('email_newsletter/campaign_details/'.$aRow['id']);
        $row[] = "<a href='".$campaign_url."'>".$aRow['campaign_name']."</a>";

        $row[] = $aRow['campaign_type'];

        $row[] = $aRow['total_emails_to_send'];

        $row[] = email_newsletter_get_emails_count($aRow['id'], 'delivered');

        $row[] = email_newsletter_get_emails_count($aRow['id'], 'bounced');

        $row[] = $aRow['created_at'];


        $options = '<div class="tw-flex tw-items-center tw-space-x-3">';
        $options .= '<a href="' . $campaign_url . '" class="tw-text-neutral-500 hover:tw-text-neutral-700 focus:tw-text-neutral-700">
        <i class="fas fa-eye fa-lg"></i></a>';
        $options .= '<a href="' . admin_url('email_newsletter/edit/'.$aRow['id']) . '" class="tw-text-neutral-500 hover:tw-text-neutral-700 focus:tw-text-neutral-700">
        <i class="fas fa-pencil fa-lg"></i></a>';

        $options .= '</div>';

        $row[]              = $options;
    }

    $output['aaData'][] = $row;
}
