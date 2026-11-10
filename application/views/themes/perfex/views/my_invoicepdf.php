<?php

defined('BASEPATH') or exit('No direct script access allowed');

$dimensions = $pdf->getPageDimensions();

$info_right_column = '';
$info_left_column  = '';

$info_right_column .= '<span style="font-weight:bold;font-size:27px;">' . _l('invoice_pdf_heading') . '</span><br />';
$info_right_column .= '<b style="color:#4e4e4e;"># ' . $invoice_number . '</b>';
if ($invoice->estimate_id) {
    $info_right_column .= '<br><b style="color:#4e4e4e;"># ' . format_estimate_number($invoice->estimate_id) . '</b>';
}

if (get_option('show_status_on_pdf_ei') == 1) {
    $info_right_column .= '<br /><span style="color:rgb(' . invoice_status_color_pdf($status) . ');text-transform:uppercase;">' . format_invoice_status($status, '', false) . '</span>';
}

if ($status != Invoices_model::STATUS_PAID && $status != Invoices_model::STATUS_CANCELLED && get_option('show_pay_link_to_invoice_pdf') == 1
    && found_invoice_mode($payment_modes, $invoice->id, false)) {
    $info_right_column .= ' - <a style="color:#84c529;text-decoration:none;text-transform:uppercase;" href="' . site_url('invoice/' . $invoice->id . '/' . $invoice->hash) . '"><1b>' . _l('view_invoice_pdf_link_pay') . '</1b></a>';
}

// Add logo
$info_left_column .= pdf_logo_url();
$CI = &get_instance();

// Write top left logo and right column info/text
pdf_multi_row($info_right_column, $info_left_column, $pdf, ($dimensions['wk'] / 2) - $dimensions['lm']);

$pdf->ln(2);

$organization_info = '<div style="color:#424242;">';

$organization_info .= format_organization_info();

$organization_info .= '</div>';

$invoice_info = '';

/* customer as a payer block*/
$carwash_customer = $CI->db->select('*')->where('userid', $invoice->client->userid)->get('tblcontacts')->row_array();

$carwash_customer_cf      = get_custom_fields('customers');

$first_last_name = is_empty_customer_company($invoice->client->userid) ? $carwash_customer['firstname'] .' '. $carwash_customer['lastname'] : get_company_name($invoice->client->userid);
$invoice_info = '<b>Name: </b> '.$first_last_name .' <br />';
$invoice_info .= '<b>Phone: </b> '.$invoice->client->phonenumber .' <br />';
$invoice_info .= '<b>Address: </b> '.$invoice->client->address .' <br />';
foreach ($carwash_customer_cf as $custom_field) {
    $invoice_info .= '<b>'.$custom_field['name'].': </b>'.get_custom_field_value($invoice->client->userid, $custom_field['id'], 'customers').'<br />';
}

// ship to to
if ($invoice->include_shipping == 1 && $invoice->show_shipping_on_invoice == 1) {
    $invoice_info .= '<br /><b>' . _l('ship_to') . '</b>';
    $invoice_info .= '<div style="color:#424242;">';
    $invoice_info .= format_customer_info($invoice, 'invoice', 'shipping');
    $invoice_info .= '</div>';
}

$invoice_info .= '<br />' . _l('invoice_data_date') . ' ' . _d($invoice->date) . '<br />';

$invoice_info = hooks()->apply_filters('invoice_pdf_header_after_date', $invoice_info, $invoice);

if (! empty($invoice->duedate)) {
    $invoice_info .= _l('invoice_data_duedate') . ' ' . _d($invoice->duedate) . '<br />';
    $invoice_info = hooks()->apply_filters('invoice_pdf_header_after_due_date', $invoice_info, $invoice);
}

if ($invoice->sale_agent && get_option('show_sale_agent_on_invoices') == 1) {
    $invoice_info .= _l('sale_agent_string') . ': ' . get_staff_full_name($invoice->sale_agent) . '<br />';
    $invoice_info = hooks()->apply_filters('invoice_pdf_header_after_sale_agent', $invoice_info, $invoice);
}

if ($invoice->project_id && get_option('show_project_on_invoice') == 1) {
    $invoice_info .= _l('project') . ': ' . get_project_name_by_id($invoice->project_id) . '<br />';
    $invoice_info = hooks()->apply_filters('invoice_pdf_header_after_project_name', $invoice_info, $invoice);
}

$invoice_info = hooks()->apply_filters('invoice_pdf_header_before_custom_fields', $invoice_info, $invoice);

$invoice_info      = hooks()->apply_filters('invoice_pdf_header_after_custom_fields', $invoice_info, $invoice);
$organization_info = hooks()->apply_filters('invoicepdf_organization_info', $organization_info, $invoice);
$invoice_info      = hooks()->apply_filters('invoice_pdf_info', $invoice_info, $invoice);

$left_info  = $swap == '1' ? $invoice_info : $organization_info;
$right_info = $swap == '1' ? $organization_info : $invoice_info;

pdf_multi_row($left_info, $right_info, $pdf, ($dimensions['wk'] / 2) - $dimensions['lm']);

/* payers list */
$payers_list = $CI->db->where('invoice_id', $invoice->id)->order_by('client_id', 'desc')->get('tbl_carwash_payers')->result_array();

$are_custom_fields_available = false;
foreach ($pdf_custom_fields as $field) {
    if (get_custom_field_value($invoice->id, $field['id'], 'invoice') !== '') {
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
    $value = get_custom_field_value($invoice->id, $field['id'], 'invoice');
    if ($value == '') {
        continue;
    }
    $cf_string =  '<b>'.$field['name'].'</b>';
    $cf_string .= '<br />'.$value;
    
    $pdf->SetLineStyle(['width' => 0.5]);
    $pdf->SetFillColor(0, 0, 0,0);
    $pdf->SetTextColor(0, 0, 0,100);
    $pdf->setCellMargins(0,0,0,5);
    if($cf_count >= 5)
        $cf_box = 1;
    else
        $cf_box = 0;

    $pdf->writeHTMLCell(40, '', '', '', $cf_string, 0, $cf_box, 1, 0, 'L', 1);
    $cf_count++;
    if($cf_count > 5)
        $cf_count = 1;
}
if($are_custom_fields_available)
    $pdf->Ln(17);
/* custom fields */



// The items table
$items = get_items_table_data($invoice, 'invoice', 'pdf');

$tblhtml = $items->table();

$pdf->writeHTML($tblhtml, true, false, false, false, '');

$pdf->Ln(1);

$tbltotal = '';
$tbltotal .= '<table cellpadding="0" style="font-size:' . ($font_size + 4) . 'px">';
$tbltotal .= '
<tr>
    <td align="right" width="85%"><strong>' . _l('invoice_subtotal') . '</strong></td>
    <td align="right" width="15%">' . app_format_money($invoice->subtotal, $invoice->currency_name) . '</td>
</tr>';

if (is_sale_discount_applied($invoice)) {
    $tbltotal .= '
    <tr>
        <td align="right" width="85%"><strong>' . _l('invoice_discount');
    if (is_sale_discount($invoice, 'percent')) {
        $tbltotal .= ' (' . app_format_number($invoice->discount_percent, true) . '%)';
    }
    $tbltotal .= '</strong>';
    $tbltotal .= '</td>';
    $tbltotal .= '<td align="right" width="15%">-' . app_format_money($invoice->discount_total, $invoice->currency_name) . '</td>
    </tr>';
}

foreach ($items->taxes() as $tax) {
    $tbltotal .= '<tr>
    <td align="right" width="85%"><strong>' . $tax['taxname'] . ' (' . app_format_number($tax['taxrate']) . '%)' . '</strong></td>
    <td align="right" width="15%">' . app_format_money($tax['total_tax'], $invoice->currency_name) . '</td>
</tr>';
}

if ((int) $invoice->adjustment != 0) {
    $tbltotal .= '<tr>
    <td align="right" width="85%"><strong>' . _l('invoice_adjustment') . '</strong></td>
    <td align="right" width="15%">' . app_format_money($invoice->adjustment, $invoice->currency_name) . '</td>
</tr>';
}

$tbltotal .= '
<tr style="background-color:#e5e7eb;">
    <td align="right" width="85%"><strong>' . _l('invoice_total') . '</strong></td>
    <td align="right" width="15%">' . app_format_money($invoice->total, $invoice->currency_name) . '</td>
</tr>';

if (count($invoice->payments) > 0 && get_option('show_total_paid_on_invoice') == 1) {
    $tbltotal .= '
    <tr>
        <td align="right" width="85%"><strong>' . _l('invoice_total_paid') . '</strong></td>
        <td align="right" width="15%">-' . app_format_money(sum_from_table(db_prefix() . 'invoicepaymentrecords', [
        'field' => 'amount',
        'where' => [
            'invoiceid' => $invoice->id,
        ],
    ]), $invoice->currency_name) . '</td>
    </tr>';
}

if (get_option('show_credits_applied_on_invoice') == 1 && $credits_applied = total_credits_applied_to_invoice($invoice->id)) {
    $tbltotal .= '
    <tr>
        <td align="right" width="85%"><strong>' . _l('applied_credits') . '</strong></td>
        <td align="right" width="15%">-' . app_format_money($credits_applied, $invoice->currency_name) . '</td>
    </tr>';
}

if (get_option('show_amount_due_on_invoice') == 1 && $invoice->status != Invoices_model::STATUS_CANCELLED) {
    $tbltotal .= '<tr style="background-color:#e5e7eb;">
       <td align="right" width="85%"><strong>' . _l('invoice_amount_due') . '</strong></td>
       <td align="right" width="15%">' . app_format_money($invoice->total_left_to_pay, $invoice->currency_name) . '</td>
   </tr>';
}

$tbltotal .= '</table>';
$pdf->writeHTML($tbltotal, true, false, false, false, '');

if (get_option('total_to_words_enabled') == 1) {
    // Set the font bold
    $pdf->SetFont($font_name, 'B', $font_size);
    $pdf->writeHTMLCell('', '', '', '', _l('num_word') . ': ' . $CI->numberword->convert($invoice->total, $invoice->currency_name), 0, 1, false, true, 'C', true);
    // Set the font again to normal like the rest of the pdf
    $pdf->SetFont($font_name, '', $font_size);
    $pdf->Ln(4);
}

if (count($invoice->payments) > 0 && get_option('show_transactions_on_invoice_pdf') == 1) {
    $pdf->Ln(4);
    $border = 'border-bottom-color:#000000;border-bottom-width:1px;border-bottom-style:solid; 1px solid black;';
    $pdf->SetFont($font_name, 'B', $font_size);
    $pdf->Cell(0, 0, _l('invoice_received_payments'), 0, 1, 'L', 0, '', 0);
    $pdf->SetFont($font_name, '', $font_size);
    $pdf->Ln(4);
    $tblhtml = '<table width="100%" bgcolor="#fff" cellspacing="0" cellpadding="0" border="0">
        <tr height="20"  style="color:#000;border:1px solid #000;">
        <th width="25%;" style="' . $border . '">' . _l('invoice_payments_table_number_heading') . '</th>
        <th width="25%;" style="' . $border . '">' . _l('invoice_payments_table_mode_heading') . '</th>
        <th width="25%;" style="' . $border . '">' . _l('invoice_payments_table_date_heading') . '</th>
        <th width="25%;" style="' . $border . '">' . _l('invoice_payments_table_amount_heading') . '</th>
    </tr>';
    $tblhtml .= '<tbody>';

    foreach ($invoice->payments as $payment) {
        $payment_name = $payment['name'];
        if (! empty($payment['paymentmethod'])) {
            $payment_name .= ' - ' . $payment['paymentmethod'];
        }
        $tblhtml .= '
            <tr>
            <td>' . $payment['paymentid'] . '</td>
            <td>' . $payment_name . '</td>
            <td>' . _d($payment['date']) . '</td>
            <td>' . app_format_money($payment['amount'], $invoice->currency_name) . '</td>
            </tr>
        ';
    }
    $tblhtml .= '</tbody>';
    $tblhtml .= '</table>';
    $pdf->writeHTML($tblhtml, true, false, false, false, '');
}

if (found_invoice_mode($payment_modes, $invoice->id, true, true)) {
    $pdf->Ln(4);
    $pdf->SetFont($font_name, 'B', $font_size);
    $pdf->Cell(0, 0, _l('invoice_html_offline_payment'), 0, 1, 'L', 0, '', 0);
    $pdf->SetFont($font_name, '', $font_size);

    foreach ($payment_modes as $mode) {
        if (is_numeric($mode['id'])) {
            if (! is_payment_mode_allowed_for_invoice($mode['id'], $invoice->id)) {
                continue;
            }
        }
        if (isset($mode['show_on_pdf']) && $mode['show_on_pdf'] == 1) {
            $pdf->Ln(1);
            $pdf->Cell(0, 0, $mode['name'], 0, 1, 'L', 0, '', 0);
            $pdf->Ln(2);
            $pdf->writeHTMLCell('', '', '', '', $mode['description'], 0, 1, false, true, 'L', true);
        }
    }
}

if (! empty($invoice->clientnote)) {
    $pdf->Ln(4);
    $pdf->SetFont($font_name, 'B', $font_size);
    $pdf->Cell(0, 0, _l('invoice_note'), 0, 1, 'L', 0, '', 0);
    $pdf->SetFont($font_name, '', $font_size);
    $pdf->Ln(2);
    $pdf->writeHTMLCell('', '', '', '', $invoice->clientnote, 0, 1, false, true, 'L', true);
}

if (! empty($invoice->terms)) {
    $pdf->Ln(4);
    $pdf->SetFont($font_name, 'B', $font_size);
    $pdf->Cell(0, 0, _l('terms_and_conditions'), 0, 1, 'L', 0, '', 0);
    $pdf->SetFont($font_name, '', $font_size);
    $pdf->Ln(2);
    $pdf->writeHTMLCell('', '', '', '', $invoice->terms, 0, 1, false, true, 'L', true);
}
