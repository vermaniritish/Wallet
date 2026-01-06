<?php
use App\Models\Admin\Sliders;
use App\Models\API\Products;
$rightTopSlide = Sliders::where('status',1)->orderByRaw('RAND()')->where('type', 'right_top')->limit(1)->first();
$rightBottomSlide = Sliders::where('status',1)->orderByRaw('RAND()')->where('type', 'right_bottom')->limit(1)->first();	
$newProducts = Products::getListing($request, [
    'products.status' => 1,
    'products.website_visible' => 1,
    '(products.id IN ('.implode(',', $cids).'))'
], 5);
?>
<div class="col-lg-3 primary-sidebar sticky-sidebar">
    <div class="row">
        <div class="col-lg-12 col-mg-6"></div>
        <div class="col-lg-12 col-mg-6"></div>
    </div>
    <div class="widget-category mb-30">
        <h5 class="section-title style-1 mb-30 wow fadeIn animated">Explore Our Full Collection</h5>
        <ul class="categories">
            @foreach($categories as $c)
            <li><a href="{{ url('/'.$c->slug) }}">{{ $c->title }}</li>
            @endforeach
        </ul>
    </div>
    
    <!-- Product sidebar Widget -->
     @if($newProducts)
    <div class="sidebar-widget product-sidebar  mb-30 p-30 bg-grey border-radius-10">
        <div class="widget-header position-relative mb-20 pb-10">
            <h5 class="widget-title mb-10">New products</h5>
            <div class="bt-1 border-color-1"></div>
        </div>
        
        @foreach($newProducts as $product)
        <div class="single-post clearfix">
            <div class="image">
                <a href="{{ url('/'.$product->slug) }}">
                    @foreach($product->image as $k => $image)
                        @if($k < 1)
                        <img src="{{ $image && $image['small'] ? url($image['small']) : url('/frontend/assets/imgs/shop/product-3-1.jpg') }}" alt="product-img">
                        @endif
                    @endforeach
                    @foreach($product->image as $k => $image)
                        @if($k == 1)
                        <img src="{{ $image && $image['small'] ? url($image['small']) : url('/frontend/assets/imgs/shop/product-3-1.jpg') }}" alt="product-img">
                        @endif
                    @endforeach
                </a>
            </div>
            <div class="content pt-10">
                <h5><a href="{{ url('/'.$product->slug) }}">{{$product['title']}}</a></h5>
                <p class="price mb-0 mt-5">{{_currency($product['price'])}}</p>
                <div class="product-rate">
                    <div class="product-rating" style="width:90%"></div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @endif
    @if($rightTopSlide)
    <div class="banner-img banner-1 wow fadeIn  animated home-3 w-100">
        <img class="border-radius-10 w-100" src="{{ url($rightTopSlide->image) }}" alt="">
        <div class="banner-text">
            <span>{{$rightTopSlide->label}}</span>
            <h4><?php echo nl2br($rightTopSlide->heading) ?></h4>
            <a href="{{ $rightTopSlide->button_url }}">{{ $rightTopSlide->button_title }} <i class="fi-rs-arrow-right"></i></a>
        </div>
    </div>
    @endif
    @if($rightBottomSlide)
    <div class="banner-img banner-2 wow fadeIn  animated mb-0 w-100">
        <img class="border-radius-10 w-100" src="{{ url($rightBottomSlide->image) }}" alt="">
        <div class="banner-text">
            <span>{{$rightBottomSlide->label}}</span>
            <h4><?php echo nl2br($rightBottomSlide->heading) ?></h4>
            <a href="{{ $rightBottomSlide->button_url }}">{{ $rightBottomSlide->button_title }} <i class="fi-rs-arrow-right"></i></a>
        </div>
    </div>
    @endif
</div>