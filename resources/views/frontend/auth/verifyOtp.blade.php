@extends('layouts.frontendlayout')
@section('content')
<div class="page-header breadcrumb-wrap">
	<div class="container">
		<div class="breadcrumb">
			<a href="{{ url('/') }}" rel="nofollow">Home</a>
			<span></span> Recover Password
		</div>
	</div>
</div>
<section class="mt-50 mb-50">
	<div class="container">
		<div class="row">
			<div class="col-lg-12">
			<div id="verifyOtp" class="panel-collapse login_form collapse show" style="">
				<div class="panel-body">
					<h2 class="">Recover Password</h2>
					<p class="mb-30 font-sm">Enter one time password to reset your password.</p>
					<form method="post" id="otp-form">
						<div class="form-group">
							<input required name="otp" placeholder="OTP" type="number" maxlength="6">
							<div v-if="errorMessages.otp" class="text-danger">@{{ errorMessages.otp }}</div>
						</div>
						<div class="login_footer form-group">
							<a href="{{route('login')}}"><i class="fas fa-arrow-left" ></i> Back to Login</a>
						</div>
						<div class="d-flex flex-row align-items-center gap-4">
							<button type="button" name="login" v-on:click="verifyOtp()" class="btn btn-md"><i class="fa fa-spin fa-spinner" v-if="loading"></i> Verify</button>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</section>
@endsection