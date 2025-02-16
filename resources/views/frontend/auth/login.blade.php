<div v-if="showLoginForm" class="col">
    <div class="account__login">
        <div class="account__login--header mb-25">
            <h2 class="account__login--header__title h3 mb-10">Login</h2>
            <p class="account__login--header__desc">Login if you are a returning customer.</p>
        </div>
        <div class="account__login--inner">
            <form id="login-form">
                <input class="account__login--input" required name="email" placeholder="Email Address" v-model="email" type="text">
                <input class="account__login--input" required name="password" placeholder="Password"  v-model="password" type="password">
                <div class="account__login--remember__forgot mb-15 d-flex justify-content-between align-items-center">
                    <div class="account__login--remember position__relative">
                        <input class="checkout__checkbox--input" id="check1" type="checkbox" :checked="remember" v-on:change="remember = !remember">
                        <span class="checkout__checkbox--checkmark"></span>
                        <label class="checkout__checkbox--label login__remember--label" for="check1">Remember me</label>
                    </div>
                    <button class="account__login--forgot" v-on:click="showForgotPassword()" type="button">Forgot Your Password?</button>
                </div>
                <div v-if="loginErrorMessages" class="text-danger text-center m-0 p-0 newsletter-error">@{{ loginErrorMessages }}</div>
                <button class="account__login--btn primary__btn" v-on:click="login()" type="button"><i class="fa fa-spin fa-spinner" v-if="loginloading"></i> Login</button>
                {{-- <p class="account__login--signup__text">Don,t Have an Account? <button v-on:click="forgotPassword()" type="button">Sign up now</button></p> --}}
            </form>
        </div>
    </div>
</div>