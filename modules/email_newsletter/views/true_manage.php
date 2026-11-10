<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <h2>
                    <?php echo $title; ?>
                </h2>
            </div>

            <div class="col-md-6">
                <div class="panel_s">
                    <div class="panel-body">
                        <div class="tab-content">
                            <?php echo form_open(admin_url(), ['id' => 'campaign-details-form']); ?>
                            <div role="tabpanel" class="tab-pane active" id="customers">
                                <div class="col-md-12">
                                    <?php echo render_input('campaign_name', 'Campaign Name'); ?>
                                </div>
                                <div class="col-md-12">
                                    <?php echo render_select('customers_status', [['id' => 'active', 'name' => _l('email_newsletter_customers_active')], ['id' => 'inactive', 'name' => _l('email_newsletter_customers_inactive')]], ['id', 'name'], 'eemail_newsletter_customers_db_status', '', [], [], '', '', false); ?>
                                </div>

                                <div class="col-md-12">
                                    <?php echo render_select('customer_groups[]', $clientGroups, ['id', 'name'], 'eemail_newsletter_customers_group_list', '', ['multiple' => true], [], '', '', false); ?>
                                </div>

                                <div class="col-md-12">
                                    <div class="card">
                                        <div class="card-body">
                                            <h4 class="card-title">
                                                <?php echo _l('email_newsletter_total_customers_emails'); ?>
                                                <span id="totalCustomers" class="badge badge-primary">0</span>
                                            </h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php echo form_close(); ?>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="panel_s">
                    <div class="panel-body" style="padding: 0;">
                        <ul class="nav nav-tabs" style="border-top: 0;">
                            <li class="active">
                                <a href="#email" aria-controls="email" role="tab" data-toggle="tab" aria-expanded="true"><?php echo _l('Email'); ?></a>
                            </li>
                            <li>
                                <a href="#sms" aria-controls="sms" role="tab" data-toggle="tab" aria-expanded="true"><?php echo _l('SMS'); ?></a>
                            </li>
                        </ul>
                        
                        <div class="tab-content" style="padding: 20px;">
                            <?php $email_template =email_newsletter_send_email_template('bulk-emails-to-client'); ?>
                            
                            <div role="tabpanel" class="tab-pane active" id="email">
                                <?php echo form_open(admin_url('email_newsletter/sendEmails'), ['id' => 'email_newsletter-form']); ?>
                                <div class="col-md-12">
                                    <?php echo $email_template['template']->message; ?>
                                </div>
                                    
                                <div class="col-md-12 mtop10" style="margin-bottom: 20px;">
                                <hr />
                                    <button type="button" id="send-email-btn" class="btn btn-primary btn-lg"><?php echo _l('Send Emails'); ?></button> <br />
                                    <p class="alert alert-info hide mtop10" id="send-email-message"></p>
                                </div>
                                <?php echo form_close(); ?>
                            </div>
                            
                            <div role="tabpanel" class="tab-pane" id="sms">
                                <?php echo form_open(admin_url('email_newsletter/sendSms'), ['id' => 'email_newsletter-sms-form']); ?>
                                <div class="col-md-12">
                                    <?php echo render_textarea('sms_body', 'SMS Body', '' , ['rows' => 6]); ?>
                                    <div style="display: flex;justify-content: space-between; align-items: center;">
                                        <span>Total Characters: <b id="charCount">0</b></span>
                                        <span>Total SMS: <b id="smsCount">0</b></span>
                                    </div>
                                </div>
                                    
                                <div class="col-md-12 mtop10" style="margin-bottom: 20px;">
                                    <button type="button" id="send-sms-btn" class="btn btn-primary btn-lg"><?php echo _l('Send SMS'); ?></button> <br />
                                    <p class="alert alert-info hide mtop10" id="send-sms-message"></p>
                                </div>
                                <?php echo form_close(); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
<?php init_tail(); ?>
<script>
$(document).ready(function(){
    $('#sms_body').on('input', function() {
        var charCount = $(this).val().length;
        var smsCount = Math.ceil(charCount / 127); // Assuming 127 characters per SMS

        $('#charCount').text(charCount);
        $('#smsCount').text(smsCount);
    });

    // Trigger input event on paste
    $('#sms_body').on('paste', function() {
        $(this).trigger('input');
    });
});
</script>
<script>
    'use strict';

    $(document).ready(function() {

        sendRequest();

        var formData = $('#campaign-details-form').serialize();

        $('#campaign-details-form select').on('input change', function() {
            sendRequest();
        });

        function sendRequest() {
            var newFormData = $('#campaign-details-form').serialize();

            if (formData !== newFormData) {
                formData = newFormData;

                $.ajax({
                    url: '<?php echo admin_url('email_newsletter/totalEmailsFound') ?>',
                    type: 'POST',
                    data: newFormData,
                    success: function(response) {
                        response = JSON.parse(response);
                        $('#totalCustomers').text(response.total_customers);
                    },
                    error: function(xhr, status, error) {
                        console.log(xhr.responseText);
                    }
                });
            }
        }
    });
    
    // Send email ajax request
    $("#send-email-btn").on('click', function(){
        let formData = new FormData();
        
        let campaign_name = $('#campaign_name').val();
        let customers_status = $('#customers_status').val();
        let customer_groups = $('[name="customer_groups[]"]').val();
        let this_ref = $(this);
        
        if(!campaign_name)
        {
            window.alert('Please enter campaign name');
            return;
        }
        if(customer_groups.length == 0)
        {
            window.alert('Please select customer group');
            return;
        }
        
        let confirmed = window.confirm("Are you sure to send bulk email request?");
        
        if(!confirmed) return;
        
        formData.append('campaign_name', campaign_name);
        formData.append('customers_status', customers_status);
        formData.append('customer_groups', customer_groups);
        
        formData.append('<?php echo $this->security->get_csrf_token_name()?>', '<?php echo $this->security->get_csrf_hash() ?>');
        
        $(this_ref).html("<i class='fa fa-spinner fa-spin'></i>");
        $(this_ref).attr("disabled", true);
        $('#send-email-message').html('').addClass('hide');
        
        $.ajax({ 
            url: '<?php echo site_url() ?>' + 'email_newsletter/sendEmails',
            method: 'post',
            processData: false, // Set processData to false for FormData
            contentType: false, // Set contentType to false for FormData
    		data: formData, 
            success: function(data) {
                data = JSON.parse(data);
                $(this_ref).html("Send Email");
                $(this_ref).attr("disabled", false);
                
                $('#send-email-message').html(data.message).removeClass('hide');
            },
            error: function(error){
                $(this_ref).html("Send Email");
                $(this_ref).attr("disabled", false);
                $('#send-email-message').html('Error in sending email. Try again.').removeClass('hide');
            }
        });
        
        return false;
    });
    // Send email ajax request: ended
    
    
    
    // Send sms ajax request
    $("#send-sms-btn").on('click', function(){
        let formData = new FormData();
        
        let campaign_name = $('#campaign_name').val();
        let sms_body = $('#sms_body').val();
        let customers_status = $('#customers_status').val();
        let customer_groups = $('[name="customer_groups[]"]').val();
        let this_ref = $(this);
        
        if(!campaign_name)
        {
            window.alert('Please enter campaign name');
            return;
        }
        if(!sms_body)
        {
            window.alert('Please enter sms body');
            return;
        }
        if(customer_groups.length == 0)
        {
            window.alert('Please select customer group');
            return;
        }
        
        let confirmed = window.confirm("Are you sure to send bulk sms request?");
        
        if(!confirmed) return;
        
        formData.append('campaign_name', campaign_name);
        formData.append('sms_body', sms_body);
        formData.append('customers_status', customers_status);
        formData.append('customer_groups', customer_groups);
        
        formData.append('<?php echo $this->security->get_csrf_token_name()?>', '<?php echo $this->security->get_csrf_hash() ?>');
        
        $(this_ref).html("<i class='fa fa-spinner fa-spin'></i>");
        $(this_ref).attr("disabled", true);
        $('#send-sms-message').html('').addClass('hide');
        
        $.ajax({ 
            url: '<?php echo site_url() ?>' + 'email_newsletter/sendSms',
            method: 'post',
            processData: false, // Set processData to false for FormData
            contentType: false, // Set contentType to false for FormData
    		data: formData, 
            success: function(data) {
                data = JSON.parse(data);
                $(this_ref).html("Send SMS");
                $(this_ref).attr("disabled", false);
                
                $('#send-sms-message').html(data.message).removeClass('hide');
                
            },
            error: function(error){
                $(this_ref).html("Send SMS");
                $(this_ref).attr("disabled", false);
                $('#send-sms-message').html('Error in sending sms. Try again.').removeClass('hide');
            }
        });
        
        return false;
    });

</script>
</body>
</html>
