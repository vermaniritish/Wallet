@extends('layouts.adminlayout')
@section('content')
<div class="header bg-primary pb-6">
	<div class="container-fluid">
		<div class="header-body">
			<div class="row align-items-center py-4">
				<div class="col-lg-6 col-7">
					<h6 class="h2 text-white d-inline-block mb-0">Manage Uniforms</h6>
				</div>
				<div class="col-lg-6 col-5 text-right">
					<a href="<?php echo route('admin.uniforms') ?>" class="btn btn-neutral"><i class="ni ni-bold-left"></i> Back</a>
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
							<h3 class="mb-0">Create New Uniform Here.</h3>
						</div>
					</div>
				</div>
				<div class="card-body">
					<div id="product" >
						<p v-if="mounting" class="text-center big" style="padding: 15%"><i style="font-size: 30px" class="fa fa-spin fa-spinner"></i></p>
						<form id="product-form" method="post" action="<?php echo route('admin.uniforms.add') ?>" class="form-validation d-none">
							<pre id="availableColor" class="d-none">{{ $colors }}</pre>
							<pre id="availableSizes" class="d-none">{{ $sizes }}</pre>
							<!--!! CSRF FIELD !!-->
							{{ @csrf_field() }}
							<h6 class="heading-small text-muted mb-4">Uniform information</h6>
							<div class="pl-lg-4">
								<?php if(!$product): ?>
									<div id="sub-category-form" class="row">
										<div class="col-md-6">
											<div class="form-group">
												<label class="form-control-label" for="input-first-name">Category</label>
												<select v-model="selectedCategory" class="no-selectpicker form-control" name="category" required  @change="updateProducts">
												<?php foreach($categories as $c): ?>
													<option 
														value="<?php echo $c->id ?>" 
														<?php echo old('category') && in_array($c->id, old('category'))  ? 'selected' : '' ?> 
													><?php echo $c->title ?></option>
												<?php endforeach; ?>
												</select>
												@error('category')
													<small class="text-danger">{{ $message }}</small>
												@enderror
											</div>
										</div>
										
										<div class="col-md-6">
											<div class="form-group">
												<label class="form-control-label" for="input-first-name">Sub Category</label>
												<select class="form-control no-selectpicker" v-model="selectedSubCategory" name="sub_category" @change="updateProducts">
													<option value=""></option>
													<option v-for="subCategory in subCategories" :key="subCategory.id" :value="subCategory.id">
														@{{ subCategory.title }}
													</option>
												</select>
												@error('category')
													<small class="text-danger">{{ $message }}</small>
												@enderror
											</div>
										</div>
									</div>
									<div class="form-group">
										<label class="form-control-label" for="input-first-name">Product</label>
										<div v-if="products && products.length > 0">
											<div>
												<select id="productDropdown" v-model="selectedProduct" data-live-search="true" class="form-control no-selectpicker" name="product" placeholder="Product" required @change="initEditValues">
													<option value=""></option>
													<option v-for="p in products" :value="p.id">@{{ p.title }} - @{{ p.sku_number }}</option>
												</select>
											</div>
										</div>
										<div v-else><p>No products available. Please adjust the categories to search and select product.</p></div>
										@error('product')
											<small class="text-danger">{{ $message }}</small>
										@enderror
									</div>
									<hr />
									<div class="form-group">
										<label class="form-control-label" for="input-first-name">School</label>
										<select v-model="schools" class="form-control no-selectpicker" name="schools[]" placeholder="Schools" required>
											<option value=""></option>
											@foreach($schools as $s)
											<option value="{{ $s->id }}">{{ $s->name }} - {{ $s->schooltype }} - {{ $s->city }}</option>
											@endforeach
										</select>
										@error('title')
											<small class="text-danger">{{ $message }}</small>
										@enderror
									</div>
								<?php else: ?>
									<div class="form-group">
										<p>School: <strong>{{ $product && $product->school ? $product->school->name : '' }}</strong> </p>
										<p>Product: <strong>{{ $product && $product->parent ? $product->parent->title . ' - ' . $product->sku_number : '' }}</strong> </p>
									</div>
								<?php endif; ?>
								<div class="row">
									<div class="col-md-6">
										<div class="form-group">
											<label class="form-control-label" for="input-first-name">Title</label>
											<input type="text" v-model="title" class="form-control" name="title" placeholder="Title" required value="{{ old('title') }}">
											@error('title')
												<small class="text-danger">{{ $message }}</small>
											@enderror
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group">
											<label class="form-control-label" for="input-username">Gender Specific To ?</label>
											<select v-model="selectedGender" required class="form-control no-selectpicker" name="gender">
												<option {{ old('gender') == 'Male' ? 'selected' : '' }}
													value="Male"> Male</option>
												<option {{ old('gender') == 'Female' ? 'selected' : '' }}
													value="Female"> Female</option>
												<option {{ old('gender') == 'Kids' ? 'selected' : '' }}
													value="Kids"> Kids</option>
												<option {{ old('gender') == 'Unisex' ? 'selected' : '' }}
													value="Unisex"> Unisex</option>
											</select>
											@error('gender')
												<small class="text-danger">{{ $message }}</small>
											@enderror
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-lg-12">
										<div class="form-group">
											<label class="form-control-label">Short Description</label>
											<textarea v-model="short_description" rows="2" class="form-control" placeholder="Short Description" name="short_description">{{ old('short_description') }}</textarea>
											@error('short_description')
												<small class="text-danger">{{ $message }}</small>
											@enderror
										</div>
									</div>
								</div>
								<div class="row">
									
									<div class="col-lg-6">
										<div class="form-group">
											<label class="form-control-label" for="input-first-name">Purchase Price</label>
											<input type="number" min="0" class="form-control" v-model="purchase_price" name="purchase_price" placeholder="Purchase Price" required value="{{ old('purchase_price') }}"  v-on:input="calculatePrice">
											@error('price')
												<small class="text-danger">{{ $message }}</small>
											@enderror
										</div>
									</div>
									<div class="col-lg-6">
										<div class="form-group">
											<label class="form-control-label" for="input-first-name">Margin (% Percentage)</label>
											<input type="number" min="0" class="form-control" v-model="margin" name="margin" placeholder="10%" required value="{{ old('margin') }}" v-on:input="calculatePrice">
											@error('price')
												<small class="text-danger">{{ $message }}</small>
											@enderror
										</div>
									</div>
									<div class="col-lg-6">
										<div class="form-group">
											<label class="form-control-label" for="input-first-name">Price</label>
											<input type="number" min="0" class="form-control" v-model="price" name="price" placeholder="Price" required value="{{ old('price') }}">
											@error('price')
												<small class="text-danger">{{ $message }}</small>
											@enderror
										</div>
									</div>
									<div class="col-lg-6">
										<div class="form-group">
											<label class="form-control-label" for="input-first-name">Max Price</label>
											<input type="number" class="form-control" v-model="maxPrice" name="max_price" <?php echo (old('status')) ?> placeholder="Max Price" value="{{ old('max_price') }}">
											@error('max_price')
												<small class="text-danger">{{ $message }}</small>
											@enderror
										</div>
									</div>
								</div>
								<div id="size-form" class="row">
									
									<div class="col-lg-6">
										<div class="form-group">
											<label class="form-control-label" for="input-tags">Tag</label>
											<input type="text" class="form-control tag" name="tags" v-model="tags" placeholder="Enter tags here." value="{{ old('tags') }}">
											@error('tags.*')
												<small class="text-danger">{{ $message }}</small>
											@enderror
										</div>
									</div>
									<div class="col-lg-6">
										<div class="form-group">
											<label class="form-control-label" for="input-first-name">SKU Number</label>
											<input type="text" class="form-control" v-model="sku_number" name="sku_number" required placeholder="SKU Number" value="{{ old('sku_number') }}">
											@error('sku_number')
												<small class="text-danger">{{ $message }}</small>
											@enderror
										</div>
									</div>
								</div>
							</div>
							<hr class="my-4" />
							<h6 class="heading-small text-muted mb-4">Price, Sizes and Colors</h6>
							<div class="pl-lg-4">
								<div class="row">
									<div class="col-lg-6">
										<div class="form-group">
											<label class="form-control-label">Sizes</label>
											<select class="form-control size-select no-selectpicker" v-on:change="markActiveColor(activeColor)" v-model="defaultSizes" multiple>
												<option v-for="size in sizes" :value="size.id">
													@{{ size.size_title }} (@{{ size.from_cm }} - @{{ size.to_cm }} cm)
												</option>
											</select>
										</div>
									</div>
									<div class="col-lg-6">
										<div class="form-group">
											<label class="form-control-label">Color</label>
											<select class="form-control no-selectpicker" v-on:change="updateSelectedColor" v-model="selectedColor" name="color_id[]" multiple required>
												<?php 
													foreach($colors as $s): 
													$content = $s->title . ' (' . $s->color_code . ')';
												?>
												<option 
													value="<?php echo $s->id ?>" 
													<?php echo old('color_id') == $s->id  ? 'selected' : '' ?>
													data-content="<?php echo $content ?>"
												>
													<?php echo $s->name; ?>		
												</option>
												<?php endforeach; ?>
											</select>
											@error('colr_id')
											<small class="text-danger">{{ $message }}</small>
											@enderror
										</div>
									</div>
								</div>	
								<div class="row">		
									<div class="col-md-12">
										<div class="form-group">
											<label class="form-control-label">Select Size For</label> <br />
												<span v-for="(color, i) in availableColors" 
													v-if="selectedColor.includes(color.id.toString())"
													:style="{ backgroundColor: color.color_code, color: '#000' }" :class="`mx-1 badge badge-lg badge-secondary ` + (color.id == activeColor ? 'active' : '' )" v-on:click="markActiveColor(color.id)">@{{ color.code ? color.code : color.title }}
											</span>
											
											<small class="text-danger"></small>
										</div>
									</div>
								</div>
								<div v-for="(colorSelectedId, colorIndex) in selectedColor" :key="colorIndex">									
									<div v-if="colorSelectedId == activeColor">
										<div class="table-responsive">
											<table class="table align-items-center table-flush view-table">
												<thead>
													<tr>
														<th>#</th>
														<th>Size Title</th>
														<th>Size (From - To)</th>
														<th>Price</th>
														<th>Sale Price</th>
														<th>In Stock</th>
														<th>Remove Item</th>
													</tr>
												</thead>
												<tbody id="sortable">
													<tr v-for="(size, sizeIndex) in selectedSize[colorSelectedId]" :key="sizeIndex" draggable="true" @dragstart="drag(colorSelectedId, sizeIndex)"  @drop="drop(colorSelectedId, sizeIndex)" @dragover="allowDrop">
														<td>
														<i class="fas fa-ellipsis-v" style="background: whitesmoke;padding: 6px;margin-right: 10px;"></i>
														@{{ sizeIndex + 1 }}
														</td>
														<td>@{{ size.size_title }}</td>
														<td>@{{ size.from_cm }} - @{{ size.to_cm }} cm</td>
														<td><input required type="number" v-model="size.price" min="0"></td>
														<td><input type="number" v-model="size.sale_price" min="0"></td>
														<td>
															<div class="custom-control">
																<label class="custom-toggle">
																	<input type="checkbox" v-model="size.status">
																	<span class="custom-toggle-slider rounded-circle" data-label-off="OFF" data-label-on="ON"></span>
																</label>
															</div>
														</td>
														<td><i class="fa fa-times" v-on:click="removeSize(colorSelectedId, sizeIndex)"></i></td>
													</tr>
												</tbody>
											</table>
										</div>
										<br />
										<label class="form-control-label">Please upload colored product image.</label><br />
										<div class="row">
										<div 
											class="col-sm-4 upload-image-section vue-image"
											data-type="image"
											data-multiple="true"
											data-path="products"
											data-resize-large="580*630"
											data-resize-small="282*310"

										>
											<div class="upload-section">
												<div class="mb-3">
													
													<button v-on:click="updateImage(colorSelectedId)" :id="`colorImage`+colorSelectedId" class="btn btn-icon btn-primary btn-lg" type="button">
														<span class="btn-inner--icon"><i class="fas fa-upload"></i></span>
														<span class="btn-inner--text">Upload Image</span>
														</button>
													<p><small>Recommend Size: 580*630</small></p>
												</div>
												<!-- PROGRESS BAR -->
												<div class="progress d-none">
													<div class="progress-bar bg-default" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%;"></div>
												</div>
											</div>
										</div>
										<div class="col-sm-8"><img v-if="colorImages && colorImages[colorSelectedId] && colorImages[colorSelectedId].path" :src="colorImages[colorSelectedId].path" style="max-height:100px;max-width:100px" /></div>
										</div>
									</div>
								</div>
							</div>
							<hr class="my-4" />
							<!-- Address -->
							<h6 class="heading-small text-muted mb-4">Publish Information</h6>
							<div class="pl-lg-4">
								<div class="row">
									<div class="col-lg-6">
										<div class="form-group">
											<!-- FILE OR IMAGE UPLOAD. FOLDER PATH SET HERE in data-path AND CHANGE THE data-multiple TO TRUE SEE MAGIC  -->
											<label>Upload Banner.</label>
											<div 
												class="upload-image-section"
												data-type="image"
												data-multiple="true"
												data-path="products"
												data-resize-large="580*630"
												data-resize-small="282*310"

											>
												<div class="upload-section">
													<div class="button-ref mb-3">
														<button class="btn btn-icon btn-primary btn-lg" type="button">
															<span class="btn-inner--icon"><i class="fas fa-upload"></i></span>
															<span class="btn-inner--text">Upload Image</span>
															</button>
														<p><small>Recommend Size: 580*630</small></p>
													</div>
													<!-- PROGRESS BAR -->
													<div class="progress d-none">
														<div class="progress-bar bg-default" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%;"></div>
													</div>
												</div>
												<!-- INPUT WITH FILE URL -->
												<textarea class="d-none" required name="image"><?php echo old('image') ?></textarea>
												<div class="show-section <?php echo !old('image') ? 'd-none' : "" ?>">
													@include('admin.partials.previewFileRender', ['file' => old('image') ])
												</div>
												<?php if(isset($product) && $product): ?>
												<div class="fixed-edit-section">
													@include('admin.partials.previewFileRender', ['file' => $product->image, 'relationType' => 'products.image', 'relationId' => $product->id ])
												</div>
												<?php endif; ?>
											</div>
											@error('image')
												<small class="text-danger">{{ $message }}</small>
											@enderror
										</div>
									</div>
									<div class="col-lg-6">
										<div class="form-group">
											<!-- FILE OR IMAGE UPLOAD. FOLDER PATH SET HERE in data-path AND CHANGE THE data-multiple TO TRUE SEE MAGIC  -->
											<label>Upload size guide PDF.</label>
											<div 
												class="upload-image-section"
												data-type="file"
												data-multiple="false"
												data-path="products"

											>
												<div class="upload-section">
													<div class="button-ref mb-3">
														<button class="btn btn-icon btn-primary btn-lg" type="button">
															<span class="btn-inner--icon"><i class="fas fa-upload"></i></span>
															<span class="btn-inner--text">Upload File</span>
															</button>
													</div>
													<!-- PROGRESS BAR -->
													<div class="progress d-none">
														<div class="progress-bar bg-default" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%;"></div>
													</div>
												</div>
												<!-- INPUT WITH FILE URL -->
												<textarea class="d-none" required name="size_file"><?php echo old('size_file') ?></textarea>
												<div class="show-section <?php echo !old('size_file') ? 'd-none' : "" ?>">
													@include('admin.partials.previewFileRender', ['file' => old('size_file') ])
												</div>
												<?php if(isset($product) && $product): ?>
												<div class="fixed-edit-section">
													@include('admin.partials.previewFileRender', ['file' => $product->size_file, 'relationType' => 'products.size_file', 'relationId' => $product->id ])
												</div>
												<?php endif; ?>
											</div>
											@error('size_file')
												<small class="text-danger">{{ $message }}</small>
											@enderror
										</div>
										<div class="form-group">
											<label class="form-control-label" for="input-first-name">Size Guide Video</label>
											<input type="text" v-model="size_guide_video" class="form-control" name="size_guide_video" placeholder="Size Guide Video" required value="{{ old('size_guide_video', $product->size_guide_video) }}">
											@error('size_guide_video')
												<small class="text-danger">{{ $message }}</small>
											@enderror
										</div>
									</div>
									
									<div class="col-lg-6">
										<div class="form-group">
											<div class="custom-control">
												<label class="custom-toggle">
													<input type="hidden" name="status" value="0">
													<input type="checkbox" name="status" value="1" <?php echo (old('status') != '0' ? 'checked' : '') ?>>
													<span class="custom-toggle-slider rounded-circle" data-label-off="No" data-label-on="Yes"></span>
												</label>
												<label class="custom-control-label">Do you want to publish this uniform ?</label>
											</div>
										</div>

										<hr class="my-2" />
										<div class="form-group">
											<div class="custom-control">
												<label class="custom-toggle">
													<input type="hidden" name="website_visible" value="0">
													<input type="checkbox" name="website_visible"  v-model="website_visible" value="1" <?php echo (old('website_visible') != '0' ? 'checked' : '') ?>>
													<span class="custom-toggle-slider rounded-circle" data-label-off="No" data-label-on="Yes"></span>
												</label>
												<label class="custom-control-label">Visible on website ?</label>
											</div>
											<div class="custom-control">
												<label class="custom-toggle">
													<input type="hidden" name="shop_visible" value="0">
													<input type="checkbox" name="shop_visible" v-model="shop_visible" value="1" <?php echo (old('shop_visible') != '0' ? 'checked' : '') ?>>
													<span class="custom-toggle-slider rounded-circle" data-label-off="No" data-label-on="Yes"></span>
												</label>
												<label class="custom-control-label">Visible on shop ?</label>
											</div>
										</div>
										
									</div>
									<div class="col-lg-6">
										<div class="form-group">
											<label class="">Logo Options</label>
											<div class="custom-control">
												<label class="custom-toggle">
													<input type="hidden" name="non_exchange" value="0">
													<input type="checkbox" name="non_exchange" v-model="non_exchange" value="1" <?php echo (old('non_exchange') ? 'checked' : '') ?>>
													<span class="custom-toggle-slider rounded-circle" data-label-off="No" data-label-on="Yes"></span>
												</label>
												<label class="custom-control-label">Non Exchangeable and Refundable</label>
											</div>
											<div class="custom-control">
												<label class="custom-toggle">
													<input type="hidden" name="printed_logo" value="0">
													<input type="checkbox" name="printed_logo" v-model="printed_logo" value="1" <?php echo (old('printed_logo') != '0' ? 'checked' : '') ?>>
													<span class="custom-toggle-slider rounded-circle" data-label-off="No" data-label-on="Yes"></span>
												</label>
												<label class="custom-control-label">Printed Logo</label>
											</div>
											<div class="custom-control">
												<label class="custom-toggle">
													<input type="hidden" name="embroidered_logo" value="0">
													<input type="checkbox" name="embroidered_logo" v-model="embroidered_logo" value="1" <?php echo (old('embroidered_logo') != '0' ? 'checked' : '') ?>>
													<span class="custom-toggle-slider rounded-circle" data-label-off="No" data-label-on="Yes"></span>
												</label>
												<label class="custom-control-label">Embroidered Logo</label>
											</div>
										</div>
									</div>
								</div>
							</div>
							<hr class="my-4" />
							<button 
								:disabled="loading"
								type="button" class="btn btn-primary finish-steps float-right"
								v-on:click="submitForm()">
								<i class="fa fa-spin fa-spinner" v-if="loading"></i>
								<i v-else class="fa fa-save"></i> Save 
							</button>
						</form>
					</div>
				</div>
			</div>
			@if (isset($product) && $product->id)
				<div class="col-xl-12 order-xl-1">
					<div class="card" id="customization">
						<div class="card-header">
							<div class="row align-items-center">
								<div class="col-8">
									<h3 class="mb-0">Customization</h3>
								</div>
							</div>
						</div>
						<div class="card-body">
							<form @submit="submitForm">
								<div class="table-responsive">
									<table class="table table-hover w-100">
										<thead class="table-dark">
											<tr>
												<th style="width: 30%">Title</th>
												<th style="width: 25%">Description</th>
												<th style="width: 12%">Cost ($)</th>
												<th style="10%">
													Required
												</th>
												<th style="width: 5%">Action</th>
											</tr>
										</thead>
										<tbody>
											<tr
											v-for="(item, index) in items"
											:key="item.id"
											>
											<td>
												<input
												type="text"
												class="form-control form-control-sm"
												placeholder="Enter title"
												v-model="item.title"
												@input="updateItem(item, 'title', item.title)"
												required
												/>
											</td>
											<td>
												<textarea
												class="form-control form-control-sm"
												placeholder="Enter description"
												rows="2"
												v-model="item.description"
												@input="updateItem(item, 'description', item.description)"
												required
												></textarea>
											</td>
											<td>
												<input
												type="number"
												class="form-control form-control-sm"
												placeholder="0.00"
												step="0.01"
												min="0"
												v-model.number="item.cost"
												@input="updateItem(item, 'cost', item.cost)"
												required
												/>
											</td>
											<td>
												<div class="form-check d-flex">
												<input
													type="checkbox"
													style="border: 1px solid"
													class="form-check-input"
													:id="'required-' + item.id"
													v-model="item.required"
												/>
												</div>
											</td>
											<td>
												<button
												type="button"
												class="btn btn-outline-danger btn-sm"
												@click="removeRow(item.id)"
												:disabled="items.length === 1"
												title="Remove row"
												>
												Remove
												</button>
											</td>
											</tr>
										</tbody>
										<tfoot class="table-light">
											<tr>
											<td colspan="2">
												<button
													type="button"
													class="btn btn-sm btn-secondary"
													@click="addRow"
												>
												Add New Row
												</button>
											</td>
											<td class="text-end fw-bold fs-5">&nbsp;
											</td>
											<td class="fw-bold fs-4 text-primary">&nbsp;
											</td>
											<td colspan="2" class="text-right"><button type="submit" class="btn btn-primary"><i class="fa fa-spin fa-spinner" v-if="loading"></i> Save</button></td>
											</tr>
										</tfoot>
									</table>
								</div>
							</form>
						</div>
					</div>
				</div>
				@endif
		</div>
	</div>
</div>
@endsection
@push('scripts')
<script>
var pageId = '{{ $product && $product->id ? $product->id : '' }}';
</script>
@endpush