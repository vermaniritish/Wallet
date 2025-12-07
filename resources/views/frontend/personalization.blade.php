@extends('layouts.frontendlayout')
@section('content')
<div class="page-header breadcrumb-wrap">
    <div class="container">
        <div class="breadcrumb">
            <a href="{{ url('/') }}" rel="nofollow">Home</a>
            <span></span> Shop
            <span></span> Customize Your Apparel
        </div>
    </div>
</div>
<section class="mt-50 mb-50" id="customize-product-page">
    <div class="container">
        <div class="row g-4">
            <div class="col-lg-12">
                <img src="{{url('/frontend/assets/imgs/personalise.jpg')}}" alt="Personalise Your Apparel" />
            </div>
            @if($categories)
            <section class="popular-categories section-padding mt-15">
                <div class="container wow fadeIn animated">
                    <h3 class="section-title mb-20"><span>Select</span> Categories</h3>
                    <div class="customzise-p-cover position-relative">
                        <div class="slider-arrow slider-arrow-2 customzise-p-arrow" id="customzise-p-arrows"></div>
                        <div class="customzise-p" id="customzise-p">
                            @foreach($categories as $c)
                            <div class="card-1">
                                <figure class="img-hover-scale overflow-hidden">
                                    <a href="javascript:;" @click="fetchSubcategories('{{$c->id}}')"><img src="{{ $c->image ? url($c->image) : url('/frontend/assets/imgs/shop/product-3-2.jpg') }}" alt=""></a>
                                </figure>
                                <h5><a href="javascript:;" @click="fetchSubcategories('{{$c->id}}')">{{$c->title}}</a></h5>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </section>
            <section class="popular-categories section-padding mt-15" v-if="fetched">
                <div class="container wow fadeIn animated">
                    <h3 class="section-title mb-20"><span>Select</span> Sub Categories</h3>
                    <div class="customzise-sub-cover position-relative" v-if="subcats && subcats.length > 0">
                        <div class="slider-arrow slider-arrow-2 customzise-sub-arrow" id="customzise-sub-arrows"></div>
                        <div class="customzise-sub" id="customzise-sub">
                            <div class="card-1" v-for="s in subcats">
                                <figure class="img-hover-scale overflow-hidden">
                                    <a :href="`{{ url('/') }}/${s.cat_slug}/${s.slug}`"><img :src="'{{ url('/') }}' + (s.image ? s.image : '/frontend/assets/imgs/shop/product-3-2.jpg' )" alt=""></a>
                                </figure>
                                <h5><a :href="`{{ url('/') }}/${s.cat_slug}/${s.slug}`">@{{s.title}}</a></h5>
                            </div>
                        </div>
                    </div>
                    <div class="" v-else>
                        <p style="color:#088178;">There is no record availble.</p>
                    </div>
                </div>
            </section>
            @else

            <section class="popular-categories section-padding mt-15">
                <div class="container wow fadeIn animated">
                    <h3 class="section-title mb-20"><span>Select</span> Categories</h3>
                    <div class="">
                        <p style="color:#088178;">There is no record availble.</p>
                    </div>
                </div>
            </section>
            @endif
        </div>
    </div>
</section>
@endsection