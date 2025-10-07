<div v-if="showForgotPasswordForm" class="col">
    <div class="account__login">
        <div class="account__login--header mb-25">
            <h2 class="account__login--header__title h3 mb-10">Forgot Password</h2>
            <p class="account__login--header__desc">Enter email to recover password.</p>
        </div>
        <div class="account__login--inner">
            <form id="forgot-form">
                <input class="account__login--input" required name="email" placeholder="Email Address" type="text">
                <div v-if="forgotSuccessMessages" class="text-success text-center">@{{ forgotSuccessMessages }}</div>
                <div v-else-if="forgotErrorMessages" class="text-danger text-center">@{{ forgotErrorMessages }}</div>
                <div class="account__login--remember__forgot mb-15 d-flex justify-content-between align-items-center">
                    <button class="account__login--forgot" v-on:click="disableForgotPassword()" type="button"><i class="fas fa-arrow-left" ></i> Back</button>
                </div>
                <button class="account__login--btn primary__btn" v-on:click="postForgotPassword()" type="button"><i class="fa fa-spin fa-spinner" v-if="forgotLoading"></i>Submit</button>
                <p class="account__login--signup__text">Don,t Have an Account? <a href="{{ url('/login') }}">Sign up now</a></p>
            </form>
        </div>
    </div>
</div>	