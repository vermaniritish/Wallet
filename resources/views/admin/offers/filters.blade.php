<div class="dropdown filter-dropdown">
	<a class="btn btn-neutral dropdown-btn" href="#" <?php echo (isset($_GET) && !empty($_GET) ? 'data-title="Filters are active" data-toggle="tooltip"' : '') ?>>
		<?php if(isset($_GET) && !empty($_GET)): ?>
		<span class="filter-dot text-info"><i class="fas fa-circle"></i></span>
		<?php endif; ?>
		<i class="fas fa-filter"></i> Filters
	</a>
	<div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
		<form action="<?php echo route('admin.brands') ?>" id="filters-form">
			<a href="javascript:;" class="float-right px-2 closeit"><i class="fa fa-times-circle"></i></a>
			<div class="dropdown-item">
				<div class="row">
					<div class="col-md-12">
						<label class="form-control-label">Created By</label>
						<select class="form-control" name="admins[]" multiple>
					      	<?php foreach($admins as $c): ?>
					      		<option value="<?php echo $c->id ?>" <?php echo isset($_GET['admins']) && in_array($c->id, $_GET['admins'])  ? 'selected' : '' ?>><?php echo $c->first_name . ' ' . $c->last_name ?></option>
					  		<?php endforeach; ?>
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
			<div class="dropdown-item">
				<div class="row">
					<div class="col-md-12">
						<div class="custom-control custom-radio custom-control-inline">
							<input type="radio" id="active" name="status" value="1" <?php echo (!isset($_GET['status']) || $_GET['status'] === '' || $_GET['status'] === '0' ? 'checked' : '') ?> class="custom-control-input">
							<label class="custom-control-label" for="active">Active</label>
						</div>
						<div class="custom-control custom-radio custom-control-inline">
							<input type="radio" id="nonactive" name="status" value="0" <?php echo (isset($_GET['status']) && $_GET['status'] == '0' ? 'checked' : '') ?> class="custom-control-input">
							<label class="custom-control-label" for="nonactive">Inactive</label>
						</div>
					</div>
				</div>
			</div>
			<div class="dropdown-divider"></div>
			<a href="<?php echo route('admin.brands') ?>" class="btn btn-sm py-2 px-3 float-left">
				Reset All
			</a>
			<button href="#" class="btn btn-sm py-2 px-3 btn-primary float-right">
				Submit
			</button>
		</form>
	</div>
</div>