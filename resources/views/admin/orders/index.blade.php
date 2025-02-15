@extends('layouts.adminlayout')
@section('content')
	<div class="header bg-primary pb-6">
		<div class="container-fluid">
			<div class="header-body">
				<div class="row align-items-center py-4">
					<div class="col-lg-6 col-7">
						<h6 class="h2 text-white d-inline-block mb-0">Manage Orders</h6>
					</div>
					<div class="col-lg-6 col-5 text-right">
						<a href="<?php echo route('admin.orders.add') ?>" target="_blank"  class="btn btn-neutral">
						<i class="fas fa-plus"></i> New</a>
                        <a href="{{route('admin.orders.exportOrders')}}"  class="btn btn-neutral">
                            <i class="fas fa-file-export"></i> Export</a>
						@include('admin.orders.filters')
					</div>
				</div>
			</div>
			<form id="filterForm" action="{{ route('admin.orders') }}" method="get">
				<input type="hidden" name="status" id="statusInput" value="">
			</form>
			<div class="row">
				<div class="col-md-12 mb-3">
					<ul class="nav nav-pills">
						<li class="nav-item">
							<a class="nav-link{{ empty(request('status')) ? ' active' : '' }}" id="all-tab" data-toggle="pill" href="#all" onclick="submitForm('')">All</a>
						</li>
						@foreach($status as $statusKey => $statusData)
							<li class="nav-item">
								<a class="nav-link{{ request('status') === $statusKey ? ' active' : '' }}" data-value="{{ $statusKey }}" id="{{ strtolower($statusKey) }}-tab" data-toggle="pill" href="#{{ strtolower($statusKey) }}" onclick="submitForm('{{ $statusKey }}')">{{ $statusData['label'] }}</a>
							</li>
						@endforeach
					</ul>
					<div class="tab-content">
						<!-- Your tab content goes here -->
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- Page content -->
	<div class="container-fluid mt--6">
		<div class="row">
			<div class="col">
				<!--!!!!! DO NOT REMOVE listing-block CLASS. INCLUDE THIS IN PARENT DIV OF TABLE ON LISTING PAGES !!!!!-->
				<div class="card listing-block">
					<!--!! FLAST MESSAGES !!-->
					@include('admin.partials.flash_messages')
					<!-- Card header -->
					<div class="card-header border-0">
						<div class="heading">
							<h3 class="mb-0">Here Is Your Orders Listing!</h3>
						</div>
						<div class="actions">
							<div class="input-group input-group-alternative input-group-merge">
								<div class="input-group-prepend">
									<span class="input-group-text"><i class="fas fa-search"></i></span>
								</div>
								<input class="form-control listing-search" placeholder="Search" type="text" value="<?php echo (isset($_GET['search']) && $_GET['search'] ? $_GET['search'] : '') ?>">
							</div>
							<?php if(Permissions::hasPermission('orders', 'update') || Permissions::hasPermission('orders', 'delete')): ?>
								<div class="dropdown" data-toggle="tooltip" data-title="Bulk Actions">
									<a class="btn btn-sm btn-icon-only text-warning" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
										<i class="fas fa-ellipsis-v"></i>
									</a>
									<div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
										<?php if(Permissions::hasPermission('orders', 'update')): ?>
											<?php foreach ($status as $statusKey => $statusData): ?>
													<a
														class="dropdown-item"
														href="javascript:;"
														onclick="bulk_actions('<?php echo route('admin.orders.bulkActions', ['action' => $statusKey]) ?>');"
													>
												<?php
													$badgeClass = '';
													switch ($statusKey) {
														case 'pending':
															$badgeClass = 'bg-danger';
															break;
														case 'accepted':
															$badgeClass = 'bg-info';
															break;
														case 'on_the_way':
															$badgeClass = 'bg-light';
															break;
														case 'reached_at_location':
															$badgeClass = 'bg-dark';
															break;
														case 'in_progress':
															$badgeClass = 'bg-warning';
															break;
														case 'completed':
															$badgeClass = 'bg-success';
															break;
														case 'cancel':
															$badgeClass = 'bg-danger';
															break;
														default:
															$badgeClass = 'bg-secondary';
															break;
													}
												?>
												<span class="badge badge-dot mr-4">
													<i class="<?php echo $badgeClass; ?>"></i>
													<span class="status">{{ $statusData['label'] }}</span>
												</span>
												</a>
												<div class="dropdown-divider"></div>
											<?php endforeach; ?>
										<?php endif; ?>
										<?php if(Permissions::hasPermission('orders', 'delete')): ?>
											<a
												href="javascript:void(0);"
												class="waves-effect waves-block dropdown-item text-danger"
												onclick="bulk_actions('<?php echo route('admin.orders.bulkActions', ['action' => 'delete']) ?>', 'delete');">
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
					<!--!!!!! DO NOT REMOVE listing-table, mark_all  CLASSES. INCLUDE THIS IN ALL TABLES LISTING PAGES !!!!!-->
						<table class="table align-items-center table-flush listing-table">
							<thead class="thead-light">
								<tr>
									<th class="checkbox-th" width="5%">
										<div class="custom-control custom-checkbox">
											<input type="checkbox" class="custom-control-input mark_all" id="mark_all">
											<label class="custom-control-label" for="mark_all"></label>
										</div>
									</th>
									<th class="sort" width="5%">
										<!--- MAKE SURE TO USE PROPOER FIELD IN data-field AND PROPOER DIRECTION IN data-sort -->
										Id
										<?php if(isset($_GET['sort']) && $_GET['sort'] == 'orders.id' && isset($_GET['direction']) && $_GET['direction'] == 'asc'): ?>
										<i class="fas fa-sort-down active" data-field="orders.id" data-sort="asc"></i>
										<?php elseif(isset($_GET['sort']) && $_GET['sort'] == 'orders.id' && isset($_GET['direction']) && $_GET['direction'] == 'desc'): ?>
										<i class="fas fa-sort-up active" data-field="orders.id" data-sort="desc"></i>
										<?php else: ?>
										<i class="fas fa-sort" data-field="orders.id" data-sort="asc"></i>
										<?php endif; ?>
									</th>
									<th class="sort" width="20%">
										Customer Name
										<?php if(isset($_GET['sort']) && $_GET['sort'] == 'orders.customer_name' && isset($_GET['direction']) && $_GET['direction'] == 'asc'): ?>
										<i class="fas fa-sort-down active" data-field="orders.customer_name" data-sort="asc"></i>
										<?php elseif(isset($_GET['sort']) && $_GET['sort'] == 'orders.customer_name' && isset($_GET['direction']) && $_GET['direction'] == 'desc'): ?>
										<i class="fas fa-sort-up active" data-field="orders.customer_name" data-sort="desc"></i>
										<?php else: ?>
										<i class="fas fa-sort" data-field="orders.customer_name"></i>
										<?php endif; ?>
									</th>
									<th class="sort" width="15%">
										City
										<?php if(isset($_GET['sort']) && $_GET['sort'] == 'orders.city' && isset($_GET['direction']) && $_GET['direction'] == 'asc'): ?>
										<i class="fas fa-sort-down active" data-field="orders.city" data-sort="asc"></i>
										<?php elseif(isset($_GET['sort']) && $_GET['sort'] == 'orders.city' && isset($_GET['direction']) && $_GET['direction'] == 'desc'): ?>
										<i class="fas fa-sort-up active" data-field="orders.city" data-sort="desc"></i>
										<?php else: ?>
										<i class="fas fa-sort" data-field="orders.city"></i>
										<?php endif; ?>
									</th>
									<th class="sort" width="10%">
										Amount
										<?php if(isset($_GET['sort']) && $_GET['sort'] == 'orders.total_amount' && isset($_GET['direction']) && $_GET['direction'] == 'asc'): ?>
										<i class="fas fa-sort-down active" data-field="orders.total_amount" data-sort="asc"></i>
										<?php elseif(isset($_GET['sort']) && $_GET['sort'] == 'orders.total_amount' && isset($_GET['direction']) && $_GET['direction'] == 'desc'): ?>
										<i class="fas fa-sort-up active" data-field="orders.total_amount" data-sort="desc"></i>
										<?php else: ?>
										<i class="fas fa-sort" data-field="orders.total_amount"></i>
										<?php endif; ?>
									</th>
									<th class="sort" width="10%">
										Status
										<?php if(isset($_GET['sort']) && $_GET['sort'] == 'orders.status' && isset($_GET['direction']) && $_GET['direction'] == 'asc'): ?>
										<i class="fas fa-sort-down active" data-field="orders.status" data-sort="asc"></i>
										<?php elseif(isset($_GET['sort']) && $_GET['sort'] == 'orders.status' && isset($_GET['direction']) && $_GET['direction'] == 'desc'): ?>
										<i class="fas fa-sort-up active" data-field="orders.status" data-sort="desc"></i>
										<?php else: ?>
										<i class="fas fa-sort" data-field="orders.status"></i>
										<?php endif; ?>
									</th>
									<th class="sort" width="15%">
										Created ON
										<?php if(isset($_GET['sort']) && $_GET['sort'] == 'orders.created' && isset($_GET['direction']) && $_GET['direction'] == 'asc'): ?>
										<i class="fas fa-sort-down active" data-field="orders.created" data-sort="asc"></i>
										<?php elseif(isset($_GET['sort']) && $_GET['sort'] == 'orders.created' && isset($_GET['direction']) && $_GET['direction'] == 'desc'): ?>
										<i class="fas fa-sort-up active" data-field="orders.created" data-sort="desc"></i>
										<?php else: ?>
										<i class="fas fa-sort" data-field="orders.created"></i>
										<?php endif; ?>
									</th>
									<th width="5%">
										Actions
									</th>
								</tr>
							</thead>
							<tbody class="list">
								<?php if(!empty($listing->items())): ?>
									@include('admin.orders.listingLoop')
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
		</div>
	</div>
@endsection
