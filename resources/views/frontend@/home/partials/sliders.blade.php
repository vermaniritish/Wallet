<section class="hero__slider--section" id="home-page">
    <div class="hero__slider--inner hero__slider--activation swiper">
        <div class="hero__slider--wrapper swiper-wrapper">
            <?php foreach($sliders as $s): ?>
            <div class="swiper-slide ">
                @if($s->image)
                <div class="hero__slider--items home1__slider--bg two" style="background-image: url({{ url($s->image) }})">
                @else
                <div class="hero__slider--items home1__slider--bg two">
                @endif
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col">
                                <div class="hero__slider--items__inner">
                                    <div class="slider__content">
                                        <p class="slider__content--desc desc1 mb-15"><img class="slider__text--shape__icon" src="{{url('frontend/assets/img/icon/text-shape-icon.png')}}" alt="text-shape-icon"> {{$s->label}}</p> 
                                        <h2 class="slider__content--maintitle h1"><?php echo nl2br($s->heading) ?></h2>
                                        <p class="slider__content--desc desc2 d-sm-2-none mb-40 "><?php echo nl2br($s->sub_heading) ?></p>    
                                        <?php if($s->button_status): ?>
                                        <a class="primary__btn slider__btn" href="<?php echo $s->button_url ?>"><?php echo $s->button_title ?>
                                            <svg class="slider__btn--arrow__icon" xmlns="http://www.w3.org/2000/svg" width="20.2" height="12.2" viewBox="0 0 6.2 6.2">
                                            <path d="M7.1,4l-.546.546L8.716,6.713H4v.775H8.716L6.554,9.654,7.1,10.2,9.233,8.067,10.2,7.1Z" transform="translate(-4 -4)" fill="currentColor"></path>
                                            </svg>
                                        </a>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <div class="swiper__nav--btn swiper-button-next"></div>
        <div class="swiper__nav--btn swiper-button-prev"></div>
    </div>
</section>
<!-- End slider section -->