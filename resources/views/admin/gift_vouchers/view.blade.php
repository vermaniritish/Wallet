@extends('layouts.adminlayout')
@section('content')
	<div class="header bg-primary pb-6">
		<div class="container-fluid">
			<div class="header-body">
				<div class="row align-items-center py-4">
					<div class="col-lg-6 col-7">
						<h6 class="h2 text-white d-inline-block mb-0">Gift Vouchers</h6>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- Page content -->
	<div class="container-fluid mt--6">
		<div class="row">
			<div class="col-xl-8 order-xl-1">
				<div class="card">
					<!--!! FLAST MESSAGES !!-->
					@include('admin.partials.flash_messages')
					<div class="card-header">
						<div class="row align-items-center">
							<div class="col-8">
								<h3 class="mb-0">Gift Voucher Information</h3>
							</div>
						</div>
					</div>
					<div class="table-responsive">
						<!-- Projects table -->
						<table class="table align-items-center table-flush">
							<tbody>
								<tr>
									<th>Id</th>
									<td><?php echo $page->id ?></td>
								</tr>
								<tr>
									<th>Code</th>
									<td><span class="badge badge-primary"><?php echo $page->code ?></span></td>
								</tr>
								<tr>
									<th>Sender </th>
									<td>
										<b><?php echo $page->sender_name ?></b><br />
										<?php echo $page->sender_email ?><br />
										<?php echo $page->sender_mobile ?>
									</td>
								</tr>
								<tr>
									<th>Receiver </th>
									<td>
										<b><?php echo $page->receiver_name ?></b><br />
										<?php echo $page->receiver_email ?><br />
										<?php echo $page->receiver_mobile ?>
									</td>
								</tr>
								<tr>
									<th>Amount</th>
									<td><b><?php echo _currency($page->amount) ?></b></td>
								</tr>
								<tr>
									<th>Balance</th>
									<td><b><?php echo _currency($page->amount - $page->applied_amount) ?></b></td>
									@if($page->applied > 1)
									<span class="text-primary">Applied twice, remaining balance cannot be used by customer.</span>
									@endif
								</tr>
								<tr>
									<th>Amount</th>
									<td>{{$page->delivery_mode}}</td>
								</tr>
								<tr>
									<th>Amount</th>
									<td>{{$page->message}}</td>
								</tr>
								<tr>
									<th>Expiry Date</th>
									<td>{{_d($page->expiry_date)}}</td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>
			</div>
			<div class="col-xl-4 order-xl-1">
				<div class="card">
					<div class="card-header">
						<div class="row align-items-center">
							<div class="col">
								<h3 class="mb-0">Other Information</h3>
							</div>
						</div>
					</div>
					<div class="table-responsive">
						<table class="table align-items-center table-flush">
							<tbody>
								<tr>
									<th scope="row">
										Status
									</th>
									<td>
										@if( $page->status && $page->status == 'pending' && strtotime($page->created) < strtotime(date('Y-m-d 00:00:01')) )
										<span class="text-danger">Failed</span>
										@elseif( $page->status && $page->status == 'completed')
										<span class="text-success">Completed</span>
										@else
										<span class="text-warning">Pending</span>
										@endif
									</td>
								</tr>
								<tr>
									<th scope="row">
										Registerd User
									</th>
									<td>
										<?php echo isset($page->user) ? $page->user->first_name . ' ' . $page->user->last_name : "-" ?>
									</td>
								</tr>
								<tr>
									<th scope="row">
										Created On
									</th>
									<td>
										<?php echo _dt($page->created) ?>
									</td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>
				<?php $payDetails = $page->paypal_payment_data ? json_decode($page->paypal_payment_data) : null; ?>
				@if($payDetails)
				<div class="card">
					<div class="card-header">
						<div class="row align-items-center">
							<div class="col">
								<h3 class="mb-0">Payment Details</h3>
							</div>
						</div>
					</div>
					<div class="card-body p-0 m-0">
						<table class="table align-items-center table-flush">
							<tbody>
								@if($payDetails)
								<tr>
									<th>Paypal Transaction No.</th>
									<td><span>{{ $payDetails && $payDetails->id ? $payDetails->id : '' }}</span></td>
								</tr>
								<tr>
									<th>Paypal Intent</th>
									<td><span>{{ $payDetails && $payDetails->intent ? $payDetails->intent : '' }}</span></td>
								</tr>
								<tr>
									<th>Paypal Status</th>
									<td><span>{{ $payDetails && $payDetails->status ? $payDetails->status : '' }}</span></td>
								</tr>
								<tr>
									<th>Paypal Response</th>
									<td><code style="
										max-width: 200px;
										overflow: scroll;
										display: block;
										word-break: break-all;
										text-wrap: auto;
										height: 200px;
									">{{ $page->paypal_payment_data }}</code></td>
								</tr>
								@endif
							</tbody>
						</table>
					</div>
				</div>
				@endif
			</div>
		</div>
	</div>
@endsection