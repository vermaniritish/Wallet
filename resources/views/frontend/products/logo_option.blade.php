<pre class="d-none" id="logo-options">{{ json_encode($logoOptions) }}</pre>
<div class="product__variant--list mb-20">
    <fieldset class="variant__input--fieldset weight">
        <a href="#" v-on:click="openLogoModal">
            <legend class="product__variant--title mb-8"><img src="{{ url('/frontend/assets/img/other/open.png')}}" /> Add Personalised Logo</legend>
        </a>
    </fieldset>
</div>
<form class="d-none" method="post" action="<?php echo route('actions.uploadFile') ?>"  enctype="multipart/form-data" class="d-none" id="fileUploadForm">
    <?php echo csrf_field() ?>
    <input type="hidden" name="path" value="cart">
    <input type="hidden" name="file_type" value="image">
    <input type="file" name="file" onchange="productDetail.uploadFile()">
</form>