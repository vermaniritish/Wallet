
<div class="dropdown filter-dropdown">
	<a class="btn btn-neutral dropdown-btn" href="#" <?php echo (isset($_GET) && !empty($_GET) ? 'data-title="Filters are active" data-toggle="tooltip"' : '') ?>>
		<?php if(isset($_GET) && !empty($_GET)): ?>
		<span class="filter-dot text-info"><i class="fas fa-circle"></i></span>
		<?php endif; ?>
		<i class="fas fa-filter"></i> Filters
	</a>
	<div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
		<form action="<?php echo route('admin.orders') ?>" id="filters-form">
			<a href="javascript:;" class="float-right px-2 closeit"><i class="fa fa-times-circle"></i></a>
			<div class="dropdown-item">
				<div class="row">
					<div class="col-md-12">
						<label class="form-control-label">Status</label>
						<select class="form-control" name="status">
							<option value="">All</option>
					      	@foreach($status as $statusKey => $statusData)
					      		<option value="{{ $statusKey }}" {{ (isset($_GET['status']) && $_GET['status'] == $statusKey ? 'selected' : '') }}>{{ $statusData['label'] }}</option>
					  		@endforeach
					    </select>
					</div>
				</div>
			</div>
			<div class="dropdown-divider"></div>
			<div class="dropdown-item">
				<div class="row">
					<div class="col-md-12">
						<label class="form-control-label">Source</label>
						<select class="form-control" name="source">
							<option value="">All</option>
							<option value="shop" {{ (isset($_GET['status']) && $_GET['status'] == 'shop' ? 'selected' : '') }}>Shop</option>
							<option value="website" {{ (isset($_GET['status']) && $_GET['status'] == 'website' ? 'selected' : '') }}>Website</option>
					    </select>
					</div>
				</div>
			</div>
			<div class="dropdown-divider"></div>
			<div class="dropdown-item">
				<div class="row">
					<div class="col-md-12">
						<label class="form-control-label">Coupon Code</label>
						<input type="text" class="form-control" name="coupon" placeholder="Enter Code" value="{{ (isset($_GET['coupon']) && $_GET['coupon'] ? $_GET['coupon'] : '') }}" />
					</div>
				</div>
			</div>
			<div class="dropdown-divider"></div>
			<div class="dropdown-item">
				<div class="row">
					<div class="col-md-12">
						<label class="form-control-label">Shipping</label>
						<select class="form-control" name="shipping">
							<option value="">All</option>
							<option {{ (isset($_GET['shipping']) && $_GET['shipping'] == 'parcelforce' ? 'selected' : '') }} value="parcelforce">Ship by Parcel Force</option>
							<option {{ (isset($_GET['shipping']) && $_GET['shipping'] == 'dpd' ? 'selected' : '') }} value="dpd">Ship by DPD</option>
							<option value="Ship to School" {{ (isset($_GET['shipping']) && $_GET['shipping'] == 'Ship to School' ? 'selected' : '') }}>Ship to School</option>
							@foreach($shops as $k => $s)
							<option value="{{$s->name}}" {{ (isset($_GET['shipping']) && $_GET['shipping'] == $s->name ? 'selected' : '') }} data-subtext="{{ ($s->status ? 'Active' : 'Inactive') }}">Collect From {{$s->name}}</option>
							@endforeach
							
					    </select>
					</div>
				</div>
			</div>
			<div class="dropdown-divider"></div>
			<div class="dropdown-item">
				<div class="row">
					<div class="col-md-12">
						<label class="form-control-label">Schools</label>
						<select class="form-control" name="schools[]" multiple>
							<option value="">All</option>
							@foreach($schools as $k => $s)
							<option value="{{$s->id}}" {{ (isset($_GET['schools']) && in_array($s->id, $_GET['schools']) ? 'selected' : '') }} data-subtext="{{ ($s->status ? 'Active' : 'Inactive') }}">{{$s->name}} - {{$s->schooltype}}</option>
							@endforeach							
					    </select>
					</div>
				</div>
			</div>
			<div class="dropdown-divider"></div>
			
			<div class="dropdown-item">
				<div class="row">
					<div class="col-md-6">
						<label class="form-control-label">Created On</label>
						<input class="form-control" type="date" name="created_on[0]" value="<?php echo (isset($_GET['created_on'][0]) && !empty($_GET['created_on'][0]) ? $_GET['created_on'][0] : '' ) ?>" placeholder="DD-MM-YYYY" >
					</div>
					<div class="col-md-6">
						<label class="form-control-label">&nbsp;</label>
						<input class="form-control" type="date" name="created_on[1]" value="<?php echo (isset($_GET['created_on'][1]) && !empty($_GET['created_on'][1]) ? $_GET['created_on'][1] : '' ) ?>" placeholder="DD-MM-YYYY">
					</div>
				</div>
			</div>
			<div class="dropdown-divider"></div>
			<a href="<?php echo route('admin.orders') ?>" class="btn btn-sm py-2 px-3 float-left">
				Reset All
			</a>
			<button href="#" class="btn btn-sm py-2 px-3 btn-primary float-right">
				Submit
			</button>
		</form>
	</div>
</div>