<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<style>
    .top_stats_wrapper {
        padding: 26px 22px 28px 22px;
    }
</style>
<div id="wrapper">
    
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <h4 class="tw-mt-0 tw-font-semibold tw-text-lg tw-text-neutral-700"><?php echo $title; ?></h4>
            </div>
        </div>
        <div class="row tw-mb-5">
            <div class="col-md-12">
                <div class="col-md-12 panel-body">
                    <div class="quick-stats-tasks col-xs-12 col-md-6 col-sm-6 col-lg-3 tw-mb-5">              
                        <div class="top_stats_wrapper">                                  
                            <div class=" tw-flex tw-items-center tw-justify-between">                      
                                <i class="fa fa-check fa-2x"></i>
                                <div class="tw-items-center tw-truncate">                                        
                                    <h4 class="tw-truncate">Delivered</h4>                      
                                </div>                      
                                <h4 class="tw-font-bold"><?php echo email_newsletter_get_emails_count($campaign_id, 'delivered') ?></h4>
                            </div>
                        </div>          
                    </div>
                    
                    <div class="quick-stats-tasks col-xs-12 col-md-6 col-sm-6 col-lg-3 tw-mb-5">              
                        <div class="top_stats_wrapper">                                  
                            <div class=" tw-flex tw-items-center tw-justify-between">                      
                                <i class="fa fa-envelope-open fa-2x"></i>
                                <div class="tw-items-center tw-truncate">                                        
                                    <h4 class="tw-truncate">Opened</h4>                      
                                </div>                      
                                <h4 class="tw-font-bold"><?php echo email_newsletter_get_emails_count($campaign_id, 'opened') ?></h4>
                            </div>
                        </div>          
                    </div>
                    
                    <div class="quick-stats-tasks col-xs-12 col-md-6 col-sm-6 col-lg-3 tw-mb-5">              
                        <div class="top_stats_wrapper">                                  
                            <div class=" tw-flex tw-items-center tw-justify-between">                      
                                <i class="fa fa-hand-pointer fa-2x"></i>
                                <div class="tw-items-center tw-truncate">                                        
                                    <h4 class="tw-truncate">Clicked</h4>                      
                                </div>                      
                                <h4 class="tw-font-bold"><?php echo email_newsletter_get_emails_count($campaign_id, 'clicked') ?></h4>
                            </div>
                        </div>          
                    </div>
                    
                    <div class="quick-stats-tasks col-xs-12 col-md-6 col-sm-6 col-lg-3 tw-mb-5">              
                        <div class="top_stats_wrapper">                                  
                            <div class=" tw-flex tw-items-center tw-justify-between">                      
                                <i class="fa fa-times fa-2x"></i>
                                <div class="tw-items-center tw-truncate">                                        
                                    <h4 class="tw-truncate">Bounced</h4>                      
                                </div>                      
                                <h4 class="tw-font-bold"><?php echo email_newsletter_get_emails_count($campaign_id, 'bounced') ?></h4>
                            </div>
                        </div>          
                    </div>
                    
                    <div class="quick-stats-tasks col-xs-12 col-md-6 col-sm-6 col-lg-3 tw-mb-5">              
                        <div class="top_stats_wrapper">                                  
                            <div class=" tw-flex tw-items-center tw-justify-between">                      
                                <i class="fa fa-exclamation-triangle fa-2x"></i>
                                <div class="tw-items-center tw-truncate">                                        
                                    <h4 class="tw-truncate">Rejected</h4>                      
                                </div>                      
                                <h4 class="tw-font-bold"><?php echo email_newsletter_get_emails_count($campaign_id, 'rejected') ?></h4>
                            </div>
                        </div>          
                    </div>
                    
                    <div class="quick-stats-tasks col-xs-12 col-md-6 col-sm-6 col-lg-3 tw-mb-5">              
                        <div class="top_stats_wrapper">                                  
                            <div class=" tw-flex tw-items-center tw-justify-between">                      
                                <i class="fa fa-ban fa-2x"></i>
                                <div class="tw-items-center tw-truncate">                                        
                                    <h4 class="tw-truncate">Unsubscribed</h4>                      
                                </div>                      
                                <h4 class="tw-font-bold"><?php echo email_newsletter_get_emails_count($campaign_id, 'unsubscribed') ?></h4>
                            </div>
                        </div>          
                    </div>
                    
                </div>
            </div>
        </div>
        
        
        <div class="row">
            <div class="col-md-12">
                <div class="panel_s">
                    <div class="col-md-12 panel-body">
                        <?php render_datatable([
                            _l('ID'),
                            _l('Email'),
                            _l('Delivered'),
                            _l('Opened'),
                            _l('Clicked'),
                            _l('Bounced'),
                            _l('Rejected'),
                            _l('Unsubscribed'),
                            _l('Sent At'),
                        ], 'newsletter-list'); ?>

                    </div>
                </div>
            </div>
        </div>

        <div class="btn-bottom-pusher"></div>
    </div>
</div>
<?php init_tail(); ?>
<script>
    $(document).ready(function() {
        "use strict";
        $(function() {
            initDataTable('.table-newsletter-list', '<?php echo admin_url('email_newsletter/campaign_details_table/?campaign_id='.$campaign_id) ?>', [], [0,0], [], [0, 'desc']);
        });
    });
</script>
</body>
</html>
