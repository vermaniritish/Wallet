<?php use App\Models\Admin\Orders; ?>
<div class="tab-pane fade active show" id="orders">
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Your Orders</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Order</th>
                            <th>Date</th>
                            <th>Payment Status</th>
                            <th>Fulfillment Status</th>
                            <th>Total</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if($orders->count() > 0)
                            <?php foreach($orders as $o): ?>
                            <tr>
                                <td>#{{$o->prefix_id}}</td>
                                <td>{{_dt($o->created)}}</td>
                                <td><span class="{{ $o->paid ? 'text-success' : 'text-danger'}}">{{ $o->paid ? 'Paid' : 'Unpaid'}}</span></td>
                                <td><span class="badge" style="{{ Orders::getStatuses($o->status)['styles'] }}" >{{ Orders::getStatuses($o->status)['label'] }}</td>
                                <td>{{ _currency($o->total_amount) }}</td>
                                <td><a href="{{ route('invoice', ['id' => $o->prefix_id]) }}" target="_blank" class="btn-small d-block">View</a></td>
                            </tr>
                            <?php endforeach; ?>
                        @else
                            <tr><td colspan="6">You have not placed any order yet!</td></tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>