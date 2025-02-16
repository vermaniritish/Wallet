<?php if($products && $products->count() > 0 ): ?>
<section class="product__section product__section--style3 section--padding">
    <div class="container product3__section--container">
        <div class="section__heading text-center mb-50">
            <h2 class="section__heading--maintitle">{{ $title }}</h2>
        </div>
        <div class="product__section--inner product__swiper--column4__activation swiper">
            <div class="swiper-wrapper">
                @foreach($products as $p)
                <div class="swiper-slide">
                    <div class="product__items ">
                        <div class="product__items--thumbnail">
                            <a class="product__items--link" href="{{ url('/' . $p->slug) }}">
                                <?php foreach($p->image as $k => $image): ?>
                                    <img class="product__items--img {{ ($k < 1 ? 'product__primary--img' : 'product__secondary--img') }}" src="{{ isset($image['small']) && $image['small'] ? $image['small'] : '' }}" alt="product-img">
                                <?php endforeach; ?>
                            </a>
                            @if($p->sale_price > 0)
                            <div class="product__badge">
                                <span class="product__badge--items sale">Sale</span>
                            </div>
                            @endif
                        </div>
                        <div class="product__items--content">
                            <span class="product__items--content__subtitle">{{$p->categories ? $p->categories->title : ''}}, {{$p->gender ? str_replace(',', ', ', $p->gender) : ''}} </span>
                            <h3 class="product__items--content__title h4"><a href="{{ url('/'.$p->slug) }}">{{ $p->title }}</a></h3>
                            <div class="product__items--price">
                                <span class="current__price">{{_currency($p->price)}} - {{_currency($p->max_price)}}</span>
                                
                                
                            </div>
                            
                            <ul class="product__items--action d-flex">
                                <li class="product__items--action__list">
                                    <a class="product__items--action__btn add__to--cart"  href="{{ url('/' . $p->slug) }}">
                                        <svg class="product__items--action__btn--svg" xmlns="http://www.w3.org/2000/svg" width="22.51" height="20.443" viewBox="0 0 14.706 13.534">
                                            <g transform="translate(0 0)">
                                                <g>
                                                <path data-name="Path 16787" d="M4.738,472.271h7.814a.434.434,0,0,0,.414-.328l1.723-6.316a.466.466,0,0,0-.071-.4.424.424,0,0,0-.344-.179H3.745L3.437,463.6a.435.435,0,0,0-.421-.353H.431a.451.451,0,0,0,0,.9h2.24c.054.257,1.474,6.946,1.555,7.33a1.36,1.36,0,0,0-.779,1.242,1.326,1.326,0,0,0,1.293,1.354h7.812a.452.452,0,0,0,0-.9H4.74a.451.451,0,0,1,0-.9Zm8.966-6.317-1.477,5.414H5.085l-1.149-5.414Z" transform="translate(0 -463.248)" fill="currentColor"></path>
                                                <path data-name="Path 16788" d="M5.5,478.8a1.294,1.294,0,1,0,1.293-1.353A1.325,1.325,0,0,0,5.5,478.8Zm1.293-.451a.452.452,0,1,1-.431.451A.442.442,0,0,1,6.793,478.352Z" transform="translate(-1.191 -466.622)" fill="currentColor"></path>
                                                <path data-name="Path 16789" d="M13.273,478.8a1.294,1.294,0,1,0,1.293-1.353A1.325,1.325,0,0,0,13.273,478.8Zm1.293-.451a.452.452,0,1,1-.431.451A.442.442,0,0,1,14.566,478.352Z" transform="translate(-2.875 -466.622)" fill="currentColor"></path>
                                                </g>
                                            </g>
                                        </svg>
                                        <span class="add__to--cart__text"> + Add to cart</span>
                                    </a>
                                </li>
                                
                                
                            </ul>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            <div class="swiper__nav--btn swiper-button-next"></div>
            <div class="swiper__nav--btn swiper-button-prev"></div>
        </div>
    </div>
</section>
<?php endif; ?>