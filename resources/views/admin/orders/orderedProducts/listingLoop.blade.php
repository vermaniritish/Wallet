<?php foreach($listing->items() as $k => $row): ?>
<tr class="table-borderless">
	<td>
		<div class="custom-control custom-checkbox">
			<input type="checkbox" class="custom-control-input {{$row->shipment_tracking ? '' : 'listing_check'}}" {{$row->shipment_tracking ? 'disabled' : ''}} id="listing_check<?php echo $k ?>" value="<?php echo $row->id ?>">
			<label class="custom-control-label" for="listing_check<?php echo $k ?>"></label>
		</div>
	</td>
	<td>
		<a href="{{ route('admin.products.view', ['id' => $row->product_id]) }}">
			{{ $row->product_id }}
		</a>
	</td>
	<td style="width:50%">
		<?php echo $row->product_title ?><br />
		<p class="m-0 p-0 small">{{ $row->category }} - {{ $row->sub_category }} | Color: {{$row->color }} | Size: {{$row->size_title }}</p>
		<?php if($row->logo_data): ?>
		<p class="m-0 p-0 small text-warning">Customization added worth: {{ $row->logo_cost }}</p>
		<?php endif; ?>
		<?php if($row->non_exchange): ?>
		<p class="m-0 p-0 small text-danger">No Refund and Exchange</p>
		<?php endif; ?>

	</td>
	<td>
		<?php echo $row->quantity ?>
	</td>
    <td>
		<span><?php echo $row->amount ? _currency($row->amount) : _currency(0) ?></span>
		@if($row->logo_data)
		<span class="ml-4" onclick="$(this).parents('tr').next('.table-borderless').toggleClass('d-none')"><i class="fa fa-tags"></i><span>
		@endif
	</td>
</tr>
<?php $logos = $row->logo_data ? (substr($row->logo_data, 0, 1) == '{' ? json_decode('['.$row->logo_data.']') : json_decode($row->logo_data)) : null; ?>
<?php if($logos): ?>
<tr class="table-borderless d-none">
	<td colspan="5" style="padding:0;">
		<table class="table">
			<thead class="thead-light">
			<tr>
				<th width="50%">Title</th>
				<th>Cost</th>
				<th>Qty</th>
				<th>Total</th>
			</tr>
			</thead>
			@foreach($logos as $logo)
			<tr>
				<td>
					{{ $logo->title }}<br /> {{ $logo->description }}
					@if($logo->required)
					<br /><small class="text-danger">Required</small>
					@endif
				</td>
				<td>{{ _currency($logo->cost) }}</td>
				<td>{{ $logo->quantity }}</td>
				<td>{{ _currency($logo->total) }}</td>
			</tr>
			@endforeach
		</table>
	</td>
</tr>
<?php endif; ?>

<?php if($row->shipment_tracking): ?>
<tr>
	<td colspan="5">
		<a target="_blank" href="http://www.parcelforce.com/track-trace?trackNumber={{$row->shipment_tracking}}"><span class="badge badge-success">Shipped: {{$row->shipment_tracking}}</span></a>
	</td>
</tr>
<?php endif; ?>
<tr><td colspan="5" style="padding:0;margin:0;"></td></tr>
<?php endforeach; ?>