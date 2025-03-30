@extends('layouts.adminlayout')
@section('content')
	<div class="header bg-primary pb-6">
		<div class="container-fluid">
			<div class="header-body">
				<div class="row align-items-center py-4">
					<div class="col-lg-6 col-7">
						<h6 class="h2 text-white d-inline-block mb-0">Manage Customers</h6>
					</div>
					<div class="col-lg-6 col-5 text-right">
						<a href="<?php echo route('admin.users') ?>" class="btn btn-neutral"><i class="fa fa-arrow-left"></i> Back</a>
						<div class="dropdown" data-toggle="tooltip" data-title="More Actions">
							<a class="btn btn-neutral" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
								<i class="fas fa-ellipsis-v"></i>
							</a>
							<div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
								<a class="dropdown-item" href="<?php echo route('admin.users.edit', ['id' => $user->id]) ?>">
									<i class="fas fa-pencil-alt text-info"></i>
									<span class="status">Edit</span>
								</a>
								<div class="dropdown-divider"></div>
								<a 
									class="dropdown-item _delete" 
									href="javascript:;"
									data-link="<?php echo route('admin.users.delete', ['id' => $user->id]) ?>"
								>
									<i class="fas fa-times text-danger"></i>
									<span class="status text-danger">Delete</span>
								</a>
							</div>
						</div>
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
								<h3 class="mb-0">Customer Information</h3>
							</div>
						</div>
					</div>
					<div class="table-responsive">
						<!-- Projects table -->
						<table class="table align-items-center table-flush view-table">
							<tbody>
								<tr>
									<th>Id</th>
									<td><?php echo $user->id ?></td>
								</tr>
								<tr>
									<th>Name</th>
									<td><?php echo $user->first_name . ' ' . $user->last_name ?></td>
								</tr>
								<tr>
									<th>Email</th>
									<td><?php echo $user->email ?></td>
								</tr>
								<tr>
									<th>Phone Number</th>
									<td><?php echo $user->phonenumber ?></td>
								</tr>
								<tr>
									<th>Facebook Account</th>
									<td><?php echo $user->facebook_id ?></td>
								</tr>
								<tr>
									<th>Google Account</th>
									<td><?php echo $user->google_email ?></td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>
				<?php if(Permissions::hasPermission('orders', 'listing')): ?>
					<div class="card listing-block">
						<div class="card-header">
							<div class="row align-items-center">
								<div class="col-md-6">
									<h3 class="mb-0">Customer Orders</h3>
								</div>
								<div class="col-md-4">
									<div class="input-group input-group-alternative input-group-merge">
										<div class="input-group-prepend">
											<span class="input-group-text"><i class="fas fa-search"></i></span>
										</div>
										<input class="form-control listing-search" placeholder="Search" type="text" value="<?php echo (isset($_GET['search']) && $_GET['search'] ? $_GET['search'] : '') ?>">
									</div>
								</div>
								<div class="col-md-2">
								</div>
							</div>
						</div>
						<div class="card-body p-0">
							@include('admin.staff.orders.index',['listing' => $listing])
						</div>
					</div>
				<?php endif; ?>
			</div>
			<div class="col-xl-4 order-xl-1">
				<?php if($user->image): ?>
				<div class="card">
					<div class="card-header">
						@include("admin.users.profile", ["id" => $user->id])
				    </div>
				</div>
				<?php endif; ?>
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
						<table class="table align-items-center table-flush view-table">
							<tbody>
								<tr>
									<th scope="row">
										Status
									</th>
									<td>
										<?php echo $user->status ? '<span class="badge badge-success">Active</span>' : '<span class="badge badge-danger">Inactive</span>' ?>
									</td>
								</tr>
								<tr>
									<th scope="row">
										Created By
									</th>
									<td>
										<?php if(isset($user->owner) && $user->owner): ?>
											<a href="<?php echo route('admin.shops.edit', ['id' => $user->owner->id]) ?>"><?php echo $user->owner->name; ?></a>
										<?php else: ?>
											Shop Owner
										<?php endif; ?>
									</td>
								</tr>
								<tr>
									<th scope="row">
										Created On
									</th>
									<td>
										<?php echo _dt($user->created) ?>
									</td>
								</tr>
								<tr>
									<th scope="row">
										Last Modified
									</th>
									<td>
										<?php echo _dt($user->modified) ?>
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