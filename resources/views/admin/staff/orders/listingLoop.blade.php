<?php
use App\Models\Admin\Settings;
$currency = Settings::get('currency_symbol'); 

 foreach($listing->items() as $k => $row): ?>
<tr>
	<td>
		<a href="<?php echo route('admin.orders.view', ['id' => $row->id]) ?>"><?php echo $row->prefix_id; ?></a>
	</td>
	<td>
		{{$currency}} {{$row->total_amount }}
	</td>
	<td>
		<?php $statusData = $status[$row->status] ?? null; ?>
		<?php if ($statusData): ?>
			<button class="btn btn-sm" style="<?php echo $statusData['styles']; ?>"
					type="button" id="statusDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"
					data-toggle="tooltip" title="{{ $row->statusBy ? ($row->statusBy->first_name . ($row->statusBy->last_name ? ' ' . $row->statusBy->last_name : '')) : null }}">
				{{ $statusData['label'] }}
			</button>
		<?php endif; ?>
	</td>
    <td>
	{{ _dt($row->created) }}
	</td>
</tr>
<?php endforeach; ?>