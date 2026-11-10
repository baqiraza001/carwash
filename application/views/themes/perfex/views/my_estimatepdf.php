<?php

defined('BASEPATH') or exit('No direct script access allowed');
$CI = &get_instance();

$dimensions = $pdf->getPageDimensions();

$info_right_column = '';
$info_left_column  = '';

$info_right_column .= '<span style="font-weight:bold;font-size:27px;">' . _l('estimate_pdf_heading') . '</span><br />';
$info_right_column .= '<b style="color:#4e4e4e;"># ' . $estimate_number . '</b>';

if (get_option('show_status_on_pdf_ei') == 1) {
    $info_right_column .= '<br /><span style="color:rgb(' . estimate_status_color_pdf($status) . ');text-transform:uppercase;">' . format_estimate_status($status, '', false) . '</span>';
}

// Add logo
$info_left_column .= pdf_logo_url();
// Write top left logo and right column info/text
pdf_multi_row($info_right_column, $info_left_column, $pdf, ($dimensions['wk'] / 2) - $dimensions['lm']);

$pdf->ln(2);

$organization_info = '<div style="color:#424242;">';
$organization_info .= format_organization_info();
$organization_info .= '</div>';

$estimate_info = '';

/* customer as a payer block*/
$carwash_customer = $CI->db->select('*')->where('userid', $estimate->client->userid)->get('tblcontacts')->row_array();
$carwash_customer_cf      = get_custom_fields('customers');
$first_last_name = is_empty_customer_company($estimate->client->userid) ? $carwash_customer['firstname'] .' '. $carwash_customer['lastname'] : get_company_name($estimate->client->userid);

$estimate_info .= '<b>Name: </b> '.$first_last_name .' <br /> ';
$estimate_info .= '<b>Phone: </b> '.$estimate->client->phonenumber .' <br />';
$estimate_info .= '<b>Address: </b> '.$estimate->client->address .' <br />';
foreach ($carwash_customer_cf as $custom_field) {
    $estimate_info .= '<b>'.$custom_field['name'].': </b>'.get_custom_field_value($estimate->client->userid, $custom_field['id'], 'customers').'<br />';
}

// ship to to
if ($estimate->include_shipping == 1 && $estimate->show_shipping_on_estimate == 1) {
    $estimate_info .= '<br /><b>' . _l('ship_to') . '</b>';
    $estimate_info .= '<div style="color:#424242;">';
    $estimate_info .= format_customer_info($estimate, 'estimate', 'shipping');
    $estimate_info .= '</div>';
}

$estimate_info .= '<br />' . _l('estimate_data_date') . ': ' . _d($estimate->date) . '<br />';

if (! empty($estimate->expirydate)) {
    $estimate_info .= _l('estimate_data_expiry_date') . ': ' . _d($estimate->expirydate) . '<br />';
}

if (! empty($estimate->reference_no)) {
    $estimate_info .= _l('reference_no') . ': ' . $estimate->reference_no . '<br />';
}

if ($estimate->sale_agent && get_option('show_sale_agent_on_estimates') == 1) {
    $estimate_info .= _l('sale_agent_string') . ': ' . get_staff_full_name($estimate->sale_agent) . '<br />';
}

if ($estimate->project_id && get_option('show_project_on_estimate') == 1) {
    $estimate_info .= _l('project') . ': ' . get_project_name_by_id($estimate->project_id) . '<br />';
}

$organization_info = hooks()->apply_filters('estimatepdf_organization_info', $organization_info, $estimate);

$left_info  = $swap == '1' ? $estimate_info : $organization_info;
$right_info = $swap == '1' ? $organization_info : $estimate_info;

pdf_multi_row($left_info, $right_info, $pdf, ($dimensions['wk'] / 2) - $dimensions['lm']);

/* payers list */
$payers_list = $CI->db->where('estimate_id', $estimate->id)->order_by('client_id', 'desc')->get('tbl_carwash_payers')->result_array();

$are_custom_fields_available = false;
foreach ($pdf_custom_fields as $field) {
    if (get_custom_field_value($estimate->id, $field['id'], 'estimate') !== '') {
        $are_custom_fields_available = true;
        break;
    }
}

if (count($payers_list) > 0) {
    $pdf->Ln(8);

    $box_width = 63;
    $row_gap = 6;
    $section_gap = 8;
    $start_x = $pdf->GetX();
    $start_y = $pdf->GetY();

    $pdf->SetLineStyle(['width' => 0.5]);
    $pdf->SetFillColor(0, 0, 0, 0);
    $pdf->SetTextColor(0, 0, 0, 100);
    $pdf->setCellMargins(0, 0, 0, 5);

    foreach (array_chunk($payers_list, 3) as $row) {
        $row_y = $pdf->GetY();
        $row_height = 0;
        $row_data = [];

        foreach ($row as $payer) {
            $html = '<b>Name: </b>'.get_relation_values(get_relation_data('customer', $payer['client_id']), 'customer')['name'].'<br />';

            if (!empty($payer['policy_number'])) $html .= '<b>Policy No.: </b>'.$payer['policy_number'].'<br />';
            if (!empty($payer['authorization_number'])) $html .= '<b>Authorization No.: </b>'.$payer['authorization_number'].'<br />';
            if (!empty($payer['date_of_loss']) && $payer['date_of_loss'] != '0000-00-00') $html .= '<b>Date of Loss: </b>'.date('j F, Y', strtotime($payer['date_of_loss'])).'<br />';
            if (!empty($payer['purchase'])) $html .= '<b>Purchase: </b>'.$payer['purchase'].'<br />';
            if (!empty($payer['order_number'])) $html .= '<b>Order No: </b>'.$payer['order_number'].'<br />';
            if (!empty($payer['amount'])) $html .= '<b>AMOUNT DUE: </b>'.$payer['amount'].'<br />';

            $height = $pdf->getStringHeight($box_width, $html, true, true, '', 1) + 2;
            $row_data[] = ['html' => $html, 'height' => $height];
            $row_height = max($row_height, $height);
        }

        $x = $start_x;

        foreach ($row_data as $box) {
            $pdf->SetXY($x, $row_y);
            $pdf->writeHTMLCell($box_width, $row_height, '', '', $box['html'], 0, 0, 1, true, 'L', true);
            $x += $box_width;
        }

        $start_y = $row_y + $row_height + $row_gap;
        $pdf->SetXY($start_x, $start_y);
    }

    /*
     * $start_y already contains:
     * last row Y + maximum cell height + row gap.
     *
     * Therefore custom/merge fields always start after
     * the tallest cell of the LAST row.
     */
    $pdf->SetXY($start_x, $start_y + $section_gap);

} elseif ($are_custom_fields_available) {
    $pdf->Ln(10);
} else {
    $pdf->Ln(20);
}

/* payers list */

/* custom fields */
$cf_string = '';
$cf_count = 1;
foreach ($pdf_custom_fields as $field) {
    $value = get_custom_field_value($estimate->id, $field['id'], 'estimate');
    if ($value == '') {
        continue;
    }
    $cf_string =  '<b>'.$field['name'].'</b>';
    $cf_string .= '<br />'.$value;
    
    $pdf->SetLineStyle(['width' => 0.5]);
    $pdf->SetFillColor(0, 0, 0,0);
    $pdf->SetTextColor(0, 0, 0,100);
    $pdf->setCellMargins(0,0,0,5);
    if($cf_count >= 4)
        $cf_box = 1;
    else
        $cf_box = 0;

    $pdf->writeHTMLCell(40, '', '', '', $cf_string, 0, $cf_box, 1, 0, 'L', 1);
    $cf_count++;
    if($cf_count > 4)
        $cf_count = 1;
}
if($are_custom_fields_available)
    $pdf->Ln(17);
/* custom fields */



// The items table
$items = get_items_table_data($estimate, 'estimate', 'pdf');

$tblhtml = $items->table();

$pdf->writeHTML($tblhtml, true, false, false, false, '');

$pdf->Ln(1);
$tbltotal = '';
$tbltotal .= '<table cellpadding="0" style="font-size:' . ($font_size + 4) . 'px">';
$tbltotal .= '
<tr>
    <td align="right" width="85%"><strong>' . _l('estimate_subtotal') . '</strong></td>
    <td align="right" width="15%">' . app_format_money($estimate->subtotal, $estimate->currency_name) . '</td>
</tr>';

if (is_sale_discount_applied($estimate)) {
    $tbltotal .= '
    <tr>
        <td align="right" width="85%"><strong>' . _l('estimate_discount');
    if (is_sale_discount($estimate, 'percent')) {
        $tbltotal .= ' (' . app_format_number($estimate->discount_percent, true) . '%)';
    }
    $tbltotal .= '</strong>';
    $tbltotal .= '</td>';
    $tbltotal .= '<td align="right" width="15%">-' . app_format_money($estimate->discount_total, $estimate->currency_name) . '</td>
    </tr>';
}

foreach ($items->taxes() as $tax) {
    $tbltotal .= '<tr>
    <td align="right" width="85%"><strong>' . $tax['taxname'] . ' (' . app_format_number($tax['taxrate']) . '%)' . '</strong></td>
    <td align="right" width="15%">' . app_format_money($tax['total_tax'], $estimate->currency_name) . '</td>
</tr>';
}

if ((int) $estimate->adjustment != 0) {
    $tbltotal .= '<tr>
    <td align="right" width="85%"><strong>' . _l('estimate_adjustment') . '</strong></td>
    <td align="right" width="15%">' . app_format_money($estimate->adjustment, $estimate->currency_name) . '</td>
</tr>';
}

$tbltotal .= '
<tr style="background-color:#f0f0f0;">
    <td align="right" width="85%"><strong>' . _l('estimate_total') . '</strong></td>
    <td align="right" width="15%">' . app_format_money($estimate->total, $estimate->currency_name) . '</td>
</tr>';

$tbltotal .= '</table>';

$pdf->writeHTML($tbltotal, true, false, false, false, '');

if (get_option('total_to_words_enabled') == 1) {
    // Set the font bold
    $pdf->SetFont($font_name, 'B', $font_size);
    $pdf->writeHTMLCell('', '', '', '', _l('num_word') . ': ' . $CI->numberword->convert($estimate->total, $estimate->currency_name), 0, 1, false, true, 'C', true);
    // Set the font again to normal like the rest of the pdf
    $pdf->SetFont($font_name, '', $font_size);
    $pdf->Ln(4);
}

if (! empty($estimate->clientnote)) {
    $pdf->Ln(4);
    $pdf->SetFont($font_name, 'B', $font_size);
    $pdf->Cell(0, 0, _l('estimate_note'), 0, 1, 'L', 0, '', 0);
    $pdf->SetFont($font_name, '', $font_size);
    $pdf->Ln(2);
    $pdf->writeHTMLCell('', '', '', '', $estimate->clientnote, 0, 1, false, true, 'L', true);
}

if (! empty($estimate->terms)) {
    $pdf->Ln(4);
    $pdf->SetFont($font_name, 'B', $font_size);
    $pdf->Cell(0, 0, _l('terms_and_conditions') . ':', 0, 1, 'L', 0, '', 0);
    $pdf->SetFont($font_name, '', $font_size);
    $pdf->Ln(2);
    $pdf->writeHTMLCell('', '', '', '', $estimate->terms, 0, 1, false, true, 'L', true);
}
