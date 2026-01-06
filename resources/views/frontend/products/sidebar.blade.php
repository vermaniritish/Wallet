<?php
use App\Models\Admin\Sliders;
use App\Models\Admin\HomePage;
use App\Models\API\Products;
$cids = HomePage::get('new_arrivals');
$cids = $cids ? json_decode($cids) : [-1];
$rightTopSlide = Sliders::where('status',1)->orderByRaw('RAND()')->where('type', 'right_top')->limit(1)->first();
$rightBottomSlide = Sliders::where('status',1)->orderByRaw('RAND()')->where('type', 'right_bottom')->limit(1)->first();	
$newProducts = Products::getListing(request(), [
    'products.status' => 1,
    'products.website_visible' => 1,
    '(products.id IN ('.implode(',', $cids).'))'
], 5);
?>
<div class="widget-category mb-15">
    <h5 class="section-title style-1 mb-30 wow fadeIn animated">Category</h5>
    <ul class="categories">
        <?php foreach($categories as $c): ?>
        <li :class="( filters.categories.includes('{{$c->slug}}') ? 'active strong' : '' )"><a href="javascript:;" v-on:click="categoryFilter('{{$c->slug}}')"><?php echo $c->title ?></a></li>
        <?php endforeach; ?>
    </ul>
</div>
<div class="widget-category mb-15">
        <h5 class="section-title style-1 mb-30 wow fadeIn animated">Gender</h5>
        <ul class="widget__form--check">
            <li class="widget__form--check__list">
                <input class="widget__form--check__input" id="check1" v-on:change="genderFilter('Male')" type="checkbox" style="height:16px;width:16px;">
                <label class="widget__form--check__label" for="check1">Men @{{ counts.menCount ? `(${counts.menCount})` : `` }}</label>
            </li>
            <li class="widget__form--check__list">
                <input class="widget__form--check__input" id="check2"  v-on:change="genderFilter('Female')" type="checkbox" style="height:16px;width:16px;">
                <label class="widget__form--check__label" for="check2">Women @{{ counts.womenCount ? `(${counts.womenCount})` : `` }}</label>
            </li>
            <li class="widget__form--check__list">
                <input class="widget__form--check__input" id="check3"  v-on:change="genderFilter('Kids')" type="checkbox" style="height:16px;width:16px;">
                <label class="widget__form--check__label" for="check3">Kids @{{ counts.kidsCount ? `(${counts.kidsCount})` : `` }}</label>
            </li>
            <li class="widget__form--check__list">
                <input class="widget__form--check__input" id="check4"  v-on:change="genderFilter('Unisex')" type="checkbox" style="height:16px;width:16px;">
                <label class="widget__form--check__label" for="check4">Unisex @{{ counts.unisexCount ? `(${counts.unisexCount})` : `` }}</label>
            </li>
            
        </ul>
    </div>
    <div class="widget-category mb-30">
        <h5 class="section-title style-1 mb-30 wow fadeIn animated">Filter By Price</h5>
        <form class="price__filter--form" action="#"> 
            <div class="price__filter--form__inner mb-15 d-flex align-items-center">
                <div class="price__filter--group">
                    <label class="price__filter--label" for="Filter-Price-GTE2">From</label>
                    <div class="price__filter--input border-radius-5 d-flex align-items-center">
                        <span class="price__filter--currency">£</span>
                        <label>
                            <input class="price__filter--input__field border-0" name="filter.v.price.gte" type="number" v-model="filters.fromPrice" >
                        </label>
                    </div>
                </div>
                <div class="price__divider">
                    <span>-</span>
                </div>
                <div class="price__filter--group">
                    <label class="price__filter--label" for="Filter-Price-LTE2">To</label>
                    <div class="price__filter--input border-radius-5 d-flex align-items-center">
                        <span class="price__filter--currency">£</span>
                        <label>
                            <input class="price__filter--input__field border-0" name="filter.v.price.lte" type="number"  v-model="filters.toPrice"> 
                        </label>
                    </div>	
                </div>
            </div>
            <small class="text-danger" v-if="priceError">Provided pricing is incorrect.</small>
            <button type="button" v-on:click="priceFilter" class="price__filter--btn primary__btn" type="submit">Filter</button>
        </form>
    </div>
    
    <!-- Product sidebar Widget -->
    @if($newProducts->count() > 0)
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
                <p class="small">SKU: {{$product['sku_number']}}</p>
                <!-- <div class="product-rate">
                    <div class="product-rating" style="width:90%"></div>
                </div> -->
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