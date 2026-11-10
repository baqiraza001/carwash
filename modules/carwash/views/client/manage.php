<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<style type="text/css">
  .vin-item {
    flex: 1;
    display: flex;
    flex-direction: row;
    justify-content: space-between;
    color: black;
  }
  .vin-heading {
    font-weight: 500;
  }
</style>
<div id="carwash_listing">
  <div class="panel_s section-heading section-carwash_vehicles">
    <div class="panel-body">
      <h4 class="no-margin section-text"><?= _l('carwash_vehicles'); ?>
      <button class="btn btn-primary pull-right" type="button" id="carwash_add"><?= _l('carwash_add_new'); ?></button>
    </h4>
  </div>
</div>
<div class="panel_s">
  <div class="panel-body">
    <table class="table dt-table table-carwash" data-order-col="3" data-order-type="desc">
      <thead>
        <tr>
          <th class="th-carwash_vehicle"><?= _l('carwash_vehicle'); ?></th>
          <th class="th-carwash_vin"><?= _l('carwash_vin'); ?></th>
          <th class="th-carwash_actions"><?= _l('carwash_actions'); ?></th>
        </tr>
      </thead>
      <tbody>
        <?php 
        foreach($vehicles as $vehicle){ ?>
          <tr>
            <td><?= $vehicle['vehicle']; ?></td>
            <td><?= $vehicle['vin']; ?></td>
            <td><button class="btn btn-primary btn-icon edit_carwash_vehicle" data-id="<?= $vehicle['id']; ?>"><i class="fa-regular fa-pen-to-square"></i></button>
              <button class="btn btn-danger btn-icon delete_carwash_vehicle" data-id="<?= $vehicle['id']; ?>"><i class="fa-solid fa-xmark"></i></button></td>

            </tr>
          <?php } ?>
        </tbody>
      </table>
    </div>
  </div>
</div>
<div class="modal fade" id="carwash_modal" role="dialog">
  <div class="modal-dialog modal-md">
    <!-- Modal content-->
    <?= form_open(site_url('carwash/carwash_client/create'),['id'=>'carwash_form','autocomplete'=>'off']); ?>
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title" id="carwash_header"><?= _l('Carwash Vehicle'); ?></h4>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-md-12">
            <?= render_input( 'vehicle', 'carwash_vehicle'); ?>
            <?= render_input( 'plate_no', 'carwash_plate'); ?>
            <div class="form-group" app-field-wrapper="vin">
              <label for="vin" class="control-label">Vin</label>
              <div class="input-group ">
                <input type="text" id="vin" name="vin" class="form-control" value="" autocomplete="off">
                <div class="input-group-addon" onclick="search_vin_data()">
                  <i class="fa-solid fa-search"></i>
                </div>
              </div>
            </div>                   
              <div style="display: none;" class="vin-search-message alert alert-info">Searching for Vin Data...</div>

            <div class="vin-searched-data" style="display: none;">
              
              <div id="vin-all-data">
              </div>
            </div>
            <input type="hidden" name="clientid" value="<?= $_SESSION['client_user_id']; ?>">
            <input type="hidden" name="id" value="">
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button id="save_carwash_vehicle" type="button" class="btn btn-primary"><?= _l('save'); ?></button>
        <button type="button" class="btn btn-default" data-dismiss="modal"><?= _l('cancel'); ?></button>
      </div>
    </div>
    <?= form_close(); ?>
  </div>
</div>
<script type="text/javascript">
  $(document).ready(function(){
    $('body').on('click','#carwash_add',function(){
      var form = $('#carwash_form')[0];
      form.reset();
      $('#carwash_form input[name="id"]').val('');
      $('#carwash_modal').modal('show');
    });

    $('body').on('click','#save_carwash_vehicle',function(){
      var formdata = new FormData($('#carwash_form')[0]);
      var action =  $('#carwash_form').attr('action');
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
        $(".table-carwash").load(location.href + " .table-carwash>*","");
        $('#carwash_modal').modal('hide');
      });
    });

    $('body').on('click','.edit_carwash_vehicle',function(){
      var id = $(this).attr('data-id');
      $('#carwash_form input[name="id"]').val(id);
      var action =  '<?= site_url('carwash/carwash_client/get_vehicle_data'); ?>';
      $.ajax(action, {
        type: 'POST',
        data: {id : id}
      }).done(function(data){
        data = JSON.parse(data);
        $('#carwash_form input[name="vehicle"]').val(data.vehicle);
        $('#carwash_form input[name="vin"]').val(data.vin); 
        $('#carwash_form input[name="plate_no"]').val(data.plate_no); 
        search_vin_data(data.vin);
        $('#carwash_modal').modal('show');
      });
    });

    $('body').on('click','.delete_carwash_vehicle',function(){
      if(confirm_delete()){
        var id = $(this).attr('data-id');
        var action =  '<?= site_url('carwash/carwash_client/delete'); ?>';
        $.ajax(action, {
          type: 'POST',
          data: {id : id}

        }).done(function(data){
          data = JSON.parse(data);
          $(".table-carwash").load(location.href + " .table-carwash>*","");
          alert_float(data.status,data.message);
        });
      }
    });

  })
  /* search for vin */
  function search_vin_data(vin_search_value = '') {
    $("#vin-all-data").html('');
    $(".vin-search-message").html('Searching Vin...');
    $(".vin-search-message").show();
    var search_vin;
    if(vin_search_value !== '')
      search_vin = vin_search_value;
    else
      search_vin = $('#vin').val();

      // search for vin_data
      var action =  '<?= site_url('carwash/carwash_client/search_vin_data'); ?>';
      $.ajax(action, {
        type: 'POST',
        data: {search_vin}
      }).done(function(data){
        vin = JSON.parse(data);
        if(vin.error){
          $(".vin-search-message").html(vin.error);
        }
        else{
          $("#vin-all-data").html('<div class="col-md-offset-3 col-md-6"><h3 class="text-center">Vin Searched Data</h3>\n'
            + '            <div class="vin-item"><div class="vin-heading">VIN</div><div>' + vin.vin + '</div></div>\n'
            + '            <div class="vin-item"><div class="vin-heading">WMI</div><div>' + vin.wmi + '</div></div>\n'
            + '            <div class="vin-item"><div class="vin-heading">VDS</div><div>' + vin.vds + '</div></div>\n'
            + '            <div class="vin-item"><div class="vin-heading">VIS</div><div>' + vin.vis + '</div></div>\n'
            + '            <div class="vin-item"><div class="vin-heading">REGION</div><div>' + vin.region + '</div></div>\n'
            + '            <div class="vin-item"><div class="vin-heading">COUNTRY</div><div>' + vin.country + '</div></div>\n'
            + '            <div class="vin-item"><div class="vin-heading">MANUFACTURER</div><div>' + vin.manufacturer + '</div></div>\n'
            + '            </div>');
          $(".vin-searched-data").show();
          $(".vin-search-message").hide();
        }
      });

    }
  </script>
