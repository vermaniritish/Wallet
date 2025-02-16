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
		<span class="badge badge-dot mr-4">
			<i class="bg-warning"></i>
			<span class="status"><?php echo $row->id ?></span>
		</span>
	</td>
	<td>
		<?php echo $row->firstname . ' '. $row->lastname?>
	</td>
	<td>
		<?php echo $row->number ?>
	</td>
	<td>
		<?php echo $row->email ?>
	</td>
	<td>
		<?php echo $row->created ?>
	</td>
	<td class="text-right">
	<?php if(Permissions::hasPermission('contact_us', 'update') || Permissions::hasPermission('contact_us', 'delete')): ?>
		<div class="dropdown">
			<a class="btn btn-sm btn-icon-only text-warning" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
				<i class="fas fa-ellipsis-v"></i>
			</a>
			<div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
				<a class="dropdown-item" href="<?php echo route('admin.contactUs.view', ['id' => $row->id]) ?>">
					<i class="fas fa-eye text-yellow"></i>
					<span class="status">View</span>
				</a>
				<?php if(Permissions::hasPermission('contact_us', 'delete')): ?>
					<div class="dropdown-divider"></div>
					<a 
						class="dropdown-item _delete" 
						href="javascript:;"
						data-link="<?php echo route('admin.contactUs.delete', ['id' => $row->id]) ?>"
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