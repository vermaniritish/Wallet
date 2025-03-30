@extends('layouts.adminlayout')
@section('content')
	<div class="header bg-primary pb-6">
		<div class="container-fluid">
			<div class="header-body">
				<div class="row align-items-center py-4">
					<div class="col-lg-6 col-7">
						<h6 class="h2 text-white d-inline-block mb-0">Manage Products</h6>
					</div>
					<div class="col-lg-6 col-5 text-right">
						<?php if(Permissions::hasPermission('products', 'create')): ?>
						<a href="<?php echo route('admin.products.add') ?>" class="btn btn-neutral"><i class="fas fa-plus"></i> New</a>
						<?php endif; ?>
						@include('admin.products.filters')
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
							<h3 class="mb-0">Here Is Your Products Listing!</h3>
						</div>
						<div class="actions">
							<div class="input-group input-group-alternative input-group-merge">
								<div class="input-group-prepend">
									<span class="input-group-text"><i class="fas fa-search"></i></span>
								</div>
								<input class="form-control listing-search" placeholder="Search" type="text" value="<?php echo (isset($_GET['search']) && $_GET['search'] ? $_GET['search'] : '') ?>">
							</div>
							<?php if(Permissions::hasPermission('products', 'update') || Permissions::hasPermission('products', 'delete')): ?>
							<div class="dropdown" data-toggle="tooltip" data-title="Bulk Actions">
								<a class="btn btn-sm btn-icon-only text-warning" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
									<i class="fas fa-ellipsis-v"></i>
								</a>
								<div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
									<?php if(Permissions::hasPermission('products', 'update')): ?>
									<a 
										class="dropdown-item" 
										href="javascript:;"
										onclick="bulk_actions('<?php echo route('admin.products.bulkActions', ['action' => 'active']) ?>', 'active');"
									>
										<span class="badge badge-dot mr-4">
											<i class="bg-success"></i>
											<span class="status">Publish</span>
										</span>
									</a>
									<a 
										class="dropdown-item" 
										href="javascript:;"
										onclick="bulk_actions('<?php echo route('admin.products.bulkActions', ['action' => 'inactive']) ?>', 'inactive');"
									>
										<span class="badge badge-dot mr-4">
											<i class="bg-warning"></i>
											<span class="status">Unpublish</span>
										</span>
									</a>
									<div class="dropdown-divider"></div>
									<?php endif; ?>
									<?php if(Permissions::hasPermission('products', 'delete')): ?>
		                            <a 
		                            	href="javascript:void(0);" 
		                            	class="waves-effect waves-block dropdown-item text-danger" 
		                            	onclick="bulk_actions('<?php echo route('admin.products.bulkActions', ['action' => 'delete']) ?>', 'delete');">
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
									<th 
										class="checkbox-th"
										width="5%" 
									>
										<div class="custom-control custom-checkbox">
											<input type="checkbox" class="custom-control-input mark_all" id="mark_all">
											<label class="custom-control-label" for="mark_all"></label>
										</div>
									</th>
									<th 
										class="sort"
										width="5%"
									>
										<!--- MAKE SURE TO USE PROPOER FIELD IN data-field AND PROPOER DIRECTION IN data-sort -->
										Id
										<?php if(isset($_GET['sort']) && $_GET['sort'] == 'products.id' && isset($_GET['direction']) && $_GET['direction'] == 'asc'): ?>
										<i class="fas fa-sort-down active" data-field="products.id" data-sort="asc"></i>
										<?php elseif(isset($_GET['sort']) && $_GET['sort'] == 'products.id' && isset($_GET['direction']) && $_GET['direction'] == 'desc'): ?>
										<i class="fas fa-sort-up active" data-field="products.id" data-sort="desc"></i>
										<?php else: ?>
										<i class="fas fa-sort" data-field="products.id" data-sort="asc"></i>
										<?php endif; ?>
									</th>
									<th class="sort" width="8%">
										SKU No.
										<?php if(isset($_GET['sort']) && $_GET['sort'] == 'products.sku_number' && isset($_GET['direction']) && $_GET['direction'] == 'asc'): ?>
										<i class="fas fa-sort-down active" data-field="products.sku_number" data-sort="asc"></i>
										<?php elseif(isset($_GET['sort']) && $_GET['sort'] == 'products.sku_number' && isset($_GET['direction']) && $_GET['direction'] == 'desc'): ?>
										<i class="fas fa-sort-up active" data-field="products.sku_number" data-sort="desc"></i>
										<?php else: ?>
										<i class="fas fa-sort" data-field="products.sku_number"></i>
										<?php endif; ?>
									</th>
									<th class="sort" width="12%">
										Title
										<?php if(isset($_GET['sort']) && $_GET['sort'] == 'products.title' && isset($_GET['direction']) && $_GET['direction'] == 'asc'): ?>
										<i class="fas fa-sort-down active" data-field="products.title" data-sort="asc"></i>
										<?php elseif(isset($_GET['sort']) && $_GET['sort'] == 'products.title' && isset($_GET['direction']) && $_GET['direction'] == 'desc'): ?>
										<i class="fas fa-sort-up active" data-field="products.title" data-sort="desc"></i>
										<?php else: ?>
										<i class="fas fa-sort" data-field="products.title"></i>
										<?php endif; ?>
									</th>								
									<th class="sort" width="8%" >
										Category
										<?php if(isset($_GET['sort']) && $_GET['sort'] == 'products.address' && isset($_GET['direction']) && $_GET['direction'] == 'asc'): ?>
										<i class="fas fa-sort-down active" data-field="products.address" data-sort="asc"></i>
										<?php elseif(isset($_GET['sort']) && $_GET['sort'] == 'products.address' && isset($_GET['direction']) && $_GET['direction'] == 'desc'): ?>
										<i class="fas fa-sort-up active" data-field="products.address" data-sort="desc"></i>
										<?php else: ?>
										<i class="fas fa-sort" data-field="products.address"></i>
										<?php endif; ?>
									</th>
									<th class="sort" width="8%" >
										Price
										<?php if(isset($_GET['sort']) && $_GET['sort'] == 'products.price' && isset($_GET['direction']) && $_GET['direction'] == 'asc'): ?>
										<i class="fas fa-sort-down active" data-field="products.price" data-sort="asc"></i>
										<?php elseif(isset($_GET['sort']) && $_GET['sort'] == 'products.price' && isset($_GET['direction']) && $_GET['direction'] == 'desc'): ?>
										<i class="fas fa-sort-up active" data-field="products.price" data-sort="desc"></i>
										<?php else: ?>
										<i class="fas fa-sort" data-field="products.price"></i>
										<?php endif; ?>
									</th>									
									<th class="sort" width="8%" >
										Status
										<?php if(isset($_GET['sort']) && $_GET['sort'] == 'products.status' && isset($_GET['direction']) && $_GET['direction'] == 'asc'): ?>
										<i class="fas fa-sort-down active" data-field="products.status" data-sort="asc"></i>
										<?php elseif(isset($_GET['sort']) && $_GET['sort'] == 'products.status' && isset($_GET['direction']) && $_GET['direction'] == 'desc'): ?>
										<i class="fas fa-sort-up active" data-field="products.status" data-sort="desc"></i>
										<?php else: ?>
										<i class="fas fa-sort" data-field="products.status"></i>
										<?php endif; ?>
									</th>
									<th class="sort" width="13.3%" >
										Created ON
										<?php if(isset($_GET['sort']) && $_GET['sort'] == 'products.created' && isset($_GET['direction']) && $_GET['direction'] == 'asc'): ?>
										<i class="fas fa-sort-down active" data-field="products.created" data-sort="asc"></i>
										<?php elseif(isset($_GET['sort']) && $_GET['sort'] == 'products.created' && isset($_GET['direction']) && $_GET['direction'] == 'desc'): ?>
										<i class="fas fa-sort-up active" data-field="products.created" data-sort="desc"></i>
										<?php else: ?>
										<i class="fas fa-sort" data-field="products.created"></i>
										<?php endif; ?>
									</th>
									<th width="10%">
										Actions
									</th>
								</tr>
							</thead>
							<tbody class="list">
								<?php if(!empty($listing->items())): ?>
									@include('admin.products.listingLoop')
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