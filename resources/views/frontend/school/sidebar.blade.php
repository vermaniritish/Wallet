<div class="col-lg-3 primary-sidebar sticky-sidebar">
                <div class="row">
                    <div class="col-lg-12 col-mg-6"></div>
                    <div class="col-lg-12 col-mg-6"></div>
                </div>
                <div class="widget-category mb-30">
                    <h5 class="section-title style-1 mb-30 wow fadeIn animated">Shop By Category</h5>
                    <ul class="categories">
                        @foreach($categories as $c)
                        <li><a href="{{ url('/'.$c->slug) }}">{{ $c->title }}</li>
                        @endforeach
                    </ul>
                </div>
                
                <!-- Product sidebar Widget -->
                <div class="sidebar-widget product-sidebar  mb-30 p-30 bg-grey border-radius-10">
                    <div class="widget-header position-relative mb-20 pb-10">
                        <h5 class="widget-title mb-10">New products</h5>
                        <div class="bt-1 border-color-1"></div>
                    </div>
                    <div class="single-post clearfix">
                        <div class="image">
                            <img src="assets/imgs/shop/thumbnail-3.jpg" alt="#">
                        </div>
                        <div class="content pt-10">
                            <h5><a href="shop-product-detail.html">Chen Cardigan</a></h5>
                            <p class="price mb-0 mt-5">£99.50</p>
                            <div class="product-rate">
                                <div class="product-rating" style="width:90%"></div>
                            </div>
                        </div>
                    </div>
                    <div class="single-post clearfix">
                        <div class="image">
                            <img src="assets/imgs/shop/thumbnail-4.jpg" alt="#">
                        </div>
                        <div class="content pt-10">
                            <h6><a href="shop-product-detail.html">Chen Sweater</a></h6>
                            <p class="price mb-0 mt-5">£89.50</p>
                            <div class="product-rate">
                                <div class="product-rating" style="width:80%"></div>
                            </div>
                        </div>
                    </div>
                    <div class="single-post clearfix">
                        <div class="image">
                            <img src="assets/imgs/shop/thumbnail-5.jpg" alt="#">
                        </div>
                        <div class="content pt-10">
                            <h6><a href="shop-product-detail.html">Colorful Jacket</a></h6>
                            <p class="price mb-0 mt-5">£25</p>
                            <div class="product-rate">
                                <div class="product-rating" style="width:60%"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="banner-img wow fadeIn mb-45 animated d-lg-block d-none">
                    <img src="assets/imgs/banner/banner-11.jpg" alt="">
                    <div class="banner-text">
                        <span>Women Zone</span>
                        <h4>Save 17% on <br>Office Dress</h4>
                        <a href="#">Shop Now <i class="fi-rs-arrow-right"></i></a>
                    </div>
                </div>
            </div>