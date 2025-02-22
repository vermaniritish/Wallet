<?php use App\Models\Admin\HomePage; ?>
@if(HomePage::get('left_right_grid_enable'))
    <section class="banner__section section--padding pt-0">
        <div class="container-fluid">
            <div class="row row-cols-md-2 row-cols-1 mb--n28">
                <div class="col mb-28">
                    <div class="banner__items position__relative">
                        <?php $url = HomePage::get('left_grid_button_url');
                        $image = HomePage::get('left_grid_image'); ?>
                        <a class="banner__items--thumbnail " href="{{ $url }}"><img class="banner__items--thumbnail__img banner__img--max__height" src="{{$image ? url($image) : url('frontend/assets/img/banner/banner5.png')}}" alt="banner-img">
                            <div class="banner__items--content">
                                <?php $label = HomePage::get('left_grid_label'); ?>
                                @if($label)
                                <span class="banner__items--content__subtitle d-none d-lg-block">{{ $label }}</span>
                                @endif
                                <?php $heading = HomePage::get('left_grid_heading'); ?>
                                @if($heading)
                                <h2 class="banner__items--content__title h3"><?php echo nl2br($heading) ?></h2>
                                @endif
                                @if(HomePage::get('left_grid_button_status'))
                                <span class="banner__items--content__link">{{ HomePage::get('left_grid_button_title') }}
                                    <svg class="banner__items--content__arrow--icon" xmlns="http://www.w3.org/2000/svg" width="20.2" height="12.2" viewBox="0 0 6.2 6.2">
                                        <path d="M7.1,4l-.546.546L8.716,6.713H4v.775H8.716L6.554,9.654,7.1,10.2,9.233,8.067,10.2,7.1Z" transform="translate(-4 -4)" fill="currentColor"></path>
                                    </svg>
                                </span>
                                @endif
                            </div>
                        </a>
                    </div>
                </div>
                <div class="col mb-28">
                    <div class="banner__items position__relative">
                        <?php $url = HomePage::get('right_grid_button_url');
                        $image = HomePage::get('right_grid_image'); ?>
                        <a class="banner__items--thumbnail " href="/"><img class="banner__items--thumbnail__img banner__img--max__height" src="{{$image ? url($image) : url('frontend/assets/img/banner/banner6.png') }}" alt="banner-img">
                            <div class="banner__items--content">
                                <?php $label = HomePage::get('right_grid_label'); ?>
                                @if($label)
                                <span class="banner__items--content__subtitle d-none d-lg-block">{{ $label }}</span>
                                @endif
                                <?php $heading = HomePage::get('right_grid_heading'); ?>
                                @if($heading)
                                <h2 class="banner__items--content__title h3"><?php echo nl2br($heading) ?></h2>
                                @endif
                                @if(HomePage::get('right_grid_button_status'))
                                <span class="banner__items--content__link">{{ HomePage::get('right_grid_button_title') }}
                                    <svg class="banner__items--content__arrow--icon" xmlns="http://www.w3.org/2000/svg" width="20.2" height="12.2" viewBox="0 0 6.2 6.2">
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