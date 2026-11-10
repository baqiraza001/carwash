<?php
defined('BASEPATH') or exit('No direct script access allowed');
$pdf->setPrintHeader(false);
$pdf->setTopMargin(15);
$pdf->AddPage();
$dimensions = $pdf->getPageDimensions();

$info_right_column = '';
$info_left_column  = '';

// Add logo
$info_right_column .= pdf_logo_url();

// Write top left logo and right column info/text


$company_info = format_organization_info();
$company_info = str_replace('<b style="color:black" class="company-name-formatted">','', $company_info);
$company_info = str_replace('</b>','', $company_info);
$organization_info = '<div style="color:#424242;text-transform:uppercase;line-height: 1.5;text-align:center;font-size: 10px;">'.$company_info.'</br></br></div>';
$organization_info .= '<div style="color:#424242;text-transform:uppercase;line-height: 1.5;text-align:center;font-size: 10px;margin-top: 10px;">Please MAKE CHEQUES PAYABLE TO '. '<br>' ."\"6TH AVENUE AUTO GLASS\"" .'<br>'. 'THANK YOU!</div>';

// Bill to
$invoice_info = '';

$left_info  = $organization_info;
$right_info = $invoice_info;
pdf_multi_row($info_right_column, $organization_info, $pdf, ($dimensions['wk'] / 2) - $dimensions['lm']);


$pdf->Ln(4);
$pdf->writeHTML('<p style="font-weight: bold; font-size: 15px; line-height: 2; text-transform: uppercase;">'.trim($payor_name).'</p>', true, false, false, false, 'C');
$pdf->writeHTML('<p style="font-weight: bold; font-size: 12px; text-transform: uppercase;">'.$report_to_date.'</p>', true, false, false, false, 'C');



$border = 'font-weight: bold;font-size: 10px;';
$pdf->Ln(4);
$tblhtml = '<table width="100%" bgcolor="#fff" cellspacing="0" cellpadding="5" border="0">
    <tr height="20"  style="color:#000;">
    <th width="15%;" style="' . $border . '">INVOICE</th>
    <th width="50%;" style="' . $border . '">CUSTOMER</th>
    <th width="15%;" style="' . $border . '">DATE</th>
    <th style="' . $border . '">AMOUNT</th>
</tr>';
$tblhtml .= '<tbody>';
foreach ($records as $record) {
    $tblhtml .= '
        <tr>
        <td style="font-size: 10px;">' . $record[0] . '</td>
        <td style="font-size: 10px;">' . $record[1] . '</td>
        <td style="font-size: 10px;">' . $record[2] . '</td>
        <td style="font-size: 10px;">' . $record[3] . '</td>
        </tr>';
}
$tblhtml .= '
        <tr style="font-size: 10px; font-weight: bold; line-height: 6;">
        <td>TOTAL</td>
        <td></td>
        <td></td>
        <td>' . $remainingPayment . '</td>
        </tr>';
$tblhtml .= '</tbody>';
$tblhtml .= '</table>';
$pdf->writeHTML($tblhtml, true, false, false, false, '');
