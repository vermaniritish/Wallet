@extends('layouts.adminlayout')
@section('content')
	<div class="header bg-primary pb-6">
		<div class="container-fluid">
			<div class="header-body">
				<div class="row align-items-center py-4">
					<div class="col-lg-6 col-7">
						<h6 class="h2 text-white d-inline-block mb-0">Manage Shops</h6>
					</div>
					<div class="col-lg-6 col-5 text-right">
						<a href="<?php echo route('admin.shops') ?>" class="btn btn-neutral"><i class="fa fa-arrow-left"></i> Back</a>
						<div class="dropdown" data-toggle="tooltip" data-title="More Actions">
							<a class="btn btn-neutral" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
								<i class="fas fa-ellipsis-v"></i>
							</a>
							<div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
								<a class="dropdown-item" href="<?php echo route('admin.shops.edit', ['id' => $shop->id]) ?>">
									<i class="fas fa-pencil-alt text-info"></i>
									<span class="status">Edit</span>
								</a>
								<div class="dropdown-divider"></div>
								<a 
									class="dropdown-item _delete" 
									href="javascript:;"
									data-link="<?php echo route('admin.shops.delete', ['id' => $shop->id]) ?>"
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
								<h3 class="mb-0">Shop Information</h3>
							</div>
						</div>
					</div>
					<div class="table-responsive">
						<!-- Projects table -->
						<table class="table align-items-center table-flush view-table">
							<tbody>
								<tr>
									<th>Id</th>
									<td><?php echo $shop->id ?></td>
								</tr>
								<tr>
									<th>Name</th>
									<td><a href="<?php echo route('admin.shops.view', ['id' => $shop->id]) ?>"><?php echo $shop->name ?></a></td>
								</tr>
								<tr>
									<th>Owner</th>
									<td>
										<a href="<?php echo route('admin.users.view', ['id' => $shop->id]) ?>"><?php echo $shop->shop_owner->first_name . ' ' . $shop->shop_owner->last_name ?></a>
									</td>
								</tr>
								<tr>
					                <th>Website</th>
					                <td>
					                    @if(!empty($shop->website))
					                        <a href="{{ $shop->website }}" target="_blank">{{ $shop->website }}</a>
					                    @else
					                        <span class="text-muted">No website</span>
					                    @endif
					                </td>
					            </tr>
					            <tr>
					                <th>Social Link</th>
					                <td>
					                    @if(!empty($shop->social_links))
					                        <a href="{{ $shop->social_links }}" target="_blank">Social Link</a>
					                    @else
					                        <span class="text-muted">No social links</span>
					                    @endif
					                </td>
					            </tr>
								<tr>
									<td colspan="2">
										<?php echo $shop->bio ?>
									</td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>
				<div class="card">
					<div class="card-header">
						<div class="row align-items-center">
							<div class="col">
								<h3 class="mb-0">Address Information</h3>
							</div>
						</div>
					</div>
					<div class="table-responsive">
						<!-- Projects table -->
						<table class="table align-items-center table-flush view-table">
							<tbody>
								<tr>
									<th scope="row">
										Address
									</th>
									<td>
										<?php echo $shop->address ?>
									</td>
								</tr>
								<tr>
									<th scope="row">
										Postcode
									</th>
									<td>
										<?php echo $shop->postcode ?>
									</td>
								</tr>
								<tr>
									<th scope="row">
										Map Link
									</th>
									<td>
										
									</td>
								</tr>
								<tr>
									<th scope="row">
										Lattitude
									</th>
									<td>
										<?php echo $shop->lat ?>
									</td>
								</tr>
								<tr>
									<th scope="row">
										Logitude
									</th>
									<td>
										<?php echo $shop->lng ?>
									</td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>
			</div>
			<div class="col-xl-4 order-xl-1">
				<?php if($shop->image): ?>
				<div class="card">
					<div class="card-body">
						@include('admin.partials.viewImage', ['files' => $shop->getResizeImagesAttribute()])
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
										<?php echo $shop->status ? '<span class="badge badge-success">Published</span>' : '<span class="badge badge-danger">Unpublished</span>' ?>
									</td>
								</tr>
								<tr>
									<th scope="row">
										Created By
									</th>
									<td>
										<?php echo isset($shop->owner) ? $shop->owner->first_name . ' ' . $shop->owner->last_name : "-" ?>
									</td>
								</tr>
								<tr>
									<th scope="row">
										Created On
									</th>
									<td>
										<?php echo _dt($shop->created) ?>
									</td>
								</tr>
								<tr>
									<th scope="row">
										Last Modified
									</th>
									<td>
										<?php echo _dt($shop->modified) ?>
									</td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>
				
				<div class="card">
					<div class="card-header">
						<div class="row align-items-center">
							<div class="col">
								<h3 class="mb-0">This Month Visits</h3>
							</div>
							<div class="col text-right">
								<a href="#!" class="btn btn-sm py-2 px-3 btn-primary">See all</a>
							</div>
						</div>
					</div>
					<div class="table-responsive small-max-card-table">
						<!-- Projects table -->
						<table class="table align-items-center table-flush view-table">
							<thead class="thead-light">
								<tr>
									<th scope="col">Date</th>
									<th scope="col">Visitors</th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<th scope="row">
										05-01-2021
									</th>
									<td>
										340
									</td>
								</tr>
								<tr>
									<th scope="row">
										05-01-2021
									</th>
									<td>
										340
									</td>
								</tr>
								<tr>
									<th scope="row">
										05-01-2021
									</th>
									<td>
										340
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