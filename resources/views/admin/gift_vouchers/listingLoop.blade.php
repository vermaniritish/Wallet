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
		<?php echo $row->code ?>
	</td>
	<td>
		<b><?php echo $row->sender_name ?></b><br />
		<?php echo $row->sender_email ?><br />
		<?php echo $row->sender_mobile ?>
	</td>
	<td>
		<b><?php echo $row->receiver_name ?></b><br />
		<?php echo $row->receiver_email ?><br />
		<?php echo $row->receiver_mobile ?>
	</td>
	<td>
		@if( $row->status && $row->status == 'pending' && strtotime($row->created) < strtotime(date('Y-m-d 00:00:01')) )
		<span class="text-danger">Failed</span>
		@elseif( $row->status && $row->status == 'completed')
		<span class="text-success">Completed</span>
		@else
		<span class="text-warning">Pending</span>
		@endif
	</td>
	<td>
		<?php echo _dt($row->created) ?>
	</td>
	<td class="text-right">
		<a class="dropdown-item" href="<?php echo route('admin.gift_voucher.view', ['id' => $row->id]) ?>">
			<i class="fas fa-eye"></i>
			<span class="status">View</span>
		</a>
	</td>
</tr>
<?php endforeach; ?>