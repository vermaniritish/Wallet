<div class="tab-pane fade active show" id="track-orders">
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Orders Tracking</h5>
        </div>
        <div class="card-body contact-from-area">
            <p>To track your order please enter your OrderID in the box below and press "Track" button. This was given to you on your receipt and in the confirmation email you should have received.</p>
            <div class="row">
                <div class="col-lg-8">
                    <form class="contact-form-style mt-30 mb-50">
                        <div class="input-style mb-20">
                            <label>Order ID</label>
                            <input name="orderid" placeholder="Found in your order confirmation email" type="text" class="square" required>
                        </div>
                        <div class="input-style mb-20">
                            <label>Billing email</label>
                            <input name="email" placeholder="Email you used during checkout" type="email" class="square" required>
                        </div>
                        <button class="submit submit-auto-width" type="submit">Track</button>
                    </form>
                </div>
                <div class="col-lg-12">
                    @if($order && $order->shipment_tracking)
                    <div>
                        <p><strong>No. of Parcels:</strong> {{ $order->parcels }}</p>
                        <p><strong>Shipment Gateway:</strong> {{ $order->shipping_gateway == 'parcelforce' ? 'Parcel Force' : 'DPD' }}</p>
                        <p><strong>Shipment Tracking Number:</strong> {{ $order->shipment_tracking }}</p>
                    </div>
                    @elseif($order)
                    <div class="alert alert-danger"><p>Order is not shiped yet. Once order is shipped the tracking number will be updated.</p></div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>