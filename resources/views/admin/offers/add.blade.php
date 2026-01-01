@extends('layouts.adminlayout')
@section('content')
<div class="header bg-primary pb-6">
	<div class="container-fluid">
		<div class="header-body">
			<div class="row align-items-center py-4">
				<div class="col-lg-6 col-7">
					<h6 class="h2 text-white d-inline-block mb-0">Manage Offers</h6>
				</div>
				<div class="col-lg-6 col-5 text-right">
					<a href="<?php echo route('admin.offers') ?>" class="btn btn-neutral"><i class="ni ni-bold-left"></i> Back</a>
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
							<h3 class="mb-0">Create New Offer Here.</h3>
						</div>
					</div>
				</div>
				<div class="card-body">
					<form method="post" action="<?php echo route('admin.offers.add') ?>" class="form-validation">
						<!--!! CSRF FIELD !!-->
						{{ @csrf_field() }}
						<h6 class="heading-small text-muted mb-4">Offer information</h6>
						<div class="pl-lg-4">
							<div class="form-group">
								<label class="form-control-label" for="input-first-name">Title</label>
								<input type="text" class="form-control" name="title" required placeholder="Title" value="{{ old('title') }}">
								@error('title')
								    <small class="text-danger">{{ $message }}</small>
								@enderror
							</div>
							<div class="row my-2">
								<div class="col-md-12">
									<label class="form-control-label" for="input-first-name">Offer Type</label>
									<br />
									<div class="mt-2 custom-control custom-radio custom-control-inline">
										<input type="radio" id="case-2" name="type" value="case-2" {{ (old('type') != 'case-3' ? 'checked' : '') }} class="custom-control-input">
										<label class="custom-control-label" for="case-2">Product Offer</label>
									</div>
									<!-- <div class="custom-control custom-radio custom-control-inline">
										<input type="radio" id="case-3" name="type" value="case-3" {{ (old('type') == 'case-3' ? 'checked' : '') }} class="custom-control-input">
										<label class="custom-control-label" for="case-3">Free Logo</label>
									</div> -->
								</div>
							</div>
							<div class="row my-2">
								<div class="form-group col-md-4">
									<label class="form-control-label" for="input-first-name">Product</label>
									<select id="product-select" class="form-control" name="product_id">
										<option value=""></option>
										<?php foreach($products as $p): ?>
											<option value="{{ $p->id }}" <?php echo (old('product_id') == $p->id ? ' selected ' : '') ?>>{{ $p->title }} - {{ $p->sku_number }}</option>
										<?php endforeach; ?>
									</select>
									@error('product_id')
										<small class="text-danger">{{ $message }}</small>
									@enderror
								</div>
								<div class="form-group col-md-4">
									<label class="form-control-label" for="input-first-name">Sizes</label>
									<select id="sizes-select" class="form-control" name="sizes[]" multiple data-val="{{old('sizes')}}">
										
									</select>
									@error('sizes')
										<small class="text-danger">{{ $message }}</small>
									@enderror
								</div>
								<div class="form-group col-md-4">
									<label class="form-control-label" for="input-first-name">Colors</label>
									<select id="colors-select" class="form-control" name="colors[]" multiple data-val="{{old('colors')}}">
										
									</select>
									@error('colors')
										<small class="text-danger">{{ $message }}</small>
									@enderror
								</div>
							</div>
							<div class="row my-2" >
								<div class="form-group col-md-6">
									<label class="form-control-label" for="input-first-name">Number of products allowed for Offer.</label>
									<input type="number" class="form-control" name="quantity" min="1" required placeholder="5" value="{{ old('quantity') }}">
									@error('quantity')
										<small class="text-danger">{{ $message }}</small>
									@enderror
								</div>
								<div class="form-group col-md-6 d-none" id="case-2-offers">
									<label class="form-control-label" for="input-first-name">Mininum cart price for offer.</label>
									<input type="number" class="form-control" name="offer_total_price" min="1" required placeholder="5000" value="{{ old('offer_total_price') }}">
									@error('offer_total_price')
										<small class="text-danger">{{ $message }}</small>
									@enderror
								</div>
								<div class="form-group col-md-6 d-none" id="case-3-offers">
									<label class="form-control-label" for="input-first-name">Number of free logos.</label>
									<input type="number" class="form-control" name="free_logo" min="1" required placeholder="2" value="{{ old('free_logo') }}">
									@error('free_logo')
										<small class="text-danger">{{ $message }}</small>
									@enderror
								</div>
								<div class="col-lg-6">
									<div class="form-group">
										<div class="custom-control">
											<label class="custom-toggle">
												<input type="hidden" name="status" value="0">
												<input type="checkbox" name="status" value="1" <?php echo (old('status') != '0' ? 'checked' : '') ?>>
												<span class="custom-toggle-slider rounded-circle" data-label-off="No" data-label-on="Yes"></span>
											</label>
											<label class="custom-control-label">Do you want to publish this offer ?</label>
										</div>
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