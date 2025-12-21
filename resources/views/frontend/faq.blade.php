@extends('layouts.frontendlayout')
@section('content')
<div class="page-header breadcrumb-wrap">
            <div class="container">
                <div class="breadcrumb">
                    <a href="{{ url('/') }}" rel="nofollow">Home</a>
                    <span></span> FAQs
                </div>
            </div>
        </div>
        <section class="mt-50 mb-50">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="single-page pr-30">
                            <div class="single-header style-2">
                                <h2>FAQ's</h2>
                            </div>
                            <div class="single-content">
                                <div class="container mt-5">
									<div class="row justify-content-center">
										<div class="col-md-10 col-lg-8">
                                            @foreach($faqs as $k => $f)
											<div class="accordion" id="faqAccordion{{$k}}">
												<div class="accordion-item mb-3 shadow-sm">
													<h2 class="accordion-header" id="headingOne">
														<button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne{{$k}}" aria-expanded="true" aria-controls="collapseOne{{$k}}">
															<i class="bi bi-question-circle me-2"></i> {{$f->title}}
														</button>
													</h2>
													<div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#faqAccordion{{$k}}">
														<div class="accordion-body">
															<?php echo $f->description ?>
														</div>
													</div>
												</div>
											</div>
                                            @endforeach
										</div>
									</div>
								</div>
                            </div>
                        </div>
                    </div>
                    
                </div>
            </div>
        </section>
@endsection