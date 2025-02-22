<html>
    <head>
        <style>
            body { 
                font-family: Helvetica, Arial, sans-serif;
            }
			p {
				margin-bottom: 20px;
			}
			th{text-align: left;}
			b{font-weight: 800;}
        </style>
    </head>
    <body>
<div style="max-width: 800px; margin: auto; padding: 30px; border: 1px solid #eee; box-shadow: 0 0 10px rgba(0, 0, 0, .15); line-height: 20px; font-family: Arial, sans-serif; color: #2F3751;">
    <table cellpadding="0" cellspacing="0" style="width: 100%; line-height: inherit; text-align: left;font-size: 14.5px">
        <tr>
			<td colspan="3" style="padding-bottom: 20px;"><img src="<?php echo public_path('/frontend/assets/img/logo/logo-workwear.jpg') ?>" alt="Pinders Workwear Ltd" /><br/><br/></td>
			<td colspan="3" style="text-align:right;" ><span style="font-size:14px;line-height:32px;">ORDER NO</span><br/><span style="font-size:25px;font-weight:700;"><?php echo $page->prefix_id ?></span><br/><br/></td>
        </tr>
        <tr>
            <td colspan="6" style="padding-bottom: 20px;">
                <p style="font-size:20px;"><b>Hello, {{$page->first_name}} {{ $page->last_name }}</b></p>
                <p><br />Thank you for your order from Pinders Workwear. Once your package ships we will send an email with a link to track your order. If you have any questions about your order please contact us at <a href="mailto:info@pindersworkwear.co.uk">info@pindersworkwear.com</a> or call us at 0114 2513275 Monday - Friday, 9am - 5pm.</p>
                <p><br />Your order confirmation is below. Thank you again for your business.</p>
                <p><br /><b>Your Order #<?php echo $page->prefix_id ?> placed on {{ _dt($page->created) }}</b></p>
            </td>
        </tr>
		<tr>
			<td colspan="6" style="padding-bottom: 20px;">
				<table style="width: 100%; line-height: inherit; text-align: left;">
					<tr>
						<td style="width:100%; vertical-align: top;">
							<table style="border:1px solid #c7a162;width: 100%; line-height: inherit; text-align: left;font-size: 13px">
								<tr>
									<td style="background-color:#f7eddd; padding:5px 8px;"><b>Shipping Information:</b></td>
								</tr>
								<tr>
									<td style="padding: 5px; vertical-align: top;">
										{{ $page->first_name }} {{ $page->last_name }}<br/>
										{{ $page->address }}<br>
										{{ $page->area }}<br>
										{{ $page->city }}, {{ $page->postcode }}<br>
									</td>
								</tr>
                            </table>
                        </td>
					</tr>
                </table>
            </td>
        </tr>
		<tr>
			<td colspan="6" style="padding-bottom: 20px;">
				<table style="width: 100%; line-height: inherit; text-align: left;">
					<tr>
						<td style="width:100%; vertical-align: top;">
							<table style="border:1px solid #c7a162;width: 100%; line-height: inherit; text-align: left;font-size: 13px">
								<tr style="background: #f7eddd; border-bottom: 1px solid #ddd; font-weight: bold;">
									<td style="width:55%;padding: 5px; vertical-align: top;">
										<b>Order Items</b>
									</td>
									<td style="width:10%;padding: 5px; vertical-align: top;">
										Color
									</td>
									<td style="width:10%;padding: 5px; vertical-align: top;">
										Size
									</td>
									<td style="width:5%;text-align:right;padding: 5px; vertical-align: top;">
										Qty
									</td>
									<td style="width:10%;text-align:right;padding: 5px; vertical-align: top;">
										Price
									</td>

									<td style="width:10%;text-align:right;padding: 5px; vertical-align: top;">
										Subtotal
									</td>
								</tr>
                                <?php 
								foreach($listing->items() as $k => $row): 
                                        $logodata = $row->logo_data ? (substr($row->logo_data, 0, 1) == '{' ? json_decode('['.$row->logo_data.']') : json_decode($row->logo_data)) : [];
                                        
                                    ?>
								<tr class="item">
									<td style="width:55%;padding: 5px; vertical-align: top;">
                                        <?php echo $row->product_title ?> <?php echo $row->product && $row->product->sku_number ? ' - ' . $row->product->sku_number : '' ?>
										@if($logodata)
										<?php 
										$existLogo = false;
										foreach($logodata as $logo)
										{
											if(($logo->text || $logo->image || $logo->category || $logo->postion))
											{
												$existLogo = true;
												break;
											}
										}
										?>
										@if($existLogo)
										<br /><br />
										<table style="border:1px solid #c7a162;width: 100%; line-height: inherit; text-align: left;font-size:12px;">
											<tr style="background: #f7eddd; border-bottom: 1px solid #ddd; font-weight: bold;">
												<td style="width:35%;padding: 5px; vertical-align: top;">Position</td>
												<td style="width:35%;padding: 5px; vertical-align: top;">Logo</td>
												<td style="width:30%;padding: 5px; vertical-align: top;">Price</td>
											</tr>
											@foreach($logodata as $logo)
											<?php if(!($logo->text || $logo->image || $logo->category || $logo->postion)) continue; ?>
											<tr style="border-bottom: 1px solid #ddd;">
												<td style="width:35%; padding: 5px;vertical-align: top;">
													<?php if(trim($logo->postion)):
														$positionSlug = Str::slug($logo->postion);	
													?>
														<img src="{{public_path('frontend/assets/size-guides/'.$positionSlug.'.jpg')}}" alt="" style="max-width:100%;height: 90px;" /><br/>
													<?php endif; ?>
													<?php echo $logo->category ?>
												</td>
												<td style="width:35%; padding: 5px;vertical-align: top;">
													<?php if(trim($logo->image)):?>
														<img src="{{public_path($logo->image)}}" alt="" style="max-width:100%;height: 90px;" /><br/>
													<?php else: ?>
														<?php echo $logo->text ?>
													<?php endif; ?>
												</td>
												<td style="width:30%; padding: 5px;vertical-align: top;">
													Price for {{ $row->quantity }} {{$row->quantity > 1 ? 'logos are' : 'logo is' }} {{_currency($row->quantity* ($logo && isset($logo->price) && $logo->price ? $logo->price : 0))}}
												</td>
											</tr>
											@endforeach
										</table>	
										@endif			
										@endif			
									</td>
									<td style="width:10%;padding: 5px; vertical-align: top;">
										{{$row->color}}
									</td>
									<td style="width:10%;padding: 5px; vertical-align: top;">
                                        {{$row->size_title}}
									</td>
									<td style="width:5%;padding: 5px; vertical-align: top;">
										{{$row->quantity}}
									</td>
									<td style="width:10%;padding: 5px; vertical-align: top;">
                                        <?php echo $row->amount ? _currency($row->amount) : _currency(0) ?>
									</td>
									<td style="width:10%;padding: 5px; vertical-align: top;">
                                        <?php
                                        $logoPrice = 0; 
                                        foreach($logodata as $lg) {
											if($lg && isset($lg->price) && isset($lg->quantity) && $lg->price && $lg->quantity > 0 )
                                            $logoPrice += $lg->price * $row->quantity;
                                        }
                                        echo $row->amount > 0 && $row->quantity > 0 ? _currency(($row->quantity*$row->amount)+$logoPrice) : _currency(0) ?>
									</td>
								</tr>
                                <?php endforeach; ?>
								
								<tr>
									<td colspan="6" style="border-bottom: 2px solid #c7a162;"></td>
								</tr>
								@if($page->logo_cost > 0 || $page->logo_discount > 0 || $page->one_time_cost > 0)
								<tr style="background: #f7eddd; border-bottom: 1px solid #c7a162; font-weight: bold;">
									<td colspan="5" style="text-align:right;padding: 5px; ">Product Costs: </td>

									<td style="text-align:right;padding: 5px; ">
									   <?php echo _currency($page->subtotal + $page->logo_discount - $page->logo_cost - $page->one_time_cost) ?>
									</td>
								</tr>
								@endif
                                @if($page->logo_cost > 0)
								<tr style="background: #f7eddd; border-bottom: 1px solid #c7a162; font-weight: bold;">
									<td colspan="5" style="text-align:right;padding: 5px; ">Costs To Add Logo </td>

									<td style="text-align:right;padding: 5px; ">
									   <?php echo _currency($page->logo_cost) ?>
									</td>
								</tr>
                                @endif
								@if($page->logo_discount > 0)
								<tr style="background: #f7eddd; border-bottom: 1px solid #c7a162; font-weight: bold;">
									<td colspan="5" style="text-align:right;padding: 5px; ">Logo Discount ({{$page->logo_discount_applied}} logo(s)):</td>
									<td style="text-align:right;padding: 5px; "> - <?php echo _currency($page->logo_discount) ?></td>
								</tr>
								@endif
								@if($page->one_time_cost > 0)
								<tr style="background: #f7eddd; border-bottom: 1px solid #c7a162; font-weight: bold;">
									<td colspan="5" style="text-align:right;padding: 5px; ">One Time Logo Setup Fees: </td>

									<td style="text-align:right;padding: 5px; ">
									   <?php echo _currency($page->one_time_cost) ?>
									</td>
								</tr>
                                @endif
								<tr style="background: #f7eddd; border-bottom: 1px solid #c7a162; font-weight: bold;">
									<td colspan="5" style="text-align:right;padding: 5px; ">Subtotal</td>
									<td style="text-align:right;padding: 5px; "><?php echo _currency($page->subtotal) ?></td>
								</tr>
								<tr style="background: #f7eddd; border-bottom: 1px solid #c7a162; font-weight: bold;">
                                    <td colspan="5" style="text-align:right;padding: 5px; ">
                                        Discount
                                        <?php 
										$coupon = $page->coupon ? json_decode($page->coupon, true) : null;
										if ($coupon): ?>
                                        Coupon code: [{{ $coupon['coupon_code'] }}]: 
                                        <?php endif; ?>
                                    </td>
									<td style="text-align:right;padding: 5px; ">
                                        <?php echo _currency($page->discount) ?>
									</td>
								</tr>
								<tr style="background: #f7eddd; border-bottom: 1px solid #c7a162; font-weight: bold;">
									<td colspan="5" style="text-align:right;padding: 5px; ">Vat {{$page->tax_percentage}}%: </td>
									<td style="text-align:right;padding: 5px; ">
                                    <?php echo $page->tax ? _currency($page->tax) : _currency(0) ?>
									</td>
								</tr>
								<tr style="background: #f7eddd; border-bottom: 1px solid #c7a162; font-weight: bold;">
									<td colspan="5" style="text-align:right;padding: 5px; ">Grand Total: </td>

									<td style="text-align:right;padding: 5px; ">
                                    <?php echo $page->total_amount ? _currency($page->total_amount) : _currency(0) ?>
									</td>
								</tr>
							</table>
						</td>
					</tr>
				</table>
			</td>
		</tr>
        <tr>
			<td colspan="6"><br/><br/></td>
		</tr>
		<tr>
			<td colspan="6" style="font-size: 12px;text-align:center;background-color:#f7eddd;">Thank you, <b>Pinders Schoolwear Ltd.</b></td></tr>
    </table>
</div>
</body>
</html>