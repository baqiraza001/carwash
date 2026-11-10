<?php init_head(); ?>
<div id="wrapper" class="">
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="panel_s">
                  <div class="panel-body">
                        <h4 class="list-title border-right list_title_hr"
                            ><?= $title; ?></h4>
                        <nav aria-label="breadcrumb" class="d-inline-block pull-left breadcrub_hr">
                            <ol class="breadcrumb p0 ol_hr">
                              <?php foreach ($breadcrumb_data['breadcrumb_array'] as $key => $value) { ?>
                                <li class="breadcrumb-item"><a href="<?= $key; ?>"><?= $value; ?></a></li>
                              <?php } ?>
                              <li class="breadcrumb-item active" aria-current="page"><?= $breadcrumb_data['current_page_title']; ?></li>
                            </ol>
                        </nav>

                        <div class="clearfix"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <?= form_open($this->uri->uri_string(), ['class' => '_suppliers_form', 'autocomplete' => 'off', 'name' => 'hr_form','enctype'=>'multipart/form-data']); ?>
            <div class="col-md-12">
                <div class="panel_s">
                    <div class="panel-body">
                        <div class="clearfix"></div>
                        <div>
                            <h4 class="tab-panel-heading"><?= $form_heading; ?></h4>
                            <div class="row">
                                <div class="col-md-6">
                                    <?php
                                    $value = isset($company) ? $company : '';
                                    echo render_input('company', _l('company'), $value);
                                    ?>
                                </div>
                                <div class="col-md-6">
                                    <?php
                                    $value = isset($address) ? $address : '';
                                    echo render_textarea('address', _l('address'), $value);
                                    ?>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <?php
                                    $value = isset($vat_no) ? $vat_no : '';
                                    echo render_input('vat_no', _l('vat_number'), $value);
                                    ?>
                                </div>
                                <div class="col-md-6">
                                    <?php
                                    $value = isset($city) ? $city : '';
                                    echo render_input('city', _l('city'), $value);
                                    ?>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <?php
                                    $value = isset($phone) ? $phone : '';
                                    echo render_input('phone', _l('phone'), $value);
                                    ?>
                                </div>
                                <div class="col-md-6">
                                    <?php
                                    $value = isset($state) ? $state : '';
                                    echo render_input('state', _l('state'), $value);
                                    ?>
                                </div>
                            </div>
                            <div class="row">

                                <div class="col-md-6">
                                    <?php
                                    $value = isset($website) ? $website : '';
                                    echo render_input('website', _l('website'), $value);
                                    ?>
                                </div>
                                <div class="col-md-6">
                                    <?php
                                    $value = isset($postal_code) ? $postal_code : '';
                                    echo render_input('postal_code', _l('postal_code'), $value);
                                    ?>
                                </div>
                            </div>
                            <div class="row">

                                <div class="col-md-6">
                                   <?php
                                   $value = isset($country) ? $country : '';
                                   echo render_select('country',stock_get_countries(),['id','short_name'],_l('country'),$value);
                                   ?>
                               </div>
                                <div class="col-md-6">
                                   <?php
                                   $value = isset($currency) ? $currency : '';
                                   echo render_select('currency',stock_get_currencies(),['id','name'],_l('currency'),$value);
                                   ?>
                               </div>
                            </div>
                            <div class="row">

                                <div class="col-md-6">
                                    <?php
                                    $value = isset($balance) ? $balance : '';
                                    echo render_input('balance', _l('balance'), $value,'number');
                                    ?>
                                </div>
                            </div>
                            <div class="row">

                                <div class="col-md-6">
                                    <?php
                                    $value = isset($other_info) ? $other_info : '';
                                    echo render_textarea('other_info', _l('other_information'), $value);
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php
        $hideClass = '';
        if (isset($is_draft) && $is_draft == 0) {
            $hideClass = "hide";
        }
    ?>
    <div class="btn-bottom-toolbar btn-toolbar-container-out text-right">
        <button type="submit" name="submit" value="save" class="btn btn-info supplier-form-submitter save"><?= _l('save');?></button>
        <button type="submit" name="submit" value="save_as_draft" class="btn btn-success supplier-form-submitter save save_as_draft <?= $hideClass; ?>"><?= _l('save_as_draft');?></button>
        <button type="button" class="btn btn-default cancel-me"><?= _l('cancel');?></button>
    </div>
    <?= form_close(); ?>
<div class="btn-bottom-pusher"></div>
</div>


<?php init_tail(); ?>
<script src="<?= module_dir_url('stock_management', 'assets/js/suppliers_js.js'); ?>"></script>
</body>
</html>
