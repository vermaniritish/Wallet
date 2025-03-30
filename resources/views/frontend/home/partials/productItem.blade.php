<?php $colorCounts = App\Models\Admin\ProductSizeRelation::select(['color_id'])->where('product_id', $product->id)->groupBy('color_id')->pluck('color_id')->toArray() ?>
                                <div class="product__items ">
                                    <div class="product__items--thumbnail">
                                        <a class="product__items--link" href="{{ url('/' . $product->slug) }}">
                                            @if($product->image)
                                            <?php foreach($product->image as $k => $image) : ?>
                                            <img class="product__items--img {{ $k > 0 ? 'product__secondary--img' :  'product__primary--img' }}" src="{{ $image && $image['small'] ? $image['small'] : url('/assets/img/product/product8.png') }}" alt="product-img">
                                            <?php endforeach; ?>                                            
                                            @endif
                                        </a>
                                        
                                    </div>
                                    <div class="product__items--content">
                                        <span class="product__items--content__subtitle">{{ $product->category }}</span>
                                        <h3 class="product__items--content__title h4"><a href="{{ url('/' . $product->slug) }}">{{ $product->title }}{{ $product->sku_number ? ' - '.$product->sku_number : '' }}</a></h3>
                                        <div class="product__items--price">
                                            <span class="current__price"><span style="color:#F96167">{{_currency($product->price)}} - {{_currency($product->max_price)}}</span> ex. VAT</span>
                                            <hr />
                                            <small><b>{{$colorCounts && count($colorCounts) > 0 ? (count($colorCounts) > 1 ? count($colorCounts). ' Colours' : '1 Colour' ) : 'No colours'}}</b> available.</small>
                                            
                                        </div>
                                    </div>
                                </div>