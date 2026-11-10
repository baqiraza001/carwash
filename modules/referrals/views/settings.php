<?php defined('BASEPATH') or exit('No direct script access allowed'); 

$referrals_commission_percentage = get_option('referrals_commission_percentage');

?>

<div class="row">
    <div class="col-md-12">
        <?php echo render_input('settings[referrals_commission_percentage]',_l('referrals_commission_percentage'),$referrals_commission_percentage,'number'); ?>
                     
                        
    </div>
</div>


