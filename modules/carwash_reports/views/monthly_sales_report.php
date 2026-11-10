<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>

<div id="wrapper" xmlns="http://www.w3.org/1999/html">
    <div class="content">
        <div class="row">
            <div class="_filters _hidden_inputs hidden my_list_filters">
                <?php
                    echo form_hidden('total','');
                ?>
            </div>
            <div class="col-md-12">
                <div class="panel_s">
                    <div class="panel-body">

                        <div class="row">
                            <div class="col-md-12">
                                <h4 class="mt0"><?= $form_heading; ?></h4>

                                <hr class="hr-panel-heading">

                                <div class="row">
                                    <form method="POST" action="<?= $_SERVER['PHP_SELF']; ?>">
                                    <div class="col-md-12">
                                        <p class="bold">Filter by</p>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="input-group date">
                                            <input type="text" class="form-control datepicker" autocomplete="off" id="report-from" name="report-from" placeholder="From Date">
                                            <div class="input-group-addon">
                                                <i class="fa-regular fa-calendar calendar-icon"></i>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="input-group date">
                                            <input type="text" class="form-control datepicker" autocomplete="off" id="report-to" name="report-to" placeholder="To Date">
                                            <div class="input-group-addon">
                                                <i class="fa-regular fa-calendar calendar-icon"></i>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-3">
                                        <div class="form-group select-placeholder input-group-select">
                                          <div class="input-group input-group-select">
                                            <div class="items-select-wrapper">
                                              <select id="clientid" name="clientid" data-live-search="true" data-width="100%" class="ajax-search selectpicker _select_input_group" data-none-selected-text="Search payor..."></select>
                                            </div>
                                            <div class="input-group-addon customer-search-vin-div"><i class="fa-solid fa-search search-icon"></i></div>
                                          </div>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <select name="monthly_report_invoice_status" id="monthly_report_invoice_status" class="selectpicker" multiple data-width="100%" data-none-selected-text="<?= _l('invoice_status_report_all'); ?>">
                                                <?php foreach($invoice_statuses as $status){ if($status ==5){continue;} ?>
                                                <option value="<?= $status; ?>"><?= format_invoice_status($status,'',false) ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <button class="btn btn-primary hide" type="button" id="download_single_payor_invoice">Download Selected Payor Invoice</button>
                                    </div>
                                    
                                    <div class="col-md-12 mtop5">
                                        <div class="alert bg-warning search_message" style="display: none;color: white;"></div>
                                    </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <div class="clearfix"></div>
                        <hr class="hr-panel-heading">

                        <?php
                        render_monthly_sales_datatable([
                            _l('Invoice #'),
                            _l('Customer'),
                            _l('report_invoice_date'),
                            _l('Amount'),
                            _l('Total paid'),
                            _l('Remaining'),
                            _l('Due Date'),
                            _l('Status'),
                            _l('Pending Payments from Payors'),

                        ], 'monthly-sales-report', [], []);

                        ?>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php init_tail(); ?>
<?php $this->load->view('admin/reports/includes/my_sales_js'); ?>
    <style>
        .table-monthly-sales-report .monthly-payor-export-lines,
        .table-monthly-sales-report .monthly-payor-dues-list {
            white-space: pre-line;
            display: block;
            line-height: 1.4;
            text-align: left;
            word-break: break-word;
        }

        .table-monthly-sales-report .monthly-payor-total {
            text-align: left;
        }

        @media print {
            .table-monthly-sales-report .monthly-payor-export-lines,
            .table-monthly-sales-report .monthly-payor-dues-list {
                page-break-inside: avoid;
                break-inside: avoid;
                display: block;
                white-space: pre-line;
            }
        }
    </style>
    <script>
        $(function(){
            /*var dtServerParams = {},
                input_Filters = $('._hidden_inputs._filters.my_list_filters input');
            $.each(input_Filters, function () {
                dtServerParams[$(this).attr('name')] = '[name="' + $(this).attr('name') + '"]';
            });
            initDataTable('.table-monthly-sales-report', window.location.href,[],[0,0],dtServerParams,['0','DESC']);
            */
        });

        $(function(){

            var report_from = $('input[name="report-from"]');
            var report_to = $('input[name="report-to"]');
            var search_payor = $('input[name="clientid"]');
            var monthly_report_invoice_status = $('input[name="monthly_report_invoice_status"]');

            if(report_from == '' && report_to == '' )
            {
                $('.search_message').html('Please fill atleast start date');
                $('.search_message').show();
                return;
            }

            var Expenses_ServerParams = {};
            $.each($('._hidden_inputs._filters input'),function(){
                Expenses_ServerParams[$(this).attr('name')] = '[name="'+$(this).attr('name')+'"]';
            });

            Expenses_ServerParams['report_from'] = '[name="report-from"]';
            Expenses_ServerParams['report_to'] = '[name="report-to"]';
            Expenses_ServerParams['search_payor'] = '[name="clientid"]';
            Expenses_ServerParams['monthly_report_invoice_status'] = '[name="monthly_report_invoice_status"]';

            // Patch the print button to keep the div wrapper (white-space:pre-line) so
            // newline-separated payors render as one-per-line in the print window.
            // Using document-level delegation so this fires on every (re-)init of the table.
            $(document).off('init.dt.monthlysales').on('init.dt.monthlysales', '.table-monthly-sales-report', function() {
                var _dt = $(this).DataTable();
                _dt.button('.buttons-print').action(function(e, dt, button, config) {
                    config.exportOptions = config.exportOptions || {};
                    config.exportOptions.stripHtml = false;
                    $.fn.dataTable.ext.buttons.print.action.call(this, e, dt, button, config);
                });
            });

            //initDataTable('.table-monthly-sales-report', window.location.href, 'undefined', 'undefined', Expenses_ServerParams, [8, 'desc']);
            initDataTable('.table-monthly-sales-report', window.location.href, undefined, undefined,Expenses_ServerParams,['0','DESC']);

            $('.table-monthly-sales-report').on('draw.dt', function() {
             var monthly_salesReportTable = $(this).DataTable();
             var sums = monthly_salesReportTable.ajax.json().sums;
              add_common_footer_sums($(this), sums);
              $(this).find('tfoot td.subtotal').next().next().next().html(sums.discount_total);
          });
            

            var date = new Date();
            var firstDay = new Date(date.getFullYear(), date.getMonth(), 1);
            var lastDay = new Date(date.getFullYear(), date.getMonth() + 1, 0);

            var dd = String(firstDay.getDate()).padStart(2, '0');
            var mm = String(firstDay.getMonth() + 1).padStart(2, '0'); //January is 0!
            var yyyy = firstDay.getFullYear();
            firstDay = yyyy + '-' +mm + '-' + dd;

            dd = String(lastDay.getDate()).padStart(2, '0');
            mm = String(lastDay.getMonth() + 1).padStart(2, '0'); //January is 0!
            yyyy = lastDay.getFullYear();
            lastDay = yyyy + '-' +mm + '-' + dd;

            report_from.val(firstDay);
            report_to.val(lastDay).change();
            $('.table-monthly-sales-report').DataTable().ajax.reload();

            report_from.on('change', function() {
                var val = $(this).val();
                if (val != '') {
                    $('.search_message').hide();
                    $('.table-monthly-sales-report').DataTable().ajax.reload();
                } else if ( val < report_to.val()) {
                    $('.search_message').html('From date cannot be smaller than to date').show();
                }
            });

            report_to.on('change', function() {
                var report_to_val = $(this).val();
                var report_from_val = report_from.val();

                var report_from_date = new Date(report_from_val);
                var report_to_date = new Date(report_to_val);
                if (report_from_val != '') {
                    $('.table-daily-sales-report').DataTable().ajax.reload();
                } else {
                    alert('From date cannot be empty');
                    report_to.val('').focus();
                }

                if (report_to_date < report_from_date){
                    report_to.val('').focus();
                    alert('From date cannot be less than to date');
                }
            });
            
            
            
            $('body').on('change','#clientid',function(){
                var report_to_val = report_to.val();
                var report_from_val = report_from.val();
                var search_payor_val = search_payor.val();
                if(!empty($(this).val()))
                    $('#download_single_payor_invoice').removeClass('hide');
                else    
                    $('#download_single_payor_invoice').addClass('hide');

                var report_from_date = new Date(report_from_val);
                var report_to_date = new Date(report_to_val);
                if (report_from_val != '') {
                    $(".table-monthly-sales-report").dataTable().fnDestroy();
                    initDataTable('.table-monthly-sales-report', window.location.href, undefined, undefined,Expenses_ServerParams,['0','DESC']);
                } else {
                    alert('From date cannot be empty');
                    report_to.val('').focus();
                }

                if (report_to_date < report_from_date){
                    report_to.val('').focus();
                    alert('From date cannot be less than to date');
                }
            
            });
            
            
             $("#monthly_report_invoice_status").on('change', function() {
             
                var monthly_report_invoice_status_val = $(this).val();
                var report_to_val = report_to.val();
                var report_from_val = report_from.val();
                var search_payor_val = search_payor.val();

                var report_from_date = new Date(report_from_val);
                var report_to_date = new Date(report_to_val);
                if (report_from_val != '') {
                    $(".table-monthly-sales-report").dataTable().fnDestroy();
                    initDataTable('.table-monthly-sales-report', window.location.href, undefined, undefined,Expenses_ServerParams,['0','DESC']);
                } else {
                    alert('From date cannot be empty');
                    report_to.val('').focus();
                }

                if (report_to_date < report_from_date){
                    report_to.val('').focus();
                    alert('From date cannot be less than to date');
                }
            
            });
            
            
            $("#download_single_payor_invoice").on('click', function() {

                var monthly_report_invoice_status_val = $('select[name="monthly_report_invoice_status"]').val();
            
                var report_to_val = report_to.val();
                var report_from_val = report_from.val();
                var search_payor_val = $('select[name="clientid"]').val();
            
                $.ajax({
                    url: '<?= admin_url('carwash_reports/monthly_sales_report_pdf'); ?>',
                    type: 'POST',
                    data: {
                        report_from: report_from_val,
                        report_to: report_to_val,
                        monthly_report_invoice_status: monthly_report_invoice_status_val,
                        search_payor: search_payor_val
                    },
                    xhrFields: {
                        responseType: 'blob'
                    },
                    beforeSend: function() {
                        $("#download_single_payor_invoice").prop("disabled", true);
                        $('.search_message')
                            .html('Processing request. Please wait...')
                            .show();
                    },
                    success: function(data, status, xhr) {
            
                        var blob = new Blob([data], {
                            type: 'application/pdf'
                        });
            
                        var url = window.URL.createObjectURL(blob);
                        
                        // window.open(url, '_blank');
            
                        var link = document.createElement('a');
                        link.href = url;
                        link.download = 'monthly_sales_report.pdf';
             
                        document.body.appendChild(link);
                        link.click();
                        document.body.removeChild(link);
            
                        window.URL.revokeObjectURL(url);
            
                        $("#download_single_payor_invoice").prop("disabled", false);
                        $('.search_message').html('').hide();
                    },
                    error: function(xhr) {
            
                        console.log(xhr.responseText);
            
                        $("#download_single_payor_invoice").prop("disabled", false);
            
                        $('.search_message')
                            .html('Error processing request. Please try again.')
                            .show();
                    }
                });
            });
        
        });

    </script>

</body>

</html>