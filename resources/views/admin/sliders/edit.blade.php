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
<div class="container-fluid mt--6" id="slider">
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
						<h6 class="heading-small text-muted mb-4">Slider information</h6>
						<div class="pl-lg-4">
							<div class="form-group">
								<div class="custom-control custom-radio custom-control-inline">
									<input type="radio" id="alltype" v-model="type" name="type" value="main" <?php echo (!old('type', $page->type) || old('type', $page->type) == 'main' ? 'checked' : '') ?> class="custom-control-input type-radio">
									<label class="custom-control-label" for="alltype">Main Banner</label>
								</div>
								<div class="custom-control custom-radio custom-control-inline">
									<input type="radio" id="onlytype" v-model="type" name="type" value="right_top" <?php echo (old('type', $page->type) == 'right_top' ? 'checked' : '') ?> class="custom-control-input type-radio">
									<label class="custom-control-label" for="onlytype">Right top</label>
								</div>
								<div class="custom-control custom-radio custom-control-inline">
									<input type="radio" id="supertype" v-model="type" name="type" value="right_bottom" <?php echo (old('type', $page->type) == 'right_bottom' ? 'checked' : '') ?> class="custom-control-input type-radio">
									<label class="custom-control-label" for="supertype">Right Bottom</label>
								</div>
							</div>
						<div class="row">
							<div class="col-md-6">
								<div class="form-group">
									<label class="form-control-label" for="input-first-name">Label</label>
									<input type="text" class="form-control" name="label"  placeholder="Label" value="{{ old('label', $page->label) }}">
									@error('label')
										<small class="text-danger">{{ $message }}</small>
									@enderror
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label class="form-control-label">Heading</label>
									<textarea type="text" class="form-control" placeholder="Heading" name="heading">{{ old('heading', $page->heading) }}</textarea>
									@error('heading')
										<small class="text-danger">{{ $message }}</small>
									@enderror
								</div>
							</div>
						</div>
						
						<div class="row" v-if="type=='main'">
							<div class="col-md-6">
								<div class="form-group">
									<label class="form-control-label">Sub Heading</label>
									<input type="text" class="form-control" placeholder="Sub Heading" name="sub_heading" value="{{ old('sub_heading', $page->sub_heading) }}">
									@error('sub_heading')
										<small class="text-danger">{{ $message }}</small>
									@enderror
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label class="form-control-label">Small Text</label>
									<input type="text" class="form-control" placeholder="Small Text" name="small_text" value="{{ old('small_text', $page->small_text	) }}">
									@error('small_text')
										<small class="text-danger">{{ $message }}</small>
									@enderror
								</div>
							</div>
						</div>
						<!-- <div class="row">
							<div class="col-md-6">
								<div class="form-group">
									<label class="form-control-label" for="input-first-name">Button (on/off)</label>
									<div class="custom-control mt-2">
										<label class="custom-toggle">
											<input type="checkbox" name="button_status" id="buttonStatus" value="1"
												<?php echo old('button_status', $page->button_status) ? 'checked' : ''; ?> required>
											<span class="custom-toggle-slider rounded-circle"
												data-label-off="No" data-label-on="Yes"></span>
										</label>
									</div>
								</div>
							</div>
						</div> -->
						<div id="buttonFields" >
							<div class="row">
								<div class="col-md-6">
									<div class="form-group">
										<label class="form-control-label" for="input-first-name">Button Title</label>
										<input type="text" class="form-control" name="button_title" required placeholder="Button Title" value="{{ old('button_title', $page->button_title) }}">
										@error('button_title')
											<small class="text-danger">{{ $message }}</small>
										@enderror
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<label class="form-control-label" for="input-first-name">Button Url</label>
										<input type="text" class="form-control" name="button_url" required placeholder="Button Url" value="{{ old('button_url', $page->button_url) }}">
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
											:data-resize-large="type == 'main' ? '1500*842' : (type == 'right_top' ? '600*712' : '600*334')"
										>
											<div class="upload-section">
												<div class="button-ref mb-3">
													<button class="btn btn-icon btn-primary btn-lg" type="button">
										                <span class="btn-inner--icon"><i class="fas fa-upload"></i></span>
										                <span class="btn-inner--text">Upload Image</span>
									              	</button>
													<p v-if="type == 'main'"><small>Recomeded Size: 1500px * 842px</small></p>
													<p v-else-if="type == 'right_top'"><small>Recomeded Size: 600px * 712px</small></p>
													<p v-else="type == 'right_bottom'"><small>Recomeded Size: 600px * 334px</small></p>
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

@push('scripts')
<script>var defaultType = '{{ old('type', $page->type) }}';</script>
@endpush