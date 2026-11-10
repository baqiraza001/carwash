<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<style>
    .vin-item {
    flex: 1;
    display: flex;
    flex-direction: row;
    justify-content: space-between;
    color: black;
}
</style>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <?= form_open($this->uri->uri_string(), ['id' => 'invoice-form', 'class' => '_transaction_form invoice-form']); ?>
            <?php if (isset($invoice)) {
                echo form_hidden('isedit');
            } ?>
            <div class="col-md-12">
                <h4 class="tw-mt-0 tw-font-bold tw-text-lg tw-text-neutral-700 tw-flex tw-items-center tw-space-x-2">
                    <span>
                        <?= e(isset($invoice) ? format_invoice_number($invoice) : _l('create_new_invoice')); ?>
                    </span>
                    <?= isset($invoice) ? format_invoice_status($invoice->status) : ''; ?>
                </h4>
                <?php if (isset($invoice)) { ?>
                <a class="send-payer-emails-btn btn btn-primary mleft10" href="#" id="send-payer-emails-btn">Send Emails</a>
                <?php } ?>
                <?php $this->load->view('admin/invoices/invoice_template'); ?>
            </div>
            <?= form_close(); ?>
            <?php $this->load->view('admin/invoice_items/item'); ?>
            <?php $this->load->view('admin/invoices/my_client'); ?>
        </div>
    </div>
</div>
<?php $invoice_id = isset($invoice) ? $invoice->id : '' ?>

<!--  carwash add payer modal -->
<div class="modal fade" id="carwash_payer_modal" role="dialog">
	<div class="modal-dialog modal-lg">
		<!-- Modal content-->
		<?=form_open(site_url('carwash/carwash/create_payer'),['id'=>'carwash_payer_form','autocomplete'=>'off']); ?>
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title" id="carwash_payer_header"><?="Payor Details"; ?></h4>
			</div>
			<div class="modal-body">
				<div class="row">
					<input type="hidden" name="invoice_id" value="<?=$invoice_id ?>">
					<input type="hidden" name="payer_id" value="">
					<div class="col-md-6">
						<div class="f_client_id">
							<div class="form-group select-placeholder carwash-payer-client">
								<label for="clientid" class="control-label"><?=_l('invoice_select_customer'); ?></label>
								<select id="clientid" name="client_id" data-live-search="true" data-width="100%" class="ajax-search" data-none-selected-text="<?=_l('dropdown_non_selected_tex'); ?>">
								</select>
							</div>
						</div>
					</div>
					<div class="col-md-6">
						<?=render_input( 'policy_number', 'Policy Number'); ?>
					</div>
					<div class="col-md-6">
						<?=render_input( 'authorization_number', 'Authorization Number'); ?>
					</div>
					<div class="col-md-6">
						<?=render_date_input( 'date_of_loss', 'Date of Loss'); ?>
					</div>
					<div class="col-md-6">
						<?=render_input( 'purchase', 'Purchase Number'); ?>
					</div>
					<div class="col-md-6">
						<?=render_input( 'order_number', 'Order Number'); ?>
					</div>
					
					<div class="col-md-6">
						<?=render_input( 'fleet_or_unit', 'Fleet or Unit #'); ?>
					</div>
				
					<div class="col-md-6">
						<?=render_input( 'amount', 'Amount', '', 'number'); ?>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button id="save_carwash_vehicle" type="button" class="btn btn-primary"><?=_l('save'); ?></button>
				<button type="button" class="btn btn-default" data-dismiss="modal"><?=_l('cancel'); ?></button>
			</div>
		</div>
		<?=form_close(); ?>
	</div>
</div>
<!--  carwash add payer modal -->


<!--  carwash send emails modal -->
<?php 
$customer_email = (isset($invoice) && isset($invoice->client)) ? $this->db->select('email')->where('userid', $invoice->client->userid)->get('tblcontacts')->row('email') : ''; 
?>
<div class="modal fade" id="carwash_payer_emails_modal" role="dialog">
	<div class="modal-dialog modal-lg">
		<!-- Modal content-->
		<?=form_open_multipart(site_url('carwash/carwash/send_invoice_to_client_payers'),['id'=>'carwash_send_payer_emails_form','autocomplete'=>'off']); ?>
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title" id=""><?="Payor Emails"; ?></h4>
			</div>
			<div class="modal-body">
				<div class="row">
					<input type="hidden" name="invoice_id" value="<?= isset($invoice) ? $invoice->id : '' ?>">
					<div class="col-md-12">
						<?=render_input('carwash_customer_email', 'Customer Email', $customer_email); ?>
					</div>
					<div class="col-md-12">
						<?=render_textarea('carwash_payer_emails', 'Payors Emails List', ''); ?>
					</div>
					<div class="col-md-12">
						<?=render_input( 'file', 'Attach File', '', 'file'); ?>
					</div>
				</div>
				<div class="row">
					<div class="col-md-12">
						<div class="email-msg alert alert-info text-center" style="display: none;">Sending email... please wait</div>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button id="send_payers_emails" type="button" class="btn btn-primary"><?=_l('save'); ?></button>
				<button type="button" class="btn btn-default" data-dismiss="modal"><?=_l('cancel'); ?></button>
			</div>
		</div>
		<?=form_close(); ?>
	</div>
</div>
<!--  carwash send emails modal -->

<!-- carwash quotes modal -->
<div class="modal fade" id="carwash_quotes_modal" role="dialog">
	<div class="modal-dialog modal-xl">
		<!-- Modal content-->
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title" id=""><?="Quotes List"; ?></h4>
			</div>
			<div class="modal-body">
				<div class="row carwash_quote_datatable">
					<div class="col-md-12">
						<?php 
						$table_data = [
							_l('Company'),
							_l('Make'),
							_l('Model'),
							_l('Year'),
							_l('VIN'),
							_l('Mileage'),
							_l('VENDOR'),
							_l('CustTotal'),
							_l('Quotedate'),
						]; 
						array_push($table_data,_l('actions'));
						render_datatable($table_data,'carwash_quotes');
						?>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal"><?=_l('Close'); ?></button>
			</div>
		</div>
	</div>
</div>
<!-- carwash quotes modal -->

<!-- carwash quotes details modal -->
<div class="modal fade" id="carwash_quotes_details_modal" role="dialog">
	<div class="modal-dialog modal-xl">
		<!-- Modal content-->
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title" id=""><?="Invoice Details"; ?></h4>
			</div>
			<div class="modal-body quotes_details_div" style="padding: 0;">
				
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-primary invoice_details_btn" ><?=_l('View Invoice'); ?></button>
				<button type="button" class="btn btn-primary quote_details_btn" style="display: none;"><?=_l('View Quote'); ?></button>
				<button type="button" class="btn btn-default" data-dismiss="modal"><?=_l('Close'); ?></button>
			</div>
		</div>
	</div>
</div>
<!-- carwash quotes details modal -->


<!-- carwash vehicle modal-->
<div class="modal fade" id="carwash_vehicle_modal" role="dialog">
  <div class="modal-dialog modal-md">
    <!-- Modal content-->
    <?=form_open(site_url('carwash/carwash_client/create'),['id'=>'carwash_vehicle_form','autocomplete'=>'off']); ?>
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title" id="carwash_header"><?=_l('Add Vehicle'); ?></h4>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-md-12">
            <?=render_input( 'vehicle', 'carwash_vehicle'); ?>
            <?=render_input( 'plate_no', 'carwash_plate'); ?>
            <div class="form-group" app-field-wrapper="vin">
              <label for="vin" class="control-label">Vin</label>
              <div class="input-group ">
                <input type="text" id="vin" name="vin" class="form-control" value="" autocomplete="off">
                <div class="input-group-addon" onclick="find_vin_data()">
                  <i class="fa-solid fa-search"></i>
                </div>
              </div>
            </div>                   
              <div style="display: none;" class="vin-search-message alert alert-info">Searching for Vin Data...</div>

            <div class="vin-searched-data" style="display: none;">
              
              <div id="vin-all-data">
              </div>
            </div>
            <input type="hidden" name="clientid" value="">
            <input type="hidden" name="id" value="">
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button id="add_carwash_vehicle" type="button" class="btn btn-primary"><?=_l('save'); ?></button>
        <button type="button" class="btn btn-default" data-dismiss="modal"><?=_l('cancel'); ?></button>
      </div>
    </div>
    <?=form_close(); ?>
  </div>
</div>
<!-- carwash vehicle modal-->

<div class="modal fade" id="carwash_edit_vehicle_invoice" role="dialog">
  <div class="modal-dialog modal-md">
    <!-- Modal content-->
    <?=form_open(site_url('carwash/carwash_client/create'),['id'=>'carwash_edit_vehicle_form','autocomplete'=>'off']); ?>
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title" id="carwash_header"><?=_l('Carwash Vehicle'); ?></h4>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-md-12">
            <?=render_input( 'vehicle', 'carwash_vehicle'); ?>
            <?=render_input( 'plate_no', 'carwash_plate'); ?>
            <div class="form-group" app-field-wrapper="vin">
              <label for="vin" class="control-label">Vin</label>
              <div class="input-group ">
                <input type="text" id="vin" name="vin" class="form-control" value="" autocomplete="off">
                <div class="input-group-addon" id="edit_invoice_vehicle">
                  <i class="fa-solid fa-search"></i>
                </div>
              </div>
            </div>                   
              <div style="display: none;" class="vin-search-message alert alert-info">Searching for Vin Data...</div>

            <div class="vin-searched-data" style="display: none;">
              
              <div id="vin-all-data">
              </div>
            </div>
            <input type="hidden" name="clientid" value="<?=isset($invoice) ? $invoice->clientid : '' ?>" id="edit_invoice_clientid">
            <input type="hidden" name="id" value="">
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button id="invoice_save_carwash_vehicle" type="button" class="btn btn-primary"><?=_l('save'); ?></button>
        <button type="button" class="btn btn-default" data-dismiss="modal"><?=_l('cancel'); ?></button>
      </div>
    </div>
    <?=form_close(); ?>
  </div>
</div>


<!-- carwash search customer by vin number modal -->
<div class="modal fade" id="customer_search_vin_modal" role="dialog">
	<div class="modal-dialog modal-xs">
		<!-- Modal content-->
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title" id=""><?="Search Customer by Vin"; ?></h4>
			</div>
			<div class="modal-body">
            <div class="row">
              <div class="col-md-6">
                <?=render_input( 'customer_vin', 'Enter Vin'); ?>
                <div class="searcing-vin-msg" style="display:none">Searcing...</div>
                <div class="searcing-vin-no-clients-msg" style="display:none">Clients not found for this vin number</div>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button id="customer-search-vin" type="button" class="btn btn-primary"><?=_l('Search'); ?></button>
            <button type="button" class="btn btn-default" data-dismiss="modal"><?=_l('cancel'); ?></button>
          </div>
		</div>
	</div>
</div>
<!-- carwash search customer by vin number modal -->

<?php init_tail(); ?>
<script>
    $(function() {
        validate_invoice_form();
        // Init accountacy currency symbol
        init_currency();
        // Project ajax search
        init_ajax_project_search_by_customer_id();
        // Maybe items ajax search
        init_ajax_search('items', '#item_select.ajax-search', undefined, admin_url + 'items/search');
        init_ajax_search('nags', '#cw_nags.ajax-search', undefined, admin_url + 'carwash/search_nags');
    });
    $('body').on('click', '#add_payor', function() {
        var form = $('#carwash_payer_form')[0];
        form.reset();
        $('#carwash_payer_form input[name="payer_id"]').val('');
        $('#carwash_payer_modal').modal('show');
    });
</script>

<script type="text/javascript">
    $(document).ready(function(){
        $('select[name="custom_fields[invoice][11]"]').trigger('change');
        get_vehicles();
    })
  function add_new_client(form) {
      
        var data = $(form).serialize();

        var cashback_url = "<?= admin_url('stock_management/stock_products/add_new_client') ?>";
        $.post(cashback_url, data).done(function(response) {
            response = JSON.parse(response);
            if (response.success == true) {
                alert_float('success', response.message);
                $("#clientid").html(`<option value="${response.client.userid}" title="${response.client.company}" selected="selected">${response.client.company}</option>`)
            }
            $('#customer_admins_assign').modal('hide');
        		$('#clientid').selectpicker('refresh');
        		get_vehicles();
        });
        return false;
    }
    $("#add-client-btn").on('click', function() {
      add_new_client('#add_client_form');
    })
    
    $('body').on('change','#client_vehicle',function(){
        var vehicle = $(this).find(':selected').attr('data-vehicle');
        var vehicle_id = $(this).find(':selected').attr('data-id');
        var vin = $(this).find(':selected').attr('data-vin');
        var plate_no = $(this).find(':selected').attr('data-plate_no');
        $('input[name="custom_fields[invoice][9]"]').val(vin);
        $('input[name="custom_fields[invoice][7]"]').val(plate_no);
        $('#carwash_edit_this_vehicle').attr('data-id', vehicle_id);
        search_vin_data(vin);
        
    });
    
    
    $('body').on('change','#clientid',function(){
             get_vehicles();
            
    });
    
    function get_vehicles(){
        $('#client_vehicle').empty();
         $('#client_vehicle').selectpicker('refresh');
        var clientid = $('#clientid').val();
        var action =  admin_url + 'carwash/get_client_vehicles';
        $.ajax(action, {
                type: 'POST',
                data: {clientid : clientid},
 
        }).done(function(data){
                data = JSON.parse(data);
                
                $('#client_vehicle').append('<option></option>');
                $.each(data, function (index, value) {
                    
                    $('#client_vehicle').append('<option data-id = "'+value.id +'" data-vehicle = "'+value.vehicle +'" data-vin = "'+value.vin +'" data-plate_no = "'+value.plate_no +'">'+value.vehicle +' ('+value.vin+')</option>');
                });
                $('#client_vehicle').selectpicker('refresh');
        });
    }
    
    
    /* search for vin */
      function search_vin_data(vin_search_value = '') {
        $("#vin-all-data").html('');
        $(".vin-search-message").html('Searching Vin...');
        $(".vin-search-message").show();
        let search_vin  = vin_search_value;
    
          // search for vin_data
          var action =  '<?=site_url('carwash/carwash_client/search_vin_data'); ?>';
          $.ajax(action, {
            type: 'POST',
            data: {search_vin}
          }).done(function(data){
            vin = JSON.parse(data);
            if(vin.error){
                $('input[name="custom_fields[invoice][6]"]').val('');
              $(".vin-search-message").html(vin.error);
            }
            else{
                 let manf = '';
                 if(vin.length !== 0)
                    manf = vin.manufacturer;
              $('input[name="custom_fields[invoice][6]"]').val(manf);
            }
          });
    
        }
     /* search for vin */
     
     
    $('select[name="custom_fields[invoice][11]"]').on('change', function() {
      if($(this).val() == 'Other AR'){
          $('textarea[name="custom_fields[invoice][12]"]').parent().parent().removeClass('hide');
      }else{
          $('textarea[name="custom_fields[invoice][12]"]').parent().parent().addClass('hide');
      }
    })
    
    
   
</script>
<script>
    /* carwash edit vehicle on invoices add/edit page and when vehicle is selected*/
    $('body').on('click','#edit_invoice_vehicle',function(){
            $("#carwash_edit_vehicle_form #vin-all-data").html('');
            $("#carwash_edit_vehicle_form .vin-search-message").html('Searching Vin...');
            $("#carwash_edit_vehicle_form .vin-search-message").show();
            let search_vin;
              search_vin = $('#carwash_edit_vehicle_form input[name="vin"]').val();
           
              // search for vin_data
              var action =  site_url+'carwash/carwash_client/search_vin_data';
              $.ajax(action, {
                type: 'POST',
                data: {search_vin}
              }).done(function(data){
                vin = JSON.parse(data);
                if(vin.error){
                  $("#carwash_edit_vehicle_form .vin-search-message").html(vin.error);
                }
                else{
                  $("#carwash_edit_vehicle_form #vin-all-data").html(`<div class="col-md-offset-3 col-md-6"><h3 class="text-center">Vin Searched Data</h3>
                    <div class="vin-item"><div class="vin-heading">VIN</div><div>${vin.vin}</div></div>
                    <div class="vin-item"><div class="vin-heading">WMI</div><div>${vin.wmi}</div></div>
                    <div class="vin-item"><div class="vin-heading">VDS</div><div>${vin.vds}</div></div>
                    <div class="vin-item"><div class="vin-heading">VIS</div><div>${vin.vis}</div></div>
                    <div class="vin-item"><div class="vin-heading">REGION</div><div>${vin.region}</div></div>
                    <div class="vin-item"><div class="vin-heading">COUNTRY</div><div>${vin.country}</div></div>
                    <div class="vin-item"><div class="vin-heading">MANUFACTURER</div><div>${vin.manufacturer}</div></div>
                    </div>`);
                  $("#carwash_edit_vehicle_form .vin-searched-data").show();
                  $("#carwash_edit_vehicle_form .vin-search-message").hide();
                }
              });
        
            });
            
    $('body').on('click','#invoice_save_carwash_vehicle',function(){
      var formdata = new FormData($('#carwash_edit_vehicle_form')[0]);
      var action =  $('#carwash_edit_vehicle_form').attr('action');
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
        get_vehicles();
        $('#carwash_edit_vehicle_invoice').modal('hide');
      });
    });

    $('body').on('click','.edit_carwash_vehicle',function(){
      var id = $(this).attr('data-id');
      $('#carwash_edit_vehicle_form input[name="id"]').val(id);
      var action =  '<?=site_url('carwash/carwash_client/get_vehicle_data'); ?>';
      $.ajax(action, {
        type: 'POST',
        data: {id : id}
      }).done(function(data){
        data = JSON.parse(data);
        $('#carwash_edit_vehicle_form input[name="vehicle"]').val(data.vehicle);
        $('#carwash_edit_vehicle_form input[name="vin"]').val(data.vin);
        $('#carwash_edit_vehicle_form input[name="plate_no"]').val(data.plate_no);
        find_vin_data(data.vin);
        $('#carwash_edit_vehicle_invoice').modal('show');
      });
    });
    /* carwash edit vehicle on invoices add/edit page and when vehicle is selected*/


    /* carwash vehicles */
          $('body').on('click','#carwash_add_vehicle',function(){
            var form = $('#carwash_vehicle_form')[0];
            form.reset();
            $('#carwash_vehicle_form input[name="id"]').val('');
            $('#carwash_vehicle_modal').modal('show');
          });
        
          $('body').on('click','#add_carwash_vehicle',function(){
            $('#carwash_vehicle_form input[name="clientid"]').val($('#clientid').val());
            
            var formdata = new FormData($('#carwash_vehicle_form')[0]);
            
            var action =  $('#carwash_vehicle_form').attr('action');
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
              $('#client_vehicle').append('<option data-vehicle = "'+data.vehicle.vehicle +'" data-vin = "'+data.vehicle.vin +'" data-plate_no = "'+data.vehicle.plate_no +'">'+data.vehicle.vehicle +' ('+data.vehicle.vin+')</option>');
             $('#client_vehicle').selectpicker('refresh');
              $('#carwash_vehicle_modal').modal('hide');
            });
          });
        
          
          /* search for vin */
          function find_vin_data(vin_search_value = '') {
            $("#vin-all-data").html('');
            $(".vin-search-message").html('Searching Vin...');
            $(".vin-search-message").show();
            let search_vin;
            if(vin_search_value !== '')
              search_vin = vin_search_value;
            else
              search_vin = $('#carwash_vehicle_form input[name="vin"]').val();
           
              // search for vin_data
              var action =  site_url+'carwash/carwash_client/search_vin_data';
              $.ajax(action, {
                type: 'POST',
                data: {search_vin}
              }).done(function(data){
                vin = JSON.parse(data);
                if(vin.error){
                  $(".vin-search-message").html(vin.error);
                }
                else{
                  $("#vin-all-data").html(`<div class="col-md-offset-3 col-md-6"><h3 class="text-center">Vin Searched Data</h3>
                    <div class="vin-item"><div class="vin-heading">VIN</div><div>${vin.vin}</div></div>
                    <div class="vin-item"><div class="vin-heading">WMI</div><div>${vin.wmi}</div></div>
                    <div class="vin-item"><div class="vin-heading">VDS</div><div>${vin.vds}</div></div>
                    <div class="vin-item"><div class="vin-heading">VIS</div><div>${vin.vis}</div></div>
                    <div class="vin-item"><div class="vin-heading">REGION</div><div>${vin.region}</div></div>
                    <div class="vin-item"><div class="vin-heading">COUNTRY</div><div>${vin.country}</div></div>
                    <div class="vin-item"><div class="vin-heading">MANUFACTURER</div><div>${vin.manufacturer}</div></div>
                    </div>`);
                  $(".vin-searched-data").show();
                  $(".vin-search-message").hide();
                }
              });
        
            }
            
            /* create vehicle from btn in quotes table*/
            function carwash_create_vehicle_from_quote(vin = '', vehicle = '') {
                let formdata = new FormData();
                formdata.append('clientid', $('#clientid').val());
                formdata.append('vehicle', vehicle);
                formdata.append('vin', vin);
                
                var action =  $('#carwash_vehicle_form').attr('action');
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
                  $('#client_vehicle').append('<option data-vehicle = "'+data.vehicle.vehicle +'" data-vin = "'+data.vehicle.vin  +'" data-plate_no = "'+data.vehicle.plate_no +'">'+data.vehicle.vehicle +' ('+data.vehicle.vin+')</option>');
                 $('#client_vehicle').selectpicker('refresh');
                });
              };
            /* create vehicle from btn in quotes table */
            
        /* carwash vehicles */
</script>


<!--carwash scripts-->
<script type="text/javascript">
		$(document).ready(function(){
			let all_payers_arr = [];
			initDataTable('.table-carwash_payers', admin_url + 'carwash/payers_index?invoice_id=<?=isset($invoice) ? $invoice->id : ''?>&payer_ids='+JSON.stringify(all_payers_arr),[], [0], '', ['0','DESC']);

			$('body').on('click','#add-payer-btn, #carwash_add, #add_payer_select_btn',function(){
				var form = $('#carwash_payer_form')[0];
				form.reset();
				
				// $('#carwash_payer_form .carwash-payer-client').find('select').val(``);
				// $('#carwash_payer_form').find('select').selectpicker('refresh');
				
				$('#carwash_payer_form').find('select').selectpicker('destroy');
				$('#carwash_payer_form .carwash-payer-client').html(`<label for="clientid" class="control-label"><?=_l('invoice_select_customer'); ?></label>
					<select id="clientid" name="client_id" data-live-search="true" data-width="100%" class="ajax-search" data-none-selected-text="<?=_l('dropdown_non_selected_tex'); ?>">
					</select>`);
				init_ajax_search('customer','#carwash_payer_form #clientid.ajax-search');

				$('#carwash_form input[name="payer_id"]').val('');
				$('#carwash_payer_modal').modal('show');
			});

			$('body').on('click','#save_carwash_vehicle',function(){
				let formdata = new FormData($('#carwash_payer_form')[0]);
				let action =  $('#carwash_payer_form').attr('action');

					$.ajax(action, {
						type: 'POST',
						data: formdata,
						cache:false,
						contentType: false,
						processData: false,

					}).done(function(data){
						data = JSON.parse(data);
						alert_float(data.status,data.message);
						all_payers_arr.push(data.id);
						$("#new_payers_list").val(JSON.stringify(all_payers_arr));
						$(".table-carwash_payers").dataTable().fnDestroy();
						initDataTable('.table-carwash_payers', admin_url + 'carwash/payers_index?invoice_id=<?=isset($invoice) ? $invoice->id : ''?>&payer_ids='+JSON.stringify(all_payers_arr),[], [0], '', ['0','DESC']);
						$('#carwash_payer_modal').modal('hide');
					});
				});

			$('body').on('click','.edit_carwash_payer',function(){
				var id = $(this).attr('data-id');
				$('#carwash_payer_form input[name="payer_id"]').val(id);
				var action =  '<?=site_url('carwash/get_payer_data'); ?>';
				$.ajax(action, {
					type: 'POST',
					data: {id : id}
				}).done(function(data){
					data = JSON.parse(data);
				
				    $('#carwash_payer_form').find('select').selectpicker('destroy');
					$('#carwash_payer_form .carwash-payer-client').html(`<label for="clientid" class="control-label"><?=_l('invoice_select_customer'); ?></label>
						<select id="clientid" name="client_id" data-live-search="true" data-width="100%" class="ajax-search" data-none-selected-text="<?=_l('dropdown_non_selected_tex'); ?>">
						<option value="${data.client_id}" selected="selected">${data.client_name}</option>
						</select>`);
					init_ajax_search('customer','#clientid.ajax-search');
				

					$('#carwash_payer_form input[name="policy_number"]').val(data.policy_number);
					$('#carwash_payer_form input[name="authorization_number"]').val(data.authorization_number);
					if(data.date_of_loss === '0000-00-00')
					    data.date_of_loss = '';
					$('#carwash_payer_form input[name="date_of_loss"]').val(data.date_of_loss);
					$('#carwash_payer_form input[name="purchase"]').val(data.purchase);
					$('#carwash_payer_form input[name="order_number"]').val(data.order_number);
					$('#carwash_payer_form input[name="fleet_or_unit"]').val(data.fleet_or_unit);
					$('#carwash_payer_form input[name="amount"]').val(data.amount);
					$('#carwash_payer_modal').modal('show');
				});
			});

			$('body').on('click','.delete_carwash_payer',function(){
				if(confirm_delete()){
					var id = $(this).attr('data-id');
					var action =  '<?=site_url('carwash/delete_payer'); ?>';
					$.ajax(action, {
						type: 'POST',
						data: {id : id}

					}).done(function(data){
						data = JSON.parse(data);
						alert_float(data.status,data.message);
						let new_payers_arr = all_payers_arr.filter(function(elem){
						   return elem != id; 
						})
						all_payers_arr = new_payers_arr;
						$("#new_payers_list").val(JSON.stringify(all_payers_arr));
						$(".table-carwash_payers").dataTable().fnDestroy();
						initDataTable('.table-carwash_payers', admin_url + 'carwash/payers_index?invoice_id=<?=isset($invoice) ? $invoice->id : ''?>&payer_ids='+JSON.stringify(all_payers_arr),[], [0], '', ['0','DESC']);
					});
				}
			});
		});

	</script>
	<script type="text/javascript">
		$('body').on('click','#send-payer-emails-btn',function(){
			$('#carwash_payer_emails_modal').modal('show');
		});

		$('body').on('click','#send_payers_emails',function(){
			$('.email-msg').show();
			var formdata = new FormData($('#carwash_send_payer_emails_form')[0]);
			var action =  $('#carwash_send_payer_emails_form').attr('action');
			$.ajax(action, {
				type: 'POST',
				data: formdata,
				cache:false,
				contentType: false,
				processData: false,

			}).done(function(data){
				$('.email-msg').hide();
				alert_float('success','Emails sent successfully');
				$('#carwash_payer_emails_modal').modal('hide');
			});
		});

	</script>
	
	<script type="text/javascript">
	$('body').on('change','#clientid',function(){
		let client_id = $(this).val();
		$('#carwash_edit_vehicle_form #edit_invoice_clientid').val(client_id);
		get_carwash_customer_history(client_id);
	});
    
    function get_carwash_customer_history(client_id = ''){
		if(client_id !== '')
		{
			let action = admin_url+'carwash/is_customer_history_available';
			$.ajax(action, {
				type: 'POST',
				data: {client_id:client_id},
				cache:false,
			}).done(function(data){
				data = JSON.parse(data);
				if(data.quotes_available){
					$("#customer-history-btn").attr('data-first-name', data.first_name);
					$("#customer-history-btn").attr('data-last-name', data.last_name);
					$(".customer-history-div").show();
				}
				else{
					$("#customer-history-btn").attr('data-first-name', '');
					$("#customer-history-btn").attr('data-last-name', '');
					$(".customer-history-div").hide();
				}
			});

		}
	}
    
    initDataTable('.table-carwash_quotes', admin_url + 'carwash/quotes_index?first_name=&last_name=',[], [0], '', ['0','DESC']);
	
	<?php if(isset($invoice) ) { ?>
	get_carwash_customer_history(<?=$invoice->clientid ?>);
	<?php }?>
	
	$('body').on('click','#customer-history-btn',function(){
		let first_name = $(this).attr('data-first-name');
		let last_name = $(this).attr('data-last-name');
		
		let client_id = $('#clientid').val();
		$('#carwash_quotes_modal .modal-title').text(`Quotes of ${first_name} ${last_name}`);
		$(".table-carwash_quotes").dataTable().fnDestroy();
		initDataTable('.table-carwash_quotes', admin_url + `carwash/quotes_index?first_name=${first_name}&last_name=${last_name}&clientid=${client_id}`,[], [0], '', ['0','DESC']);
	});


	function carwash_view_quote(quote='', crm='') {
		$('#carwash_quotes_details_modal .modal-title').text(`Quote #${quote} details`);
		let is_invoice = 0;
		if(crm == 'crm')
    	    is_invoice = 1;   
		   
		let action = admin_url+'carwash/get_quote_data';
		$.ajax(action, {
			type: 'POST',
			data: {quote:quote, is_invoice: is_invoice},
			cache:false,
		}).done(function(data){
			$(".quotes_details_div").html(data);
			$('.quote_details').show();
			$('.invoice_details').hide();
			
    		$('.invoice_details_btn').show();
			$('.quote_details_btn').hide();
			$('#carwash_quotes_details_modal').modal('show');
		});
	}

	$('body').on('click','.invoice_details_btn',function(){
		$('.quote_details').hide();
		$('.invoice_details').show();
		$('.invoice_details_btn').hide();
		$('.quote_details_btn').show();
	});

	$('body').on('click','.quote_details_btn',function(){
		$('.quote_details').show();
		$('.invoice_details').hide();
		$('.invoice_details_btn').show();
		$('.quote_details_btn').hide();
	});
</script>
<!--carwash scripts-->


<script>
    // search customer by vin number
    $('body').on('click','#customer_search_vin_modal #customer-search-vin',function(){
        $(".searcing-vin-msg").show();
        
        $(".searcing-vin-no-clients-msg").hide();
        
        var data = {vin_number: $('#customer_search_vin_modal #customer_vin').val()};

        var cashback_url = "<?= admin_url('carwash/search_customer_by_vin') ?>";
        $.post(cashback_url, data).done(function(response) {
            response = JSON.parse(response);
            if (response.success == true) {
                var $elm = $('select#' + 'clientid');
                $elm.data('AjaxBootstrapSelect').list.cache = {};
                var $elmWrapper = $elm.parents('.bootstrap-select');
                $("#clientid").html(`<option value=""><?=_l('invoice_select_customer'); ?></option>`);
                $elmWrapper.removeClass('ajax-remove-values-option').find('.ajax-clear-values').remove();
                
                let clients = response.clients;
                $elm.html('').append('<option value=""></option>').selectpicker('val', $elm.attr('multiple') == 'multiple' ? [] : '');
                for(var i = 0; i < clients.length; i++) {
                    $("#clientid").append(`<option value="${clients[i].userid}" title="${clients[i].company}" >${clients[i].company}</option>`)
                }
                $('#clientid').selectpicker('refresh');
                $("#customer_search_vin_modal").modal('hide');
                $(".searcing-vin-msg").hide(); 
            }
            else
            {
                $(".searcing-vin-msg").hide();
                $(".searcing-vin-no-clients-msg").show();
            }
        	
        });
        return false;
    });
</script>

<script>
var termsTemplates = window.carwashTermsTemplates || [];

var templatesById = {};

if (Array.isArray(termsTemplates)) {
    termsTemplates.forEach(function (item) {
        if (item && typeof item.id !== 'undefined') {
            templatesById[String(item.id)] = item;
        }
    });
}

$('body').on('change', '#terms_and_conditions_selector', function () {
    var selectedId = String($(this).val() || '');
    var selected = templatesById[selectedId] || null;
    var description = selected && selected.description ? selected.description : '';

    var $terms = $('#terms');
    if ($terms.length) {
        $terms.val(description);
    }
});
</script>
</body>

</html>