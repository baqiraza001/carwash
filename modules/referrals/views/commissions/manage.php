<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="panel_s">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-4">
                                <h4><?php echo _l('referrals_commissions'); ?></h4>
                            </div>
                        </div>
                        <hr class="hr-panel-heading">
                        <div class="row">
                            <div class="col-md-3">
                                <?php
                                $CI = &get_instance();
                                $sources = $CI->db->get('tblleads_sources')->result_array();
                                echo render_select('referrals_source_filter',$sources,array('id' , 'name'),_l('referrals_source'),'',array());
                                ?>
                            </div>
                            <div class="col-md-3">
                                <?php
                                echo render_input('referrals_date_filter', _l('referrals_date'),'','',array('autocomplete' => 'off'));
                                ?>
                            </div>
                        </div>

                        <?php 
                        $table_data = array(
                            _l('referrals_client_name'),
                            _l('referrals_referral'),
                            _l('referrals_amount'),
                            _l('referrals_status'),
                            _l('referrals_source'),
                            _l('referrals_date'),
                            _l('referrals_invoice'),
                            _l('referrals_actions'),
                            
                        ); 
                        render_datatable($table_data,'referral_commissions')
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="referral_pay_commission_modal" role="dialog">
    <div class="modal-dialog modal-md">
        <!-- Modal content-->
        <?php echo form_open(site_url('referrals/commissions/update_commissions_status'),array('id'=>'referral_pay_commission_form','autocomplete'=>'off')); ?>
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title" id=""><?php echo _l('referrals_pay_commission'); ?></h4>
            </div>
            <div class="modal-body">
                <?php echo render_input( 'amount', 'referrals_amount','','number'); ?>
                <?php
                $CI = &get_instance();
                $CI->load->model('payment_modes_model');
                $payment_modes = $CI->payment_modes_model->get();
                 echo render_select( 'method', $payment_modes,array('id' ,'name'),'referrals_method','',array('required' => 'required')); ?>
                <?php echo render_select( 'status', array(array('value' => 'Paid','name' => 'Paid'),array('value' => 'Unpaid','name' => 'Unpaid')),array('value' ,'name'),'referrals_status','',array('required' => 'required')); ?>
                <input type="hidden" name="id" value="">
                <input type="hidden" name="clientid" value="">
            </div>
            <div class="col-md-12"></div>
            <div class="modal-footer">
                <button id="save_referral_commission" type="button" class="btn btn-primary"><?php echo _l('referrals_save'); ?></button>
                <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo _l('referrals_cancel'); ?></button>
            </div>
        </div>
        <?php echo form_close(); ?>
    </div>
</div>
<?php
$current_date_format = get_current_date_format(true);
?>
<?php init_tail(); ?>
<script>
    $(function () {
        initDataTable('.table-referral_commissions', admin_url + 'referrals/commissions/table', [], [], '', [0, 'DESC']);

        var currentDateFormat = '<?php echo $current_date_format; ?>';
        if(currentDateFormat == 'd-m-Y'){
            currentDateFormat = 'DD-MM-YYYY';
        }else if(currentDateFormat == 'd/m/Y'){
            currentDateFormat = 'DD/MM/YYYY';
        }else if(currentDateFormat == 'm-d-Y'){
            currentDateFormat = 'MM-DD-YYYY';
        }else if(currentDateFormat == 'm.d.Y'){
            currentDateFormat = 'MM.DD.YYYY';
        }else if(currentDateFormat == 'm/d/Y'){
            currentDateFormat = 'MM/DD/YYYY';
        }else if(currentDateFormat == 'Y-m-d'){
            currentDateFormat = 'YYYY-MM-DD';
        }else if(currentDateFormat == 'd.m.Y'){
            currentDateFormat = 'DD.MM.YYYY';
        }
        $('input[name="referrals_date_filter"]').daterangepicker({
            autoUpdateInput: false,
            locale: {
              format: currentDateFormat
            }
        });

        $('input[name="referrals_date_filter"]').on('apply.daterangepicker', function(ev, picker) {
            $(this).val(picker.startDate.format(currentDateFormat) + ' - ' + picker.endDate.format(currentDateFormat));
            referrals_search_commissions();
        });

        $('input[name="referrals_date_filter"]').on('cancel.daterangepicker', function(ev, picker) {
            $(this).val('');
        });


        $('body').on('change','#referrals_source_filter',function () {
            referrals_search_commissions();
        });

        var referralsDatelastValue = null;
        

        $('#referrals_date_filter').on('change', function () {
            if ($(this).val() != referralsDatelastValue)
            {
                referrals_search_commissions();
            }
            referralsDatelastValue = $(this).val();
        });

        function referrals_search_commissions(){
          var source = $('#referrals_source_filter').val();
          var daterange = $('#referrals_date_filter').val();
          $(".table-referral_commissions").dataTable().fnDestroy();
          initDataTable('.table-referral_commissions', admin_url + 'referrals/commissions/table?source='+source+'&date='+daterange, [], [], '', [0, 'DESC']);
        }

        $('body').on('click','.pay_commission',function(){
            var form = $('#referral_pay_commission_form')[0];
            form.reset();
            $('#referral_pay_commission_form select[name="method"]').selectpicker('refresh');
            $('#referral_pay_commission_form select[name="status"]').selectpicker('refresh');
            $('#referral_pay_commission_form input[name="id"]').val('');
            $('#referral_pay_commission_form input[name="clientid"]').val('');

            var method = $(this).attr('data-method');
            $('#referral_pay_commission_form select[name="method"]').val(method);
            $('#referral_pay_commission_form select[name="method"]').selectpicker('refresh');

            var status = $(this).attr('data-status');
            $('#referral_pay_commission_form select[name="status"]').val(status);
            $('#referral_pay_commission_form select[name="status"]').selectpicker('refresh');

            var amount = $(this).attr('data-amount');
            $('#referral_pay_commission_form input[name="amount"]').val(amount);

            var id = $(this).attr('data-id');
            $('#referral_pay_commission_form input[name="id"]').val(id);

            var clientid = $(this).attr('data-clientid');
            $('#referral_pay_commission_form input[name="clientid"]').val(clientid);
            $('#referral_pay_commission_modal').modal('show');
        });

        $('body').on('click','#save_referral_commission',function(){
            var form = $('#referral_pay_commission_form');
            var formdata = new FormData($('#referral_pay_commission_form')[0]);
            var action =  $('#referral_pay_commission_form').attr('action');
            if(form.valid()){
               $.ajax(action, {
                type: 'POST',
                data: formdata,
                cache:false,
                contentType: false,
                processData: false,
                enctype: 'multipart/form-data',
         
            }).done(function(data){
                data = JSON.parse(data);
                alert_float(data.status,data.message);
                $('#referral_pay_commission_modal').modal('hide');
                $('.table-referral_commissions').DataTable().ajax.reload();
            }); 
            }
            
        });
    });
</script>
</body>
</html>
