@extends('layouts.frontendlayout')
@section('content')
<div id="product-listing-vue">
    <pre class="d-none" id="careoryId">{{ isset($category) && $category ? $category->id : '' }}</pre>
        <div class="offcanvas__filter--sidebar widget__area">
            <button type="button" class="offcanvas__filter--close" data-offcanvas>
                <svg class="minicart__close--icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path fill="currentColor" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="32" d="M368 368L144 144M368 144L144 368"></path></svg> <span class="offcanvas__filter--close__text">Close</span>
            </button>
            <div class="offcanvas__filter--sidebar__inner">
                @include('frontend.partials.sidebar-productlist-mobile')
                
            </div>
        </div>     
        <!-- Start breadcrumb section -->
        <section class="breadcrumb__section" style=" background: url({{ url('assets/img/other/bg-shape1.png')}});background-size: cover;border-bottom: 1px solid #e7e7e7;">
            <div class="container">
                <div class="row row-cols-1">
                    <div class="col">
                        <div class="breadcrumb__content text-center" v-if="searchPage && search">
                            <br />
                            <h2 class="breadcrumb__content--title mb-15"><span style="font-weight: 400;">
                                Search Results for:</span> @{{search}}
                            </h2>                           
                        </div>
                        <div class="breadcrumb__content text-center" v-else>
                            <ul class="breadcrumb__content--menu d-flex justify-content-center">
                                <li class="breadcrumb__content--menu__items mb-25 mt-15"><a href="{{url('/')}}">Home</a></li>
                                @if($category && $category->slug)
								<li class="breadcrumb__content--menu__items mb-25 mt-15"><a href="{{url('/' . ($category && $category->slug ? $category->slug : ''))}}">{{ $category && $category->title ? $category->title : '' }}</a></li>
                                <?php if($subCategory): ?>
                                <li class="breadcrumb__content--menu__items mb-25 mt-15"><span>{{ $subCategory && $subCategory->title ? $subCategory->title : '' }}</span></li>
                                <?php endif; ?>
                                @elseif(isset($_GET['brand']) && $_GET['brand'])
                                <li class="breadcrumb__content--menu__items mb-25 mt-15"><span>{{ ucwords(str_replace('-', ' ', $_GET['brand'])) }}</span></li>
                                @else
                                <li class="breadcrumb__content--menu__items mb-25 mt-15"><span>Sale</span></li>
                                @endif
                            </ul>
							<h2 class="breadcrumb__content--title mb-15">{{ $subCategory ? $subCategory->title : ($category ? $category->title : '') }}</h2>
							<p class="mt-15">{{ $subCategory ? $subCategory->description : ($category ? $category->description : '') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- End breadcrumb section -->

        <!-- Start shop section -->
        <section class="shop__section section--padding">
            <div class="container-fluid">
                <div class="shop__header bg__gray--color d-flex align-items-center justify-content-between mb-30">
                    <button class="widget__filter--btn d-flex d-lg-none align-items-center" data-offcanvas>
                        <svg  class="widget__filter--btn__icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="28" d="M368 128h80M64 128h240M368 384h80M64 384h240M208 256h240M64 256h80"/><circle cx="336" cy="128" r="28" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="28"/><circle cx="176" cy="256" r="28" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="28"/><circle cx="336" cy="384" r="28" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="28"/></svg> 
                        <span class="widget__filter--btn__text">Filter</span>
                    </button>
                    <div class="product__view--mode d-flex align-items-center">
                        
                        <div class="product__view--mode__list product__short--by align-items-center d-none d-lg-flex">
                            <label class="product__view--label">Sort By :</label>
                            <div class="select shop__header--select">
                                <select class="product__view--select" v-on:change="sortIt">
                                    <option :selected="!sort_by ? true : false" value="">Sort by latest</option>
                                    <option :selected="sort_by == 'price_asc' ? true : false" value="price_asc">Price (Low - High)</option>
                                    <option :selected="sort_by == 'price_desc' ? true : false" value="price_desc">Price (High - Low)</option>
                                    <option :selected="sort_by == 'a_z' ? true : false" value="a_z">Sort Name (A - Z) </option>
                                </select>
                            </div>
                        </div>
                        
                    </div>
                    <p class="product__showing--count">@{{paginationMessage}}</p>
                </div>
                <div class="row">
                    <div :class="(searchPage ? `col-xl-12 col-lg-12` : `col-xl-9 col-lg-8 `)">
                        <div class="shop__product--wrapper">
                            <div class="tab_content">
                                <div id="product_grid" class="tab_pane active show">
                                    <div class="product__section--inner product__grid--inner">
                                        <div class="row row-cols-xl-4 row-cols-lg-3 row-cols-md-3 row-cols-2 mb--n30">

                                            <div v-if="listing && listing.length > 0" v-for="product in listing" class="col mb-30">
                                                <div class="product__items ">
                                                    <div class="product__items--thumbnail">
                                                        <a class="product__items--link" :href="'/'+product.slug">
                                                            <img v-for="(image, k) in product.image" :class="`product__items--img`+ (k > 0 ? ` product__secondary--img` : ` product__primary--img` )" :src="image && image.small ? image.small : (site_url + '/assets/img/product/product8.png')" alt="product-img">
                                                        </a>
                                                        <div class="product__badge" v-if="(product.sale_price*1) > 0">
                                                            <span class="product__badge--items sale">Sale</span>
                                                        </div>
                                                    </div>
                                                    <div class="product__items--content">
                                                        <span class="product__items--content__subtitle">@{{product.category}}, @{{product.gender}}</span>
                                                        <h3 class="product__items--content__title h4"><a :href="'/'+product.slug">@{{product.title}} - @{{product.sku_number}}</a></h3>
                                                        <div class="product__items--price">
                                                            <span class="current__price"><span style="color:#F96167">@{{currency(product.price)}} - @{{currency(product.max_price)}}</span> ex. VAT</span>
                                                            <hr />
                                                            <small><b>@{{product.colors_count && product.colors_count.length > 0 ? (product.colors_count.length > 1 ? product.colors_count.length + ` Colours` : `1 Colour` ) : `No colours`}}</b> available.</small>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col mb-30" v-if="empty"><div>No records are available. Please adjust your filters criteria!</p></div></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="pagination__area bg__gray--color">
                                <nav class="pagination justify-content-center">
                                    <ul class="pagination__wrapper d-flex align-items-center justify-content-center">
                                        <li class="pagination__list">
                                            <a href="javascript:;" :class="`pagination__item--arrow  link` + (page <= 1 ? ` text-muted` : `` )" v-on:click="page > 1 ? paginateIt(page-1) : null">
                                                <svg xmlns="http://www.w3.org/2000/svg"  width="22.51" height="20.443" viewBox="0 0 512 512"><path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="48" d="M244 400L100 256l144-144M120 256h292"/></svg>
                                                <span class="visually-hidden">pagination arrow</span>
                                            </a>
                                        <li>
                                        <li v-for="i in pagination" class="pagination__list" >
                                            <span v-if="i == page" class="pagination__item pagination__item--current">@{{ i }}</span>
                                            <a v-else href="javascript:;" v-on:click="paginateIt(i)" :class="( i == page ? `pagination__item pagination__item--current` : 'pagination__item link')">@{{ i }}</a>
                                        </li>
                                        <li class="pagination__list">
                                            <a href="javascript:;" :class="`pagination__item--arrow  link` + (page >= maxPages ? ` text-muted` : `` )" v-on:click="page < maxPages ? paginateIt(page+1) : null">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="22.51" height="20.443" viewBox="0 0 512 512"><path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="48" d="M268 112l144 144-144 144M392 256H100"/></svg>
                                                <span class="visually-hidden">pagination arrow</span>
                                            </a>
                                        <li>
                                    </ul>
                                </nav>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-lg-4" v-if="!searchPage">
							@include('frontend.partials.sidebar-productlist')
                    </div>
                </div>
            </div>
        </section>
        <!-- End shop section -->
</div>
@endsection