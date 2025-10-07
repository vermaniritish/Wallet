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
                                                <h2 class="animated fw-900">{{$s->heading}}</h2>
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
                                                <img src="{{ url($s->image) }}" alt="">
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
                        <div class="banner-img banner-1 wow fadeIn  animated home-3">
                            <img class="border-radius-10" src="{{ url('/frontend/assets/imgs/banner/banner-5.jpg') }}" alt="">
                            <div class="banner-text">
                                <span>Accessories</span>
                                <h4>Save 17% on <br>Autumn Hat</h4>
                                <a href="#">Shop Now <i class="fi-rs-arrow-right"></i></a>
                            </div>
                        </div>
                        <div class="banner-img banner-2 wow fadeIn  animated mb-0">
                            <img class="border-radius-10" src="{{ url('/frontend/assets/imgs/banner/banner-7.jpg') }}" alt="">
                            <div class="banner-text">
                                <span>Smart Offer</span>
                                <h4>Save 20% on <br>Eardrop</h4>
                                <a href="#">Shop Now <i class="fi-rs-arrow-right"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <section class="featured section-padding">
            <div class="container">
                <div class="row">
                     <div class="col-lg-2 col-md-4 mb-md-3 mb-lg-0">
                        <div class="banner-features wow fadeIn animated hover-up animated">
                            <img src="{{ url('frontend/assets/imgs/theme/icons/schools.png') }}" alt="">
                            <h4 class="bg-2">Find School</h4>
                        </div>
                    </div>
					<div class="col-lg-2 col-md-4 mb-md-3 mb-lg-0">
                        <div class="banner-features wow fadeIn animated hover-up animated">
                            <img src="{{ url('frontend/assets/imgs/theme/icons/junior-schools.png') }}" alt="">
                            <h4 class="bg-1">Junior Schools</h4>
                        </div>
                    </div>
                    <div class="col-lg-2 col-md-4 mb-md-3 mb-lg-0">
                        <div class="banner-features wow fadeIn animated hover-up animated">
                            <img src="{{ url('frontend/assets/imgs/theme/icons/senior-schools.png') }}" alt="">
                            <h4 class="bg-3">Senior Schools</h4>
                        </div>
                    </div>
                   
                    <div class="col-lg-2 col-md-4 mb-md-3 mb-lg-0">
                        <div class="banner-features wow fadeIn animated hover-up animated">
                            <img src="{{ url('frontend/assets/imgs/theme/icons/categories.png') }}" alt="">
                            <h4 class="bg-4">Categories</h4>
                        </div>
                    </div>
                    <div class="col-lg-2 col-md-4 mb-md-3 mb-lg-0">
                        <div class="banner-features wow fadeIn animated hover-up animated">
                            <img src="{{ url('frontend/assets/imgs/theme/icons/sportswear.png') }}" alt="">
                            <h4 class="bg-5">Sportswear</h4>
                        </div>
                    </div>
                    <div class="col-lg-2 col-md-4 mb-md-3 mb-lg-0">
                        <div class="banner-features wow fadeIn animated hover-up animated">
                            <img src="{{ url('frontend/assets/imgs/theme/icons/accessories.png') }}" alt="">
                            <h4 class="bg-6">Accessories</h4>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <section class="product-tabs section-padding wow fadeIn animated">
            <div class="container">
                <div class="tab-header">
                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="nav-tab-one" data-bs-toggle="tab" data-bs-target="#tab-one" type="button" role="tab" aria-controls="tab-one" aria-selected="true">Featured</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="nav-tab-two" data-bs-toggle="tab" data-bs-target="#tab-two" type="button" role="tab" aria-controls="tab-two" aria-selected="false">Popular</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="nav-tab-three" data-bs-toggle="tab" data-bs-target="#tab-three" type="button" role="tab" aria-controls="tab-three" aria-selected="false">New added</button>
                        </li>
                    </ul>
                    <a href="category-products.php" class="view-more d-none d-md-flex">View More<i class="fi-rs-angle-double-small-right"></i></a>
                </div>
                <!--End nav-tabs-->
                <div class="tab-content wow fadeIn animated" id="myTabContent">
                    <div class="tab-pane fade show active" id="tab-one" role="tabpanel" aria-labelledby="tab-one">
                        <div class="row product-grid-4">
                            <div class="col-lg-3 col-md-4 col-12 col-sm-6">
                                <div class="product-cart-wrap mb-30">
                                    <div class="product-img-action-wrap">
                                        <div class="product-img product-img-zoom">
                                            <a href="product-full.php">
                                                <img class="default-img" src="{{ url('frontend/assets/imgs/shop/product-1-1.jpg') }}" alt="">
                                                <img class="hover-img" src="{{ url('frontend/assets/imgs/shop/product-1-2.jpg') }}" alt="">
                                            </a>
                                        </div>
                                        
                                        <div class="product-badges product-badges-position product-badges-mrg">
                                            <span class="hot">Out of Stock</span>
                                        </div>
                                    </div>
                                    <div class="product-content-wrap">
                                        <div class="product-category">
                                            <a href="#">Sweatshirts</a>
                                        </div>
                                        <h2><a href="product-full.php">Colorful Pattern Shirts</a></h2>
                                        <div class="product-price">
                                            <span>£238.85 </span>
                                            <span class="old-price">£245.8</span>
                                        </div>
                                        
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-4 col-12 col-sm-6">
                                <div class="product-cart-wrap mb-30">
                                    <div class="product-img-action-wrap">
                                        <div class="product-img product-img-zoom">
                                            <a href="product-full.php">
                                                <img class="default-img" src="{{ url('frontend/assets/imgs/shop/product-2-1.jpg') }}" alt="">
                                                <img class="hover-img" src="{{ url('frontend/assets/imgs/shop/product-2-2.jpg') }}" alt="">
                                            </a>
                                        </div>
                                        
                                        <div class="product-badges product-badges-position product-badges-mrg">
                                            <span class="new">New</span>
                                        </div>
                                    </div>
                                    <div class="product-content-wrap">
                                        <div class="product-category">
                                            <a href="#">Sweatshirts</a>
                                        </div>
                                        <h2><a href="product-full.php">Plain Color Pocket Shirts</a></h2>
                                        <div class="product-price">
                                            <span>£138.85 </span>
                                            <span class="old-price">£255.8</span>
                                        </div>
                                        
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-4 col-12 col-sm-6">
                                <div class="product-cart-wrap mb-30">
                                    <div class="product-img-action-wrap">
                                        <div class="product-img product-img-zoom">
                                            <a href="product-full.php">
                                                <img class="default-img" src="{{ url('frontend/assets/imgs/shop/product-3-1.jpg') }}" alt="">
                                                <img class="hover-img" src="{{ url('frontend/assets/imgs/shop/product-3-2.jpg') }}" alt="">
                                            </a>
                                        </div>
                                        
                                        <div class="product-badges product-badges-position product-badges-mrg">
                                            <span class="best">Best Sell</span>
                                        </div>
                                    </div>
                                    <div class="product-content-wrap">
                                        <div class="product-category">
                                            <a href="#">Shirts</a>
                                        </div>
                                        <h2><a href="product-full.php">Vintage Floral Oil Shirts</a></h2>
                                        <div class="product-price">
                                            <span>£338.85 </span>
                                            <span class="old-price">£445.8</span>
                                        </div>
                                        
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-4 col-12 col-sm-6">
                                <div class="product-cart-wrap mb-30">
                                    <div class="product-img-action-wrap">
                                        <div class="product-img product-img-zoom">
                                            <a href="product-full.php">
                                                <img class="default-img" src="{{ url('frontend/assets/imgs/shop/product-4-1.jpg') }}" alt="">
                                                <img class="hover-img" src="{{ url('frontend/assets/imgs/shop/product-4-2.jpg') }}" alt="">
                                            </a>
                                        </div>
                                        
                                        <div class="product-badges product-badges-position product-badges-mrg">
                                            <span class="sale">Sale</span>
                                        </div>
                                    </div>
                                    <div class="product-content-wrap">
                                        <div class="product-category">
                                            <a href="#">Sweatshirts</a>
                                        </div>
                                        <h2><a href="product-full.php">Colorful Hawaiian Shirts</a></h2>
                                        <div class="product-price">
                                            <span>£123.85 </span>
                                            <span class="old-price">£235.8</span>
                                        </div>
                                        
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-4 col-12 col-sm-6">
                                <div class="product-cart-wrap mb-xs-30">
                                    <div class="product-img-action-wrap">
                                        <div class="product-img product-img-zoom">
                                            <a href="product-full.php">
                                                <img class="default-img" src="{{ url('frontend/assets/imgs/shop/product-5-1.jpg') }}" alt="">
                                                <img class="hover-img" src="{{ url('frontend/assets/imgs/shop/product-5-2.jpg') }}" alt="">
                                            </a>
                                        </div>
                                        
                                        <div class="product-badges product-badges-position product-badges-mrg">
                                            <span class="hot">Out of Stock</span>
                                        </div>
                                    </div>
                                    <div class="product-content-wrap">
                                        <div class="product-category">
                                            <a href="#">Shirt</a>
                                        </div>
                                        <h2><a href="product-full.php">Flowers Sleeve Lapel Shirt</a></h2>
                                        <div class="product-price">
                                            <span>£28.85 </span>
                                            <span class="old-price">£45.8</span>
                                        </div>
                                        
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-4 col-12 col-sm-6">
                                <div class="product-cart-wrap mb-xs-30">
                                    <div class="product-img-action-wrap">
                                        <div class="product-img product-img-zoom">
                                            <a href="product-full.php">
                                                <img class="default-img" src="{{ url('frontend/assets/imgs/shop/product-6-1.jpg') }}" alt="">
                                                <img class="hover-img" src="{{ url('frontend/assets/imgs/shop/product-6-2.jpg') }}" alt="">
                                            </a>
                                        </div>
                                        
                                        <div class="product-badges product-badges-position product-badges-mrg">
                                            <span class="hot">Out of Stock</span>
                                        </div>
                                    </div>
                                    <div class="product-content-wrap">
                                        <div class="product-category">
                                            <a href="#">Shirts</a>
                                        </div>
                                        <h2><a href="product-full.php">Ethnic Floral Casual Shirts</a></h2>
                                        <div class="product-price">
                                            <span>£238.85 </span>
                                            <span class="old-price">£245.8</span>
                                        </div>
                                        
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-4 col-12 col-sm-6">
                                <div class="product-cart-wrap mb-xs-30">
                                    <div class="product-img-action-wrap">
                                        <div class="product-img product-img-zoom">
                                            <a href="product-full.php">
                                                <img class="default-img" src="{{ url('frontend/assets/imgs/shop/product-7-1.jpg') }}" alt="">
                                                <img class="hover-img" src="{{ url('frontend/assets/imgs/shop/product-7-2.jpg') }}" alt="">
                                            </a>
                                        </div>
                                        
                                        <div class="product-badges product-badges-position product-badges-mrg">
                                            <span class="new">New</span>
                                        </div>
                                    </div>
                                    <div class="product-content-wrap">
                                        <div class="product-category">
                                            <a href="#">Shoes</a>
                                        </div>
                                        <h2><a href="product-full.php">Stitching Hole Sandals</a></h2>
                                        <div class="product-price">
                                            <span>£1275.85 </span>
                                        </div>
                                        
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-4 col-12 col-sm-6">
                                <div class="product-cart-wrap mb-xs-30">
                                    <div class="product-img-action-wrap">
                                        <div class="product-img product-img-zoom">
                                            <a href="product-full.php">
                                                <img class="default-img" src="{{ url('frontend/assets/imgs/shop/product-8-1.jpg') }}" alt="">
                                                <img class="hover-img" src="{{ url('frontend/assets/imgs/shop/product-8-2.jpg') }}" alt="">
                                            </a>
                                        </div>
                                        
                                    </div>
                                    <div class="product-content-wrap">
                                        <div class="product-category">
                                            <a href="#">Shirt</a>
                                        </div>
                                        <h2><a href="product-full.php">Mens Porcelain Shirt</a></h2>
                                        <div class="product-price">
                                            <span>£238.85 </span>
                                            <span class="old-price">£245.8</span>
                                        </div>
                                        
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--End product-grid-4-->
                    </div>
                    <!--En tab one (Featured)-->
                    <div class="tab-pane fade" id="tab-two" role="tabpanel" aria-labelledby="tab-two">
                        <div class="row product-grid-4">
                            <div class="col-lg-3 col-md-4 col-12 col-sm-6">
                                <div class="product-cart-wrap mb-30">
                                    <div class="product-img-action-wrap">
                                        <div class="product-img product-img-zoom">
                                            <a href="product-full.php">
                                                <img class="default-img" src="{{ url('frontend/assets/imgs/shop/product-9-1.jpg') }}" alt="">
                                                <img class="hover-img" src="{{ url('frontend/assets/imgs/shop/product-9-2.jpg') }}" alt="">
                                            </a>
                                        </div>
                                        
                                        <div class="product-badges product-badges-position product-badges-mrg">
                                            <span class="hot">Out of Stock</span>
                                        </div>
                                    </div>
                                    <div class="product-content-wrap">
                                        <div class="product-category">
                                            <a href="#">Boys Trousers </a>
                                        </div>
                                        <h2><a href="product-full.php">Lorem ipsum dolor</a></h2>
                                        <div class="product-price">
                                            <span>£238.85 </span>
                                            <span class="old-price">£245.8</span>
                                        </div>
                                        
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-4 col-12 col-sm-6">
                                <div class="product-cart-wrap mb-30">
                                    <div class="product-img-action-wrap">
                                        <div class="product-img product-img-zoom">
                                            <a href="product-full.php">
                                                <img class="default-img" src="{{ url('frontend/assets/imgs/shop/product-10-1.jpg') }}" alt="">
                                                <img class="hover-img" src="{{ url('frontend/assets/imgs/shop/product-10-2.jpg') }}" alt="">
                                            </a>
                                        </div>
                                        
                                        <div class="product-badges product-badges-position product-badges-mrg">
                                            <span class="new">New</span>
                                        </div>
                                    </div>
                                    <div class="product-content-wrap">
                                        <div class="product-category">
                                            <a href="#">Hoodies</a>
                                        </div>
                                        <h2><a href="product-full.php">Sed tincidunt interdum</a></h2>
                                        <div class="product-price">
                                            <span>£138.85 </span>
                                            <span class="old-price">£255.8</span>
                                        </div>
                                        
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-4 col-12 col-sm-6">
                                <div class="product-cart-wrap mb-30">
                                    <div class="product-img-action-wrap">
                                        <div class="product-img product-img-zoom">
                                            <a href="product-full.php">
                                                <img class="default-img" src="{{ url('frontend/assets/imgs/shop/product-11-1.jpg') }}" alt="">
                                                <img class="hover-img" src="{{ url('frontend/assets/imgs/shop/product-11-2.jpg') }}" alt="">
                                            </a>
                                        </div>
                                        
                                        <div class="product-badges product-badges-position product-badges-mrg">
                                            <span class="best">Best Sell</span>
                                        </div>
                                    </div>
                                    <div class="product-content-wrap">
                                        <div class="product-category">
                                            <a href="#">Blazers</a>
                                        </div>
                                        <h2><a href="product-full.php">Fusce metus orci</a></h2>
                                        <div class="product-price">
                                            <span>£338.85 </span>
                                            <span class="old-price">£445.8</span>
                                        </div>
                                        
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-4 col-12 col-sm-6">
                                <div class="product-cart-wrap mb-30">
                                    <div class="product-img-action-wrap">
                                        <div class="product-img product-img-zoom">
                                            <a href="product-full.php">
                                                <img class="default-img" src="{{ url('frontend/assets/imgs/shop/product-12-1.jpg') }}" alt="">
                                                <img class="hover-img" src="{{ url('frontend/assets/imgs/shop/product-12-2.jpg') }}" alt="">
                                            </a>
                                        </div>
                                        
                                        <div class="product-badges product-badges-position product-badges-mrg">
                                            <span class="sale">Sale</span>
                                        </div>
                                    </div>
                                    <div class="product-content-wrap">
                                        <div class="product-category">
                                            <a href="#">Hoodies</a>
                                        </div>
                                        <h2><a href="product-full.php">Integer venenatis libero</a></h2>
                                        <div class="product-price">
                                            <span>£123.85 </span>
                                            <span class="old-price">£235.8</span>
                                        </div>
                                        
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-4 col-12 col-sm-6">
                                <div class="product-cart-wrap mb-30">
                                    <div class="product-img-action-wrap">
                                        <div class="product-img product-img-zoom">
                                            <a href="product-full.php">
                                                <img class="default-img" src="{{ url('frontend/assets/imgs/shop/product-13-1.jpg') }}" alt="">
                                                <img class="hover-img" src="{{ url('frontend/assets/imgs/shop/product-13-2.jpg') }}" alt="">
                                            </a>
                                        </div>
                                        
                                        <div class="product-badges product-badges-position product-badges-mrg">
                                            <span class="hot">Out of Stock</span>
                                        </div>
                                    </div>
                                    <div class="product-content-wrap">
                                        <div class="product-category">
                                            <a href="#">T-Shirts</a>
                                        </div>
                                        <h2><a href="product-full.php">Cras tempor orci id</a></h2>
                                        <div class="product-price">
                                            <span>£28.85 </span>
                                            <span class="old-price">£45.8</span>
                                        </div>
                                        
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-4 col-12 col-sm-6">
                                <div class="product-cart-wrap mb-30">
                                    <div class="product-img-action-wrap">
                                        <div class="product-img product-img-zoom">
                                            <a href="product-full.php">
                                                <img class="default-img" src="{{ url('frontend/assets/imgs/shop/product-14-1.jpg') }}" alt="">
                                                <img class="hover-img" src="{{ url('frontend/assets/imgs/shop/product-14-2.jpg') }}" alt="">
                                            </a>
                                        </div>
                                        
                                        <div class="product-badges product-badges-position product-badges-mrg">
                                            <span class="hot">Out of Stock</span>
                                        </div>
                                    </div>
                                    <div class="product-content-wrap">
                                        <div class="product-category">
                                            <a href="#">Jumpers</a>
                                        </div>
                                        <h2><a href="product-full.php">Nullam cursus mi qui</a></h2>
                                        <div class="product-price">
                                            <span>£238.85 </span>
                                            <span class="old-price">£245.8</span>
                                        </div>
                                        
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-4 col-12 col-sm-6">
                                <div class="product-cart-wrap mb-30">
                                    <div class="product-img-action-wrap">
                                        <div class="product-img product-img-zoom">
                                            <a href="product-full.php">
                                                <img class="default-img" src="{{ url('frontend/assets/imgs/shop/product-15-1.jpg') }}" alt="">
                                                <img class="hover-img" src="{{ url('frontend/assets/imgs/shop/product-15-2.jpg') }}" alt="">
                                            </a>
                                        </div>
                                        
                                        <div class="product-badges product-badges-position product-badges-mrg">
                                            <span class="new">New</span>
                                        </div>
                                    </div>
                                    <div class="product-content-wrap">
                                        <div class="product-category">
                                            <a href="#">Jacket</a>
                                        </div>
                                        <h2><a href="product-full.php">Fusce fringilla ultrices</a></h2>
                                        <div class="product-price">
                                            <span>£1275.85 </span>
                                        </div>
                                        
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-4 col-12 col-sm-6">
                                <div class="product-cart-wrap mb-30">
                                    <div class="product-img-action-wrap">
                                        <div class="product-img product-img-zoom">
                                            <a href="product-full.php">
                                                <img class="default-img" src="{{ url('frontend/assets/imgs/shop/product-1-1.jpg') }}" alt="">
                                                <img class="hover-img" src="{{ url('frontend/assets/imgs/shop/product-1-2.jpg') }}" alt="">
                                            </a>
                                        </div>
                                        
                                    </div>
                                    <div class="product-content-wrap">
                                        <div class="product-category">
                                            <a href="#">Accessories </a>
                                        </div>
                                        <h2><a href="product-full.php">Sed sollicitudin est</a></h2>
                                        <div class="product-price">
                                            <span>£238.85 </span>
                                            <span class="old-price">£245.8</span>
                                        </div>
                                        
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--End product-grid-4-->
                    </div>
                    <!--En tab two (Popular)-->
                    <div class="tab-pane fade" id="tab-three" role="tabpanel" aria-labelledby="tab-three">
                        <div class="row product-grid-4">
                            <div class="col-lg-3 col-md-4 col-12 col-sm-6">
                                <div class="product-cart-wrap mb-30">
                                    <div class="product-img-action-wrap">
                                        <div class="product-img product-img-zoom">
                                            <a href="product-full.php">
                                                <img class="default-img" src="{{ url('frontend/assets/imgs/shop/product-2-1.jpg')}}" alt="">
                                                <img class="hover-img" src="{{ url('frontend/assets/imgs/shop/product-2-2.jpg')}}" alt="">
                                            </a>
                                        </div>
                                        
                                        <div class="product-badges product-badges-position product-badges-mrg">
                                            <span class="hot">Out of Stock</span>
                                        </div>
                                    </div>
                                    <div class="product-content-wrap">
                                        <div class="product-category">
                                            <a href="#">Hoodies</a>
                                        </div>
                                        <h2><a href="product-full.php">Boys Trousers ut nisl rutrum</a></h2>
                                        <div class="product-price">
                                            <span>£238.85 </span>
                                            <span class="old-price">£245.8</span>
                                        </div>
                                        
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-4 col-12 col-sm-6">
                                <div class="product-cart-wrap mb-30">
                                    <div class="product-img-action-wrap">
                                        <div class="product-img product-img-zoom">
                                            <a href="product-full.php">
                                                <img class="hover-img" src="{{ url('frontend/assets/imgs/shop/product-3-1.jpg')}}" alt="">
                                                <img class="default-img" src="{{ url('frontend/assets/imgs/shop/product-3-2.jpg')}}" alt="">
                                            </a>
                                        </div>
                                        
                                        <div class="product-badges product-badges-position product-badges-mrg">
                                            <span class="new">New</span>
                                        </div>
                                    </div>
                                    <div class="product-content-wrap">
                                        <div class="product-category">
                                            <a href="#">Hoodies</a>
                                        </div>
                                        <h2><a href="product-full.php">Nullam dapibus pharetra</a></h2>
                                        <div class="product-price">
                                            <span>£138.85 </span>
                                            <span class="old-price">£255.8</span>
                                        </div>
                                        
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-4 col-12 col-sm-6">
                                <div class="product-cart-wrap mb-30">
                                    <div class="product-img-action-wrap">
                                        <div class="product-img product-img-zoom">
                                            <a href="product-full.php">
                                                <img class="hover-img" src="{{ url('frontend/assets/imgs/shop/product-4-1.jpg')}}" alt="">
                                                <img class="default-img" src="{{ url('frontend/assets/imgs/shop/product-4-2.jpg')}}" alt="">
                                            </a>
                                        </div>
                                        
                                        <div class="product-badges product-badges-position product-badges-mrg">
                                            <span class="best">Best Sell</span>
                                        </div>
                                    </div>
                                    <div class="product-content-wrap">
                                        <div class="product-category">
                                            <a href="#">Blazers</a>
                                        </div>
                                        <h2><a href="product-full.php">Morbi dictum finibus</a></h2>
                                        <div class="product-price">
                                            <span>£338.85 </span>
                                            <span class="old-price">£445.8</span>
                                        </div>
                                        
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-4 col-12 col-sm-6">
                                <div class="product-cart-wrap mb-30">
                                    <div class="product-img-action-wrap">
                                        <div class="product-img product-img-zoom">
                                            <a href="product-full.php">
                                                <img class="hover-img" src="{{ url('frontend/assets/imgs/shop/product-5-1.jpg')}}" alt="">
                                                <img class="default-img" src="{{ url('frontend/assets/imgs/shop/product-5-2.jpg')}}" alt="">
                                            </a>
                                        </div>
                                        
                                        <div class="product-badges product-badges-position product-badges-mrg">
                                            <span class="sale">Sale</span>
                                        </div>
                                    </div>
                                    <div class="product-content-wrap">
                                        <div class="product-category">
                                            <a href="#">Hoodies</a>
                                        </div>
                                        <h2><a href="product-full.php">Nunc volutpat massa</a></h2>
                                        <div class="product-price">
                                            <span>£123.85 </span>
                                            <span class="old-price">£235.8</span>
                                        </div>
                                        
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-4 col-12 col-sm-6">
                                <div class="product-cart-wrap mb-30">
                                    <div class="product-img-action-wrap">
                                        <div class="product-img product-img-zoom">
                                            <a href="product-full.php">
                                                <img class="hover-img" src="{{ url('frontend/assets/imgs/shop/product-6-1.jpg')}}" alt="">
                                                <img class="default-img" src="{{ url('frontend/assets/imgs/shop/product-6-2.jpg')}}" alt="">
                                            </a>
                                        </div>
                                        
                                        <div class="product-badges product-badges-position product-badges-mrg">
                                            <span class="hot">Out of Stock</span>
                                        </div>
                                    </div>
                                    <div class="product-content-wrap">
                                        <div class="product-category">
                                            <a href="#">T-Shirts</a>
                                        </div>
                                        <h2><a href="product-full.php">Nullam ultricies luctus</a></h2>
                                        <div class="product-price">
                                            <span>£28.85 </span>
                                            <span class="old-price">£45.8</span>
                                        </div>
                                        
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-4 col-12 col-sm-6">
                                <div class="product-cart-wrap mb-30">
                                    <div class="product-img-action-wrap">
                                        <div class="product-img product-img-zoom">
                                            <a href="product-full.php">
                                                <img class="hover-img" src="{{ url('frontend/assets/imgs/shop/product-7-1.jpg')}}" alt="">
                                                <img class="default-img" src="{{ url('frontend/assets/imgs/shop/product-7-2.jpg')}}" alt="">
                                            </a>
                                        </div>
                                        
                                        <div class="product-badges product-badges-position product-badges-mrg">
                                            <span class="hot">Out of Stock</span>
                                        </div>
                                    </div>
                                    <div class="product-content-wrap">
                                        <div class="product-category">
                                            <a href="#">Jumpers</a>
                                        </div>
                                        <h2><a href="product-full.php">Nullam mattis enim</a></h2>
                                        <div class="product-price">
                                            <span>£238.85 </span>
                                            <span class="old-price">£245.8</span>
                                        </div>
                                        
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-4 col-12 col-sm-6">
                                <div class="product-cart-wrap mb-30">
                                    <div class="product-img-action-wrap">
                                        <div class="product-img product-img-zoom">
                                            <a href="product-full.php">
                                                <img class="hover-img" src="{{ url('frontend/assets/imgs/shop/product-8-1.jpg')}}" alt="">
                                                <img class="default-img" src="{{ url('frontend/assets/imgs/shop/product-8-2.jpg')}}" alt="">
                                            </a>
                                        </div>
                                        
                                        <div class="product-badges product-badges-position product-badges-mrg">
                                            <span class="new">New</span>
                                        </div>
                                    </div>
                                    <div class="product-content-wrap">
                                        <div class="product-category">
                                            <a href="#">Jacket</a>
                                        </div>
                                        <h2><a href="product-full.php">Vivamus sollicitudin</a></h2>
                                        <div class="product-price">
                                            <span>£1275.85 </span>
                                        </div>
                                        
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-4 col-12 col-sm-6">
                                <div class="product-cart-wrap mb-30">
                                    <div class="product-img-action-wrap">
                                        <div class="product-img product-img-zoom">
                                            <a href="product-full.php">
                                                <img class="hover-img" src="{{ url('frontend/assets/imgs/shop/product-9-1.jpg')}}" alt="">
                                                <img class="default-img" src="{{ url('frontend/assets/imgs/shop/product-9-2.jpg')}}" alt="">
                                            </a>
                                        </div>
                                        
                                    </div>
                                    <div class="product-content-wrap">
                                        <div class="product-category">
                                            <a href="#">Accessories </a>
                                        </div>
                                        <h2><a href="product-full.php"> Boys Trousers ut nisl rutrum</a></h2>
                                        <div class="product-price">
                                            <span>£238.85 </span>
                                            <span class="old-price">£245.8</span>
                                        </div>
                                        
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--End product-grid-4-->
                    </div>
                    <!--En tab three (New added)-->
                </div>
                <!--End tab-content-->
            </div>
        </section>
        <section class="banner-2 section-padding pb-0">
            <div class="container">
                <div class="banner-img banner-big wow fadeIn animated f-none">
                    <img src="{{ url('frontend/assets/imgs/banner/banner-4.png')}}" alt="">
                    <div class="banner-text d-md-block d-none">
                        <h4 class="mb-15 mt-40 text-brand">Hoodies</h4>
                        <h1 class="fw-600 mb-20">Get the multi-color hoodies<br>in discounted price</h1>
                        <a href="#" class="btn">Learn More <i class="fi-rs-arrow-right"></i></a>
                    </div>
                </div>
            </div>
        </section>
        <section class="popular-categories section-padding mt-15">
            <div class="container wow fadeIn animated">
                <h3 class="section-title mb-20"><span>Popular</span> Categories</h3>
                <div class="carausel-6-columns-cover position-relative">
                    <div class="slider-arrow slider-arrow-2 carausel-6-columns-arrow" id="carausel-6-columns-arrows"></div>
                    <div class="carausel-6-columns" id="carausel-6-columns">
                        <div class="card-1">
                            <figure class=" img-hover-scale overflow-hidden">
                                <a href="#"><img src="{{ url('frontend/assets/imgs/shop/category-thumb-1.jpg')}}" alt=""></a>
                            </figure>
                            <h5><a href="#">T-Shirt</a></h5>
                        </div>
                        <div class="card-1">
                            <figure class=" img-hover-scale overflow-hidden">
                                <a href="#"> <img src="{{ url('frontend/assets/imgs/shop/category-thumb-2.jpg') }}" alt=""></a>
                            </figure>
                            <h5><a href="#">Bags</a></h5>
                        </div>
                        <div class="card-1">
                            <figure class=" img-hover-scale overflow-hidden">
                                <a href="#"><img src="{{ url('frontend/assets/imgs/shop/category-thumb-3.jpg') }}" alt=""></a>
                            </figure>
                            <h5><a href="#">Jacket</a></h5>
                        </div>
                        <div class="card-1">
                            <figure class=" img-hover-scale overflow-hidden">
                                <a href="#"><img src="{{ url('frontend/assets/imgs/shop/category-thumb-4.jpg') }}" alt=""></a>
                            </figure>
                            <h5><a href="#">Blazers</a></h5>
                        </div>
                        <div class="card-1">
                            <figure class=" img-hover-scale overflow-hidden">
                                <a href="#"><img src="{{ url('frontend/assets/imgs/shop/category-thumb-5.jpg') }}" alt=""></a>
                            </figure>
                            <h5><a href="#">Hoodies</a></h5>
                        </div>
                        <div class="card-1">
                            <figure class=" img-hover-scale overflow-hidden">
                                <a href="#"><img src="{{ url('frontend/assets/imgs/shop/category-thumb-6.jpg') }}" alt=""></a>
                            </figure>
                            <h5><a href="#">Sweatshirts</a></h5>
                        </div>
                        <div class="card-1">
                            <figure class=" img-hover-scale overflow-hidden">
                                <a href="#"><img src="{{ url('frontend/assets/imgs/shop/category-thumb-7.jpg') }}" alt=""></a>
                            </figure>
                            <h5><a href="#">Skirts</a></h5>
                        </div>
                        <div class="card-1">
                            <figure class=" img-hover-scale overflow-hidden">
                                <a href="#"><img src="{{ url('frontend/assets/imgs/shop/category-thumb-8.jpg') }}" alt=""></a>
                            </figure>
                            <h5><a href="#">Trousers</a></h5>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <section class="banners mb-20">
            <div class="container">
                <div class="row">
                    <div class="col-lg-4 col-md-6">
                        <div class="banner-img wow fadeIn animated">
                            <img src="{{ url('frontend/assets/imgs/banner/banner-1.png') }}" alt="">
                            <div class="banner-text">
                                <span>Smart Offer</span>
                                <h4>Save 20% on <br>Woman Bag</h4>
                                <a href="#">Shop Now <i class="fi-rs-arrow-right"></i></a>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6">
                        <div class="banner-img wow fadeIn animated">
                            <img src="{{ url('frontend/assets/imgs/banner/banner-2.png') }}" alt="">
                            <div class="banner-text">
                                <span>Sale off</span>
                                <h4>Great Summer <br>Collection</h4>
                                <a href="#">Shop Now <i class="fi-rs-arrow-right"></i></a>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 d-md-none d-lg-flex">
                        <div class="banner-img wow fadeIn animated  mb-sm-0">
                            <img src="{{ url('frontend/assets/imgs/banner/banner-3.png') }}" alt="">
                            <div class="banner-text">
                                <span>New Arrivals</span>
                                <h4>Shop Today’s <br>Deals & Offers</h4>
                                <a href="#">Shop Now <i class="fi-rs-arrow-right"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <section class="pt-25 pb-20">
            <div class="container wow fadeIn animated">
                <h3 class="section-title mb-20"><span>New</span> Arrivals</h3>
                <div class="carausel-6-columns-cover position-relative">
                    <div class="slider-arrow slider-arrow-2 carausel-6-columns-arrow" id="carausel-6-columns-2-arrows"></div>
                    <div class="carausel-6-columns carausel-arrow-center" id="carausel-6-columns-2">
                        <div class="product-cart-wrap small hover-up">
                            <div class="product-img-action-wrap">
                                <div class="product-img product-img-zoom">
                                    <a href="product-full.php">
                                        <img class="default-img" src="{{ url('frontend/assets/imgs/shop/product-2-1.jpg') }}" alt="">
                                        <img class="hover-img" src="{{ url('frontend/assets/imgs/shop/product-2-2.jpg') }}" alt="">
                                    </a>
                                </div>
                                
                                <div class="product-badges product-badges-position product-badges-mrg">
                                    <span class="hot">Out of Stock</span>
                                </div>
                            </div>
                            <div class="product-content-wrap">
                                <h2><a href="product-full.php">Lorem ipsum dolor</a></h2>
                                <div class="product-price">
                                    <span>£238.85 </span>
                                    <span class="old-price">£245.8</span>
                                </div>
                            </div>
                        </div>
                        <!--End product-cart-wrap-2-->
                        <div class="product-cart-wrap small hover-up">
                            <div class="product-img-action-wrap">
                                <div class="product-img product-img-zoom">
                                    <a href="product-full.php">
                                        <img class="default-img" src="{{ url('frontend/assets/imgs/shop/product-4-1.jpg') }}" alt="">
                                        <img class="hover-img" src="{{ url('frontend/assets/imgs/shop/product-4-2.jpg') }}" alt="">
                                    </a>
                                </div>
                                
                                <div class="product-badges product-badges-position product-badges-mrg">
                                    <span class="new">New</span>
                                </div>
                            </div>
                            <div class="product-content-wrap">
                                <h2><a href="product-full.php">Aliquam posuere</a></h2>
                                <div class="product-price">
                                    <span>£173.85 </span>
                                    <span class="old-price">£185.8</span>
                                </div>
                            </div>
                        </div>
                        <!--End product-cart-wrap-2-->
                        <div class="product-cart-wrap small hover-up">
                            <div class="product-img-action-wrap">
                                <div class="product-img product-img-zoom">
                                    <a href="product-full.php">
                                        <img class="default-img" src="{{ url('frontend/assets/imgs/shop/product-15-1.jpg') }}" alt="">
                                        <img class="hover-img" src="{{ url('frontend/assets/imgs/shop/product-15-2.jpg') }}" alt="">
                                    </a>
                                </div>
                                
                                <div class="product-badges product-badges-position product-badges-mrg">
                                    <span class="sale">Sale</span>
                                </div>
                            </div>
                            <div class="product-content-wrap">
                                <h2><a href="product-full.php">Sed dapibus orci</a></h2>
                                <div class="product-price">
                                    <span>£215.85 </span>
                                    <span class="old-price">£235.8</span>
                                </div>
                            </div>
                        </div>
                        <!--End product-cart-wrap-2-->
                        <div class="product-cart-wrap small hover-up">
                            <div class="product-img-action-wrap">
                                <div class="product-img product-img-zoom">
                                    <a href="product-full.php">
                                        <img class="default-img" src="{{ url('frontend/assets/imgs/shop/product-3-1.jpg') }}" alt="">
                                        <img class="hover-img" src="{{ url('frontend/assets/imgs/shop/product-3-2.jpg') }}" alt="">
                                    </a>
                                </div>
                                
                                <div class="product-badges product-badges-position product-badges-mrg">
                                    <span class="hot">Out of Stock</span>
                                </div>
                            </div>
                            <div class="product-content-wrap">
                                <h2><a href="product-full.php">Boys Trousers congue</a></h2>
                                <div class="product-price">
                                    <span>£83.8 </span>
                                    <span class="old-price">£125.2</span>
                                </div>
                            </div>
                        </div>
                        <!--End product-cart-wrap-2-->
                        <div class="product-cart-wrap small hover-up">
                            <div class="product-img-action-wrap">
                                <div class="product-img product-img-zoom">
                                    <a href="product-full.php">
                                        <img class="default-img" src="{{ url('frontend/assets/imgs/shop/product-9-1.jpg') }}" alt="">
                                        <img class="hover-img" src="{{ url('frontend/assets/imgs/shop/product-9-2.jpg') }}" alt="">
                                    </a>
                                </div>
                                
                                <div class="product-badges product-badges-position product-badges-mrg">
                                    <span class="hot">Out of Stock</span>
                                </div>
                            </div>
                            <div class="product-content-wrap">
                                <h2><a href="product-full.php">Curabitur porta</a></h2>
                                <div class="product-price">
                                    <span>£1238.85 </span>
                                    <span class="old-price">£1245.8</span>
                                </div>
                            </div>
                        </div>
                        <!--End product-cart-wrap-2-->
                        <div class="product-cart-wrap small hover-up">
                            <div class="product-img-action-wrap">
                                <div class="product-img product-img-zoom">
                                    <a href="product-full.php">
                                        <img class="default-img" src="{{ url('frontend/assets/imgs/shop/product-7-1.jpg') }}" alt="">
                                        <img class="hover-img" src="{{ url('frontend/assets/imgs/shop/product-7-2.jpg') }}" alt="">
                                    </a>
                                </div>
                                
                                <div class="product-badges product-badges-position product-badges-mrg">
                                    <span class="new">New</span>
                                </div>
                            </div>
                            <div class="product-content-wrap">
                                <h2><a href="product-full.php">Praesent maximus</a></h2>
                                <div class="product-price">
                                    <span>£123 </span>
                                    <span class="old-price">£156</span>
                                </div>
                            </div>
                        </div>
                        <!--End product-cart-wrap-2-->
                        <div class="product-cart-wrap small hover-up">
                            <div class="product-img-action-wrap">
                                <div class="product-img product-img-zoom">
                                    <a href="product-full.php">
                                        <img class="default-img" src="{{ url('frontend/assets/imgs/shop/product-1-1.jpg') }}" alt="">
                                        <img class="hover-img" src="{{ url('frontend/assets/imgs/shop/product-1-2.jpg') }}" alt="">
                                    </a>
                                </div>
                                
                            </div>
                            <div class="product-content-wrap">
                                <h2><a href="product-full.php">Vestibulum ante</a></h2>
                                <div class="product-price">
                                    <span>£238.85 </span>
                                    <span class="old-price">£245.8</span>
                                </div>
                            </div>
                        </div>
                        <!--End product-cart-wrap-2-->
                    </div>
                </div>
            </div>
        </section>
        <section class="deals section-padding">
            <div class="container">
                <div class="row">
                    <div class="col-lg-6 deal-co">
                        <div class="deal wow fadeIn animated mb-md-4 mb-sm-4 mb-lg-0" style="background-image: url('assets/imgs/banner/menu-banner-7.jpg');">
                            <div class="deal-top">
                                <h2 class="text-brand">Deal of the Day.</h2>
                                <h5>Limited quantities.</h5>
                            </div>
                            <div class="deal-content">
                                <h6 class="product-title"><a href="product-full.php">Summer Collection New Morden Design</a></h6>
                                <div class="product-price"><span class="new-price">£139.00</span><span class="old-price">£160.99</span></div>
                            </div>
                            <div class="deal-bottom">
                                <p>Hurry Up! Offer End In:</p>
                                <div class="deals-countdown" data-countdown="2025/03/25 00:00:00"></div>
                                <a href="#" class="btn hover-up">Shop Now <i class="fi-rs-arrow-right"></i></a>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 deal-co">
                        <div class="deal wow fadeIn animated" style="background-image: url('{{url('frontend/assets/imgs/banner/menu-banner-8.jpg')}}');">
                            <div class="deal-top">
                                <h2 class="text-success">Men Sweatshirts</h2>
                                <h5>Shirt & Bag</h5>
                            </div>
                            <div class="deal-content">
                                <h6 class="product-title"><a href="product-full.php">Try something new on vacation</a></h6>
                                <div class="product-price"><span class="new-price">£178.00</span><span class="old-price">£256.99</span></div>
                            </div>
                            <div class="deal-bottom">
                                <p>Hurry Up! Offer End In:</p>
                                <div class="deals-countdown" data-countdown="2025/04/12 00:00:00"></div>
                                <a href="#" class="btn hover-up">Shop Now <i class="fi-rs-arrow-right"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <section class="section-padding">
            <div class="container pb-20">
                <h3 class="section-title mb-20 wow fadeIn animated"><span>Featured</span> Brands</h3>
                <div class="carausel-6-columns-cover position-relative wow fadeIn animated">
                    <div class="slider-arrow slider-arrow-2 carausel-6-columns-arrow" id="carausel-6-columns-3-arrows"></div>
                    <div class="carausel-6-columns text-center" id="carausel-6-columns-3">
                        <div class="brand-logo">
                            <img class="img-grey-hover" src="{{ url('/frontend/assets/imgs/banner/brand-1.png') }}" alt="">
                        </div>
                        <div class="brand-logo">
                            <img class="img-grey-hover" src="{{ url('/frontend/assets/imgs/banner/brand-2.png') }}" alt="">
                        </div>
                        <div class="brand-logo">
                            <img class="img-grey-hover" src="{{ url('/frontend/assets/imgs/banner/brand-3.png') }}" alt="">
                        </div>
                        <div class="brand-logo">
                            <img class="img-grey-hover" src="{{ url('/frontend/assets/imgs/banner/brand-4.png') }}" alt="">
                        </div>
                        <div class="brand-logo">
                            <img class="img-grey-hover" src="{{ url('/frontend/assets/imgs/banner/brand-5.png') }}" alt="">
                        </div>
                        <div class="brand-logo">
                            <img class="img-grey-hover" src="{{ url('/frontend/assets/imgs/banner/brand-6.png') }}" alt="">
                        </div>
                        <div class="brand-logo">
                            <img class="img-grey-hover" src="{{ url('/frontend/assets/imgs/banner/brand-3.png') }}" alt="">
                        </div>
                    </div>
                </div>
            </div>
        </section>
@endsection
