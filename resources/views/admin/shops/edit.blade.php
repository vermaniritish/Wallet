@extends('layouts.adminlayout')
@section('content')
<div class="header bg-primary pb-6">
	<div class="container-fluid">
		<div class="header-body">
			<div class="row align-items-center py-4">
				<div class="col-lg-6 col-7">
					<h6 class="h2 text-white d-inline-block mb-0">Manage Shops</h6>
				</div>
				<div class="col-lg-6 col-5 text-right">
					<a href="<?php echo route('admin.shops') ?>" class="btn btn-neutral"><i class="ni ni-bold-left"></i> Back</a>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- Page content -->
<div class="container-fluid mt--6">
	<form method="post" action="<?php echo route('admin.shops.edit', ['id' => $shop->id]) ?>" class="form-validation">
		<!--!! CSRF FIELD !!-->
		{{ @csrf_field() }}
		<div class="row">
			<div class="col-xl-12 order-xl-1">
				<div class="card">
					<!--!! FLAST MESSAGES !!-->
					@include('admin.partials.flash_messages')
					
					<div class="card-header">
						<div class="row align-items-center">
							<div class="col-8">
								<h3 class="mb-0">Update Shops Details Here.</h3>
							</div>
						</div>
					</div>
					<div class="card-body">		
						<h6 class="heading-small text-muted mb-4">Shop information</h6>
						<div class="pl-lg-4">
							<div class="form-group">
								<label class="form-control-label" for="input-first-name">Shop Owner</label>
								<select class="form-control" name="user_id" required="">
							      	<option value="">Select</option>
							      	<?php 
							      		foreach($users as $s): 
							      		$content = $s->name . "<small class='badge badge-".($s->status ? "success" : "danger")."'>".($s->status ? "Active" : "Inactive")."</small>";
							      	?>

							      		<option 
							      			value="<?php echo $s->id ?>" 
							      			<?php echo $s->id == $shop->user_id  ? 'selected' : '' ?>
							      			data-content="<?php echo $content ?>"
							      		>
							      			<?php echo $s->name; ?>		
							      		</option>
							  		<?php endforeach; ?>
							    </select>
								@error('user_id')
								    <small class="text-danger">{{ $message }}</small>
								@enderror
							</div>
							<div class="form-group">
								<label class="form-control-label" for="input-name">Shop Name</label>
								<input type="text" class="form-control" name="name" required placeholder="Name" value="<?php echo $shop->name ?>">
								@error('name')
								    <small class="text-danger">{{ $message }}</small>
								@enderror
							</div>
							<div class="row">
								<div class="col-lg-12">
									<div class="form-group">
										<label class="form-control-label" for="input-website">Shop Website</label>
										<input type="text" id="input-website" class="form-control" placeholder="Website Link" name="website"  value="<?php echo $shop->website ?>">
										@error('website')
										    <small class="text-danger">{{ $message }}</small>
										@enderror
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-lg-12">
									<div class="form-group">
										<label class="form-control-label" for="input-social_links">Social Link</label>
										<input type="text" id="input-social_links" class="form-control" placeholder="Social Link" name="social_links" value="<?php echo $shop->social_links ?>" >
										@error('social_links')
										    <small class="text-danger">{{ $message }}</small>
										@enderror
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-lg-12">
									<div class="form-group">
										<label>Bio</label>
										<textarea rows="4" class="form-control" placeholder="Bio" name="bio"><?php echo $shop->bio ?></textarea>
										@error('bio')
										    <small class="text-danger">{{ $message }}</small>
										@enderror
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-md-6">
									<!-- FILE OR IMAGE UPLOAD. FOLDER PATH SET HERE in data-path AND CHANGE THE data-multiple TO TRUE SEE MAGIC  -->
									<div 
										class="upload-image-section"
										data-type="image"
										data-multiple="false"
										data-path="shops"
										data-resize-medium="350*350"
										data-resize-small="100*100"
									>
										<div class="upload-section">
											<div class="button-ref mb-3">
												<button class="btn btn-icon btn-primary btn-lg" type="button">
									                <span class="btn-inner--icon"><i class="fas fa-upload"></i></span>
									                <span class="btn-inner--text">Upload Logo</span>
								              	</button>
								              	@error('image')
												    <small class="text-danger">{{ $message }}</small>
												@enderror
								            </div>
								            <!-- PROGRESS BAR -->
											<div class="progress d-none">
							                  <div class="progress-bar bg-default" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%;"></div>
							                </div>
							            </div>
						                <!-- INPUT WITH FILE URL -->
						                <textarea class="d-none" name="image"><?php echo old('image') ?></textarea>
						                <div class="show-section <?php echo !old('image') ? 'd-none' : "" ?>">
						                	@include('admin.partials.previewFileRender', ['file' => old('image') ])
						                </div>
						                <div class="fixed-edit-section">
						                	@include('admin.partials.previewFileRender', ['file' => $shop->image, 'relationType' => 'shops.image', 'relationId' => $shop->id ])
						                </div>
									</div>
								</div>	
							</div>
						</div>
						<hr class="my-4" />
						<!-- Address -->
						<h6 class="heading-small text-muted mb-4">Address Information</h6>
						<div class="pl-lg-4">
							<div class="form-group">
								<label class="form-control-label">Address</label>
								<textarea rows="2" class="form-control" placeholder="Your address" required name="address"><?php echo $shop->address ?></textarea>
								@error('address')
								    <small class="text-danger">{{ $message }}</small>
								@enderror
							</div>
							<div class="form-group">
							    <label class="form-control-label">Latitude</label>
							    <input type="text" class="form-control" placeholder="e.g. 30.7333" name="lat" 
							           value="{{ $shop->lat ?? old('lat') }}">
							    @error('latitude')
							        <small class="text-danger">{{ $message }}</small>
							    @enderror
							</div>

							<div class="form-group">
							    <label class="form-control-label">Longitude</label>
							    <input type="text" class="form-control" placeholder="e.g. 76.7794" name="lng" 
							           value="{{ $shop->lng ?? old('lng') }}">
							    @error('lng')
							        <small class="text-danger">{{ $message }}</small>
							    @enderror
							</div>

							<div class="form-group">
								<label class="form-control-label" for="input-postcode">Postcode</label>
								<input type="text" id="input-postcode" class="form-control" required placeholder="Postcode" name="postcode"  value="<?php echo $shop->postcode ?>">
								@error('postcode')
								    <small class="text-danger">{{ $message }}</small>
								@enderror
							</div>
							
						</div>
						<hr class="my-4" />
						<button href="#" class="btn btn-sm py-2 px-3 btn-primary float-right">
							<i class="fa fa-save"></i> Submit
						</button>
					</div>
				</div>
			</div>
		</div>
	</form>
</div>
@endsection