@extends('layouts.frontendlayout')
@section('content')
<?php $user = request()->session()->get('user') ?>
    <section class="my__account--section section--padding">
        <div class="container">
            @include('frontend.account.name')
            <div class="my__account--section__inner border-radius-10 d-flex">
                <div class="account__left--sidebar">
                    <h2 class="account__content--title h3 mb-20">My Profile</h2>
                    @include('frontend.account.aside')
                </div>
                <div class="account__wrapper">
                    <div class="account__content">
                        @include('admin.partials.flash_messages')
                        <h3 class="account__content--title mb-20 mt-2">My Details</h3>
                        <form action="" method="post" id="edit-account">
                            {{ csrf_field() }}
                            <div class="row">
                                <div class="col-lg-6 mb-12">
                                    <input class="account__login--input" placeholder="Name" type="text" required name="name" value="{{ $user->first_name . ' ' . $user->last_name }}">
                                </div>
                                <div class="col-lg-6 mb-12">
                                    <input class="account__login--input" placeholder="Email Address" disabled type="text"  value="{{ $user->email }}">
                                </div>
                                <div class="col-lg-6 mb-12">
                                    <input class="account__login--input" placeholder="Phone Number" disabled type="text" value="+41-{{ $user->phonenumber }}">
                                </div>
                            </div>
                            <h3 class="account__content--title mb-20">Address Details</h3>
                            <div class="row">
                                <div class="col-lg-6 mb-12">
                                    <input class="account__login--input" placeholder="Address" type="text" required  name="address" value="{{ $user->address }}">
                                </div>
                                <div class="col-lg-6 mb-12">
                                    <input class="account__login--input" placeholder="Apartment, suite, etc." type="text" name="address2" value="{{ $user->address2 }}">
                                </div>
                                <div class="col-lg-6 mb-12">
                                    <input class="account__login--input" placeholder="City" type="text" required  name="city" value="{{ $user->city }}">
                                </div>
                                <div class="col-lg-6 mb-12">
                                    <input class="account__login--input" placeholder="Post Code" type="text" required  name="postcode" value="{{ $user->postcode }}">
                                </div>
                                <div class="col-lg-6 mb-12">
                                    <div class="checkout__input--list checkout__input--select select">
                                        <label class="checkout__select--label" for="country">Country/region</label>
                                        <select class="checkout__input--select__field border-radius-5" id="country" required>
                                            <option value="United Kingdom" {{ $user->country == 'United Kingdom' ? 'selected' : '' }} >United Kingdom</option>
                                        </select>
                                    </div>
                                </div>
                                <button class="account__login--btn primary__btn mb-10 mt-5" type="submit">Submit</button>
                            </div>
                        </form>
                        <form action="{{ url('/auth/update-password') }}" method="post" id="change-password">
                            {{ csrf_field() }}
                                <h3 class="account__content--title mb-20 mt-4">Change Password</h3>
                                <div class="row">
                                    <div class="col-lg-6">
                                        <input class="account__login--input" type="password" placeholder="Current Password" type="text" required  name="current_password" value="{{ old('current_password') }}">
                                        @error('current_password')
										    <small class="text-danger">{{ $message }}</small>
										@enderror
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-6">
                                        <input class="account__login--input" type="password" placeholder="New Password" type="text" required  minlength="8" maxlength="36"  name="new_password" value="{{ old('new_password') }}">
                                        @error('new_password')
										    <small class="text-danger">{{ $message }}</small>
										@enderror
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-6">
                                        <input class="account__login--input" type="password" placeholder="Confirm Password" type="text" required  minlength="8" maxlength="36"  name="confirmed_password" value="{{ old('confirmed_password') }}">
                                        @error('confirm_password')
										    <small class="text-danger">{{ $message }}</small>
										@enderror
                                    </div>
                                </div>
                                <button class="account__login--btn primary__btn mb-10 mt-5" type="submit">Submit</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection