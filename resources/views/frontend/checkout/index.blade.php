@extends('layouts.frontendlayout')
@section('content')
<div class="checkout__page--area" id="checkout-page" data-token="{{ $user ? General::encrypt($user->id) : '' }}">
        <div class="container">
        
            <div class="checkout__page--inner d-flex" v-if="orderPlaced">
                <div class="main checkout__mian">
                    <div class="row categories-listing time-slot-page">
                        <div class="col-sm-12 category-heading">
                            <h2><i class="far fa-arrow-left" style="margin-right: 15px"></i> Order #@{{ orderPlaced }}</h2>
                        </div>
                        <div class="row">
                            <div class="col-sm-12">
                                <p class="text-success text-center" style="font-size: 100px;"><i class="far fa-check-circle"></i></p>
                                <h3 class="text-center my-4">Order Id: #@{{ orderPlaced }}</h3>
                                <p class="text-center mb-1">We have recieved your order. Your order will be accepted and processed in some minutes.</p>
                                <p class="text-center mb-1">For order realted queries, feel free to contact us at <a href="tel:+91-3434343434">+91 343434343</a> </p>
                                <p class="text-center mt-4"><a href="{{url('/my-orders')}}" target="_blank" class="btn btn-primary" >My Order</a></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="checkout__page--inner d-flex" v-else>
                <div class="main checkout__mian">
                    <header class="main__header checkout__mian--header mb-30">
                       
                        @include('frontend.checkout.summary')
                        <nav>
                            <ol class="breadcrumb checkout__breadcrumb d-flex">
                                <li class="breadcrumb__item breadcrumb__item--completed d-flex align-items-center">
                                    <a class="breadcrumb__link" href="{{url('/cart')}}">Cart</a>
                                    <svg class="readcrumb__chevron-icon" xmlns="http://www.w3.org/2000/svg" width="17.007" height="16.831" viewBox="0 0 512 512"><path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="48" d="M184 112l144 144-144 144"></path></svg>
                                </li>
                        
                                <li class="breadcrumb__item breadcrumb__item--current d-flex align-items-center">
                                    <span class="breadcrumb__text current">Information</span>
                                    <svg class="readcrumb__chevron-icon" xmlns="http://www.w3.org/2000/svg" width="17.007" height="16.831" viewBox="0 0 512 512"><path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="48" d="M184 112l144 144-144 144"></path></svg>
                                </li>
                                <li class="breadcrumb__item breadcrumb__item--blank">
                                    <span class="breadcrumb__text">Payment</span>
                                </li>
                            </ol>
                            </nav>
                    </header>
                    <main class="main__content_wrapper">
                        <form action="#">
                            @if(!$user)
                            <div class="checkout__content--step section__contact--information" id="nologinsection">
                                <div class="section__header checkout__section--header d-flex align-items-center justify-content-between mb-25">
                                    <h2 class="section__header--title h3">Contact information</h2>
                                    
                                    <p class="layout__flex--item">
                                        Already have an account?
                                        <a class="layout__flex--item__link" href="{{url('/login')}}">Log in</a>  
                                    </p>
                                    
                                </div>
                                <div class="customer__information">
                                    <div class="checkout__email--phone mb-12">
                                       <label>
                                            <input class="checkout__input--field border-radius-5" v-model="checkout.phone_email" required placeholder="Email or mobile phone number"  type="text">
                                       </label>
                                       <small v-if="errors && errors.phone_email == ``">This field is required.</small>
                                    </div>
                                    <div class="checkout__checkbox">
                                        <input class="checkout__checkbox--input" id="check1" type="checkbox"  v-model="checkout.newsletterSubscribe">
                                        <span class="checkout__checkbox--checkmark"></span>
                                        <label class="checkout__checkbox--label" for="check1">
                                            Email me with news and offers</label>
                                    </div>
                                </div>
                            </div>
                            @endif
                            <div class="checkout__content--step section__shipping--address {{ $user ? 'pt-0' : '' }}"  >
                                <div class="section__header mb-25">
                                    <h3 class="section__header--title">Shipping address</h3>
                                </div>
                                <div class="section__shipping--address__content">
                                    <div class="row">
                                        <div class="col-lg-6 mb-12">
                                            <div class="checkout__input--list ">
                                                <label>
                                                    <input class="checkout__input--field border-radius-5" placeholder="First name (optional)"  type="text" v-model="checkout.first_name" required>
                                                </label>
                                                <small v-if="errors && errors.first_name == ``">This field is required.</small>
                                            </div>
                                        </div>
                                        <div class="col-lg-6 mb-12">
                                            <div class="checkout__input--list">
                                                <label>
                                                    <input class="checkout__input--field border-radius-5" placeholder="Last name"  type="text" v-model="checkout.last_name" required>
                                                </label>
                                                <small v-if="errors && errors.last_name == ``">This field is required.</small>
                                            </div>
                                        </div>
                                        <div class="col-12 mb-12">
                                            <div class="checkout__input--list">
                                                <label>
                                                    <input class="checkout__input--field border-radius-5" placeholder="Company (optional)"  type="text" v-model="checkout.company" required>
                                                </label>
                                                <small v-if="errors && errors.company == ``">This field is required.</small>
                                            </div>
                                        </div>
                                        <div class="col-12 mb-12">
                                            <div class="checkout__input--list">
                                                <label>
                                                    <input class="checkout__input--field border-radius-5" placeholder="Address1"  type="text" v-model="checkout.address" required>
                                                </label>
                                                <small v-if="errors && errors.address == ``">This field is required.</small>
                                            </div>
                                        </div>
                                        <div class="col-12 mb-12">
                                            <div class="checkout__input--list">
                                                <label>
                                                    <input class="checkout__input--field border-radius-5" placeholder="Apartment, suite, etc. (optional)"  type="text" v-model="checkout.address2" required>
                                                </label>
                                                <small v-if="errors && errors.address2 == ``">This field is required.</small>                                                
                                            </div>
                                        </div>
                                        <div class="col-12 mb-12">
                                            <div class="checkout__input--list">
                                                <label>
                                                    <input class="checkout__input--field border-radius-5" placeholder="City"  type="text" v-model="checkout.city" required>
                                                </label>
                                                <small v-if="errors && errors.city == ``">This field is required.</small>
                                            </div>
                                        </div>
                                        <div class="col-lg-6 mb-12">
                                            <div class="checkout__input--list checkout__input--select select">
                                                <label class="checkout__select--label" for="country">Country/region</label>
                                                <select class="checkout__input--select__field border-radius-5" disabled id="country">
                                                    <option value="2">United Kingdom</option>
                                                    <option value="3">Netherlands</option>
                                                    <option value="4">Afghanistan</option>
                                                    <option value="5">Islands</option>
                                                    <option value="6">Albania</option>
                                                    <option value="7">Antigua Barbuda</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-lg-6 mb-12">
                                            <div class="checkout__input--list">
                                                <label>
                                                    <input class="checkout__input--field border-radius-5" placeholder="Postal code" type="text" v-model="checkout.postalcode" required>
                                                </label>
                                                <small v-if="errors && errors.postalcode == ``">This field is required.</small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="checkout__checkbox">
                                        <input class="checkout__checkbox--input" id="check2" type="checkbox" v-model="checkout.saveInfo">
                                        <span class="checkout__checkbox--checkmark"></span>
                                        <label class="checkout__checkbox--label" for="check2">
                                            Save this information for next time</label>
                                    </div>
                                </div>
                            </div>
                            
                        </form>
                    </main>
                    <footer class="main__footer checkout__footer">
                        <p>&nbsp;</p>
                    </footer>
                </div>
                @include('frontend.checkout.aside')
            </div>
        </div>
    </div>
@endsection