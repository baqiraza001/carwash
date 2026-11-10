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
                                            <input type="text" class="form-control" id="search-payor" name="search-payor" placeholder="Search payor...">
                                            <div class="input-group-addon">
                                                <i class="fa-solid fa-search search-icon"></i>
                                            </div>
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
                        render_monthly_sales_datatable([
                            _l('Invoice #'),
                            _l('Customer'),
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
<?php $this->load->view('admin/reports/includes/sales_js'); ?>
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
            var search_payor = $('input[name="search-payor"]');
            
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
            Expenses_ServerParams['search_payor'] = '[name="search-payor"]';

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
            
            
            
             search_payor.on('keyup', $.debounce(600, function(e) {
             
                var report_to_val = $(this).val();
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
            
            }));
            
        });
    </script>
    <script src="https://benalman.com/code/projects/jquery-throttle-debounce/jquery.ba-throttle-debounce.js"></script>
</body>

</html>

