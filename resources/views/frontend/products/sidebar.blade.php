<div class="widget-category mb-15">
    <h5 class="section-title style-1 mb-30 wow fadeIn animated">Category</h5>
    <ul class="categories">
        <?php foreach($categories as $c): ?>
        <li :class="( filters.categories.includes('{{$c->slug}}') ? 'active strong' : '' )"><a href="javascript:;" v-on:click="categoryFilter('{{$c->slug}}')"><?php echo $c->title ?></a></li>
        <?php endforeach; ?>
    </ul>
</div>
<div class="widget-category mb-15">
        <h5 class="section-title style-1 mb-30 wow fadeIn animated">Gender</h5>
        <ul class="widget__form--check">
            <li class="widget__form--check__list">
                <input class="widget__form--check__input" id="check1" v-on:change="genderFilter('Male')" type="checkbox" style="height:16px;width:16px;">
                <label class="widget__form--check__label" for="check1">Men @{{ counts.menCount ? `(${counts.menCount})` : `` }}</label>
            </li>
            <li class="widget__form--check__list">
                <input class="widget__form--check__input" id="check2"  v-on:change="genderFilter('Female')" type="checkbox" style="height:16px;width:16px;">
                <label class="widget__form--check__label" for="check2">Women @{{ counts.womenCount ? `(${counts.womenCount})` : `` }}</label>
            </li>
            <li class="widget__form--check__list">
                <input class="widget__form--check__input" id="check3"  v-on:change="genderFilter('Kids')" type="checkbox" style="height:16px;width:16px;">
                <label class="widget__form--check__label" for="check3">Kids @{{ counts.kidsCount ? `(${counts.kidsCount})` : `` }}</label>
            </li>
            <li class="widget__form--check__list">
                <input class="widget__form--check__input" id="check4"  v-on:change="genderFilter('Unisex')" type="checkbox" style="height:16px;width:16px;">
                <label class="widget__form--check__label" for="check4">Unisex @{{ counts.unisexCount ? `(${counts.unisexCount})` : `` }}</label>
            </li>
            
        </ul>
    </div>
    <div class="widget-category mb-30">
        <h5 class="section-title style-1 mb-30 wow fadeIn animated">Filter By Price</h5>
        <form class="price__filter--form" action="#"> 
            <div class="price__filter--form__inner mb-15 d-flex align-items-center">
                <div class="price__filter--group">
                    <label class="price__filter--label" for="Filter-Price-GTE2">From</label>
                    <div class="price__filter--input border-radius-5 d-flex align-items-center">
                        <span class="price__filter--currency">£</span>
                        <label>
                            <input class="price__filter--input__field border-0" name="filter.v.price.gte" type="number" v-model="filters.fromPrice" >
                        </label>
                    </div>
                </div>
                <div class="price__divider">
                    <span>-</span>
                </div>
                <div class="price__filter--group">
                    <label class="price__filter--label" for="Filter-Price-LTE2">To</label>
                    <div class="price__filter--input border-radius-5 d-flex align-items-center">
                        <span class="price__filter--currency">£</span>
                        <label>
                            <input class="price__filter--input__field border-0" name="filter.v.price.lte" type="number"  v-model="filters.toPrice"> 
                        </label>
                    </div>	
                </div>
            </div>
            <small class="text-danger" v-if="priceError">Provided pricing is incorrect.</small>
            <button type="button" v-on:click="priceFilter" class="price__filter--btn primary__btn" type="submit">Filter</button>
        </form>
    </div>
    
    <!-- Product sidebar Widget -->
    <div class="sidebar-widget product-sidebar  mb-30 p-30 bg-grey border-radius-10">
        <div class="widget-header position-relative mb-20 pb-10">
            <h5 class="widget-title mb-10">New products</h5>
            <div class="bt-1 border-color-1"></div>
        </div>
        <div class="single-post clearfix">
            <div class="image">
                <img src="{{ url('frontend/assets/imgs/shop/thumbnail-3.jpg') }}" alt="#">
            </div>
            <div class="content pt-10">
                <h5><a href="shop-product-detail.html">Chen Cardigan</a></h5>
                <p class="price mb-0 mt-5">$99.50</p>
                
            </div>
        </div>
        <div class="single-post clearfix">
            <div class="image">
                <img src="{{ url('frontend/assets/imgs/shop/thumbnail-3.jpg') }}" alt="#">
            </div>
            <div class="content pt-10">
                <h6><a href="shop-product-detail.html">Chen Sweater</a></h6>
                <p class="price mb-0 mt-5">$89.50</p>
                
            </div>
        </div>
        <div class="single-post clearfix">
            <div class="image">
                <img src="{{ url('frontend/assets/imgs/shop/thumbnail-3.jpg') }}" alt="#">
            </div>
            <div class="content pt-10">
                <h6><a href="shop-product-detail.html">Colorful Jacket</a></h6>
                <p class="price mb-0 mt-5">$25</p>
                
            </div>
        </div>
    </div>
    <div class="banner-img wow fadeIn mb-45 animated d-lg-block d-none">
        <img src="{{ url('frontend/assets/imgs/banner/banner-11.jpg') }}" alt="">
        <div class="banner-text">
            <span>Women Zone</span>
            <h4>Save 17% on <br>Office Dress</h4>
            <a href="category-products.php">Shop Now <i class="fi-rs-arrow-right"></i></a>
        </div>
    </div>