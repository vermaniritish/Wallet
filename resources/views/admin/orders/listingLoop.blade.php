<?php
	use App\Models\Admin\Settings;
	$currency = Settings::get('currency_symbol'); 
?>
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
			<span class="status"><?php echo $row->prefix_id ?></span>
		</span>
	</td>
	<td>
		<?php echo $row->first_name . ' ' . $row->last_name; ?>
		<?php echo 'Email: ' . $row->customer_email; ?>
		<?php echo 'Phone: ' . $row->customer_phone; ?>
	</td>
	<td>
		<?php echo $row->shop_id ? $row->shop_name : "Online" ?>
	</td>
	<td>
		<?php echo $row->city ?>
	</td>
	<td>
		<?php echo $row->total_amount ? _currency($row->total_amount) : _currency(0) ?>
	</td>
	<td>
	<div class="dropdown">
		<?php $statusData = $status[$row->status] ?? null; ?>
		<?php if ($statusData): ?>
			<button class="btn btn-sm dropdown-toggle" style="<?php echo $statusData['styles']; ?>"
					type="button" id="statusDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"
					data-toggle="tooltip" title="{{ $row->statusBy ? ($row->statusBy->first_name . ($row->statusBy->last_name ? ' ' . $row->statusBy->last_name : '')) : null }}">
				{{ $statusData['label'] }}
			</button>
			<input type="hidden" id="Currentstatus" value={{ $row->status }} >
			<div class="dropdown-menu dropdown-menu-left" aria-labelledby="statusDropdown">
				<?php $switchUrl = route('admin.order.switchStatus', ['field' => 'status', 'id' => $row->id]); ?>
				<?php foreach ($status as $statusKey => $statusData): ?>
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
					<a class="dropdown-item" href="javascript:;" data-value="<?php echo $statusKey; ?>" 
						onclick="switch_diary_page_action('<?php echo $switchUrl; ?>', this)"
					>
						<span class="badge badge-dot mr-4">
							<i class="<?php echo $badgeClass; ?>"></i>
							<span class="status">{{ $statusData['label'] }}</span>
						</span>
					</a>
				<?php endforeach; ?>
			</div>
		<?php endif; ?>
	</div>
	</td>
	<td>
		<?php echo _dt($row->created) ?>
	</td>
	<td class="text-right">
	<?php if(Permissions::hasPermission('orders', 'update') || Permissions::hasPermission('orders', 'delete')): ?>
		<div class="dropdown">
			<a class="btn btn-sm btn-icon-only text-warning" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
				<i class="fas fa-ellipsis-v"></i>
			</a>
			<div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
				<a class="dropdown-item" href="<?php echo route('admin.orders.view', ['id' => $row->id]) ?>">
					<i class="fas fa-eye text-yellow"></i>
					<span class="status">View</span>
				</a>
			</div>
		</div>
	<?php endif; ?>
	</td>
</tr>
<?php endforeach; ?>