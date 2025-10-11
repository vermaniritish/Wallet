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
			<div id="recoverPassword" class="panel-collapse login_form collapse show" style="">
				<div class="panel-body">
					<h2>Recover Password!</h2>
					<p class="mb-30 font-sm">Create new password for account.</p>
					<form id="recover-password-form">
						<div class="form-group">
							<input required name="new_password" placeholder="New Password" id="new_password" type="password" type="password">
						</div>
						<div class="form-group">
							<input required name="confirm_password" placeholder="Confirm Password" id="confirm_password" type="password" type="password">
						</div>
						<p v-if="errorMessages.new_password" class="text-danger">@{{ errorMessages.new_password }}</p>
						<p v-if="errorMessages.confirm_password" class="text-danger">@{{ errorMessages.confirm_password }}</p>
						<div class="login_footer form-group">
							<a href="{{route('login')}}"><i class="fas fa-arrow-left" ></i> Back to Login</a>
						</div>
						<div class="d-flex flex-row align-items-center gap-4">
							<button type="button" name="login" v-on:click="recovePassword()" class="btn btn-md"><i class="fa fa-spin fa-spinner" v-if="loading"></i> Verify</button>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</section>
@endsection