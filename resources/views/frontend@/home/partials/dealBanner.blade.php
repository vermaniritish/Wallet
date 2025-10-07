<?php use App\Models\Admin\HomePage; ?>
<!-- Start deals banner section -->
 @if(HomePage::get('deal_day_enable'))
    <section class="deals__banner--section section--padding pt-0">
        <div class="container-fluid">
                <?php $image = HomePage::get('deal_day_image');?>
                @if($image)
                <div class="deals__banner--inner" style="background: url({{url($image)}})">
                @else
                <div class="deals__banner--inner banner__bg">
                @endif
                <div class="row row-cols-1 align-items-center">
                    <div class="col">
                        <div class="deals__banner--content position__relative">
                            <span class="deals__banner--content__subtitle text__secondary"><?php echo HomePage::get('deal_day_label') ?></span>
                            <h2 class="deals__banner--content__maintitle"><?php echo nl2br(HomePage::get('deal_day_heading')) ?></h2>
                            <p class="deals__banner--content__desc"><?php echo nl2br(HomePage::get('deal_day_subheading')) ?></p>
                            <div class="deals__banner--countdown d-flex" data-countdown="Sep 30, 2022 00:00:00"></div>
                            @if(HomePage::get('deal_day_button_status'))
                            <a class="primary__btn" href="<?php echo HomePage::get('deal_day_button_url') ?>"><?php echo HomePage::get('deal_day_button_title') ?>
                                <svg class="primary__btn--arrow__icon" xmlns="http://www.w3.org/2000/svg" width="20.2" height="12.2" viewBox="0 0 6.2 6.2">
                                <path d="M7.1,4l-.546.546L8.716,6.713H4v.775H8.716L6.554,9.654,7.1,10.2,9.233,8.067,10.2,7.1Z" transform="translate(-4 -4)" fill="currentColor"></path>
                                </svg>
                            </a>
                            @endif
                            <br>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endif
<!-- End deals banner section -->