<div id="popup1" :class="`overlay show` + (!editLogo ? ` d-none` : `` )">
	<div class="popup">
		<h2 style="color:#C69D5F;">Add Personalised Logo</h2>
		<!-- <span>Note* - One Time £15 Setup Fees will be applicable apart from logo adding cost</span> -->
		<br/><br/>
		<a class="close" href="#" v-on:click="closeModal">&times;</a>
		<div class="content">
			<div class="product__variant--list mb-20" >
				<div class="accordion accordion-flush" id="accordionFlushExample">
					<div class="accordion-item" v-for="(s, i) in sizes" v-if="s && s.logo && (s.quantity*1) > 0">
						<h2 class="accordion-header" id="flush-headingOne">
						  <button class="accordion-button collapsed popupproductheading" type="button" data-bs-toggle="collapse" :data-bs-target="`#flush-collapseOne`+i" aria-expanded="false" :aria-controls="`flush-collapseOne`+i">
							@{{s.title}} | @{{s.size_title}} | @{{s.color}}
						  </button>
						</h2>
						<div 
							:id="`flush-collapseOne`+i"
							class="accordion-collapse collapse show" aria-labelledby="flush-headingOne" data-bs-parent="#accordionFlushExample">
							<div class="accordion-body" v-for="(lVal, lKey) in s.logo">
								<div class="row">
									<p class="formhead">Select Logo Position.</p>
									<div class="col-lg-3">
										
										<div style="border:1px solid #2F3751;height:250px;overflow: scroll">
											<ul class="imgradio">
											  <li v-for="(p, pi) in logoOptions.positions">
												<input type="radio" :name="`logop`+i+lKey+pi" :id="`logop`+i+lKey+pi" :value="p" v-model="lVal.postion" v-on:change="onChange(i, s, null, lKey)" />
												<label :for="`logop`+i+lKey+pi"><img :src="`{{url('/frontend/assets/size-guides')}}/`+p.trim().toLowerCase().replace(/ /g, '-').replace(/[^a-zA-Z0-9]/g, '-')+`.jpg`" /></label>
											  </li>
											</ul>
										</div>
									</div>
									<div class="col-lg-9">
										<span class="formhead">Choose Application Method.</span><br/>
										<label class="variant__color--value2 red d-none" title="None"  style="margin:0px;padding:0px;">
											<input type="radio" :name="`logooption`+i+lKey" type="radio" v-on:input="onChange(i, s, 'None', lKey)" :checked="!lVal.category || lVal.category == 'None'">
											None
										</label>
										<label v-if="logoOptions && logo" v-for="(c, k) in logoOptions.category"  class="variant__color--value2 red" :title="c"  style="margin:0px;padding:0px;margin-right:10px;">
											<input type="radio" :name="`logooption`+i+lKey" type="radio" v-on:input="onChange(i, s, c, lKey)" :checked="lVal.category == c">
											@{{c}}
										</label>

										<div class="row" v-if="lVal.category != 'None'">
											<div class="col-lg-12">
												<label class="m-0 p-0"><input type="checkbox" v-model="lVal.already_uploaded"  /> Pinder already have a logo.</label>
											</div>
											<div class="col-lg-5" v-if="!lVal.already_uploaded"><br/>
												<div >
													<span class="formhead">Upload your Logo</span>
													<button class="btn btn-sm btn-primary" v-on:click="handleFileUpload(i, lKey)"><i v-if="uploading !== null && uploading == i" class="fa fa-spin fa-spinner"></i> <i v-else class="fa fa-upload"></i> Upload Logo</button><br/>
													<p style="color:#ee2761;font-size:12.5px;">Image should not exceed 2MB size</p>
												</div>
												<div class="logo-image" style="max-width:150px; max-height:150px; object-fit: content;" v-if="lVal && lVal.image"><img :src="lVal.image" style="max-width: 100%;max-height:100%;" /></div>
											</div>
											<div class="col-lg-1" v-if="!lVal.already_uploaded"><br/>
												<h4>OR</h4>
											</div>
											<div class="col-lg-6" v-if="!lVal.already_uploaded"><br/>
												<span class="formhead">Write your Logo Text</span>&nbsp;&nbsp;<input style="display:inline-block;width: 95%;" type="text" id="logotext" name="logotext" v-model="lVal.text">
											</div>
											<div class="col-lg-12"><br/>
												<span class="formhead">Price:</span> &pound; @{{lVal.price && (lVal.price*1) > 0 ? lVal.price : '0.00' }}
											</div>
										</div>
										<div class="row" v-else>
											<div class="col-lg-12"><p>There is no logo been applied for this product.</p></div>
										</div>
									
									</div>
								</div>
							</div>
							<button type="button" class="btn btn-sm btn-primary" v-on:click="addMoreLogo(i)"><i class="fa fa-plus"></i> Add More</button>
						</div>
						
					</div>
				</div>
			</div>
		</div>
		<div class="product__variant--list mb-15">
            <button class="variant__buy--now__btn primary__btn" v-on:click="addToCart()" type="button">Confirm & Add to cart</button>
		</div>
	</div>
</div>
 
