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
                <button class="btn btn-fill-out btn-block mt-30 w-100">Add Money</button>
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