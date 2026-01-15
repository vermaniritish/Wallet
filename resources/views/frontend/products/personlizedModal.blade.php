<div id="popup1" style="overflow:auto" :class="`overlay show` + (!editLogo ? ` d-none` : `` )">
	<div class="container custom-modal">
		<a class="close float-end" style="font-size:24px" href="#" v-on:click="closeModal">&times;</a>
		<div class="" v-for="(s, i) in sizes" v-if="s && s.logo && (s.quantity*1) > 0">
			<h1>Customise Your Apparel</h1>
			<p>@{{s.title}} | @{{s.size_title}} | @{{s.color}}</p>
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
							<div class="logo-image" style="max-width:150px; max-height:150px; object-fit: content;" v-if="lVal && lVal.image"><img :src="lVal.image" style="max-width: 100%;max-height:100%;" /></div>
							<p class="file-note">PNG / JPG • Max 2MB • Transparent preferred</p>
							<p class="file-note">Don't worry how it looks, we will make it look great and send a proof before we add to your products!</p>
							<p style="text-align:center;font-weight:600;">alternatively...</p>
							<label>
								<input type="radio" name="logo_option" value="later">
								Don't have your logo to hand? Don't worry — we will contact you after you place your order.
							</label>
							<label>
								<input type="radio" name="logo_option" value="existing">
								You already have my logo, it's just not in my account (no setup fee will be charged).
							</label>
						</div>

						<!-- Text Box -->
						<div class="inline-box">
							<input type="text" id="customText" maxlength="20" placeholder="Write your text (max 20 chars)" v-model="lVal.text">
							<div class="inline-box">
								<div class="box-title">✍️ Write Your Text</div>
								<input type="text" class="text-line" maxlength="10" placeholder="Line 1 (max 10)" v-model="lVal.text">
								<input type="text" class="text-line" maxlength="10" placeholder="Line 2 (max 10)" v-model="lVal.text1">
								<input type="text" class="text-line" maxlength="10" placeholder="Line 3 (max 10)" v-model="lVal.text2">
								<label>Font</label>
								<select id="fontSelect" v-model="lVal.font">
									<option value="Roboto">Roboto</option>
									<option value="Poppins">Poppins</option>
									<option value="Montserrat">Montserrat</option>
									<option value="Playfair Display">Playfair</option>
									<option value="Oswald">Oswald</option>
									<option value="Raleway">Raleway</option>
									<option value="Lobster">Lobster</option>
									<option value="Pacifico">Pacifico</option>
								</select>

								<label>Color</label>
								<select id="colorSelect" v-model="lVal.color">
									<option value="#000000">Black</option>
									<option value="#ffffff">White</option>
									<option value="#e63946">Red</option>
									<option value="#1d3557">Navy</option>
									<option value="#2a9d8f">Teal</option>
									<option value="#f4a261">Orange</option>
									<option value="#6c63ff">Purple</option>
									<option value="#088178">Green</option>
								</select>
							</div>
						</div>

					</div>
					<div class="section">
						<label class="title">Live Preview</label>
						<div id="textPreview">
							<div
								:style="{
									fontFamily: lVal.font || 'Roboto',
									color: lVal.color || '#000'
								}"
								>
								@{{ lVal.text }}
							</div>

							<div
							:style="{
								fontFamily: lVal.font || 'Roboto',
								color: lVal.color1 || '#000'
							}"
							>
								@{{ lVal.text1 }}
							</div>

							<div
							:style="{
								fontFamily: lVal.font || 'Roboto',
								color: lVal.color2 || '#000'
							}"
							>
								@{{ lVal.text2 }}
							</div>
						</div>
					</div>
					<div class="section">
					<label class="title">4. Additional Notes (Optional)</label>
					<div class="inline-box">
						<div class="box-title">📝 Notes for Designer / Printer</div>
							<textarea v-model="notes" id="notes" maxlength="500" rows="5" placeholder="Please let us know if you have any special requirements and instructions. (Max 500 characters)"></textarea>
							<div class="char-count" id="notesCount">@{{lVal.notes.length}} / 500</div>
						</div>
					</div>
				</div>
				<small><span class="formhead">Price:</span> &pound; @{{lVal.price && (lVal.price*1) > 0 ? lVal.price : '0.00' }}</small>
			</template>
		</div>
		<button v-on:click="addToCart()" class="submit-btn">Submit Customisation</button>
	</div>
</div>
 
