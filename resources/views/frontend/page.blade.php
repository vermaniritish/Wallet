@extends('layouts.frontendlayout')
@section('content')

<div class="page-header breadcrumb-wrap">
        <div class="container">
            <div class="breadcrumb">
                <a href="{{ url('/') }}" rel="nofollow">Home</a>
                <span></span> {{$page->title}}
            </div>
        </div>
    </div>
    <section class="mt-50 mb-50">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="single-page pr-30">
                        <div class="single-header style-2">
                            <h2><?php echo $page->title ?></h2>
                        </div>
                        <div class="single-content">
                            <?php echo $page->description ?>
                        </div>
                    </div>
                </div>
                
            </div>
        </div>
    </section>
@endsection