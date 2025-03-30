@extends('layouts.adminlayout')
@section('content')
<div class="header bg-primary pb-6">
	<div class="container-fluid">
		<div class="header-body">
			<div class="row align-items-center py-4">
				<div class="col-lg-6 col-7">
					<h6 class="h2 text-white d-inline-block mb-0">Manage Slider</h6>
				</div>
				<div class="col-lg-6 col-5 text-right">
					<a href="<?php echo route('admin.sliders') ?>" class="btn btn-neutral"><i class="ni ni-bold-left"></i> Back</a>
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
							<h3 class="mb-0">Create New Slider Here.</h3>
						</div>
					</div>
				</div>
				<div class="card-body">
					<form method="post" action="<?php echo route('admin.sliders.edit',['id' => $page->id]) ?>" class="form-validation">
						<!--!! CSRF FIELD !!-->
						{{ @csrf_field() }}
						<h6 class="heading-small text-muted mb-4">Rating information</h6>
						<div class="pl-lg-4">
						<div class="row">
							<div class="col-md-6">
								<div class="form-group">
									<label class="form-control-label" for="input-first-name">Label</label>
									<input type="text" class="form-control" name="label" required placeholder="Label" value="{{ old('label', $page->label) }}">
									@error('label')
										<small class="text-danger">{{ $message }}</small>
									@enderror
								</div>
							</div>
							
						</div>
						
						<div class="row">
							<div class="col-md-6">
								<div class="form-group">
									<label class="form-control-label">Heading</label>
									<textarea rows="2" class="form-control" placeholder="Heading" required name="heading">{{ old('heading', $page->heading) }}</textarea>
									@error('heading')
										<small class="text-danger">{{ $message }}</small>
									@enderror
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label class="form-control-label">Sub Heading</label>
									<textarea rows="2" class="form-control" placeholder="Sub Heading" name="sub_heading">{{ old('sub_heading', $page->sub_heading) }}</textarea>
									@error('sub_heading')
										<small class="text-danger">{{ $message }}</small>
									@enderror
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-6">
								<div class="form-group">
									<label class="form-control-label" for="input-first-name">Button (on/off)</label>
									<div class="custom-control mt-2">
										<label class="custom-toggle">
											<input type="checkbox" name="button_status" id="buttonStatus" value="1"
												<?php echo old('button_status', $page->button_status) ? 'checked' : ''; ?>>
											<span class="custom-toggle-slider rounded-circle"
												data-label-off="No" data-label-on="Yes"></span>
										</label>
									</div>
								</div>
							</div>
						</div>
						<div id="buttonFields" style="display: none;" >
							<div class="row">
								<div class="col-md-6">
									<div class="form-group">
										<label class="form-control-label" for="input-first-name">Button Title</label>
										<input type="text" class="form-control" name="button_title" placeholder="Button Title" value="{{ old('button_title', $page->button_title) }}">
										@error('button_title')
											<small class="text-danger">{{ $message }}</small>
										@enderror
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<label class="form-control-label" for="input-first-name">Button Url</label>
										<input type="text" class="form-control" name="button_url" placeholder="Button Url" value="{{ old('button_url', $page->button_url) }}">
										@error('button_url')
											<small class="text-danger">{{ $message }}</small>
										@enderror
									</div>
								</div>
							</div>
						</div>
						<hr class="my-4" />
						<!-- Address -->
						<h6 class="heading-small text-muted mb-4">Publish Slider Image</h6>
						<div class="pl-lg-4">
							<div class="row">
								<div class="col-lg-6">
									<div class="form-group">
										<!-- FILE OR IMAGE UPLOAD. FOLDER PATH SET HERE in data-path AND CHANGE THE data-multiple TO TRUE SEE MAGIC. DO NOT REMOVE THE NESTED CALSSES -->
										<div 
											class="upload-image-section"
											data-type="image"
											data-multiple="false"
											data-path="sliders"
											data-resize-large="1920*753"
											data-resize-small="192*74"
										>
											<div class="upload-section">
												<div class="button-ref mb-3">
													<button class="btn btn-icon btn-primary btn-lg" type="button">
										                <span class="btn-inner--icon"><i class="fas fa-upload"></i></span>
										                <span class="btn-inner--text">Upload Image</span>
									              	</button>
													<p><small>Recomeded Size: 1920*753</small></p>
									            </div>
									            <!-- PROGRESS BAR -->
												<div class="progress d-none">
								                  <div class="progress-bar bg-default" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%;"></div>
								                </div>
								            </div>
							                <!-- INPUT WITH FILE URL -->
							                <textarea class="d-none" name="image"></textarea>
							                <div class="show-section <?php echo !old('image') ? 'd-none' : "" ?>">
							                	@include('admin.partials.previewFileRender', ['file' => old('image',$page->image) ])
							                </div>
							                <div class="fixed-edit-section">
							                	@include('admin.partials.previewFileRender', ['file' => $page->image, 'relationType' => 'sliders.image', 'relationId' => $page->id ])
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