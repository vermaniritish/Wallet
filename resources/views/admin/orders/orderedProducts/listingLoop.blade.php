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
	<td>
		<?php echo $row->product_title ?>
		
	</td>
	<td>
		<?php echo $row->quantity ?>
	</td>
    <td>
		<?php echo $row->amount ? _currency($row->amount) : _currency(0) ?>
	</td>
</tr>
<?php 
pr($row->logo_data);
$logos = $row->logo_data ? (substr($row->logo_data, 0, 1) == '{' ? json_decode('['.$row->logo_data.']') : json_decode($row->logo_data)) : []; 
pr($logos);
?>
@foreach($logos as $logo)
<?php if(!( (isset($logo->text) && $logo->text) || $logo->image || $logo->category || $logo->postion)) continue; ?>
<tr class="table-borderless">
	<td></td>
	<td colspan="4">
		<div class="row">
			@if(isset($logo->already_uploaded) && $logo->already_uploaded)
			<div class="col-sm-2">
				<p class="text-danger">Pinder has already my logo.</p>
			</div>
			@else			
			<div class="col-sm-2">
				<div class="" style="width: 80px;height:80px;border: 1px solid #ddd;">
					<?php if(trim($logo->image)):?>
					<img src="{{ $logo->image }}" style="max-width:100%;max-height:100%;">
					<?php endif; ?>
				</div>
			</div>
			@endif
			<div class="col-sm-5">
				<span class="text-muted">Text:</span> <?php echo $logo->text ?><br />
				<span class="text-muted">Category:</span> <?php echo $logo->category ?><br />
				<span class="text-muted">Position:</span> <?php echo $logo->postion ?><br />
			</div>
			<div class="col-sm-4">
				<span class="text-muted">Size:</span> <?php echo $row->size_title ?><br />
				<span class="text-muted">Color:</span> <?php echo $row->color ?><br />
			</div>
		</div>
	</td>
</tr>
@endforeach
@foreach($logos as $logo)
<?php if(!($logo->title)) continue; ?>
<tr class="table-borderless d-none">
	<td colspan="5" style="padding:0;">
		<table class="table">
			<thead class="thead-light">
			<tr>
				<th width="30%">Title</th>
				<th width="30%">Initial</th>
				<th>Cost</th>
				<th>Qty</th>
				<th>Total</th>
			</tr>
			</thead>
			@foreach($logos as $logo)
			<tr>
				<td>{{ $logo->title }}</td>
				<td>{{ $logo->initial }}</td>
				<td>{{ _currency($logo->cost) }}</td>
				<td>{{ $row->quantity }}</td>
				<td>{{ _currency($logo->cost * $row->quantity) }}</td>
			</tr>
			@endforeach
		</table>
	</td>
</tr>
@endforeach

<?php /*if($row->shipment_tracking): ?>
<tr>
	<td colspan="5">
		<a target="_blank" href="http://www.parcelforce.com/track-trace?trackNumber={{$row->shipment_tracking}}"><span class="badge badge-success">Shipped: {{$row->shipment_tracking}}</span></a>
	</td>
</tr>
<?php endif;*/ ?>
<tr><td colspan="5" style="padding:0;margin:0;"></td></tr>
<?php endforeach; ?>