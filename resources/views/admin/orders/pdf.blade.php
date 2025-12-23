<?php
use App\Models\Admin\Settings;
use Carbon\Carbon;
$currency = Settings::get('currency_symbol');
?>

<style>
    body { font-family: Helvetica, Arial, sans-serif; color: #122246; }
    .invoice-container { width: 100%; padding: 20px; border: 1px solid #ddd; }
    .title { font-size: 18px; font-weight: 700; text-align: center; margin: 10px 0; }
    .section-title { background: #f7eddd; padding: 6px 10px; font-weight: 700; font-size: 14px; border-bottom: 1px solid #c7a162; }
    .box { border: 1px solid #c7a162; margin-bottom: 15px; }
    table { width: 100%; border-collapse: collapse; font-size: 13px; }
    td, th { padding: 6px; vertical-align: top; }
    .item-row { border-bottom: 1px solid #eee; }
    .total-row { background: #f7eddd; font-weight: 700; border-top: 1px solid #c7a162; }
    .right { text-align: right; }
    .center { text-align: center; }
</style>

<div class="invoice-container">

    <!-- Header -->
    <table>
        <tr>
            <td style="width:70%;">
                @if($logo)
                    <img src="<?php echo public_path($logo) ?>" style="height: 50px;">
                @endif
            </td>
            <td style="width:30%; font-size: 12px;">
                <b>Pinders Schoolwear Ltd</b><br>
                Mansfield Road, Aston, Sheffield, S26 2BS<br>
                <b>Phone:</b> 0114 2513275<br>
                <b>Email:</b> info@pindersschoolwear.co.uk<br>
                <b>VAT:</b> GB251618808
            </td>
        </tr>
    </table>

    <div class="title">TAX INVOICE</div>

    <!-- Welcome Message -->
    <p style="padding-bottom: 20px;color: #122246;font-size:14px;">
        <b>Hello, {{$page->first_name}} {{$page->last_name}}</b><br><br>
        Thank you for your order. Once shipped, you will receive tracking details via email.<br>
        For queries, contact us at <b>info@pindersschoolwear.co.uk</b> or call <b>0114 2513275</b>.
        <?php
        $created = Carbon::parse($page->created);
        if ($created->between(Carbon::create($created->year, 7, 1), Carbon::create($created->year, 9, 30))) {
            echo '<p><b>Note: <span style="color: #ff568a">Orders placed during July, August & September can take up to 4 weeks to be processed.</span></b></p>';
        }
        ?>
    </p>
    <!-- Customer & Order Details -->
    <table>
        <tr>
            <td style="width:50%;">
                <table style="border:1px solid #c7a162;width: 100%; line-height: inherit; text-align: left;font-size: 14px">
                    <tr>
                        <td style="background-color:#f7eddd; padding:5px 8px;"><b>Customer Details:</b></td>
                    </tr>
                    <tr>
                        <td style="padding: 5px; vertical-align: top;">
                            {{$page->first_name}} {{$page->last_name}}<br/>
                            {{$page->address}}<br>
                            {{$page->area}}<br>
                            {{$page->city}}, {{$page->postcode}}<br>
                            {{$page->customer_phone}} | {{$page->customer_email}}<br><br/>
                            {{$page->note}}
                        </td>
                    </tr>
                </table>
            </td>

            <td style="width:50%;">
                <table style="border:1px solid #c7a162;width: 100%; line-height: inherit; text-align: left;font-size: 14px">
                    <tr>
                        <td style="background-color:#f7eddd; padding:5px 8px;"><b>Order Details:</b></td>
                    </tr>
                    <tr>
                        <td style="padding: 5px; vertical-align: top;">
                            Invoice Date: <b>{{_d($page->created)}}</b><br/>
                            Invoice Number: <b>{{$page->prefix_id}}</b><br/>
                            Order Placed at: <b>{{$page->shop_id ? ($page->shop ? $page->shop->name : 'Shop') : 'Website'}}</b><br>
                            @if($page->staff_id && $page->staff)
                            Served by: {{$page->staff->first_name}} {{$page->staff->last_name}}<br>
                            @endif
                            Order Type: <b>{{ $page->shipping_gateway }}</b><br>
                            
                            @if($page->shop_id)
                            Payment Mode: <b>{{$page->cash_paid > 0 ? 'Cash' : ''}} {{$page->card_paid > 0 ? 'Card' : ''}}</b><br>
                            @else
                            Payment Mode: <b>PAYPAL</b>
                            @endif
                            
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    <!-- Order Items -->
    <div class="box">
        <div class="section-title">Order Items</div>

        <table style="border:1px solid #c7a162;">
            <tr style="font-weight:700; background:#f7eddd;">
                <td style="width:45%;">Item</td>
                <td style="width:12%;">Color</td>
                <td style="width:12%;">Size</td>
                <td class="right" style="width:8%;">Qty</td>
                <td class="right" style="width:12%;">Price</td>
                <td class="right" style="width:12%;">Subtotal</td>
            </tr>

            @foreach($listing->items() as $row)
            <?php $row->logo_data = $row->logo_data ? json_decode($row->logo_data, true) : []; ?>

            <tr class="item-row">
                <td>
                    <table>
                        <tr>
                            @if($row->image && $row->image)
                            <td style="width:25%">
                            <img src="{{ public_path($row->image) }}" style="height: 60px" />
                            </td>
                            @endif
                            <td  style="width:75%">
                                {{$row->product->school->name ?? ''}},
                                {{$row->product_title}},
                                {{$row->sku_number}}

                                @if($row->logo_data)
                                    @foreach($row->logo_data as $logoD)
                                        <table style="border:1px solid #c7a162;width: 100%; line-height: inherit; text-align: left;font-size:12px;">
                                            @if(isset($logoD['title']) && $logoD['title'])
                                            <tr style="background: #f7eddd; border-bottom: 1px solid #ddd; font-weight: bold;">
                                                <td style="width:35%;padding: 5px; vertical-align: top; font-weight: bold;">
                                                {{$logoD['title']}}: {{$logoD['initial']}}
                                                </td>
                                            </tr>
                                            @else
                                            <tr class="table-borderless">
                                                <td>
                                                    <div class="row">
                                                        @if(isset($logoD['already_uploaded']) && $logoD['already_uploaded'])
                                                        <div class="col-sm-2">
                                                            <p class="text-danger">Pinder has already my logo.</p>
                                                        </div>
                                                        @else			
                                                        <div class="col-sm-2">
                                                            <div class="" style="border: 1px solid #ddd;">
                                                                <?php if(trim($logoD['image'])):?>
                                                                <img src="{{ $logoD['image'] }}" style="width: 40px;height:40px;">
                                                                <?php endif; ?>
                                                            </div>
                                                        </div>
                                                        @endif
                                                        <div class="col-sm-5">
                                                            <span class="text-muted">Text:</span> <?php echo $logoD['text'] ?><br />
                                                            <span class="text-muted">Category:</span> <?php echo $logoD['category'] ?><br />
                                                            <span class="text-muted">Position:</span> <?php echo $logoD['postion'] ?><br />
                                                        </div>
                                                        <div class="col-sm-4">
                                                            <span class="text-muted">Size:</span> <?php echo $row->size_title ?><br />
                                                            <span class="text-muted">Color:</span> <?php echo $row->color ?><br />
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                            @endif
                                        </table>
                                    @endforeach
                                @endif

                                @if($row->non_exchange)
                                    <span style="color:#ff0000;font-size:11px;">
                                        * Made to order — Non-Exchangeable & Non-Refundable.
                                    </span>
                                @endif
                            </td>
                        </tr>
                    </table>
                </td>

                <td>{{$row->color}}</td>
                <td>{{$row->size_title}}</td>
                <td class="right">
                    {{$row->quantity}}
                    @if($row->logo_data)
                    @foreach($row->logo_data as $logoD)
                    <table style="border:1px solid #c7a162;width: 100%; line-height: inherit; text-align: left;font-size:12px;">
                        <tr style="background: #f7eddd; border-bottom: 1px solid #ddd; font-weight: bold;">
                            <td style="width:35%;padding: 5px; vertical-align: top; text-align:right;">
                            {{ $row->quantity }} 
                            </td>
                        </tr>
                    </table>
                    @endforeach
                    @endif

                </td>
                <td class="right">{{$currency . $row->amount}}

                    @if($row->logo_data)
                    @foreach($row->logo_data as $logoD)
                    <table style="border:1px solid #c7a162;width: 100%; line-height: inherit; text-align: left;font-size:12px;">
                        <tr style="background: #f7eddd; border-bottom: 1px solid #ddd; font-weight: bold;">
                            <td style="width:35%;padding: 5px; vertical-align: top; text-align:right;">
                            {{ $currency . (isset($logoD['cost']) ? $logoD['cost'] : (isset($logoD['price']) ? $logoD['price'] : "") ) }}
                            </td>
                        </tr>
                    </table>
                    @endforeach
                    @endif
                </td>
                <td class="right">
                    {{$currency . ($row->amount * $row->quantity)}}
                    @if($row->logo_data)
                    @foreach($row->logo_data as $logoD)
                    <table style="border:1px solid #c7a162;width: 100%; line-height: inherit; text-align: left;font-size:12px;">
                        <tr style="background: #f7eddd; border-bottom: 1px solid #ddd; font-weight: bold;">
                            <td style="width:35%;padding: 5px; vertical-align: top; text-align:right;">
                            {{$currency . ((isset($logoD['cost']) ? $logoD['cost'] : (isset($logoD['price']) ? $logoD['price'] : 0) ) * $row->quantity)}}
                            </td>
                        </tr>
                    </table>
                    @endforeach
                    @endif
                </td>
            </tr>

            @endforeach
        </table>
    </div>

    <!-- Totals -->
    <table>
		@if($page->logo_cost > 0)
        <tr class="total-row">
            <td class="right" style="width:80%;">Product Cost:</td>
            <td class="right">{{$currency.$page->subtotal}}</td>
        </tr>

        <tr class="total-row">
            <td class="right">Customization Cost:</td>
            <td class="right">{{$currency.$page->logo_cost}}</td>
        </tr>
		@endif

        <tr class="total-row">
            <td class="right">Subtotal:</td>
            <td class="right">{{$currency.($page->subtotal + $page->logo_cost)}}</td>
        </tr>

        @if($page->coupon)
        <?php $coupon = json_decode($page->coupon, true); ?>
        <tr class="total-row">
            <td class="right">Discount ({{$coupon['coupon_code']}}):</td>
            <td class="right">{{$currency.$page->discount}}</td>
        </tr>
        @endif

        <tr class="total-row">
            <td class="right">VAT ({{$page->tax_percentage}}%):</td>
            <td class="right">
                {{$currency.( ($page->tax ?? 0) + ($page->logo_tax ?? 0) )}}
            </td>
        </tr>
		@if($page->delivery_cost > 0)
        <tr class="total-row">
            <td class="right">Shipping Charges:</td>
            <td class="right">{{$currency.$page->delivery_cost}}</td>
        </tr>
		@endif
		<tr class="total-row">
            <td class="right">Total Amount:</td>
            <td class="right"><?php echo $page->total_amount ? $currency.($page->total_amount) : _currency(0) ?></td>
        </tr>
    </table>

    <!-- Footer -->
    <p class="center" style="font-size:12px; margin-top:20px; background:#f7eddd; padding:8px;">
        Thank you, <b>Pinders Schoolwear Ltd.</b>
    </p>

</div>
