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
								<label class="form-control-label" for="input-first-name">Title</label>
								<input type="text" class="form-control" name="title" required placeholder="Title" value="{{ HomePage::get('title') }}">
								@error('title')
								    <small class="text-danger">{{ $message }}</small>
								@enderror
							</div>
                            <div class="form-group">
                                <label class="form-control-label" for="input-first-name">Sliders</label>
                                <p>Manage the slider here. <a href="{{ route('admin.sliders') }}" target="_blank">Click here.</a></p>
                            </div>
						</div>
                        <hr class="my-4" />
                        <h6 class="heading-small text-muted mb-4">Banners information</h6>
                        <div class="pl-lg-4">
                            <div class="row">
                                <div class="col-md-6">
                                    @include('admin.pages.bannerarea', ['key' => 'banner_1', 'imagesize' => '623*691'])
                                </div>
                                <div class="col-md-6">
                                    @include('admin.pages.bannerarea', ['key' => 'banner_2', 'imagesize' => '425*337'])
                                </div>
                            </div>
                            <hr class="my-4" />
                            <div class="row">
                                <div class="col-md-6">
                                    @include('admin.pages.bannerarea', ['key' => 'banner_3', 'imagesize' => '425*337'])
                                </div>
                                <div class="col-md-6">
                                    @include('admin.pages.bannerarea', ['key' => 'banner_4', 'imagesize' => '881*332'])
                                </div>
                            </div>
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
											<option value="{{ $p->id }}" {{ in_array($p->id, $selected) ? 'selected' : ''}}>{{ $p->title }}</option>
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
										<label class="form-control-label" for="input-first-name">Trending</label>
										<select name="trending_products[]" class="form-control" multiple>
											<option value=""></option>
											<?php foreach($products as $p): ?>
											<option value="{{ $p->id }}" {{ in_array($p->id, $selected) ? 'selected' : ''}}>{{ $p->title }}</option>
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
										<label class="form-control-label" for="input-first-name">New Arrival</label>
										<select name="new_arrivals[]" class="form-control" multiple>
											<option value=""></option>
											<?php foreach($products as $p): ?>
											<option value="{{ $p->id }}" {{ in_array($p->id, $selected) ? 'selected' : ''}}>{{ $p->title }}</option>
											<?php endforeach; ?>
										</select>
									</div>
								</div>
							</div>
						</div>
						<hr class="my-4" />
						<h6 class="heading-small text-muted mb-4">Deals of the day information</h6>
                        <div class="pl-lg-4">
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
							@include('admin.pages.bannerarea', ['key' => 'deal_day', 'imagesize' => '1531*500', 'subheading' => true])
						</div>
						<hr class="my-4" />
						<h6 class="heading-small text-muted mb-4">Our Best Seller</h6>
                        <div class="pl-lg-4">
							<?php $enable  = HomePage::get('best_seller_enable'); ?>	
							<div class="row">	
								<div class="col-md-12 py-2">	
									<div class="custom-control">
										<label class="custom-toggle">
											<input type="hidden" name="best_seller_enable" value="0">
											<input type="checkbox" id="dealEnalbe" value="1" name="best_seller_enable" {{ $enable ? 'checked=""' : '' }}>
											<span class="custom-toggle-slider rounded-circle" data-label-off="No" data-label-on="Yes"></span>
										</label>
										<label class="custom-control-label">Active/Inactive</label>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-sm-8">
									@php
									$selected  = HomePage::get('best_products');
									$selected = $selected ? json_decode($selected, true) : [];
									@endphp
									<div class="form-group">
										<label class="form-control-label" for="input-first-name">Select Products</label>
										<select name="best_products[]" class="form-control" multiple>
											<option value=""></option>
											<?php foreach($products as $p): ?>
											<option value="{{ $p->id }}" {{ in_array($p->id, $selected) ? 'selected' : ''}}>{{ $p->title }}</option>
											<?php endforeach; ?>
										</select>
									</div>
								</div>
							</div>
						</div>
						<hr class="my-4" />
						<h6 class="heading-small text-muted mb-4">50*50 Blocks information</h6>
                        <div class="pl-lg-4">
							<?php $enable  = HomePage::get('left_right_grid_enable'); ?>	
							<div class="row">	
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
							</div>
							<div class="row">
								<div class="col-md-6">
									@include('admin.pages.bannerarea', ['key' => 'left_grid', 'imagesize' => '753*283'])
								</div>
								<div class="col-md-6">
									@include('admin.pages.bannerarea', ['key' => 'right_grid', 'imagesize' => '753*283'])
								</div>
							</div>
						</div>
						<hr class="my-4" />
						<h6 class="heading-small text-muted mb-4">Bottom banner information</h6>
                        <div class="pl-lg-4">
							<?php $enable  = HomePage::get('bottom_banner_enable'); ?>	
							<div class="row">	
								<div class="col-md-12 py-2">	
									<div class="custom-control">
										<label class="custom-toggle">
											<input type="hidden" name="bottom_banner_enable" value="0">
											<input type="checkbox" id="dealEnalbe" value="1" name="bottom_banner_enable" {{ $enable ? 'checked=""' : '' }}>
											<span class="custom-toggle-slider rounded-circle" data-label-off="No" data-label-on="Yes"></span>
										</label>
										<label class="custom-control-label">Active/Inactive</label>
									</div>
								</div>
							</div>
							@include('admin.pages.bannerarea', ['key' => 'bottom_banner', 'imagesize' => '1535*400'])
						</div>
						<hr class="my-4" />
						<h6 class="heading-small text-muted mb-4">Footer 4 icons</h6>
                        <div class="pl-lg-4">
							<div class="row">
								<div class="col-md-3">
									@include('admin.pages.bannerarea', ['key' => 'footer_icon_1', 'imagesize' => '148*175', 'nobuttons' => true])
								</div>
								<div class="col-md-3">
									@include('admin.pages.bannerarea', ['key' => 'footer_icon_2', 'imagesize' => '148*175', 'nobuttons' => true])
								</div>
								<div class="col-md-3">
									@include('admin.pages.bannerarea', ['key' => 'footer_icon_3', 'imagesize' => '148*175', 'nobuttons' => true])
								</div>
								<div class="col-md-3">
									@include('admin.pages.bannerarea', ['key' => 'footer_icon_4', 'imagesize' => '148*175', 'nobuttons' => true])
								</div>
							</div>
						</div>
						<hr class="my-4" />
                        <div class="pl-lg-4">
							<div class="row">
								<div class="col-md-6">
									<h6 class="heading-small text-muted mb-4">Footer information</h6>
									<div class="form-group">
										<label class="form-control-label" for="input-first-name">About Us Title</label>
										<input type="text" class="form-control" name="footer_title" placeholder="" value="{{ HomePage::get('footer_title') }}">
									</div>
									<div class="form-group">
										<label class="form-control-label" for="input-first-name">About Us Description</label>
										<textarea rows="2" class="form-control" name="footer_description" placeholder="">{{ HomePage::get('footer_description') }}</textarea>
									</div>
									<h6 class="heading-small text-muted mb-4">Social Links</h6>
									<div class="form-group">
										<div class="input-group input-group-alternative mb-4">
											<div class="input-group-prepend">
												<span class="input-group-text text-primary"><i class="fab fa-facebook"></i></span>
											</div>
											<input class="form-control form-control-alternative" type="url" name="facebook" placeholder="https://facebook.com/example" value="{{ HomePage::get('facebook') }}">
										</div>
									</div>
									<div class="form-group">
										<div class="input-group input-group-alternative mb-4">
											<div class="input-group-prepend">
												<span class="input-group-text text-primary"><i class="fab fa-twitter"></i></span>
											</div>
											<input class="form-control form-control-alternative" type="url" name="twitter" placeholder="https://twitter.com/example" value="{{ HomePage::get('twitter') }}">
										</div>
									</div>
									<div class="form-group">
										<div class="input-group input-group-alternative mb-4">
											<div class="input-group-prepend">
												<span class="input-group-text text-primary"><i class="fab fa-instagram"></i></span>
											</div>
											<input class="form-control form-control-alternative" type="url" name="instagram" placeholder="https://instagram.com/example" value="{{ HomePage::get('instagram') }}">
										</div>
									</div>
									<div class="form-group">
										<div class="input-group input-group-alternative mb-4">
											<div class="input-group-prepend">
												<span class="input-group-text text-primary"><i class="fab fa-youtube"></i></span>
											</div>
											<input class="form-control form-control-alternative" type="url" name="youtube" placeholder="https://youtube.com/example" value="{{ HomePage::get('youtube') }}">
										</div>
									</div>
									<div class="form-group">
										<div class="input-group input-group-alternative mb-4">
											<div class="input-group-prepend">
												<span class="input-group-text text-primary"><i class="fab fa-whatsapp"></i></span>
											</div>
											<input class="form-control form-control-alternative" type="text" name="whatsapp" placeholder="+21-2233445566" value="{{ HomePage::get('whatsapp') }}">
										</div>
									</div>

								</div>
								<div class="col-md-6">
									<h6 class="heading-small text-muted mb-4">Quick Links information</h6>
									<div class="form-group">
										<label class="form-control-label" for="input-first-name">Title</label>
										<input type="text" class="form-control" name="quick_links_title" placeholder="" value="{{ HomePage::get('quick_links_title') }}">
									</div>
									<?php for($k = 1; $k <=6; $k++): ?>
									<div class="row">
										<div class="col-sm-5">
											<label class="form-control-label" for="input-first-name">Link Title</label>
											<input type="text" class="form-control" name="{{ 'footer_link'.$k.'_title' }}" placeholder="" value="{{ HomePage::get('footer_link'.$k.'_title') }}">
										</div>
										<div class="col-sm-7">
											<div class="form-group">
												<label class="form-control-label" for="input-first-name">Link</label>
												<input type="text" class="form-control" name="{{ 'footer_link'.$k }}" placeholder="" value="{{ HomePage::get('footer_link'.$k) }}">
											</div>
										</div>
									</div>
									<?php endfor; ?>
								</div>
							</div>
							<div class="row">
								<div class="col-md-6">
									<h6 class="heading-small text-muted mb-4">Instagram Widget</h6>
									<div class="form-group">
										<label class="form-control-label" for="input-first-name">Paste your code here.</label>
										<textarea type="text" name="instagram_widget" class="form-control">{{ HomePage::get('instagram_widget') }}</textarea>
									</div>
								</div>
								<div class="col-md-6">
									<h6 class="heading-small text-muted mb-4">Newsletter</h6>
									<div class="form-group">
										<label class="form-control-label" for="input-first-name">Newsletter Text</label>
										<textarea type="text" name="newsletter_text" class="form-control">{{ HomePage::get('newsletter_text') }}</textarea>
									</div>
								</div>
							</div>
						</div>
						<hr class="my-4" />
						<!-- Address -->
						<h6 class="heading-small text-muted mb-4">Contact Information</h6>
						<div class="pl-lg-4">
							<div class="form-group">
								<label class="form-control-label" for="input-first-name">Phone</label>
								<textarea type="text" class="form-control" name="contact_phone" placeholder="">{{ HomePage::get('contact_phone') }}</textarea>
								@error('contact_phone')
								    <small class="text-danger">{{ $message }}</small>
								@enderror
							</div>
							<div class="form-group">
								<label class="form-control-label" for="input-first-name">Email</label>
								<textarea type="text" class="form-control" name="contact_email" placeholder="">{{ HomePage::get('contact_email') }}</textarea>
								@error('contact_email')
								    <small class="text-danger">{{ $message }}</small>
								@enderror
							</div>
							<div class="form-group">
								<label class="form-control-label" for="input-first-name">Address</label>
								<textarea type="text" class="form-control" name="contact_address" placeholder="">{{ HomePage::get('contact_address') }}</textarea>
								@error('contact_address')
								    <small class="text-danger">{{ $message }}</small>
								@enderror
							</div>
						</div>
						<h6 class="heading-small text-muted mb-4">SEO Meta Information</h6>
						<div class="pl-lg-4">
							<div class="form-group">
								<label class="form-control-label" for="input-first-name">Meta Title</label>
								<input type="text" class="form-control" name="meta_title" placeholder="Meta Title" value="{{ HomePage::get('meta_title') }}">
								@error('meta_title')
								    <small class="text-danger">{{ $message }}</small>
								@enderror
							</div>
							<div class="form-group">
								<label class="form-control-label">Meta Description</label>
								<textarea rows="2" class="form-control" placeholder="Your description" name="meta_description">{{ HomePage::get('meta_description') }}</textarea>
								@error('meta_description')
								    <small class="text-danger">{{ $message }}</small>
								@enderror
							</div>
							<div class="form-group">
								<label class="form-control-label" for="input-first-name">Meta Keywords</label>
								<input type="text" class="form-control" name="meta_keywords" placeholder="Meta Keywords" value="{{ HomePage::get('meta_keywords') }}">
								@error('meta_keywords')
								    <small class="text-danger">{{ $message }}</small>
								@enderror
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