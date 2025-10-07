@extends('layouts.frontendlayout')
@section('content')
{{-- <?php require_once 'header-top.php'; ?>
<?php require_once 'header-bottom.php'; ?> --}}
    <!-- Start login section  -->
        <div class="login__section section--padding">
            <div class="container">
                <div class="login__section--inner">
                    <div id="verifyOtp" class="row row-cols-md-2 row-cols-1">
						<div class="col">
							<div class="account__login">
								<div class="account__login--header mb-25">
									<h2 class="account__login--header__title h3 mb-10">Recover Password</h2>
									<p class="account__login--header__desc">Enter one time password to reset your password.</p>
								</div>
								<div class="account__login--inner">
									<form id="otp-form">
										<input class="account__login--input" required name="otp" placeholder="OTP" type="number" maxlength="6">
										<div v-if="errorMessages.otp" class="text-danger text-center">@{{ errorMessages.otp }}</div>
										<div class="account__login--remember__forgot mb-15 d-flex justify-content-between align-items-center">
											<button class="account__login--forgot" href="{{route('login')}}" type="button"><i class="fas fa-arrow-left" ></i> Back to Login</button>
										</div>
										<button class="account__login--btn primary__btn" v-on:click="verifyOtp()" type="button"><i class="fa fa-spin fa-spinner" v-if="loading"></i>Verify</button>
									</form>
								</div>
							</div>
						</div>	
                    </div>
                </div>
            </div>     
        </div>
    <!-- End login section  -->
@endsection