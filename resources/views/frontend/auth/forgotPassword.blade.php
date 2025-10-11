<div v-if="showForgotPasswordForm" class="panel-collapse login_form collapse show" style="">
    <div class="panel-body">
        <h2 class="">Forgot Password</h2>
        <p class="mb-30 font-sm">Enter email to recover password.</p>
        <form method="post" id="forgot-form">
            <div class="form-group">
                <input type="text" name="email" placeholder="Email Address" required>
            </div>
            <div class="login_footer form-group">
                <div class="chek-form">
                    <a class="account__login--forgot" v-on:click="disableForgotPassword()" type="button"><i class="fas fa-arrow-left" ></i> Back</a>
                </div>
            </div>
            <div class="d-flex flex-row align-items-center gap-4">
                <button type="button" name="login" v-on:click="postForgotPassword()" class="btn btn-md"><i class="fa fa-spin fa-spinner" v-if="forgotLoading"></i>Submit</button>
                <p v-if="forgotSuccessMessages" class="text-success text-center">@{{ forgotSuccessMessages }}</p>
                <p v-else-if="forgotErrorMessages" class="text-danger text-center">@{{ forgotErrorMessages }}</p>
            </div>
        </form>
    </div>
</div>
