@extends('layouts.frontendlayout')
@section('content')
<div class="page-header breadcrumb-wrap">
    <div class="container">
        <div class="breadcrumb">
            <a href="{{ url('/') }}" rel="nofollow">Home</a>
            <span></span> Hi! {{$user->first_name}} {{$user->last_name}}
        </div>
    </div>
</div>
<section class="pt-50 pb-150">
    <div class="container">
        <div class="row">
            <div class="col-lg-10 m-auto">
                <div class="row">
                    <div class="col-md-4">
                        @include('frontend.account.aside')
                    </div>
                    <div class="col-md-8">
                        <div class="dashboard-content">
                            @if(isset($screen) && $screen == 'dashboard')
                                @include('frontend.account.dashboard', ['screen' => 'dashboard', 'user' => $user])
                            @endif
                            @if(isset($screen) && $screen == 'orders')
                                @include('frontend.account.orders', ['screen' => 'orders'])
                            @endif
                            @if(isset($screen) && $screen == 'track-order')
                                @include('frontend.account.trackorder', ['screen' => 'track-order'])
                            @endif
                            @if(isset($screen) && $screen == 'address')
                                @include('frontend.account.address', ['screen' => 'address'])
                            @endif
                            @if(isset($screen) && $screen == 'account')
                                @include('frontend.account.account', ['screen' => 'account'])
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
