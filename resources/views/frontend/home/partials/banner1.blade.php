<?php use App\Models\Admin\HomePage; ?>
<!-- Start banner section -->
    <section class="banner__section section--padding">
        <div class="container-fluid">
            <div class="row mb--n28">
                <div class="col-lg-5 col-md-order mb-28">
                    <?php $url = HomePage::get('banner_1_button_url');
                    $image = HomePage::get('banner_1_image'); 
                    if($image):
                    ?>
                        <div class="banner__items">
                            
                            <a class="banner__items--thumbnail position__relative" href="{{ $url }}"><img class="banner__items--thumbnail__img" src="{{ $image ? url($image) : ''}}" alt="banner-img">
                                <div class="banner__items--content">
                                    <?php $label = HomePage::get('banner_1_label'); ?>
                                    @if($label)
                                    <span class="banner__items--content__subtitle">{{ $label }}</span>
                                    @endif
                                    <?php $heading = HomePage::get('banner_1_heading'); ?>
                                    @if($heading)
                                    <h2 class="banner__items--content__title h3"><?php echo nl2br($heading) ?></h2>
                                    @endif
                                    @if(HomePage::get('banner_1_button_status'))
                                    <span class="banner__items--content__link">{{ HomePage::get('banner_1_button_title') }}
                                        <svg class="banner__items--content__arrow--icon" xmlns="http://www.w3.org/2000/svg" width="20.2" height="12.2" viewBox="0 0 6.2 6.2">
                                            <path d="M7.1,4l-.546.546L8.716,6.713H4v.775H8.716L6.554,9.654,7.1,10.2,9.233,8.067,10.2,7.1Z" transform="translate(-4 -4)" fill="currentColor"></path>
                                        </svg>
                                    </span>
                                    @endif
                                </div>
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="col-lg-7 mb-28">
                    <div class="row row-cols-lg-2 row-cols-sm-2 row-cols-1">
                        <?php $url = HomePage::get('banner_2_button_url');
                        $image = HomePage::get('banner_2_image'); ?>
                        @if($image)
                        <div class="col mb-28">
                            <div class="banner__items">
                                
                                <a class="banner__items--thumbnail position__relative" href="{{ $url }}"><img class="banner__items--thumbnail__img" src="{{ $image ? url($image) : '' }}" alt="banner-img"> 
                                    <div class="banner__items--content">
                                        <?php $label = HomePage::get('banner_2_label'); ?>
                                        @if($label)
                                        <span class="banner__items--content__subtitle text__secondary">{{ $label }}</span>
                                        @endif
                                        <?php $heading = HomePage::get('banner_2_heading'); ?>
                                        @if($heading)
                                        <h2 class="banner__items--content__title h3"><?php echo nl2br($heading) ?></h2>
                                        @endif
                                        @if(HomePage::get('banner_2_button_status'))
                                        <span class="banner__items--content__link">{{ HomePage::get('banner_2_button_title') }}
                                            <svg class="banner__items--content__arrow--icon" xmlns="http://www.w3.org/2000/svg" width="20.2" height="12.2" viewBox="0 0 6.2 6.2">
                                                <path d="M7.1,4l-.546.546L8.716,6.713H4v.775H8.716L6.554,9.654,7.1,10.2,9.233,8.067,10.2,7.1Z" transform="translate(-4 -4)" fill="currentColor"></path>
                                            </svg>
                                        </span>
                                        @endif
                                    </div>
                                </a>
                            </div>
                        </div>
                        @endif
                        <?php $url = HomePage::get('banner_3_button_url');
                        $image = HomePage::get('banner_3_image'); ?>
                        @if($image)
                        <div class="col mb-28">
                            <div class="banner__items">
                                <a class="banner__items--thumbnail position__relative" href="{{ $url }}"><img class="banner__items--thumbnail__img" src="{{ $image ? url($image) : '' }}" alt="banner-img"> 
                                    <div class="banner__items--content">
                                        <?php $label = HomePage::get('banner_3_label'); ?>
                                        @if($label)
                                        <span class="banner__items--content__subtitle text__secondary">{{ $label }}</span>
                                        @endif
                                        <?php $heading = HomePage::get('banner_3_heading'); ?>
                                        @if($heading)
                                        <h2 class="banner__items--content__title h3"><?php echo nl2br($heading) ?></h2>
                                        @endif
                                        @if(HomePage::get('banner_3_button_status'))
                                        <span class="banner__items--content__link">{{ HomePage::get('banner_3_button_title') }}
                                            <svg class="banner__items--content__arrow--icon" xmlns="http://www.w3.org/2000/svg" width="20.2" height="12.2" viewBox="0 0 6.2 6.2">
                                                <path d="M7.1,4l-.546.546L8.716,6.713H4v.775H8.716L6.554,9.654,7.1,10.2,9.233,8.067,10.2,7.1Z" transform="translate(-4 -4)" fill="currentColor"></path>
                                            </svg>
                                        </span>
                                        @endif
                                    </div>
                                </a>
                            </div>
                        </div>
                        @endif
                    </div>
                    <?php $url = HomePage::get('banner_4_button_url');
                        $image = HomePage::get('banner_4_image'); ?>
                    @if($image)
                    <div class="banner__items">
                        <a class="banner__items--thumbnail position__relative" href="{{ $url }}"><img class="banner__items--thumbnail__img banner__img--max__height" src="{{ $image ? url($image) : ''}}" alt="banner-img"> 
                            <div class="banner__items--content">
                                <span class="banner__items--content__subtitle"></span>
                                <h2 class="banner__items--content__title h3"></h2>
                                <span class="banner__items--content__link">
                                </span>
                            </div>
                        </a>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </section>
<!-- End banner section -->