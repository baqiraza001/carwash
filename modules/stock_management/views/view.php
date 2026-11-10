<?php init_head(); ?>

<style>
    .error{
        color:red!important;
    }
	label{
		font-weight: 500!important;
	}
</style>    
<div id="wrapper" class="contact_details">
<div class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="panel_s">
                <?php
                    $headingTextTop = 'SIM A';
                ?>
                <div class="panel-body">
                    <h4 class="list-title border-right" style="border-right: 1px solid #dee2e6 !important;border-right-color: rgb(222, 226, 230);margin-top: 2px;margin-bottom: 0px;margin-right: 1rem !important;padding-right: 1rem !important;display: inline-block;float: left;">Sim A</h4>
                        <nav aria-label="breadcrumb" class="d-inline-block pull-left" style="background-color: transparent !important;margin-bottom: 0px;">
                        <ol class="breadcrumb p0" style="background-color: transparent !important;margin-bottom: 0px;padding: 0px;margin-top: 3px;">
                            <li class="breadcrumb-item"><a href="<?= admin_url();?>">Dashboard</a></li>

                                <li class="breadcrumb-item"><a href="<?= admin_url('sim_a')?>"><?= $headingTextTop;?></a></li>

                            <li class="breadcrumb-item active" aria-current="page">View <?= $headingTextTop; ?></li>

                        </ol>

                    </nav>
					
					<?php if( isset($id) && staff_can('edit', 'sim_a') ){?>
					<div class="pull-right"><a href="<?= admin_url('sim_a/create/'.$id);?>" class="btn btn-info btn-xs">Edit</a></div>
					<?php }?>
                    <div class="clearfix"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <?= form_open($this->uri->uri_string(), ['class' => '_sim_a_form', 'autocomplete' => 'off', 'name'=>'sim_a_form']); ?>
        <div class="col-md-12">
            <div class="panel_s">
                <div class="panel-body">
                    <div class="clearfix"></div>
                    <div>
                        <div class="tab-content">
                            <h4 class="right-side-group-heading"><?= 'Details';?></h4>
                            <div class="row hide">
                                <div class="col-md-6">
                                <?php
                                $value=isset($pixelperfect_id) ? $pixelperfect_id : '';
                                echo render_input('pixelperfect_id','ID',$value,'',['disabled'=>'disabled']);
                                if(isset($id) && $id > 0) {echo form_hidden('id', $id); }
                                if(isset($you_do_id)) {echo form_hidden('pixelperfect_id', $you_do_id ); }
                                ?>
                                </div>
                            </div>
                            <br/>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <?php $value = isset($iccid) ? $iccid : ''; ?>
                                        <label class="control-label">ICCID</label>
                                        <div class="control-label"><?= $value;?></div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <?php $value = isset($msisdn) ? $msisdn : ''; ?>
                                        <label class="control-label">MSISDN</label>
                                        <div class="control-label"><?= $value;?></div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
								<div class="col-md-6">
									<div class="form-group">
										<?php $value = isset($customer_name) ? $customer_name['firstname'] .' '.$customer_name['lastname'] : ''; ?>
										<label class="control-label">Customer</label>
										<div class="control-label"><?= $value;?></div>
									</div>
								</div>
								
								<div class="col-md-6">
									<div class="form-group">
										<?php 
											$value = "";
											foreach($staff as $oneStaff){
												$value .= ucwords($oneStaff['firstname'] .' '.$oneStaff['lastname']);
												$value .= ', ';
											}
											$value = rtrim($value, ', ');
											
										?>
										<label class="control-label">Partners</label>
										<div class="control-label"><?= $value;?></div>
									</div>
								</div>
							
                            </div>

                            <div class="row">
							
								<div class="col-md-6">
                                    <div class="form-group">
                                        <?php $value = isset($contract) ? $contract['subject'] : ''; ?>
                                        <label class="control-label">Contracts</label>
                                        <div class="control-label"><?= $value;?></div>
                                    </div>
                                </div>
								
								<div class="col-md-6">
                                    <div class="form-group">
                                        <?php $value = isset($reference) ? $reference : ''; ?>
                                        <label class="control-label">Reference</label>
                                        <div class="control-label"><?= $value;?></div>
                                    </div>
                                </div>
								
                            </div>
							
							<div class="row">
							
								<div class="col-md-6">
                                    <div class="form-group">
                                        <?php $value = isset($start_date) ? $start_date : ''; ?>
                                        <label class="control-label">Start Date</label>
                                        <div class="control-label"><?= _d($value);?></div>
                                    </div>
                                </div>
								
								<div class="col-md-6">
                                    <div class="form-group">
                                        <?php $value = isset($end_date) ? $end_date : ''; ?>
                                        <label class="control-label">End Date</label>
                                        <div class="control-label"><?= _d($value);?></div>
                                    </div>
                                </div>
								
                            </div>

							<div class="row">
							
								<div class="col-md-6">
                                    <div class="form-group">
                                        <?php $value = isset($pin1) ? $pin1 : ''; ?>
                                        <label class="control-label">Pin 1</label>
                                        <div class="control-label"><?= $value;?></div>
                                    </div>
                                </div>
								
								<div class="col-md-6">
                                    <div class="form-group">
                                        <?php $value = isset($pin2) ? $pin2 : ''; ?>
                                        <label class="control-label">Pin 2</label>
                                        <div class="control-label"><?= $value;?></div>
                                    </div>
                                </div>
								
                            </div>
							
							<div class="row">
							
								<div class="col-md-6">
                                    <div class="form-group">
                                        <?php $value = isset($puk1) ? $puk1 : ''; ?>
                                        <label class="control-label">Puk 1</label>
                                        <div class="control-label"><?= $value;?></div>
                                    </div>
                                </div>
								
								<div class="col-md-6">
                                    <div class="form-group">
                                        <?php $value = isset($puk2) ? $puk2 : ''; ?>
                                        <label class="control-label">Puk 2</label>
                                        <div class="control-label"><?= $value;?></div>
                                    </div>
                                </div>
								
                            </div>
							
							<div class="row">
							
								<div class="col-md-6">
                                    <div class="form-group">
                                        <?php $value = isset($apn) ? $apn : ''; ?>
                                        <label class="control-label">APN</label>
                                        <div class="control-label"><?= $value;?></div>
                                    </div>
                                </div>
								
								<div class="col-md-6">
                                    <div class="form-group">
                                        <?php $value = isset($fixed_iP) ? $fixed_iP : ''; ?>
                                        <label class="control-label">Fixed IP</label>
                                        <div class="control-label"><?= $value;?></div>
                                    </div>
                                </div>
								
                            </div>
							
							<div class="row">
							
								<div class="col-md-6">
                                    <div class="form-group">
                                        <?php $value = isset($product) ? $product['description'] : ''; ?>
                                        <label class="control-label">Product</label>
                                        <div class="control-label"><?= $value;?></div>
                                    </div>
                                </div>
								
								<div class="col-md-6">
                                    <div class="form-group">
                                        <?php $value = isset($price) ? $price : ''; ?>
                                        <label class="control-label">Price</label>
                                        <div class="control-label"><?= $value;?></div>
                                    </div>
                                </div>
								
                            </div>
							
							<div class="row">
							
								<div class="col-md-6">
                                    <div class="form-group">
                                        <?php $value = isset($status) ? $status : ''; ?>
                                        <label class="control-label">Status</label>
                                        <div class="control-label"><?= $value;?></div>
                                    </div>
                                </div>
								
								<div class="col-md-6">
                                    <div class="form-group">
                                        <?php $value = isset($notes) ? $notes : ''; ?>
                                        <label class="control-label">Notes</label>
                                        <div class="control-label"><?= $value;?></div>
                                    </div>
                                </div>
								
                            </div>
							
                        </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="btn-bottom-toolbar btn-toolbar-container-out text-right">
            <button type="submit" name="submit" value="save"  class="btn btn-info contact-form-submitter save">SAVE</button>
            <button type="button" class="btn btn-default cancel-me" onclick="history.go(-1)">CANCEL</button>
        </div>
        <?= form_close(); ?>
    </div>
    <div class="btn-bottom-pusher"></div>
</div>


<?php init_tail(); ?>

</body>
</html>
