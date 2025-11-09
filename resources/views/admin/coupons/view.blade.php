@extends('layouts.adminlayout')
@section('content')
	<div class="header bg-primary pb-6">
		<div class="container-fluid">
			<div class="header-body">
				<div class="row align-items-center py-4">
					<div class="col-lg-6 col-7">
						<h6 class="h2 text-white d-inline-block mb-0">Manage Coupons</h6>
					</div>
					<div class="col-lg-6 col-5 text-right">
						<a href="<?php echo route('admin.coupons') ?>" class="btn btn-neutral"><i class="fa fa-arrow-left"></i> Back</a>
						<a href="<?php echo route('admin.coupon.download', ['id' => $page->id]) ?>" class="btn btn-neutral" target="_blank"><i class="fa fa-download"></i> Download</a>
						<?php if(Permissions::hasPermission('coupons', 'update') || Permissions::hasPermission('coupons', 'delete')): ?>
							<div class="dropdown" data-toggle="tooltip" data-title="More Actions">
								<a class="btn btn-neutral" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
									<i class="fas fa-ellipsis-v"></i>
								</a>
								<div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
									<?php /*if(Permissions::hasPermission('coupons', 'update')): ?>
										<a class="dropdown-item" href="<?php echo route('admin.coupons.edit', ['id' => $page->id]) ?>">
											<i class="fas fa-pencil-alt text-info"></i>
											<span class="status">Edit</span>
										</a>
										<div class="dropdown-divider"></div>
									<?php endif;*/ ?>
									<?php if(Permissions::hasPermission('coupons', 'delete')): ?>
										<a 
											class="dropdown-item _delete" 
											href="javascript:;"
											data-link="<?php echo route('admin.coupons.delete', ['id' => $page->id]) ?>"
										>
											<i class="fas fa-times text-danger"></i>
											<span class="status text-danger">Delete</span>
										</a>
									<?php endif; ?>
								</div>
							</div>
						<?php endif; ?>
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
								<h3 class="mb-0">Coupon Information</h3>
							</div>
						</div>
					</div>
					<div class="table-responsive">
						<!-- Projects table -->
						<table class="table align-items-center table-flush">
							<tbody>
								<tr>
									<th>Title</th>
									<td><?php echo $page->title ?></td>
								</tr>
								<tr>
									<th>End Date</th>
									<td><?php echo _d($page->end_date) ?></td>
								</tr>
								<tr>
									<th>Description</th>
									<td><?php echo $page->description ?></td>
								</tr>
								<tr>
									<th>Amount</th>
									<td><?php echo $page->is_percentage ? $page->amount . '%' :  _currency($page->amount) ?></td>
								</tr>
								<tr>
									<th>Minimum Order Amount</th>
									<td><?php echo _currency($page->min_amount) ?></td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>
				<div class="card">
					<div class="card-header">
						<h3 class="mb-0">Coupons</h3>
					</div>
					<div class="table-responsive">
						<table class="table table-bordered table-striped mt-3">
							<thead>
								<tr>
									<th>Code</th>
									<th>Max Usage Limit</th>
									<th>Used</th>
								</tr>
							</thead>
							<tbody>
								@foreach ($coupons as $coupon)
								<tr>
									<td>{{ $coupon->coupon_code }}</td>
									<td>{{ $coupon->max_use }}</td>
									<td>{{ $coupon->used }}</td>
								</tr>
								@endforeach
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
						<!-- Projects table -->
						<table class="table align-items-center table-flush">
							<tbody>
								<tr>
									<th scope="row">
										Status
									</th>
									<td>
										<?php echo $page->status ? '<span class="badge badge-success">Published</span>' : '<span class="badge badge-danger">Unpublished</span>' ?>
									</td>
								</tr>
								<tr>
									<th scope="row">
										Created By
									</th>
									<td>
										<?php echo isset($page->owner) ? $page->owner->first_name . ' ' . $page->owner->last_name : "-" ?>
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
								<tr>
									<th scope="row">
										Last Modified
									</th>
									<td>
										<?php echo _dt($page->modified) ?>
									</td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
@endsection