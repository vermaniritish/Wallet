<?php
use App\Models\Admin\Settings;
$favicon = Settings::get('favicon');
$logo = Settings::get('logo');
$companyName = Settings::get('company_name');
$googleKey = Settings::get('google_api_key');
$gstTax = Settings::get('gst');
$version = '2.4';
?>
<!DOCTYPE html>
<html class="no-js" lang="en">
<head>
    <meta charset="utf-8">
    <title>Pinders Schoolwear</title>
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta property="og:title" content="">
    <meta property="og:type" content="">
    <meta property="og:url" content="">
    <meta property="og:image" content="">
    <!-- Favicon -->
    <link rel="shortcut icon" type="image/x-icon" href="{{ url('frontend/assets/img/favicon.ico') }}">
    <!-- Template CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.9.0/css/all.min.css" integrity="sha512-q3eWabyZPc1XTCmF+8/LuE1ozpg5xxn7iO89yfSOd5/oKvyqLngoNGsx8jq92Y8eXJ/IRxQbEC+FGSYxtk2oiw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="{{ url('frontend/assets/css/main.css?v=' . $version) }}">
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
    <script src="https://www.paypal.com/sdk/js?client-id=AYXlDi-lHWz99toobrKV0fzLCeQanEV5z4UnJB1fsSZN6r-xzqEacYY4KnESQuEyVgu-ARJ47y_YEkKb&currency=GBP"></script>
</head>

<body>
    <!-- Quick view -->
    
    @include('layouts.partials.preloader')
    @include('layouts.partials.header')
    <main class="main d-none">
        @yield('content')
    </main>
    @include('layouts.partials.footer')
     <!-- Vendor JS-->

     <script>
        var site_url = "<?php echo url('/'); ?>";
        var admin_url = "<?php echo url('/admin/'); ?>";
        var current_url = "<?php echo url()->current(); ?>";
        var current_full_url = "<?php echo url()->full(); ?>";
        var previous_url = "<?php echo url()->previous(); ?>";
        var oneTimeLogoCost = "<?php echo Settings::get('one_time_setup_cost') ?>";
        var oneTimeLogoTxtCost = "<?php echo Settings::get('one_time_setup_cost_text') ?>";
        var freeLogoDiscount = <?php $d = Settings::get('free_logo_discount'); echo $d ? $d : 'null' ?>;
        var freeDelivery = null;
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
    @stack('scripts')
    <script src="https://cdn.jsdelivr.net/npm/vue@2.7.16"></script>
    
    <script src="{{ url('frontend/assets/js/vendor/modernizr-3.6.0.min.js') }}"></script>
    <script src="{{ url('frontend/assets/js/vendor/jquery-3.6.0.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.3/dist/jquery.validate.min.js"></script>
    <script src="<?php echo url('assets/js/jquery.form.min.js') ?>"></script>
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>
    <script src="<?php echo url('assets/js/bootstrap-notify.js') ?>"></script>
    <script src="{{ url('frontend/assets/js/vendor/jquery-migrate-3.3.0.min.js') }}"></script>
    <script src="{{ url('frontend/assets/js/vendor/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ url('frontend/assets/js/plugins/slick.js') }}"></script>
    <script src="{{ url('frontend/assets/js/plugins/jquery.syotimer.min.js') }}"></script>
    <script src="{{ url('frontend/assets/js/plugins/wow.js') }}"></script>
    <script src="{{ url('frontend/assets/js/plugins/jquery-ui.js') }}"></script>
    <script src="{{ url('frontend/assets/js/plugins/perfect-scrollbar.js') }}"></script>
    <script src="{{ url('frontend/assets/js/plugins/magnific-popup.js') }}"></script>
    <script src="{{ url('frontend/assets/js/plugins/select2.min.js') }}"></script>
    <script src="{{ url('frontend/assets/js/plugins/waypoints.js') }}"></script>
    <script src="{{ url('frontend/assets/js/plugins/counterup.js') }}"></script>
    <script src="{{ url('frontend/assets/js/plugins/jquery.countdown.min.js') }}"></script>
    <script src="{{ url('frontend/assets/js/plugins/images-loaded.js') }}"></script>
    <script src="{{ url('frontend/assets/js/plugins/isotope.js') }}"></script>
    <script src="{{ url('frontend/assets/js/plugins/scrollup.js') }}"></script>
    <script src="{{ url('frontend/assets/js/plugins/jquery.vticker-min.js') }}"></script>
    <script src="{{ url('frontend/assets/js/plugins/jquery.theia.sticky.js') }}"></script>
    <script src="{{ url('frontend/assets/js/plugins/jquery.elevatezoom.js') }}"></script>
    <script src="{{ url('frontend/assets/js/main.js?v='.$version) }}"></script>
    <script src="{{ url('frontend/assets/js/shop.js?v='.$version) }}"></script>
    <script src="{{ url('frontend/assets/js/auth.js?v='.$version) }}"></script>
    <script src="{{ url('frontend/assets/js/product-listing.js?v='.$version) }}"></script>

</body>

</html>