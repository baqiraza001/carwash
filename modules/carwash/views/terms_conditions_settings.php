<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div class="row">
    <div class="col-md-12">
        <div class="panel_s">
            <div class="panel-body">
                <h4 class="no-margin"><?= _l('carwash_terms_and_conditions'); ?></h4>
                <hr class="hr-panel-heading" />

                <button type="button" class="btn btn-primary" id="add_term_condition"><?= _l('carwash_add_new'); ?></button>

                <div class="clearfix"></div>
                <div class="mtop15">
                    <?php render_datatable([
                        _l('carwash_title'),
                        _l('carwash_description'),
                        _l('carwash_created_on'),
                        _l('carwash_actions'),
                    ], 'carwash_terms_conditions'); ?>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="term_condition_modal" tabindex="-1" role="dialog" aria-labelledby="termConditionModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="termConditionModalLabel"><?= _l('carwash_terms_and_conditions'); ?></h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <?= render_input('cw_tc_title', 'carwash_title'); ?>
                    </div>
                    <div class="col-md-12">
                        <?= render_textarea('cw_tc_description', 'carwash_description'); ?>
                    </div>
                </div>
                <input type="hidden" id="cw_tc_id" value="">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="save_term_condition"><?= _l('save'); ?></button>
                <button type="button" class="btn btn-default" data-dismiss="modal"><?= _l('cancel'); ?></button>
            </div>
        </div>
    </div>
</div>
