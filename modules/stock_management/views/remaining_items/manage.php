<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="panel_s">
                    <div class="panel-body">
                        <div class="clearfix"></div>
                        <hr class="hr-panel-heading">
                        <?php render_datatable([
                            _l('Warehouse'),
                            _l('Item'),
                            _l('Remaining Quantity'),
                            ],'remaining_items'); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php init_tail(); ?>
<script>
    $(function(){
        initDataTable('.table-remaining_items', window.location.href,[],[],'',['0','DESC']);
    });
</script>
</body>
</html>
