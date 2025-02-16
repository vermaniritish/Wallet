<?php
use App\Models\Admin\Settings;
$favicon = Settings::get('favicon');
$logo = Settings::get('logo');
$companyName = Settings::get('company_name');
$googleKey = Settings::get('google_api_key');
$gstTax = Settings::get('gst');
$version = '1.7';
?>
<!doctype html>
<html lang="zxx">

<head>
    <meta charset="utf-8">
    <title>Pinders Work Wear</title>
    <meta name="description" content="Morden Bootstrap HTML5 Template">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" type="image/x-icon" href="{{ url('frontend/assets/img/favicon.ico') }}">

    <!-- ======= All CSS Plugins here ======== -->
    <link rel="stylesheet" href="{{ url('frontend/assets/css/plugins/swiper-bundle.min.css') }}">
    <link rel="stylesheet" href="{{ url('frontend/assets/css/plugins/glightbox.min.css') }}">
    <link href="{{ url('frontend/assets/css/plugins/jquery.fancybox.min.css') }}" rel="stylesheet" type="text/css" />
    <link
        href="{{ url('https://fonts.googleapis.com/css2?family=Jost:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap') }}"
        rel="stylesheet">

    <!-- Plugin css -->
    <link rel="stylesheet" href="{{ url('frontend/assets/css/vendor/bootstrap.min.css') }}">

    <!-- Custom Style CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@splidejs/splide@1.2.0/dist/css/splide.min.css">
    <link rel="stylesheet" href="{{ url('frontend/assets/css/style.css') }}">
    <link rel="stylesheet" href="{{ url('frontend/assets/css/dev.css') }}">
    
    <!-- Vue js -->
    <style>
        ul#brands-block {
            display: flex;
            list-style-type: none;
            margin: 10px 0;
            padding: 0;
        }

        ul#brands-block li:first-child {
            margin-left: 0;
        }

        ul#brands-block li {
            margin: 0 15px;
            margin-left: 15px;
            padding: 0;
            position: relative;
            overflow: hidden;
        }

        ul#brands-block li img {
            width: 100%;
            display: block;
            outline: 2px solid #fff;

            transition: .3s;
        }

        ul#brands-block li a {
            color: #000 !important;
            background: #000;
        }

        ul#brands-block li a:hover {
            color: #ea2c49 !important;
            background: #ea2c49;
        }
    </style>

    <!-- Icons -->
	<link rel="stylesheet" href="<?php echo url('assets/vendor/nucleo/css/nucleo.css') ?>" type="text/css">
	<link rel="stylesheet" href="<?php echo url('assets/vendor/@fortawesome/fontawesome-free/css/all.min.css') ?>" type="text/css">


</head>



<body>
    @include('layouts.partials.preloader')
    @include('layouts.partials.header')
    <main class="main__content_wrapper">
        @yield('content')
        @include('frontend.home.partials.newsLetter')
    </main>
    @include('layouts.partials.shipping')
    @include('layouts.partials.footer')
    @include('layouts.partials.quickView')

    <!-- End News letter popup -->

    <!-- Scroll top bar -->
    <button id="scroll__top"><svg xmlns="http://www.w3.org/2000/svg" class="ionicon" viewBox="0 0 512 512">
            <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                stroke-width="48" d="M112 244l144-144 144 144M256 120v292" />
        </svg></button>
    <!-- Core -->
    <script>
        var site_url = "<?php echo url('/'); ?>";
        var admin_url = "<?php echo url('/admin/'); ?>";
        var current_url = "<?php echo url()->current(); ?>";
        var current_full_url = "<?php echo url()->full(); ?>";
        var previous_url = "<?php echo url()->previous(); ?>";
        var oneTimeProductCost = "<?php echo Settings::get('one_time_setup_cost') ?>";
        var freeLogoDiscount = <?php $d = Settings::get('free_logo_discount'); echo $d ? $d : 'null' ?>;
        var freeDelivery = <?php $d = Settings::get('free_delivery'); echo $d ? $d : 'null' ?>;
        var csrf_token = function() {
            return "<?php echo csrf_token(); ?>";
        }
        var sleep = function (ms) {
            return new Promise(resolve => setTimeout(resolve, ms));
        }
        var gstTax = function() {
            return "<?php echo $gstTax ?>";
        }
    </script>
    <!-- All Script JS Plugins here  -->
    <script src="{{ url('frontend/assets/js/vendor/popper.js') }}" defer="defer')}}"></script>
    <!-- jQuery -->
    <script src="{{ url('frontend/assets/js/plugins/jquery-3.6.0.min.js') }}"></script>
	<script src="{{ url('frontend/assets/js/vendor/jquery.validate.min.js') }}"></script>
	<script src="{{ url('frontend/assets/js/vendor/vue.js') }}"></script>
    <script src="<?php echo url('assets/vendor/jquery-scroll-lock/dist/jquery-scrollLock.min.js') ?>"></script>
    <script src="{{ url('frontend/assets/js/vendor/bootstrap.min.js') }}"></script>
    
    <script src="https://cdn.jsdelivr.net/npm/@splidejs/splide@1.2.0/dist/js/splide.min.js"></script>
    <script src="{{ url('frontend/assets/js/plugins/swiper-bundle.min.js') }}"></script>
    <script src="{{ url('frontend/assets/js/plugins/glightbox.min.js') }}"></script>
    <script src="{{ url('frontend/assets/js/plugins/jquery.fancybox.min.js') }}"></script>
	<script src="<?php echo url('assets/js/bootstrap-notify.js') ?>"></script>
    <!-- Customscript js -->
    <script src="<?php echo url('assets/js/jquery.form.min.js') ?>"></script>
    <script src="{{ url('frontend/assets/js/script.js') }}"></script>
    <script src="<?php echo url('assets/js/auth.js') ?>"></script>
    <script src="{{ url('frontend/assets/js/product-listing.js') }}"></script>
    <script src="https://www.paypal.com/sdk/js?client-id=AcHH3SOCSzLpPXioPVl5m15be29_PrHru6j0v6aVpcQdx9padzQ3EXfXVPkBStFVx-7FZZ4ZJzDNKxKh&currency=GBP"></script>
    <script>
        paypal.Buttons({
            createOrder: async function(data, actions) {
                let response = await checkoutPage.submit();
                if(response && response.status && response.orderId)
                {
                    return fetch('{{url("/paypal/create-order")}}', {
                        method: 'post',
                        headers: {
                            'content-type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}' // Add CSRF token for Laravel
                        },
                        body: JSON.stringify({
                            amount: response.amount,
                            id: response.orderId
                        })
                    }).then(function(res) {
                        return res.json();
                    }).then(function(orderData) {
                        return orderData && orderData.result ? orderData.result.id : null; // Use the key returned from your server to set up the transaction
                    });
                }

                return Promise.reject(new Error('API request failed'));
            },
            onApprove: function(data, actions) {
                return fetch('{{ url("/paypal/capture-order")}}', {
                    method: 'post',
                    headers: {
                        'content-type': 'application/json'
                    },
                    body: JSON.stringify({
                        _token: csrf_token(),
                        orderId: data.orderID
                    })
                }).then(function(res) {
                    return res.json();
                }).then(function(details) {
                    if(details && details.status && details.id)
                    {
                        localStorage.removeItem('orderId');
                        localStorage.removeItem('cart');
                        localStorage.removeItem('coupon');
                        localStorage.removeItem('checkout');
                        window.location.href = site_url + "/paypal/success?id=" + details.id;
                    }
                    else
                        window.location.href = site_url + "/paypal/error";
                });
            }
        }).render('#paypal-button-container');
    </script>
    <?php 
    $action = get_controller_action(request()->route()->getAction()['controller']);
    if($action == 'home/index') {
        echo '<script>newsletterPopup();</script>';
    }
    ?>

    
    
</body>

</html>
