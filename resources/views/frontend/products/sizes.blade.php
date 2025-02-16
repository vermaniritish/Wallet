<span id="productId" class="d-none">{{ $product->id }}</span>
<pre id="product-sizes" class="d-none">{{ json_encode($product->sizes ? $product->sizes : '') }}</pre>
<div class="product__variant--list mb-10">
    <fieldset class="variant__input--fieldset">
        <legend class="product__variant--title mb-8">Color :</legend>
        <?php 
        foreach($product->colors as $c): ?>
        <input id="color-red1" name="color" type="radio" checked>
        <label :class="`variant__color--value` + (color == '{{$c->id}}' ? ' red active' : '')" for="color-red1" title="{{ $c->title }}" style="background-color: {{$c->color_code ? $c->color_code : '#FFF'}}"
            v-on:click="selectColor({{$c->id}})"
        >
            @if($c->image)
            <img class="variant__color--value__img" src="{{url($c->image)}}" alt="variant-color-img">
            @else
            <span style="background-color: {{$c->color_code}}"></span>
            @endif
        </label>
        <?php endforeach; ?>
    </fieldset>
</div>
<br/>
    
<div class="product__variant--list mb-15">
    <fieldset class="variant__input--fieldset weight">
        <legend class="product__variant--title mb-8">Size & Quantity : &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        @if($product->size_file)
        <small><a class='fancybox fancybox.iframe' rel='group' target='_blank' href='{{ url($product->size_file)}}'><img src="{{ url('/frontend/assets/img/other/measure.png')}}" style="vertical-align: bottom;" /> Size Guide</a></small>
        @endif
        </legend>
    </fieldset>
</div>
<div class="product__variant--list quantity d-flex align-items-center mb-20">
    <div class="productsizesbox">
        <div class="productsizesboxContainer">

            <ul class="productsizesboxUL" data-loading="false" data-test-id="SizeList" v-if="renderSizes().length > 0">
                <li data-active="false" class="ProductSizes-newProductSizesItem-xII" data-test-id="ProductSize" v-for="s in renderSizes()">
                    <div class="productsizes" data-stock-status="InStock"><small>@{{ s.size_title }} </small></div>
                    <div class="productsizes-stockinfo1">
                        <small class="productsizes-stockinfo2">£@{{s.price}}</small>
                    </div>
                    <div class="quantity__box">
                        <button type="button" class="quantity__value" aria-label="quantity value" value="Decrease Value" v-on:click="decrement(s.id)">-</button>
                        <label>
                            <input type="number" class="quantity__number quickview__value--number" readonly :value="s.quantity && s.quantity > 0 ? s.quantity : ``" />
                        </label>
                        <button type="button" class="quantity__value" aria-label="quantity value" value="Increase Value"  v-on:click="increment(s.id)">+</button>
                    </div>
                </li>												
            </ul>
            <p class="text-danger" v-else>Out of Stock!</p>
        </div>
    </div>
    
</div>