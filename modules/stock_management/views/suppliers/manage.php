<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="panel_s">
                    <div class="panel-body">
                        <div class="_buttons">
                            <h4 class="list-title border-right list_title_hr"><?= $title; ?></h4>
                            <nav aria-label="breadcrumb" class="d-inline-block pull-left breadcrub_hr">
                                <ol class="breadcrumb p0 ol_hr">
                                  <?php foreach ($breadcrumb_data['breadcrumb_array'] as $key => $value) { ?>
                                    <li class="breadcrumb-item"><a href="<?= $key; ?>"><?= $value; ?></a></li>
                                  <?php } ?>
                                    <li class="breadcrumb-item active" aria-current="page"><?= $breadcrumb_data['current_page_title']; ?></li>
                                </ol>
                            </nav>
                            <span class="pull-right header-btn-new">
                                <a href="<?= admin_url('stock_management/'.$module.'/create') ?>" class="btn btn-info pull-left display-block"><?= _l('add');?>
                                    <i class="fa-regular fa-plus" aria-hidden="true"></i>
                                </a>
                            </span>
                        </div>
                        <div class="clearfix"></div>
                        <hr class="hr-panel-heading">
                        <?php render_datatable([
                            _l('company'),
                            _l('phone'),
                            _l('website'),
                            _l('country'),
                            _l('actions'),
                          ], 'suppliers'); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php init_tail(); ?>
<script>
    $(function () {
        initDataTable('.table-suppliers', window.location.href, [], [], '', ['0', 'DESC']);
    });
</script>
</body>
</html>
