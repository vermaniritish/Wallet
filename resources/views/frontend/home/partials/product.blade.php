<?php 
use App\Models\Admin\HomePage;
use App\Models\API\Products;
 ?>
<!-- Start product section -->
    <section class="product__section section--padding pt-0">
        <div class="container-fluid">
            <div class="section__heading text-center mb-35">
                <h2 class="section__heading--maintitle">Our Products</h2>
            </div>
            <ul class="product__tab--one product__tab--primary__btn d-flex justify-content-center mb-50">
                <li class="product__tab--primary__btn__list active" data-toggle="tab" data-target="#featured">Featured </li>
                <li class="product__tab--primary__btn__list" data-toggle="tab" data-target="#trending">Trending </li>
                <li class="product__tab--primary__btn__list" data-toggle="tab" data-target="#newarrival">New Arrival  </li>
            </ul>
            <div class="tab_content">
                <div id="featured" class="tab_pane active show">
                    <div class="product__section--inner">
                        <div class="row row-cols-xl-5 row-cols-lg-4 row-cols-md-3 row-cols-2 mb--n30">
                            @php
                            $selected  = HomePage::get('featured_products');
                            $selected = $selected ? json_decode($selected, true) : [-1];
                            $selected = Products::select([
                                'products.id',
                                'products.title',
                                'products.slug',
                                'products.sku_number',
                                'products.price',
                                'products.phonenumber',
                                'products.image',
                                'products.max_price',
                                'products.price',
                                'products.gender',
                                'product_categories.title as category'
                            ])->leftJoin('product_categories', 'product_categories.id', '=', 'products.category_id')->whereIn('products.id', $selected)->where('products.status', 1)->limit(5)->orderByRaw('Rand()')->get()
                            @endphp
                            <?php foreach($selected as $p): ?>
                            <div class="col mb-30">
                            @include('frontend/home/partials/productItem', ['product' => $p])
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
                <div id="trending" class="tab_pane">
                    <div class="product__section--inner">
                        <div class="row row-cols-xl-5 row-cols-lg-4 row-cols-md-3 row-cols-2 mb--n30">
                            @php
                            $selected  = HomePage::get('trending_products');
                            $selected = $selected ? json_decode($selected, true) : [];
                            $selected = Products::select([
                                'products.id',
                                'products.title',
                                'products.slug',
                                'products.sku_number',
                                'products.price',
                                'products.phonenumber',
                                'products.image',
                                'products.max_price',
                                'products.price',
                                'products.gender',
                                'product_categories.title as category'
                            ])->leftJoin('product_categories', 'product_categories.id', '=', 'products.category_id')->whereIn('products.id', $selected)->where('products.status', 1)->limit(5)->orderByRaw('Rand()')->get()
                            @endphp
                            <?php foreach($selected as $p): ?>
                            <div class="col mb-30">
                            @include('frontend/home/partials/productItem', ['product' => $p])
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
                <div id="newarrival" class="tab_pane">
                    <div class="product__section--inner">
                        <div class="row row-cols-xl-5 row-cols-lg-4 row-cols-md-3 row-cols-2 mb--n30">
                            @php
                            $selected  = HomePage::get('new_arrivals');
                            $selected = $selected ? json_decode($selected, true) : [];
                            $selected = Products::select([
                                'products.id',
                                'products.title',
                                'products.slug',
                                'products.sku_number',
                                'products.price',
                                'products.phonenumber',
                                'products.image',
                                'products.max_price',
                                'products.price',
                                'products.gender',
                                'product_categories.title as category'
                            ])->leftJoin('product_categories', 'product_categories.id', '=', 'products.category_id')->whereIn('products.id', $selected)->where('products.status', 1)->limit(5)->orderByRaw('Rand()')->get()
                            @endphp
                            <?php foreach($selected as $p): ?>
                            <div class="col mb-30">
                            @include('frontend/home/partials/productItem', ['product' => $p])
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
<!-- End product section -->