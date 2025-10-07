@extends('layouts.frontendlayout')
@section('content')

<div class="page-header breadcrumb-wrap">
    <div class="container">
        <div class="breadcrumb">
            <a href="{{url('/')}}" rel="nofollow">Home</a>
            <span></span> <strong>{{ $title }}</strong>
        </div>
    </div>
</div>
<section class="mt-50 mb-50">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="shop-product-fillter">
                    <div class="totall-product">
                        <p> </p>
                    </div>
                    <div class="sort-by-product-area">
                        <p><strong class="text-brand">{{ $title }}</strong></p>
                    </div>
                </div>
                <div class="row product-grid-3">
                    @foreach($schools as $s)
                    <div class="col-lg-3 col-md-3 col-12 col-sm-6">
                        <div class="product-cart-wrap mb-30">
                            <div class="product-img-action-wrap">
                                <div class="product-img product-img-zoom">
                                    <a href="{{ route('school.uniforms', ['slug' => Str::slug($s->name. ' ' . $s->id)]) }}">
                                        <img class="default-img" src="{{ url($s->logo ? $s->logo : '/frontend/assets/imgs/shop/product-2-2.jpg' ) }}" alt="">
                                        <!-- <img class="hover-img" src="" alt=""> -->
                                    </a>
                                </div>
                            </div>
                            <div class="product-content-wrap">
                                <h2><a href="{{ route('school.uniforms', ['slug' => Str::slug($s->name. ' ' . $s->id)]) }}">{{ $s->name }}</a></h2>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                
            </div>
            
        </div>
    </div>
</section>
@endsection