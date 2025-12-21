<div class="col-lg-3 col-md-4 col-12 col-sm-6">
    <div class="product-cart-wrap mb-30">
        <div class="product-img-action-wrap">
            <div class="product-img product-img-zoom">
                <a href="{{ url('/'.$product->slug) }}">
                    @foreach($product->image as $k => $image)
                        @if($k < 1)
                        <img class="default-img" src="{{ $image && $image['small'] ? url($image['small']) : url('/frontend/assets/imgs/shop/product-3-1.jpg') }}" alt="product-img">
                        @endif
                    @endforeach
                    @foreach($product->image as $k => $image)
                        @if($k == 1)
                        <img class="hover-img" src="{{ $image && $image['small'] ? url($image['small']) : url('/frontend/assets/imgs/shop/product-3-1.jpg') }}" alt="product-img">
                        @endif
                    @endforeach
                </a>
            </div>
            @if($product['sale_price'] && $product['sale_price'] > 0)
            <div class="product-badges product-badges-position product-badges-mrg">
                <span class="best">Sale</span>
            </div>
            @endif
        </div>
        <div class="product-content-wrap">
            <div class="product-category">
                @if(isset($product['school']) && $product['school'])
                <a href="{{ url('/'.$product->slug) }}">{{$product['school']}}</a>
                @else
                <a href="{{ url('/'.$product->slug) }}">{{$product['category']}} {{$product['gender'] ? ', '.$product['gender'] : ''}}</a>
                @endif
            </div>
            <h2><a href="{{ url('/'.$product->slug) }}">{{$product['title']}}</a></h2>
            <div><span>SKU: {{$product['sku_number']}}</span></div>
            <div class="product-price">
                <span>{{_currency($product['price'])}}</span>
                @if($product['max_price'])
                <span class="old-price">{{_currency($product['max_price'])}}</span>
                @endif
            </div>
        </div>
    </div>
</div>