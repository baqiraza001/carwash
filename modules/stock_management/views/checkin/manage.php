<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="panel_s">
                    <div class="panel-body">
                        <div class="_buttons">
                            <?php if (staff_can('create', $permission_module) ) {?>
                                <span class="header-btn-new">
                                <a href="<?= admin_url('stock_management/'.$module.'/create') ?>"
                                   class="btn btn-info"><?= _l('add_checkin');?>
                                    <i class="fa-regular fa-plus" aria-hidden="true"></i>
                                </a>
                            </span>
                            <?php } ?>
                        </div>
                        <div class="clearfix"></div>
                        <hr class="hr-panel-heading">
                        <?php render_datatable([
                            _l('reference'),
                            _l('item'),
                            _l('supplier'),
                            _l('quantity'),
                            _l('warehouse'),
                            _l('date'),
                            _l('actions'),
                            ],'stock_checkins'); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php init_tail(); ?>
<script>
    $(function(){
        initDataTable('.table-stock_checkins', window.location.href,[],[],'',['0','DESC']);
    });
</script>
</body>
</html>
