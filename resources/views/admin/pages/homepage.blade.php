<?php use App\Models\Admin\HomePage; ?>
@extends('layouts.adminlayout')
@section('content')
<div class="header bg-primary pb-6">
	<div class="container-fluid">
		<div class="header-body">
			<div class="row align-items-center py-4">
				<div class="col-lg-6 col-7">
					<h6 class="h2 text-white d-inline-block mb-0">Home Page</h6>
				</div>
				<div class="col-lg-6 col-5 text-right">
					<a href="<?php echo route('admin.pages') ?>" class="btn btn-neutral"><i class="ni ni-bold-left"></i> Back</a>
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
							<h3 class="mb-0">Home Page Content.</h3>
						</div>
					</div>
				</div>
				<div class="card-body">
					<form method="post" action="<?php echo route('admin.pages.home') ?>" class="form-validation">
						<!--!! CSRF FIELD !!-->
						{{ @csrf_field() }}
						<h6 class="heading-small text-muted mb-4">Page information</h6>
						<div class="pl-lg-4">
							<div class="form-group">
                                <label class="form-control-label" for="input-first-name">Sliders</label>
                                <p>Manage the slider here. <a href="{{ route('admin.sliders') }}" target="_blank">Click here.</a></p>
                            </div>
						</div>
						<hr class="my-4" />
						<h6 class="heading-small text-muted mb-4">6 grid tiles</h6>
                        <div class="pl-lg-4">
							@for($k = 1; $k <= 6; $k++)
							<div class="row">
								<div class="col-sm-4">
									<div class="form-group">
										<label class="form-control-label" for="input-first-name">Title</label>
										<input type="text" class="form-control" name="{{'grid_title_'.$k}}"  placeholder="Title" value="{{ HomePage::get('grid_title_'.$k) }}">
									</div>
								</div>								
								<div class="col-sm-4">
									<div class="form-group">
										<label class="form-control-label" for="input-first-name">Link</label>
										<input type="text" class="form-control" name="grid_link_{{$k}}"  placeholder="/" value="{{ HomePage::get('grid_link_'.$k) }}">
										
									</div>
								</div>
								<div class="col-sm-4">
									<div class="form-group">
										<div 
											class="upload-image-section"
											data-type="image"
											data-multiple="false"
											data-path="home"
											data-resize-large="252*174"
										>
											<div class="upload-section">
												<div class="button-ref mb-3">
													<button class="btn btn-icon btn-primary btn-lg" type="button">
														<span class="btn-inner--icon"><i class="fas fa-upload"></i></span>
														<span class="btn-inner--text">Upload Image</span>
													</button>
													<p><small>Recomeded Size: 252px * 174px</small></p>
												</div>
												<!-- PROGRESS BAR -->
												<div class="progress d-none">
												<div class="progress-bar bg-default" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%;"></div>
												</div>
											</div>
											<!-- INPUT WITH FILE URL -->
											<?php $image = HomePage::get('grid_image_'.$k) ?>
											<textarea class="d-none" name="<?php echo 'grid_image_'.$k ?>"><?php echo $image ?></textarea>
											<div class="show-section <?php echo !$image ? 'd-none' : "" ?>">
												@include('admin.partials.previewFileRender', ['file' =>  $image])
											</div>
										</div>
									</div>
								</div>
							</div>
							@endfor
						</div>
                        <hr class="my-4" />
						<h6 class="heading-small text-muted mb-4">Our Products</h6>
                        <div class="pl-lg-4">
							<div class="row">
								<div class="col-sm-4">
									@php
									$selected  = HomePage::get('featured_products');
									$selected = $selected ? json_decode($selected, true) : [];
									@endphp
									<div class="form-group">
										<label class="form-control-label" for="input-first-name">Featured</label>
										<select name="featured_products[]" class="form-control" multiple>
											<option value=""></option>
											<?php foreach($products as $p): ?>
											<option data-subtext="{{ $p->parent_id ? 'Uniform' : '' }}" value="{{ $p->id }}" {{ in_array($p->id, $selected) ? 'selected' : ''}}>{{ $p->title }}</option>
											<?php endforeach; ?>
										</select>
									</div>
								</div>
								<div class="col-sm-4">
									@php
									$selected  = HomePage::get('trending_products');
									$selected = $selected ? json_decode($selected, true) : [];
									@endphp
									<div class="form-group">
										<label class="form-control-label" for="input-first-name">Popular</label>
										<select name="trending_products[]" class="form-control" multiple>
											<option value=""></option>
											<?php foreach($products as $p): ?>
											<option data-subtext="{{ $p->parent_id ? 'Uniform' : '' }}" value="{{ $p->id }}" {{ in_array($p->id, $selected) ? 'selected' : ''}}>{{ $p->title }}</option>
											<?php endforeach; ?>
										</select>
									</div>
								</div>
								<div class="col-sm-4">
									@php
									$selected  = HomePage::get('new_arrivals');
									$selected = $selected ? json_decode($selected, true) : [];
									@endphp
									<div class="form-group">
										<label class="form-control-label" for="input-first-name">Newly Added</label>
										<select name="new_arrivals[]" class="form-control" multiple>
											<option value=""></option>
											<?php foreach($products as $p): ?>
											<option data-subtext="{{ $p->parent_id ? 'Uniform' : '' }}" value="{{ $p->id }}" {{ in_array($p->id, $selected) ? 'selected' : ''}}>{{ $p->title }}</option>
											<?php endforeach; ?>
										</select>
									</div>
								</div>
							</div>
						</div>
						<hr class="my-4" />
						<h6 class="heading-small text-muted mb-4">Banner Infromation</h6>
                        <div class="pl-lg-4">
							@include('admin.pages.bannerarea', ['key' => 'banner_1', 'imagesize' => '1320*300', 'subheading' => false, 'nobuttons' => false])
						</div>
						<hr class="my-4" />
						<h6 class="heading-small text-muted mb-4">Popular Categories</h6>
                        <div class="pl-lg-4">
							<div class="form-group">
								@php
								$selected  = HomePage::get('popular_categories');
								$selected = $selected ? json_decode($selected, true) : [];
								@endphp
								<label class="form-control-label" for="input-first-name">Select Categories</label>
								<select name="popular_categories[]" class="form-control" multiple>
									<option value=""></option>
									<?php foreach($categories as $p): ?>
									<option value="{{ $p->id }}" {{ in_array($p->id, $selected) ? 'selected' : ''}}>{{ $p->title }}</option>
									<?php endforeach; ?>
								</select>
							</div>
						</div>
						<hr class="my-4" />
						<h6 class="heading-small text-muted mb-4">Promotional Blocks</h6>
                        <div class="pl-lg-4">
							<?php $enable  = HomePage::get('left_right_grid_enable'); ?>	
							<!-- <div class="row">	
								<div class="col-md-12 py-2">	
									<div class="custom-control">
										<label class="custom-toggle">
											<input type="hidden" name="left_right_grid_enable" value="0">
											<input type="checkbox" id="dealEnalbe" value="1" name="left_right_grid_enable" {{ $enable ? 'checked=""' : '' }}>
											<span class="custom-toggle-slider rounded-circle" data-label-off="No" data-label-on="Yes"></span>
										</label>
										<label class="custom-control-label">Active/Inactive</label>
									</div>
								</div>
							</div> -->
							<div class="row">
								<div class="col-md-4">
									@include('admin.pages.bannerarea', ['key' => 'left_grid', 'imagesize' => '600*225'])
								</div>
								<div class="col-md-4">
									@include('admin.pages.bannerarea', ['key' => 'mid_grid', 'imagesize' => '600*225'])
								</div>
								<div class="col-md-4">
									@include('admin.pages.bannerarea', ['key' => 'right_grid', 'imagesize' => '600*225'])
								</div>
							</div>
						</div>
						<hr class="my-4" />
						<h6 class="heading-small text-muted mb-4">Deals of the day information</h6>
                        <div class="pl-lg-4">
							<div class="row">
								<div class="col-md-6">
									<?php $enable  = HomePage::get('deal_day_enable'); ?>	
									<div class="row">	
										<div class="col-md-12 py-2">	
											<div class="custom-control">
												<label class="custom-toggle">
													<input type="hidden" name="deal_day_enable" value="0">
													<input type="checkbox" id="dealEnalbe" value="1" name="deal_day_enable" {{ $enable ? 'checked=""' : '' }}>
													<span class="custom-toggle-slider rounded-circle" data-label-off="No" data-label-on="Yes"></span>
												</label>
												<label class="custom-control-label">Active/Inactive</label>
											</div>
										</div>
									</div>
									@include('admin.pages.bannerarea', ['key' => 'deal_day', 'imagesize' => '700*511', 'subheading' => true])
									<hr class="my-2">
									<div class="row">
										<div class="col-md-4">
											<div class="form-group">
												<label class="form-control-label" for="input-first-name">Sale Price</label>
												<input type="number" class="form-control" name="{{'deal_day_sale_price'}}"  placeholder="$" value="{{ HomePage::get('deal_day_sale_price') }}">
											</div>
										</div>
										<div class="col-md-4">
											<div class="form-group">
												<label class="form-control-label" for="input-first-name">Actual Price</label>
												<input type="number" class="form-control" name="{{'deal_day_actual_price'}}"  placeholder="$" value="{{ HomePage::get('deal_day_actual_price') }}">
											</div>
										</div>
										<div class="col-md-4">
											<div class="form-group">
												<label class="form-control-label" for="input-first-name">Offer till</label>
												<input type="date" min="{{ date('Y-m-d 23:59:59') }}" class="form-control" name="{{'deal_day_offer_days'}}"  placeholder="12" value="{{ HomePage::get('deal_day_offer_days') }}">
											</div>
										</div>
									</div>
								</div>
								<div class="col-md-6">
									<?php $enable  = HomePage::get('deal_day_2_enable'); ?>	
									<div class="row">	
										<div class="col-md-12 py-2">	
											<div class="custom-control">
												<label class="custom-toggle">
													<input type="hidden" name="deal_day_2_enable" value="0">
													<input type="checkbox" id="dealEnalbe2" value="1" name="deal_day_2_enable" {{ $enable ? 'checked=""' : '' }}>
													<span class="custom-toggle-slider rounded-circle" data-label-off="No" data-label-on="Yes"></span>
												</label>
												<label class="custom-control-label">Active/Inactive</label>
											</div>
										</div>
									</div>
									@include('admin.pages.bannerarea', ['key' => 'deal_day_2', 'imagesize' => '700*511', 'subheading' => true])
									<hr class="my-2">
									<div class="row">
										<div class="col-md-4">
											<div class="form-group">
												<label class="form-control-label" for="input-first-name">Sale Price</label>
												<input type="number" class="form-control" name="{{'deal_day_2_sale_price'}}"  placeholder="$" value="{{ HomePage::get('deal_day_2_sale_price') }}">
											</div>
										</div>
										<div class="col-md-4">
											<div class="form-group">
												<label class="form-control-label" for="input-first-name">Actual Price</label>
												<input type="number" class="form-control" name="{{'deal_day_2_actual_price'}}"  placeholder="$" value="{{ HomePage::get('deal_day_2_actual_price') }}">
											</div>
										</div>
										<div class="col-md-4">
											<div class="form-group">
												<label class="form-control-label" for="input-first-name">Offer till</label>
												<input type="date" min="{{ date('Y-m-d 23:59:59') }}" class="form-control" name="{{'deal_day_2_offer_days'}}"  placeholder="12" value="{{ HomePage::get('deal_day_2_offer_days') }}">
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<hr class="my-4" />
						<h6 class="heading-small text-muted mb-4">Featured Brands</h6>
                        <div class="pl-lg-4">
							<div class="row">
								<div class="col-md-12">
									<div class="form-group">
										<div 
											class="upload-image-section"
											data-type="image"
											data-multiple="true"
											data-path="home"
											data-resize-large="500*195"
										>
											<div class="upload-section">
												<div class="button-ref mb-3">
													<button class="btn btn-icon btn-primary btn-lg" type="button">
														<span class="btn-inner--icon"><i class="fas fa-upload"></i></span>
														<span class="btn-inner--text">Upload Image</span>
													</button>
													<p><small>Recomeded Size: 500px * 195px</small></p>
												</div>
												<!-- PROGRESS BAR -->
												<div class="progress d-none">
												<div class="progress-bar bg-default" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%;"></div>
												</div>
											</div>
											<!-- INPUT WITH FILE URL -->
											<?php $image = HomePage::get('featured_brands'); ?>
											<textarea class="d-none" name="<?php echo 'featured_brands' ?>"><?php echo $image ?></textarea>
											<div class="show-section <?php echo !$image ? 'd-none' : "" ?>">
												@include('admin.partials.previewFileRender', ['file' =>  $image])
											</div>
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