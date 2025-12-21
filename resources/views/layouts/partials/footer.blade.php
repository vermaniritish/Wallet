<?php use App\Models\Admin\Settings; ?>
<footer class="main">
        <section class="newsletter p-30 text-white wow fadeIn animated">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-lg-7 mb-md-3 mb-lg-0">
                        <div class="row align-items-center">
                            <div class="col flex-horizontal-center">
                                <img class="icon-email" src="assets/imgs/theme/icons/icon-email.svg" alt="">
                                <h4 class="font-size-20 mb-0 ml-3">Sign up to Newsletter</h4>
                            </div>
                            <div class="col my-4 my-md-0 des">
                                <h5 class="font-size-15 ml-4 mb-0">...and receive <strong>£25 coupon for first shopping.</strong></h5>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-5">
                        <!-- Subscribe Form -->
                        <form class="form-subcriber d-flex wow fadeIn animated">
                            <input type="email" class="form-control bg-white font-small" placeholder="Enter your email">
                            <button class="btn bg-dark text-white" type="button">Subscribe</button>
                        </form>
                        <!-- End Subscribe Form -->
                    </div>
                </div>
            </div>
        </section>
        <section class="section-padding footer-mid">
            <div class="container pt-15 pb-20">
                <div class="row">
                    <div class="col-lg-4 col-md-6">
                        <div class="widget-about font-md mb-md-5 mb-lg-0">
                            <div class="logo logo-width-1 wow fadeIn animated">
                                <a href="{{url('/')}}"><img src="{{url(Settings::get('logo'))}}" alt="logo"></a>
                            </div>
                            <h5 class="mt-20 mb-10 fw-600 text-grey-4 wow fadeIn animated">Contact</h5>
                            <p class="wow fadeIn animated">
                                <strong>Address: </strong>{{Settings::get('company_address')}}
                            </p>
                            <p class="wow fadeIn animated">
                                <strong>Phone: </strong>{{Settings::get('hotline_number')}}
                            </p>
                            <p class="wow fadeIn animated">
                                <strong>Email: </strong>{{Settings::get('company_email')}}
                            </p>
                            <p class="wow fadeIn animated">
                                <strong>Hours: </strong>{{Settings::get('business_hours')}}
                            </p>
                            <h5 class="mb-10 mt-30 fw-600 text-grey-4 wow fadeIn animated">Follow Us</h5>
                            <div class="mobile-social-icon wow fadeIn animated mb-sm-5 mb-md-0">
                                <a href="#"><img src="{{ url('frontend/assets/imgs/theme/icons/icon-facebook.svg') }}" alt=""></a>
                                <a href="#"><img src="{{ url('frontend/assets/imgs/theme/icons/icon-instagram.svg') }}" alt=""></a>
                                <a href="#"><img src="{{ url('frontend/assets/imgs/theme/icons/icon-pinterest.svg') }}" alt=""></a>
                                <a href="#"><img src="{{ url('frontend/assets/imgs/theme/icons/icon-youtube.svg') }}" alt=""></a>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-2 col-md-3">
                        <h5 class="widget-title wow fadeIn animated">About</h5>
                        <ul class="footer-list wow fadeIn animated mb-sm-5 mb-md-0">
                            <li><a href="{{ url('/about-us') }}">About Us</a></li>
                            <li><a href="{{ url('/delivery-information') }}">Delivery Information</a></li>
                            <li><a href="{{ url('/faqs') }}">FAQs</a></li>
                            <li><a href="{{ url('/privacy-policy') }}">Privacy Policy</a></li>
                            <li><a href="{{ url('/terms-conditions') }}">Terms &amp; Conditions</a></li>
                            <li><a href="{{ url('/contact-us') }}">Contact Us</a></li>
                        </ul>
                    </div>
                    <div class="col-lg-2  col-md-3">
                        <h5 class="widget-title wow fadeIn animated">My Account</h5>
                        <ul class="footer-list wow fadeIn animated">
                            <li><a href="{{ url('/login') }}">Sign In</a></li>
                            <li><a href="{{ url('/cart') }}">View Cart</a></li>
                            <li><a href="{{ url('/track') }}">Track My Order</a></li>
                            <li><a href="{{ url('/contact-us') }}">Help</a></li>
                            <li><a href="{{ url('/my-orders') }}">My Order</a></li>
                        </ul>
                    </div>
                    <div class="col-lg-4">
                        <h5 class="widget-title wow fadeIn animated">Secured Payment Gateways</h5>
                        <div class="row">
                            
                            <div class="col-md-4 col-lg-12 mt-md-3 mt-lg-0">
                                
                                <img class="wow fadeIn animated" src="{{ url('/frontend/assets/imgs/theme/payment-method.png') }}" alt="">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <div class="container pb-20 wow fadeIn animated">
            <div class="row">
                <div class="col-12 mb-20">
                    <div class="footer-bottom"></div>
                </div>
                <div class="col-lg-6">
                    <p class="float-md-left font-sm text-muted mb-0">&copy; <?php echo date('Y') ?>, <strong class="text-brand">Pinders Schoolwear</strong> - All rights reserved </p>
                </div>
                <div class="col-lg-6">
                    <p class="text-lg-end text-start font-sm text-muted mb-0">
                        Powered by <a href="http://www.aureoleglobal.com" target="_blank">Aureole Global</a>.
                    </p>
                </div>
            </div>
        </div>
    </footer>