<?php  
    $list_id_error = "";
    $list_id = get_option('mailtrap_sync_list_id');
    $account_id = get_option('mailtrap_sync_account_id');
    $api_key = get_option('mailtrap_sync_api_key');
    if( (!empty($account_id) && !empty($api_key)) && empty($list_id))
    {
        $lists = mailtrap_sync_get_contact_lists();

        if (isset($lists['error'])) {
            $list_id_error = "Error Getting List ID: " . $lists['message'] . " (HTTP Status: " . $lists['status'] . ")";
        } else {
            if(!empty($lists)){
                $list_id = $lists[0]['id'];
                update_option('mailtrap_sync_list_id', $list_id);
            }
        }
    }
?>

<style>
    .error{
        color:red!important;
    }
</style>

<?php if ($this->session->flashdata('mailtrap_sync_error')): ?>
    <?php $error = $this->session->flashdata('mailtrap_sync_error'); ?>
    <div class="alert alert-danger tw-mt-3">
        <strong>Error!</strong> <?= isset($error['message']) ? $error['message'] : 'An error occurred during the sync process.'; ?>
    </div>
<?php endif; ?>

<?php if ($list_id_error): ?>
    <div class="alert alert-danger tw-mt-3">
        <strong>Error!</strong> <?= $list_id_error; ?>
    </div>
<?php endif; ?>

<div class="row">
    <div class="col-md-12">
        <div class="row">
            <input type="hidden" name="mailtrap_sync_module" value="1">
            <div class="col-md-12">
                <?php
                $value = get_option('mailtrap_sync_account_id');
                echo render_input('mailtrap_sync_account_id',_l('Mailtrap Account ID'),$value);
                ?>
            </div>
            <div class="col-md-12">
                <?php
                $value = get_option('mailtrap_sync_api_key');
                echo render_input('mailtrap_sync_api_key',_l('Mailtrap API Key'),$value);
                ?>
            </div>
            <div class="col-md-12">
                <?php
                echo render_input('mailtrap_sync_list_id',_l('Mailtrap List ID'),$list_id);
                ?>
            </div>
        </div>

    </div>
</div>


<!-- <hr /> -->
<div class="row hide" style="margin-top: 50px;">
    <div class="col-md-12">
     <div class="col-md-6">
        <a class="btn btn-success btn-block" 
        onclick="syncData(this, '<?php echo admin_url('mailtrap_sync/bulk_sync_contacts') ?>', 'Sync Contacts With Mailtrap'); return false;" 
        href="#">Sync Contacts With Mailtrap</a>
        <!-- <div class="alert alert-info tw-mt-4">Last Sync At: <?php echo date('d F, Y H:i:a', strtotime(get_option('mailtrap_sync_at_datetime'))) ?></div> -->
    </div>
</div>
</div>

