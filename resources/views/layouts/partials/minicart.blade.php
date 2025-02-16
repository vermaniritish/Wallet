<div class="offCanvas__minicart active" v-if="open">
    <div class="minicart__header ">
        <div class="minicart__header--top d-flex justify-content-between align-items-center">
            <h2 class="minicart__title h3"> Shopping Cart</h2>
            <button class="minicart__close--btn" aria-label="minicart close button" v-on:click="initcart">
                <svg class="minicart__close--icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                    <path fill="currentColor" stroke="currentColor" stroke-linecap="round"
                        stroke-linejoin="round" stroke-width="32" d="M368 368L144 144M368 144L144 368" />
                </svg>
            </button>
        </div>
        <p class="minicart__header--desc">Clothing and fashion products are limited</p>
    </div>
    <div class="minicart__product">
        <div class="minicart__product--items d-flex" v-if="cart && cart.length > 0" v-for="c in cart">
            <div class="minicart__thumb">
                <a :href="'/' + c.slug"><img
                        :src="getImagePath(c.image)" :alt="c.title"></a>
            </div>
            <div class="minicart__text">
                <h3 class="minicart__subtitle h4"><a :href="'/' + c.slug">@{{c.title}}</a>
                </h3>
                <span class="color__variant"><b>Color:</b> @{{c.color}}</span><br />
                <span class="color__variant"><b>Size:</b> @{{c.size_title}}</span>
                <div class="minicart__price" v-if="offerPrice(c).haveOffer">
                    <span class="current__price">£@{{ (offerPrice(c).price) }}</span>
                    <span class="old__price">£@{{c.price}}</span>
                </div>
                <div class="minicart__price" v-else>
                    <span class="current__price">£@{{c.price}}</span>
                    <span class="old__price" v-if="c.sale_price && (c.sale_price*1) > 0">£@{{c.sale_price}}</span>
                </div>
                <div class="minicart__text--footer d-flex align-items-center">
                    <div class="quantity__box minicart__quantity">
                        <button type="button" class="quantity__value decrease" aria-label="quantity value"
                            value="" v-on:click="decrement(c.id)">-</button>
                        <label>
                            <input type="number" class="quantity__number" readonly :value="c.quantity && c.quantity > 0 ? c.quantity : ``"/>
                        </label>
                        <button type="button" class="quantity__value increase"
                            value="" v-on:click="increment(c.id)">+</button>
                    </div>
                    <button class="minicart__product--remove" v-on:click="remove(c.id)">Remove</button>
                </div>
            </div>
        </div>
        <div v-if="!cart || cart.length < 1"class="minicart__product--items d-flex">
            <p class="text-center py-5">Your cart is empty. No product is availble.</p>
        </div>
    </div>
    <div class="minicart__amount">
            <div class="minicart__amount_list d-flex justify-content-between">
                <span>Product Costs:</span>
                <span><b>£@{{calculate().product_cost}}</b></span>
            </div>
            <div class="minicart__amount_list d-flex justify-content-between" v-if="calculate().logo_cost > 0">
                <span>Costs To Add Logo:</span>
                <span><b>£@{{calculate().logo_cost}}</b></span>
            </div>
            <div class="minicart__amount_list d-flex justify-content-between" v-if="calculate().logo_discount > 0">
                <span class="discount-highlight">Logo Discount <small v-if="calculate().applied_logo_discount > 0" style="color:#ee2761">@{{ `(${calculate().applied_logo_discount} logo(s))`}}</small>:</span>
                <span class="discount-highlight"><b style="color:#ee2761">- £@{{calculate().logo_discount}}</b></span>
            </div>
            <div class="minicart__amount_list d-flex justify-content-between" v-if="calculate().oneTimeCost > 0">
                <span>One Time Setup Fees:</span>
                <span><b>£@{{calculate().oneTimeCost}}</b></span>
            </div>
            <hr class="minicart__amount p-0">
            <div class="minicart__amount_list d-flex justify-content-between">
                <span>Subtotal:</span>
                <span><b>£@{{ calculate().subtotal }}</b></span>
            </div>
        <div class="minicart__amount_list d-flex justify-content-between" v-if="calculate().discount > 0">
            <span>Discount:</span>
            <span><b style="color:#ee2761">- £@{{calculate().discount}}</b></span>
        </div>

        <div class="minicart__amount_list d-flex justify-content-between" v-if="freeDelivery()">
            <span>Delivery:</span>
            <span><b style="color:#ee2761">Free</b></span>
        </div>
        
        <div class="minicart__amount_list d-flex justify-content-between">
            <span>VAT (@{{gstTax}}%):</span>
            <span><b>£@{{calculate().tax}}</b></span>
        </div>
        <div class="minicart__amount_list d-flex justify-content-between">
            <span>Total:</span>
            <span><b>£@{{calculate().total}}</b></span>
        </div>
    </div>
    <div class="minicart__conditions text-center">
        <input class="minicart__conditions--input" id="accept" v-model="agree" type="checkbox">
        <label class="minicart__conditions--label" for="accept">I agree with the <a
                class="minicart__conditions--link" href="{{ url('/privacy-policy') }}">Privacy and Policy</a></label>
    </div>
    <div class="minicart__button d-flex justify-content-center">
        <a class="primary__btn minicart__button--link" v-if="cart && cart.length > 0" href="{{url('/cart')}}">View cart</a>
        <a class="primary__btn minicart__button--link" v-else disabled href="javascript:;">View cart</a>
        <a class="primary__btn minicart__button--link" v-if="cart && cart.length > 0 && agree" href="{{url('/checkout')}}">Checkout</a>
        <a class="primary__btn minicart__button--link" v-else href="javascript:;">Checkout</a>
    </div>
</div>