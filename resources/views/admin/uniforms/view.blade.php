<?php
use App\Models\Admin\Permissions;
use App\Models\Admin\Settings;
	$currency = Settings::get('currency_symbol'); 
?>
@extends('layouts.adminlayout')
@section('content')
	<div class="header bg-primary pb-6">
		<div class="container-fluid">
			<div class="header-body">
				<div class="row align-items-center py-4">
					<div class="col-lg-6 col-7">
						<h6 class="h2 text-white d-inline-block mb-0">Manage Uniforms</h6>
					</div>
					<div class="col-lg-6 col-5 text-right">
						<a href="<?php echo route('admin.uniforms') ?>" class="btn btn-neutral"><i class="fa fa-arrow-left"></i> Back</a>
						<a href="<?php echo url($product->slug) ?>" class="btn btn-neutral" target="_blank"><i class="fa fa-eye"></i> View Product</a>
						<?php if($product->size_file): ?>
						<a href="<?php echo url($product->size_file) ?>" target="_blank" class="btn btn-neutral" target="_blank"><i class="fa fa-download"></i> View Size Guide</a>
						<?php endif; ?>
						<div class="dropdown" data-toggle="tooltip" data-title="More Actions">
							<a class="btn btn-neutral" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
								<i class="fas fa-ellipsis-v"></i>
							</a>
							<div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
								<a class="dropdown-item" href="<?php echo route('admin.uniforms.edit', ['id' => $product->id]) ?>">
									<i class="fas fa-pencil-alt text-info"></i>
									<span class="status">Edit</span>
								</a>
								<div class="dropdown-divider"></div>
								<a 
									class="dropdown-item _delete" 
									href="javascript:;"
									data-link="<?php echo route('admin.uniforms.delete', ['id' => $product->id]) ?>"
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
								<h3 class="mb-0">Uniform Information</h3>
							</div>
						</div>
					</div>
					<div class="table-responsive">
						<!-- Projects table -->
						<table class="table align-items-center table-flush view-table">
							<tbody>
								<tr>
									<th width="30%">Id</th>
									<td><?php echo $product->id ?></td>
								</tr>
								<tr>
									<th>Product</th>
								<td><a href="{{ route('admin.products.view', ['id' => $product->parent_id]) }}"><?php echo $product->parent->title ?></a></td>
								</tr>
								<tr>
									<th>Title</th>
									<td><?php echo $product->title ?></td>
								</tr>
								<tr>
									<th>School</th>
									<td><?php echo $product->school ? $product->school->name . ' - ' . $product->school->schooltype : '' ?></td>
								</tr>
								<tr>
									<th>Color</th>
									<td>
										<?php 
											if(isset($product->colors) && $product->colors ): 
												foreach ($product->colors as $color):
												echo '<span class="badge badge-warning">'.$color->title . ' (' . $color->color_code . ')'.'</span> ';
												endforeach;
											endif; 
										?>
									</td>
								</tr>
								<tr>
									<th>SKU Number</th>
									<td><?php echo $product->parent->sku_number ?></td>
								</tr>
								<tr>
									<th>Price</th>
									<td><?php echo $product->price ? _currency($product->price) : currency() ?></td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>
				<div class="card listing-block">
					<div class="card-header">
						<div class="row align-items-center">
							<div class="col-md-8">
								<h3 class="mb-0">Uniform Size's</h3>
							</div>
							<div class="col-md-4">
								<div class="input-group input-group-alternative input-group-merge">
									<div class="input-group-prepend">
										<span class="input-group-text"><i class="fas fa-search"></i></span>
									</div>
									<input class="form-control listing-search" placeholder="Search" type="text" value="<?php echo (isset($_GET['search']) && $_GET['search'] ? $_GET['search'] : '') ?>">
								</div>
							</div>
						</div>
					</div>
					<div class="card-body p-0">
						@include('admin.products.productSizes.index',['listing' => $listing])
					</div>
				</div>
			</div>
			<div class="col-xl-4 order-xl-1">
				<?php if($product->image): ?>
				<div class="card">
					<div class="card-header">
						@include('admin.partials.viewImage', ['files' => $product->getResizeImagesAttribute()])
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
										<?php echo $product->status ? '<span class="badge badge-success">Published</span>' : '<span class="badge badge-danger">Unpublished</span>' ?>
									</td>
								</tr>
								<tr>
									<th scope="row">
										Created By
									</th>
									<td>
										<?php if(isset($product->owner) && $product->owner): ?>
											<a href="<?php echo route('admin.users.view', ['id' => $product->created_by]) ?>"><?php echo $product->owner->name; ?></a>
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
										<?php echo _dt($product->created) ?>
									</td>
								</tr>
								<tr>
									<th scope="row">
										Last Modified
									</th>
									<td>
										<?php echo _dt($product->modified) ?>
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