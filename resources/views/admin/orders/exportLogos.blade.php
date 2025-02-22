@extends('layouts.adminlayout')
@section('content')
    <div class="header bg-primary pb-6">
        <div class="container-fluid">
            <div class="header-body">
                <div class="row align-items-center py-4">
                    <div class="col-lg-6 col-7">
                        <h6 class="h2 text-white d-inline-block mb-0">Logos Report</h6>
                    </div>
                    <div class="col-lg-6 col-5 text-right">
                        <a href="{{ route('admin.orders') }}" class="btn btn-neutral"><i class="ni ni-bold-left"></i> Back</a>
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
                    <!--!! FLASH MESSAGES !!-->
                    @include('admin.partials.flash_messages')
                    <div class="card-header">
                        <div class="row align-items-center">
                            <div class="col-8">
                                <h3 class="mb-0">Use the filters to export logo report.</h3>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <form method="post" action="{{ route('admin.report.exportLogo') }}" class="form-validation">
                            {{ csrf_field() }}
                            <div class="pl-lg-4">
                                <!-- Selling Date -->
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label class="form-control-label" for="selling-date-from">Order Date From</label>
                                            <input type="date" id="selling-date-from" class="form-control"
                                                name="selling_date_from" value="{{ old('selling_date_from') }}">
                                            @error('selling_date_from')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label class="form-control-label" for="selling-date-to">Order Date To</label>
                                            <input type="date" id="selling-date-to" class="form-control"
                                                name="selling_date_to" value="{{ old('selling_date_to') }}">
                                            @error('selling_date_to')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <!-- Status & Category -->
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label class="form-control-label" for="status-select">Status</label>
                                            <select class="form-control" name="statuses[]" id="status-select" multiple>
                                                <option value="">Select Status</option>
                                                @foreach ($statuses as $k => $status)
                                                    <option value="{{ $k }}">
                                                        {{ $status['label'] }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label class="form-control-label" for="category-select">Category</label>
                                            <select class="form-control" name="categories[]" id="category-select" multiple>
                                                <option value="">Select Category</option>
                                                @foreach ($categories as $category)
                                                    <option value="{{ $category->id }}">
                                                         {{ $category->title }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('categories')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label class="form-control-label" for="sku-select">Product / SKU Number</label>
                                            <select class="form-control" name="skuNumber[]" id="sku-select" multiple>
                                                <option value="">Select</option>
                                                @foreach ($skuNumbers as $sku)
                                                    <option value="{{ $sku->id }}">
                                                        {{ $sku->title }} - {{ $sku->sku_number }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('skuNumber')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label class="form-control-label" for="size-select">Sizes</label>
                                            <select class="form-control" name="sizes[]" id="size-select" multiple>
                                                <option value="">Select Size</option>
                                                @foreach ($sizes as $size)
                                                    <option value="{{ $size->id }}">
                                                        {{ $size->size_title }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('sizes')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label class="form-control-label" for="color-select">Colors</label>
                                            <select class="form-control" name="colors[]" id="color-select" multiple>
                                                <option value="">Select Color</option>
                                                @foreach ($colors as $color)
                                                    <option value="{{ $color->title }}">
                                                        {{ $color->title }} - {{ $color->code }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('colors')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label class="form-control-label" for="brand-select">Brands</label>
                                            <select class="form-control" name="brands[]" id="brand-select" multiple>
                                                <option value="">Select Brand</option>
                                                @if (!empty($brands) && $brands->count())
                                                    @foreach ($brands as $brand)
                                                        <option value="{{ $brand->id }}">
                                                            {{ $brand->title }}
                                                        </option>
                                                    @endforeach
                                                @endif
                                            </select>
                                            @error('brands')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                            </div>

                            <hr class="my-4" />
                            <button type="submit" class="btn btn-sm py-2 px-3 btn-primary float-right">
                                <i class="fa fa-save"></i> Submit
                            </button>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
