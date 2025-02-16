<?php
use App\Models\Admin\Settings;
$freeDelivery = Settings::get('free_delivery'); 
$freeDelivery = $freeDelivery ? json_decode($freeDelivery, true) : null;
$freeLogo = Settings::get('free_logo_discount'); 
$freeLogo = $freeLogo ? json_decode($freeLogo, true) : null;
?>
@extends('layouts.adminlayout')
@section('content')
	<div class="header bg-primary pb-6">
		<div class="container-fluid">
			<div class="header-body">
				<div class="row align-items-center py-4">
					<div class="col-lg-6 col-7">
						<h6 class="h2 text-white d-inline-block mb-0">Manage Offers</h6>
					</div>
					<div class="col-lg-6 col-5 text-right">
					<?php if(Permissions::hasPermission('offers', 'create')): ?>
						<a href="<?php echo route('admin.offers.add') ?>" class="btn btn-neutral"><i class="fas fa-plus"></i> New</a>
					<?php endif; ?>	
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- Page content -->
	<div class="container-fluid mt--6">
		
		<div class="row">
			<div class="col-md-7">
				<div class="card listing-block">
					<!--!! FLAST MESSAGES !!-->
					@include('admin.partials.flash_messages')
					<!-- Card header -->
					<div class="card-header border-0">
						<div class="heading">
							<h3 class="mb-0">Free Offers</h3>
						</div>
						<div class="actions">
							<div class="input-group input-group-alternative input-group-merge">
								<div class="input-group-prepend">
									<span class="input-group-text"><i class="fas fa-search"></i></span>
								</div>
								<input class="form-control listing-search" placeholder="Search" type="text" value="<?php echo (isset($_GET['search']) && $_GET['search'] ? $_GET['search'] : '') ?>">
							</div>
							<?php if(Permissions::hasPermission('offers', 'update') || Permissions::hasPermission('offers', 'delete')): ?>
								<div class="dropdown" data-toggle="tooltip" data-title="Bulk Actions">
									<a class="btn btn-sm btn-icon-only text-warning" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
										<i class="fas fa-ellipsis-v"></i>
									</a>
									<div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
										<?php if(Permissions::hasPermission('offers', 'update')): ?>
											<a 
												class="dropdown-item" 
												href="javascript:;"
												onclick="bulk_actions('<?php echo route('admin.offers.bulkActions', ['action' => 'active']) ?>', 'active');"
											>
												<span class="badge badge-dot mr-4">
													<i class="bg-success"></i>
													<span class="status">Active</span>
												</span>
											</a>
											<a 
												class="dropdown-item" 
												href="javascript:;"
												onclick="bulk_actions('<?php echo route('admin.offers.bulkActions', ['action' => 'inactive']) ?>', 'inactive');"
											>
												<span class="badge badge-dot mr-4">
													<i class="bg-warning"></i>
													<span class="status">Inactive</span>
												</span>
											</a>
											<div class="dropdown-divider"></div>
										<?php endif; ?>
										<?php if(Permissions::hasPermission('offers', 'delete')): ?>
											<a 
												href="javascript:void(0);" 
												class="waves-effect waves-block dropdown-item text-danger" 
												onclick="bulk_actions('<?php echo route('admin.offers.bulkActions', ['action' => 'delete']) ?>', 'delete');">
													<i class="fas fa-times text-danger"></i>
													<span class="status text-danger">Delete</span>
											</a>
										<?php endif; ?>
									</div>
								</div>
							<?php endif; ?>
						</div>
					</div>
					<div class="table-responsive">
						<table class="table align-items-center table-flush listing-table">
							<thead class="thead-light">
								<tr>
									<th width="5%" class="checkbox-th">
										<div class="custom-control custom-checkbox">
											<input type="checkbox" class="custom-control-input mark_all" id="mark_all">
											<label class="custom-control-label" for="mark_all"></label>
										</div>
									</th>
									<th class="sort" width="10%">
										<!--- MAKE SURE TO USE PROPOER FIELD IN data-field AND PROPOER DIRECTION IN data-sort -->
										Id
										<?php if(isset($_GET['sort']) && $_GET['sort'] == 'offers.id' && isset($_GET['direction']) && $_GET['direction'] == 'asc'): ?>
										<i class="fas fa-sort-down active" data-field="offers.id" data-sort="asc"></i>
										<?php elseif(isset($_GET['sort']) && $_GET['sort'] == 'offers.id' && isset($_GET['direction']) && $_GET['direction'] == 'desc'): ?>
										<i class="fas fa-sort-up active" data-field="offers.id" data-sort="desc"></i>
										<?php else: ?>
										<i class="fas fa-sort" data-field="offers.id" data-sort="asc"></i>
										<?php endif; ?>
									</th>
									<th class="sort" width="26.6%">
										Title
										<?php if(isset($_GET['sort']) && $_GET['sort'] == 'offers.title' && isset($_GET['direction']) && $_GET['direction'] == 'asc'): ?>
										<i class="fas fa-sort-down active" data-field="offers.title" data-sort="asc"></i>
										<?php elseif(isset($_GET['sort']) && $_GET['sort'] == 'offers.title' && isset($_GET['direction']) && $_GET['direction'] == 'desc'): ?>
										<i class="fas fa-sort-up active" data-field="offers.title" data-sort="desc"></i>
										<?php else: ?>
										<i class="fas fa-sort" data-field="offers.title"></i>
										<?php endif; ?>
									</th>
									<th class="sort" width="26.6%">
										Status
										<?php if(isset($_GET['sort']) && $_GET['sort'] == 'offers.status' && isset($_GET['direction']) && $_GET['direction'] == 'asc'): ?>
										<i class="fas fa-sort-down active" data-field="offers.status" data-sort="asc"></i>
										<?php elseif(isset($_GET['sort']) && $_GET['sort'] == 'offers.status' && isset($_GET['direction']) && $_GET['direction'] == 'desc'): ?>
										<i class="fas fa-sort-up active" data-field="offers.status" data-sort="desc"></i>
										<?php else: ?>
										<i class="fas fa-sort" data-field="offers.status"></i>
										<?php endif; ?>
									</th>
									<th class="sort" width="26.6%">
										Created ON
										<?php if(isset($_GET['sort']) && $_GET['sort'] == 'offers.created' && isset($_GET['direction']) && $_GET['direction'] == 'asc'): ?>
										<i class="fas fa-sort-down active" data-field="offers.created" data-sort="asc"></i>
										<?php elseif(isset($_GET['sort']) && $_GET['sort'] == 'offers.created' && isset($_GET['direction']) && $_GET['direction'] == 'desc'): ?>
										<i class="fas fa-sort-up active" data-field="offers.created" data-sort="desc"></i>
										<?php else: ?>
										<i class="fas fa-sort" data-field="offers.created"></i>
										<?php endif; ?>
									</th>
									<th width="5%">
										Actions
									</th>
								</tr>
							</thead>
							<tbody class="list">
								<?php if(!empty($listing->items())): ?>
									@include('admin.offers.listingLoop')
								<?php else: ?>
									<td align="left" colspan="7">
		                            	No records found!
		                            </td>
								<?php endif; ?>
							</tbody>
							<tfoot>
		                        <tr>
		                            <th align="left" colspan="20">
		                            	@include('admin.partials.pagination', ["pagination" => $listing])
		                            </th>
		                        </tr>
		                    </tfoot>
						</table>
					</div>
					<!-- Card footer -->
				</div>
			</div>
			<div class="col-md-5">
				<div class="row">
					<div class="col-md-12">
						<div class="card">
							<div class="card-header border-0">
								<div class="heading">
									<h3 class="mb-0">Free Delivery</h3>
								</div>
							</div>
							<div class="card-body">
								<form action="{{ route('admin.settings.freeDelivery') }}" method="post">
								{{ @csrf_field() }}
								<p>Please fill in minimum cart value to apply free delivery.</p>
								<div class="row">
									<div class="col-lg-12">
										<div class="form-group">
											<div class="custom-control">
												<label class="custom-toggle">
													<input type="hidden" name="free_delivery" value="0">
													<input type="checkbox" name="free_delivery" value="1" {{ $freeDelivery ? 'checked' : ''}}>
													<span class="custom-toggle-slider rounded-circle" data-label-off="No" data-label-on="Yes"></span>
												</label>
												<label class="custom-control-label">Active / Inactive Offer?</label>
											</div>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-lg-12 delivery-fields {{ !$freeDelivery ? 'd-none' : '' }}">
										<div class="form-group">
											<label class="form-control-label" for="input-first-name">Mininum cart value.</label>
											<input type="number" class="form-control" name="min_cart_price" required placeholder="2000" value="{{ isset($freeDelivery['min_cart_price']) && $freeDelivery['min_cart_price'] ? $freeDelivery['min_cart_price'] : '' }}">
										</div>
									</div>
									<div class="col-lg-12 text-right">
										<button type="submit" class="btn btn-primary">Submit</button>
									</div>
								</div>
								</form>
							</div>
						</div>
					</div>
					<div class="col-md-12">
						<div class="card">
							<div class="card-header border-0">
								<div class="heading">
									<h3 class="mb-0">Free Logos Offer</h3>
								</div>
							</div>
							<div class="card-body">
							<form action="{{ route('admin.settings.freeLogo') }}" method="post">
							{{ @csrf_field() }}
								<p>Please fill in minimum cart value and minimum logos quantity to apply free logos offer.</p>
								<div class="row">
									<div class="col-lg-12">
										<div class="form-group">
											<div class="custom-control">
												<label class="custom-toggle">
													<input type="hidden" name="free_logo" value="0">
													<input type="checkbox" name="free_logo" value="1"  {{ $freeLogo ? 'checked' : ''}}>
													<span class="custom-toggle-slider rounded-circle" data-label-off="No" data-label-on="Yes"></span>
												</label>
												<label class="custom-control-label">Active / Inactive Offer?</label>
											</div>
										</div>
									</div>
								</div>
								<div class="row free-logo-fields {{ !$freeLogo ? 'd-none' : '' }}">
									<div class="col-lg-6">
										<div class="form-group">
											<label class="form-control-label" for="input-first-name">Mininum cart value.</label>
											<input type="number" class="form-control" name="min_cart_price" required placeholder="5000" value="{{ $freeLogo ? $freeLogo['min_cart_price'] : '' }}">
										</div>
									</div>
									<div class="col-lg-6">
										<div class="form-group">
											<label class="form-control-label" for="input-first-name">Number of logo to offer.</label>
											<input type="number" class="form-control" name="quantity" required placeholder="2" value="{{ $freeLogo ? $freeLogo['quantity'] : '' }}">
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-lg-12 text-right">
										<button class="btn btn-primary">Submit</button>
									</div>
								</div>
								</form>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
@endsection