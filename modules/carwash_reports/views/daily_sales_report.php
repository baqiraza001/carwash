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
                                            <input type="text" class="form-control datepicker" id="report-from" name="report-from" placeholder="From Date">
                                            <div class="input-group-addon">
                                                <i class="fa-regular fa-calendar calendar-icon"></i>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="input-group date">
                                            <input type="text" class="form-control datepicker" id="report-to" name="report-to" placeholder="To Date">
                                            <div class="input-group-addon">
                                                <i class="fa-regular fa-calendar calendar-icon"></i>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="input-group">
                                            <span class="input-group-addon" id="basic-addon1">INV-</span>
                                            <input type="text" class="form-control" id="invoice-id" name="invoice-id" placeholder="Enter Invoice Id">
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="alert bg-warning search_message" style="display: none;color: white;"></div>
                                    </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <div class="clearfix"></div>
                        <hr class="hr-panel-heading">

                        <?php
                        render_datatable([
                            _l('Payment Id'),
                            _l('Invoice #'),
                            _l('Client'),
                            _l('Payment Mode'),
                            _l('Transaction ID'),
                            _l('Amount Paid'),
                            _l('Note'),
                            _l('Date'),

                        ], 'daily-sales-report', [], []);?>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php init_tail(); ?>

    <script>
        $(function(){
            /*var dtServerParams = {},
                input_Filters = $('._hidden_inputs._filters.my_list_filters input');
            $.each(input_Filters, function () {
                dtServerParams[$(this).attr('name')] = '[name="' + $(this).attr('name') + '"]';
            });
            initDataTable('.table-daily-sales-report', window.location.href,[],[0,0],dtServerParams,['0','DESC']);
            */
        });

        $(function(){

            var report_from = $('input[name="report-from"]');
            var report_to = $('input[name="report-to"]');
            var invoice_id = $('input[name="invoice-id"]');

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
            Expenses_ServerParams['invoice_id'] = '[name="invoice-id"]';

            //initDataTable('.table-daily-sales-report', window.location.href, 'undefined', 'undefined', Expenses_ServerParams, [8, 'desc']);
            initDataTable('.table-daily-sales-report', window.location.href, undefined, undefined,Expenses_ServerParams,['0','DESC']);

            //let today = new Date().toISOString().slice(0, 10);

            var today = new Date();
            const dd = String(today.getDate()).padStart(2, '0');
            const mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
            const yyyy = today.getFullYear();
            today = yyyy + '-' +mm + '-' + dd;

            report_from.val(today).change();
            $('.table-daily-sales-report').DataTable().ajax.reload();

            report_from.on('change', function() {
                var val = $(this).val();
                if (val != '') {
                    $('.table-daily-sales-report').DataTable().ajax.reload();
                } else if ( val < report_to.val()) {
                    alert('From date cannot be smaller than to date');
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

            invoice_id.keyup(function(e){
               $(this).trigger('change');
            });
            invoice_id.blur(function(){
               $(this).trigger('change');
            });
            invoice_id.on('change', function() {
                var val = $(this).val();
                $('.table-daily-sales-report').DataTable().ajax.reload();
            });

        });
    </script>
</body>

</html>

