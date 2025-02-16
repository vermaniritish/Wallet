@extends('layouts.frontendlayout')
@section('content')
  <!-- cart section start -->
        <section class="cart__section section--padding" id="cart-page">
            <div class="container-fluid">
                <div class="cart__section--inner">
                    <form action="#"> 
                        <h2 class="cart__title mb-40">Shopping Cart</h2>
                        <div class="row">
                            <div class="col-lg-8">
                                <div class="cart__table">
                                    <table class="cart__table--inner">
                                        <thead class="cart__table--header">
                                            <tr class="cart__table--header__items">
                                                <th class="cart__table--header__list">Product</th>
                                                <th class="cart__table--header__list">Price</th>
                                                <th class="cart__table--header__list">Quantity</th>
                                                <th class="cart__table--header__list">Total</th>
                                            </tr>
                                        </thead>
                                        <tbody class="cart__table--body">
                                            <tr class="cart__table--body__items" v-if="cart && cart.length > 0" v-for="c in cart">
                                                <td class="cart__table--body__list">
                                                    <div class="cart__product d-flex align-items-center">
                                                        <button class="cart__remove--btn" aria-label="search button" type="button" v-on:click="remove(c.id)">
                                                            <svg fill="currentColor" xmlns="http://www.w3.org/2000/svg"  viewBox="0 0 24 24" width="16px" height="16px"><path d="M 4.7070312 3.2929688 L 3.2929688 4.7070312 L 10.585938 12 L 3.2929688 19.292969 L 4.7070312 20.707031 L 12 13.414062 L 19.292969 20.707031 L 20.707031 19.292969 L 13.414062 12 L 20.707031 4.7070312 L 19.292969 3.2929688 L 12 10.585938 L 4.7070312 3.2929688 z"/></svg>
                                                        </button>
                                                        <div class="cart__thumbnail">
                                                            <a :href="'/' + c.slug"><img class="border-radius-5" :src="getImagePath(c.image)" :alt="c.title"></a>
                                                        </div>
                                                        <div class="cart__content">
                                                            <h4 class="cart__content--title"><a :href="'/' + c.slug">@{{ c.title }}</a></h4>
                                                            <span class="cart__content--variant">COLOR: @{{c.color}}</span>
                                                            <span class="cart__content--variant">SIZE: @{{c.size_title}}</span>
                                                            <span class="cart__content--variant" style="color:#ee2761" v-html="renderLogoInfo(c)"></span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="cart__table--body__list">
                                                    <span class="cart__price">£@{{c.price}}</span>
                                                </td>
                                                <td class="cart__table--body__list">
                                                    <div class="quantity__box">
                                                        <button type="button" class="quantity__value quickview__value--quantity decrease" aria-label="quantity value" value="Decrease Value" v-on:click="decrement(c.id)">-</button>
                                                        <label>
                                                            <input type="number" class="quantity__number quickview__value--number" readonly :value="c.quantity && c.quantity > 0 ? c.quantity : ``"/>
                                                        </label>
                                                        <button type="button" class="quantity__value quickview__value--quantity increase" aria-label="quantity value" value="Increase Value" v-on:click="increment(c.id)">+</button>
                                                    </div>
                                                </td>
                                                <td class="cart__table--body__list">
                                                    <span class="cart__price end">£@{{(c.quantity * c.price).toFixed(2)}}</span>
                                                </td>
                                            </tr>
                                            <tr v-if="!cart || cart.length < 1">
                                                <td colspan="5" class="cart__table--body__list"><p class="text-center py-5">Your cart is empty. No product is availble.</p></td>
                                            </tr>
                                        </tbody>
                                    </table> 
                                    <div class="continue__shopping d-flex justify-content-between">
                                        <a class="continue__shopping--link" href="{{url('/')}}">Continue shopping</a>
                                        <button class="continue__shopping--clear" type="button" v-on:click="clearCart">Clear Cart</button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="cart__summary border-radius-10">
                                    <div class="coupon__code mb-30">
                                        <h3 class="coupon__code--title">Coupon</h3>
                                        <p class="coupon__code--desc">Enter your coupon code if you have one.</p>
                                        <div class="coupon__code--field d-flex">
                                            <label>
                                                <input class="coupon__code--field__input border-radius-5" placeholder="Coupon code" type="text" v-model="coupon" :disabled="appliedCoupon ? true : false">
                                            </label>
                                            <button class="coupon__code--field__btn primary__btn" type="button" v-on:click="removeCoupon" v-if="appliedCoupon"><i class="fa fa-times"></i> Remove</button>
                                            <button class="coupon__code--field__btn primary__btn" type="button" v-on:click="applyCoupon" v-else>Apply Coupon</button>
                                        </div>
                                        <p class="text-danger" v-if="couponError">@{{ couponError }}</p>
                                    </div>
                                    <div class="cart__note mb-20">
                                        <h3 class="cart__note--title">Note</h3>
                                        <p class="cart__note--desc">Add special instructions for your seller...</p>
                                        <textarea class="cart__note--textarea border-radius-5" v-model="note"></textarea>
                                    </div>
                                    <div class="cart__summary--total mb-20">
                                        <table class="cart__summary--total__table">
                                            <tbody>
                                                <tr class="cart__summary--total__list">
                                                    <td class="cart__summary--total__title text-left">Product Costs</td>
                                                    <td class="cart__summary--amount text-right">£@{{calculate().product_cost}}</td>
                                                </tr>
                                                <tr class="cart__summary--total__list" v-if="calculate().logo_cost > 0">
                                                    <td class="cart__summary--total__title text-left">Costs To Add Logo</td>
                                                    <td class="cart__summary--amount text-right">£@{{calculate().logo_cost}}</td>
                                                </tr>
                                                <tr class="cart__summary--total__list" v-if="calculate().logo_discount > 0">
                                                    <td class="cart__summary--total__title text-left">Logo Discount <small v-if="calculate().applied_logo_discount > 0" style="color:#ee2761">@{{ `(${calculate().applied_logo_discount} logo(s))`}}</small>:</td>
                                                    <td class="cart__summary--amount text-right"><b style="color:#ee2761">- £@{{calculate().logo_discount}}</b></td>
                                                </tr>
                                                <tr class="cart__summary--total__list" v-if="calculate().oneTimeCost > 0">
                                                    <td class="cart__summary--total__title text-left">One Time Setup Fees</td>
                                                    <td class="cart__summary--amount text-right">£@{{calculate().oneTimeCost}}</td>
                                                </tr>
                                                <tr class="">
                                                    <td colspan="2"><hr /></td>
                                                </tr>
                                                <tr class="cart__summary--total__list">
                                                    <td class="cart__summary--total__title text-left">Subtotal: </td>
                                                    <td class="cart__summary--amount text-right">£@{{calculate().subtotal}}</td>
                                                </tr>
                                                <tr class="cart__summary--total__list"  v-if="calculate().discount > 0">
                                                    <td class="cart__summary--total__title text-left">DISCOUNT</td>
                                                    <td class="cart__summary--amount text-right"><b style="color:#ee2761">- £@{{calculate().discount}}</b></td>
                                                </tr>
                                                <tr class="cart__summary--total__list"  v-if="freeDelivery()">
                                                    <td class="cart__summary--total__title text-left">Delivery</td>
                                                    <td class="cart__summary--amount text-right"><b style="color:#ee2761">Free</b></td>
                                                </tr>
                                                <tr class="cart__summary--total__list">
                                                    <td class="cart__summary--total__title text-left">VAT (@{{gstTax}}%):</td>
                                                    <td class="cart__summary--amount text-right">£@{{calculate().tax}}</td>
                                                </tr>
                                                <tr class="cart__summary--total__list">
                                                    <td class="cart__summary--total__title text-left">GRAND TOTAL</td>
                                                    <td class="cart__summary--amount text-right">£@{{calculate().total}}</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="cart__summary--footer">
                                        <p class="cart__summary--footer__desc">Shipping & taxes calculated at checkout</p>
                                        <ul class="d-flex justify-content-between">
                                            <!-- <li><button class="cart__summary--footer__btn primary__btn cart" type="submit">Update Cart</button></li> -->
                                            <li><a class="cart__summary--footer__btn primary__btn checkout" href="{{url('/checkout')}}">Check Out</a></li>
                                        </ul>
                                    </div>
                                </div> 
                            </div>
                        </div> 
                    </form> 
                </div>
            </div>     
        </section>
        <!-- cart section end -->
@endsection