<?php 
use App\Models\Admin\Settings;
use App\Models\Admin\Menu;
$user = request()->session()->get('user');
$headerMenu = Menu::select('id', 'key', 'value', 'slug', 'mega_menu as megaMenu')->where('slug', 'header')->get();
foreach ($headerMenu as $k => $v) {
    $headerMenu[$k]->megaMenu = $headerMenu[$k]->megaMenu ? json_decode($headerMenu[$k]->megaMenu) : [];
}
?>
<header class="header-area header-style-3 header-height-2 d-none" id="header">
        <div class="header-top header-top-ptb-1 d-none d-lg-block">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-xl-3 col-lg-4">
                        <div class="header-info">
                            <ul>
                                <!--<li><i class="fi-rs-smartphone"></i> <a href="#">0114 2513275</a></li>-->
                                <li><i class="fi-rs-marker"></i><a  href="#">Our Shops</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-xl-6 col-lg-4">
                        <div class="text-center">
                            <div id="news-flash" class="d-inline-block">
                                <ul>
                                    <li>Get great offers up to 50% off <a href="#">View details</a></li>
                                    <li>Supper Value Deals - Save more with coupons</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-lg-4">
                        <div class="header-info header-info-right">
                            <ul>
                                @if($user)
                                <li><i class="fi-rs-user"></i><a href="{{ url('/my-account') }}">My Account</a></li>
                                @else
                                <li><i class="fi-rs-user"></i><a href="{{ url('/login') }}">Log In</a></li>
                                @endif
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="header-middle header-middle-ptb-1 d-none d-lg-block">
            <div class="container">
                <div class="header-wrap">
                    <div class="logo logo-width-1">
                        <a href="{{url('/')}}">
                            <img src="{{ url(Settings::get('logo')) }}" alt="logo">
                        </a>
                    </div>
                    <div class="header-right">
                        <div class="search-style-2">
                            <form action="#">
                                <select class="select-active">
                                    <option>All Categories</option>
                                    <option>Accessories</option>
                                    <option>Boys</option>
                                    <option>Girls</option>
                                    <option>Doc & Bags</option>
                                    <option>Health Care</option>
                                    <option>Hoodies</option>
                                    <option>Knitwear</option>
                                    <option>PE Leggings & Training Trousers</option>
                                    <option>Polo Shirts & T-shirts</option>
									<option>Seasonal</option>
									<option>Shoes & Socks</option>
									<option>Special Offers</option>
                                    <option>Stationery</option>
									<option>Tabard Bibs and Aprons</option>
                                </select>
                                <input type="text" placeholder="Search for items...">
                            </form>
                        </div>
                        <div class="header-action-right">
                            <div class="header-action-2">
                                <div class="header-action-icon-2">
                                    <a class="mini-cart-icon" href="{{ url('/cart') }}">
                                        <img alt="Pinders" src="{{ url('frontend/assets/imgs/theme/icons/icon-cart.svg') }}">
                                        <span class="pro-count blue" v-if="cartCount > 0">@{{ cartCount }}</span>
                                    </a>
                                    <div class="cart-dropdown-wrap cart-dropdown-hm2 d-none">
                                        <ul>
                                            <li>
                                                <div class="shopping-cart-img">
                                                    <a href="product-full.php"><img alt="Evara" src="{{ url('frontend/assets/imgs/shop/thumbnail-3.jpg') }}"></a>
                                                </div>
                                                <div class="shopping-cart-title">
                                                    <h4><a href="product-full.php">Daisy Casual Bag</a></h4>
                                                    <h4><span>1 × </span>£800.00</h4>
                                                </div>
                                                <div class="shopping-cart-delete">
                                                    <a href="#"><i class="fi-rs-cross-small"></i></a>
                                                </div>
                                            </li>
                                            <li>
                                                <div class="shopping-cart-img">
                                                    <a href="product-full.php"><img alt="Evara" src="{{ url('frontend/assets/imgs/shop/thumbnail-2.jpg') }}"></a>
                                                </div>
                                                <div class="shopping-cart-title">
                                                    <h4><a href="product-full.php">Corduroy Shirts</a></h4>
                                                    <h4><span>1 × </span>£3200.00</h4>
                                                </div>
                                                <div class="shopping-cart-delete">
                                                    <a href="#"><i class="fi-rs-cross-small"></i></a>
                                                </div>
                                            </li>
                                        </ul>
                                        <div class="shopping-cart-footer">
                                            <div class="shopping-cart-total">
                                                <h4>Total <span>£4000.00</span></h4>
                                            </div>
                                            <div class="shopping-cart-button">
                                                <a href="shop-cart.php" class="outline">View cart</a>
                                                <a href="shop-checkout.php">Checkout</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="header-bottom header-bottom-bg-color sticky-bar">
            <div class="container">
                <div class="header-wrap header-space-between position-relative  main-nav">
                    <div class="logo logo-width-1 d-block d-lg-none">
                        <a href="{{url('/')}}">
                            <img src="{{ url(Settings::get('logo')) }}" alt="logo">
                        </a>
                    </div>
                    <div class="header-nav d-none d-lg-flex">
                       
                        <div class="main-menu main-menu-padding-1 main-menu-lh-2 d-none d-lg-block">
                            <nav>
                                <ul>
                                    @foreach ($headerMenu as $k => $menuItem)
                                        <li>
                                            <a href="{{ $menuItem->value }}">{{ $menuItem->key }}
                                                @if (is_array($menuItem->megaMenu) && count($menuItem->megaMenu) > 0)
                                                    <i class="fi-rs-angle-down"></i>
                                                @endif
                                            </a>
                                            @if (is_array($menuItem->megaMenu) && count($menuItem->megaMenu) > 0)
                                                <ul class="sub-menu">
                                                    @foreach ($menuItem->megaMenu as $s)
                                                        <li><a href="{{ $s->link }}">{{$s->title}}</a></li>
                                                    @endforeach
                                                </ul>
                                            @endif
                                        </li>
                                    @endforeach
                                </ul>
                            </nav>
                        </div>
                    </div>
                    <div class="hotline d-none d-lg-block">
                        <p><i class="fi-rs-headset"></i><span>Hotline</span> {{Settings::get('hotline_number')}} </p>
                    </div>
                    <p class="mobile-promotion">Happy <span class="text-brand">Mother's Day</span>. Big Sale Up to 40%</p>
                    <div class="header-action-right d-block d-lg-none">
                        <div class="header-action-2">
                            <!--<div class="header-action-icon-2">
                                <a href="shop-wishlist.html">
                                    <img alt="Evara" src="assets/imgs/theme/icons/icon-heart.svg">
                                    <span class="pro-count white">4</span>
                                </a>
                            </div>-->
                            <div class="header-action-icon-2">
                                <a class="mini-cart-icon" href="{{url('/cart')}}">
                                    <img alt="Evara" src="assets/imgs/theme/icons/icon-cart.svg">
                                    <span class="pro-count white" v-if="cartcount() > 0">@{{ cartcount() }}</span>
                                </a>
                                <div class="cart-dropdown-wrap cart-dropdown-hm2 d-none">
                                    <ul>
                                        <li>
                                            <div class="shopping-cart-img">
                                                <a href="product-full.php"><img alt="Evara" src="assets/imgs/shop/thumbnail-3.jpg"></a>
                                            </div>
                                            <div class="shopping-cart-title">
                                                <h4><a href="product-full.php">Plain Striola Shirts</a></h4>
                                                <h3><span>1 × </span>£800.00</h3>
                                            </div>
                                            <div class="shopping-cart-delete">
                                                <a href="#"><i class="fi-rs-cross-small"></i></a>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="shopping-cart-img">
                                                <a href="product-full.php"><img alt="Evara" src="assets/imgs/shop/thumbnail-4.jpg"></a>
                                            </div>
                                            <div class="shopping-cart-title">
                                                <h4><a href="product-full.php">Macbook Pro 2022</a></h4>
                                                <h3><span>1 × </span>£3500.00</h3>
                                            </div>
                                            <div class="shopping-cart-delete">
                                                <a href="#"><i class="fi-rs-cross-small"></i></a>
                                            </div>
                                        </li>
                                    </ul>
                                    <div class="shopping-cart-footer">
                                        <div class="shopping-cart-total">
                                            <h4>Total <span>£383.00</span></h4>
                                        </div>
                                        <div class="shopping-cart-button">
                                            <a href="shop-cart.html">View cart</a>
                                            <a href="shop-checkout.html">Checkout</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="header-action-icon-2 d-block d-lg-none">
                                <div class="burger-icon burger-icon-white">
                                    <span class="burger-icon-top"></span>
                                    <span class="burger-icon-mid"></span>
                                    <span class="burger-icon-bottom"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
</header>
<div class="mobile-header-active mobile-header-wrapper-style d-none">
    <div class="mobile-header-wrapper-inner">
        <div class="mobile-header-top">
            <div class="mobile-header-logo">
                <a href="{{ url('/') }}"><img src="{{ url('/assets/imgs/theme/logo.jpg') }}" alt="logo"></a>
            </div>
            <div class="mobile-menu-close close-style-wrap close-style-position-inherit">
                <button class="close-style search-close">
                    <i class="icon-top"></i>
                    <i class="icon-bottom"></i>
                </button>
            </div>
        </div>
        <div class="mobile-header-content-area">
            <div class="mobile-search search-style-3 mobile-header-border">
                <form action="#">
                    <input type="text" placeholder="Search for items…">
                    <button type="submit"><i class="fi-rs-search"></i></button>
                </form>
            </div>
            <div class="mobile-menu-wrap mobile-header-border">
                <div class="main-categori-wrap mobile-header-border">
                    <a class="categori-button-active-2" href="#">
                        <span class="fi-rs-apps"></span> Browse Categories
                    </a>
                    <div class="categori-dropdown-wrap categori-dropdown-active-small">
                        <ul>
                            <li><a href="#"><i class="evara-font-dress"></i>Girl's Clothing</a></li>
                            <li><a href="#"><i class="evara-font-tshirt"></i>Boy's Clothing</a></li>
                            <li><a href="#"><i class="evara-font-diamond"></i>Jewelry & Accessories</a></li>
                            <li><a href="#"><i class="evara-font-home"></i>Home & Garden</a></li>
                            <li><a href="#"><i class="evara-font-high-heels"></i>Shoes</a></li>
                            <li><a href="#"><i class="evara-font-teddy-bear"></i>Mother & Kids</a></li>
                        </ul>
                    </div>
                </div>
                <!-- mobile menu start -->
                <nav>
                    <ul class="mobile-menu">
                        @foreach ($headerMenu as $k => $menuItem)
                            <li class="{{ (is_array($menuItem->megaMenu) && count($menuItem->megaMenu) > 0 ? 'menu-item-has-children' : '') }}">
                                @if (is_array($menuItem->megaMenu) && count($menuItem->megaMenu) > 0)
                                <span class="menu-expand"></span>
                                @endif

                                <a href="{{ $menuItem->value }}">{{ $menuItem->key }}
                                    @if (is_array($menuItem->megaMenu) && count($menuItem->megaMenu) > 0)
                                        <i class="fi-rs-angle-down"></i>
                                    @endif
                                </a>
                                @if (is_array($menuItem->megaMenu) && count($menuItem->megaMenu) > 0)
                                    <ul class="dropdown">
                                        @foreach ($menuItem->megaMenu as $s)
                                            <li><a href="{{ $s->link }}">{{$s->title}}</a></li>
                                        @endforeach
                                    </ul>
                                @endif
                            </li>
                        @endforeach
                    </ul>
                </nav>
                <!-- mobile menu end -->
            </div>
            <div class="mobile-header-info-wrap mobile-header-border">
                <div class="single-mobile-header-info mt-30">
                    <a href="#"> Our Shops </a>
                </div>
                <div class="single-mobile-header-info">
                    <a href="#">Log In</a>
                </div>
                <div class="single-mobile-header-info">
                    <a href="#">0114 2513275</a>
                </div>
            </div>
            <div class="mobile-social-icon">
                <h5 class="mb-15 text-grey-4">Follow Us</h5>
                <a href="#"><img src="{{ url('/assets/imgs/theme/icons/icon-facebook.svg') }}" alt=""></a>
                <a href="#"><img src="{{ url('/assets/imgs/theme/icons/icon-instagram.svg') }}" alt=""></a>
                <a href="#"><img src="{{ url('/assets/imgs/theme/icons/icon-pinterest.svg') }}" alt=""></a>
                <a href="#"><img src="{{ url('/assets/imgs/theme/icons/icon-youtube.svg') }}" alt=""></a>
            </div>
        </div>
    </div>
</div>