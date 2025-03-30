<?php use App\Models\Admin\Pages; 
$page = Pages::where('slug', 'LIKE', 'newsletter')->where('status', 1)->limit(1)->first();
if($page):
?>
<!-- Start News letter popup -->
    <div class="newsletter__popup" data-animation="slideInUp">
        <div id="boxes" class="newsletter__popup--inner">
            <button class="newsletter__popup--close__btn" aria-label="search close button">
                <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 512 512"><path fill="currentColor" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="32" d="M368 368L144 144M368 144L144 368"></path></svg>
            </button>
            <div class="box newsletter__popup--box d-flex align-items-center">
                <div class="newsletter__popup--thumbnail">
                    <img class="newsletter__popup--thumbnail__img display-block" src="{{$page->image && $page->getResizeImagesAttribute()['large'] ? url($page->getResizeImagesAttribute()['large']) : url('frontend/assets/img/banner/newsletter-popup-thumb2.png')}}" alt="newsletter-popup-thumb">
                </div>
                <div class="newsletter__popup--box__right">
                    <h2 class="newsletter__popup--title">{{ $page->title }}</h2>
                    <div class="newsletter__popup--content">
                        <label class="newsletter__popup--content--desc">{!! $page->description !!}</label>
                        <div class="newsletter__popup--subscribe" id="frm_subscribe">
                            <form class="newsletter__popup--subscribe__form">
                                <input class="newsletter__popup--subscribe__input mb-3" type="text" placeholder="Enter you email address here...">
                                <p class="d-none newsletter-error text-danger m-0 p-0"></p>
                                <button type="submit" class="newsletter__popup--subscribe__btn mt-0">Subscribe</button>
                            </form>
                            <div class="newsletter__popup--footer">
                                <input type="checkbox" id="newsletter__dont--show">
                                <label class="newsletter__popup--dontshow__again--text" for="newsletter__dont--show">Don't show this popup again</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<!-- End News letter popup -->
<?php endif; ?>