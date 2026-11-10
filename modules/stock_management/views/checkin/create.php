<?php init_head(); ?>  
<div id="wrapper" class="contact_details">
<div class="content">
    <div class="row">
        <?= form_open($this->uri->uri_string(), ['class' => 'checkin_form', 'autocomplete' => 'off', 'name'=>'checkin_form']); ?>
        <div class="col-md-12">
            <div class="panel_s">
                <div class="panel-body">
                    <h4 class="no-margin"><?= $form_heading; ?></h4>
                    <hr class="hr-panel-heading" />
                           <?php 
                            $disabled_attributes = [];
                            if($edit_mode){
                                $disabled_attributes = ['disabled' => 'disabled'];
                            } ?>
                            <div class="row">
                                <div class="col-md-6">
                                    <?php
                                    $value = isset($supplier) ? $supplier : '';
                                    $options = isset($suppliers) ? $suppliers : [];
                                    echo render_select('supplier',$options,['id','company'],_l('supplier'),$value);
                                    ?>
                                </div>
                                <div class="col-md-6">
                                    <?php
                                    $value = isset($item) ? $item : '';
                                    echo render_select('item' , $products , ['id','description'] , _l('item'),$value,$disabled_attributes); ?>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <?php
                                    $value = isset($reference) ? $reference : '';
                                    echo render_input('reference',_l('reference'),$value);
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
                                <div class="col-md-6">
                                    <?php
                                    $value = isset($warehouse) ? $warehouse : 1;
                                    echo render_select('warehouse' , $warehouses , ['id','name'] , _l('warehouse'),$value,$disabled_attributes); ?>
                                </div>
                                <div class="col-md-6">
                                    <?php
                                    $value = isset($shelf) ? $shelf : '';
                                    echo render_input('shelf',_l('shelf'),$value);
                                    ?>
                                </div>
                                
                            </div>
                            <div class="row">
                                <!-- <div class="col-md-6">
                                    <?php
                                    $value = isset($purchase_price) ? $purchase_price : '';
                                    echo render_input('purchase_price',_l('purchase_price'),$value,'number');
                                    ?>
                                </div> -->
                                <div class="col-md-6">
                                    <?php
                                    $value = isset($units) ? $units : '';
                                    echo render_input('units',_l('quantity'),$value,'number',$disabled_attributes);
                                    ?>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <?php
                                    $value = isset($note) ? $note : '';
                                    echo render_textarea('note',_l('note'),$value,[],[],'','tinymce');
                                    ?>
                                </div>
                            </div>
                        </div>
                </div>
            </div>
        </div>
    </div>
    <div class="btn-bottom-toolbar btn-toolbar-container-out text-right">
        <button type="submit" name="submit" value="save"  class="btn btn-info checkin-form-submitter save"><?= _l('save');?></button>
            <button type="button" class="btn btn-default cancel-me"><?= _l('cancel');?></button>
    </div>
    <?= form_close(); ?>
    <div class="btn-bottom-pusher"></div>
</div>

<?php init_tail(); ?>
<script src="<?= module_dir_url('stock_management', 'assets/js/checkin_js.js'); ?>"></script>
</body>
</html>
