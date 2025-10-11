<div class="order_review">
                    <div class="mb-20">
                        <h4>Your Orders</h4>
                    </div>
                    <div class="table-responsive order_table">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th colspan="2">Product</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-if="cart && cart.length > 0" v-for="c in cart">
                                    <td class="image product-thumbnail" width="25%"><img :src="getImagePath(c.image)" alt="#"></td>
                                    <td  width="60%">
                                        <h5><a :href="'/' + c.slug">@{{c.title}}</a></h5> <span class="product-qty">x @{{c.quantity && c.quantity > 0 ? c.quantity : ``}}</span>
                                        <p class="font-xs">SKU: @{{c.sku_number}}, Size: @{{c.size_title}}, Color: @{{c.color}}</p>
                                        <span class="text-danger" v-if="c.customization && c.customization.length > 0">@{{ c.customization.length }} customization added worth £@{{(c.quantity * getCustomizationCost(c.customization)).toFixed(2)}}.</span>
                                    </td>
                                    <td  width="15%">£@{{c.quantity && c.quantity > 0 ? (c.quantity*c.price).toFixed(2) : ``}}</td>
                                </tr>
                                <tr>
                                    <td colspan="2">Product Costs</td>
                                    <td>£@{{formatMoney(calculate().product_cost)}}</td>
                                </tr>
								<tr v-if="calculate().logo_cost > 0">
                                    <td colspan="2">Customization Cost</td>
                                    <td>£@{{formatMoney(calculate().logo_cost)}}</td>
                                </tr>
                                <tr v-if="calculate().logo_discount > 0">
                                    <td colspan="2">Logo Discount <small v-if="calculate().applied_logo_discount > 0" style="color:#ee2761">@{{ `(${calculate().applied_logo_discount} logo(s))`}}</small>:</td>
                                    <td><b style="color:#ee2761">- £@{{formatMoney(calculate().logo_discount)}}</b></td>
                                </tr>
								<tr v-if="calculate().oneTimeCost > 0">
                                    <td colspan="2">One Time Setup Fees <div v-html="renderOneTimeFeeHtml()"></div></td>
                                    <td>£@{{formatMoney(calculate().oneTimeCost)}}</td>
                                </tr>
                                <tr class="">
                                    <td colspan="3"><hr /></td>
                                </tr>
                                <tr>
                                    <td colspan="2">Subtotal:</td>
                                    <td>£@{{formatMoney(calculate().subtotal)}}</td>
                                </tr>
								<tr  v-if="calculate().discount > 0">
                                    <td colspan="2">
                                        Discount <small v-if="appliedCoupon">(@{{ appliedCoupon.title }} | @{{ appliedCoupon.coupon_code }})</small>
                                    </td>
                                    <td><b style="color:#ee2761">- £@{{formatMoney(calculate().discount)}}</b></td>
                                </tr>
                                <tr  v-if="freeDelivery()">
                                    <td colspan="2">Delivery</td>
                                    <td><b style="color:#ee2761">Free</b></td>
                                </tr>
                                <tr>
                                    <td colspan="2">VAT (@{{gstTax}}%):</td>
                                    <td>£@{{formatMoney(calculate().tax)}}</td>
                                </tr>
                                <tr>
                                    <th>Shipping & Handling Charges</th>
                                    <td colspan="2">
                                        <div class="shipping_option">
                                            <div class="custome-radio">
                                                <input class="form-check-input" required="" type="radio" name="shipping_option" value="parcelforce" id="exampleRadios1a" checked="" @change="handleShipping">
                                                <label class="form-check-label" for="exampleRadios1a" data-bs-toggle="collapse" data-target="#parcellavbel"  aria-controls="parcellavbel">{{_currency($settings['shipping_cost_parcelforce'])}} - Ship to Address by Parcel Force</label>
                                                <div class="form-group collapse in" id="parcellavbel">
                                                    <p class="text-muted mt-5"></p>
                                                </div>
                                            </div>
                                            <div class="custome-radio">
                                                <input class="form-check-input" required="" type="radio" name="shipping_option" id="dpd" checked=""  value="dpd"@change="handleShipping">
                                                <label class="form-check-label" for="dpd" data-bs-toggle="collapse" data-target="#dpdlabel"  aria-controls="dpdlabel">{{_currency($settings['shipping_cost_dpd'])}} - Ship to Address by DPD</label>
                                                <div class="form-group collapse in" id="dpdlabel">
                                                    <p class="text-muted mt-5"></p>
                                                </div>
                                            </div>
                                            <div class="custome-radio">
                                                <input class="form-check-input" required="" type="radio" name="shipping_option" id="exampleRadios2a" checked="" value="Ship to School"@change="handleShipping">
                                                <label class="form-check-label" for="exampleRadios2a" data-bs-toggle="collapse" data-target="#shiptoschool"  aria-controls="shiptoschool">£0.00 - Ship to School</label>
                                                <div class="form-group collapse in" id="shiptoschool">
                                                    <p class="text-muted mt-5"></p>
                                                </div>
                                            </div>
                                            @foreach($shops as $k => $s)
                                            <div class="custome-radio">
                                                <input class="form-check-input" required="" type="radio" name="shipping_option" id="exampleRadios3a{{$k}}" @change="handleShipping" checked=""  value="Collect From {{$s->name}}">
                                                <label class="form-check-label" for="exampleRadios3a{{$k}}" data-bs-toggle="collapse" data-target="#collectfromaston{{$k}}" aria-controls="collectfromaston{{$k}}">£0.00 - Collect From {{$s->name}}</label>
                                                <div class="form-group collapse in" id="collectfromaston">
                                                    <p class="text-muted mt-5">Monday to Friday (9am-5pm)</p>
                                                </div>
                                            </div>
                                            @endforeach
                                        </div>
                                            
                                    
                                    </td>
                                </tr>
                                <tr>
                                    <th>Total</th>
                                    <td colspan="2" class="product-subtotal"><span class="font-xl text-brand fw-900">£@{{formatMoney(calculate().total)}}</span></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="bt-1 border-color-1 mt-30 mb-30"></div>
                    <div class="payment_method">
                        <div class="mb-25">
                            <h5>Payment</h5>
                        </div>
                        <div class="payment_option">
                            <div class="custome-radio">
                                <input class="form-check-input" required="" type="radio" name="payment_option" id="exampleRadios3" checked="">
                                <label class="form-check-label" for="exampleRadios3" data-bs-toggle="collapse" data-target="#bankTranfer" aria-controls="bankTranfer">Direct Bank Transfer</label>
                                <div class="form-group collapse in" id="bankTranfer">
                                    <p class="text-muted mt-5">There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration. </p>
                                </div>
                            </div>
                            <div class="custome-radio">
                                <input class="form-check-input" required="" type="radio" name="payment_option" id="exampleRadios4" checked="">
                                <label class="form-check-label" for="exampleRadios4" data-bs-toggle="collapse" data-target="#checkPayment" aria-controls="checkPayment">Check Payment</label>
                                <div class="form-group collapse in" id="checkPayment">
                                    <p class="text-muted mt-5">Please send your cheque to Store Name, Store Street, Store Town, Store State / County, Store Postcode. </p>
                                </div>
                            </div>
                            <div class="custome-radio">
                                <input class="form-check-input" required="" type="radio" name="payment_option" id="exampleRadios5" checked="">
                                <label class="form-check-label" for="exampleRadios5" data-bs-toggle="collapse" data-target="#paypal" aria-controls="paypal">Paypal</label>
                                <div class="form-group collapse in" id="paypal">
                                    <p class="text-muted mt-5">Pay via PayPal; you can pay with your credit card if you don't have a PayPal account.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <a href="#" v-on:click="submit" class="btn btn-fill-out btn-block mt-30"><i class="fa fa-spin fa-spinner" v-if="saving"></i> Place Order</a>
                </div>