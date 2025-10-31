<?php foreach($listing->items() as $k => $row): ?>
<tr>
	<td>
		<!-- MAKE SURE THIS HAS ID CORRECT AND VALUES CORRENCT. THIS WILL EFFECT ON BULK CRUTIAL ACTIONS -->
		<div class="custom-control custom-checkbox">
			<input type="checkbox" class="custom-control-input listing_check" id="listing_check<?php echo $row->id ?>" value="<?php echo $row->id ?>">
			<label class="custom-control-label" for="listing_check<?php echo $row->id ?>"></label>
		</div>
	</td>
	<td>
		<?php echo $row->coupon_code ?>
	</td>
	<td>
		<?php echo $row->title ?>
	</td>
	<td>
		<?php echo $row->max_use ?>
	</td>
	<td>
		<?php echo _d($row->end_date) ?>
	</td>
	<td>
		<?php echo _dt($row->created) ?>
	</td>
	<td class="text-right">
		<?php if(Permissions::hasPermission('coupons', 'update') || Permissions::hasPermission('coupons', 'delete')): ?>
			<div class="dropdown">
				<a class="btn btn-sm btn-icon-only text-warning" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
					<i class="fas fa-ellipsis-v"></i>
				</a>
				<div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
					<a class="dropdown-item" href="<?php echo route('admin.coupons.view', ['id' => $row->id]) ?>">
						<i class="fas fa-eye text-yellow"></i>
						<span class="status">View</span>
					</a>
					<?php if(Permissions::hasPermission('coupons', 'delete')): ?>
						<div class="dropdown-divider"></div>
						<a 
							class="dropdown-item _delete" 
							href="javascript:;"
							data-link="<?php echo route('admin.coupons.delete', ['id' => $row->id]) ?>"
						>
							<i class="fas fa-times text-danger"></i>
							<span class="status text-danger">Delete</span>
						</a>
					<?php endif; ?>
				</div>
			</div>
		<?php endif; ?>
	</td>
</tr>
<?php endforeach; ?>