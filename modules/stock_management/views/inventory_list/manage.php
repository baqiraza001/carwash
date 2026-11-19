<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php // VERSION: 2026-11-19 22:20 PKT - fix double save: Save button is type=button, one saveNewItem() path for click + Enter, in-flight guard disables the button during the request ?>
<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="panel_s">
                    <div class="panel-body">
                        
                        <div class="row">
                            <div class="col-md-3">
                                <?= render_select('sm_stock_filter', [
                                    ['value' => '1', 'name' => 'All Items'],
                                    ['value' => '2', 'name' => 'Low Stock']
                                ], ['value', 'name'], 'Stock Filter', '1'); ?>
                            </div>
                        
                            <div class="col-md-3">
                                <label for="remaining_items">Quantity at or below</label>
                                <input type="number" min="0" step="1" class="form-control" id="remaining_items" name="remaining_items" placeholder="Quantity">
                            </div>
                            
                            <div class="col-md-3">
                                <label for="remaining_items_greater_than">Quantity at or above</label>
                                <input type="number" min="0" step="1" class="form-control" id="remaining_items_greater_than" name="remaining_items_greater_than" placeholder="Quantity">
                            </div>
                        
                            <div class="col-md-3">
                                <label for="inventory_value">Total value at or above</label>
                                <input type="number" min="0" step="0.01" class="form-control" id="inventory_value" name="inventory_value" placeholder="Amount">
                            </div>
                        
                            <div class="col-md-12 text-right mt-2">
                                <button type="button" id="clear_filters_button" class="btn btn-default">
                                    <i class="fa-solid fa-eraser"></i> Clear Filters
                                </button>
                                <button id="filter_button" class="btn btn-primary">
                                    <i class="fa-solid fa-filter"></i> Filter
                                </button>
                            </div>
                        </div>

                        
                    </div>
                </div>
            </div>
        </div>

        <div class="row" style="display: none;">
            <div class="col-md-12">
                <div class="panel_s">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-6">
                                <?= render_input('inventory_low_stock', 'Low Stock Threshold', get_option('sm_low_stock'), 'number', ['id' => 'inventory_low_stock', 'min' => '0', 'step' => '1']); ?>
                            </div>
                            <div class="col-md-6 text-right" style="padding-top: 25px;">
                                <?php if (staff_can('edit', 'stock_management')) { ?>
                                <button type="button" id="save_low_stock_button" class="btn btn-primary">
                                    <i class="fa-solid fa-floppy-disk"></i> Save Low Stock Setting
                                </button>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <?php if (staff_can('create', 'stock_management')) { ?>
                <button type="button" class="btn btn-info mbot10" style="float:right; margin-left:5px;" id="add_new_item_header_btn">
                    <i class="fa-regular fa-plus"></i> Add New Item
                </button>
                <?php } ?>
                <button type="button" class="btn btn-success mbot10" style="float:right;" id="add_inventory_item">
                    <i class="fa-regular fa-plus"></i> Add New
                </button>
            </div>
            <div class="col-md-12">
                <div class="panel_s">
                    <div class="panel-body">
                        <div class="clearfix"></div>
                        <hr class="hr-panel-heading">
                        <?php render_stock_management_datatable([
                            ['name' => 'ID', 'th_attrs' => ['class' => 'not_visible']],
                            _l('Item'),
                            _l('Supplier'),
                            'Glass Price',
                            'Total Value',
                            'Quantity',
                            'Notes',
                            'Actions',
                            ],'inventory_list');
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="inventoryItemModal" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                <h4 class="modal-title" id="inventoryItemModalTitle">Add Inventory Item</h4>
      </div>
            <div class="modal-body">
                    <input type="hidden" id="inventory_checkin_id" value="">
                    <?php
                    if (staff_can('create', 'stock_management')) {
                        echo render_select_with_input_group('inventory_item_id', $inventory_items, ['id', 'description'], 'Item', '', '<div class="input-group-btn"><button type="button" class="btn btn-default" id="add_new_item_btn" title="Add New Item"><i class="fa-regular fa-plus"></i> Add New Item</button></div>', ['id' => 'inventory_item_id']);
                    } else {
                        echo render_select('inventory_item_id', $inventory_items, ['id', 'description'], 'Item', '', ['id' => 'inventory_item_id']);
                    }
                    echo render_select('inventory_supplier_id', $inventory_suppliers, ['id', 'company'], 'Supplier', '', ['id' => 'inventory_supplier_id']);
                    echo render_input('inventory_glass_price', 'Glass Price', '', 'number', ['id' => 'inventory_glass_price', 'min' => '0', 'step' => '0.01']);
                    echo render_input('inventory_quantity', 'Quantity', '', 'number', ['id' => 'inventory_quantity', 'step' => '1']);
                    echo render_textarea('note', 'Notes');
                    ?>
      </div>
      <div class="modal-footer">
        <button class="btn btn-default" data-dismiss="modal">Close</button>
                <button class="btn btn-primary" id="saveInventoryItemBtn">Save</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="newItemModal" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                <h4 class="modal-title">Add New Item</h4>
      </div>
      <?php echo form_open('', ['id' => 'new_item_form']); ?>
            <div class="modal-body">
                    <?php echo render_input('description', 'invoice_item_add_edit_description', '', 'text', ['id' => 'new_item_description']); ?>
                    <?php echo render_textarea('long_description', 'invoice_item_long_description', '', ['id' => 'new_item_long_description']); ?>
                    <div class="form-group">
                        <label for="rate" class="control-label">
                            <?php echo _l('invoice_item_add_edit_rate_currency', e($base_currency->name) . ' <small>(' . _l('base_currency_string') . ')</small>'); ?></label>
                        <input type="number" id="new_item_rate" name="rate" class="form-control" value="">
                    </div>
                    <?php foreach ($currencies as $currency) {
                        if ($currency['isdefault'] == 0 && total_rows(db_prefix() . 'clients', ['default_currency' => $currency['id']]) > 0) { ?>
                    <div class="form-group">
                        <label for="rate_currency_<?php echo e($currency['id']); ?>" class="control-label">
                            <?php echo e(_l('invoice_item_add_edit_rate_currency', $currency['name'])); ?></label>
                        <input type="number" id="rate_currency_<?php echo e($currency['id']); ?>" name="rate_currency_<?php echo e($currency['id']); ?>" class="form-control" value="">
                    </div>
                    <?php }
                    } ?>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label" for="tax"><?php echo _l('tax_1'); ?></label>
                                <select class="selectpicker display-block" data-width="100%" name="tax" id="new_item_tax" data-none-selected-text="<?php echo _l('no_tax'); ?>">
                                    <option value=""></option>
                                    <?php foreach ($taxes as $tax) { ?>
                                    <option value="<?php echo e($tax['id']); ?>" data-subtext="<?php echo e($tax['name']); ?>"><?php echo e($tax['taxrate']); ?>%</option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label" for="tax2"><?php echo _l('tax_2'); ?></label>
                                <select class="selectpicker display-block" disabled data-width="100%" name="tax2" id="new_item_tax2" data-none-selected-text="<?php echo _l('no_tax'); ?>">
                                    <option value=""></option>
                                    <?php foreach ($taxes as $tax) { ?>
                                    <option value="<?php echo e($tax['id']); ?>" data-subtext="<?php echo e($tax['name']); ?>"><?php echo e($tax['taxrate']); ?>%</option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="clearfix mbot15"></div>
                    <?php echo render_input('unit', 'unit', '', 'text', ['id' => 'new_item_unit']); ?>
                    <div id="custom_fields_items">
                        <?php echo render_custom_fields('items'); ?>
                    </div>
                    <?php echo render_select('group_id', $items_groups, ['id', 'name'], 'item_group', '', ['id' => 'new_item_group_id']); ?>
                    <p class="text-muted">The item will be added to your items and will be visible in the inventory list only when inventory is added for that item.</p>
      </div>
      <div class="modal-footer">
        <button class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="saveNewItemBtn">Save</button>
      </div>
      <?php echo form_close(); ?>
    </div>
  </div>
</div>

<?php init_tail(); ?>
<script>
function init_inventory_list_table() {
    var dtServerParams = {};
    dtServerParams['sm_stock_filter'] = '[name="sm_stock_filter"]';
    dtServerParams['remaining_items'] = '[name="remaining_items"]';
    dtServerParams['remaining_items_greater_than'] = '[name="remaining_items_greater_than"]';
    dtServerParams['inventory_value'] = '[name="inventory_value"]';

    return initDataTable('.table-inventory_list', window.location.href, [7], [7], dtServerParams, ['0', 'DESC']);
}

function inventoryAlertResponse(response, successCallback) {
    var result = response;
    if (typeof response === 'string') {
        try {
            result = JSON.parse(response);
        } catch (error) {
            alert_float('danger', 'Invalid server response');
            return;
        }
    }
    if (result.success) {
        alert_float('success', result.message);
        successCallback();
    } else {
        alert_float('danger', result.message || 'The request could not be completed.');
    }
}

$(function(){
    app.options.tables_pagination_limit = "50";
    init_inventory_list_table();
});

$('#filter_button').on('click', function() {
    var minQuantity = $('#remaining_items').val();
    var maxQuantity = $('#remaining_items_greater_than').val();
    var minValue = $('#inventory_value').val();

    if ((minQuantity !== '' && (!/^\d+$/.test(minQuantity) || Number(minQuantity) < 0)) ||
        (maxQuantity !== '' && (!/^\d+$/.test(maxQuantity) || Number(maxQuantity) < 0)) ||
        (minValue !== '' && (!/^\d+(\.\d{1,2})?$/.test(minValue) || Number(minValue) < 0))) {
        alert_float('warning', 'Enter non-negative whole-number quantities and a valid non-negative inventory value.');
        return;
    }
    if (minQuantity !== '' && maxQuantity !== '' && Number(maxQuantity) > Number(minQuantity)) {
        alert_float('warning', 'Quantity at or above cannot be greater than quantity at or below.');
        return;
    }
    $('.table-inventory_list').DataTable().ajax.reload();
});

$('#clear_filters_button').on('click', function() {
    $('#sm_stock_filter').val('1').trigger('change');
    $('#remaining_items, #remaining_items_greater_than, #inventory_value').val('');
    $('.table-inventory_list').DataTable().ajax.reload();
});

$('#save_low_stock_button').on('click', function() {
    var lowStockValue = $('#inventory_low_stock').val();
    if (!/^\d+$/.test(lowStockValue) || Number(lowStockValue) < 0) {
        alert_float('warning', 'Low stock threshold must be a non-negative whole number.');
        return;
    }
    $.post(admin_url + 'stock_management/stock_products/update_inventory_low_stock', { sm_low_stock: lowStockValue })
        .done(function(response) {
            inventoryAlertResponse(response, function() {
                $('.table-inventory_list').DataTable().ajax.reload();
            });
        }).fail(function() {
            alert_float('danger', 'Something went wrong while saving the low stock setting.');
        });
});

$('#add_inventory_item').on('click', function() {
    $('#inventoryItemModalTitle').text('Add Inventory Item');
    $('#inventory_checkin_id').val('');
    $('#inventory_item_id').prop('disabled', false).val('').trigger('change');
    $('#inventory_supplier_id').prop('disabled', false).val('').trigger('change');
    $('#inventory_glass_price, #inventory_quantity, #note').val('');
    $('#inventoryItemModal').modal('show');
});

$(document).on('click', '.inventory-edit', function() {
    var button = $(this);
    $('#inventoryItemModalTitle').text('Edit Inventory Item');
    $('#inventory_checkin_id').val(button.data('checkin-id'));
    $('#inventory_item_id').val(button.data('item-id')).trigger('change').prop('disabled', true);
    $('#inventory_supplier_id').val(button.data('supplier-id')).trigger('change').prop('disabled', true);
    $('#inventory_glass_price').val(button.data('glass-price'));
    $('#inventory_quantity').val(button.data('quantity'));
    $('#note').val(button.data('note'));
    $('#inventoryItemModal').modal('show');
});

$('#saveInventoryItemBtn').on('click', function() {
    var itemId = $('#inventory_item_id').val();
    var supplierId = $('#inventory_supplier_id').val();
    var glassPrice = $('#inventory_glass_price').val();
    var quantity = $('#inventory_quantity').val();
    var note = $('#note').val();
    if (!itemId || !supplierId || glassPrice === '' || quantity === '' || !Number.isInteger(Number(quantity))) {
        alert_float('warning', 'Select an item and supplier and enter a valid glass price and whole-number quantity.');
        return;
    }

    $.post(admin_url + 'stock_management/stock_products/save_inventory_item', {
        checkin_id: $('#inventory_checkin_id').val(),
        item_id: itemId,
        supplier_id: supplierId,
        glass_price: glassPrice,
        quantity: quantity,
        note: note
    })
    .done(function(response) {
        inventoryAlertResponse(response, function() {
            $('#inventoryItemModal').modal('hide');
            $('.table-inventory_list').DataTable().ajax.reload(null, false);
        });
    }).fail(function() {
        alert_float('danger', 'Something went wrong while saving inventory.');
    });
});

// Add New Item from the Inventory page - the full items-page form, no need
// to leave for the items page.
$('#add_new_item_btn, #add_new_item_header_btn').on('click', function() {
    var form = $('#new_item_form');
    if (form.length) {
        form[0].reset();
        form.find('select').selectpicker('val', '').selectpicker('refresh');
    }
    $('#new_item_tax2').prop('disabled', true).selectpicker('refresh');
    $('#newItemModal').modal('show');
});

// One save path only - the click handler and the form submit handler both
// call saveNewItem(), and the in-flight guard stops a second request.
var newItemSaving = false;
function saveNewItem() {
    if (newItemSaving) {
        return;
    }
    var description = $.trim($('#new_item_description').val());
    if (!description) {
        alert_float('warning', 'Enter an item name.');
        return;
    }
    var rate = $('#new_item_rate').val();
    if (rate === '' || rate === null || isNaN(Number(rate))) {
        alert_float('warning', 'Enter the item rate.');
        return;
    }

    newItemSaving = true;
    $('#saveNewItemBtn').prop('disabled', true);
    $.post(admin_url + 'stock_management/stock_products/create_item_ajax', $('#new_item_form').serialize())
    .done(function(response) {
        var result = response;
        if (typeof response === 'string') {
            try {
                result = JSON.parse(response);
            } catch (error) {
                alert_float('danger', 'Invalid server response');
                return;
            }
        }
        if (!result.success) {
            alert_float('danger', result.message || 'The item could not be added.');
            return;
        }
        alert_float('success', result.message);
        if (result.data && result.data.id) {
            var select = $('#inventory_item_id');
            if (select.find('option[value="' + result.data.id + '"]').length === 0) {
                select.append(new Option(result.data.description, result.data.id));
            }
            select.selectpicker('refresh');
            select.val(result.data.id).trigger('change');
        }
        $('#newItemModal').modal('hide');
    }).fail(function() {
        alert_float('danger', 'Something went wrong while adding the item.');
    }).always(function() {
        newItemSaving = false;
        $('#saveNewItemBtn').prop('disabled', false);
    });
}

$('#saveNewItemBtn').on('click', saveNewItem);

$('#new_item_form').on('submit', function(e) {
    e.preventDefault();
    saveNewItem();
});

$(document).on('click', '.inventory-delete', function() {
    var checkinId = $(this).data('checkin-id');
    if (!confirm('Delete this item and supplier from inventory?')) {
        return;
    }
    $.post(admin_url + 'stock_management/stock_products/delete_inventory_item', { checkin_id: checkinId })
        .done(function(response) {
            inventoryAlertResponse(response, function() {
                $('.table-inventory_list').DataTable().ajax.reload(null, false);
            });
        }).fail(function() {
            alert_float('danger', 'Something went wrong while deleting inventory.');
        });
});
</script>


</body>
</html>