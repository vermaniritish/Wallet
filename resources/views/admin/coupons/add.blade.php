@extends('layouts.adminlayout')
@section('content')
<div class="header bg-primary pb-6">
	<div class="container-fluid">
		<div class="header-body">
			<div class="row align-items-center py-4">
				<div class="col-lg-6 col-7">
					<h6 class="h2 text-white d-inline-block mb-0">Manage Coupons</h6>
				</div>
				<div class="col-lg-6 col-5 text-right">
					<a href="<?php echo route('admin.coupons') ?>" class="btn btn-neutral"><i class="ni ni-bold-left"></i> Back</a>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- Page content -->
<div class="container-fluid mt--6">
	<div class="row">
		<div class="col-xl-12 order-xl-1">
			<div class="card">
				<!--!! FLAST MESSAGES !!-->
				@include('admin.partials.flash_messages')
				<div class="card-header">
					<div class="row align-items-center">
						<div class="col-8">
							<h3 class="mb-0">Create New Coupon Here.</h3>
						</div>
					</div>
				</div>
				<div class="card-body">
					<form method="post" action="<?php echo route('admin.coupons.add') ?>" class="form-validation">
						<!--!! CSRF FIELD !!-->
						{{ @csrf_field() }}
						<h6 class="heading-small text-muted mb-4">Coupon information</h6>
						<div class="pl-lg-4">
							<div class="row">
								<div class="col-md-6">
									<label class="form-control-label" for="input-first-name">Number of coupons you want to add.</label>
									<input 
										type="number"
										class="form-control"
										name="number_of_coupons"
										required
										placeholder="Number of coupons"
										min="0"
										step="1"
										oninput="this.value = this.value.replace(/[^0-9]/g, '');"
										value="{{ old('number_of_coupons') }}"
									>
								</div>
								<div class="col-md-6">
									<label class="form-control-label" for="input-first-name">Schools</label>
									<select class="form-control" name="school_id" required>
										<option value=""></option>
										@foreach($schools as $s)
										<option value="{{ $s->id }}">{{ $s->name }}</option>
										@endforeach
									</select>
									
								</div>
							</div>
							<hr class="my-4" />
							<div class="row">
								<div class="col-md-6">
									<div class="form-group">
										<label class="form-control-label" for="input-first-name">Title</label>
										<input type="text" class="form-control" name="title" required placeholder="Title" value="{{ old('title') }}">
										@error('title')
											<small class="text-danger">{{ $message }}</small>
										@enderror
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<label class="form-control-label" for="input-first-name">Code</label>
										<input type="text" class="form-control" name="coupon_code" required placeholder="Coupon Code" value="{{ old('coupon_code') }}"    pattern="^[A-Za-z0-9]*[0-9]$">
										<p class="small text-muted">Enter only uppercase alphabets e.g. (ABC)</p>
										@error('coupon_code')
											<small class="text-danger">{{ $message }}</small>
										@enderror
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-md-6">
									<div class="form-group">
										<label class="form-control-label" for="input-first-name">Max Use</label>
										<input type="number" class="form-control" name="max_use" required placeholder="Coupon Maximum Use" value="{{ old('max_use') }}">
										@error('max_use')
											<small class="text-danger">{{ $message }}</small>
										@enderror
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<label class="form-control-label" for="input-first-name">Expiry Date</label>
										<input 
											class="form-control" 
											type="date" 
											name="end_date" 
											required 
											placeholder="Coupon End Date"
											min="{{ date('Y-m-d') }}"
											value="{{ old('end_date') }}"
										>
										@error('end_date')
											<small class="text-danger">{{ $message }}</small>
										@enderror
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-md-6">
									<div class="form-group">
										<label class="form-control-label" for="input-first-name">Amount</label>
										<input type="number" class="form-control" name="amount" required placeholder="Amount" value="{{ old('amount') }}">
										@error('amount')
											<small class="text-danger">{{ $message }}</small>
										@enderror
									</div>
								</div>
								<div class="col-md-3">
									<div class="form-group">
										<label class="form-control-label" for="input-first-name">Is Percentage ?</label>
										<div required class="custom-control mt-2">
											<label class="custom-toggle">
												<input type="hidden" name="is_percentage" value="0">
												<input type="checkbox" name="is_percentage" value="1"
													<?php echo old('is_percentage') != '0' ? 'checked' : ''; ?>>
												<span class="custom-toggle-slider rounded-circle" data-label-off="No"
													data-label-on="Yes"></span>
											</label>
											<label class="custom-control-label">Yes/No</label>
										</div>
									</div>
								</div>
								<div class="col-md-3">
									<div class="form-group">
										<label class="form-control-label" for="input-first-name">Min Amount to apply</label>
										<input type="number" class="form-control" name="min_amount" required placeholder="Min Amount" value="{{ old('min_amount') }}">
										@error('min_amount')
											<small class="text-danger">{{ $message }}</small>
										@enderror
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-lg-12">
									<div class="form-group">
										<label class="form-control-label">Description</label>
										<textarea rows="2" class="form-control" placeholder="Description" required name="description">{{ old('description') }}</textarea>
										@error('description')
										    <small class="text-danger">{{ $message }}</small>
										@enderror
									</div>
								</div>
							</div>
						</div>
						<hr class="my-4" />
						<button href="#" class="btn btn-sm py-2 px-3 btn-primary float-right">
							<i class="fa fa-save"></i> Submit
						</button>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection