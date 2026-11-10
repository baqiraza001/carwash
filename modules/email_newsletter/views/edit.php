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
                                    <?php echo render_input('campaign_name', 'Campaign Name', $campaign_name, 'text', array('disabled' => 'disabled')); ?>
                                </div>
                                <div class="col-md-12">
                                    <?php echo render_select('customers_status', [['id' => 'active', 'name' => _l('email_newsletter_customers_active')], ['id' => 'inactive', 'name' => _l('email_newsletter_customers_inactive')]], ['id', 'name'], 'eemail_newsletter_customers_db_status', $customer_status, ['disabled' => 'disabled'], [], '', '', false); ?>
                                </div>

                                <div class="col-md-12">
                                    <?php echo render_select('customer_groups[]', $clientGroups, ['id', 'name'], 'eemail_newsletter_customers_group_list', explode(",", $customer_groups), ['multiple' => true, 'disabled' => 'disabled'], [], '', '', false); ?>
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
                    <div class="panel-body">
                        <div class="horizontal-scrollable-tabs panel-full-width-tabs">
                            <div class="scroller arrow-left" style="display: none;"><i class="fa fa-angle-left"></i>
                            </div>
                            <div class="scroller arrow-right" style="display: none;"><i class="fa fa-angle-right"></i>
                            </div>
                            <div class="horizontal-tabs">
                                <ul class="nav nav-tabs nav-tabs-horizontal" role="tablist">
                                    <li role="presentation" class="<?php echo ($campaign_type == 'Email') ? 'active' : '' ?>">
                                        <a href="#email" aria-controls="email" role="tab" data-toggle="tab"
                                           aria-expanded="true"><?php echo _l('Email'); ?></a>
                                    </li>
                                    <li role="presentation" class="<?php echo ($campaign_type == 'SMS') ? 'active' : '' ?>">
                                        <a href="#sms" aria-controls="sms" role="tab" data-toggle="tab"
                                           aria-expanded="true"><?php echo _l('SMS'); ?></a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        
                        <div class="tab-content">
                            <?php $email_template =email_newsletter_send_email_template('bulk-emails-to-client'); ?>
                            
                            <div role="tabpanel" class="tab-pane <?php echo ($campaign_type == 'Email') ? 'active' : '' ?>" id="email">
                                <?php echo form_open(admin_url('email_newsletter/sendEmails'), ['id' => 'email_newsletter-form']); ?>
                                <div class="col-md-12">
                                    <?php echo $email_template['template']->message; ?>
                                </div>
                                    
                                <div class="col-md-12 mtop10">
                                <hr />
                                    <button type="button" disabled class="btn btn-primary btn-lg"><?php echo _l('Send Emails'); ?></button> <br />
                                    <p class="alert alert-info hide mtop10" id="send-email-message"></p>
                                </div>
                                <?php echo form_close(); ?>
                            </div>
                            
                            <div role="tabpanel" class="tab-pane <?php echo ($campaign_type == 'SMS') ? 'active' : '' ?>" id="sms">
                                <?php echo form_open(admin_url('email_newsletter/sendSms'), ['id' => 'email_newsletter-sms-form']); ?>
                                <div class="col-md-12">
                                    <?php echo render_textarea('sms_body', 'SMS Body', $sms_body , ['rows' => 6, 'disabled' => 'disabled']); ?>
                                    <div style="display: flex;justify-content: space-between; align-items: center;">
                                        <span>Total Characters: <b id="charCount">0</b></span>
                                        <span>Total SMS: <b id="smsCount">0</b></span>
                                    </div>
                                </div>
                                    
                                <div class="col-md-12 mtop10">
                                    <button type="button" disabled class="btn btn-primary btn-lg"><?php echo _l('Send SMS'); ?></button> <br />
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
    $('#sms_body').trigger('input');
    $('#sms_body').on('paste', function() {
        $(this).trigger('input');
    });
});
</script>
<script>
    $(document).ready(function() {
        let formData = new FormData();
        
        let customers_status = $('#customers_status').val();
        var customerGroupsArray = <?php echo json_encode(explode(',', $customer_groups)); ?>;
        
        formData.append('customers_status', customers_status);
        customerGroupsArray.forEach(function(group) {
            formData.append('customer_groups[]', group);
        });
        formData.append('<?php echo $this->security->get_csrf_token_name()?>', '<?php echo $this->security->get_csrf_hash() ?>');
    
        $.ajax({ 
            url: '<?php echo site_url() ?>' + 'email_newsletter/totalEmailsFound',
            method: 'post',
            processData: false, // Set processData to false for FormData
            contentType: false, // Set contentType to false for FormData
    		data: formData, 
            success: function(data) {
                response = JSON.parse(data);
                $('#totalCustomers').text(response.total_customers);
            },
            error: function(error){
                console.log(xhr.responseText);
            }
        });
    });

</script>
</body>
</html>
