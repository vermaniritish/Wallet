<?php 
use App\Models\Admin\HomePage;
use App\Models\API\Products;
 ?>
@if(HomePage::get('best_seller_enable'))
    <section class="product__section section--padding pt-0">
        <div class="container-fluid">
            <div class="section__heading text-center mb-50">
                <h2 class="section__heading--maintitle">Our Best Seller</h2>
            </div>
            <div class="product__section--inner product__swiper--activation swiper">
                <div class="swiper-wrapper">
                    @php
                    $selected  = HomePage::get('best_products');
                    $selected = $selected ? json_decode($selected, true) : [-1];
                    $selected = Products::select([
                        'products.id',
                        'products.title',
                        'products.slug',
                        'products.price',
                        'products.phonenumber',
                        'products.image',
                        'products.max_price',
                        'products.price',
                        'products.gender',
                        'product_categories.title as category'
                    ])->leftJoin('product_categories', 'product_categories.id', '=', 'products.category_id')->whereIn('products.id', $selected)->where('products.status', 1)->limit(20)->orderByRaw('Rand()')->get()
                    @endphp
                    @foreach($selected as $s)
                        <div class="swiper-slide">
                        <div class="product__items ">
                            @include('frontend/home/partials/productItem', ['product' => $s])
                        </div>
                    </div>
                    @endforeach
                </div>
                <div class="swiper__nav--btn swiper-button-next"></div>
                <div class="swiper__nav--btn swiper-button-prev"></div>
            </div>
        </div>
    </section>
@endif