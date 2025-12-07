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
    <div class="container py-5">

        <h2 class="text-center mb-4">Create Your Gift Voucher</h2>

        <div class="row g-4">
            <!-- Form Section -->
            <div class="col-md-6">
                <div class="card p-4 shadow">
                    
                    <div class="mb-3">
                        <label class="form-label">Your Name<span class="alert">*</span></label>
                        <input type="text" class="form-control" id="name" placeholder="Enter name">
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Your Email<span class="alert">*</span></label>
                        <input type="email" class="form-control" placeholder="example@email.com">
                    </div>

                    <!-- Receiver Mobile -->
                    <div class="mb-3">
                        <label class="form-label">Your Mobile<span class="alert">*</span></label>
                        <input type="tel" class="form-control" placeholder="Enter mobile number">
                    </div>
                    
                    <h5 class="mb-3 text-brand">Voucher Details</h5>
                
                    <!-- Coupon Amount -->
                    <div class="mb-3">
                        <label class="form-label">Select Voucher Amount<span class="alert">*</span></label>
                        <select class="form-select" id="amount" onchange="updatePreview()">
                            <option value="£500">£10</option>
                            <option value="£1000">£20</option>
                            <option value="£1500">£30</option>
                            <option value="£2000">£50</option>
                            <option value="£2500">£100</option>
                        </select>
                    </div>

                    <!-- Delivery Mode -->
                    <div class="mb-3">
                        <label class="form-label">Delivery Mode</label>
                        <select class="form-select">
                            <option>Email</option>
                            <option>SMS</option>
                            <option>Both</option>
                        </select>
                    </div>

                    <h5 class="mt-4 mb-3 text-brand">Receiver Details</h5>

                    <!-- Receiver Name -->
                    <div class="mb-3">
                        <label class="form-label">Receiver's Name<span class="alert">*</span></label>
                        <input type="text" class="form-control" id="rname" oninput="updatePreview()" placeholder="Enter name">
                    </div>

                    <!-- Receiver Email -->
                    <div class="mb-3">
                        <label class="form-label">Receiver's Email<span class="alert">*</span></label>
                        <input type="email" class="form-control" placeholder="example@email.com">
                    </div>

                    <!-- Receiver Mobile -->
                    <div class="mb-3">
                        <label class="form-label">Receiver's Mobile</label>
                        <input type="tel" class="form-control" placeholder="Enter mobile number">
                    </div>

                    <!-- Message -->
                    <div class="mb-3">
                        <label class="form-label">Message for Receiver (max 200 chars)</label>
                        <textarea class="form-control" id="msg" maxlength="200" oninput="updatePreview()" rows="3"></textarea>
                    </div>

                    <!-- Pay Now -->
                    <button class="btn btn-primary w-100 btn-lg mt-3">
                        Pay Now
                    </button>

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

                    <div class="amount" id="previewAmount">£100</div>

                    <div class="code-box">
                        CODE: <span>GV-XXXX-YYYY</span>
                    </div>
                    <p><span id="previewName">This voucher entitles the bearer to make purchases worth <strong>£100</strong> on our <strong><a href="https://www.pindersschoolwear.com/" target="_blank">website</a></strong> or stores.</span><br><br></p>
                    <p><strong>Issued To:</strong> <span id="previewName">Receiver Name</span></p>
                    <p><strong>Message:</strong> <span id="previewMsg">Your special gift message will appear here.</span></p>
                    <strong>Issued By:</strong> <span id="previewMsg">Gifted By Name </span><br>
                    <strong>Expiry Date:</strong> <span id="previewMsg">31/12/2025 </span><br>

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
<!-- Script to update live preview -->
<script>
function updatePreview() {
    document.getElementById("previewAmount").innerText = document.getElementById("amount").value;

    let name = document.getElementById("rname").value;
    document.getElementById("previewName").innerText = name ? name : "Receiver Name";

    let msg = document.getElementById("msg").value;
    document.getElementById("previewMsg").innerText = msg ? msg : "Your special gift message will appear here.";
}
</script>

@endpush