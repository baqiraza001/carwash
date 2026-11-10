<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-6 left-column">
                <div class="panel_s">
					<div class="panel-body">
                        <?php echo form_open(admin_url('sage/save_configuration'),array('id'=>'meeting-submit-form')); ?>
                    <div class="row">

                        <div class="col-md-12">
                            <h2>Sage Configuration Details</h2>
                        </div>

                    <div class="row">

                    <div class="col-md-12">
                          <div class="form-group open-ticket-subject-group">
                              <?php echo render_input('client_id','sage_client_id', $client_id); ?>
                              <?php echo form_error('client_id'); ?>
                              <p class="text-muted">Note: Get the value of <a target="_blank" href="https://developer.sage.com/api/accounting/guides/client_app_registration/">client id</a> from sage app after login.</p>
                          </div>
                     </div>

                        <div class="col-md-12">
                            <div class="form-group open-ticket-subject-group">
                                <?php echo render_input('client_secret','sage_client_secret', $client_secret); ?>
                                <?php echo form_error('client_secret'); ?>
                                <p class="text-muted">Note: Get the value of <a target="_blank" href="https://developer.sage.com/api/accounting/guides/client_app_registration/">client secret</a> from sage app after login.</p>
                            </div>
                        </div>


                        <div class="col-md-12">
                            <div class="form-group open-ticket-subject-group">
                                <?php echo render_input('redirect_uri','oAuth_redirect_uri', $redirect_uri); ?>
                                <?php echo form_error('redirect_uri'); ?>
                                <p class="text-muted">Note: Get the value of <a target="_blank" href="https://developer.sage.com/api/accounting/guides/client_app_registration/">OAuth 2.0 redirect URI</a> from sage app after login.</p>
                            </div>
                        </div>


                        <div class="col-md-12">
                            <div class="form-group open-ticket-subject-group">
                                <button type="submit" class="btn btn-info"><?php echo _l('submit'); ?></button>
                            </div>

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
        </div>
    </div>
</div>

<?php init_tail(); ?>

</body>
</html>
