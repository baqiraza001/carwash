<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper" class="">
    <div class="content">
        <div class="row">
            <?= form_open($this->uri->uri_string(), ['class' => 'sm_adjustment_form', 'autocomplete' => 'off', 'name' => 'sm_adjustment_form','enctype'=>'multipart/form-data']); ?>
            <div class="col-md-12">
                <div class="panel_s">
                    <div class="panel-body">
                        <h4 class="no-margin"><?= $form_heading; ?></h4>
                        <hr class="hr-panel-heading" />
                        <div class="row">
                            <div class="col-md-6">
                                <?php
                                $value = isset($warehouse) ? $warehouse : '';
                                echo render_select('warehouse' , $warehouses , ['id','name'] , _l('warehouse'),$value); ?>
                            </div>
                            <div class="col-md-6">
                                <?php
                                $value = isset($product) ? $product : '';
                                echo render_select('product' , $products , ['id','name'] , _l('product'),$value); ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <?php
                                $value = isset($quantity) ? $quantity : '';
                                echo render_input('quantity',_l('quantity'),$value,'number');
                                ?>
                            </div>
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
                                echo render_textarea('reason',_l('reason'),$value,[],[],'','tinymce');
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="btn-bottom-toolbar btn-toolbar-container-out text-right">
                <button type="submit" name="submit" value="save" class="btn btn-info save"><?= _l('save');?></button>
                <a href="<?= admin_url('stock_management/stock_adjustment'); ?>" type="button" class="btn btn-default cancel-me"><?= _l('cancel');?></a>
            </div>
            <?= form_close(); ?>
        </div>
    </div>
    <div class="btn-bottom-pusher"></div>
</div>
<?php init_tail(); ?>
</body>
</html>
