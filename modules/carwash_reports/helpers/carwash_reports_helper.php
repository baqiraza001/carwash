<?php 
function render_monthly_sales_datatable($headings = [], $class = '', $additional_classes = [''], $table_attributes = [])
{
    $_additional_classes = '';
    $_table_attributes   = ' ';
    if (count($additional_classes) > 0) {
        $_additional_classes = ' ' . implode(' ', $additional_classes);
    }
    $CI      = & get_instance();
    $browser = $CI->agent->browser();
    $IEfix   = '';
    if ($browser == 'Internet Explorer') {
        $IEfix = 'ie-dt-fix';
    }

    foreach ($table_attributes as $key => $val) {
        $_table_attributes .= $key . '=' . '"' . $val . '" ';
    }

    $table = '<div class="' . $IEfix . '"><table' . $_table_attributes . 'class="dt-table-loading table table-' . $class . '' . $_additional_classes . '">';
    $table .= '<thead>';
    $table .= '<tr>';
    foreach ($headings as $heading) {
        if (!is_array($heading)) {
            $table .= '<th>' . $heading . '</th>';
        } else {
            $th_attrs = '';
            if (isset($heading['th_attrs'])) {
                foreach ($heading['th_attrs'] as $key => $val) {
                    $th_attrs .= $key . '=' . '"' . $val . '" ';
                }
            }
            $th_attrs = ($th_attrs != '' ? ' ' . $th_attrs : $th_attrs);
            $table .= '<th' . $th_attrs . '>' . $heading['name'] . '</th>';
        }
    }
    $table .= '</tr>';
    $table .= '</thead>';
    $table .= '<tbody></tbody>';
    $table .= '<tfoot>
      <tr>
         <td></td>
         <td></td>
         <td></td>
         <td></td>
         <td class="total"></td>
         <td class="subtotal"></td>
         <td></td>
         <td></td>
         <td></td>
    </tr>
    </tfoot>';
    $table .= '</table></div>';
    echo $table;
}

function carwash_reports_format_invoice_number($id)
{
    $CI = &get_instance();

    $CI->db->select('date,number,prefix,number_format,status')
        ->from(db_prefix() . 'invoices')
        ->where('id', $id);

    $invoice = $CI->db->get()->row();

    if (!$invoice) {
        return '';
    }

    if (!class_exists('Invoices_model', false)) {
        get_instance()->load->model('invoices_model');
    }

    if ($invoice->status == Invoices_model::STATUS_DRAFT) {
        $number = $invoice->prefix . 'DRAFT';
    } else {
        $number = carwash_reports_sales_number_format($invoice->number, 4, $invoice->prefix, $invoice->date);
    }

    return hooks()->apply_filters('carwash_reports_format_invoice_number', $number, [
        'id'      => $id,
        'invoice' => $invoice,
    ]);
}

function carwash_reports_sales_number_format($number, $format, $applied_prefix, $date)
{
    $originalNumber = $number;
    $prefixPadding  = get_option('number_padding_prefixes');

    if ($format == 1) {
        // Number based
        $number = $applied_prefix . str_pad($number, $prefixPadding, '0', STR_PAD_LEFT);
    } elseif ($format == 2) {
        // Year based
        $number = $applied_prefix . date('Y', strtotime($date)) . '/' . str_pad($number, $prefixPadding, '0', STR_PAD_LEFT);
    } elseif ($format == 3) {
        // Number-yy based
        $number = $applied_prefix . str_pad($number, $prefixPadding, '0', STR_PAD_LEFT) . '-' . date('y', strtotime($date));
    } elseif ($format == 4) {
        // Number-mm-yyyy based
        $number = $applied_prefix . str_pad($number, $prefixPadding, '0', STR_PAD_LEFT) . '/' . date('d', strtotime($date)) . '/' . date('m', strtotime($date)) . '/' . date('Y', strtotime($date));
    }

    return hooks()->apply_filters('carwash_reports_sales_number_format', $number, [
        'format'         => $format,
        'date'           => $date,
        'number'         => $originalNumber,
        'prefix_padding' => $prefixPadding,
    ]);
}

?>
