@extends('layouts.frontendlayout')
@section('content')
<div class="page-header breadcrumb-wrap">
    <div class="container">
        <div class="breadcrumb">
            <a href="{{ url('/') }}" rel="nofollow">Home</a>
            <span></span> Gift Voucher
        </div>
    </div>
</div>
<section class="mb-50" id="gift-voucher">
    <div class="container py-3">

        <h2 class="text-center mb-4">Create Your Gift Voucher</h2>

        <div class="row g-4">
            <!-- Form Section -->
            <div class="col-md-6">
                <div id="" class="card p-4 shadow">
                    <div class="mb-3">
                        <label class="form-label">Your Name<span class="alert">*</span></label>
                        <input type="text" class="form-control" v-model="form.name">
                        <small v-if="errors.name" class="text-danger">@{{ errors.name }}</small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Your Email<span class="alert">*</span></label>
                        <input type="email" class="form-control" v-model="form.email">
                        <small v-if="errors.email" class="text-danger">@{{ errors.email }}</small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Your Mobile<span class="alert">*</span></label>
                        <input type="tel" class="form-control" v-model="form.mobile"  @input="form.mobile = form.mobile.replace(/[^0-9]/g, '')"  maxlength="10">
                        <small v-if="errors.mobile" class="text-danger">@{{ errors.mobile }}</small>
                    </div>

                    <h5 class="mb-3 text-brand">Voucher Details</h5>

                    <div class="mb-3">
                        <label class="form-label">Select Voucher Amount<span class="alert">*</span></label>
                        <select class="form-select" v-model="form.amount">
                            <option disabled value="">Select Amount</option>
                            <option value="10">£10</option>
                            <option value="20">£20</option>
                            <option value="30">£30</option>
                            <option value="50">£50</option>
                            <option value="100">£100</option>
                        </select>
                        <small v-if="errors.amount" class="text-danger">@{{ errors.amount }}</small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Delivery Mode</label>
                        <select class="form-select" v-model="form.delivery_mode">
                            <option>Email</option>
                            <option>SMS</option>
                            <option>Both</option>
                        </select>
                    </div>

                    <h5 class="mt-4 mb-3 text-brand">Receiver Details</h5>

                    <div class="mb-3">
                        <label class="form-label">Receiver's Name<span class="alert">*</span></label>
                        <input type="text" class="form-control" v-model="form.receiver_name">
                        <small v-if="errors.receiver_name" class="text-danger">@{{ errors.receiver_name }}</small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Receiver's Email<span class="alert">*</span></label>
                        <input type="email" class="form-control" v-model="form.receiver_email">
                        <small v-if="errors.receiver_email" class="text-danger">@{{ errors.receiver_email }}</small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Receiver's Mobile</label>
                        <input type="tel" class="form-control" v-model="form.receiver_mobile"  @input="form.receiver_mobile = form.receiver_mobile.replace(/[^0-9]/g, '')" maxlength="10">
                        <small v-if="errors.receiver_mobile" class="text-danger">@{{ errors.receiver_mobile }}</small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Message for Receiver (max 200 chars)</label>
                        <textarea class="form-control" maxlength="200" rows="3" v-model="form.message"></textarea>
                        <small v-if="errors.message" class="text-danger">@{{ errors.message }}</small>
                    </div>

                    <!-- <button class="btn btn-primary w-100 btn-lg mt-3"
                            @click="submitForm"
                            :disabled="loading">
                        @{{ loading ? 'Processing...' : 'Pay Now' }}
                    </button> -->

                    <div id="paypal-button-container"></div>

                </div>

            </div>

            <!-- Voucher Preview Section -->
            <div class="col-md-6">
                <h4>Preview:</h4><br/>
                <div class="voucher-preview shadow">

                    <div class="logo">
                        <img src="assets/imgs/theme/logo.jpg" alt="Pinders Schoolwear">
                    </div>
                    
                    <div class="voucher-title">Gift Voucher</div>

                    <div class="amount" id="previewAmount">@{{ form.amount ? `£` + form.amount : '£---'  }}</div>

                    <div class="code-box">
                        CODE: <span>GV-XXXX-YYYY</span>
                    </div>
                    <p><span id="previewName">This voucher entitles the bearer to make purchases worth <strong>@{{ form.amount ? `£` + form.amount : '£---'  }}</strong> on our <strong><a href="https://www.pindersschoolwear.com/" target="_blank">website</a></strong> or stores.</span><br><br></p>
                    <p><strong>Issued To:</strong> <span id="previewName">@{{ form.receiver_name ? `£` + form.receiver_name : 'Receiver Name'  }}</span></p>
                    <p><strong>Message:</strong> <span id="previewMsg">@{{ form.message ? form.message : `Your special gift message will appear here.`}}</span></p>
                    <strong>Issued By:</strong> <span id="previewMsg">@{{ form.name ? `£` + form.name : 'Your Name'  }}</span><br>
                    <strong>Expiry Date:</strong> <span id="previewMsg">{{ date('d/M/Y', strtotime('+1 Year')) }}</span><br>

                    <hr>

                    <p style="font-size: 13px; color: #6c757d;">
                        *This voucher is non-refundable and cannot be exchanged for cash.  
                        Valid for two-time use only. Cannot be combined with other offers.  
                        Terms and conditions apply.
                    </p>

                </div>
            </div>
        </div>
    </div>
</section>
@endsection
@push('scripts')
var loginuseremail = '{{ $user && $user->email ? $user->email : '' }}';
var loginuserphone = '{{ $user && $user->phonenumber ? $user->phonenumber : '' }}';
var initPaypal = function()
{
    if (typeof paypal !== 'undefined' && paypal.Buttons) {
        paypal.Buttons({
            createOrder: async function(data, actions) {
                let response = await voucherApp.submit();
                if(response && response.status && response.voucher_id) {
                    return fetch('{{url("/paypal/create-order")}}', {
                        method: 'post',
                        headers: {
                            'content-type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            amount: response.amount,
                            voucher_id: response.voucher_id
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
                        voucher_id: data.orderID
                    })
                }).then(res => res.json())
                .then(details => {
                    console.log(details);
                    if(details?.status && details?.id) {
                        
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
@endpush