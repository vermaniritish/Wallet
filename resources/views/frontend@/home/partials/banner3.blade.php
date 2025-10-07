<?php use App\Models\Admin\HomePage; ?>
@if(HomePage::get('bottom_banner_enable'))
    <section class="banner__section section--padding pt-0">
        <div class="container-fluid">
            <div class="row row-cols-1">
                <div class="col">
                    <div class="banner__section--inner position__relative">
                        <?php $url = HomePage::get('bottom_banner_button_url');
                        $image = HomePage::get('bottom_banner_image'); ?>
                        <a class="banner__items--thumbnail display-block" href="{{ $url }}"><img class="banner__items--thumbnail__img banner__img--height__md display-block" src="{{$image ? url($image) : url('frontend/assets/img/banner/banner-bg2.png') }}" alt="banner-img">
                            <div class="banner__content--style2">
                                <?php $label = HomePage::get('bottom_banner_label'); ?>
                                @if($label)
                                <h2 class="banner__content--style2__title text-white">{{$label}}</h2>
                                @endif
                                <?php $heading = HomePage::get('bottom_banner_heading'); ?>
                                @if($heading)
                                <p class="banner__content--style2__desc"><?php echo nl2br($heading) ?></p>
                                @endif
                                @if(HomePage::get('bottom_banner_button_status'))
                                <span class="primary__btn">{{ HomePage::get('bottom_banner_button_title') }}
                                    <svg class="primary__btn--arrow__icon" xmlns="http://www.w3.org/2000/svg" width="20.2" height="12.2" viewBox="0 0 6.2 6.2">
                                    <path d="M7.1,4l-.546.546L8.716,6.713H4v.775H8.716L6.554,9.654,7.1,10.2,9.233,8.067,10.2,7.1Z" transform="translate(-4 -4)" fill="currentColor"></path>
                                    </svg>
                                </span>
                                @endif
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endif