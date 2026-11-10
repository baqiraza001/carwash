<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php if ((credits_can_be_applied_to_invoice($invoice->status) && $credits_available > 0)) { ?>
<div class="alert alert-warning mbot5">
    <?= e(_l('x_credits_available', app_format_money($credits_available, $customer_currency->name))); ?>
    <br />
    <a href="#" data-toggle="modal" class="alert-link"
        data-target="#apply_credits"><?= _l('apply_credits'); ?></a>
</div>
<?php } ?>
<?php if (count($invoices_to_merge) > 0) { ?>
<div class="panel_s no-padding mbot5 mergeable-invoices">
    <div class="panel-body">
        <h4 class="tw-mt-0 tw-text-base tw-font-semibold">
            <?= _l('invoices_available_for_merging'); ?>
        </h4>
        <?php foreach ($invoices_to_merge as $_inv) { ?>
        <div class="tw-flex tw-justify-between tw-items-center tw-mb-2 last:tw-mb-0">
            <div>
                <a href="<?= admin_url('invoices/list_invoices/' . $_inv->id); ?>"
                    target="_blank"
                    class="tw-font-medium"><?= e(format_invoice_number($_inv->id)); ?></a>
                -
                <span class="tw-text-neutral-500">
                    <?= e(app_format_money($_inv->total, $_inv->currency_name)); ?>
                </span>
            </div>
            <?= format_invoice_status($_inv->status); ?>
        </div>
        <?php } ?>
    </div>
</div>
<?php } ?>
<?= form_hidden('_attachment_sale_id', $invoice->id); ?>
<?= form_hidden('_attachment_sale_type', 'invoice'); ?>
<div class="col-md-12 no-padding">
    <div class="panel_s">
        <div class="panel-body">
            <div class="horizontal-scrollable-tabs preview-tabs-top panel-full-width-tabs">
                <div class="scroller arrow-left"><i class="fa fa-angle-left"></i></div>
                <div class="scroller arrow-right"><i class="fa fa-angle-right"></i></div>
                <div class="horizontal-tabs">
                    <ul class="nav nav-tabs nav-tabs-horizontal mbot15" role="tablist">
                        <li role="presentation" class="active">
                            <a href="#tab_invoice" aria-controls="tab_invoice" role="tab" data-toggle="tab">
                                <?= _l('invoice'); ?>
                            </a>
                        </li>
                        <?php if (count($invoice->payments) > 0) { ?>
                        <li role="presentation">
                            <a href="#invoice_payments_received" aria-controls="invoice_payments_received" role="tab"
                                data-toggle="tab">
                                <?= _l('payments'); ?>
                                <span
                                    class="badge"><?= count($invoice->payments); ?>
                                </span>
                            </a>
                        </li>
                        <?php } ?>
                        <?php if (count($applied_credits) > 0) { ?>
                        <li role="presentation">
                            <a href="#invoice_applied_credits" aria-controls="invoice_applied_credits" role="tab"
                                data-toggle="tab">
                                <?= _l('applied_credits'); ?>
                                <span
                                    class="badge"><?= count($applied_credits); ?></span>
                            </a>
                        </li>
                        <?php } ?>
                        <?php if (count($invoice_recurring_invoices) > 0 || $invoice->recurring != 0) { ?>
                        <li role="presentation">
                            <a href="#tab_child_invoices" aria-controls="tab_child_invoices" role="tab"
                                data-toggle="tab">
                                <?= _l('child_invoices'); ?>
                            </a>
                        </li>
                        <?php } ?>
                        <li role="presentation">
                            <a href="#tab_tasks"
                                onclick="init_rel_tasks_table(<?= e($invoice->id); ?>,'invoice'); return false;"
                                aria-controls="tab_tasks" role="tab" data-toggle="tab">
                                <?= _l('tasks'); ?>
                            </a>
                        </li>
                        <li role="presentation">
                            <a href="#tab_activity" aria-controls="tab_activity" role="tab" data-toggle="tab">
                                <?= _l('invoice_view_activity_tooltip'); ?>
                            </a>
                        </li>
                        <li role="presentation">
                            <a href="#tab_reminders"
                                onclick="initDataTable('.table-reminders', admin_url + 'misc/get_reminders/' + <?= $invoice->id; ?> + '/' + 'invoice', undefined, undefined,undefined,[1,'asc']); return false;"
                                aria-controls="tab_reminders" role="tab" data-toggle="tab">
                                <?= _l('estimate_reminders'); ?>
                                <?php
                        $total_reminders = total_rows(
                            db_prefix() . 'reminders',
                            [
                                'isnotified' => 0,
                                'staff'      => get_staff_user_id(),
                                'rel_type'   => 'invoice',
                                'rel_id'     => $invoice->id,
                            ]
                        );
if ($total_reminders > 0) {
    echo '<span class="badge">' . $total_reminders . '</span>';
}
?>
                            </a>
                        </li>
                        <li role="presentation" class="tab-separator">
                            <a href="#tab_notes"
                                onclick="get_sales_notes(<?= e($invoice->id); ?>,'invoices'); return false"
                                aria-controls="tab_notes" role="tab" data-toggle="tab">
                                <?= _l('estimate_notes'); ?>
                                <span class="notes-total">
                                    <?php if ($totalNotes > 0) { ?>
                                    <span
                                        class="badge"><?= e($totalNotes); ?></span>
                                    <?php } ?>
                                </span>
                            </a>
                        </li>
                            <li role="presentation">
                                <a href="#tab_payers" aria-controls="tab_payers" role="tab" data-toggle="tab">
                                    <?= _l('Payors'); ?>
                                </a>
                            </li>
                        <li role="presentation" data-toggle="tooltip"
                            title="<?= _l('emails_tracking'); ?>"
                            class="tab-separator">
                            <a href="#tab_emails_tracking" aria-controls="tab_emails_tracking" role="tab"
                                data-toggle="tab">
                                <?php if (! is_mobile()) { ?>
                                <i class="fa-regular fa-envelope-open" aria-hidden="true"></i>
                                <?php } else { ?>
                                <?= _l('emails_tracking'); ?>
                                <?php } ?>
                            </a>
                        </li>
                        <li role="presentation" data-toggle="tooltip"
                            title="<?= _l('view_tracking'); ?>"
                            class="tab-separator">
                            <a href="#tab_views" aria-controls="tab_views" role="tab" data-toggle="tab">
                                <?php if (! is_mobile()) { ?>
                                <i class="fa fa-eye"></i>
                                <?php } else { ?>
                                <?= _l('view_tracking'); ?>
                                <?php } ?>
                            </a>
                        </li>
                        <li role="presentation" data-toggle="tooltip"
                            data-title="<?= _l('toggle_full_view'); ?>"
                            class="tab-separator toggle_view">
                            <a href="#" onclick="small_table_full_view(); return false;">
                                <i class="fa fa-expand"></i></a>
                        </li>
                        <?php hooks()->do_action('after_admin_invoice_preview_template_tab_menu_last_item', $invoice); ?>
                    </ul>
                </div>
            </div>
            <div class="row mtop20">
                <div class="col-md-3">
                    <?= format_invoice_status($invoice->status, 'mtop5 inline-block'); ?>
                </div>
                <div class="col-md-9 _buttons">
                    <div class="visible-xs">
                        <div class="mtop10"></div>
                    </div>
                    <div class="pull-right">
                        <?php
                     $_tooltip = _l('invoice_sent_to_email_tooltip');
$_tooltip_already_send         = '';
if ($invoice->sent == 1 && is_date($invoice->datesend)) {
    $_tooltip_already_send = _l('invoice_already_send_to_client_tooltip', time_ago($invoice->datesend));
}
?>
                        <?php if (staff_can('edit', 'invoices')) { ?>
                        <a href="<?= admin_url('invoices/invoice/' . $invoice->id); ?>"
                            data-toggle="tooltip"
                            title="<?= _l('edit_invoice_tooltip'); ?>"
                            class="btn btn-default btn-with-tooltip sm:!tw-px-3" data-placement="bottom"><i
                                class="fa-regular fa-pen-to-square"></i></a>
                        <?php } ?>
                        <div class="btn-group">
                            <a href="#" class="btn btn-default dropdown-toggle" data-toggle="dropdown"
                                aria-haspopup="true" aria-expanded="false"><i class="fa-regular fa-file-pdf"></i><?php if (is_mobile()) {
                                    echo ' PDF';
                                } ?> <span class="caret"></span></a>
                            <ul class="dropdown-menu dropdown-menu-right">
                                <li class="hidden-xs"><a
                                        href="<?= admin_url('invoices/pdf/' . $invoice->id . '?output_type=I'); ?>"><?= _l('view_pdf'); ?></a>
                                </li>
                                <li class="hidden-xs"><a
                                        href="<?= admin_url('invoices/pdf/' . $invoice->id . '?output_type=I'); ?>"
                                        target="_blank"><?= _l('view_pdf_in_new_window'); ?></a>
                                </li>
                                <li><a
                                        href="<?= admin_url('invoices/pdf/' . $invoice->id); ?>"><?= _l('download'); ?></a>
                                </li>
                                <li>
                                    <a href="<?= admin_url('invoices/pdf/' . $invoice->id . '?print=true'); ?>"
                                        target="_blank">
                                        <?= _l('print'); ?>
                                    </a>
                                </li>
                            </ul>
                        </div>
                        <?php if (! empty($invoice->clientid)) { ?>
                        <span<?php if ($invoice->status == Invoices_model::STATUS_CANCELLED) { ?>
                            data-toggle="tooltip"
                            data-title="<?= _l('invoice_cancelled_email_disabled'); ?>"
                            <?php } ?>>
                            <a href="#" class="invoice-send-to-client btn-with-tooltip sm:!tw-px-3 btn btn-default<?php if ($invoice->status == Invoices_model::STATUS_CANCELLED) {
                                echo ' disabled';
                            } ?>" data-toggle="tooltip"
                                title="<?= e($_tooltip); ?>"
                                data-placement="bottom"><span data-toggle="tooltip"
                                    data-title="<?= e($_tooltip_already_send); ?>"><i
                                        class="fa-regular fa-envelope"></i></span></a>
                            </span>
                            <?php } ?>
                            <?php hooks()->do_action('before_invoice_preview_more_menu_button', $invoice); ?>
                            <!-- Single button -->
                            <div class="btn-group">
                                <button type="button" class="btn btn-default pull-left dropdown-toggle"
                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <?= _l('more'); ?>
                                    <span class="caret"></span>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-right">
                                    <li>
                                        <a href="<?= site_url('invoice/' . $invoice->id . '/' . $invoice->hash) ?>"
                                            target="_blank"><?= _l('view_invoice_as_customer_tooltip'); ?>
                                        </a>
                                    </li>
                                    <?php hooks()->do_action('after_invoice_view_as_client_link', $invoice); ?>
                                    <li>
                                        <?php if (is_invoice_overdue($invoice) && is_invoices_overdue_reminders_enabled()) { ?>
                                        <a
                                            href="<?= admin_url('invoices/send_overdue_notice/' . $invoice->id); ?>">
                                            <?= _l('send_overdue_notice_tooltip'); ?>
                                        </a>
                                        <?php } ?>
                                    </li>
                                    <?php if ($invoice->status != Invoices_model::STATUS_CANCELLED
                                  && staff_can('create', 'credit_notes')
                                  && ! empty($invoice->clientid)) {?>
                                    <li>
                                        <a href="<?= admin_url('credit_notes/credit_note_from_invoice/' . $invoice->id); ?>"
                                            id="invoice_create_credit_note"
                                            data-status="<?= e($invoice->status); ?>"><?= _l('create_credit_note'); ?></a>
                                    </li>
                                    <?php } ?>
                                    <li>
                                        <a href="#" data-toggle="modal"
                                            data-target="#sales_attach_file"><?= _l('invoice_attach_file'); ?></a>
                                    </li>
                                    <?php if (staff_can('create', 'invoices')) { ?>
                                    <li>
                                        <a
                                            href="<?= admin_url('invoices/copy/' . $invoice->id); ?>"><?= _l('invoice_copy'); ?></a>
                                    </li>
                                    <?php } ?>
                                    <?php if ($invoice->sent == 0) { ?>
                                    <li>
                                        <a
                                            href="<?= admin_url('invoices/mark_as_sent/' . $invoice->id); ?>"><?= _l('invoice_mark_as_sent'); ?></a>
                                    </li>
                                    <?php } ?>
                                    <?php if (staff_can('edit', 'invoices') || staff_can('create', 'invoices')) { ?>
                                    <li>
                                        <?php if ($invoice->status != Invoices_model::STATUS_CANCELLED
                                     && $invoice->status != Invoices_model::STATUS_PAID
                                     && $invoice->status != Invoices_model::STATUS_PARTIALLY) { ?>
                                        <a
                                            href="<?= admin_url('invoices/mark_as_cancelled/' . $invoice->id); ?>"><?= e(_l('invoice_mark_as', _l('invoice_status_cancelled'))); ?></a>
                                        <?php } elseif ($invoice->status == Invoices_model::STATUS_CANCELLED) { ?>
                                        <a
                                            href="<?= admin_url('invoices/unmark_as_cancelled/' . $invoice->id); ?>"><?= e(_l('invoice_unmark_as', _l('invoice_status_cancelled'))); ?></a>
                                        <?php } ?>
                                    </li>
                                    <?php } ?>
                                    <?php if (! in_array($invoice->status, [Invoices_model::STATUS_PAID, Invoices_model::STATUS_CANCELLED, Invoices_model::STATUS_DRAFT])
                                  && staff_can('edit', 'invoices')
                                  && $invoice->duedate
                                  && is_invoices_overdue_reminders_enabled()) { ?>
                                    <li>
                                        <?php if ($invoice->cancel_overdue_reminders == 1) { ?>
                                        <a
                                            href="<?= admin_url('invoices/resume_overdue_reminders/' . $invoice->id); ?>"><?= _l('resume_overdue_reminders'); ?></a>
                                        <?php } else { ?>
                                        <a
                                            href="<?= admin_url('invoices/pause_overdue_reminders/' . $invoice->id); ?>"><?= _l('pause_overdue_reminders'); ?></a>
                                        <?php } ?>
                                    </li>
                                    <?php } ?>
                                    <?php
                                  if ((get_option('delete_only_on_last_invoice') == 1 && is_last_invoice($invoice->id)) || (get_option('delete_only_on_last_invoice') == 0)) { ?>
                                    <?php if (staff_can('delete', 'invoices')) { ?>
                                    <li data-toggle="tooltip"
                                        data-title="<?= _l('delete_invoice_tooltip'); ?>">
                                        <a href="<?= admin_url('invoices/delete/' . $invoice->id); ?>"
                                            class="text-danger delete-text _delete"><?= _l('delete_invoice'); ?></a>
                                    </li>
                                    <?php } ?>
                                    <?php } ?>
                                    <?php hooks()->do_action('after_invoice_preview_more_menu'); ?>
                                </ul>
                            </div>
                            <?php if (staff_can('create', 'payments') && abs($invoice->total) > 0) { ?>
                            <a href="#"
                                onclick="record_payment(<?= e($invoice->id); ?>); return false;"
                                class="mleft10 pull-right btn btn-success<?php if ($invoice->status == Invoices_model::STATUS_PAID || $invoice->status == Invoices_model::STATUS_CANCELLED) {
                                    echo ' disabled';
                                } ?>">
                                <i class="fa fa-plus-square"></i>
                                <?= _l('payment'); ?></a>
                            <?php } ?>
                    </div>
                </div>
                <?php
                  if (is_invoice_overdue($invoice)) { ?>
                <div class="col-md-12">
                    <p class="text-danger tw-mt-2.5 tw-mb-0">
                        <?= e(_l('invoice_is_overdue', get_total_days_overdue($invoice->duedate))); ?>
                    </p>
                </div>
                <?php } ?>
            </div>
            <div class="clearfix"></div>
            <hr class="hr-panel-separator" />
            <div class="tab-content">
                <div role="tabpanel" class="tab-pane active" id="tab_invoice">
                    <?php if ($invoice->status == Invoices_model::STATUS_CANCELLED && $invoice->recurring > 0) { ?>
                    <div class="alert alert-info">
                        Recurring invoice with status Cancelled <b>is still ongoing recurring invoice</b>. If you want
                        to stop this recurring invoice you should update the invoice recurring field to <b>No</b>.
                    </div>
                    <?php } ?>
                    <?php $this->load->view('admin/invoices/invoice_preview_html'); ?>
                </div>
                <?php if (count($invoice->payments) > 0) { ?>
                <div class="tab-pane" role="tabpanel" id="invoice_payments_received">
                    <?php include_once APPPATH . 'views/admin/invoices/invoice_payments_table.php'; ?>
                </div>
                <?php } ?>
                <?php if (count($applied_credits) > 0) { ?>
                <div class="tab-pane" role="tabpanel" id="invoice_applied_credits">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <th><span
                                        class="bold"><?= _l('credit_note'); ?>
                                        #</span></th>
                                <th><span
                                        class="bold"><?= _l('credit_date'); ?></span>
                                </th>
                                <th><span
                                        class="bold"><?= _l('credit_amount'); ?></span>
                                </th>
                            </thead>
                            <tbody>
                                <?php foreach ($applied_credits as $credit) { ?>
                                <tr>
                                    <td>
                                        <a
                                            href="<?= admin_url('credit_notes/list_credit_notes/' . $credit['credit_id']); ?>"><?= e(format_credit_note_number($credit['credit_id'])); ?></a>
                                    </td>
                                    <td><?= e(_d($credit['date'])); ?>
                                    </td>
                                    <td><?= app_format_money($credit['amount'], $invoice->currency_name) ?>
                                        <?php if (staff_can('delete', 'credit_notes')) { ?>
                                        <a href="<?= admin_url('credit_notes/delete_invoice_applied_credit/' . $credit['id'] . '/' . $credit['credit_id'] . '/' . $invoice->id); ?>"
                                            class="pull-right text-danger _delete"><i class="fa fa-trash"></i></a>
                                        <?php } ?>
                                    </td>
                                </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <?php } ?>
                <div role="tabpanel" class="tab-pane" id="tab_tasks">
                    <?php init_relation_tasks_table(['data-new-rel-id' => $invoice->id, 'data-new-rel-type' => 'invoice'], 'tasksFilters'); ?>
                </div>
                <div role="tabpanel" class="tab-pane" id="tab_reminders">
                    <a href="#" class="btn btn-primary" data-toggle="modal"
                        data-target=".reminder-modal-invoice-<?= e($invoice->id); ?>"><i
                            class="fa-regular fa-bell"></i>
                        <?= _l('invoice_set_reminder_title'); ?></a>
                    <hr />
                    <?php render_datatable([_l('reminder_description'), _l('reminder_date'), _l('reminder_staff'), _l('reminder_is_notified')], 'reminders'); ?>
                    <?php $this->load->view('admin/includes/modals/reminder', ['id' => $invoice->id, 'name' => 'invoice', 'members' => $members, 'reminder_title' => _l('invoice_set_reminder_title')]); ?>
                </div>
                <?php if (count($invoice_recurring_invoices) > 0 || $invoice->recurring != 0) { ?>
                <div role="tabpanel" class="tab-pane" id="tab_child_invoices">
                    <?php if (count($invoice_recurring_invoices)) { ?>
                    <p class="tw-text-lg tw-font-medium">
                        <?= _l('invoice_add_edit_recurring_invoices_from_invoice'); ?>
                    </p>
                    <ul class="list-group">
                        <?php foreach ($invoice_recurring_invoices as $recurring) { ?>
                        <li class="list-group-item">
                            <a href="<?= admin_url('invoices/list_invoices/' . $recurring->id); ?>"
                                class="tw-font-semibold"
                                onclick="init_invoice(<?= e($recurring->id); ?>); return false;"
                                target="_blank"><?= e(format_invoice_number($recurring->id)); ?>
                                <span
                                    class="pull-right bold"><?= e(app_format_money($recurring->total, $recurring->currency_name)); ?></span>
                            </a>
                            <br />
                            <span class="inline-block tw-mt-1">
                                <?= '<span class="bold">' . e(_d($recurring->date)) . '</span>'; ?><br />
                                <?= format_invoice_status($recurring->status, '', false); ?>
                            </span>
                        </li>
                        <?php } ?>
                    </ul>
                    <?php } else { ?>
                    <p class="bold">
                        <?= e(_l('no_child_found', _l('invoices'))); ?>
                    </p>
                    <?php } ?>
                </div>
                <?php } ?>
                <div role="tabpanel" class="tab-pane ptop10" id="tab_emails_tracking">
                    <?php $this->load->view(
                        'admin/includes/emails_tracking',
                        [
                            'tracked_emails' => get_tracked_emails($invoice->id, 'invoice'), ]
                    ); ?>
                </div>
                <div role="tabpanel" class="tab-pane" id="tab_notes">
                    <?= form_open(admin_url('invoices/add_note/' . $invoice->id), ['id' => 'sales-notes', 'class' => 'invoice-notes-form']); ?>
                    <?= render_textarea('description'); ?>
                    <div class="text-right">
                        <button type="submit"
                            class="btn btn-primary mtop15 mbot15"><?= _l('estimate_add_note'); ?></button>
                    </div>
                    <?= form_close(); ?>
                    <hr />
                    <div class="mtop20" id="sales_notes_area"></div>
                </div>
      <div role="tabpanel" class="tab-pane" id="tab_payers">
         <div class="panel_s">
          <div class="panel-body">
            <div class="row">
              <div class="col-md-12">
                <h4 class="no-margin section-text"><?=_l('Payors'); ?>
                <button class="btn btn-primary pull-right" type="button" id="carwash_add"><?=_l('carwash_add_new'); ?></button>
                <a class="send-payer-emails-btn pull-right btn btn-primary mright10" href="#" id="send-payer-emails-btn">Send Emails</a>
             </h4>
          </div>
       </div>
       <div class="row mtop15">
         <div class="col-md-12">
           <?php 
           $table_data = [
             _l('id'),
             _l('Client'),
             _l('Created On'),

          ]; 
           array_push($table_data,_l('actions'));
           render_datatable($table_data,'carwash_payers')
           ?>
        </div>
     </div>
  </div>
</div>
</div>

                <div role="tabpanel" class="tab-pane ptop10" id="tab_activity">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="activity-feed">
                                <?php foreach ($activity as $activity) {
                                    $_custom_data = false; ?>
                                <div class="feed-item"
                                    data-sale-activity-id="<?= e($activity['id']); ?>">
                                    <div class="date">
                                        <span class="text-has-action" data-toggle="tooltip"
                                            data-title="<?= e(_dt($activity['date'])); ?>">
                                            <?= e(time_ago($activity['date'])); ?>
                                        </span>
                                    </div>
                                    <div class="text">
                                        <?php if (is_numeric($activity['staffid']) && $activity['staffid'] != 0) { ?>
                                        <a
                                            href="<?= admin_url('profile/' . $activity['staffid']); ?>">
                                            <?= staff_profile_image($activity['staffid'], ['staff-profile-xs-image pull-left mright5']);
                                            ?>
                                        </a>
                                        <?php } ?>
                                        <?php
                                            $additional_data = '';
                                    if (! empty($activity['additional_data']) && $additional_data = unserialize($activity['additional_data'])) {
                                        $i = 0;

                                        foreach ($additional_data as $data) {
                                            if (strpos($data, '<original_status>') !== false) {
                                                $original_status     = get_string_between($data, '<original_status>', '</original_status>');
                                                $additional_data[$i] = format_invoice_status($original_status, '', false);
                                            } elseif (strpos($data, '<new_status>') !== false) {
                                                $new_status          = get_string_between($data, '<new_status>', '</new_status>');
                                                $additional_data[$i] = format_invoice_status($new_status, '', false);
                                            } elseif (strpos($data, '<custom_data>') !== false) {
                                                $_custom_data = get_string_between($data, '<custom_data>', '</custom_data>');
                                                unset($additional_data[$i]);
                                            }
                                            $i++;
                                        }
                                    }

                                    $_formatted_activity = _l($activity['description'], $additional_data);

                                    if ($_custom_data !== false) {
                                        $_formatted_activity .= ' - ' . $_custom_data;
                                    }

                                    if (! empty($activity['full_name'])) {
                                        $_formatted_activity = e($activity['full_name']) . ' - ' . $_formatted_activity;
                                    }

                                    echo $_formatted_activity;

                                    if (is_admin()) {
                                        echo '<a href="#" class="pull-right text-muted" onclick="delete_sale_activity(' . $activity['id'] . '); return false;"><i class="fa-regular fa-trash-can"></i></a>';
                                    } ?>
                                    </div>
                                </div>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div role="tabpanel" class="tab-pane ptop10" id="tab_views">
                    <?php
                  $views_activity = get_views_tracking('invoice', $invoice->id);
if (count($views_activity) === 0) {
    echo '<h4 class="tw-m-0 tw-text-base tw-font-medium tw-text-neutral-500">' . _l('not_viewed_yet', _l('invoice_lowercase')) . '</h4>';
}

foreach ($views_activity as $activity) { ?>
                    <p class="text-success no-margin">
                        <?= _l('view_date') . ': ' . _dt($activity['date']); ?>
                    </p>
                    <p class="text-muted">
                        <?= _l('view_ip') . ': ' . $activity['view_ip']; ?>
                    </p>
                    <hr />
                    <?php } ?>
                </div>

                <?php hooks()->do_action('after_admin_invoice_preview_template_tab_content_last_item', $invoice); ?>
            </div>
        </div>
    </div>
</div>
<!--  carwash add payer modal -->
<div class="modal fade" id="carwash_payer_modal" role="dialog">
   <div class="modal-dialog modal-lg">
      <!-- Modal content-->
      <?=form_open(site_url('carwash/carwash/create_payer'),['id'=>'carwash_payer_form','autocomplete'=>'off']); ?>
      <div class="modal-content">
         <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title" id="carwash_payer_header"><?="Payor Details"; ?></h4>
         </div>
         <div class="modal-body">
            <div class="row">
               <input type="hidden" name="invoice_id" value="<?=$invoice->id ?>">
               <input type="hidden" name="payer_id" value="">
               <div class="col-md-6">
					<div class="f_client_id">
						<div class="form-group select-placeholder carwash-payer-client">
							<label for="clientid" class="control-label"><?=_l('invoice_select_customer'); ?></label>
							<select id="clientid" name="client_id" data-live-search="true" data-width="100%" class="ajax-search" data-none-selected-text="<?=_l('dropdown_non_selected_tex'); ?>">
							</select>
						</div>
					</div>
				</div>
               <div class="col-md-6">
                  <?=render_input( 'policy_number', 'Policy Number'); ?>
               </div>
               <div class="col-md-6">
                  <?=render_input( 'authorization_number', 'Authorization Number'); ?>
               </div>
               <div class="col-md-6">
                  <?=render_date_input( 'date_of_loss', 'Date of Loss'); ?>
               </div>
               <div class="col-md-6">
                  <?=render_input( 'purchase', 'Purchase Number'); ?>
               </div>
               <div class="col-md-6">
                  <?=render_input( 'order_number', 'Order Number'); ?>
               </div>
               <div class="col-md-6">
                  <?=render_input( 'fleet_or_unit', 'Fleet or Unit #'); ?>
               </div>
               	<div class="col-md-6">
					<?=render_input( 'amount', 'Amount', '', 'number'); ?>
				</div>
            </div>
         </div>
         <div class="modal-footer">
            <a id="save_carwash_vehicle" href="#" class="btn btn-primary"><?=_l('save'); ?></a>
            <button type="button" class="btn btn-default" data-dismiss="modal"><?=_l('cancel'); ?></button>
         </div>
      </div>
      <?=form_close(); ?>
   </div>
</div>
<!--  carwash add payer modal -->


<!--  carwash send emails modal -->
<?php 

$customer_email = $this->db->select('email')->where('userid', $invoice->client->userid)->get('tblcontacts')->row('email'); 
?>
<div class="modal fade" id="carwash_payer_emails_modal" role="dialog">
   <div class="modal-dialog modal-lg">
      <!-- Modal content-->
      <?=form_open_multipart(site_url('carwash/carwash/send_invoice_to_client_payers'),['id'=>'carwash_send_payer_emails_form','autocomplete'=>'off']); ?>
      <div class="modal-content">
         <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title" id=""><?="Payor Emails"; ?></h4>
         </div>
         <div class="modal-body">
            <div class="row">
               <input type="hidden" name="invoice_id" value="<?=$invoice->id ?>">
               <div class="col-md-12">
                  <?=render_input('carwash_customer_email', 'Customer Email', $customer_email); ?>
               </div>
               <div class="col-md-12">
                  <?=render_textarea('carwash_payer_emails', 'Payors Emails List', ''); ?>
               </div>
               <div class="col-md-12">
					<?=render_input( 'file', 'Attach File', '', 'file'); ?>
				</div>
            </div>
            <div class="row">
               <div class="col-md-12">
                  <div class="email-msg alert alert-info text-center" style="display: none;">Sending email... please wait</div>
               </div>
            </div>
         </div>
         <div class="modal-footer">
            <button id="send_payers_emails" type="button" class="btn btn-primary"><?=_l('save'); ?></button>
            <button type="button" class="btn btn-default" data-dismiss="modal"><?=_l('cancel'); ?></button>
         </div>
      </div>
      <?=form_close(); ?>
   </div>
</div>
<!--  carwash send emails modal -->

<?php $this->load->view('admin/invoices/invoice_send_to_client'); ?>
<?php $this->load->view('admin/credit_notes/apply_invoice_credits'); ?>
<?php $this->load->view('admin/credit_notes/invoice_create_credit_note_confirm'); ?>
<script>
    init_items_sortable(true);
    init_btn_with_tooltips();
    init_datepicker();
    init_selectpicker();
    init_form_reminder();
    init_tabs_scrollable();
    <?php if ($record_payment) { ?>
    record_payment( <?= e($invoice->id); ?> );
    <?php } elseif ($send_later) { ?>
    schedule_invoice_send( <?= e($invoice->id); ?> );
    <?php } ?>
</script>
<?php hooks()->do_action('after_invoice_preview_template_rendered', $invoice); ?>

<script type="text/javascript">
   $(document).ready(function(){
      initDataTable('.table-carwash_payers', admin_url + 'carwash/payers_index?invoice_id='+<?=$invoice->id?>,[], [0], '', ['0','DESC']);

      $('body').on('click','#add-payer-btn, #carwash_add',function(){
         var form = $('#carwash_payer_form')[0];
         form.reset();

    //     $('#carwash_payer_form .carwash-payer-client').find('select').val(``);
	   // $('#carwash_payer_form').find('select').selectpicker('refresh');
	   
	   $('#carwash_payer_form').find('select').selectpicker('destroy');
		$('#carwash_payer_form .carwash-payer-client').html(`<label for="clientid" class="control-label"><?=_l('invoice_select_customer'); ?></label>
			<select id="clientid" name="client_id" data-live-search="true" data-width="100%" class="ajax-search" data-none-selected-text="<?=_l('dropdown_non_selected_tex'); ?>">
			</select>`);
		init_ajax_search('customer','#carwash_payer_form #clientid.ajax-search');


         $('#carwash_payer_form input[name="payer_id"]').val('');
         $('#carwash_payer_modal').modal('show');
      });

      $('#save_carwash_vehicle').on('click',function(){
         var formdata = new FormData($('#carwash_payer_form')[0]);
         var action =  $('#carwash_payer_form').attr('action');
         $.ajax(action, {
            type: 'POST',
            data: formdata,
            cache:false,
            contentType: false,
            processData: false,

         }).done(function(data){
            data = JSON.parse(data);
            alert_float(data.status,data.message);
            $(".table-carwash_payers").dataTable().fnDestroy();
            initDataTable('.table-carwash_payers', admin_url + 'carwash/payers_index?invoice_id='+<?=$invoice->id?>,[], [0], '', ['0','DESC']);
            $('#carwash_payer_modal').modal('hide');
         });
         return false;
      });
      


   });
   function carwash_edit_payer(invoice_id){
      var id = invoice_id;
      $('#carwash_payer_form input[name="payer_id"]').val(id);
      var action =  '<?=site_url('carwash/get_payer_data'); ?>';
      $.ajax(action, {
         type: 'POST',
         data: {id : id}
      }).done(function(data){
         data = JSON.parse(data);
         
        $('#carwash_payer_form').find('select').selectpicker('destroy');
		$('#carwash_payer_form .carwash-payer-client').html(`<label for="clientid" class="control-label"><?=_l('invoice_select_customer'); ?></label>
			<select id="clientid" name="client_id" data-live-search="true" data-width="100%" class="ajax-search" data-none-selected-text="<?=_l('dropdown_non_selected_tex'); ?>">
			<option value="${data.client_id}" selected="selected">${data.client_name}</option>
			</select>`);
		init_ajax_search('customer','#clientid.ajax-search');

         $('#carwash_payer_form input[name="policy_number"]').val(data.policy_number);
         $('#carwash_payer_form input[name="authorization_number"]').val(data.authorization_number);
         if(data.date_of_loss === '0000-00-00')
		    data.date_of_loss = '';
         $('#carwash_payer_form input[name="date_of_loss"]').val(data.date_of_loss);
         $('#carwash_payer_form input[name="purchase"]').val(data.purchase);
         $('#carwash_payer_form input[name="order_number"]').val(data.order_number);
         $('#carwash_payer_form input[name="fleet_or_unit"]').val(data.fleet_or_unit);
		 $('#carwash_payer_form input[name="amount"]').val(data.amount);
         $('#carwash_payer_modal').modal('show');
      });
   };

   function delete_carwash_payer(invoice_id){
      if(confirm_delete()){
         var id = invoice_id;
         var action =  '<?=site_url('carwash/delete_payer'); ?>';
         $.ajax(action, {
            type: 'POST',
            data: {id : id}

         }).done(function(data){
            data = JSON.parse(data);
            alert_float(data.status,data.message);
            $(".table-carwash_payers").dataTable().fnDestroy();
            initDataTable('.table-carwash_payers', admin_url + 'carwash/payers_index?invoice_id='+<?=$invoice->id?>,[], [0], '', ['0','DESC']);
         });
      }
   };




</script>
<script type="text/javascript">
   $('body').on('click','#send-payer-emails-btn',function(){
      $('#carwash_payer_emails_modal').modal('show');
   });

   $('body').on('click','#send_payers_emails',function(){
      $('.email-msg').show();
      var formdata = new FormData($('#carwash_send_payer_emails_form')[0]);
      var action =  $('#carwash_send_payer_emails_form').attr('action');
      $.ajax(action, {
         type: 'POST',
         data: formdata,
         cache:false,
         contentType: false,
         processData: false,

      }).done(function(data){
         $('.email-msg').hide();
         alert_float('success','Emails sent successfully');
         $('#carwash_payer_emails_modal').modal('hide');
      });
   });

</script>
