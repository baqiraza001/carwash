<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper" class="">
    <div class="content">
        <div class="row">
            <?= form_open($this->uri->uri_string(), ['class' => 'sm_transfer_form','id' => 'sm_transfer_form', 'autocomplete' => 'off', 'name' => 'sm_transfer_form','enctype'=>'multipart/form-data']); ?>
            <div class="col-md-12">
                <div class="panel_s">
                    <div class="panel-body">
                        <h4 class="no-margin"><?= $form_heading; ?></h4>
                        <hr class="hr-panel-heading" />
                        <div class="row">
                            <div class="col-md-6">
                                <?php
                                $value = isset($from_warehouse) ? $from_warehouse : 1;
                                echo render_select('from_warehouse' , $warehouses , ['id','name'] , _l('from_warehouse'),$value); ?>
                            </div>
                            <div class="col-md-6">
                                <?php
                                $value = isset($to_warehouse) ? $to_warehouse : 2;
                                echo render_select('to_warehouse' , $warehouses , ['id','name'] , _l('to_warehouse'),$value); ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <?php
                                $value = isset($product) ? $product : '';
                                $options = isset($products) ? $products : []; ?>
                                <div class="form-group" app-field-wrapper="product">
                                    <label for="product" class="control-label"><?= _l('product'); ?></label>
                                    <select id="product" name="product" class="selectpicker" data-width="100%" data-none-selected-text="Nothing selected" data-live-search="true" tabindex="-98">
                                        <option value=""></option>
                                    <?php
                                    foreach($options as $option){ ?>
                                        <option value="<?= $option['id']; ?>"><?= $option['description']; ?></option>
                                    <?php } ?>
                                    </select>
                                </div>
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
                                echo render_textarea('reason',_l('reason'),$value,[],[],'','tinymce');
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="btn-bottom-toolbar btn-toolbar-container-out text-right">
                <button type="submit" name="submit" value="save" class="btn btn-info save"><?= _l('save');?></button>
                <a href="<?= admin_url('stock_management/stock_transfer'); ?>" type="button" class="btn btn-default cancel-me"><?= _l('cancel');?></a>
            </div>
            <?= form_close(); ?>
        </div>
    </div>
    <div class="btn-bottom-pusher"></div>
</div>
<?php init_tail(); ?>
<script type="text/javascript">
    $('body').on('change','#from_warehouse, #product',function () {
        getProductQuantity();
    });

    $(document).ready(function () {
        getProductQuantity();
        $("#sm_transfer_form").validate({
            rules: {
                quantity: {
                    required : true,
                    remote: {
                        url: admin_url + 'stock_management/stock_transfer/check_if_quantity_exists',
                        type: 'post',
                        data: {
                            warehouse: function () {
                                return $('#from_warehouse').val();
                            },
                            product: function () {
                                return $('#product').val();
                            },
                            quantity: function () {
                                return $('#quantity').val();
                            }
                        }
                    }
                },

                product : {
                    required : true
                },
                from_warehouse : {
                    required : true
                },
                to_warehouse : {
                    required : true
                },
            },
            messages : {
                quantity : {
                  remote : 'Quantity exceeded',
                }
            }
        });
    });

    function getProductQuantity(){
        var warehouse = $('#from_warehouse').val();
        var product = $('#product').val();
        var action= admin_url + 'stock_management/stock_transfer/get_product_count';
        $.ajax(action, {
          type: 'POST',
          data: {warehouse : warehouse , product : product},
        }).done(function(data){
            data = JSON.parse(data);
            $('#product_count').text(data.count);
        });
    }
</script>
</body>
</html>
