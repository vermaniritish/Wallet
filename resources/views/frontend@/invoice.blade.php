		
		<div style="width:100%;max-width: 800px; margin: auto; padding: 30px;   font-size: 12px; line-height: 24px; color: #555;">
			<table cellpadding="0" cellspacing="0" style="width: 100%; line-height: inherit; text-align: left;font-size: 14px;">
                <tr>
                    <td colspan="3" style="padding-bottom: 20px;">
                        <img src="{{ public_path('/frontend/assets/img/logo/logo-workwear.jpg') }}" alt="Pinders Workwear" />
                    </td>
                    <td colspan="3" style="text-align:right;" ><span style="font-size:14px;line-height:32px;">ORDER NO</span><br/><span style="font-size:28px;font-weight:700;">{{ $order->prefix_id }}</span></td>
                </tr>
                <tr>
                    <td colspan="6" style="padding-bottom: 20px;">

                            <p style="font-size:20px;"><b>Hello, {{$order->first_name}} {{$order->last_name}}</b></p>
                            <p style="font-size:15px;">Thank you for your order from Pinders Workwear. Once your package ships we will send an email with a link to track your order. If you have any questions about your order please contact us at <a href="mailto:info@pindersworkwear.co.uk">info@pindersworkwear.co.uk</a> or call us at 0114 2513275 Monday - Friday, 9am - 5pm.</p>
                            <p style="font-size:15px;">Your order confirmation is below. Thank you again for your business.</p>
                            <p style="font-size:16px;"><b>Your Order #{{$order->prefix_id}} placed on {{ _dt($order->created)}}</b></p>
                            
                    </td>
                </tr>
				<tr>
                    <td colspan="6" style="padding-bottom: 20px;">
                        <table style="width: 100%; line-height: inherit; text-align: left;">
                            <tr>
                                <td style="width:100%; vertical-align: top;">
                                    <table style="border:1px solid #f3f3f3;width: 100%; line-height: inherit; text-align: left;">
                                            <tr>
												<td style="background-color:#f3f3f3; padding:5px 8px;"><b>Shipping Information</b></td>
                                            </tr>
                                            <tr>
												<td style="padding: 5px; vertical-align: top;">
													<b>{{ $order->company }}</b><br/>
													{{ $order->address }}<br/>
													{{ $order->area }} , {{ $order->city }} {{ $order->postcode }}<br/>
													Tel: {{ $order->customer_phone ? $order->customer_phone : ' - ' }} | Email: {{ $order->customer_email ? $order->customer_email : ' - ' }}
												</td>
                                            </tr>
                                    </table>
                                </td>
							</tr>
                        </table>
                    </td>
                </tr>
                <tr style="background: #f3f3f3; border-bottom: 1px solid #ddd; font-weight: bold;">
                    <td style="padding: 5px; vertical-align: top;">
                        Order Items
                    </td>
                    <td style="padding: 5px; vertical-align: top;">
                        Color
                    </td>
                    <td style="padding: 5px; vertical-align: top;">
                        Size
                    </td>
                    <td style="padding: 5px; vertical-align: top;">
                        Qty
                    </td>
                    <td style="padding: 5px; vertical-align: top;">
                        Price
                    </td>

                    <td style="padding: 5px; vertical-align: top;">
                        Subtotal
                    </td>
                </tr>
				<?php foreach($listing as $row): ?>
				<tr>
					<td style="padding: 5px; vertical-align: top;">
						<b> {{$row->product_title}}<br/> {{ $row->product ? $row->product->sku_number : '' }}</b></td>
					<td style="padding: 5px; vertical-align: top;">
						{{$row->color}}
					</td>
					<td style="padding: 5px; vertical-align: top;">{{$row->size_title}}</td>
					<td style="padding: 5px; vertical-align: top;text-align:right;">{{$row->quantity}}</td>
					<td style="padding: 5px; vertical-align: top;text-align:right;">{{_currency($row->amount)}}</td>
					<td style="padding: 5px; vertical-align: top;text-align:right;">{{_currency($row->amount * $row->quantity)}}</td>
				</tr>
                <?php endforeach; ?>
				<tr>
					<td colspan='3' style='padding: 5px;padding-top:0; vertical-align: top;'>&nbsp;</td>
					<td style='padding: 5px;padding-top:0; vertical-align: top;'>&nbsp;</td>
					<td style='padding: 5px;padding-top:0; vertical-align: top;'>&nbsp;</td>
					<td style='padding: 5px;padding-top:0; vertical-align: top;text-align:right;'>&nbsp;</td>
				</tr>
				<tr><td colspan="6" style="border-bottom: 2px solid #f3f3f3;"></td></tr>
                <tr style="background: #f3f3f3; border-bottom: 1px solid #ddd; font-weight: bold;">
					<td colspan="5" style="text-align:right;padding: 5px; ">Product Costs: </td>
					<td style="text-align:right;padding: 5px; ">{{ _currency($order->subtotal - $order->logo_cost - $order->one_time_cost) }}</td>
                </tr>
				<tr style="background: #f3f3f3; border-bottom: 1px solid #ddd; font-weight: bold;">
					<td colspan="5" style="text-align:right;padding: 5px; ">Costs To Add Logo: </td>

					<td style="text-align:right;padding: 5px; ">
					   <?php echo _currency($order->logo_cost) ?>
					</td>
                </tr>
				<tr style="background: #f3f3f3; border-bottom: 1px solid #ddd; font-weight: bold;">
					<td colspan="5" style="text-align:right;padding: 5px; ">One Time Setup Fees: </td>

					<td style="text-align:right;padding: 5px; ">
					   {{ _currency($order->one_time_cost) }}
					</td>
                </tr>
				
				<tr style="background: #f3f3f3; border-bottom: 1px solid #ddd; font-weight: bold;">
					<td colspan="5" style="text-align:right;padding: 5px; ">Total (ex. VAT): </td>
					<td style="text-align:right;padding: 5px; ">
					  {{ _currency($order->subtotal) }}
					</td>
				</tr>
                <tr style="background: #f3f3f3; border-bottom: 1px solid #ddd; font-weight: bold;">
					<td colspan="5" style="text-align:right;padding: 5px; ">Discount: </td>
					<td style="text-align:right;padding: 5px; ">
					  - {{ _currency($order->discount) }}
					</td>
				</tr>
				<tr style="background: #f3f3f3; border-bottom: 1px solid #ddd; font-weight: bold;">
					<td colspan="5" style="text-align:right;padding: 5px; ">VAT ({{$order->tax_percentage}}%): </td>
					<td style="text-align:right;padding: 5px; ">
					  {{ $order->tax ? _currency($order->tax) : _currency(0) }}
					</td>
				</tr>
                {{-- <tr style="background: #f3f3f3; border-bottom: 1px solid #ddd; font-weight: bold;">
					<td colspan="5" style="text-align:right;padding: 5px; ">Shipping & Handling: </td>

					<td style="text-align:right;padding: 5px; ">
					   &pound; fixed shipping charges
					</td>
				</tr> --}}
				
				<tr style="background: #f3f3f3; border-bottom: 1px solid #ddd; font-weight: bold;">
					<td colspan="5" style="text-align:right;padding: 5px;color:#ee2761;">Grand Total: </td>

					<td style="text-align:right;padding: 5px;color:#ee2761; ">
					    <?php echo $order->total_amount ? _currency($order->total_amount) : _currency(0) ?>
					</td>
				</tr>
				<tr><td colspan="6"><br/><br/></td></tr>
                    <tr><td colspan="6" style="font-size: 12px;text-align:center;background-color:#EAEAEA;">Thank you, <b>Pinders Workwear</b></td></tr>
                </table>
            </div>