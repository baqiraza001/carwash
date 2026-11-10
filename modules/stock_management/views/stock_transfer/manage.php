<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="panel_s">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-4">
                                <span class="header-btn-new">
                                    <a href="<?= admin_url('stock_management/stock_transfer/create'); ?>"
                                       class="btn btn-info pull-left"><?= _l('add_new');?>
                                    </a>
                                </span>
                            </div>
                        </div>
                        <hr class="hr-panel-heading">

                        <?php 
                        $table_data = [
                            _l('from_warehouse'),
                            _l('to_warehouse'),
                            _l('product'),
                            _l('quantity'),
                            _l('date'),
                            
                        ]; 
                        array_push($table_data,_l('actions'));
                        render_datatable($table_data,'stock_transfer')
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php init_tail(); ?>
<script>
    $(function () {
        initDataTable('.table-stock_transfer', window.location.href, [], [], '', [0, 'DESC']);
    });
</script>
</body>
</html>
