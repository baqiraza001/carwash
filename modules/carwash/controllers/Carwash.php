<?php
// VERSION: 2026-11-10 07:47 PKT - Insurance information: upload/delete insurance attachment methods
defined('BASEPATH') OR exit('No direct script access allowed');

class Carwash extends AdminController {

    private $_module = 'carwash';

    public function __construct(){
        parent::__construct();
        $this->load->model($this->_module.'_model','module_model');
    }

    public function table(){
        if ($this->input->is_ajax_request()) {
            $this->app->get_table_data(module_views_path($this->_module,'vehicles_table'));  
        }

    }

    public function create()
    {
        $post_data = $this->input->post();
        $id = $post_data['vehicle_id'];
        unset($post_data['vehicle_id']);
        //add new record
        if (!isset($id) || empty($id)) {
            $inserted_id = $this->module_model->save($post_data);
            if ($inserted_id) {
                echo json_encode(array('message' => _l('carwash_added_successfully'), 'status' => 'success'));
            }else{
                echo json_encode(array('message' => _l('carwash_problem_in_adding'), 'status' => 'danger'));
            }
            exit();
        //update existing record
        } else {

            $updated = $this->module_model->save($post_data, $id);
            if($updated){
                echo json_encode(array('message' => _l('carwash_updated_successfully'), 'status' => 'success'));
            }else{
                echo json_encode(array('message' => _l('carwash_problem_in_updating'), 'status' => 'danger'));
            }
            exit();
        } 
    }

    /* Delete record from database */
    public function delete()
    {   
        $id = $this->input->post('id');
        $response = $this->module_model->delete($id);
        if ($response == true) {
            echo json_encode(array('message' => _l('carwash_deleted_successfully'), 'status' => 'success'));
        } else {
            echo json_encode(array('message' => _l('carwash_problem_in_deleting'), 'status' => 'danger'));
        }
        exit();
    }
    
    public function get_client_vehicles(){
        $id = $this->input->post('clientid');
        $result = $this->db->where('clientid' , $id)->order_by('id', 'desc')->get('tbl_client_vehicles')->result_array();
        echo json_encode($result);
        exit();
    }

    public function terms_and_conditions()
    {
        if (!is_admin()) {
            access_denied('carwash');
        }

        if ($this->input->is_ajax_request()) {
            $this->app->get_table_data(module_views_path($this->_module, 'tables/terms_conditions'));
            return;
        }

        show_404();
    }

    public function save_terms_condition()
    {
        if (!is_admin()) {
            access_denied('carwash');
        }

        $id = (int) $this->input->post('id');
        $data = [
            'title'       => trim((string) $this->input->post('title', true)),
            'nature'      => trim((string) $this->input->post('nature', true)),
            'description' => $this->input->post('description', false),
        ];

        if ($data['title'] === '') {
            echo json_encode(['status' => 'danger', 'message' => _l('carwash_title_required')]);
            exit();
        }

        if ($id > 0) {
            $data['modified_on'] = date('Y-m-d H:i:s');
            $data['modified_by'] = get_staff_user_id();

            $this->db->where('id', $id);
            $this->db->update('tbl_carwash_terms_conditions', $data);

            if ($this->db->affected_rows() > 0) {
                echo json_encode(['status' => 'success', 'message' => _l('carwash_updated_successfully')]);
            } else {
                echo json_encode(['status' => 'warning', 'message' => _l('carwash_no_changes_found')]);
            }
        } else {
            $data['created_on'] = date('Y-m-d H:i:s');
            $data['created_by'] = get_staff_user_id();

            $this->db->insert('tbl_carwash_terms_conditions', $data);
            $inserted_id = $this->db->insert_id();

            if ($inserted_id) {
                echo json_encode(['status' => 'success', 'message' => _l('carwash_added_successfully')]);
            } else {
                echo json_encode(['status' => 'danger', 'message' => _l('carwash_problem_in_adding')]);
            }
        }

        exit();
    }

    public function get_terms_condition()
    {
        if (!is_admin()) {
            access_denied('carwash');
        }

        $id = (int) $this->input->post('id');
        $row = $this->db->where('id', $id)->get('tbl_carwash_terms_conditions')->row_array();
        echo json_encode($row ?: []);
        exit();
    }

    public function delete_terms_condition()
    {
        if (!is_admin()) {
            access_denied('carwash');
        }

        $id = (int) $this->input->post('id');
        $deleted = $this->db->where('id', $id)->delete('tbl_carwash_terms_conditions');

        if ($deleted) {
            echo json_encode(['status' => 'success', 'message' => _l('carwash_deleted_successfully')]);
        } else {
            echo json_encode(['status' => 'danger', 'message' => _l('carwash_problem_in_deleting')]);
        }

        exit();
    }


    

    public function payers_index(){
        if ($this->input->is_ajax_request()) {
            $this->app->get_table_data(module_views_path('carwash', 'payers_table'));
        }
    }


    public function create_payer()
    {
        $posted_data = $this->input->post();
        $payer_id = $posted_data['payer_id'];
        unset($posted_data['payer_id']);
        if(isset($payer_id) && !empty($payer_id)){
            $posted_data['modified_on'] = date('Y-m-d H:i:s');
            // $posted_data['carwash_payer_options'] = json_encode($posted_data['carwash_payer_options']);
            $this->db->where('id' , $payer_id);
            $this->db->update('tbl_carwash_payers' , $posted_data);
            if($this->db->affected_rows() > 0){
                echo json_encode(array('message' => _l('carwash_updated_successfully'), 'status' => 'success' , 'id' => $payer_id , 'edited' => true));
            }else{
                echo json_encode(array('message' => _l('carwash_problem_in_updating'), 'status' => 'danger' , 'edited' => true));
            }
            
        }else{
            $posted_data['created_on'] = date('Y-m-d H:i:s');
            // $posted_data['carwash_payer_options'] = json_encode($posted_data['carwash_payer_options']);
            $this->db->insert('tbl_carwash_payers' , $posted_data);
            $inserted = $this->db->insert_id();
            if ($inserted) {
                echo json_encode(array('message' => _l('carwash_added_successfully'), 'status' => 'success' , 'id' => $inserted));
            }else{
                echo json_encode(array('message' => _l('carwash_problem_in_adding'), 'status' => 'danger'));
            }
        }
        exit();
    }


    public function get_payer_data(){
        $id = $this->input->post('id');
        $data = $this->db->where('id' , $id)->get('tbl_carwash_payers')->row_array();
        $rel_data = get_relation_data('customer',$data['client_id']);
        $rel_val = get_relation_values($rel_data,'customer');
        $data['client_name'] = $rel_val['name'];
        echo json_encode($data);
        exit();
    }

    /* Delete record from database */
    public function delete_payer()
    {   
        $id = $this->input->post('id');
        $response = $this->db->where('id' , $id)->delete('tbl_carwash_payers');
        if ($response == true) {
            echo json_encode(array('message' => _l('carwash_deleted_successfully'), 'status' => 'success'));
        } else {
            echo json_encode(array('message' => _l('carwash_problem_in_deleting'), 'status' => 'danger'));
        }
        exit();
    }


    public function send_invoice_to_client_payers()
    {
        $post_data = $this->input->post();
        $id = $post_data['invoice_id'];
        $carwash_payer_emails = $post_data['carwash_payer_emails'];
    
        /* handle file attachement */
        $attachment_data = '';
        $attachment_data_path = '';
        if(carwash_total_files_selected('file')){
            $attachment_data = carwash_handle_sales_attachments($id, 'invoice');
            $attachment_data_path = get_upload_path_by_type('invoice') . $id . '/' . $attachment_data['file_name'];
        }
        /* handle file attachement */

        $template_name = '';
        $attachpdf = true;
        $cc = '';
        $manually = true;
        $attachStatement = [];

        $originalNumber = null;
        $this->load->model('invoices_model');
        if ($isDraft = $this->invoices_model->is_draft($id)) {
            // Update invoice number from draft before sending
            $originalNumber = $this->invoices_model->change_invoice_number_when_status_draft($id);
        }

        $invoice = hooks()->apply_filters(
            'invoice_object_before_send_to_client',
            $this->invoices_model->get($id)
        );

        if ($template_name == '') {
            $template_name = $invoice->sent == 0 ?
            'invoice_send_to_customer' :
            'invoice_send_to_customer_already_sent';

            $template_name = hooks()->apply_filters('after_invoice_sent_template_statement', $template_name);
        }

        $emails_sent = [];
        $send_to     = [];

        $contacts = $this->clients_model->get_contacts(
            $invoice->clientid,
            ['active' => 1, 'invoice_emails' => 1]
        );
        
        $contacts_length = count($contacts);
        foreach ($contacts as $contact) {
            array_push($send_to, $contact['id']);
        }

        /* get carwash payers list*/
        $payers =  explode(',', $carwash_payer_emails);

        foreach ($payers as $key => $payer) {
            array_push($send_to, $key);
        }
        /* get carwash payers list*/

        $attachStatementPdf = false;
        if (is_array($send_to) && count($send_to) > 0) {

            $status_updated = update_invoice_status($invoice->id, true, true);

            $invoice_number = format_invoice_number($invoice->id);

            if ($attachpdf) {
                set_mailing_constant();
                $pdf    = invoice_pdf($this->invoices_model->get($id));
                $attach = $pdf->Output($invoice_number . '.pdf', 'S');
            }

            $i = 0;
            foreach ($send_to as $key => $contact_id) {

                    // Send cc only for the first contact
                    if (!empty($cc) && $i > 0) {
                        $cc = '';
                    }

                    if(($key +1) <= $contacts_length){
                        $this->db->where('id', $contact_id);
                        $contact =  $this->db->get(db_prefix() . 'contacts')->row();
                    }
                    else {
                        $contact = array();
                        $contact['email'] =  $payers[$contact_id];
                        $contact['firstname'] =  '';
                        $contact['lastname'] = '';
                        $contact['phonenumber'] =  '';
                        $contact['email_verification_sent_at'] = NULL;
                        $contact['active'] = 1;
                        $contact['invoice_emails'] = 1;
                        $contact = (object)$contact;
                    }
                    if (!$contact) {
                        continue;
                    }

                    $template = mail_template($template_name, $invoice, $contact, $cc);

                    if ($attachpdf) {
                        $template->add_attachment([
                            'attachment' => $attach,
                            'filename'   => str_replace('/', '-', $invoice_number . '.pdf'),
                            'type'       => 'application/pdf',
                        ]);
                    }

                    if ($attachStatementPdf) {
                        $template->add_attachment([
                            'attachment' => $attachStatementPdf,
                            'filename'   => $statementPdfFileName,
                            'type'       => 'application/pdf',
                        ]);
                    }
                    
                    if(isset($attachment_data['key'])){
                        $template->add_attachment([
                            'attachment' => file_get_contents($attachment_data_path),
                            'filename'   => $attachment_data['file_name'],
                            'type'   => $attachment_data['filetype'],
                        ]);
                    }

                    if ($template->send()) {
                        $sent = true;
                        array_push($emails_sent, $contact->email);
                    }
                $i++;
            }
        } elseif ($isDraft) {
            // Revert the number on failure
            $this->db->where('id', $id);
            $this->db->update('invoices', ['number' => $originalNumber]);

            $this->invoices_model->decrement_next_number();

            return false;
        }

        if (count($emails_sent) > 0) {
            $this->invoices_model->set_invoice_sent($id, false, $emails_sent, true);

            hooks()->do_action('invoice_sent', $id);

            return true;
        }

        // In case the invoice not sent and the status was draft and
        // the invoice status is updated before send return back to draft status
        // and actually update the number to the orignal number
        if ($isDraft && $status_updated !== false) {
            $this->invoices_model->decrement_next_number();

            $this->db->where('id', $invoice->id);
            $this->db->update('invoices', [
                'status' => self::STATUS_DRAFT,
                'number' => $originalNumber,
            ]);
        }

        return false;
    }


    public function send_estimate_to_client_payers()
    {
        $post_data = $this->input->post();
        $id = $post_data['estimate_id'];
        $carwash_payer_emails = $post_data['carwash_payer_emails'];
        
        /* handle file attachement */
        $attachment_data = '';
        $attachment_data_path = '';
        if(carwash_total_files_selected('file')){
            $attachment_data = carwash_handle_sales_attachments($id, 'estimate');
            $attachment_data_path = get_upload_path_by_type('estimate') . $id . '/' . $attachment_data['file_name'];
        }
        /* handle file attachement */

        $template_name = '';
        $attachpdf = true;
        $cc = '';
        $manually = true;
        $this->load->model('estimates_model');

        $estimate = $this->estimates_model->get($id);

        if ($template_name == '') {
            $template_name = $estimate->sent == 0 ?
            'estimate_send_to_customer' :
            'estimate_send_to_customer_already_sent';
        }

        $estimate_number = format_estimate_number($estimate->id);

        $emails_sent = [];
        $send_to     = [];


        $contacts = $this->clients_model->get_contacts(
            $estimate->clientid,
            ['active' => 1, 'estimate_emails' => 1]
        );

        $contacts_length = count($contacts);
        foreach ($contacts as $contact) {
            array_push($send_to, $contact['id']);
        }

        /* get carwash payers list*/
        $payers =  explode(',', $carwash_payer_emails);
 
        foreach ($payers as $key => $payer) {
            array_push($send_to, $key);
        }
        /* get carwash payers list*/

        $status_auto_updated = false;
        $status_now          = $estimate->status;

        if (is_array($send_to) && count($send_to) > 0) {
            $i = 0;
            // Auto update status to sent in case when user sends the estimate is with status draft
            if ($status_now == 1) {
                $this->db->where('id', $estimate->id);
                $this->db->update(db_prefix() . 'estimates', [
                    'status' => 2,
                ]);
                $status_auto_updated = true;
            }

            if ($attachpdf) {
                $_pdf_estimate = $this->estimates_model->get($estimate->id);
                set_mailing_constant();
                $pdf = estimate_pdf($_pdf_estimate);

                $attach = $pdf->Output($estimate_number . '.pdf', 'S');
            }

            foreach ($send_to as $contact_id) {
                    // Send cc only for the first contact
                    if (!empty($cc) && $i > 0) {
                        $cc = '';
                    }

                    if(($key +1) <= $contacts_length){
                        $this->db->where('id', $contact_id);
                        $contact =  $this->db->get(db_prefix() . 'contacts')->row();
                    }
                    else {
                        $contact = array();
                        $contact['email'] =  $payers[$contact_id];
                        $contact['firstname'] =  '';
                        $contact['lastname'] = '';
                        $contact['phonenumber'] =  '';
                        $contact['email_verification_sent_at'] = NULL;
                        $contact['active'] = 1;
                        $contact['invoice_emails'] = 1;
                        $contact = (object)$contact;
                    }

                    if (!$contact) {
                        continue;
                    }

                    $template = mail_template($template_name, $estimate, $contact, $cc);

                    if ($attachpdf) {
                        $hook = hooks()->apply_filters('send_estimate_to_customer_file_name', [
                            'file_name' => str_replace('/', '-', $estimate_number . '.pdf'),
                            'estimate'  => $_pdf_estimate,
                        ]);

                        $template->add_attachment([
                            'attachment' => $attach,
                            'filename'   => $hook['file_name'],
                            'type'       => 'application/pdf',
                        ]);
                    }
                    
                    if(isset($attachment_data['key'])){
                        $template->add_attachment([
                            'attachment' => file_get_contents($attachment_data_path),
                            'filename'   => $attachment_data['file_name'],
                            'type'   => $attachment_data['filetype'],
                        ]);
                    }

                    if ($template->send()) {
                        array_push($emails_sent, $contact->email);
                    }
                $i++;
            }
        } else {
            return false;
        }

        if (count($emails_sent) > 0) {
            $this->estimates_model->set_estimate_sent($id, $emails_sent);
            hooks()->do_action('estimate_sent', $id);

            return true;
        }

        if ($status_auto_updated) {
            // Estimate not send to customer but the status was previously updated to sent now we need to revert back to draft
            $this->db->where('id', $estimate->id);
            $this->db->update(db_prefix() . 'estimates', [
                'status' => 1,
            ]);
        }

        return false;
    }
    
    public function is_customer_history_available(){
        $this->db2 = $this->load->database('carwash_db', TRUE);
        // $this->db2 = $this->db;
        $client_id = $this->input->post('client_id');
        $primary_contact = carwash_get_primary_contact($client_id);
        $first_name = $last_name = '';
        $data['quotes_available'] = FALSE;

        if(!is_null($primary_contact)){
            $first_name = $primary_contact->firstname;
            $last_name = $primary_contact->lastname;

            $this->db2->where('LOWER(INSFIRST)' , strtolower($first_name));
            $this->db2->where('LOWER(INSLAST)' , strtolower($last_name));
            $rows = $this->db2->get('quotes')->num_rows();
            if($rows !== 0)
                $data['quotes_available'] = TRUE;

            $data['rows'] = $rows;
        }
        $data['first_name'] = $first_name;
        $data['last_name'] = $last_name;
        echo json_encode($data);
        exit();
    }

    public function quotes_index(){
        if ($this->input->is_ajax_request()) {
            $this->app->get_table_data(module_views_path('carwash', 'quotes_table'));
        }
    }

    public function get_quote_data(){
        $this->db2 = $this->load->database('carwash_db', TRUE);
        $quote = $this->input->post('quote');
        $is_estimate = !empty($this->input->post('is_estimate')) ? $this->input->post('is_estimate') : false;
        $is_invoice = !empty($this->input->post('is_invoice')) ? $this->input->post('is_invoice') : false;
        
        if(!empty($is_estimate) || !empty($is_invoice)){
            $this->get_new_quote_data($is_estimate, $is_invoice, $quote);
        }
        else {
            $this->db2->select('venbranch, vendor, detail.quote, invoice,transtype, drlicence, vehdesc, venparwh, repbyven, cause, depamount, _note, parttot, labourtot, comdiscsubtotal, quotes.gstexempt as quote_gstexempt, quotes.pstexempt as quote_pstexempt, custtotal, 
                invded, invdep, invdedcr, invcusttot, invparttot, invlabtot, insdiscoun, invgst, invpst, invdedcr, 
                prodclass, partident, description, origprice, discount, quantity, price, labour, labrate, extended, detail.pstexempt');
            $this->db2->from('detail');
            $this->db2->join('quotes', 'quotes.quote = detail.quote', 'left');
            $data['quotes'] = $this->db2->where('detail.quote' , $quote)->get()->result_array();
            
            $data['invoice_data'] = $this->db2->where('quote' , $quote)->get('qoutepay')->row();
            $this->load->view('quote_details', $data);
        }
    }    
    
    public function get_new_quote_data($is_estimate, $is_invoice, $quote) {
        if(!empty($is_estimate)){
            $tbl_name = 'tblestimates';
            $rel_type = 'estimate';
            $payer_column_name = 'estimate_id';
            $invoice_estimate_id_column = 'invoiceid';
        }
        else {
            $tbl_name = 'tblinvoices';
            $rel_type = 'invoice';
            $payer_column_name = 'invoice_id';
            $invoice_estimate_id_column = 'estimate_id';
        }
        
        $data['quote'] = $this->db->select("id, clientid, total as custtotal, DATE(datecreated) as quotedate, adminnote, discount_total,  $invoice_estimate_id_column")->where('id', $quote)->get($tbl_name)->row();
        $data['quotes'] = $this->db->select('*')->where('rel_id', $quote)->where('rel_type', $rel_type)->get('tblitemable')->result_array();
        $data['converted_invoiceid'] = $data['quote']->invoiceid;
       
        $payers = $this->db->select('client_id')->where($payer_column_name, $quote)->get('tbl_carwash_payers')->result_array();
        
        $quote_payers = array();        
        foreach($payers as $payer) {
            $quote_payers[] = get_relation_values(get_relation_data('customer',$payer['client_id']),'customer')['name'];
        }
        $data['quote_payers'] = implode(',', $quote_payers);
        
        /* converted estimate invoice data */
        if($is_estimate){
            if($data['quote']->invoiceid){
                $data['invoice_quote'] = $this->db->select("id, clientid, total as custtotal, DATE(datecreated) as quotedate, adminnote, discount_total")->where('id', $quote)->get('tblinvoices')->row();
                $data['invoice_quotes'] = $this->db->select('*')->where('rel_id', $quote)->where('rel_type', 'invoice')->get('tblitemable')->result_array();
            }
            else {
                $data['invoice_quote'] = null;
                $data['invoice_quotes'] = [];
            }
        }
        else {
            $data['is_invoce'] = 1;
        }
        /* converted estimate invoice data*/
        
        // echo '<pre>'; var_dump($data);die;        
        $this->load->view('quote_details_new', $data);
        
    }
    
        
    public function search_nags()
    {
        if ($this->input->is_ajax_request()) {
            $query = $this->input->post('q');
            $this->db = $this->load->database('carwash_db', TRUE);
            
            $items = $this->db->query("SELECT DISTINCT NAGS_GLASS_ID as id FROM NAGS_GLASS WHERE NAGS_GLASS_ID LIKE '$query%' 
                                    UNION
                                    SELECT DISTINCT NAGS_GLASS_ID as id FROM NAGS_GLASS_CFG WHERE NAGS_GLASS_ID LIKE '$query%'
                                    
                                    UNION
                                    SELECT DISTINCT NAGS_HW_ID as id FROM NAGS_HW WHERE NAGS_HW_ID LIKE '$query%'
                                    UNION
                                    SELECT DISTINCT NAGS_HW_ID as id FROM NAGS_HW_DET WHERE NAGS_HW_ID LIKE '$query%'
                                    
                                    UNION
                                    SELECT DISTINCT NAGS_HW_CFG_ID as id FROM NAGS_HW_CFG_DET_NOTE WHERE NAGS_HW_CFG_ID LIKE '$query%'
                                    UNION
                                    SELECT DISTINCT NAGS_HW_CFG_ID as id FROM NAGS_HW_CFG_DET_QUAL WHERE NAGS_HW_CFG_ID LIKE '$query%'
                                    
                                    UNION
                                    SELECT DISTINCT NAGS_SVC_ID as id FROM NAGS_SVC WHERE NAGS_SVC_ID LIKE '$query%'
                                    
                                    LIMIT 100 ")->result();
            echo json_encode($items);
        }
    }
    
    
    
     /*++++++++++++++++ Tables +++++++++++++++++++*/

    public function tables($table_name = 'payments', $clientid='')
    {
        if ($this->input->is_ajax_request()) {

            if($table_name == 'payments')
            {
                $this->load->model('payments_model');
                $this->load->model('payment_modes_model');
            }
            

            $this->app->get_table_data(module_views_path('carwash', 'tables/'.$table_name), [
                'clientid' => $clientid,
            ]);
        }
    }
    
    
    public function search_customer_by_vin()
    {
        $vin_number = $this->input->post('vin_number');
        $client_data = $this->db->select('clientid')->where('vin', $vin_number)->get('tbl_client_vehicles')->result_array();
        $client_ids = array_column($client_data, 'clientid');
        if(!empty($client_ids))
            $clients = $this->db->select('company, userid')->where_in('clients.userid', $client_ids)->get('tblclients')->result_array();
        
        if(!empty($clients))
        {
            echo json_encode([
                'success' => 1,
                'clients' => $clients,
            ]);
        }
        else
        {
            echo json_encode([
                'success' => 0,
                'clients' => '',
            ]);
        }
    }
    
    public function search_client_for_taxes()
    {
        $clientid = $this->input->post('clientid');
        if($clientid)
        {
            $client = $this->db->select('*')->where('clients.userid', $clientid)->get('tblclients')->row_array();
            echo json_encode($client);
        }
        else
        echo json_encode([]);
        
    }

    /* Insurance information attachments (customer profile > Insurance information tab) */
    public function upload_insurance_attachment($userid)
    {
        if (!staff_can('edit', 'customers') && !staff_can('create', 'customers')) {
            echo json_encode(['success' => false, 'message' => _l('access_denied')]);
            exit();
        }

        $userid = (int) $userid;
        $path   = get_upload_path_by_type('customer') . $userid . '/';

        $totalUploaded = 0;

        if (isset($_FILES['insurance_files']['name']) && is_array($_FILES['insurance_files']['name'])) {
            _file_attachments_index_fix('insurance_files');

            for ($i = 0; $i < count($_FILES['insurance_files']['name']); $i++) {
                $tmpFilePath = $_FILES['insurance_files']['tmp_name'][$i];

                if (empty($tmpFilePath)) {
                    continue;
                }

                if (_perfex_upload_error($_FILES['insurance_files']['error'][$i])
                    || !_upload_extension_allowed($_FILES['insurance_files']['name'][$i])) {
                    continue;
                }

                _maybe_create_upload_path($path);

                $filename = unique_filename($path, $_FILES['insurance_files']['name'][$i]);

                if (move_uploaded_file($tmpFilePath, $path . $filename)) {
                    $attachment   = [];
                    $attachment[] = [
                        'file_name' => $filename,
                        'filetype'  => $_FILES['insurance_files']['type'][$i],
                    ];

                    if (is_image($path . $filename)) {
                        create_img_thumb($path . $filename, $filename);
                    }

                    $this->load->model('misc_model');
                    $this->misc_model->add_attachment_to_database($userid, 'customer_insurance', $attachment);

                    $totalUploaded++;
                }
            }
        }

        echo json_encode(['success' => $totalUploaded > 0, 'uploaded' => $totalUploaded]);
        exit();
    }

    public function delete_insurance_attachment($userid, $file_id)
    {
        $userid  = (int) $userid;
        $file_id = (int) $file_id;

        if (staff_can('delete', 'customers') || is_customer_admin($userid)) {
            $this->db->where('id', $file_id);
            $this->db->where('rel_id', $userid);
            $this->db->where('rel_type', 'customer_insurance');
            $attachment = $this->db->get(db_prefix() . 'files')->row();

            if ($attachment) {
                if (empty($attachment->external)) {
                    $relPath  = get_upload_path_by_type('customer') . $userid . '/';
                    $fullPath = $relPath . $attachment->file_name;

                    if (file_exists($fullPath)) {
                        unlink($fullPath);
                    }

                    $fname     = pathinfo($fullPath, PATHINFO_FILENAME);
                    $fext      = pathinfo($fullPath, PATHINFO_EXTENSION);
                    $thumbPath = $relPath . $fname . '_thumb.' . $fext;

                    if (file_exists($thumbPath)) {
                        unlink($thumbPath);
                    }
                }

                $this->db->where('id', $attachment->id)->delete(db_prefix() . 'files');
                set_alert('success', _l('deleted', _l('customer_attachments_file')));
            }
        }

        redirect(admin_url('clients/client/' . $userid . '?group=profile&tab=insurance_info'));
    }
}
