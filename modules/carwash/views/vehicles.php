<?php

defined('BASEPATH') or exit('No direct script access allowed'); ?>
<h4 class="customer-profile-group-heading"><?= _l('carwash_vehicles'); ?></h4>
<div class="row">
    
    <div class="clearfix"></div>
    <div class="col-md-12" id="">
        <button type="button" class="btn btn-primary" id="carwash_add_vehicle"><?= _l('carwash_add_new'); ?></button>
    </div>
    <div class="col-md-12 mtop15" id="">
      <?php render_datatable([
           _l('carwash_vehicle'),
           _l('carwash_vin'),
           _l('carwash_plate'),
           _l('carwash_actions'),
       ], 'carwash'); ?>
    </div>

<div class="modal fade" id="vehicles_modal" role="dialog">
    <div class="modal-dialog modal-md">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><?= _l('carwash_vehicle'); ?></h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <?php
                        echo render_input('vehicle','carwash_vehicle');
                        ?>
                    </div>
                    <div class="col-md-6">
                        <?php
                        echo render_input('vin','carwash_vin');
                        ?>
                    </div>
                    <div class="col-md-6">
                        <?php
                        echo render_input('plate_no','carwash_plate');
                        ?>
                    </div>
                    <input type="hidden" name="vehicle_id" id="vehicle_id" value="">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="carwash_save_vehicle"><?= _l('save'); ?></button>
                <button type="button" class="btn btn-default" data-dismiss="modal"><?= _l('cancel'); ?></button>
            </div>
 
        </div>

    </div>
</div>
</div>
