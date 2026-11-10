<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<div id="referrals_listing">
  <div class="panel_s section-heading section-bookings">
    <div class="panel-body">
      <h4 class="no-margin section-text"><?php echo _l('referrals'); ?>
      <button class="btn btn-primary pull-right" type="button" id="referral_add"><?php echo _l('referrals_add_new'); ?></button>
    </h4>
  </div>
</div>
<div class="panel_s">
  <div class="panel-body">
    <table class="table dt-table table-referrals" data-order-col="3" data-order-type="desc">
      <thead>
        <tr>
          <th class="th-referral_name"><?php echo _l('referrals_name'); ?></th>
          <th class="th-referral_city"><?php echo _l('referrals_city'); ?></th>
          <th class="th-referral_email"><?php echo _l('referrals_email'); ?></th>
          <th class="th-referral_phone"><?php echo _l('referrals_phone'); ?></th>
          <th class="th-referral_status"><?php echo _l('referrals_status'); ?></th>
          <th class="th-referral_actions"><?php echo _l('referrals_actions'); ?></th>
        </tr>
      </thead>
      <tbody>
        <?php 
        foreach($referrals as $referral){ ?>
          <tr>
            <td><?php echo $referral['name']; ?></td>
            <td><?php echo $referral['city']; ?></td>
            <td><?php echo $referral['email']; ?></td>
            <td><?php echo $referral['phonenumber']; ?></td>
            <?php 
            if(isset($referral['date_converted']) && !empty($referral['date_converted'])){ ?>
              <td><span class="label label inline-block text-success" style="color: #84c529;border:1px solid #84c529;font-size:12px;"><?php echo _l('referrals_approved'); ?></span></td>
            <?php }else{ ?>
              <td><span class="label label inline-block text-primary" style="color: #03a9f4;border:1px solid #03a9f4;font-size:12px;"><?php echo _l('referrals_pending'); ?></span></td>
            <?php } ?>
            <td><button class="btn btn-primary btn-icon edit_referral" data-id="<?php echo $referral['id']; ?>"><i class="fa fa-pencil-square-o"></i></button>
            <button class="btn btn-danger btn-icon delete_referral" data-id="<?php echo $referral['id']; ?>"><i class="fa fa-remove"></i></button></td>
            
          </tr>
        <?php } ?>
      </tbody>
    </table>
  </div>
</div>
</div>
<div class="modal fade" id="referral_modal" role="dialog">
    <div class="modal-dialog modal-md">
        <!-- Modal content-->
        <?php echo form_open(site_url('referrals/create'),array('id'=>'referrals_form','autocomplete'=>'off')); ?>
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title" id="referrals_header"><?php echo _l('referrals_add_edit'); ?></h4>
            </div>
            <div class="modal-body">
                <?php echo render_input( 'name', 'referrals_name'); ?>
                <?php echo render_input( 'city', 'referrals_city'); ?>
                <?php echo render_input( 'email', 'referrals_email'); ?>
                <?php echo render_input( 'phone', 'referrals_phone'); ?>
                <input type="hidden" name="clientid" value="<?php echo $_SESSION['client_user_id']; ?>">
                <input type="hidden" name="id" value="">
            </div>
            <div class="col-md-12"></div>
            <div class="modal-footer">
                <button id="save_referral" type="button" class="btn btn-primary"><?php echo _l('referrals_save'); ?></button>
                <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo _l('referrals_cancel'); ?></button>
            </div>
        </div>
        <?php echo form_close(); ?>
    </div>
</div>
<script type="text/javascript">
  $(document).ready(function(){
    $('body').on('click','#referral_add',function(){
      var form = $('#referrals_form')[0];
      form.reset();
      $('#referrals_form input[name="id"]').val('');
      $('#referral_modal').modal('show');
    });

    $('body').on('click','#save_referral',function(){
        var formdata = new FormData($('#referrals_form')[0]);
        var action =  $('#referrals_form').attr('action');
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
            $(".table-referrals").load(location.href + " .table-referrals>*","");
            $('#referral_modal').modal('hide');
        });
    });

    $('body').on('click','.edit_referral',function(){
      var id = $(this).attr('data-id');
      $('#referrals_form input[name="id"]').val(id);
        var action =  '<?php echo site_url('referrals/get_referral_data'); ?>';
        $.ajax(action, {
            type: 'POST',
            data: {id : id}

        }).done(function(data){
            data = JSON.parse(data);
            console.log(data.name);
            $('#referrals_form input[name="name"]').val(data.name);
            $('#referrals_form input[name="phone"]').val(data.phonenumber);
            $('#referrals_form input[name="email"]').val(data.email);
            $('#referrals_form input[name="city"]').val(data.city);
            $('#referral_modal').modal('show');
        });
    });

    $('body').on('click','.delete_referral',function(){
      if(confirm_delete()){
        var id = $(this).attr('data-id');
          var action =  '<?php echo site_url('referrals/delete'); ?>';
          $.ajax(action, {
              type: 'POST',
              data: {id : id}

          }).done(function(data){
              data = JSON.parse(data);
              $(".table-referrals").load(location.href + " .table-referrals>*","");
              alert_float(data.status,data.message);
          });
      }
    });
  })
</script>
