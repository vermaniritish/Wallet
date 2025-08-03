<?php use Illuminate\Support\Arr; ?>
@extends('layouts.adminlayout')
@section('content')
<div class="header bg-primary pb-6">
	<div class="container-fluid">
		<div class="header-body">
			<div class="row align-items-center py-4">
				<div class="col-lg-6 col-7">
					<h6 class="h2 text-white d-inline-block mb-0">Manage Schools</h6>
				</div>
				<div class="col-lg-6 col-5 text-right">
					<a href="<?php echo route('admin.schools') ?>" class="btn btn-neutral"><i class="ni ni-bold-left"></i> Back</a>
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
							<h3 class="mb-0">Update School Details Here.</h3>
						</div>
					</div>
				</div>
				<div class="card-body">
					<form method="POST" action="{{ route('admin.schools.edit', ['id' => $page->id]) }}" class="form-validation">
					    @csrf

					    <h6 class="heading-small text-muted mb-4">School Information</h6>
					    <div class="pl-lg-4">
					        <div class="form-group">
					            <label class="form-control-label" for="name">Name </label>
					            <input type="text" class="form-control" name="name" id="name" required placeholder="Name" value="{{ old('name', $page->name) }}">
					            @error('name')
					                <small class="text-danger">{{ $message }}</small>
					            @enderror
					        </div>

					        {{-- School Type --}}
					        <div class="form-group">
					            <label class="form-control-label" for="schooltype">School Type</label>
					            <select class="form-control" name="schooltype" id="schooltype">
					                <option value="Junior" {{ old('schooltype', $page->schooltype) == 'Junior' ? 'selected' : '' }}>Junior</option>
					                <option value="Senior" {{ old('schooltype', $page->schooltype) == 'Senior' ? 'selected' : '' }}>Senior</option>
					                <option value="High School" {{ old('schooltype', $page->schooltype) == 'High School' ? 'selected' : '' }}>High School</option>
					            </select>
					        </div>

					        {{-- House Names --}}
					        <div class="form-group">
					            <label class="form-control-label" for="house_names">House Names</label>
					            <input type="text" class="form-control" name="house_names" value="{{ old('house_names', $page->house_names) }}" placeholder="Red, Blue, Green" required>
					            @error('house_names')
					                <small class="text-danger">{{ $message }}</small>
					            @enderror
					        </div>

					        {{-- Display Order --}}
					        <!-- <div class="form-group">
					            <label class="form-control-label" for="displayorder">Display Order</label>
					            <input type="number" class="form-control" name="displayorder" id="displayorder" value="{{ old('displayorder', $page->displayorder ?? 1) }}" min="1">
					            @error('displayorder')
					                <small class="text-danger">{{ $message }}</small>
					            @enderror
					        </div> -->

					        {{-- Shipping Charges --}}
					        <div class="form-group">
					            <label class="form-control-label" for="shipping_charges">Shipping Charges</label>
					            <input type="number" step="0.01" name="shipping_charges" class="form-control" value="{{ old('shipping_charges', $page->shipping_charges) }}">
					        </div>

					        {{-- Description --}}
					        <!-- <div class="form-group">
					            <label class="form-control-label">Description</label>
					            <textarea rows="5" id="editor1" class="form-control" name="description" placeholder="Enter your description">{{ old('description', $page->description) }}</textarea>
					            @error('description')
					                <small class="text-danger">{{ $message }}</small>
					            @enderror
					        </div> -->
					    </div>

					    <hr class="my-4" />
					    <h6 class="heading-small text-muted mb-4">Contact Details</h6>
					    <div class="pl-lg-4">
					        <div class="row">
					            <div class="col-lg-6 form-group">
					                <label class="form-control-label">Address</label>
					                <input type="text" class="form-control" name="address" value="{{ old('address', $page->address) }}">
					            </div>
					            <div class="col-lg-3 form-group">
					                <label class="form-control-label">City</label>
					                <input type="text" class="form-control" name="city" value="{{ old('city', $page->city) }}">
					            </div>
					            <div class="col-lg-3 form-group">
					                <label class="form-control-label">Country</label>
					                <input type="text" class="form-control" name="country" value="{{ old('country', $page->country) }}">
					            </div>
					        </div>
					        <div class="row">
					            <div class="col-lg-6 form-group">
					                <label class="form-control-label">Phone</label>
					                <input type="text" class="form-control" name="phone" value="{{ old('phone', $page->phone) }}">
					            </div>
					            <div class="col-lg-6 form-group">
					                <label class="form-control-label">Email</label>
					                <input type="email" class="form-control" name="email" value="{{ old('email', $page->email) }}">
					            </div>
					        </div>
					    </div>

					    <hr class="my-4" />
					    <h6 class="heading-small text-muted mb-4">School Logo</h6>
					    <div class="pl-lg-4">
					        <div class="row">
					            <div class="col-lg-6">
					                <div 
					                    class="upload-image-section"
					                    data-type="image"
					                    data-multiple="false"
					                    data-path="schools"
					                    data-resize-large="70*18"
					                    data-resize-small="70*18"
					                >
					                    <div class="upload-section">
					                        <div class="button-ref mb-3">
					                            <button class="btn btn-icon btn-primary btn-lg" type="button">
					                                <span class="btn-inner--icon"><i class="fas fa-upload"></i></span>
					                                <span class="btn-inner--text">Upload Logo</span>
					                            </button>
					                        </div>

					                        <div class="progress d-none">
					                            <div class="progress-bar bg-default" role="progressbar" style="width: 0%;"></div>
					                        </div>
					                    </div>

					                    {{-- Hidden input to store logo URL --}}
					                    <textarea class="d-none" name="logo">{{ old('logo', $page->logo) }}</textarea>

					                    {{-- Old uploaded logo preview --}}
					                    <div class="show-section {{ !old('logo') ? 'd-none' : '' }}">
					                        @include('admin.partials.previewFileRender', ['file' => old('logo') ])
					                    </div>

					                    {{-- Fixed preview from DB --}}
					                    <div class="fixed-edit-section">
					                        @include('admin.partials.previewFileRender', [
					                            'file' => $page->logo,
					                            'relationType' => 'schools.image',
					                            'relationId' => $page->id
					                        ])
					                    </div>
					                </div>
					            </div>
					            {{-- Status --}}
							    <div class="col-lg-6">
									<div class="form-group">
										<div class="custom-control">
											<label class="custom-toggle">
												<input type="hidden" name="status" value="0">
												<input type="checkbox" name="status" value="1" <?php echo ($page->status != '0' ? 'checked' : '') ?>>
												<span class="custom-toggle-slider rounded-circle" data-label-off="No" data-label-on="Yes"></span>
											</label>
											<label class="custom-control-label">Do you want to publish this page ?</label>
										</div>
									</div>
								</div>
					        </div>
					    </div>

					    <hr class="my-4" />
					    <div class="pl-lg-4 mt-4">
					        <button type="submit" class="btn btn-sm py-2 px-3 btn-primary float-right">
					            <i class="fa fa-save"></i> Submit
					        </button>
					    </div>
					</form>

				</div>
			</div>
		</div>
	</div>
</div>
@endsection