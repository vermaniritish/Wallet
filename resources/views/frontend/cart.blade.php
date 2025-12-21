@extends('layouts.frontendlayout')
@section('content')
<div class="page-header breadcrumb-wrap">
    <div class="container">
        <div class="breadcrumb">
            <a href="{{url('/)}}" rel="nofollow">Home</a>
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
                                    <span>£@{{(c.quantity * c.price).toFixed(2)}}</span>
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
            </div>
        </div>
    </div>
</section>
@endsection
