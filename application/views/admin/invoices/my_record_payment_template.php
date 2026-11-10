<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?= form_open(admin_url('invoices/record_payment'), ['id' => 'record_payment_form']); ?>
<div class="col-md-12 no-padding animated fadeIn">
    <div class="panel_s">
        <?= form_hidden('invoiceid', $invoice->id); ?>
        <div class="panel-heading">
            <h4 class="panel-title">
                <?= _l('record_payment_for_invoice'); ?>
                <?= e(format_invoice_number($invoice->id)); ?>
            </h4>
        </div>
        <div class="panel-body">
            <div class="row">
                <div class="col-md-6">
                    <?php
                    $payers = $this->db
                        ->select("
                                cp.client_id,
                                (cp.amount - COALESCE(SUM(ipr.amount), 0)) AS amount
                            ", false)
                        ->from('tbl_carwash_payers cp')
                        ->join(
                            db_prefix() . 'invoicepaymentrecords ipr',
                            'ipr.invoiceid = cp.invoice_id
                                 AND ipr.client_id = cp.client_id',
                            'left'
                        )
                        ->where('cp.invoice_id', $invoice->id)
                        ->group_by(['cp.client_id', 'cp.amount'])
                        ->get()
                        ->result_array();
                    ?>
                    <div class="form-group">
                        <label for="client_id" class="control-label"><?= _l('invoice_select_customer'); ?></label>
                        <select class="selectpicker" id="payment_client" name="client_id" data-width="100%" data-none-selected-text="<?= _l('dropdown_non_selected_tex'); ?>">
                            <option value=""></option>
                            <?php foreach ($payers as $payer) {
                                $payer_client = get_client($payer['client_id']);
                                ?>
                            <option value="<?= $payer['client_id']; ?>" data-amount="<?= $payer['amount']; ?>" data-credit-amount="<?php if ($payer_client->credit_amount > 0) { echo $payer_client->credit_amount; } ?>"><?= $payer_client->company; ?>
                                <?php if ($payer_client->credit_amount > 0) { ?>
                                <small>(Credits Available)</small>
                                <?php } ?>
                            </option>
                            <?php } ?>
                        </select>
                        <input type="hidden" id="payment_credit_value" value="" />
                    </div>
                    <div class="checkbox checkbox-primary apply_credit_amount hide inline-block">
                        <input type="checkbox" name="credit_amount_applied" id="apply_credit_amount" value="1">
                        <label for="apply_credit_amount" id="apply_credit_amount_label"><?= _l('Apply Credit Amount'); ?></label>
                    </div>
                    <?php
                    $amount = $invoice->total_left_to_pay;
$totalAllowed               = 0;
echo render_input('amount', 'record_payment_amount_received', $amount, 'number', ['max' => $amount]); ?>
                    <input type="hidden" id="payment_original_amount" value="<?= $amount; ?>" />
                    <?= render_date_input('date', 'record_payment_date', _d(date('Y-m-d'))); ?>
                    <div class="form-group">
                        <label for="paymentmode"
                            class="control-label"><?= _l('payment_mode'); ?></label>
                        <select class="selectpicker" name="paymentmode" data-width="100%"
                            data-none-selected-text="<?= _l('dropdown_non_selected_tex'); ?>">
                            <option value=""></option>
                            <?php foreach ($payment_modes as $mode) { ?>
                            <?php
        if (is_payment_mode_allowed_for_invoice($mode['id'], $invoice->id)) {
            $totalAllowed++; ?>
                            <option
                                value="<?= e($mode['id']); ?>">
                                <?= e($mode['name']); ?>
                            </option>
                            <?php
        } ?>
                            <?php } ?>
                        </select>
                    </div>
                    <?php
                    if ($totalAllowed === 0) {
                        ?>
                    <div class="alert alert-info">
                        Allowed payment modes not found for this invoice.<br />
                        Click <a
                            href="<?= admin_url('invoices/invoice/' . $invoice->id . '?allowed_payment_modes=1'); ?>"
                            class="alert-link">
                            here
                        </a> to edit the invoice and allow payment modes.
                    </div>
                    <?php
                    } ?>
                </div>
                <div class="col-md-6">
                    <?= render_input('transactionid', 'payment_transaction_id'); ?>
                    <div class="form-gruoup">
                        <label for="note"
                            class="control-label"><?= _l('record_payment_leave_note'); ?></label>
                        <textarea name="note" class="form-control" rows="8"
                            placeholder="<?= _l('invoice_record_payment_note_placeholder'); ?>"
                            id="note"></textarea>
                    </div>
                </div>
                <div class="col-md-12 tw-mt-3">
                    <?php
                    $pr_template = is_email_template_active('invoice-payment-recorded');
$sms_trigger                     = is_sms_trigger_active(SMS_TRIGGER_PAYMENT_RECORDED);
if ($pr_template || $sms_trigger) { ?>
                    <div class="checkbox checkbox-primary mtop15">
                        <input type="checkbox" name="do_not_send_email_template" id="do_not_send_email_template">
                        <label for="do_not_send_email_template">
                            <?php
        if ($pr_template) {
            echo _l('do_not_send_invoice_payment_email_template_contact');
            if ($sms_trigger) {
                echo '/';
            }
        }
    if ($sms_trigger) {
        echo 'SMS ' . _l('invoice_payment_recorded');
    }
    ?>
                        </label>
                    </div>
                    <?php } ?>
                    <div class="checkbox checkbox-primary mtop15 do_not_redirect hide">
                        <input type="checkbox" name="do_not_redirect" id="do_not_redirect" checked>
                        <label
                            for="do_not_redirect"><?= _l('do_not_redirect_payment'); ?></label>
                    </div>
                </div>
            </div>
            <?php
            hooks()->do_action('after_admin_last_record_payment_form_field', $invoice);
if ($payments) { ?>
            <div class="mtop25 inline-block full-width">
                <h5 class="bold">
                    <?= _l('invoice_payments_received'); ?>
                </h5>
                <?php include_once APPPATH . 'views/admin/invoices/invoice_payments_table.php'; ?>
            </div>
            <?php } ?>

            <?php hooks()->do_action('before_admin_add_payment_form_submit', $invoice); ?>
        </div>

        <div class="panel-footer text-right">
            <a href="#" class="btn btn-danger"
                onclick="init_invoice(<?= e($invoice->id); ?>); return false;"><?= _l('cancel'); ?></a>
            <button type="submit" autocomplete="off"
                data-loading-text="<?= _l('wait_text'); ?>"
                data-form="#record_payment_form"
                class="btn btn-success"><?= _l('submit'); ?></button>
        </div>
    </div>
</div>
<?= form_close(); ?>
<script>
    $(function() {
        init_selectpicker();
        init_datepicker();
        appValidateForm($('#record_payment_form'), {
            amount: 'required',
            date: 'required',
            paymentmode: 'required',
            client_id: 'required'
        });
        var $sMode = $('select[name="paymentmode"]');
        var total_available_payment_modes = $sMode.find('option').length - 1;
        if (total_available_payment_modes == 1) {
            $sMode.selectpicker('val', $sMode.find('option').eq(1).attr('value'));
            $sMode.trigger('change');
        }

        $("#payment_client").on('change', function() {
            var selectedOption = $("#payment_client").find("option:selected");
            var creditAmount = selectedOption.data("credit-amount");
            var amount = selectedOption.data("amount");
            $('#payment_original_amount').val(amount);
            $('#amount').val(amount);
            $('#amount').attr('max', $(amount));
            $("#apply_credit_amount").removeAttr('checked');

            if (creditAmount) {
                $('#payment_credit_value').val(creditAmount);
                $("#apply_credit_amount_label").html('Apply Credit Amount ' + `(${creditAmount})`);
                $('.apply_credit_amount').removeClass('hide');
            } else {
                $('#payment_credit_value').val('');
                $('.apply_credit_amount').addClass('hide');
            }
        });

        $("#apply_credit_amount").change(function() {
            if ($(this).is(":checked")) {
                $('#amount').val($('#payment_credit_value').val());
                $('#amount').attr('max', $('#payment_credit_value').val());
            } else {
                $('#amount').val($('#payment_original_amount').val());
                $('#amount').attr('max', $('#payment_original_amount').val());
            }
        });
    });
</script>