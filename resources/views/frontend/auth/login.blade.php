<div v-if="showLoginForm" id="loginform" class="panel-collapse login_form collapse show" style="">
    <div class="panel-body">
        <h2 class="">Login</h2>
        <p class="mb-30 font-sm">If you have shopped with us before, please enter your details below. If you are a new customer, please proceed to the Billing &amp; Shipping section.</p>
        <form method="post" id="login-form">
            <div class="form-group"><input class="account__login--input" required name="email" placeholder="Email Address" v-model="email" type="text"></div>
            <div class="form-group"><input class="account__login--input" required name="password" placeholder="Password"  v-model="password" type="password"></div>
            <div class="login_footer form-group">
                <div class="chek-form">
                    <div class="custome-checkbox"><input type="checkbox" name="checkbox"  :checked="remember" v-on:change="remember = !remember" id="remember" value="" class="form-check-input" :checked="remember" v-on:change="remember = !remember"> <label for="remember" class="form-check-label"><span>Remember me</span></label></div>
                </div>
                <a href="javascript:;" v-on:click="showForgotPassword()">Forgot password?</a>
            </div>
            <div class="d-flex flex-row align-items-center gap-4">
                <button type="button" name="login" v-on:click="login()" class="btn btn-md">Log in</button>
                <div v-if="loginErrorMessages" class="text-danger text-center m-0 p-0 newsletter-error">@{{ loginErrorMessages }}</div>
            </div>
        </form>
    </div>
</div>