<?php use App\Models\Admin\HomePage; ?>
<?php if($products && $products->count() > 0 ): ?>
<div class="row mt-60">
    <div class="col-12">
        <h3 class="section-title style-1 mb-30">{{ $title }}</h3>
    </div>
    <div class="col-12">
        <div class="row related-products">
            @foreach($products as $p)
            <div class="col-lg-3 col-md-4 col-12 col-sm-6">
                <div class="product-cart-wrap small hover-up">
                    <div class="product-img-action-wrap">
                        <div class="product-img product-img-zoom">
                            <a href="{{ url('/' . $p->slug) }}" tabindex="0">
                                @if($p->image)
                                <?php 
                                foreach($p->image as $k => $image): if($k > 0) continue;?>
                                <img class="default-img" src="{{ isset($image['small']) && $image['small'] ? $image['small'] : '' }}" alt="product-img">
                                <?php endforeach; ?>
                                @else
                                <img class="default-img" src="{{ url('/frontend/assets/imgs/shop/product-2-1.jpg') }}" alt="">
                                @endif
                            </a>
                        </div>
                        
                        
                    </div>
                    <div class="product-content-wrap">
                        <h2><a href="{{ url('/'.$p->slug) }}">{{ $p->title }}</a></h2>
                        @if($p->sku_number)
                        <div><span>SKU: {{$p->sku_number}}</span></div>
                        @endif
                        <div class="product-price">
                            <span>{{_currency($p->price)}}</span>
                            @if($p->max_price)
                            <span class="old-price">{{_currency($p->max_price)}}</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
<?php endif; ?>
<div class="banner-img banner-big wow fadeIn f-none animated mt-50">
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