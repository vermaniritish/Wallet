<aside class="checkout__sidebar sidebar">
					<h2 class="section__header--title h3">Your Order Summary </h2>
                    <div class="cart__table checkout__product--table">
                        <table class="cart__table--inner">
                            <tbody class="cart__table--body">
                                <tr class="cart__table--body__items" v-if="cart && cart.length > 0" v-for="c in cart">
                                    <td class="cart__table--body__list">
                                        <div class="product__image two  d-flex align-items-center">
                                            <div class="product__thumbnail border-radius-5">
                                                <a :href="'/' + c.slug"><img class="border-radius-5" :src="getImagePath(c.image)" :alt="c.title"></a>
                                                <span class="product__thumbnail--quantity">@{{c.quantity && c.quantity > 0 ? c.quantity : ``}}</span>
                                            </div>
                                            <div class="product__description">
                                                <h3 class="product__description--name h4"><a :href="'/' + c.slug">@{{c.title}}</a></h3>
                                                <small class="">COLOR: @{{c.color}}</small>
                                                <small class="">SIZE: @{{c.size_title}}</small><br />
                                                <small style="color:#ee2761" v-html="renderLogoInfo(c)"></small>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="cart__table--body__list">
                                        <span class="cart__price">£@{{c.quantity && c.quantity > 0 ? (c.quantity*c.price).toFixed(2) : ``}}</span>
                                    </td>
                                    <td>
                                        <a href="javascript:;" v-on:click="remove(c.id)"><i class="fa fa-times text-danger"></i></a>
                                    </td>
                                </tr>
                            </tbody>
                        </table> 
                    </div>
                    
                    <div class="checkout__total">
                        <table class="checkout__total--table">
                            <tbody class="checkout__total--body">
                                <tr class="checkout__total--items">
                                    <td class="checkout__total--title text-left">Product Costs </td>
                                    <td class="checkout__total--amount text-right">£@{{formatMoney(calculate().product_cost)}}</td>
                                </tr>
								<tr class="checkout__total--items" v-if="calculate().logo_cost > 0">
                                    <td class="checkout__total--title text-left">Costs To Add Logo </td>
                                    <td class="checkout__total--amount text-right">£@{{formatMoney(calculate().logo_cost)}}</td>
                                </tr>
                                <tr class="checkout__total--items" v-if="calculate().logo_discount > 0">
                                    <td class="checkout__total--title text-left">Logo Discount <small v-if="calculate().applied_logo_discount > 0" style="color:#ee2761">@{{ `(${calculate().applied_logo_discount} logo(s))`}}</small>:</td>
                                    <td class="checkout__total--amount text-right"><b style="color:#ee2761">- £@{{formatMoney(calculate().logo_discount)}}</b></td>
                                </tr>
								<tr class="checkout__total--items" v-if="calculate().oneTimeCost > 0">
                                    <td class="checkout__total--title text-left">One Time Setup Fees </td>
                                    <td class="checkout__total--amount text-right">£@{{formatMoney(calculate().oneTimeCost)}}</td>
                                </tr>
                                <tr class="">
                                    <td colspan="2"><hr /></td>
                                </tr>
                                <tr class="checkout__total--items">
                                    <td class="checkout__total--title text-left">Subtotal:</td>
                                    <td class="checkout__total--amount text-right">£@{{formatMoney(calculate().subtotal)}}</td>
                                </tr>
								<tr class="checkout__total--items"  v-if="calculate().discount > 0">
                                    <td class="checkout__total--title text-left">Discount</td>
                                    <td class="checkout__total--amount text-right"><b style="color:#ee2761">- £@{{formatMoney(calculate().discount)}}</b></td>
                                </tr>
                                <tr class="checkout__total--items"  v-if="freeDelivery()">
                                    <td class="checkout__total--title text-left">Delivery</td>
                                    <td class="checkout__total--amount text-right"><b style="color:#ee2761">Free</b></td>
                                </tr>
                                <tr class="checkout__total--items">
                                    <td class="checkout__total--title text-left">VAT (@{{gstTax}}%):</td>
                                    <td class="checkout__total--amount text-right">£@{{formatMoney(calculate().tax)}}</td>
                                </tr>
                            </tbody>
                            <tfoot class="checkout__total--footer">
                                <tr class="checkout__total--footer__items">
                                    <td class="checkout__total--footer__amount checkout__total--footer__list text-left" style="color: var(--secondary-color);">Total </td>
                                    <td class="checkout__total--footer__amount checkout__total--footer__list text-right" style="color: var(--secondary-color);">£@{{formatMoney(calculate().total)}}</td>
                                </tr>
                            </tfoot>
                        </table>
						<br/>
						<div class="checkout__content--step__footer d-flex align-items-center">
                            <div id="paypal-button-container"></div>
							{{-- <a class="continue__shipping--btn primary__btn border-radius-5" href="javascript:;" v-on:click="submit"><i class="fa fa-spin fa-spinner" v-if="saving"></i> Pay Now</a>
							<a class="previous__link--content" href="{{url('/cart')}}">Return to cart</a> --}}
						</div>
                    </div>
                </aside>