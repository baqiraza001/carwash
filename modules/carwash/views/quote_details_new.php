<style type="text/css">
	.disp_flex_with_justify_between {
		display: flex;
		flex: 1;
		justify-content: space-between;
	}
	.border_top {
		border: 1px solid #ddd !important;
	}
</style>
<input type="hidden" value="<?=$converted_invoiceid?>" id="converted_invoiceid" />
<script>
	if( $('#converted_invoiceid').val() !== ''){
	    $('body').find('#carwash_quotes_details_modal').find('.invoice_details_btn').removeClass('hide');
		$('body').find('#carwash_quotes_details_modal').find('.invoice_details_btn').show();
	}
	else{
		$('body').find('#carwash_quotes_details_modal').find('.invoice_details_btn').addClass('hide as');
	}
</script>
<div class="panel_s" style="margin-bottom: 0;">
	<div class="panel-body quote_details">
		<?php if(!is_null($quote)) {?>
			<div class="row main-quote-details-div">
				<div class="col-md-4">
					<div class="row">
						<div class="col-md-3">
							<strong class="flex_child">Branch:</strong>
						</div>
						<div class="col-md-8">
							<span class="flex_child">-</span>
						</div>
					</div>
				</div>
				<div class="col-md-7">
					<div class="row">
						<div class="col-md-3">
							<strong>Agreemement:</strong>
						</div>
						<div class="col-md-8">
							<span class="mleft20">-</span>
						</div>
					</div>
				</div>
			</div>

			<div class="row main-quote-details-div">
				<div class="col-md-4">
					<div class="row">
						<div class="col-md-3">
							<strong class="flex_child">Vendor:</strong>
						</div>
						<div class="col-md-8">
							<span class="flex_child">-</span>
						</div>
					</div>
				</div>
				<div class="col-md-7">
					<div class="row">
						<div class="col-md-3">
							<strong>Payor:</strong>
						</div>
						<div class="col-md-8">
							<span class="mleft20"><?=$quote_payers?></span>
						</div>
					</div>
				</div>
			</div>

			<div class="row main-quote-details-div">
				<div class="col-md-4">
					<div class="row">
						<div class="col-md-3">
							<strong class="flex_child">Quote #:</strong>
						</div>
						<div class="col-md-8">
							<span class="flex_child"><?=$quote->id?></span>
						</div>
					</div>
				</div>
				<div class="col-md-7">
					<div class="row">
						<div class="col-md-3">
							<strong>Customer:</strong>
						</div>
						<div class="col-md-8">
							<span class="mleft20"><?= get_relation_values(get_relation_data('customer',$quote->clientid),'customer')['name']?></span>
						</div>
					</div>
				</div>
			</div>

			<div class="row main-quote-details-div">
				<div class="col-md-4">
					<div class="row">
						<div class="col-md-3">
							<strong class="flex_child">Invoice #:</strong>
						</div>
						<div class="col-md-8">
							<span class="flex_child"><?=$quote->invoiceid?><?php if($is_invoce) echo $quote->id ?></span>
						</div>
					</div>
				</div>
				<div class="col-md-7">
					<div class="row">
						<div class="col-md-3">
							<strong>Driver:</strong>
						</div>
						<div class="col-md-8">
							<span class="mleft20">-</span>
						</div>
					</div>
				</div>
			</div>

			<div class="row main-quote-details-div">
				<div class="col-md-4">
					<div class="row">
						<div class="col-md-3">
							<strong class="flex_child">Type:</strong>
						</div>
						<div class="col-md-8">
							<span class="flex_child">-</span>
						</div>
					</div>
				</div>
				<div class="col-md-7">
					<div class="row">
						<div class="col-md-3">
							<strong>Vehicle:</strong>
						</div>
						<div class="col-md-8">
							<span class="mleft20">-</span>
						</div>
					</div>
				</div>
			</div>

			<!-- table -->
			<table class="table items items-preview estimate-items-preview table-bordered">
				<thead>
					<tr>
						<th><?= _l('Class'); ?></th>
						<th><?= _l('Part ID'); ?></th>
						<th><?= _l('Description'); ?></th>
						<th><?= _l('Orig. Price'); ?></th>
						<th><?= _l('Discount'); ?></th>
						<th><?= _l('Quantity'); ?></th>
						<th><?= _l('Price'); ?></th>
						<th><?= _l('Unit'); ?></th>
						<th><?= _l('Labour'); ?></th>
						<th><?= _l('Lab. Rate'); ?></th>
						<th><?= _l('Extended'); ?></th>
						<th><?= _l('PST Ex.'); ?></th>
					</tr>
				</thead>
				<tbody>
					<?php 
					foreach($quotes as $item){ ?>
						<tr>
							<td></td>
							<td><?= $item['id']; ?></td>
							<td><?= $item['description']; ?></td>
							<td><?= $item['rate']; ?></td>
							<td></td>
							<td><?= $item['qty']; ?></td>
							<td><?= $item['rate']; ?></td>
							<td><?= $item['unit']; ?></td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
						</tr>
						<?php } ?>
					</tbody>
			</table>
			<!-- table -->

			<div class="row " style="line-height: 2;margin-top: 10px;">
				<div class="col-md-5">
					<div class="row">
						<div class="col-md-2">
							<strong>Deductible:</strong>
						</div>
						<div class="col-md-3">
							<span class="">-</span>
						</div>

						<div class="col-md-4">
							<strong>Repaired By Vendor?:</strong>
						</div>
						<div class="col-md-2">
							<span class="">-</span>
						</div>

							<div class="col-md-5">
								<strong>Ded. Credit:</strong>
								<span class="">-</span>
							</div>

							<div class="col-md-4">
								<strong>Auto Glass:</strong>
							</div>
							<div class="col-md-2">
								<span class="">-</span>
							</div>

							<div class="col-md-5">
								<strong>Cust. Desc:</strong>
								<span class=""><?=$quote->adminnote?></span>
							</div>

							<div class="col-md-4">
								<strong>Deposit:</strong>
							</div>
							<div class="col-md-2">
								<span class="">-</span>
							</div>

							<div class="col-md-5">
								<strong>Cust. Pays:</strong>
								<span class=""><?= $quote->custtotal; ?></span>
							</div>

							<div class="col-md-4">
								<strong>Deposit:</strong>
							</div>
							<div class="col-md-2">
								<span class="">-</span>
							</div>

							<div class="col-md-5">
								<strong>Withheld:</strong>
								<span class="">-</span>
							</div>

					</div>
				</div>

				<div class="col-md-3">
					<div class="row">
						<div class="col-md-3">
							<strong>Cause:</strong>
						</div>
						<div class="col-md-8">
							<span class="">-</span>
						</div>
					</div>

					<div class="row">
						<div class="col-md-4">
							<strong>All Parts:</strong>
						</div>
						<div class="col-md-8">
							<span class="">-</span>
						</div>
					</div>

					<div class="row">
						<div class="col-md-4">
							<strong>Notes:</strong>
						</div>
						<div class="col-md-8">
							<span class="">-</span>
						</div>
					</div>
				</div>

				<div class="col-md-4">
					<div class="row">
						<div class="col-md-4">
							<strong>Shop Parts:</strong>
						</div>
						<div class="col-md-8">
							<strong class="">Total Parts:</strong>
							<span class="">-</span>
						</div>
					</div>
					<div class="row">
						<div class="col-md-4"></div>
						<div class="col-md-8">
							<strong class="">Total Labour:</strong>
							<span class="">-</span>
						</div>
					</div>
					<div class="row">
						<div class="col-md-4"></div>
						<div class="col-md-8">
							<strong class="">Less Discount:</strong>
							<span class=""><?= $quote->discount_total; ?></span>
						</div>
					</div>
					<div class="row">
						<div class="col-md-4"></div>
						<div class="col-md-8">
							<strong class="">GST Exempt:</strong>
							<span class="">-</span>
						</div>
					</div>
					<div class="row">
						<div class="col-md-4"></div>
						<div class="col-md-8">
							<strong class="">PST Exempt:</strong>
							<span class="">-</span>
						</div>
					</div>
					<div class="row">
						<div class="col-md-4"></div>
						<div class="col-md-8">
							<strong class="">(Customer Pays):</strong>
							<span class=""><?= $quote->custtotal; ?></span>
						</div>
					</div>
					<div class="row">
						<div class="col-md-4"></div>
						<div class="col-md-8">
							<strong class="">(Ded. Credit):</strong>
							<span class="">-</span>
						</div>
					</div>
					<div class="row">
						<div class="col-md-4"></div>
						<div class="col-md-8">
							<strong class="">Total:</strong>
							<span class=""><?= $quote->custtotal; ?></span>
						</div>
					</div>
				</div>
			</div>

			<?php } else { ?>
				<div class="alert alert-info">Data not available for this quote.</div>
			<?php } ?>
		</div>
		
		
		<!-- converted estimate invoice details-->
		<div class="panel-body invoice_details">
		<?php if(!is_null($invoice_quote)) {?>
			<div class="row main-quote-details-div">
				<div class="col-md-4">
					<div class="row">
						<div class="col-md-3">
							<strong class="flex_child">Branch:</strong>
						</div>
						<div class="col-md-8">
							<span class="flex_child">-</span>
						</div>
					</div>
				</div>
				<div class="col-md-7">
					<div class="row">
						<div class="col-md-3">
							<strong>Agreemement:</strong>
						</div>
						<div class="col-md-8">
							<span class="mleft20">-</span>
						</div>
					</div>
				</div>
			</div>

			<div class="row main-quote-details-div">
				<div class="col-md-4">
					<div class="row">
						<div class="col-md-3">
							<strong class="flex_child">Vendor:</strong>
						</div>
						<div class="col-md-8">
							<span class="flex_child">-</span>
						</div>
					</div>
				</div>
				<div class="col-md-7">
					<div class="row">
						<div class="col-md-3">
							<strong>Payor:</strong>
						</div>
						<div class="col-md-8">
							<span class="mleft20"><?=$quote_payers?></span>
						</div>
					</div>
				</div>
			</div>

			<div class="row main-quote-details-div">
				<div class="col-md-4">
					<div class="row">
						<div class="col-md-3">
							<strong class="flex_child">Quote #:</strong>
						</div>
						<div class="col-md-8">
							<span class="flex_child"><?=$invoice_quote->id?></span>
						</div>
					</div>
				</div>
				<div class="col-md-7">
					<div class="row">
						<div class="col-md-3">
							<strong>Customer:</strong>
						</div>
						<div class="col-md-8">
							<span class="mleft20"><?= get_relation_values(get_relation_data('customer',$invoice_quote->clientid),'customer')['name']?></span>
						</div>
					</div>
				</div>
			</div>
            
			<div class="row main-quote-details-div">
				<div class="col-md-4">
					<div class="row">
						<div class="col-md-3">
							<strong class="flex_child">Invoice #:</strong>
						</div>
						<div class="col-md-8">
							<span class="flex_child"><?=$invoice_quote->id?></span>
						</div>
					</div>
				</div>
				<div class="col-md-7">
					<div class="row">
						<div class="col-md-3">
							<strong>Driver:</strong>
						</div>
						<div class="col-md-8">
							<span class="mleft20">-</span>
						</div>
					</div>
				</div>
			</div>

			<div class="row main-quote-details-div">
				<div class="col-md-4">
					<div class="row">
						<div class="col-md-3">
							<strong class="flex_child">Type:</strong>
						</div>
						<div class="col-md-8">
							<span class="flex_child">-</span>
						</div>
					</div>
				</div>
				<div class="col-md-7">
					<div class="row">
						<div class="col-md-3">
							<strong>Vehicle:</strong>
						</div>
						<div class="col-md-8">
							<span class="mleft20">-</span>
						</div>
					</div>
				</div>
			</div>

			<!-- table -->
			<table class="table items items-preview estimate-items-preview table-bordered">
				<thead>
					<tr>
						<th><?= _l('Class'); ?></th>
						<th><?= _l('Part ID'); ?></th>
						<th><?= _l('Description'); ?></th>
						<th><?= _l('Orig. Price'); ?></th>
						<th><?= _l('Discount'); ?></th>
						<th><?= _l('Quantity'); ?></th>
						<th><?= _l('Price'); ?></th>
						<th><?= _l('Unit'); ?></th>
						<th><?= _l('Labour'); ?></th>
						<th><?= _l('Lab. Rate'); ?></th>
						<th><?= _l('Extended'); ?></th>
						<th><?= _l('PST Ex.'); ?></th>
					</tr>
				</thead>
				<tbody>
					<?php 
					foreach($invoice_quotes as $item){ ?>
						<tr>
							<td></td>
							<td><?= $item['id']; ?></td>
							<td><?= $item['description']; ?></td>
							<td><?= $item['rate']; ?></td>
							<td></td>
							<td><?= $item['qty']; ?></td>
							<td><?= $item['rate']; ?></td>
							<td><?= $item['unit']; ?></td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
						</tr>
						<?php } ?>
					</tbody>
			</table>
			<!-- table -->

			<div class="row " style="line-height: 2;margin-top: 10px;">
				<div class="col-md-5">
					<div class="row">
						<div class="col-md-2">
							<strong>Deductible:</strong>
						</div>
						<div class="col-md-3">
							<span class="">-</span>
						</div>

						<div class="col-md-4">
							<strong>Repaired By Vendor?:</strong>
						</div>
						<div class="col-md-2">
							<span class="">-</span>
						</div>

							<div class="col-md-5">
								<strong>Ded. Credit:</strong>
								<span class="">-</span>
							</div>

							<div class="col-md-4">
								<strong>Auto Glass:</strong>
							</div>
							<div class="col-md-2">
								<span class="">-</span>
							</div>

							<div class="col-md-5">
								<strong>Cust. Desc:</strong>
								<span class=""><?=$invoice_quote->adminnote?></span>
							</div>

							<div class="col-md-4">
								<strong>Deposit:</strong>
							</div>
							<div class="col-md-2">
								<span class="">-</span>
							</div>

							<div class="col-md-5">
								<strong>Cust. Pays:</strong>
								<span class=""><?= $invoice_quote->custtotal; ?></span>
							</div>

							<div class="col-md-4">
								<strong>Deposit:</strong>
							</div>
							<div class="col-md-2">
								<span class="">-</span>
							</div>

							<div class="col-md-5">
								<strong>Withheld:</strong>
								<span class="">-</span>
							</div>

					</div>
				</div>

				<div class="col-md-3">
					<div class="row">
						<div class="col-md-3">
							<strong>Cause:</strong>
						</div>
						<div class="col-md-8">
							<span class="">-</span>
						</div>
					</div>

					<div class="row">
						<div class="col-md-4">
							<strong>All Parts:</strong>
						</div>
						<div class="col-md-8">
							<span class="">-</span>
						</div>
					</div>

					<div class="row">
						<div class="col-md-4">
							<strong>Notes:</strong>
						</div>
						<div class="col-md-8">
							<span class="">-</span>
						</div>
					</div>
				</div>

				<div class="col-md-4">
					<div class="row">
						<div class="col-md-4">
							<strong>Shop Parts:</strong>
						</div>
						<div class="col-md-8">
							<strong class="">Total Parts:</strong>
							<span class="">-</span>
						</div>
					</div>
					<div class="row">
						<div class="col-md-4"></div>
						<div class="col-md-8">
							<strong class="">Total Labour:</strong>
							<span class="">-</span>
						</div>
					</div>
					<div class="row">
						<div class="col-md-4"></div>
						<div class="col-md-8">
							<strong class="">Less Discount:</strong>
							<span class=""><?= $invoice_quote->discount_total; ?></span>
						</div>
					</div>
					<div class="row">
						<div class="col-md-4"></div>
						<div class="col-md-8">
							<strong class="">GST Exempt:</strong>
							<span class="">-</span>
						</div>
					</div>
					<div class="row">
						<div class="col-md-4"></div>
						<div class="col-md-8">
							<strong class="">PST Exempt:</strong>
							<span class="">-</span>
						</div>
					</div>
					<div class="row">
						<div class="col-md-4"></div>
						<div class="col-md-8">
							<strong class="">(Customer Pays):</strong>
							<span class=""><?= $invoice_quote->custtotal; ?></span>
						</div>
					</div>
					<div class="row">
						<div class="col-md-4"></div>
						<div class="col-md-8">
							<strong class="">(Ded. Credit):</strong>
							<span class="">-</span>
						</div>
					</div>
					<div class="row">
						<div class="col-md-4"></div>
						<div class="col-md-8">
							<strong class="">Total:</strong>
							<span class=""><?= $invoice_quote->custtotal; ?></span>
						</div>
					</div>
				</div>
			</div>

			<?php } else { ?>
				<div class="alert alert-info">Data not available for this invoice.</div>
			<?php } ?>
		</div>
		<!--converted estimate invoice details-->
</div>