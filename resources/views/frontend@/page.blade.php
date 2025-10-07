@extends('layouts.frontendlayout')
@section('content')
<section class="breadcrumb__section" style=" background: url({{ url('assets/img/other/bg-shape1.png')}});background-size: cover;border-bottom: 1px solid #e7e7e7;">
            <div class="container">
                <div class="row row-cols-1">
                    <div class="col">
                        <div class="breadcrumb__content text-center">
                            <ul class="breadcrumb__content--menu d-flex justify-content-center">
                                <li class="breadcrumb__content--menu__items mb-25 mt-15"><a href="/">Home</a></li>
								<li class="breadcrumb__content--menu__items mb-25 mt-15">{{ $page->title }}</li>
                            </ul>
							<h2 class="breadcrumb__content--title mb-15">{{ $page->title }}</h2>
							
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- End breadcrumb section -->

        <!-- Start privacy policy section -->
        <div class="privacy__policy--section section--padding">
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <div class="privacy__policy--content section_2">
                            <?php echo $page->description ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- End privacy policy section -->
@endsection