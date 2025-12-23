@extends('layouts.frontendlayout')
@section('content')
<div class="page-header breadcrumb-wrap">
    <div class="container">
        <div class="breadcrumb">
            <a href="{{ url('/') }}" rel="nofollow">Home</a>
            <span></span> Hi! {{$user->first_name}} {{$user->last_name}}
        </div>
    </div>
</div>
<section class="pt-50 pb-150">
    <div class="container">
        <div class="row">
            <div class="col-lg-10 m-auto">
                <div class="row">
                    <div class="col-md-4">
                        @include('frontend.account.aside')
                    </div>
                    <div class="col-md-8">
                        <div class="dashboard-content">
                            @if(isset($screen) && $screen == 'dashboard')
                                @include('frontend.account.dashboard', ['screen' => 'dashboard', 'user' => $user])
                            @endif
                            @if(isset($screen) && $screen == 'orders')
                                @include('frontend.account.orders', ['screen' => 'orders'])
                            @endif
                            @if(isset($screen) && $screen == 'track-order')
                                @include('frontend.account.trackorder', ['screen' => 'track-order'])
                            @endif
                            @if(isset($screen) && $screen == 'address')
                                @include('frontend.account.address', ['screen' => 'address'])
                            @endif
                            @if(isset($screen) && $screen == 'account')
                                @include('frontend.account.account', ['screen' => 'account'])
                            @endif

                            @if(isset($screen) && $screen == 'wallet')
                                @include('frontend.account.wallet', ['screen' => 'wallet'])
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
@if(isset($screen) && $screen == 'wallet')
@push('afterscripts')
<script>
var walletApp = new Vue({
    el: '#walletApp',
    data: {
        amounts: [20, 50, 100, 500, 1000],
        selectedAmount: 0,
        customAmount: null,
        showCustom: false,
        error: ''
    },
    computed: {
        finalAmount() {
            return this.showCustom ? (this.customAmount || 0) : this.selectedAmount;
        }
    },
    methods: {
        selectAmount(amount) {
            this.selectedAmount = amount;
            this.customAmount = null;
            this.showCustom = false;
            this.error = '';
        },
        toggleCustom() {
            this.showCustom = !this.showCustom;
            this.selectedAmount = 0;
            this.customAmount = null;
            this.error = '';
        },
        validateCustomAmount() {
            if (this.customAmount < 20) {
                this.error = 'Minimum amount is €20 need to add.';
            } else {
                this.error = '';
            }
        },
        async submitForm() {
            if (this.finalAmount < 20) {
                this.error = 'Minimum amount is €20 need to add.';
                return;
            }
            let response = await fetch('{{ url("/wallet") }}', {
                method: 'post',
                headers: {
                    'content-type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    amount: this.finalAmount,
                })
            });
            response = await response.json();
            if(response && response.status && response.token)
            {
                return response;
            }
            else
            {
                set_notification('error', response.message);
                return null;
            }
        }
    }
});
var initPaypal = function()
{
    if (typeof paypal !== 'undefined' && paypal.Buttons) {
        console.log('yes');
        paypal.Buttons({
            createOrder: async function(data, actions) {
                let response = await walletApp.submitForm();
                if(response && response.status && response.token) {
                    return fetch('{{url("/paypal/create-order")}}', {
                        method: 'post',
                        headers: {
                            'content-type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            amount: response.amount,
                            wallet_id: response.token
                        })
                    }).then(res => {
                        return res.json();
                    })
                    .then(orderData => orderData?.result?.id || null);
                }
                return Promise.reject(new Error('API request failed'));
            },
            onApprove: function(data, actions) {
                return fetch('{{ url("/paypal/capture-order")}}', {
                    method: 'post',
                    headers: {
                        'content-type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        wallet_id: data.orderID
                    })
                }).then(res => res.json())
                .then(details => {
                    if(details?.status && details?.id) {
                        window.location.reload();
                    } else {
                        if(details && !details.status && details.message) {
                            set_notification('error', details.message);
                        }
                        else {
                            set_notification('error', 'Payment could not be processed. Please try again.');
                        }
                    }
                });
            }
        }).render('#paypal-button-container');
    } else {
        console.error("PayPal SDK failed to load.");
    }
}
initPaypal();
</script>

@endpush
@endif