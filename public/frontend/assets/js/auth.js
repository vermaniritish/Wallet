if($('#auth').length)
var auth = new Vue({
    el: '#auth',
    data: {
    mounting: true,
    loading: false,
    remember: false,
    loginloading: false,
    forgotLoading: false,
    showLoginForm: true,
    showRegisterForm: true,
    showForgotPasswordForm: false,
    loginErrorMessages: null,
    registerErrorMessages: null,
    forgotErrorMessages: null,
    forgotSuccessMessages: null,
    registered: false,
    },
    mounted: function() {
        this.mounting = false;
        let remember = localStorage.getItem('remember');
        remember = remember ? JSON.parse(remember) : null;
        if(remember) {
            this.email = remember.email;
            this.password = remember.password;
            this.remember = true;
        }
    },
    methods: {
        register: async function() {
            if ($('#register-form').valid() && !this.loading) {
                this.loading = true;
                const password = document.getElementById('password').value;
                const confirmPassword = document.getElementById('confirmPassword').value;
                const termsConditionsChecked = document.getElementById('check2').checked;
                if (!termsConditionsChecked) {
                    this.registerErrorMessages = 'Please agree to the terms & conditions.';
                    this.loading = false;
                    return false;
                }

                let formData = new FormData(document.getElementById('register-form'));
                formData.append('_token', csrf_token()); 
                let response = await fetch(site_url+'/auth/register', {
                    method: 'POST',
                    body: formData,
                });
                response = await response.json();
                if(response && response.status)
                { 
                    document.getElementById('register-form').reset();
                    this.loading = false;
                    this.registered = true;
                }else{
                    this.registerErrorMessages = response.message;
                }
                this.loading = false;
                
            }
            else{
                return false;
            }
        },    
        login: async function() {
            if ($('#login-form').valid() && !this.loginloading) {
                this.loginloading = true;
                let formData = new FormData(document.getElementById('login-form'));
                formData.append('_token', csrf_token()); 
                let response = await fetch(site_url+'/auth/login', {
                    method: 'POST',
                    body: formData,
                });
                response = await response.json();
                if(response && response.status)
                {
                    if(this.remember) {
                        localStorage.setItem('remember', JSON.stringify({email: formData.get('email'), password: formData.get('password')}));
                    }
                    else {
                        localStorage.removeItem('remember');
                    }
                    this.loginloading = false;
                    set_notification('success', response.message);
                    window.location.href = site_url;
                }else{
                    this.loginErrorMessages = response.message;
                }
                this.loginloading = false;
            }
            else{
                return false;
            }
        },  
        showForgotPassword: function() {
            this.showLoginForm = false;
            this.showRegisterForm = false;
            this.showForgotPasswordForm = true;
        },
        disableForgotPassword: function() {
            this.showLoginForm = true;
            this.showRegisterForm = true;
            this.showForgotPasswordForm = false;
        },
        postForgotPassword: async function(){
            if ($('#forgot-form').valid() && !this.forgotLoading) {
                this.forgotLoading = true;
                let formData = new FormData(document.getElementById('forgot-form'));
                formData.append('_token', csrf_token()); 
                let response = await fetch(site_url+'/auth/forgot-password', {
                    method: 'POST',
                    body: formData,
                });
                response = await response.json();
                if(response && response.status)
                {
                    this.forgotLoading = false;
                    document.getElementById('forgot-form').reset();
                    this.forgotSuccessMessages = response.message;

                }else{
                    this.forgotLoading = false;
                    this.forgotErrorMessages = response.message;
                }
            }
            else{
                return false;
            }
        }
    },
});

if($('#verifyOtp').length)
var verifyOtp = new Vue({
    el: '#verifyOtp',
    data: {
    mounting: true,
    loading: false,
    errorMessages: {} ,
    },
    mounted: function() {
        this.mounting = false;
    },
    methods: {
        verifyOtp: async function() {
            if ($('#otp-form').valid()) {
                this.loading = true;
                let formData = new FormData(document.getElementById('otp-form'));
                formData.append('_token', csrf_token()); 
                let hash = window.location.pathname.split('/').pop();
                let url = `${site_url}/auth/otp-verify/${hash}`;
                let response = await fetch(url, {
                    method: 'POST',
                    body: formData,
                });
                response = await response.json();
                if(response && response.status)
                { 
                    document.getElementById('otp-form').reset();
                    this.loading = false;
                    set_notification('success', response.message);
                    window.location.href = `${site_url}/auth/recover-password/${hash}`;
                }else{
                    this.loading = false;
                    this.errorMessages = {};
                    for (let field in response.message) {
                        if (Array.isArray(response.message[field])) {
                            this.$set(this.errorMessages, field, response.message[field].join(', '));
                        } else {
                            this.$set(this.errorMessages, field, response.message[field]);
                        }
                    }
                }
            }
            else{
                return false;
            }
        },    
    },
});

if($('#recoverPassword').length)
var recoverPassword = new Vue({
    el: '#recoverPassword',
    data: {
    mounting: true,
    loading: false,
    errorMessages: {} ,
    },
    mounted: function() {
        this.mounting = false;
    },
    methods: {
        recovePassword: async function() {
            if ($('#recover-password-form').valid()) {
                this.loading = true;
                let formData = new FormData(document.getElementById('recover-password-form'));
                formData.append('_token', csrf_token()); 
                let hash = window.location.pathname.split('/').pop();
                let url = `${site_url}/auth/recover-password/${hash}`;
                let response = await fetch(url, {
                    method: 'POST',
                    body: formData,
                });
                response = await response.json();
                if(response && response.status)
                { 
                    document.getElementById('recover-password-form').reset();
                    set_notification('success', response.message);
                    window.location.href = '/login'
                }else{
                    this.errorMessages = {};
                    for (let field in response.message) {
                        if (Array.isArray(response.message[field])) {
                            this.$set(this.errorMessages, field, response.message[field].join(', '));
                        } else {
                            this.$set(this.errorMessages, field, response.message[field]);
                        }
                    }
                }
                this.loading = false;
            }
            else{
                return false;
            }
        },    
    },
});