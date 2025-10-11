<?php
use App\Models\Admin\Permissions;
?>
<?php foreach($listing->items() as $k => $row): if(!$row->colors) continue; ?>
<tr>
	<td class="text-center" >
		<span style="background-color: {{ $row->colors->color_code }}; color: #444;" class="badge badge-secondary">{{ $row->colors->title }}</span>
	</td>
	<td class="text-center" >
		<?php echo $row->size_title ?>
	</td>
	<td class="text-center" >
		<?php echo $row->from_cm ?>
	</td>
    <td class="text-center" >
		<?php echo $row->to_cm ?>
	</td>
    <td class="text-center" >
		<?php echo $row->price ? _currency($row->price) : _currency(0) ?>
	</td>
	<td class="text-center" >
		<?php echo $row->sale_price ? _currency($row->sale_price) : _currency(0) ?>
	</td>
</tr>
<?php endforeach; ?>