@extends('layouts.frontendlayout')
@section('content')

<div class="page-header breadcrumb-wrap">
        <div class="container">
            <div class="breadcrumb">
                <a href="{{ url('/') }}" rel="nofollow">Home</a>
                <span></span> Verification
            </div>
        </div>
    </div>
    <section class="mt-50 mb-50">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="single-page pr-30">
                        <div class="single-header style-2">
                            <h2>Account Verification</h2>
                        </div>
                        <div class="single-content">
                            <p class="text-success text-center mb-1" style="font-size: 100px;"><i class="far fa-check-circle"></i></p>
                            <p class="text-center mb-1">Your account is verified. Please login to continue</p>

                            <p class="text-center mt-4"><a href="{{url('/login')}}" target="_blank" class="btn btn-primary" >Login</a></p>
                        </div>
                    </div>
                </div>
                
            </div>
        </div>
    </section>
@endsection