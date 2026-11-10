<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>

<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="panel_s">
                    <div class="panel-body">
                        <div class="_buttons">
                            <h4 class="list-title border-right list_title_hr pull-left"><?php echo $title; ?></h4>
                            <div class="pull-right header-btn-new">
                                <a href="<?php echo admin_url('email_newsletter/manage') ?>" class="btn btn-info pull-left display-block"><?php echo _l('Create Campaign');?>
                                    <i class="fa fa-plus" aria-hidden="true"></i>
                                </a>
                            </div>
                        </div>
                        <div class="clearfix"></div>
                        <hr class="hr-panel-heading">
                        <?php render_datatable([
                            _l('email_newsletter_newsletter_campaign_name'),
                            _l('Type'),
                            _l('email_newsletter_total_emails_to_send'),
                            _l('email_newsletter_emails_sent'),
                            _l('email_newsletter_emails_failed'),
                            _l('email_newsletter_created_at'),
                            _l('options'),
                        ], 'newsletter-list'); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php init_tail(); ?>
<script>
    $(document).ready(function() {
        "use strict";
        $(function() {
            initDataTable('.table-newsletter-list', window.location.href, [5], [5], [], [5, 'desc']);
        });
    });
</script>
</body>
</html>
