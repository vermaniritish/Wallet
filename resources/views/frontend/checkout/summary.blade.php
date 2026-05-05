<?php 
use App\Models\Admin\Settings;
$gstTax = Settings::get('gst'); ?>
<div class="order_review">
    <div class="mb-20">
        <h4>Your Orders</h4>
    </div>
    <div class="table-responsive order_table" style="overflow: unset;">
        <table class="table">
            <thead>
                <tr>
                    <th colspan="2">Product</th>
                    <th>VAT</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                <tr v-if="cart && cart.length > 0" v-for="c in cart">
                    <td class="image product-thumbnail" width="25%"><img :src="getImagePath(c.image)" alt="#"></td>
                    <td  width="60%">
                        <h5><a :href="'/' + c.slug">@{{c.title}}</a></h5> <br /><span class="product-qty"> @{{c.quantity && c.quantity > 0 ? c.quantity : ``}} x £@{{ c.price }}</span>
                        <p class="font-xs">SKU: @{{c.sku_number}}, Size: @{{c.size_title}}, Length: @{{c.length}}, Color: @{{c.color}}</p>
                        <div class="font-xs" v-if="c.customization && c.customization.length > 0">
                            <span class="position-relative popover-block">
                                <span 
                                    class="text-danger font-xs" 
                                    
                                >@{{ c.customization.length }} customization added worth £@{{(c.quantity * getCustomizationCost(c.customization)).toFixed(2)}}.</span>
                                <div v-if="c.customization && c.customization.length > 0" class="popover bs-popover-auto fade show" data-popper-placement="right" role="tooltip" id="popover995992" style="position: absolute; top: -46px; right: -80%; bottom: unset; left: unset; margin: 0px; display: none;">
                                    <div class="popover-arrow" style="position: absolute; transform: translate(0px, 47px);"></div>
                                    <h3 class="popover-header">Customization</h3>
                                    <div class="popover-body pt-2">
                                        <ul>
                                            <li v-for="l in c.customization" style="border-bottom: 1px solid #eee;padding: 5px 0;">
                                                <span class="text-muted">@{{ l.title }} | @{{ formatMoney(l.cost) }}</span><br />
                                                <strong>@{{ l.initial }}</strong>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </span>
                        </div>
                        <div v-else-if="c.logo" v-html="renderLogoInfo(c)"></div>
                        <div v-if="offerPrice(c).description" class="pro-details-brand"><span><b>Offer Applied:</b><span class="in-stock text-danger ml-5"> @{{offerPrice(c).description}}</span><span></div>
                        <p v-if="c.non_exchange == 1 || c.non_exchange == '1'" class="font-xs" style="color: rgb(209, 0, 31);"> Made to order only. This is a Non-Exchangeable &amp; Non-Refundable product.</p>
                    </td>
                    <td>
                        <span v-if="(c.vat*1 > 0) && offerPrice(c).price < (c.quantity * c.price)">£@{{( ( (offerPrice(c).price)*gstVal()/100 ).toFixed(2) )}}</span>
                        <span v-else-if="(c.vat*1 > 0)">£@{{( ( (c.price*c.quantity)*gstVal()/100 ).toFixed(2) )}}</span>
                        <span v-else>£0.00</span>
                    </td>
                    <td  width="15%">
                        <span class="old-price" v-if="offerPrice(c).price < (c.quantity * c.price)">£@{{( (c.quantity * offerPrice(c).price) + ( (offerPrice(c).price)*gstVal()/100 ) ).toFixed(2)}}</span>
                        <span v-else>
£@{{
    (
        (c.quantity * c.price) +
        ((c.vat * 1 > 0) ? ((c.price * c.quantity) * gstVal() / 100) : 0)
    ).toFixed(2)
}}
</span>
                        <span class="discount-price" v-if="offerPrice(c).price < (c.quantity * c.price)">£@{{(offerPrice(c).price).toFixed(2)}}</span>
                    </td>
                </tr>
                <tr>
                    <td colspan="3">Product Costs</td>
                    <td>£@{{formatMoney(calculate().product_cost)}}</td>
                </tr>
                <tr v-if="calculate().logo_cost > 0">
                    <td colspan="3">Customization Cost</td>
                    <td>£@{{formatMoney(calculate().logo_cost)}}</td>
                </tr>
                <tr v-if="calculate().logo_discount > 0">
                    <td colspan="3">Logo Discount <small v-if="calculate().applied_logo_discount > 0" style="color:#ee2761">@{{ `(${calculate().applied_logo_discount} logo(s))`}}</small>:</td>
                    <td><b style="color:#ee2761">- £@{{formatMoney(calculate().logo_discount)}}</b></td>
                </tr>
                <tr v-if="calculate().oneTimeCost > 0">
                    <td colspan="3">One Time Setup Fees 
                        <!-- <div v-html="renderOneTimeFeeHtml()"></div> -->
                    </td>
                    <td>£@{{formatMoney(calculate().oneTimeCost)}}</td>
                </tr>
                <tr class="">
                    <td colspan="4"><hr /></td>
                </tr>
                <tr>
                    <td colspan="3">Subtotal:</td>
                    <td>£@{{formatMoney(calculate().subtotal)}}</td>
                </tr>
                <tr  v-if="calculate().discount > 0">
                    <td colspan="3">
                        Discount <small v-if="appliedCoupon">(@{{ appliedCoupon.title }} | @{{ appliedCoupon.coupon_code }})</small>
                    </td>
                    <td><b style="color:#ee2761">- £@{{formatMoney(calculate().discount)}}</b></td>
                </tr>
                <tr  v-if="freeDelivery()">
                    <td colspan="3">Delivery</td>
                    <td><b style="color:#ee2761">Free</b></td>
                </tr>
                <tr>
                    <td colspan="3">VAT (@{{gstTax}}%):</td>
                    <td>£@{{formatMoney(calculate().tax)}}</td>
                </tr>
                <tr>
                    <th width="33%">Shipping & Handling Charges</th>
                    <td colspan="3">
                        <div class="shipping_option">
                            <div class="custome-radio" v-if="parcelforceEnable">
                                <input class="form-check-input" required="" type="radio" name="shipping_option" value="parcelforce" id="exampleRadios1a" @change="handleShipping">
                                <label class="form-check-label" for="exampleRadios1a" data-bs-toggle="collapse" data-target="#parcellavbel"  aria-controls="parcellavbel">{{_currency($settings['shipping_cost_parcelforce'])}} - Ship to Address by Parcel Force</label>
                                <div class="form-group collapse in" id="parcellavbel">
                                    <p class="text-muted mt-5"></p>
                                </div>
                            </div>
                            <div class="custome-radio"  v-if="dpdEnable">
                                <input class="form-check-input" required="" type="radio" name="shipping_option" id="dpd" value="dpd" @change="handleShipping">
                                <?php
                                $shipping = $settings['shipping_cost_dpd'];
                                $gstTax = 20;
                                $base = $shipping / (1 + ($gstTax / 100));
                                $shiptax  = $shipping - $base;
                                ?>
                                <label class="form-check-label" for="dpd" data-bs-toggle="collapse" data-target="#dpdlabel"  aria-controls="dpdlabel">{{_currency($shipping)}} - Ship to Address by DPD including VAT {{ _currency($shiptax) }}</label>
                                <div class="form-group collapse in" id="dpdlabel">
                                    <p class="text-muted mt-5"></p>
                                </div>
                            </div>
                            <div class="custome-radio">
                                <input class="form-check-input" required="" type="radio" name="shipping_option" id="exampleRadios2a" alue="Ship to School"@change="handleShipping">
                                <label class="form-check-label" for="exampleRadios2a" data-bs-toggle="collapse" data-target="#shiptoschool"  aria-controls="shiptoschool">£0.00 - Ship to School</label>
                                <div class="form-group collapse in" id="shiptoschool">
                                    <p class="text-muted mt-5"></p>
                                </div>
                            </div>
                            <template v-if="!isSchoolUniform || shopsAllowed.length > 0">
                            @foreach($shops as $k => $s)
                            <p>{{ $s->slug }}</p>
                            <div class="custome-radio" v-if="!isSchoolUniform || shopsAllowed.includes(`{{ $s->slug }}`)">
                                <input class="form-check-input" required="" type="radio" name="shipping_option" id="exampleRadios3a{{$k}}" @change="handleShipping" value="Collect From {{$s->name}}">
                                <label class="form-check-label" for="exampleRadios3a{{$k}}" data-bs-toggle="collapse" data-target="#collectfromaston{{$k}}" aria-controls="collectfromaston{{$k}}">£0.00 - Collect From {{$s->name}}</label>
                                <div class="form-group collapse in" id="collectfromaston">
                                    <p class="text-muted mt-5">Monday to Friday (9am-5pm)</p>
                                </div>
                            </div>
                            @endforeach
                            </template>
                        </div>
                    </td>
                </tr>
                <tr v-if="walletAmount > 0">
                    <th>Payment Method</th>
                    <td colspan="2">
                        <div class="shipping_option">
                            <div class="custome-radio">
                                <input class="form-check-input" required="" type="radio" name="payment_method" value="wallet" id="wallet-radio" @change="applyPayMethod('wallet')">
                                <label class="form-check-label" for="wallet-radio">Wallet</label>
                            </div>
                            <div class="custome-radio">
                                <input class="form-check-input" required="" type="radio" name="payment_method" value="paypal" id="paypal-radio" @change="applyPayMethod('paypal')">
                                <label class="form-check-label" for="paypal-radio">Paypal</label>
                            </div>
                        </div>
                    </td>
                </tr>
                <tr>
                    <th>Total</th>
                    <td colspan="2" class="product-subtotal"><span class="font-xl text-brand fw-900">£@{{formatMoney(calculate().total)}}</span></td>
                </tr>
                <tr v-if="walletAmount > 0">
                    <td>Wallet (Balance: <span class="text-brand">-£@{{ (walletAmount)}}</span>)</td>
                    <td>-£@{{formatMoney(calculate().wallet_applied)}}</td>
                </tr>
                <tr v-if="walletAmount > 0">
                    <td>Pay Total</td>
                    <td colspan="2" class="product-subtotal"><span class="font-xl text-brand fw-900">£@{{formatMoney(calculate().total - calculate().wallet_applied)}}</span></td>
                </tr>
            </tbody>
        </table>
    </div>
    <div class="bt-1 border-color-1 mt-30 mb-30"></div>
    <!-- <div class="payment_method">
        <div class="mb-25">
            <h5>Payment</h5>
        </div>
    </div> -->
    <a v-if="calculate().total - calculate().wallet_applied <= 0" href="javascript:;" v-on:click="submit" class="btn btn-fill-out btn-block w-100"><i class="fa fa-spin fa-spinner" v-if="saving"></i> Place Order</a>
    <div :class="calculate().total - calculate().wallet_applied > 0 ? `` : `d-none`">
        <div id="paypal-button-container"></div>
    </div>
</div>