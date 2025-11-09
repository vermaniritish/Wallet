<?php use App\Models\Admin\HomePage; ?>
@extends('layouts.frontendlayout')
@section('content')

<section class="home-slider position-relative pt-25 pb-20">
    <div class="container">
        <div class="row">
            <div class="col-lg-9">
                <div class="position-relative">
                    <div class="hero-slider-1 style-3 dot-style-1 dot-style-1-position-1">
                        @foreach($sliders as $s)
                        <div class="single-hero-slider single-animation-wrap">
                            <div class="container">
                                <div class="slider-1-height-3 slider-animated-1">
                                    <div class="hero-slider-content-2">
                                        @if($s->label)
                                        <h4 class="animated">{{$s->label}}</h4>
                                        @endif
                                        @if($s->heading)
                                        <h2 class="animated fw-900"><?php echo nl2br($s->heading) ?></h2>
                                        @endif
                                        @if($s->sub_heading)
                                        <h1 class="animated fw-900 text-brand">{{$s->sub_heading}}</h1>
                                        @endif
                                        @if($s->small_text)
                                        <p class="animated">{{$s->small_text}}</p>
                                        @endif
                                        @if($s->button_status)
                                        <a class="animated btn btn-brush btn-brush-3" href="{{ $s->button_url }}">{{ $s->button_title }}</a>
                                        @endif
                                    </div>
                                    <div class="slider-img">
                                        @if($s->image)
                                        <img src="{{ url($s->image) }}" alt="">
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    <div class="slider-arrow hero-slider-1-arrow style-3"></div>
                </div>
            </div>
            <div class="col-lg-3 d-md-none d-lg-block">
                @if($rightTopSlide)
                <div class="banner-img banner-1 wow fadeIn  animated home-3">
                    <img class="border-radius-10" src="{{ url($rightTopSlide->image) }}" alt="">
                    <div class="banner-text">
                        <span>{{$rightTopSlide->label}}</span>
                        <h4><?php echo nl2br($rightTopSlide->heading) ?></h4>
                        <a href="{{ $rightTopSlide->button_url }}">{{ $rightTopSlide->button_title }} <i class="fi-rs-arrow-right"></i></a>
                    </div>
                </div>
                @endif
                @if($rightBottomSlide)
                <div class="banner-img banner-2 wow fadeIn  animated mb-0">
                    <img class="border-radius-10" src="{{ url($rightBottomSlide->image) }}" alt="">
                    <div class="banner-text">
                        <span>{{$rightBottomSlide->label}}</span>
                        <h4><?php echo nl2br($rightBottomSlide->heading) ?></h4>
                        <a href="{{ $rightBottomSlide->button_url }}">{{ $rightBottomSlide->button_title }} <i class="fi-rs-arrow-right"></i></a>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</section>
        <section class="featured section-padding">
            <div class="container">
                <div class="row">
                    @for($k = 1; $k <= 6; $k++)
                     <div class="col-lg-2 col-md-4 mb-md-3 mb-lg-0">
                        <div class="banner-features wow fadeIn animated hover-up animated">
                            <a href="HomePage::get('grid_link_'.$k)">
                                <img src="{{ url(HomePage::get('grid_image_'.$k)) }}" alt="">
                                <h4 class="bg-2">{{HomePage::get('grid_title_'.$k)}}</h4>
                            </a>
                        </div>
                    </div>
                    @endfor
                </div>
            </div>
        </section>
        @if($featuredProducts->count() > 0 || $trendingProducts->count() > 0 || $newProducts->count() > 0)
        <section class="product-tabs section-padding wow fadeIn animated">
            <div class="container">
                <div class="tab-header">
                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                        @if($featuredProducts->count() > 0)
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="nav-tab-one" data-bs-toggle="tab" data-bs-target="#tab-one" type="button" role="tab" aria-controls="tab-one" aria-selected="true">Featured</button>
                        </li>
                        @endif
                        @if($trendingProducts->count() > 0)
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="nav-tab-two" data-bs-toggle="tab" data-bs-target="#tab-two" type="button" role="tab" aria-controls="tab-two" aria-selected="false">Popular</button>
                        </li>
                        @endif
                        @if($newProducts->count() > 0)
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="nav-tab-three" data-bs-toggle="tab" data-bs-target="#tab-three" type="button" role="tab" aria-controls="tab-three" aria-selected="false">New added</button>
                        </li>
                        @endif
                    </ul>
                    <!-- <a href="category-products.php" class="view-more d-none d-md-flex">View More<i class="fi-rs-angle-double-small-right"></i></a> -->
                </div>
                <!--End nav-tabs-->
                <div class="tab-content wow fadeIn animated" id="myTabContent">
                    @if($featuredProducts)
                    <div class="tab-pane fade show active" id="tab-one" role="tabpanel" aria-labelledby="tab-one">
                        <div class="row product-grid-4">
                            @foreach($featuredProducts as $product)
                            @include('frontend.home.product', ['product' => $product])
                            @endforeach
                        </div>
                        <!--End product-grid-4-->
                    </div>
                    @endif
                    @if($trendingProducts)
                    <!--En tab one (Featured)-->
                    <div class="tab-pane fade" id="tab-two" role="tabpanel" aria-labelledby="tab-two">
                        <div class="row product-grid-4">
                            @foreach($trendingProducts as $product)
                            @include('frontend.home.product', ['product' => $product])
                            @endforeach
                        </div>
                        <!--End product-grid-4-->
                    </div>
                    @endif
                    @if($newProducts)
                    <div class="tab-pane fade" id="tab-three" role="tabpanel" aria-labelledby="tab-three">
                        <div class="row product-grid-4">
                            @foreach($newProducts as $product)
                            @include('frontend.home.product', ['product' => $product])
                            @endforeach
                        </div>
                        <!--End product-grid-4-->
                    </div>
                    @endif
                </div>
                <!--End tab-content-->
            </div>
        </section>
        @endif
        <section class="banner-2 section-padding pb-0">
            <div class="container">
                <div class="banner-img banner-big wow fadeIn animated f-none">
                    <?php $image = HomePage::get('banner_1_image'); ?>
                    <img src="{{ url($image ? $image : '/frontend/assets/imgs/banner/banner-4.png')}}" alt="">
                    <div class="banner-text d-md-block d-none">
                        <h4 class="mb-15 mt-40 text-brand">{{ HomePage::get('banner_1_label') }}</h4>
                        <h1 class="fw-600 mb-20"><?php echo nl2br(HomePage::get('banner_1_heading')) ?></h1>
                        @if(HomePage::get('banner_1_button_status'))
                        <a href="{{HomePage::get('banner_1_button_url')}}" class="btn">{{HomePage::get('banner_1_button_title')}} <i class="fi-rs-arrow-right"></i></a>
                        @endif
                    </div>
                </div>
            </div>
        </section>
        @if($categories)
        <section class="popular-categories section-padding mt-15">
            <div class="container wow fadeIn animated">
                <h3 class="section-title mb-20"><span>Popular</span> Categories</h3>
                <div class="carausel-6-columns-cover position-relative">
                    <div class="slider-arrow slider-arrow-2 carausel-6-columns-arrow" id="carausel-6-columns-arrows"></div>
                    <div class="carausel-6-columns" id="carausel-6-columns">
                        @foreach($categories as $c)
                        <div class="card-1">
                            <figure class=" img-hover-scale overflow-hidden">
                                <a href="{{$c->slug ? '/'.$c->slug : ''}}"><img src="{{ $c->image ? url($c->image) : '/frontend/assets/imgs/shop/product-3-2.jpg'}}" alt=""></a>
                            </figure>
                            <h5><a href="{{$c->slug ? '/'.$c->slug : ''}}">{{$c->title}}</a></h5>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </section>
        @endif
        <section class="banners mb-20">
            <div class="container">
                <div class="row">
                    <?php $pros = ['left', 'mid', 'right'] ?>
                    @foreach($pros as $k => $v)
                    <div class="col-lg-4 col-md-6">
                        <div class="banner-img wow fadeIn animated">
                            <img src="{{url(HomePage::get($v.'_grid_image'))}}" alt="">
                            <div class="banner-text">
                                <span>{{HomePage::get($v.'_grid_label')}}</span>
                                <h4><?php echo nl2br(HomePage::get($v.'_grid_heading')) ?></h4>
                                @if(HomePage::get($v.'_grid_button_status'))
                                <a href="{{HomePage::get($v.'_grid_button_url')}}">{{HomePage::get($v.'_grid_button_title')}} <i class="fi-rs-arrow-right"></i></a>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </section>
        <section class="deals section-padding">
            <div class="container">
                <div class="row">
                    @if(HomePage::get('deal_day_enable'))
                    <div class="col-lg-6 deal-co">
                        <?php $image = HomePage::get('deal_day_image') ?>
                        <div class="deal wow fadeIn animated mb-md-4 mb-sm-4 mb-lg-0" style="background-image: url({{ url($image ? $image : '/frontend/assets/imgs/banner/menu-banner-7.jpg')}});">
                            <div class="deal-top">
                                <h2 class="text-brand">{{HomePage::get('deal_day_label')}}</h2>
                                <h5><?php echo nl2br(HomePage::get('deal_day_heading')) ?></h5>
                            </div>
                            <div class="deal-content">
                                <h6 class="product-title"><a href="javascript:;"><?php echo nl2br(HomePage::get('deal_day_subheading')) ?></a></h6>
                                
                                @if(HomePage::get('deal_day_price_enable'))
                                <div class="product-price"><span class="new-price">£{{HomePage::get('deal_day_sale_price')}}</span><span class="old-price">£{{HomePage::get('deal_day_actual_price')}}</span></div>
                                @endif
                            </div>
                            <div class="deal-bottom">
                                @if(HomePage::get('deal_day_offer_days_enable'))
                                <p>Hurry Up! Offer End In:</p>
                                <div class="deals-countdown" data-countdown="{{ date('Y/m/d H:i:s', strtotime(HomePage::get('deal_day_offer_days'))) }}"></div>
                                @endif
                                @if(HomePage::get('deal_day_button_status'))
                                <a href="{{HomePage::get('deal_day_button_url')}}" class="btn hover-up">{{HomePage::get('deal_day_button_title')}} <i class="fi-rs-arrow-right"></i></a>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endif
                    @if(HomePage::get('deal_day_2_enable'))
                    <div class="col-lg-6 deal-co">
                        <?php $image = HomePage::get('deal_day_2_image') ?>
                        <div class="deal wow fadeIn animated mb-md-4 mb-sm-4 mb-lg-0" style="background-image: url({{ url($image ? $image : '/frontend/assets/imgs/banner/menu-banner-7.jpg')}});">
                            <div class="deal-top">
                                <h2 class="text-brand">{{HomePage::get('deal_day_2_label')}}</h2>
                                <h5><?php echo nl2br(HomePage::get('deal_day_2_heading')) ?></h5>
                            </div>
                            <div class="deal-content">
                                <h6 class="product-title"><a href="javascript:;"><?php echo nl2br(HomePage::get('deal_day_2_subheading')) ?></a></h6>
                                @if(HomePage::get('deal_day_2_price_enable'))
                                <div class="product-price"><span class="new-price">£{{HomePage::get('deal_day_2_sale_price')}}</span><span class="old-price">£{{HomePage::get('deal_day_2_actual_price')}}</span></div>
                                @endif
                            </div>
                            <div class="deal-bottom">
                                @if(HomePage::get('deal_day_2_offer_days_enable'))
                                <p>Hurry Up! Offer End In:</p>
                                <div class="deals-countdown" data-countdown="{{ date('Y/m/d H:i:s', strtotime(HomePage::get('deal_day_2_offer_days'))) }}"></div>
                                @endif
                                @if(HomePage::get('deal_day_2_button_status'))
                                <a href="{{HomePage::get('deal_day_2_button_url')}}" class="btn hover-up">{{HomePage::get('deal_day_2_button_title')}} <i class="fi-rs-arrow-right"></i></a>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </section>
        <?php $brands = HomePage::get('featured_brands');
        $brands = $brands ? json_decode($brands) : [];
        ?>
        @if($brands)
        <section class="section-padding">
            <div class="container pb-20">
                <h3 class="section-title mb-20 wow fadeIn animated"><span>Featured</span> Brands</h3>
                <div class="carausel-6-columns-cover position-relative wow fadeIn animated">
                    <div class="slider-arrow slider-arrow-2 carausel-6-columns-arrow" id="carausel-6-columns-3-arrows"></div>
                    <div class="carausel-6-columns text-center" id="carausel-6-columns-3">
                        @foreach($brands as $b)
                        <div class="brand-logo">
                            <img class="img-grey-hover" src="{{ url($b) }}" alt="">
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </section>
        @endif
@endsection
