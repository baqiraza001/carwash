<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php  
$groups = $this->clients_model->get_groups();
$this->load->model('currencies_model');
$currencies = $this->currencies_model->get();
?>
<div class="modal fade" id="customer_admins_assign" tabindex="-1" role="dialog">
   <div class="modal-dialog modal-lg">
      <?=form_open(admin_url('clients/client'),['id'=>'add_client_form', 'class'=>'client-form','autocomplete'=>'off']); ?>
      <div class="modal-content">
         <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title"><?=_l('client_add_edit_profile'); ?></h4>
         </div>
         <div class="modal-body">
          <div class="col-md-12">
            <div class="tab-content mtop15">
               <?php hooks()->do_action('after_custom_profile_tab_content',isset($client) ? $client : false); ?>
               <div role="tabpanel" class="tab-pane<?php if(!$this->input->get('tab')){echo ' active';}; ?>" id="contact_info">
                  <div class="row">
                     <div class="col-md-12 mtop15 <?php if(isset($client) && (!is_empty_customer_company($client->userid) && total_rows(db_prefix().'contacts',array('userid'=>$client->userid,'is_primary'=>1)) > 0)) { echo ''; } else {echo ' hide';} ?>" id="client-show-primary-contact-wrapper">
                        <div class="checkbox checkbox-info mbot20 no-mtop">
                           <input type="checkbox" name="show_primary_contact"<?php if(isset($client) && $client->show_primary_contact == 1){echo ' checked';}?> value="1" id="show_primary_contact">
                           <label for="show_primary_contact"><?=_l('show_primary_contact',_l('invoices').', '._l('estimates').', '._l('payments').', '._l('credit_notes')); ?></label>
                        </div>
                     </div>
                     <div class="col-md-12 mtop15">
                      <?=render_custom_fields( 'customers'); ?>
                      </div>
                     <div class="col-md-6">
                        <?=render_input( 'company', 'client_company','','text'); ?>
                        <div id="company_exists_info" class="hide"></div>
                        
                        <?php if(get_option('company_requires_vat_number_field') == 1){
                           $value=( isset($client) ? $client->vat : '');
                           echo render_input( 'vat', 'client_vat_number',$value);
                        } ?>
                        <?php $value=( isset($client) ? $client->phonenumber : ''); ?>
                        <?=render_input( 'phonenumber', 'client_phonenumber',$value); ?>
                        <?php if((isset($client) && empty($client->website)) || !isset($client)){
                           $value=( isset($client) ? $client->website : '');
                           echo render_input( 'website', 'client_website',$value);
                        } else { ?>
                           <div class="form-group">
                              <label for="website"><?=_l('client_website'); ?></label>
                              <div class="input-group">
                                 <input type="text" name="website" id="website" value="<?=$client->website; ?>" class="form-control">
                                 <div class="input-group-addon">
                                    <span><a href="<?=maybe_add_http($client->website); ?>" target="_blank" tabindex="-1"><i class="fa fa-globe"></i></a></span>
                                 </div>
                              </div>
                           </div>
                        <?php }
                        $selected = [];
                        if(isset($customer_groups)){
                         foreach($customer_groups as $group){
                            array_push($selected,$group['groupid']);
                         }
                      }
                      if(is_admin() || get_option('staff_members_create_inline_customer_groups') == '1'){
                       echo render_select_with_input_group('groups_in[]',$groups,['id','name'],'customer_groups',$selected,'<a href="#" data-toggle="modal" data-target="#customer_group_modal"><i class="fa fa-plus"></i></a>',['multiple'=>true,'data-actions-box'=>true],[],'','',false);
                    } else {
                     echo render_select('groups_in[]',$groups,['id','name'],'customer_groups',$selected,['multiple'=>true,'data-actions-box'=>true],[],'','',false);
                  }
                  ?>
                  <?php if(!isset($client)){ ?>
                     <i class="fa-regular fa-circle-question pull-left" data-toggle="tooltip" data-title="<?=_l('customer_currency_change_notice'); ?>"></i>
                  <?php }
                  $s_attrs = ['data-none-selected-text'=>_l('system_default_string')];
                  $selected = '';
                  if(isset($client) && client_have_transactions($client->userid)){
                     $s_attrs['disabled'] = true;
                  }
                  foreach($currencies as $currency){
                     if(isset($client)){
                      if($currency['id'] == $client->default_currency){
                       $selected = $currency['id'];
                    }
                 }
              }
              // Do not remove the currency field from the customer profile!
              echo render_select('default_currency',$currencies,['id','name','symbol'],'invoice_add_edit_currency',$selected,$s_attrs); ?>
              <?php if(!is_language_disabled()){ ?>
               <div class="form-group select-placeholder">
                  <label for="default_language" class="control-label"><?=_l('localization_default_language'); ?>
               </label>
               <select name="default_language" id="default_language" class="form-control selectpicker" data-none-selected-text="<?=_l('dropdown_non_selected_tex'); ?>">
                  <option value=""><?=_l('system_default_string'); ?></option>
                  <?php foreach($this->app->get_available_languages() as $availableLanguage){
                     $selected = '';
                     if(isset($client)){
                        if($client->default_language == $availableLanguage){
                           $selected = 'selected';
                        }
                     }
                     ?>
                     <option value="<?=$availableLanguage; ?>" <?=$selected; ?>><?=ucfirst($availableLanguage); ?></option>
                  <?php } ?>
               </select>
            </div>
         <?php } ?>
      </div>
      <div class="col-md-6">
         <?php $value=( isset($client) ? $client->address : ''); ?>
         <?php $countries= get_all_countries();
         $customer_default_country = get_option('customer_default_country');
         $selected =( isset($client) ? $client->country : $customer_default_country);
         echo render_select( 'country',$countries,[ 'country_id',[ 'short_name']], 'clients_country',$selected,['data-none-selected-text'=>_l('dropdown_non_selected_tex')]);
         ?>
         <?php $value=( isset($client) ? $client->city : ''); ?>
         <?=render_input( 'city', 'client_city',$value); ?>
         <?php $value=( isset($client) ? $client->state : ''); ?>
         <?=render_input( 'state', 'client_state',$value); ?>
         <?php $value=( isset($client) ? $client->zip : ''); ?>
         <?=render_input( 'zip', 'client_postal_code',$value); ?>
         <?php $value=( isset($client) ? $client->pst : ''); ?>
         <?=render_input('pst', 'PST#',$value); ?>
      </div>
      <div class="col-md-12">
         <?=render_textarea( 'address', 'client_address',$value); ?>
      </div>
      
   </div>
   <hr/>
   <div class="row">
       <div class="col-md-12">
           <h4><?=_l('carwash_vehicle'); ?></h4>
       </div>
   </div>
   <div class="row">
       <div class="col-md-6">
           <?=render_input( 'vehicle', 'carwash_vehicle'); ?>
       </div>
       <div class="col-md-6">
           <?=render_input( 'vin', 'carwash_vin'); ?>
       </div>
   </div>
</div>
</div>
</div>
</div>
<div class="modal-footer">
   <button type="button" class="btn btn-default" data-dismiss="modal"><?=_l('close'); ?></button>
   <button type="button" id="add-client-btn" class="btn btn-info"><?=_l('submit'); ?></button>
</div>
</div>
<!-- /.modal-content -->
<?=form_close(); ?>
</div>
<!-- /.modal-dialog -->
</div>
<!-- /.modal -->
