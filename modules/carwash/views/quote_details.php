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
<input type="hidden" value="1" id="converted_invoiceid" />
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
	<div class="panel-body">
		<?php if(!empty($quotes)) {?>
			<div class="row main-quote-details-div">
				<div class="col-md-4">
					<div class="row">
						<div class="col-md-3">
							<strong class="flex_child">Branch:</strong>
						</div>
						<div class="col-md-8">
							<span class="flex_child"><?=$quotes[0]['venbranch']?></span>
						</div>
					</div>
				</div>
				<div class="col-md-7">
					<div class="row">
						<div class="col-md-3">
							<strong>Agreemement:</strong>
						</div>
						<div class="col-md-8">
							<span class="mleft20">Nill</span>
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
							<span class="flex_child"><?=$quotes[0]['vendor']?></span>
						</div>
					</div>
				</div>
				<div class="col-md-7">
					<div class="row">
						<div class="col-md-3">
							<strong>Payor:</strong>
						</div>
						<div class="col-md-8">
							<span class="mleft20">Nill</span>
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
							<span class="flex_child"><?=$quotes[0]['quote']?></span>
						</div>
					</div>
				</div>
				<div class="col-md-7">
					<div class="row">
						<div class="col-md-3">
							<strong>Customer:</strong>
						</div>
						<div class="col-md-8">
							<span class="mleft20">Nill</span>
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
							<span class="flex_child"><?=$quotes[0]['invoice']?></span>
						</div>
					</div>
				</div>
				<div class="col-md-7">
					<div class="row">
						<div class="col-md-3">
							<strong>Driver:</strong>
						</div>
						<div class="col-md-8">
							<span class="mleft20"><?=$quotes[0]['drlicence']?></span>
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
							<span class="flex_child"><?=$quotes[0]['transtype']?></span>
						</div>
					</div>
				</div>
				<div class="col-md-7">
					<div class="row">
						<div class="col-md-3">
							<strong>Vehicle:</strong>
						</div>
						<div class="col-md-8">
							<span class="mleft20"><?=$quotes[0]['vehdesc']?></span>
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
						<th><?= _l('Labour'); ?></th>
						<th><?= _l('Lab. Rate'); ?></th>
						<th><?= _l('Extended'); ?></th>
						<th><?= _l('PST Ex.'); ?></th>
					</tr>
				</thead>
				<tbody>
					<?php 
					foreach($quotes as $quote){ ?>
						<tr>
							<td><?= $quote['prodclass']; ?></td>
							<td><?= $quote['partident']; ?></td>
							<td><?= $quote['description']; ?></td>
							<td><?= $quote['origprice']; ?></td>
							<td><?= $quote['discount']; ?></td>
							<td><?= $quote['quantity']; ?></td>
							<td><?= $quote['price']; ?></td>
							<td><?= $quote['labour']; ?></td>
							<td><?= $quote['labrate']; ?></td>
							<td><?= $quote['extended']; ?></td>
							<td><?= $quote['pstexempt']; ?></td>
						<?php } ?>
					</tbody>
			</table>
			<!-- table -->

			<div class="row quote_details" style="line-height: 2;margin-top: 10px;">
				<div class="col-md-5">
					<div class="row">
						<div class="col-md-2">
							<strong>Deductible:</strong>
						</div>
						<div class="col-md-3">
							<span class="">Nill</span>
						</div>

						<div class="col-md-4">
							<strong>Repaired By Vendor?:</strong>
						</div>
						<div class="col-md-2">
							<span class=""><?= $quotes[0]['repbyven']; ?></span>
						</div>

							<div class="col-md-5">
								<strong>Ded. Credit:</strong>
								<span class="">Nill</span>
							</div>

							<div class="col-md-4">
								<strong>Auto Glass:</strong>
							</div>
							<div class="col-md-2">
								<span class="">Nill</span>
							</div>

							<div class="col-md-5">
								<strong>Cust. Desc:</strong>
								<span class="">Nill</span>
							</div>

							<div class="col-md-4">
								<strong>Deposit:</strong>
							</div>
							<div class="col-md-2">
								<span class=""><?= $quotes[0]['depamount']; ?></span>
							</div>

							<div class="col-md-5">
								<strong>Cust. Pays:</strong>
								<span class=""><?= $quotes[0]['custtotal']; ?></span>
							</div>

							<div class="col-md-4">
								<strong>Deposit:</strong>
							</div>
							<div class="col-md-2">
								<span class=""><?= $quotes[0]['depamount']; ?></span>
							</div>

							<div class="col-md-5">
								<strong>Withheld:</strong>
								<span class=""><?= $quotes[0]['venparwh']; ?></span>
							</div>

					</div>
				</div>

				<div class="col-md-3">
					<div class="row">
						<div class="col-md-3">
							<strong>Cause:</strong>
						</div>
						<div class="col-md-8">
							<span class=""><?= $quotes[0]['cause']; ?></span>
						</div>
					</div>

					<div class="row">
						<div class="col-md-4">
							<strong>All Parts:</strong>
						</div>
						<div class="col-md-8">
							<span class=""><?= $quotes[0]['cause']; ?></span>
						</div>
					</div>

					<div class="row">
						<div class="col-md-4">
							<strong>Notes:</strong>
						</div>
						<div class="col-md-8">
							<span class="">Nill</span>
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
							<span class=""><?= $quotes[0]['parttot']; ?></span>
						</div>
					</div>
					<div class="row">
						<div class="col-md-4"></div>
						<div class="col-md-8">
							<strong class="">Total Labour:</strong>
							<span class=""><?= $quotes[0]['labourtot']; ?></span>
						</div>
					</div>
					<div class="row">
						<div class="col-md-4"></div>
						<div class="col-md-8">
							<strong class="">Less Discount:</strong>
							<span class=""><?= $quotes[0]['comdiscsubtotal']; ?></span>
						</div>
					</div>
					<div class="row">
						<div class="col-md-4"></div>
						<div class="col-md-8">
							<strong class="">GST Exempt:</strong>
							<span class=""><?= $quotes[0]['quote_gstexempt']; ?></span>
						</div>
					</div>
					<div class="row">
						<div class="col-md-4"></div>
						<div class="col-md-8">
							<strong class="">PST Exempt:</strong>
							<span class=""><?= $quotes[0]['quote_pstexempt']; ?></span>
						</div>
					</div>
					<div class="row">
						<div class="col-md-4"></div>
						<div class="col-md-8">
							<strong class="">(Customer Pays):</strong>
							<span class=""><?= $quotes[0]['custtotal']; ?></span>
						</div>
					</div>
					<div class="row">
						<div class="col-md-4"></div>
						<div class="col-md-8">
							<strong class="">(Ded. Credit):</strong>
							<span class="">Nill</span>
						</div>
					</div>
					<div class="row">
						<div class="col-md-4"></div>
						<div class="col-md-8">
							<strong class="">Total:</strong>
							<span class="">Nill</span>
						</div>
					</div>
				</div>
			</div>

			<div class="row invoice_details" style="display: none;line-height: 2;margin-top: 10px;">
				<div class="col-md-5">
					<div class="row">
						<div class="col-md-2">
							<strong>Deductible:</strong>
						</div>
						<div class="col-md-3">
							<span class=""><?= $quotes[0]['invded']; ?></span>
						</div>

						<div class="col-md-4">
							<strong>Repaired By Vendor?:</strong>
						</div>
						<div class="col-md-2">
							<span class=""><?= $quotes[0]['repbyven']; ?></span>
						</div>

							<div class="col-md-5">
								<strong>Ded. Credit:</strong>
								<span class=""><?= $quotes[0]['invdedcr']; ?></span>
							</div>

							<div class="col-md-4">
								<strong>Auto Glass:</strong>
							</div>
							<div class="col-md-2">
								<span class="">Nill</span>
							</div>

							<div class="col-md-5">
								<strong>Cust. Desc:</strong>
								<span class="">Nill</span>
							</div>

							<div class="col-md-4">
								<strong>Deposit:</strong>
							</div>
							<div class="col-md-2">
								<span class=""><?= $quotes[0]['invdep']; ?></span>
							</div>

							<div class="col-md-5">
								<strong>Cust. Pays:</strong>
								<span class=""><?= $quotes[0]['custtotal']; ?></span>
							</div>

							<div class="col-md-4">
								<strong>Deposit:</strong>
							</div>
							<div class="col-md-2">
								<span class=""><?= $quotes[0]['invdep']; ?></span>
							</div>

							<div class="col-md-5">
								<strong>Withheld:</strong>
								<span class=""><?= $quotes[0]['venparwh']; ?></span>
							</div>

					</div>
				</div>

				<div class="col-md-3">
					<div class="row">
						<div class="col-md-3">
							<strong>Cause:</strong>
						</div>
						<div class="col-md-8">
							<span class=""><?= $quotes[0]['cause']; ?></span>
						</div>
					</div>

					<div class="row">
						<div class="col-md-4">
							<strong>All Parts:</strong>
						</div>
						<div class="col-md-8">
							<span class=""><?= $quotes[0]['cause']; ?></span>
						</div>
					</div>

					<div class="row">
						<div class="col-md-4">
							<strong>Notes:</strong>
						</div>
						<div class="col-md-8">
							<span class="">Nill</span>
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
							<span class=""><?= $quotes[0]['invparttot']; ?></span>
						</div>
					</div>
					<div class="row">
						<div class="col-md-4"></div>
						<div class="col-md-8">
							<strong class="">Total Labour:</strong>
							<span class=""><?= $quotes[0]['invlabtot']; ?></span>
						</div>
					</div>
					<div class="row">
						<div class="col-md-4"></div>
						<div class="col-md-8">
							<strong class="">Less Discount:</strong>
							<span class=""><?= $quotes[0]['insdiscoun']; ?></span>
						</div>
					</div>
					<div class="row">
						<div class="col-md-4"></div>
						<div class="col-md-8">
							<strong class="">GST Exempt:</strong>
							<span class=""><?= $quotes[0]['invgst']; ?></span>
						</div>
					</div>
					<div class="row">
						<div class="col-md-4"></div>
						<div class="col-md-8">
							<strong class="">PST Exempt:</strong>
							<span class=""><?= $quotes[0]['invpst']; ?></span>
						</div>
					</div>
					<div class="row">
						<div class="col-md-4"></div>
						<div class="col-md-8">
							<strong class="">(Customer Pays):</strong>
							<span class=""><?= $quotes[0]['custtotal']; ?></span>
						</div>
					</div>
					<div class="row">
						<div class="col-md-4"></div>
						<div class="col-md-8">
							<strong class="">(Ded. Credit):</strong>
							<span class=""><?= $quotes[0]['invdedcr']; ?></span>
						</div>
					</div>
					<div class="row">
						<div class="col-md-4"></div>
						<div class="col-md-8">
							<strong class="">Total Paid:</strong>
							<span class=""><?= isset($invoice_data) ? $invoice_data->AMOUNT : ''; ?></span><span class=""><?= isset($invoice_data) ? ' (Paid on ' . date("jS M Y",strtotime($invoice_data->PAYDATE)) . ')' : ''; ?></span>
						</div>
					</div>
				</div>
			</div>

			<?php } else { ?>
				<div class="alert alert-info">Data not available for this quote.</div>
			<?php } ?>
		</div>
	</div>