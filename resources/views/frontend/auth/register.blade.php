<div class="col">
    <div class="account__login register">
        <div class="account__login--header mb-25"  v-if="!registered">
            <h2 class="account__login--header__title h3 mb-10">Create an Account</h2>
            <p class="account__login--header__desc">Register here if you are a new customer</p>
        </div>
		@include('admin.partials.flash_messages')
        <div class="account__login--inner">
            <div class="row" v-if="registered">
                <div class="col-md-12 text-center">
                    <i style="font-size:60px" class="my-4 fa fa-circle-check fa-check-circle text-success"></i>
                    <p class="h3 text-center mb-3">Welcome to Pinders Workwear.</p> <p class="text-center my-4">Confirmation email has been sent, please check your email and confirm/activate your account.</p> <p class="text-center my-4">Thankyou for Registration.</p>
                </div>
            </div>
            <form v-else id="register-form" >
                <input required class="account__login--input" name="first_name" placeholder="First Name" type="text">
                <input required class="account__login--input" name="email" placeholder="Email Addres" type="email">
                <div class="input-group account__login--input">
                    <div class="input-group-prepend">
                        <span class="input-group-text" id="basic-addon1">+44</span>
                    </div>
                    <input type="text" class="form-control" placeholder="Phone Number" aria-label="Phone Number" aria-describedby="basic-addon1" name="phonenumber">
                </div>
                <input required class="account__login--input" name="password" placeholder="Password" id="password" type="password">
                <input required class="account__login--input" name="password_confirmation" placeholder="Confirm Password" id="confirmPassword" type="password">
                <div v-if="registerErrorMessages" class="text-danger text-center">@{{ registerErrorMessages }}</div>
                <button type="button" class="account__login--btn primary__btn mb-10" v-on:click="register()"><i class="fa fa-spin fa-spinner" v-if="loading"></i> Submit & Register </button>
                <div class="account__login--remember position__relative">
                    <input class="checkout__checkbox--input" id="check2" type="checkbox">
                    <span class="checkout__checkbox--checkmark"></span>
                    <label class="checkout__checkbox--label login__remember--label" for="check2">
                        I have read and agree to the <a target="_blank" href="{{ url('/terms-conditions') }}">terms & conditions</a></label>
                </div>
            </form>
        </div>
    </div>
</div>