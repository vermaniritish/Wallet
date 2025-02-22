    @if(isset($categories) && $categories && $categories->count() > 0)

                            <div class="single__widget widget__bg">
                                <h4 class="widget__title h4">Categories In {{ $category && $category->title ? $category->title : '' }}</h4>
                                <ul class="widget__tagcloud">
                                    <li class="widget--list">
                                        <a :class="`sideitemspace `+( filters.categories.legth < 1 ? 'active' : '' )" href="javascript:;" v-on:click="categoryFilter(``)">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-right" viewBox="0 0 16 16">
                                                <path fill-rule="evenodd" d="M1 8a.5.5 0 0 1 .5-.5h11.793l-3.147-3.146a.5.5 0 0 1 .708-.708l4 4a.5.5 0 0 1 0 .708l-4 4a.5.5 0 0 1-.708-.708L13.293 8.5H1.5A.5.5 0 0 1 1 8"/>
                                            </svg>
                                            All
                                        </a>
                                    </li>
                                    <?php foreach($categories as $c): ?>
                                    <li class="widget--list">
                                        
                                        <a :class="`sideitemspace `+( filters.categories.includes('{{$c->slug}}') ? 'active' : '' )" href="javascript:;" v-on:click="categoryFilter('{{$c->slug}}')">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-right" viewBox="0 0 16 16">
                                                <path fill-rule="evenodd" d="M1 8a.5.5 0 0 1 .5-.5h11.793l-3.147-3.146a.5.5 0 0 1 .708-.708l4 4a.5.5 0 0 1 0 .708l-4 4a.5.5 0 0 1-.708-.708L13.293 8.5H1.5A.5.5 0 0 1 1 8"/>
                                            </svg>
                                            <?php echo $c->title ?>
                                        </a>
                                    </li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
    @endif

							<div class="single__widget widget__bg">
                                <h4 class="widget__title h4">Gender</h4>
                                <ul class="widget__form--check">
                                    <li class="widget__form--check__list">
                                        <label class="widget__form--check__label" for="check1">Men @{{ counts.menCount ? `(${counts.menCount})` : `` }}</label>
                                        <input class="widget__form--check__input" id="check1" v-on:change="genderFilter('Male')" type="checkbox">
                                        <span class="widget__form--checkmark"></span>
                                    </li>
                                    <li class="widget__form--check__list">
                                        <label class="widget__form--check__label" for="check2">Women @{{ counts.womenCount ? `(${counts.womenCount})` : `` }}</label>
                                        <input class="widget__form--check__input" id="check2"  v-on:change="genderFilter('Female')" type="checkbox">
                                        <span class="widget__form--checkmark"></span>
                                    </li>
                                    <li class="widget__form--check__list">
                                        <label class="widget__form--check__label" for="check3">Kids @{{ counts.kidsCount ? `(${counts.kidsCount})` : `` }}</label>
                                        <input class="widget__form--check__input" id="check3"  v-on:change="genderFilter('Kids')" type="checkbox">
                                        <span class="widget__form--checkmark"></span>
                                    </li>
                                    <li class="widget__form--check__list">
                                        <label class="widget__form--check__label" for="check4">Unisex @{{ counts.unisexCount ? `(${counts.unisexCount})` : `` }}</label>
                                        <input class="widget__form--check__input" id="check4"  v-on:change="genderFilter('Unisex')" type="checkbox">
                                        <span class="widget__form--checkmark"></span>
                                    </li>
                                    
                                </ul>
                            </div>
                            <div class="single__widget price__filter widget__bg">
                                <h4 class="widget__title h4">Filter By Price</h4>
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
                            
							<div class="single__widget widget__bg">
                                <h4 class="widget__title h4">Available Brands</h4>
                                <ul class="widget__tagcloud">
                                    <?php foreach($brands as $b): ?>
                                    <li class="widget__tagcloud--list"><a class="widget__tagcloud--link" href="shop.html">Protex</a></li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
							
                        