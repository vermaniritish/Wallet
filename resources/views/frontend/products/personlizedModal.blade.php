<div id="popup1" style="overflow:auto" :class="`overlay show` + (!editLogo ? ` d-none` : `` )">
	<a class="close" href="#" v-on:click="closeModal">&times;</a>
	<div class="container custom-modal">
		<div class="" v-for="(s, i) in sizes" v-if="s && s.logo && (s.quantity*1) > 0">
			<h1>Customise Your Apparel</h1>
			<p class="small">@{{s.title}} | @{{s.size_title}} | @{{s.color}}</p>
			<template v-for="(lVal, lKey) in s.logo">
				<div class="section">
					<label class="title">1. Select Logo / Text Position</label>
					<div class="image-options">
						<label class="image-option" v-for="(p, pi) in logoOptions.positions">
							<input type="radio" :name="`logop`+i+lKey+pi" :id="`logop`+i+lKey+pi" :value="p" v-model="lVal.postion" v-on:change="onChange(i, s, null, lKey)">
							<img :src="`{{url('/frontend/assets/size-guides')}}/`+p.trim().toLowerCase().replace(/ /g, '-').replace(/[^a-zA-Z0-9]/g, '-')+`.jpg`">
						</label>
					</div>
				</div>

				<!-- 2. Application Method -->
				<div class="section">
					<label class="title">2. Choose Application Method</label>
					<div class="radio-group">
						<label><input type="radio" :name="`logooption`+i+lKey" type="radio" v-on:input="onChange(i, s, 'None', lKey)" :checked="!lVal.category || lVal.category == 'None'"> None</label>
						<label v-if="logoOptions && logo" v-for="(c, k) in logoOptions.category"><input type="radio" :name="`logooption`+i+lKey" type="radio" v-on:input="onChange(i, s, c, lKey)" :checked="lVal.category == c">@{{c}}</label>
					</div>
				</div>

				<!-- 3. Upload OR Write Text -->
				<div class="section" v-if="lVal.category != 'None'">
					<label class="title">3. Upload Your Logo OR Write Your Text</label>

					<div class="inline-row">

						<!-- Upload Box -->
						<div class="inline-box">
							<button class="btn btn-sm btn-primary" v-on:click="handleFileUpload(i, lKey)"><i v-if="uploading !== null && uploading == i" class="fa fa-spin fa-spinner"></i> <i v-else class="fa fa-upload"></i> Upload Logo</button>
							<p class="file-note">Image should not exceed 2MB</p>
							<div class="logo-image" style="max-width:150px; max-height:150px; object-fit: content;" v-if="lVal && lVal.image"><img :src="lVal.image" style="max-width: 100%;max-height:100%;" /></div>
						</div>

						<!-- Text Box -->
						<div class="inline-box">
							<input type="text" id="customText" maxlength="20" placeholder="Write your text (max 20 chars)" v-model="lVal.text">
							<div class="char-limit" id="charCount">@{{ lVal.text.length }} / 20</div>
						</div>

					</div>
				</div>
				<small><span class="formhead">Price:</span> &pound; @{{lVal.price && (lVal.price*1) > 0 ? lVal.price : '0.00' }}</small>
			</template>
		</div>
		<button v-on:click="addToCart()" class="submit-btn">Submit Customisation</button>
	</div>
</div>
 
