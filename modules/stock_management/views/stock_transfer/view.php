<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper" class="">
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="panel_s">
                    <div class="panel-body">
                        <h4 class="no-margin"><?= $form_heading; ?></h4>
                        <hr class="hr-panel-heading" />
                        <div class="row">
                            <div class="col-md-6">
                                <?php
                                $value = isset($from_warehouse) ? $from_warehouse : '';
                                echo render_select('from_warehouse' , $warehouses , ['id','name'] , _l('from_warehouse'),$value); ?>
                            </div>
                            <div class="col-md-6">
                                <?php
                                $value = isset($to_warehouse) ? $to_warehouse : '';
                                echo render_select('to_warehouse' , $warehouses , ['id','name'] , _l('to_warehouse'),$value); ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <?php
                                $value = isset($product) ? $product : '';
                                echo render_select('product' , $products , ['id','description'] , _l('product'),$value); ?>
                            </div>
                            <div class="col-md-6">
                                <?php
                                $value = isset($quantity) ? $quantity : '';
                                echo render_input('quantity',_l('quantity'),$value,'number');
                                ?>
                            </div>
                        </div>
                        <div class="row">
                            
                            <div class="col-md-6">
                                <?php
                                $value = isset($date) ? _d($date) : _d(date('Y-m-d'));
                                echo render_date_input('date',_l('date'),$value);
                                ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <?php
                                $value = isset($reason) ? $reason : '';
                                echo render_textarea('reason',_l('reason'),$value,[],[],'','');
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="btn-bottom-toolbar btn-toolbar-container-out text-right">
                <a href="<?= admin_url('stock_management/stock_transfer'); ?>" type="button" class="btn btn-default cancel-me"><?= _l('cancel');?></a>
            </div>
        </div>
    </div>
    <div class="btn-bottom-pusher"></div>
</div>
<?php init_tail(); ?>
<script type="text/javascript">
    init_editor('#reason', {
               readonly: 1,
          });
    $(document).ready(function(){
        $('.panel-body *').prop('disabled', true);
        
    })
</script>
</body>
</html>
