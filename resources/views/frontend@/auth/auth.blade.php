@extends('layouts.frontendlayout')
@section('content')
{{-- <?php require_once 'header-top.php'; ?>
<?php require_once 'header-bottom.php'; ?> --}}
    <!-- Start login section  -->
        <div class="login__section section--padding">
            <div class="container">
                <div class="login__section--inner">
                    <div id="auth" class="row row-cols-md-2 row-cols-1">
                        @include('frontend.auth.login')		
                        @include('frontend.auth.forgotPassword')		
                        @include('frontend.auth.register')		
                    </div>
                </div>
            </div>     
        </div>
    <!-- End login section  -->
@endsection