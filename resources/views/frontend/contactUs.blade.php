<?php 
use App\Models\Admin\Settings;
use App\Libraries\General; ?>
@extends('layouts.frontendlayout')
@section('content')
<div class="page-header breadcrumb-wrap">
    <div class="container">
        <div class="breadcrumb">
            <a href="{{ url('/') }}" rel="nofollow">Home</a>
            <span></span> Contact us
        </div>
    </div>
</div>  
<section class="section-border pt-50 pb-50">
    <div class="container">
        
        <div class="row">
            @foreach($shops as $s)
            <div class="col-md-4 mb-4 mb-md-0">
                <h4 class="mb-15 text-brand">{{ $s->name }}</h4>
                <?php echo nl2br($s->address) ?>
                <br />
                <a href="https://www.google.com/maps?q={{ $s->lat }},{{ $s->lng }}" target="_blank" class="btn btn-outline btn-sm btn-brand-outline font-weight-bold text-brand bg-white text-hover-white mt-20 border-radius-5 btn-shadow-brand hover-up"><i class="fi-rs-marker mr-10"></i>View map</a>
            </div>
            @endforeach
        </div>
    </div>
</section>
<section class="pt-50 pb-50">
    <div class="container">
        <div class="row">
            <div class="col-md-6 mb-6 mb-md-0" style="text-align:center;"><h5>Phone: <a href="tel:{{Settings::get('hotline_number')}}">{{Settings::get('hotline_number')}}</a></h5></div>
            <div class="col-md-6 mb-6 mb-md-0" style="text-align:center;"><h5>Email: <a href="mailto:{{Settings::get('company_email')}}">{{Settings::get('company_email')}}</a></h5></div>
            <div class="col-xl-8 col-lg-10 m-auto">
                @if(Session::has('success'))
                <div class="flash-message mb-4">
                    <p class="alert alert-success">
                        {{ Session::get('success') }}
                    </p>
                    {{ Session::forget('success') }}
                </div>
                @endif
                @if(Session::has('error'))
                <div class="flash-message mb-4">
                    <p class="alert alert-danger">
                        {{ Session::get('error') }}
                    </p>
                    {{ Session::forget('error') }}
                </div>
                @endif
                
                <div class="contact-from-area padding-20-row-col wow FadeInUp">
                    <h3 class="mb-10 text-center">Drop Us a Line</h3>
                    <p class="text-muted mb-30 text-center font-sm">Fill out the form and our team will get back to you</p>
                    <form class="contact-form-style text-center" id="contact-form" action="<?php echo route('contactUs') ?>" method="post">
                        {{ @csrf_field() }}
                        <div class="row">
                            <div class="col-lg-6 col-md-6">
                                <div class="input-style mb-20">
                                    <input name="firstname" placeholder="Name" type="text" required>
                                    @error('firstname')<small class="text-danger">{{ $message }}</small>@enderror
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6">
                                <div class="input-style mb-20">
                                    <input name="email" placeholder="Your Email" type="email" required>
                                    @error('email')<small class="text-danger">{{ $message }}</small>@enderror
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6">
                                <div class="input-style mb-20">
                                    <input  name="number" placeholder="Your Phone" type="tel" required
                                        maxlength="15"
                                        onkeyup="this.value=this.value.replace(/[^0-9]/g,'')"
                                    >
                                    @error('number')<small class="text-danger">{{ $message }}</small>@enderror
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6">
                                <div class="input-style mb-20">
                                    <input name="subject" placeholder="Subject" type="text" maxlength="200">
                                    @error('subject')<small class="text-danger">{{ $message }}</small>@enderror
                                </div>
                            </div>
                            <div class="col-lg-12 col-md-12">
                                <div class="textarea-style mb-30">
                                    <textarea name="message" placeholder="Message"></textarea>
                                    @error('message')<small class="text-danger">{{ $message }}</small>@enderror
                                </div>
                                <button class="submit submit-auto-width" type="submit">Send message</button>
                            </div>
                        </div>
                    </form>
                    <p class="form-messege"></p>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection