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
					<?php if(Permissions::hasPermission('coupons', 'create')): ?>
						<a href="<?php echo route('admin.coupons.add') ?>" class="btn btn-neutral"><i class="fas fa-plus"></i> New</a>
					<?php endif; ?>	
						@include('admin.coupons.filters')
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
							<h3 class="mb-0">Here Is Your Coupons Listing!</h3>
						</div>
						<div class="actions">
							<div class="input-group input-group-alternative input-group-merge">
								<div class="input-group-prepend">
									<span class="input-group-text"><i class="fas fa-search"></i></span>
								</div>
								<input class="form-control listing-search" placeholder="Search" type="text" value="<?php echo (isset($_GET['search']) && $_GET['search'] ? $_GET['search'] : '') ?>">
							</div>
							<?php if(Permissions::hasPermission('coupons', 'update') || Permissions::hasPermission('coupons', 'delete')): ?>
								<div class="dropdown" data-toggle="tooltip" data-title="Bulk Actions">
									<a class="btn btn-sm btn-icon-only text-warning" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
										<i class="fas fa-ellipsis-v"></i>
									</a>
									<div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
										<?php if(Permissions::hasPermission('coupons', 'update')): ?>
											<a 
												class="dropdown-item" 
												href="javascript:;"
												onclick="bulk_actions('<?php echo route('admin.coupons.bulkActions', ['action' => 'active']) ?>', 'active');"
											>
												<span class="badge badge-dot mr-4">
													<i class="bg-success"></i>
													<span class="status">Active</span>
												</span>
											</a>
											<a 
												class="dropdown-item" 
												href="javascript:;"
												onclick="bulk_actions('<?php echo route('admin.coupons.bulkActions', ['action' => 'inactive']) ?>', 'inactive');"
											>
												<span class="badge badge-dot mr-4">
													<i class="bg-warning"></i>
													<span class="status">Inactive</span>
												</span>
											</a>
											<div class="dropdown-divider"></div>
										<?php endif; ?>
										<?php if(Permissions::hasPermission('coupons', 'delete')): ?>
											<a 
												href="javascript:void(0);" 
												class="waves-effect waves-block dropdown-item text-danger" 
												onclick="bulk_actions('<?php echo route('admin.coupons.bulkActions', ['action' => 'delete']) ?>', 'delete');">
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
									<th width="5%" class="checkbox-th">
										<div class="custom-control custom-checkbox">
											<input type="checkbox" class="custom-control-input mark_all" id="mark_all">
											<label class="custom-control-label" for="mark_all"></label>
										</div>
									</th>
									
									<th width="15%" class="sort">
										Title
										<?php if(isset($_GET['sort']) && $_GET['sort'] == 'coupons.title' && isset($_GET['direction']) && $_GET['direction'] == 'asc'): ?>
										<i class="fas fa-sort-down active" data-field="coupons.title" data-sort="asc"></i>
										<?php elseif(isset($_GET['sort']) && $_GET['sort'] == 'coupons.title' && isset($_GET['direction']) && $_GET['direction'] == 'desc'): ?>
										<i class="fas fa-sort-up active" data-field="coupons.title" data-sort="desc"></i>
										<?php else: ?>
										<i class="fas fa-sort" data-field="coupons.title"></i>
										<?php endif; ?>
									</th>

									<th width="15%" class="sort">
										School
										<?php if(isset($_GET['sort']) && $_GET['sort'] == 'school_name' && isset($_GET['direction']) && $_GET['direction'] == 'asc'): ?>
										<i class="fas fa-sort-down active" data-field="school_name" data-sort="asc"></i>
										<?php elseif(isset($_GET['sort']) && $_GET['sort'] == 'school_name' && isset($_GET['direction']) && $_GET['direction'] == 'desc'): ?>
										<i class="fas fa-sort-up active" data-field="school_name" data-sort="desc"></i>
										<?php else: ?>
										<i class="fas fa-sort" data-field="school_name"></i>
										<?php endif; ?>
									</th>
									
									<th width="10%" class="sort">
										Max Use
										<?php if(isset($_GET['sort']) && $_GET['sort'] == 'coupons.max_use' && isset($_GET['direction']) && $_GET['direction'] == 'asc'): ?>
										<i class="fas fa-sort-down active" data-field="coupons.max_use" data-sort="asc"></i>
										<?php elseif(isset($_GET['sort']) && $_GET['sort'] == 'coupons.max_use' && isset($_GET['direction']) && $_GET['direction'] == 'desc'): ?>
										<i class="fas fa-sort-up active" data-field="coupons.max_use" data-sort="desc"></i>
										<?php else: ?>
										<i class="fas fa-sort" data-field="coupons.max_use"></i>
										<?php endif; ?>
									</th>
									<th width="12.5%" class="sort">
										Expiry Date
										<?php if(isset($_GET['sort']) && $_GET['sort'] == 'coupons.end_date' && isset($_GET['direction']) && $_GET['direction'] == 'asc'): ?>
										<i class="fas fa-sort-down active" data-field="coupons.end_date" data-sort="asc"></i>
										<?php elseif(isset($_GET['sort']) && $_GET['sort'] == 'coupons.end_date' && isset($_GET['direction']) && $_GET['direction'] == 'desc'): ?>
										<i class="fas fa-sort-up active" data-field="coupons.end_date" data-sort="desc"></i>
										<?php else: ?>
										<i class="fas fa-sort" data-field="coupons.end_date"></i>
										<?php endif; ?>
									</th>
									<th width="12.5%" class="sort">
										Created ON
										<?php if(isset($_GET['sort']) && $_GET['sort'] == 'coupons.created' && isset($_GET['direction']) && $_GET['direction'] == 'asc'): ?>
										<i class="fas fa-sort-down active" data-field="coupons.created" data-sort="asc"></i>
										<?php elseif(isset($_GET['sort']) && $_GET['sort'] == 'coupons.created' && isset($_GET['direction']) && $_GET['direction'] == 'desc'): ?>
										<i class="fas fa-sort-up active" data-field="coupons.created" data-sort="desc"></i>
										<?php else: ?>
										<i class="fas fa-sort" data-field="coupons.created"></i>
										<?php endif; ?>
									</th>
									<th width="5%">
										Actions
									</th>
								</tr>
							</thead>
							<tbody class="list">
								<?php if(!empty($listing->items())): ?>
									@include('admin.coupons.listingLoop')
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