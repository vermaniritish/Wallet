@extends('layouts.frontendlayout')
@section('content')
<div class="page-header breadcrumb-wrap">
    <div class="container">
        <div class="breadcrumb">
            <a href="{{url('/')}}" rel="nofollow">Home</a>
            <span></span> Shop
            <span></span> Your Cart
        </div>
    </div>
</div>
<section class="mt-50 mb-50" id="cart-page">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="table-responsive" style="overflow:unset">
                    <table class="table shopping-summery clean">
                        <thead>
                            <tr class="main-heading">
                                <th scope="col">Image</th>
                                <th scope="col">Name</th>
                                <th scope="col">Price</th>
                                <th scope="col">Quantity</th>
                                <th scope="col">Subtotal</th>
                                <th scope="col">Remove</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr  v-if="cart && cart.length > 0" v-for="c in cart">
                                <td class="image product-thumbnail">
                                    <img class="border-radius-5" :src="getImagePath(c.image)">
                                </td>
                                <td class="product-des product-name">
                                    <h5 class="product-name"><a :href="'/' + c.slug">@{{ c.title }}</a></h5>
                                    <p v-if="c.non_exchange == 1 || c.non_exchange == '1'" class="font-xs" style="color: rgb(209, 0, 31);"> Made to order only. This is a Non-Exchangeable &amp; Non-Refundable product.</p>
                                    <p class="font-xs">SKU: @{{c.sku_number}}<br> Size:  @{{c.size_title}}<br/> Color: @{{c.color}} </p>
                                    
                                    <div class="font-xs">
                                        <span class="position-relative popover-block">
                                            <span 
                                                class="text-danger font-xs" 
                                                v-if="c.customization && c.customization.length > 0"
                                            >@{{ c.customization.length }} customization added worth £@{{(c.quantity * getCustomizationCost(c.customization)).toFixed(2)}}.</span>
                                            <span 
                                                v-else-if="c.logo" 
                                                class="text-danger font-xs" style="color:#ee2761" 
                                                v-html="renderLogoInfo(c)"></span>

                                            <div v-if="c.customization && c.customization.length > 0" class="popover bs-popover-auto fade show" data-popper-placement="right" role="tooltip" id="popover995992" style="position: absolute; inset: 0px auto auto 0px; margin: 0px;bottom: unset;top: -50px;right: -80%;left: unset;display:none;">
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
                                </td>
                                <td class="price" data-title="Price"><span>£@{{c.price}}</span></td>
                                <td class="text-center" data-title="Stock">
                                    <div class="detail-qty border radius  m-auto">
                                        <a href="javascript:;" v-on:click="decrement(c.id)" class="qty-down"><i class="fi-rs-angle-small-down"></i></a>
                                        <span class="qty-val">@{{ c.quantity && c.quantity > 0 ? c.quantity : `` }}</span>
                                        <a href="javascript:;" v-on:click="increment(c.id)" class="qty-up"><i class="fi-rs-angle-small-up"></i></a>
                                    </div>
                                </td>
                                <td class="text-right" data-title="Cart">
                                    
                                    <span class="old-price" v-if="offerPrice(c).price < (c.quantity * c.price)">£@{{(c.quantity * c.price).toFixed(2)}}</span>
                                    <span v-else>£@{{(c.quantity * c.price).toFixed(2)}}</span>
                                    <span class="discount-price" v-if="offerPrice(c).price < (c.quantity * c.price)">£@{{(offerPrice(c).price).toFixed(2)}}</span>
                                </td>
                                <td class="action" data-title="Remove"><a href="javascript:;" v-on:click="remove(c.id)" class="text-muted"><i class="fi-rs-trash"></i></a></td>
                            </tr>
                            <tr v-if="!cart || cart.length < 1">
                                <td colspan="5" class="cart__table--body__list"><p class="text-center py-5">Your cart is empty. No product is availble.</p></td>
                            </tr>
                            <tr v-if="cart && cart.length > 0" >
                                <td colspan="6" class="text-end">
                                    <a href="#" v-on:click="clearCart" class="text-muted"> <i class="fi-rs-cross-small"></i> Clear Cart</a>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="divider center_icon mt-50 mb-50"><i class="fi-rs-fingerprint"></i></div>
                <div class="row mb-50">
                    <div class="col-lg-6 col-md-12">
                        
                        <div class="mb-30 mt-50">
                            <div class="heading_s1 mb-3">
                                <h4>Apply Coupon</h4>
                            </div>
                            <div class="total-amount">
                                <div class="left">
                                    <div class="coupon">
                                        <form action="#" target="_blank">
                                            <div class="form-row row justify-content-center">
                                                <div class="form-group col-lg-6">
                                                    <input class="font-medium" name="Coupon" placeholder="Enter Your Coupon" v-model="coupon" :disabled="appliedCoupon ? true : false">
                                                    <small class="text-danger" v-if="couponError">@{{couponError}}</small>
                                                </div>
                                                <div class="form-group col-lg-6">
                                                    <button type="button" class="btn  btn-sm btn-danger"  v-on:click="removeCoupon" v-if="appliedCoupon"><i class="fi-rs-cross-small"></i>  Remove</button>
                                                    <button type="button" class="btn  btn-sm" v-on:click="applyCoupon" v-else><i class="fi-rs-label mr-10"></i>Apply</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-12">
                        <textarea class="d-none cart__note--textarea border-radius-5" v-model="note"></textarea>
                        <div class="border p-md-4 p-30 border-radius cart-totals">
                            <div class="heading_s1 mb-3">
                                <h4>Cart Totals</h4>
                            </div>
                            <div class="table-responsive">
                                <table class="table">
                                    <tbody>
                                        <tr v-if="calculate().logo_cost > 0">
                                            <td class="cart_total_label">Product Costs</td>
                                            <td class="cart_total_amount"><span class="font-lg fw-900 text-brand">£@{{formatMoney(calculate().product_cost)}}</span></td>
                                        </tr>
                                        <tr v-if="calculate().logo_cost > 0">
                                            <td class="cart_total_label">Customization Cost</td>
                                            <td class="cart_total_amount"><span class="font-lg fw-900 text-brand">£@{{formatMoney(calculate().logo_cost)}}</span></td>
                                        </tr>
                                        <tr v-if="calculate().logo_discount > 0">
                                            <td class="cart_total_label">Customization Discount <br /><small v-if="calculate().applied_logo_discount > 0" style="color:#ee2761">@{{ `(${calculate().applied_logo_discount} logo(s))`}}</small></td>
                                            <td class="cart_total_amount"><span class="font-lg fw-900 text-brand">- £@{{formatMoney(calculate().logo_discount)}}</span></td>
                                        </tr>
                                        <tr>
                                            <td class="cart_total_label">Subtotal</td>
                                            <td class="cart_total_amount"><span class="font-lg fw-900 text-brand">£@{{formatMoney(calculate().subtotal)}}</span></td>
                                        </tr>
                                        <tr v-if="calculate().discount > 0">
                                            <td class="cart_total_label">Discount</td>
                                            <td class="cart_total_amount"><span class="font-lg fw-900 text-brand">- £@{{formatMoney(calculate().discount)}}</span></td>
                                        </tr>
                                        <tr v-if="freeDelivery()">
                                            <td class="cart_total_label">Delivery</td>
                                            <td class="cart_total_amount"><span class="font-lg fw-900 text-brand">Free</span></td>
                                        </tr>
                                        <tr>
                                            <td class="cart_total_label">VAT (@{{gstTax}}%)</td>
                                            <td class="cart_total_amount"><span class="font-lg fw-900 text-brand">£@{{formatMoney(calculate().tax)}}</span></td>
                                        </tr>
                                        <tr>
                                            <td class="cart_total_label">GRAND TOTAL</td>
                                            <td class="cart_total_amount"><span class="font-lg fw-900 text-brand">£@{{formatMoney(calculate().total)}}</span></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="d-flex gap-2 align-items-center justify-content-between">
                                <a class="btn" href="{{url('/')}}"><i class="fi-rs-shopping-bag mr-10"></i>Continue Shopping</a>
                                <a href="{{url('/checkout')}}" class="btn "> <i class="fi-rs-box-alt mr-10"></i> Proceed To CheckOut</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection