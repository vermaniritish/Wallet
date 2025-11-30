@extends('layouts.frontendlayout')
@section('content')

<div class="page-header breadcrumb-wrap">
    <div class="container">
        <div class="breadcrumb">
            <a href="{{url('/')}}" rel="nofollow">Home</a>
            <span></span> <strong>{{$school->name}}</strong>
        </div>
    </div>
</div>
<section class="mt-50 mb-50">
    <div class="container">
        <div class="row">
            <div class="col-lg-9" id="product-listing-vue">
                <div class="shop-product-fillter">
                    <div class="totall-product">
                        @if($school->logo)
                        <p> <img src="{{ url($school->logo) }}" alt="{{$school->name}}" style="height: 100px;" /></p>
                        @endif
                    </div>
                    <div class="sort-by-product-area">
                        <p><strong class="text-brand">All garments come with the school logo unless otherwise stated</strong></p>
                    </div>
                </div>
                <div class="row product-grid-3">
                    @include('frontend.products.product')
                </div>
                <div class="pagination-area mt-15 mb-sm-5 mb-lg-0" v-if="maxPages > 1">
                    <nav aria-label="Page navigation example">
                        <ul class="pagination justify-content-start">
                            <li v-if="page > 1" class="page-item"><a class="page-link" href="#" v-on:click="page > 1 ? paginateIt(page-1) : null"><i class="fi-rs-angle-double-small-left"></i></a></li>
                            <li :class="( i == page ? `page-item active` : 'page-item')" v-for="i in pagination"><a class="page-link" v-on:click="paginateIt(i)" href="#">@{{ i }}</a></li>
                            <li v-if="page < maxPages" class="page-item"><a class="page-link" v-on:click="page < maxPages ? paginateIt(page+1) : null" href="#"><i class="fi-rs-angle-double-small-right"></i></a></li>
                        </ul>
                    </nav>
                </div>
            </div>
			@include('frontend.school.sidebar')        
        </div>
    </div>
</section>
@endsection
@push('scripts')
<script>var schoolPageId = '{{ $schoolId }}';</script>
<script>var brandSlug = "{{isset($brand) && $brand ? $brand->slug : ''}}";</script>
<script>var brandPage = "{{ isset($brandPage) && $brandPage ? 'true' : 'false'}}";</script>
@endpush