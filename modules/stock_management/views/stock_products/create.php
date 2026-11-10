<?php init_head(); ?> 
<div id="wrapper" class="contact_details">
    <div class="content">
        <div class="row">
            <?= form_open($this->uri->uri_string(), ['class' => '_stock_products_form','id' => '_stock_products_form', 'autocomplete' => 'off', 'name'=>'_stock_products_form','enctype'=>'multipart/form-data']); ?>
                <div class="col-md-<?php if($edit_mode){ echo '9';}else{ echo '12'; } ?>">
                    <div class="panel_s">
                        <div class="panel-body">
                            <h4 class="no-margin"><?= $form_heading; ?></h4>
                            <hr class="hr-panel-heading" />
                            <div class="row">
                                <div class="col-md-6">
                                    <?php
                                    $value = isset($name) ? $name : '';
                                    echo render_input('name',_l('product').' '._l('title'),$value);
                                    ?>
                                </div>

                                <div class="col-md-6">
                                    <?php
                                    $value = isset($code) ? $code : '';
                                    echo render_input('code',_l('product').' '._l('code'),$value);
                                    ?>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <?php
                                    $value = isset($category) ? $category : '';
                                    $options = isset($categories) ? $categories : [];
                                    echo render_select('category',$options,['id',['name']],_l('product').' '._l('category'),$value);
                                    ?>
                                </div>
                                <div class="col-md-6">
                                    <?php
                                    $value = isset($qty_alert) ? $qty_alert : '';
                                    echo render_input('qty_alert',_l('alert').' '._l('quantity'),$value);
                                    ?>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <?php
                                    $value = isset($tax) ? $tax : '';
                                    $options = isset($taxes) ? $taxes : []; ?>
                                    <label for="tax" class="control-label"><?= _l('tax'); ?></label>
                                    <select id="tax" name="tax" class="selectpicker" data-width="100%" data-none-selected-text="Nothing selected" data-live-search="true" tabindex="-98">
                                        <option value=""></option>
                                    <?php
                                    foreach($options as $option){ ?>
                                        <option value="<?= $option['id']; ?>" data-rate="<?= $option['taxrate']; ?>"><?= $option['name']; ?></option>
                                    <?php } ?>
                                    </select>
                                </div>
                                
                                <div class="col-md-6">
                                    <?php
                                    $value = isset($tax_rate) ? $tax_rate : '';
                                    echo render_input('tax_rate',_l('tax_rate').' ('.$currency_symbol.')',$value,'number');
                                    ?>
                                </div>
                            </div>
                            <div class="row mbot10">
                                <div class="col-md-6">
                                    <label for="product_code" class="control-label"><?= _l('product_code'); ?></label>
                                    <div class="input-group">
                                        <?php 
                                        $value = (isset($product_code) ? $product_code : $random_product_code); ?>
                                        <input type="number" autocomplete="false" class="form-control" name="product_code" value="<?= $value; ?>" id="product_code">

                                        <span class="input-group-addon">
                                            <a href="#" onclick="generateCode(this);return false;"><i
                                                class="fa-solid fa-arrows-rotate" title="Generate Product Code" data-toggle="tooltip"></i>
                                            </a>
                                        </span>
                                        <span class="input-group-addon">
                                            <a href="#" onclick=""><i
                                                class="fa-solid fa-barcode" title="Generate Barcode" data-toggle="tooltip"></i>
                                            </a>
                                        </span>
                                    </div>

                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group" app-field-wrapper="image">
                                        <label for="image" class="control-label"><?= _l('image'); ?></label>
                                        <input type="file" id="image" name="image" class="form-control" accept="image/*">
                                    </div>
                                </div>
                                <?php 
                                if(isset($image) && !empty($image)){ ?>
                                <div class="col-md-4 mtop30">
                                    <input type="hidden" name="image" id="image" value="<?= $image; ?>">
                                    <input type="hidden" name="image_name" id="image_name" value="<?= $image_name; ?>">
                                    <a href="<?= $image; ?>" download="<?= $image_name; ?>" id=""><i class="fa-solid fa-download fa-download_custom"></i></a>
                                    <span class="delete_image" id="" data-id=""><i class="fa-regular fa-trash-can fa-trash_custom"></i></span> <?= $image_name; ?><br/>
                                </div>
                                <?php } ?>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <?php
                                    $value = isset($description) ? $description : '';
                                    echo render_textarea('description',_l('product').' '._l('description'),$value,[],[],'','tinymce');
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php
                if($edit_mode){ ?>
                    <div class="col-md-3">
                        <div class="panel_s">
                            <div class="panel-body">
                                <h4 class="no-margin"><?= _l('item_quantity'); ?></h4>
                                <hr class="hr-panel-heading" />
                                <table class="table">
                                    <thead class="bg-primary">
                                        <tr>
                                            <th class="clr-white"><?= _l('warehouse'); ?></th>
                                            <th class="clr-white"><?= _l('quantity'); ?></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $CI = &get_instance();
                                        $warehouses = $CI->db->get('tbl_sm_warehouses')->result_array();
                                        ?>
                                        <?php 
                                        foreach($warehouses as $warehouse){ 
                                            $qty = sm_item_qty_find($id , $warehouse['id']); ?>
                                        <tr>
                                            <td><?= $warehouse['name']; ?></td>
                                            <td><span class="item_qty_span"><?php if(isset($qty) && !empty($qty)){ echo $qty; }else{ echo '0';}?></span></td>
                                        </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                <?php } ?>
                <div class="btn-bottom-toolbar btn-toolbar-container-out text-right">
                    <button type="submit" name="submit" value="save"  class="btn btn-info save"><?= _l('save');?></button>
                    <a href="<?= admin_url('stock_management/stock_products'); ?>" type="button" class="btn btn-default" onclick=""><?= _l('cancel');?></a>
                </div>
            <?= form_close(); ?>
        </div>
    </div>
    <div class="btn-bottom-pusher"></div>
</div>
<?php init_tail(); ?>
<script src="<?= module_dir_url('stock_management', 'assets/js/stock_products_js.js'); ?>"></script>
<script type="text/javascript">
    $(document).ready(function () {
        $("#_stock_products_form").validate({
            rules: {
                product_code: {
                    remote: {
                        url: admin_url + 'stock_management/stock_products/check_if_code_exists',
                        type: 'post',
                        async : false,
                        data: {
                            code: function () {
                                return $('#product_code').val();
                            },
                        }
                    }
                },
            },
            messages : {
                product_code : {
                  remote : 'Code already exists',
                }
            }
        });
    });
</script>
</body>
</html>
