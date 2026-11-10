<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Carwash_reports extends AdminController {

    private $_module = 'carwash_reports';
    private $_title = '';

    public function __construct(){

        parent::__construct();

       // $this->load->model('carwash_reports_model', 'carwash_reports_model');
        //$this->load->library('Carwash_reports_repository',NULL,'module_repo');
    }

    public function daily_sales_report()
    {
        if (!has_permission('carwash_reports', '', 'daily_sales_report')) {
            access_denied(_l('carwash_reports'));
        }

        if ($this->input->is_ajax_request()) {
            $this->app->get_table_data(module_views_path('carwash_reports', 'daily_sales_report_table'));
        }

        //breadcrumb

        $data['form_heading'] = _l('Daily Sales Report');
        $data['title'] = _l('Daily Sales Report');
        $breadcrumb_data['current_page_title'] = $data['title'];
        $breadcrumb_data['breadcrumb_array'] = array(
            admin_url('') => 'Dashboard',
            admin_url('carwash_reports/daily_sales_report') => _l('Daily Sales Report'),
        );
        $data['breadcrumb_data'] = $breadcrumb_data;
        $this->load->view('daily_sales_report',$data);
    }

    public function monthly_sales_report()
    {
        if (!has_permission('carwash_reports', '', 'monthly_sales_report')) {
            access_denied(_l('carwash_reports'));
        }

        if ($this->input->is_ajax_request()) {
            $this->app->get_table_data(module_views_path('carwash_reports', 'monthly_sales_report_table'));

//            echo json_encode($output);
//            die;
        }

        //breadcrumb
        $data['form_heading'] = _l('Monthly Sales Report');
        $data['title'] = _l('Monthly Sales Report');
        $breadcrumb_data['current_page_title'] = $data['title'];
        $breadcrumb_data['breadcrumb_array'] = array(
            admin_url('') => 'Dashboard',
            admin_url('carwash_reports/monthly_sales_report') => _l('Monthly Sales Report'),
        );
        $data['breadcrumb_data'] = $breadcrumb_data;
        
        $this->load->model('invoices_model');
        $data['invoice_statuses']      = $this->invoices_model->get_statuses();
        
        $this->load->view('monthly_sales_report',$data);
    }
    
    
    public function monthly_sales_report_pdf()
    {
        if (!$this->input->is_ajax_request() || !has_permission('carwash_reports', '', 'monthly_sales_report')) {
            access_denied(_l('carwash_reports'));
        }
        
        ini_set('max_execution_time', 0);
        
        // =========== DB Queries =================
        $this->db->select('tblinvoices.id as invoiceid, tblinvoices.date as invoicedate, tblinvoices.clientid as invoiceclient, tblinvoices.status as invoicestatus, tblinvoices.number as invoicenumber,tblinvoices.prefix as invoiceprefix')
        ->from('tblinvoices');
        
        $filters = $this->input->post();
        $reportFrom = isset($filters['report_from']) ? trim($filters['report_from']) : '';
        $reportTo   = isset($filters['report_to']) ? trim($filters['report_to']) : '';
        
        $where = [];
        if ($reportFrom !== '') {
            if ($reportTo !== '') {
                $where[] = "tblinvoices.date BETWEEN " . $this->db->escape($reportFrom) . " AND " . $this->db->escape($reportTo);
            } else {
                $where[] = "tblinvoices.date = " . $this->db->escape($reportFrom);
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
            $where[] = 'tblinvoices.status IN (' . implode(',', $_statuses) . ')';
        }
        
        $search_payor_client_id = 0;
        if (isset($filters['search_payor']) && $filters['search_payor'] !== '') {
            $search_payor_client_id = (int) $filters['search_payor'];
            $where[] = "EXISTS ( SELECT 1 FROM tbl_carwash_payers cp WHERE cp.invoice_id = tblinvoices.id AND cp.client_id = {$search_payor_client_id})";
        }
        
        if (!empty($where))
            $this->db->where(implode(' AND ', $where), null, false);
        $this->db->order_by('tblinvoices.id', 'DESC');
        $rResult = $this->db->get()->result_array();
        
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
            $this->db->select('invoice_id, client_id, amount')->from('tbl_carwash_payers');
            if(!empty($search_payor_client_id))
                    $this->db->where('client_id', $search_payor_client_id);
            $payorRows = $this->db->where_in('invoice_id', $invoiceIds)->get()->result_array();
        
            foreach ($payorRows as $pr) {
                $invId = (int) $pr['invoice_id'];
                $thisd   = (int) $pr['client_id'];
        
                if (!isset($payorsByInvoice[$invId])) {
                    $payorsByInvoice[$invId] = [];
                }
                $payorsByInvoice[$invId][] = [
                    'client_id' => $thisd,
                    'amount'    => (float) $pr['amount'],
                ];
        
                $allClientIds[] = $thisd;
            }
        
            // Payment totals grouped by invoice + client
            $paymentRows = $this->db
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
            $clientRows = $this->db
                ->select('userid, company')
                ->from('tblclients')
                ->where_in('userid', $allClientIds)
                ->get()
                ->result_array();
        
            foreach ($clientRows as $cr) {
                $clientNameMap[(int) $cr['userid']] = $cr['company'];
            }
        }
        
        $remainingPayment = 0;
        
        foreach ($rResult as $aRow) {
            $row = [];
        
            $invoiceId       = (int) $aRow['invoiceid'];
            $invoiceClientId = (int) $aRow['invoiceclient'];
            $invoiceStatus   = (int) $aRow['invoicestatus'];
            $invoiceTotal    = (float) $aRow['invoicetotal'];
        
            $row[] = $aRow['invoiceprefix'] . $aRow['invoicenumber'];
            $row[] = get_company_name($invoiceClientId);
            $row[] = _d($aRow['invoicedate']);
        
            $payorHtmlLines = [];
            $totalPaid  = 0.0;
            
             $invoicePayors = isset($payorsByInvoice[$invoiceId]) ? $payorsByInvoice[$invoiceId] : [];
        
            foreach ($invoicePayors as $payor) {
                $payorClientId = (int) $payor['client_id'];
                $payorAmount   = (float) $payor['amount'];
        
                $sumKey            = $invoiceId . '_' . $payorClientId;
                $totalPaymentsMade = isset($paymentSums[$sumKey]) ? (float) $paymentSums[$sumKey] : 0.0;
                $paymentsOwed = '';
        
                if ($totalPaymentsMade < $payorAmount && $invoiceStatus !== 2) {
                    $paymentsOwed = $payorAmount - $totalPaymentsMade;
                    $remainingPayment += $paymentsOwed;
        
                    $payorHtmlLines[] = '$'. number_format($paymentsOwed, 2);
        
                    if (!in_array($payorClientId, $payors_arr, true)) {
                        $payors_arr[] = $payorClientId;
                    }
        
                    if (!isset($payors_due_amount_arr[$payorClientId])) {
                        $payors_due_amount_arr[$payorClientId] = 0;
                    }
                    $payors_due_amount_arr[$payorClientId] += $paymentsOwed;
                }
            }
        
            $payorsHtml = '';
            if (!empty($payorHtmlLines)) {
                $payorsHtml = implode("\n", $payorHtmlLines);
            }
        
            if ($remainingPayment < 0) {
                $remainingPayment = 0;
            }
        
            $row[] = $payorsHtml;
            if(!empty($paymentsOwed))
                $data['records'][] = $row;
        }
        
        // =========== DB Queries Ended============
        
        $data['remainingPayment'] = '$' . number_format($remainingPayment, 2);
        $data['report_to_date'] = date('F Y', strtotime($reportTo));
        $data['payor_name'] = get_company_name($search_payor_client_id) ?? '';

        $pdf = new TCPDF();
        $data['pdf'] = $pdf;
        $html = $this->load->view('carwash_reports/monthly_sales_report_pdf', $data, true);
        $pdfContent = $pdf->Output('monthly_sales_report.pdf', 'S');
        $this->output->set_content_type('application/pdf')->set_header('Content-Disposition: attachment; filename="monthly_sales_report.pdf"')->set_output($pdfContent);
    }

    public function table($clientid = '')
    {
        echo '{
                "draw":2,
                "iTotalRecords":"1",
                "iTotalDisplayRecords":"1",
                "aaData":[{
                    "0":"<a href=\"http:\/\/crm.test\/admin\/payments\/payment\/1\">1<\/a><div class=\"row-options\"><a href=\"http:\/\/crm.test\/admin\/payments\/payment\/1\">View<\/a> | <a href=\"http:\/\/crm.test\/admin\/payments\/delete\/1\" class=\"text-danger _delete\">Delete <\/a><\/div>",
                    "1":"<a href=\"http:\/\/crm.test\/admin\/invoices\/list_invoices\/1\">INV-000001<\/a>",
                    "2":"Bank - paypal",
                    "3":"123123",
                    "4":"<a href=\"http:\/\/crm.test\/admin\/clients\/client\/1\">test<\/a>",
                    "5":"$10.00",
                    "6":"2022-05-29",
                    "DT_RowClass":"has-row-options"
                }]
               }';
    }
    
    // public function payors_report()
    // {
    //     if (!has_permission('carwash_reports', '', 'payors_report')) {
    //         access_denied(_l('carwash_reports'));
    //     }

    //     if ($this->input->is_ajax_request()) {
    //         $this->app->get_table_data(module_views_path('carwash_reports', 'payors_report_table'));
    //     }

    //     //breadcrumb
    //     $data['form_heading'] = _l('Payors Report');
    //     $data['title'] = _l('Payors Report');
    //     $breadcrumb_data['current_page_title'] = $data['title'];
    //     $breadcrumb_data['breadcrumb_array'] = array(
    //         admin_url('') => 'Dashboard',
    //         admin_url('carwash_reports/payors_report') => _l('Payors Report'),
    //     );
    //     $data['breadcrumb_data'] = $breadcrumb_data;
    //     $this->load->view('payors_report',$data);
    // }
    
    public function items_checkin_report()
    {
        if (!has_permission('carwash_reports', '', 'items_checkin_report')) {
            access_denied(_l('carwash_reports'));
        }

        if ($this->input->is_ajax_request()) {
            $this->app->get_table_data(module_views_path('carwash_reports', 'items_checkin_report_table'));
        }

        //breadcrumb

        $data['form_heading'] = _l('Items Checkin Report');
        $data['title'] = _l('Items Checkin Report');
        $breadcrumb_data['current_page_title'] = $data['title'];
        $breadcrumb_data['breadcrumb_array'] = array(
            admin_url('') => 'Dashboard',
            admin_url('carwash_reports/items_checkin_report') => _l('Items Checkin Report'),
        );
        $data['breadcrumb_data'] = $breadcrumb_data;
        $this->load->view('items_checkin_report',$data);
    }

}