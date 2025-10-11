@extends('layouts.frontendlayout')
@section('content')
<div class="page-header breadcrumb-wrap">
    <div class="container">
        <div class="breadcrumb">
            <a href="index.html" rel="nofollow">Home</a>
            <span></span> Shop
            <span></span> Checkout
        </div>
    </div>
</div>
<section class="mt-50 mb-50" id="checkout-page" data-token="{{ $user ? General::encrypt($user->id) : '' }}">
    <div class="container" v-if="orderPlaced">
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
    <div v-else class="container">
        @if(!$user)
        <div class="row">
            <div class="col-lg-6 mb-sm-15">
                <div class="toggle_info">
                    <span><i class="fi-rs-user mr-10"></i><span class="text-muted">Already have an account?</span> <a href="{{url('/login?redirect=checkout')}}">Click here to login</a></span>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="divider mt-50 mb-50"></div>
            </div>
        </div>
        @endif
        <div class="row">
            <div class="col-md-6">
                <div class="mb-25">
                    <h4>Billing Details</h4>
                </div>
                <form method="post">
                    <div class="form-group">
                        <input type="text" required="" name="fname" placeholder="First name *" v-model="checkout.first_name">
                        <small v-if="errors && errors.first_name == ``">This field is required.</small>

                    </div>
                    <div class="form-group">
                        <input type="text" required="" name="lname" placeholder="Last name *" v-model="checkout.last_name">
                        <small v-if="errors && errors.last_name == ``">This field is required.</small>

                    </div>
                    <div class="form-group">
                        <input required="" type="text" name="cname" placeholder="Company Name"  v-model="checkout.company">
                        <small v-if="errors && errors.company == ``">This field is required.</small>

                    </div>
                    
                    <div class="form-group">
                        <input type="text" name="billing_address" required="" placeholder="Address *" v-model="checkout.address">
                        <small v-if="errors && errors.address == ``">This field is required.</small>

                    </div>
                    <div class="form-group">
                        <input type="text" name="billing_address2" placeholder="Address line2"  v-model="checkout.address2">
                        <small v-if="errors && errors.address2 == ``">This field is required.</small>

                    </div>
                    <div class="form-group">
                        <input required="" type="text" name="city" placeholder="City / Town *" v-model="checkout.city">
                        <small v-if="errors && errors.city == ``">This field is required.</small>
                    </div>
                    <div class="form-group">
                        <select class="checkout__input--select__field border-radius-5" disabled id="country">
                            <option value="2">United Kingdom</option>
                            <option value="3">Netherlands</option>
                            <option value="4">Afghanistan</option>
                            <option value="5">Islands</option>
                            <option value="6">Albania</option>
                            <option value="7">Antigua Barbuda</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <input required="" type="text" name="zipcode" placeholder="Postcode / ZIP *" v-model="checkout.postalcode" >
                        <small v-if="errors && errors.postalcode == ``">This field is required.</small>

                    </div>
                    @if(!$user)
                    <div class="form-group">
                        <input required="" type="text" name="phone" placeholder="Phone *">
                        <small v-if="errors && errors.phone == ``">This field is required.</small>
                    </div>
                    <div class="form-group">
                        <input required="" type="text" name="email" placeholder="Email address *"  v-model="checkout.phone_email">
                        <small v-if="errors && errors.email == ``">This field is required.</small>
                    </div>
                    <div class="form-group">
                        <div class="checkbox">
                            <div class="custome-checkbox">
                                <input class="form-check-input" type="checkbox" name="checkbox" id="createaccount" v-model="checkout.saveInfo">
                                <label class="form-check-label label_info" data-bs-toggle="collapse" href="#collapsePassword" data-target="#collapsePassword" aria-controls="collapsePassword" for="createaccount"><span>Create an account?</span></label>
                            </div>
                        </div>
                    </div>
                    <div id="collapsePassword" class="form-group create-account collapse in">
                        <input required="" type="password" placeholder="Password" name="password">
                    </div>
                    @endif
                    <div class="ship_detail">
                        <div class="form-group">
                            <div class="chek-form">
                                <div class="custome-checkbox">
                                    <input class="form-check-input" type="checkbox" name="checkbox" id="differentaddress">
                                    <label class="form-check-label label_info" data-bs-toggle="collapse" data-target="#collapseAddress" href="#collapseAddress" aria-controls="collapseAddress" for="differentaddress"><span>Ship to a different address?</span></label>
                                </div>
                            </div>
                        </div>
                        <div id="collapseAddress" class="different_address collapse in">
                            <div class="form-group">
                                <input type="text" name="fname" placeholder="First name *">
                            </div>
                            <div class="form-group">
                                <input type="text" name="lname" placeholder="Last name *">
                            </div>
                            <div class="form-group">
                                <input type="text" name="cname" placeholder="Company Name">
                            </div>
                            
                            <div class="form-group">
                                <input type="text" name="billing_address" placeholder="Address *">
                            </div>
                            <div class="form-group">
                                <input type="text" name="billing_address2" placeholder="Address line2">
                            </div>
                            <div class="form-group">
                                <input type="text" name="city" placeholder="City / Town *">
                            </div>
                            <div class="form-group">
                                <input type="text" name="state" placeholder="State / County *">
                            </div>
                            <div class="form-group">
                                <input type="text" name="zipcode" placeholder="Postcode / ZIP *">
                            </div>
                        </div>
                    </div>
                    <div class="mb-20">
                        <h5>Additional information</h5>
                    </div>
                    <div class="form-group mb-30">
                        <textarea rows="5" placeholder="Order notes"></textarea>
                    </div>
                </form>
            </div>
            <div class="col-md-6">
                @include('frontend.checkout.summary')
            </div>
        </div>
    </div>
</section>
@endsection
@push("scripts")
<script>
var parcelforceCost = {{($settings['shipping_cost_parcelforce'] ? $settings['shipping_cost_parcelforce'] : 0)}};
var dpdCost = {{($settings['shipping_cost_dpd'] ? $settings['shipping_cost_dpd'] : 0)}}; 
var loginuseremail = '{{ $user ? $user->email : '' }}';
</script>
@endpush