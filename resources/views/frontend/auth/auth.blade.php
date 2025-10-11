@extends('layouts.frontendlayout')
@section('content')
<div class="page-header breadcrumb-wrap">
        <div class="container">
            <div class="breadcrumb">
                <a href="{{ url('/') }}" rel="nofollow">Home</a>
                <span></span> Login
            </div>
        </div>
    </div>
    <section class="mt-50 mb-50">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div id="auth" class="row">
                        @include('frontend.auth.login')
                        @include('frontend.auth.forgotPassword')
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
@push('scripts')
<script>var redirectUrl = "{{ $redirect }}" ; </script>
@endpush