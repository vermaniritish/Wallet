@extends('layouts.frontendlayout')
@section('content')
<div class="page-header breadcrumb-wrap">
    <div class="container">
        <div class="breadcrumb">
            <a href="{{url('/)}}" rel="nofollow">Home</a>
            <span></span> Shop
            <span></span> Customize Your Apparel
        </div>
    </div>
</div>
<section class="mt-50 mb-50" id="cart-page">
    <div class="container">
        <div class="row g-4">
            <div class="col-lg-12">
                <img src="personalise.jpg" alt="Personalise Your Apparel" />
            </div>
            <section class="popular-categories section-padding mt-15">
                <div class="container wow fadeIn animated">
                    <h3 class="section-title mb-20"><span>Select</span> Categories</h3>
                    <div class="carausel-6-columns-cover position-relative">
                        <div class="slider-arrow slider-arrow-2 carausel-6-columns-arrow" id="carausel-6-columns-arrows"></div>
                        <div class="carausel-6-columns" id="carausel-6-columns">
                            @foreach($categories as $c)
                            <div class="card-1">
                                <figure class="img-hover-scale overflow-hidden">
                                    
                                </figure>
                                <h5><a href="{{ $c->slug ? '/'.$c->slug : ''}}">{{$c->title}}</a></h5>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
</section>