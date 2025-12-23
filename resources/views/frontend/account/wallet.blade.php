<?php use App\Models\Admin\Orders; ?>
<div class="tab-pane fade active show" id="orders">
    <div class="card w-50">
        <div class="card-header">
            <h5 class="mb-0">Add Money to Your Wallet</h5>
        </div>
        <div class="card-body">
            <div class="d-flex flex-row align-items-center justify-content-between">
                <div><i style="font-size: 40px;" class="fas fa-wallet"></i></div>
                <div>
                    <p class="strong mb-1">Balance</p>
                    <h4>{{ _currency($user->wallet ? $user->wallet : 0) }}</h4>
                </div>
            </div>
            <div class="d-flex flex-row">
                <button type="button" class="btn btn-fill-out btn-block mt-30 w-100" data-bs-toggle="modal" data-bs-target="#addMoneyModal">Add Money</button>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Wallet</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Amount</th>
                            <th>Mode</th>
                            <th>Payment Status</th>
                            <th>Created</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if($wallet->count() > 0)
                            <?php foreach($wallet as $o): ?>
                            <tr>
                                <td>#{{$o->id}}</td>
                                <td>{{ _currency($o->amount) }}</td>
                                <td>
                                    @if($o->mode == 'add')
                                        <span class="text-success">Added</span>
                                    @else
                                        <span class="text-danger">Deducted</span>
                                    @endif
                                </td>
                                <td>
                                    @if($o->payment_status == 'paid')
                                        <span class="text-success">Paid/</span>
                                    @else
                                        <span class="text-danger">Pending / Failed</span>
                                    @endif
                                </td>
                                <td>{{_dt($o->created)}}</td>
                            </tr>
                            <?php endforeach; ?>
                        @else
                            <tr><td colspan="6">You have not added any amount in your wallet!</td></tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Add Money Modal -->
<div class="modal fade" id="addMoneyModal" tabindex="-1" aria-labelledby="addMoneyLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addMoneyLabel">Add Money to Wallet</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <!-- Amount Buttons -->
                <div class="d-flex flex-wrap gap-2 mb-3">
                    <button class="btn btn-outline-primary amount-btn" data-amount="20">+20</button>
                    <button class="btn btn-outline-primary amount-btn" data-amount="50">+50</button>
                    <button class="btn btn-outline-primary amount-btn" data-amount="100">+100</button>
                    <button class="btn btn-outline-primary amount-btn" data-amount="500">+500</button>
                    <button class="btn btn-outline-primary amount-btn" data-amount="1000">+1000</button>
                </div>

                <!-- Custom Amount -->
                <div class="mb-3">
                    <label class="form-label">Custom Amount</label>
                    <input type="number" min="1" class="form-control" id="customAmount" placeholder="Enter amount">
                </div>

                <!-- Selected Amount -->
                <div class="alert alert-info py-2">
                    Amount to Add: <strong>₹<span id="finalAmount">0</span></strong>
                </div>
            </div>

            <div class="modal-footer">
                <button class="btn btn-success w-100" id="addMoneyBtn">
                    Add Money
                </button>
            </div>
        </div>
    </div>
</div>

@push('stack')
<script>
    let selectedAmount = 0;

    document.querySelectorAll('.amount-btn').forEach(btn => {
        btn.addEventListener('click', function () {
            selectedAmount = parseInt(this.dataset.amount);
            document.getElementById('customAmount').value = '';
            updateAmount();

            document.querySelectorAll('.amount-btn').forEach(b => b.classList.remove('active'));
            this.classList.add('active');
        });
    });

    document.getElementById('customAmount').addEventListener('input', function () {
        selectedAmount = parseInt(this.value || 0);
        document.querySelectorAll('.amount-btn').forEach(b => b.classList.remove('active'));
        updateAmount();
    });

    function updateAmount() {
        document.getElementById('finalAmount').innerText = selectedAmount;
    }

    document.getElementById('addMoneyBtn').addEventListener('click', function () {
        if (selectedAmount <= 0) {
            alert('Please select or enter an amount');
            return;
        }

        // TODO: Laravel API / Payment Gateway Call
        console.log('Adding Amount:', selectedAmount);

        // Example: axios.post('/wallet/add', { amount: selectedAmount })
    });
</script>

@endpush