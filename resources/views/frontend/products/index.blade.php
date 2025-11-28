@extends('layouts.frontendlayout')
@section('content')
<div id="product-listing-vue">
    <div class="page-header breadcrumb-wrap">
            <div class="container">
                <div class="breadcrumb">
                    <a href="index.html" rel="nofollow">Home</a>
                    <span></span> Shop
                </div>
            </div>
        </div>
        <section class="mt-50 mb-50">
            <div class="container">
                <div class="row flex-row-reverse">
                    <div class="{{ $brandPage ? 'col-lg-12' : 'col-lg-9' }}">
                        <div class="shop-product-fillter">
                            <div class="totall-product">
                                <p v-html="paginationMessage"></p>
                                
                            </div>
                            <div class="sort-by-product-area">
                                <div class="sort-by-cover mr-10">
                                    <div class="sort-by-product-wrap" @click="toggleDropdown">
                                        <div class="sort-by">
                                            <span><i class="fi-rs-apps"></i>Show:</span>
                                        </div>
                                        <div class="sort-by-dropdown-wrap">
                                            <span> @{{ selectedOption }} <i class="fi-rs-angle-small-down"></i></span>
                                        </div>
                                    </div>
                                    <div class="sort-by-dropdown show" v-if="isOpen">
                                        <ul>
                                            <li v-for="option in options" :key="option" @click.prevent="selectOption(option)">
                                                <a href="javascript:;"
                                                    :class="{ active: option === selectedOption }"
                                                    @click.prevent="selectOption(option)">
                                                    @{{ option }}
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                                
                            </div>
                        </div>
                        <div class="row product-grid-3">
                            @include('frontend.products.product')
                        </div>
                        <!--pagination-->
                        <div class="pagination-area mt-15 mb-sm-5 mb-lg-0"  v-if="maxPages > 1">
                            <nav aria-label="Page navigation example">
                                <ul class="pagination justify-content-start">
                                    <li v-if="page > 1" class="page-item"><a class="page-link" href="#" v-on:click="page > 1 ? paginateIt(page-1) : null"><i class="fi-rs-angle-double-small-left"></i></a></li>
                                    <li :class="( i == page ? `page-item active` : 'page-item')" v-for="i in pagination"><a class="page-link" v-on:click="paginateIt(i)" href="#">@{{ i }}</a></li>
                                    <li v-if="page < maxPages" class="page-item"><a class="page-link" v-on:click="page < maxPages ? paginateIt(page+1) : null" href="#"><i class="fi-rs-angle-double-small-right"></i></a></li>
                                </ul>
                            </nav>
                        </div>
                    </div>
                    @if(!$brandPage)
                    <div class="col-lg-3 primary-sidebar sticky-sidebar" v-if="!searchPage">
						@include('frontend.products.sidebar')
                    </div>
                    @endif
                </div>
            </div>
        </section>
</div>
@endsection
@push('scripts')
<script>var cId = "{{$category ? $category->id : ''}}";</script>
<script>var brandSlug = "{{$brand ? $brand->slug : ''}}";</script>
<script>var brandSlug = "{{$brandPage ? 'true' : 'false'}}";</script>
@endpush