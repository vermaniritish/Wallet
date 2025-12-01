<?php 
use App\Libraries\FileSystem; 
use Illuminate\Support\Str;
?>
@extends('layouts.frontendlayout')
@section('content')
<?php 
$colorIds = Arr::pluck($product->colors, 'id'); 
$nonExchange = $product->non_exchange || $product->sizes->filter(function ($size) {
    return $size->non_exchange == 1;
})->count() > 0;
?>
<div id="{{ $isUniformPage ? 'product-page' : 'product-cat-page' }}">
<div class="page-header breadcrumb-wrap">
            <div class="container">
                <div class="breadcrumb">
                    <a href="index.html" rel="nofollow">Home</a>
                    @if($product->school_id)
                    <span></span> <a :href="site_url + `/school/{{Str::slug($product->school_name . '-' . $product->school_id)}}/uniforms`" rel="nofollow">{{ $product->school_name }}</a>
                    @elseif($product->categories)
                    <span></span> <a :href="site_url + `/{{$product->categories->slug}}`" rel="nofollow">{{ $product->categories && $product->categories->title ? $product->categories->title : ''}}</a>
                    @endif
                    <span></span> {{$product->title}}
                </div>
            </div>
        </div>
        <section class="mt-50 mb-50">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="product-detail accordion-detail">
                            <div class="row mb-50">
                                <div class="col-md-6 col-sm-12 col-xs-12">
                                    <div class="detail-gallery">
                                        <span class="zoom-icon"><i class="fi-rs-search"></i></span>
                                        <!-- MAIN SLIDES -->
                                        <div class="product-image-slider">
                                            @if($product->image)
                                            @foreach($product->image as $i)
                                            <figure class="border-radius-10">
                                                <img src="{{ url($i['large']) }}" >
                                            </figure>
                                            @endforeach
                                            @endif
                                            @if($product->color_images)
                                            @foreach($product->color_images as $k => $i)
                                            <?php $image = FileSystem::getAllSizeImages($i['path']);
                                            if(!in_array($k, $colorIds)) continue; ?>
                                            <figure class="border-radius-10">
                                                <img src="{{ url($image['large']) }}" >
                                            </figure>
                                            @endforeach
                                            @endif
                                        </div>
                                        <!-- THUMBNAILS -->
                                        <div class="slider-nav-thumbnails pl-15 pr-15">
                                            @if($product->image)
                                            @foreach($product->image as $i)
                                            <div><img src="{{ url($i['small']) }}" ></div>
                                            @endforeach
                                            @endif
                                            @if($product->color_images)
                                            <?php
                                            foreach($product->color_images as $k => $i):
                                                if(!in_array($k, $colorIds)) continue; ?>
                                                <?php $image = FileSystem::getAllSizeImages($i['path']);?>
                                                <div class="slider-thumb" data-item="{{ $k }}"><img src="{{ url($image['small']) }}" ></div>
                                            <?php endforeach; ?>
                                            @endif
                                        </div>
                                    </div>
                                    <!-- End Gallery -->
                                </div>
                                <div class="col-md-6 col-sm-12 col-xs-12">
                                    <div class="detail-info">
                                        <div class="d-flex align-items-center flex-row gap-1">
                                            @if($product->school_id)
                                            
                                            <a href="{{url('/school/'.Str::slug($product->school_name . '-' . $product->school_id).'/uniforms')}}" alt="{{$product->school_name}}">{{$product->school_name}}</a>
                                            @else
                                                @if($product->categories)
                                                <a href="{{url('/'.$product->categories->slug)}}" alt="{{$product->categories->title}}">{{$product->categories->title}}</a>
                                                @endif
                                                @if($product->subCategories && $product->subCategories->count() > 0)
                                                <a href="{{url('/'.$product->subCategories[0]->title)}}" alt="{{$product->subCategories[0]->title}}">{{$product->subCategories[0]->title}}</a>
                                                @endif
                                            @endif
                                        </div>

										<h2 class="title-detail" style="padding-top:10px;">{{$product->title}}</h2>
                                        <div class="product-detail-rating">
                                            <div class="pro-details-brand">
                                                <span>Availability:<span class="in-stock text-success ml-5"> 8 Items In Stock</span><span>
                                            </div>
                                            <div class="product-rate-cover text-end">
                                                
                                                <span class="font-small ml-5 text-muted"> SKU:<span class="in-stock text-success ml-5">{{$product->sku_number}}</span></span>
                                            </div>
                                            
                                        </div>
                                        @if($product->printed_logo || $product->embroidered_logo)
                                            <p>
                                                @if($product->printed_logo)
                                                <span class="badge bg-primary">Printed Logo</span>
                                                @endif
                                                @if($product->embroidered_logo)
                                                <span class="badge bg-primary">Embroidered Logo</span>
                                                @endif
                                            </p>
                                        @endif
                                        <div class="clearfix product-price-cover">
                                            <div class="product-price primary-color float-left">
                                                <ins>
                                                    <span class="text-brand">{{_currency($product->price) }}</span>
                                                </ins>
                                                @if($product->max_price > 0)
                                                <ins><span class="old-price font-md ml-15">{{_currency($product->max_price) }}</span></ins>
                                                <span class="save-price  font-md color3 ml-15">{{round(($product->max_price/$product->price) * 100)}}% Off</span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="bt-1 border-color-1 mt-15 mb-15"></div>
                                        <div class="short-desc mb-30">
                                            @if($product->brands && $product->brands->count() > 0)
                                            <p>Brand:
                                            <?php $brands = []; 
                                            foreach($product->brands as $b):
                                            echo '<span class="in-stock text-success ml-5">'.$b->title.'</span>';
                                            endforeach;
                                            ?></p>
                                            @endif
                                            <p>{{ $product->short_description }}</p>
                                            @if($product->size_file || $product->size_guide_video)
                                            <div style="padding-bottom:10px;">
                                                @if($product->size_file)
                                                <a class="mx-2" href="#" onclick="openPDF(event, '{{ url($product->size_file) }}')"><img src="{{ url('assets/img/measure.png') }}" style="width: 20px; vertical-align:middle;"> <strong>Size Guide</strong></a>
                                                @endif
                                                @if($product->size_guide_video)
                                                <a class="mx-2" href="#" onclick="openYouTubeEmbed(event, '{{ url($product->size_guide_video) }}')"><img src="{{ url('assets/img/play.png') }}" style="width: 20px; vertical-align:middle;"> <strong>Size Guide Video</strong></a>
                                                @endif
                                            </div>
                                            @endif
                                        </div>
                                        
                                        <div class="product_sort_info font-xs mb-30" v-if="nonExchangeable">
                                            <ul>
                                                <li class="mb-10" style="color:#d1001f;font-weight:bold;"><i class="fi-rs-refresh mr-5"></i> Made to order only. This is a Non-Exchangeable & Non-Refundable product.</li>
                                            </ul>
                                        </div>

                                        <span id="productId" class="d-none">{{ $product->id }}</span>
                                        <pre id="product-sizes" class="d-none">{{ json_encode($product->sizes ? $product->sizes : []) }}</pre>
                                        <pre id="default-color" class="d-none">{{ json_encode($product->colors && count($product->colors) > 0 ? $product->colors[0] : [])}}</div>
										<div style="padding-bottom:10px;">
										<strong class="mr-10">Color</strong>: @{{ colorTitle ? colorTitle : '' }}
										</div>
                                        <div class="attr-detail attr-color mb-15">
                                            
                                            <ul class="list-filter color-filter">
                                                <?php foreach($product->colors as $c): ?>
                                                <?php $codes = explode(',',$c->color_code); ?>
                                                
                                                <li :class="renderActiveColor('{{$c->id}}')">
                                                    <a v-on:click="selectColor('{{$c->id}}', '{{$c->title}}')" href="javascript:;" data-color="{{ $c->title }}" data-code="{{$c->color_code}}"><span style="background-repeat: no-repeat;{{ (count($codes) > 1 ? 'background:linear-gradient('.$c->color_code.')' : 'background-color:' .$c->color_code) }}" class="product-color-white"></span></a>
                                                </li>
                                                <?php endforeach; ?>
                                            </ul>
                                        </div>
                                        
										<div class="product__variant--list quantity d-flex align-items-center mb-10">
											<div class="productsizesbox">
												<div class="productsizesboxContainer">
													<ul class="productsizesboxUL" data-loading="false" data-test-id="SizeList" v-if="renderSizes().length > 0">
                                                        <li data-active="false" class="ProductSizes-newProductSizesItem-xII" data-test-id="ProductSize" v-for="s in renderSizes()">
                                                            <div class="productsizes" data-stock-status="InStock"><small>@{{ s.size_title }} </small></div>
                                                            <div class="productsizes-stockinfo1">
                                                                <small class="productsizes-stockinfo2" style="color:#088178">£@{{s.price}}</small>
                                                            </div>
                                                            <div class="quantity__box" v-if="s.status">
                                                                <button type="button" class="quantity__value" aria-label="quantity value" value="Decrease Value" v-on:click="decrement(s)">-</button>
                                                                <label>
                                                                    <input type="number" class="quantity__number quickview__value--number" v-on:input="manualQty"  :data-id="s.id" :value="s.quantity && s.quantity > 0 ? s.quantity : ``" />
                                                                </label>
                                                                <button type="button" class="quantity__value" aria-label="quantity value" value="Increase Value"  v-on:click="increment(s)">+</button>
                                                            </div>
                                                            <div class="quantity__box" v-else>
                                                                <button type="button" class="quantity__value" style="width: 100%;padding: 8px 0;"><small style="font-size: 80%;" class="text-danger">Out of Stock</small></button>
                                                            </div>
                                                        </li>												
                                                    </ul>
												</div>
											</div>
										</div>
                                        @if($product->logo_customization)
                                        <br>
                                        <div style="padding-bottom:10px;">
											<p style="text-brand"><i class="fi-rs-scale mr-5"></i> <strong class="mr-10">Add Personalisation</strong></p>
											<small>Please note we do not accept exchanges or refunds for personalised items.</small>
											<br><br>
											
                                            <?php 
                                            $customization = $product->logo_customization;
                                            $customization = $customization ? $customization : null;
                                            echo '<pre id="customization" class="d-none">'.$customization.'</pre>';
                                            ?>
											<div style="padding-bottom:10px;" v-for="(c, k) in customization">
                                                <strong> @{{ c.title }} (@{{currency(c.cost)}})</strong> 
                                                <span v-if="c.required" style="color:#ff0000;font-size:14px">*</span>:  
                                                <input type="text" style="width:99%;font-size:13px;" v-model="c.initial" placeholder="Enter Initial or Text" value="" :required="c.required" maxlength="15">
                                                <small v-if="c.required && !c.initial" class="errors text-danger">This customization is required.</small>
											</div>
										</div>
										<div class="bt-1 border-color-1 mt-30 mb-30"></div>
										@endif
                                        
										<div class="form-group" v-if="nonExchangeable">
											<div class="checkbox">
												<div class="custome-checkbox">
													<input class="form-check-input" type="checkbox" name="iacknowledge" id="iacknowledge" v-model="accept">
													<label class="form-check-label label_info" for="iacknowledge"><span>I acknowledge that there will be no exchange or refunds as these garments are specially made to order.</span></label>
												</div>
											</div>
										</div>
                                        
										 <div class="product-extra-link2">
                                                <button type="submit" class="button button-add-to-cart" v-on:click="addToCart(null)"><i class="fa fa-spin fa-spinner" v-if="adding && !buyNow"></i><i class="fa fa-check text-success" v-else-if="!buyNow && adding === false"></i> Add to cart</button>
                                                <!--<a aria-label="Add To Wishlist" class="action-btn hover-up" href="shop-wishlist.html"><i class="fi-rs-heart"></i></a>
                                                <a aria-label="Compare" class="action-btn hover-up" href="shop-compare.html"><i class="fi-rs-shuffle"></i></a>-->
                                            </div>
                                    </div>
                                    <!-- Detail Info -->
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-10 m-auto entry-main-content">
                                    @if($product->description)
                                    <h2 class="section-title style-1 mb-30">Description</h2>
                                    <div class="description mb-50">
                                        <?php echo $product->description; ?>
                                    </div>
                                    @endif
                                    
                                    <div class="social-icons single-share">
                                        <ul class="text-grey-5 d-inline-block">
                                            <li><strong class="mr-10">Share this:</strong></li>
                                            <li class="social-facebook"><a target="_blank" href="https://www.facebook.com/sharer/sharer.php?u={{url()->current()}}"><img src="{{ url('/frontend/assets/imgs/theme/icons/icon-facebook.svg') }}" alt=""></a></li>
                                            <li class="social-instagram"><a href="instagram://camera"><img src="{{ url('/frontend/assets/imgs/theme/icons/icon-instagram.svg') }}" alt=""></a></li>
                                        </ul>
                                    </div>
                                    
                                </div>
                            </div>
                            @include('frontend.products.similarProducts', ['products' => $similarProducts, 'title' => 'Related Products'])
                        </div>
                    </div>
                </div>
            </div>
        </section>
</div>
@endsection
@push("scripts")
<script>var nonExchange = {{$product->non_exchange ? "true" : "false"}}; </script>
@endpush