@extends('layouts.adminlayout')
@section('content')
<div class="header bg-primary pb-6">
	<div class="container-fluid">
		<div class="header-body">
			<div class="row align-items-center py-4">
				<div class="col-lg-6 col-7">
					<h6 class="h2 text-white d-inline-block mb-0">Manage Staff</h6>
				</div>
				<div class="col-lg-6 col-5 text-right">
					<a href="<?php echo route('admin.staff') ?>" class="btn btn-neutral"><i class="ni ni-bold-left"></i> Back</a>
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
							<h3 class="mb-0">Create New Staff Here.</h3>
						</div>
					</div>
				</div>
				<div class="card-body">
					<form method="post" action="<?php echo route('admin.staff.add') ?>" class="form-validation">
						<!--!! CSRF FIELD !!-->
						{{ @csrf_field() }}
						<h6 class="heading-small text-muted mb-4">Staff information</h6>
						<div class="pl-lg-4">
						<div class="row">
								<div class="col-md-12">
									<div class="form-group">
										<label class="form-control-label" for="input-first-name">Shops</label>
										<select class="form-control" name="shops[]" multiple>
											@foreach($shops as $k => $s)
											<option value="{{$s->id}}" {{ (old('shops') && in_array($s->id, old('shops')) ? 'selected' : '') }} data-subtext="{{ ($s->status ? 'Active' : 'Inactive') }}">{{$s->name}}</option>
											@endforeach							
										</select>
										@error('shops')
											<small class="text-danger">{{ $message }}</small>
										@enderror
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<label class="form-control-label" for="input-first-name">First Name</label>
										<input type="text" class="form-control" name="first_name" required placeholder="First Name" value="{{ old('first_name') }}">
										@error('first_name')
											<small class="text-danger">{{ $message }}</small>
										@enderror
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<label class="form-control-label" for="input-first-name">Last Name</label>
										<input type="text" class="form-control" name="last_name" placeholder="Last Name" value="{{ old('last_name') }}">
										@error('last_name')
											<small class="text-danger">{{ $message }}</small>
										@enderror
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-md-6">
									<div class="form-group">
										<label class="form-control-label" for="input-first-name">Phone Number</label>
										<input type="number" class="form-control" name="phone_number" required placeholder="Phone Number" value="{{ old('phone_number') }}">
										@error('phone_number')
											<small class="text-danger">{{ $message }}</small>
										@enderror
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<label class="form-control-label" for="input-first-name">Email</label>
										<input class="form-control" type="email" name="email" required placeholder="Email" value="{{ old('email') }}">
										@error('email')
											<small class="text-danger">{{ $message }}</small>
										@enderror
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-md-6">
									<div class="form-group">
										<label class="form-control-label" for="input-first-name">Authorization ID</label>
										<input type="number" class="form-control" name="password" required placeholder="Id Number" value="{{ old('password') }}">
										@error('password')
											<small class="text-danger">{{ $message }}</small>
										@enderror
									</div>
								</div>
							</div>
						</div>
						<hr class="my-4" />
						<!-- Address -->
						<h6 class="heading-small d-none text-muted mb-4">Publish Staff Image</h6>
						<div class="pl-lg-4 d-none">
							<div class="row">
								<div class="col-lg-6">
									<div class="form-group">
										<!-- FILE OR IMAGE UPLOAD. FOLDER PATH SET HERE in data-path AND CHANGE THE data-multiple TO TRUE SEE MAGIC  -->
										<div 
											class="upload-image-section"
											data-type="image"
											data-multiple="false"
											data-path="brands"
											data-resize-large="920*640"
											data-resize-medium="400*250"
											data-resize-small="100*70"
										>
											<div class="upload-section">
												<div class="button-ref mb-3">
													<button class="btn btn-icon btn-primary btn-lg" type="button">
										                <span class="btn-inner--icon"><i class="fas fa-upload"></i></span>
										                <span class="btn-inner--text">Upload Image</span>
									              	</button>
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
										</div>
									</div>
								</div>
							</div>
						</div>
						<hr class="my-4 d-none" />
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