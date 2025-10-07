<pre class="d-none" id="logo-options">{{ json_encode($logoOptions) }}</pre>
<div class="product__variant--list mb-20">
        <fieldset class="variant__input--fieldset weight">
            <a href="#" v-on:click="openLogoModal"><legend class="product__variant--title mb-8"><img src="{{ url('/frontend/assets/img/other/open.png')}}" /> Add Personalised Logo :</legend></a>
            <a href="#/" id="show"><legend class="product__variant--title mb-8"><img src="{{ url('/frontend/assets/img/other/close.png')}}" /> Close :</legend></a>
            <div id="answer">
                <p>We offer embroidered AND/OR printed logos.</p>
                <span  v-if="logoOptions && logo" v-for="(c, i) in logoOptions.category">
                    <input type="radio" :id="`logooption`+i" name="logooption" type="radio" v-model="logo.category">
                    <label class="variant__color--value2 red" :for="`logooption`+i" title="Printed Logo">@{{c}}</label>
                </span>
                <br/><br/>
                <div><a class='fancybox fancybox.iframe' rel='group' target='_blank' href='{{ url('/frontend/assets/size-guides/logo-positions.jpg')}}'> Click here to check Logo & Text/Initial Positions in pictures</a></div><br/>
                <div class="checkout__input--list checkout__input--select select">
                    <label class="checkout__select--label" for="country">Logo Position</label>
                    <select class="checkout__input--select__field border-radius-5" id="country" v-model="logo.postion">
                        <option value="">Select</option>
                        <option :value="p" v-if="logoOptions && logo" v-for="p in logoOptions.positions">@{{p}}</option>
                    </select>
                </div>
                <br/>
                <label for="uploadlogo" style="display:inline-block;">Upload your Logo: </label>&nbsp;&nbsp;
                    <button type="button" class="btn btn-primary" id="upload-product-logo" v-on:click="handleFileUpload"><i class="fa fa-upload"></i> Upload Logo</button>
                    <div class="logo-image" style="width:150px; height:150px; object-fit: content;" v-if="logo && logo.image"><img :src="logo.image" style="max-width: 100%;max-height:100%;" /></div>
                <br/>
                <small style="color:#ee2761;">Note: Image should not exceed 2MB size</small><br/>
                <h4>OR</h4>
                <label for="logotext" style="display:inline-block;">Write your Logo Text: </label>&nbsp;&nbsp;<input style="display:inline-block;width: 65%;" v-model="logo.text" type="text" id="logotext" name="logotext"><br/>
            </div>
            
        </fieldset>
    </div>
    <form class="d-none" method="post" action="<?php echo route('actions.uploadFile') ?>"  enctype="multipart/form-data" class="d-none" id="fileUploadForm">
        <?php echo csrf_field() ?>
        <input type="hidden" name="path" value="cart">
        <input type="hidden" name="file_type" value="image">
        <input type="file" name="file" onchange="productDetail.uploadFile()">
    </form>