@extends('layouts.adminlayout')
@section('content')
<div class="header bg-primary pb-6">
	<div class="container-fluid">
		<div class="header-body">
			<div class="row align-items-center py-4">
				<div class="col-lg-6 col-7">
					<h6 class="h2 text-white d-inline-block mb-0">School SKU Export</h6>
				</div>
				<div class="col-lg-6 col-5 text-right">
					
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
							<h3 class="mb-0">Use the filters to export report.</h3>
						</div>
					</div>
				</div>
				<div class="card-body">
					<div id="product" >
						<p v-if="mounting" class="text-center big" style="padding: 15%"><i style="font-size: 30px" class="fa fa-spin fa-spinner"></i></p>
						<form id="product-form" method="post" action="" class="form-validation d-none">
							<pre id="availableColor" class="d-none">{{ $colors }}</pre>
							<pre id="availableSizes" class="d-none">{{ $sizes }}</pre>
							<!--!! CSRF FIELD !!-->
							{{ @csrf_field() }}
							<h6 class="heading-small text-muted mb-4"></h6>
							<div class="pl-lg-4">
								
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
									<!-- Add this table below the <hr /> line -->
<div class="text-right mb-3" v-if="selectedProductData.length">
    <button 
        type="button"
        class="btn btn-success"
        @click="downloadExcel"
    >
        <i class="fa fa-file-excel"></i> Download XLS
    </button>
</div>
<!-- <div class="table-responsive mt-4" v-if="selectedProductData && selectedProductData.length">
    <table class="table table-bordered align-items-center">
        <thead class="thead-light">
            <tr>
                <th>School name</th>
                <th>Colors</th>
                <th>Uniform Title</th>
            </tr>
        </thead>

        <tbody>
            <tr v-for="(item, index) in selectedProductData" :key="index">
                <td>@{{ item.name }}</td>
                <td>@{{ item.colors }}</td>
                <td>@{{ item.uniform_title }}</td>
            </tr>
        </tbody>
    </table>
</div>

<div v-else class="mt-3">
    <p class="text-muted">No data found.</p>
</div> -->

<div class="table-responsive mt-4">

    <!-- Initial Loader -->
    <div v-if="loadingTable" class="text-center p-5">
        <i class="fa fa-spinner fa-spin fa-2x"></i>
        <p class="mt-2">Loading report...</p>
    </div>

    <!-- Table -->
    <div
        v-if="!loadingTable && selectedProductData.length"
        class="table-scroll-wrapper"
        @scroll="handleScroll"
        style="max-height:500px; overflow-y:auto;"
    >

        <table class="table table-bordered align-items-center mb-0">

            <thead class="thead-light sticky-top bg-white">
                <tr>
                    <th width="30%">School Name</th>
                    <th width="40%">Colors</th>
                    <th width="30%">Uniform Title</th>
                </tr>
            </thead>

            <tbody>

                <tr
                    v-for="(item, index) in selectedProductData"
                    :key="index"
                >
                    <td>
                        @{{ item.name }}
                    </td>

                    <td style="white-space: normal;">
                        @{{ item.colors }}
                    </td>

                    <td>
                        @{{ item.uniform_title }}
                    </td>
                </tr>

                <!-- Infinite Scroll Loader -->
                <tr v-if="fetchingMore">
                    <td colspan="3" class="text-center p-3">
                        <i class="fa fa-spinner fa-spin"></i>
                        Loading more...
                    </td>
                </tr>

            </tbody>

        </table>

    </div>

    <!-- Empty -->
    <div
        v-if="!loadingTable && !selectedProductData.length"
        class="text-center p-4"
    >
        No data found.
    </div>

</div>
								
								
							</div>
							
							<!-- <button 
								:disabled="loading"
								type="button" class="btn btn-primary finish-steps float-right"
								v-on:click="submitForm()">
								<i class="fa fa-spin fa-spinner" v-if="loading"></i>
								<i v-else class="fa fa-save"></i> Save 
							</button> -->
						</form>
					</div>
				</div>
			</div>
			
		</div>
	</div>
</div>
@endsection
@push('scripts')

<script>
var pageId = '{{ $product && $product->id ? $product->id : '' }}';
var allColors = <?php echo $colors->count() > 0 ? json_encode($colors->toArray()) : '[]' ?>
</script>
@endpush
@section('mcontent')
<script src="<?php echo url('assets/js/uniforms/form.js?v=3.9') ?>"></script>
@endsection