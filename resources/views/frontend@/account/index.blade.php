@extends('layouts.frontendlayout')
@section('content')
<?php $user = request()->session()->get('user') ?>
    <section class="my__account--section section--padding">
        <div class="container">
            @include('frontend.account.name')
            <div class="my__account--section__inner border-radius-10 d-flex">
                <div class="account__left--sidebar">
                    <h2 class="account__content--title h3 mb-20">My Profile</h2>
                    @include('frontend.account.aside')
                </div>
                <div class="account__wrapper">
                    <div class="account__content">
                        <h3 class="account__content--title mb-20">My Details</h3>
                        <button class="new__address--btn primary__btn mb-25" type="button">Add a new address</button>
                        <div class="account__details two">
                            <h4 class="account__details--title">{{$user->first_name . ' ' . $user->last_name}}</h4>
                            <p class="account__details--desc">{{$user->email}}
                            @if($user->address)
                            <br>{{$user->address}} 
                            @endif
                            @if($user->address2)
                            <br> {{$user->address2}} 
                            @endif
                            @if($user->city || $user->postcode)
                            <br> {{$user->city}} {{$user->postcode ? ', ' . $user->postcode : ''}}
                            @endif
                            @if($user->country)
                            <br>{{$user->country}}
                            @endif
                            </p>
                            
                        </div>
                        <div class="account__details--footer d-flex">
                            <a href="{{ url('/edit-account') }}" class="account__details--footer__btn">Edit</a>
                            {{-- <button class="account__details--footer__btn" type="button">Delete</button> --}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection