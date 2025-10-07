<div class="col-lg-4 col-md-4 col-12 col-sm-6" v-if="listing && listing.length > 0" v-for="product in listing">
                                <div class="product-cart-wrap mb-30">
                                    <div class="product-img-action-wrap">
                                        <div class="product-img product-img-zoom">
                                            <a :href="'/'+product.slug">
                                                <img v-for="(image, k) in product.image" v-if="k < 1" class="default-img" :src="image && image.small ? image.small : (site_url + '/frontend/assets/imgs/shop/product-3-1.jpg')" alt="product-img">
                                                <img v-for="(image, k) in product.image" v-if="k == 1" class="hover-img" :src="image && image.small ? image.small : (site_url + '/frontend/assets/imgs/shop/product-3-1.jpg')" alt="product-img">
                                            </a>
                                        </div>
                                        
                                        <div class="product-badges product-badges-position product-badges-mrg" v-if="(product.sale_price*1) > 0">
                                            <span class="best">Sale</span>
                                        </div>
                                    </div>
                                    <div class="product-content-wrap">
                                        <div class="product-category">
                                            <a :href="'/'+product.slug">@{{product.category}} @{{product.gender ? `, `+product.gender : ''}}</a>
                                        </div>
                                        <h2><a  :href="'/'+product.slug">@{{product.title}}</a></h2>
                                        <div><span>SKU: @{{product.sku_number}}</span></div>
                                        <div class="product-price">
                                            <span>@{{currency(product.price)}}</span>
                                            <span v-if="product.max_price" class="old-price">@{{currency(product.max_price)}}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 mb-30" v-if="empty"><div>No records are available. Please adjust your filters criteria!</p></div></div>