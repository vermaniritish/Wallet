@extends('layouts.adminlayout')
@section('content')
<div class="header bg-primary pb-6">
	<div class="container-fluid">
		<div class="header-body">
			<div class="row align-items-center pt-4 pb-2">
				<div class="col-lg-6 col-7">
					<h6 class="h2 text-white d-inline-block mb-0">Manage Logo Prices</h6>
				</div>
				<div class="col-lg-6 col-5 text-right">
				</div>
			</div>
			<div class="row align-items-center pb-4 pl-3">
			</div>
		</div>
	</div>
</div>
<!-- Page content -->
<div class="container-fluid mt--6">
	<div class="row">
		<div id="logoPrices" class="col-xl-12 order-xl-1">
			<!--!! FLASH MESSAGES !!-->
			@include('admin.partials.flash_messages')
			<form method="POST" action="{{ route('admin.logoPrice.add') }}">
                @csrf
				<div class="card">
					<div class="card-header border-0">
						<div class="heading">
							<h3 class="mb-0">Embroidery</h3>
						</div>
						<div class="actions">
							<button type="button" v-on:click="addRow('embroidered-logo')" class="btn-sm btn-primary"><i class="fas fa-plus"></i> Add</button>
						</div>
					</div>
					<input type="hidden" name="type" value="embroidered-logo">
					<div class="card-body p-0">
						<div class="table-responsive">
							<table class="table align-items-center table-flush listing-table">
								<thead class="thead-light">
									<tr>
										<th>From Quantity</th>
										<th>To Quantity</th>
										<th v-for="(value, key) in logoPositions" :key="value">@{{ key }}</th>
										<th>Action</th>
									</tr>
								</thead>
								<tbody>
									<tr v-if="embroideryDataNotAvailable">
                                        <td colspan="11"><p class="text-center">No information available.</p></td>
                                    </tr>
									<tr v-else v-for="(row, index) in embroideryRows" :key="index">
										<td><input type="number" step=".01" v-model="row.from_quantity" class="form-control" min="1" :name="'embroidered-logo[' + index + '][from_quantity]'"></td>
										<td><input type="number" step=".01" v-model="row.to_quantity" class="form-control" min="1" :name="'embroidered-logo[' + index + '][to_quantity]'"></td>
										<td v-for="position in logoPositions" :key="position">
											<input type="number" step=".01" v-model="row.prices[position]" class="form-control" :name="'embroidered-logo[' + index + '][prices][' + position + ']'">
										</td>
										<td class="text-center" >
											<i v-on:click="removeRow('embroidered-logo', index)" class="fa fa-times"></i>
										</td>
									</tr>
								</tbody>
							</table>
						</div>
						<hr v-if="!embroideryDataNotAvailable" class="my-4" />
						<button v-if="!embroideryDataNotAvailable" type="submit" class="btn btn-sm mb-3 mr-3 py-2 px-3 btn-primary float-right"><i class="fa fa-save"></i> Submit
						</button>
					</div>
				</div>
			</form>
			<form method="POST" action="{{ route('admin.logoPrice.add') }}">
                @csrf
				<div class="card">
					<div class="card-header border-0">
						<div class="heading">
							<h3 class="mb-0">Printing</h3>
						</div>
						<div class="actions">
							<button type="button" v-on:click="addRow('printed-logo')" class="btn-sm btn-primary"><i class="fas fa-plus"></i> Add</button>
						</div>
					</div>
					<input type="hidden" name="type" value="printed-logo">
					<div class="card-body p-0">
						<div class="table-responsive">
							<table class="table align-items-center table-flush listing-table">
								<thead class="thead-light">
									<tr>
										<th>From Quantity</th>
										<th>To Quantity</th>
										<th v-for="(value, key) in logoPositions" :key="value">@{{ key }}</th>
										<th>Action</th>
									</tr>
								</thead>
								<tbody>
									<tr v-if="printingDataNotAvailable">
                                        <td colspan="11"><p class="text-center">No information available.</p></td>
                                    </tr>
									<tr v-else v-for="(row, index) in printingRows" :key="index">
										<td><input type="number" step=".01" v-model="row.from_quantity" class="form-control" min="1" :name="'printed-logo[' + index + '][from_quantity]'"></td>
										<td><input type="number" step=".01" v-model="row.to_quantity" class="form-control" min="1" :name="'printed-logo[' + index + '][to_quantity]'"></td>
										<td v-for="position in logoPositions" :key="position">
											<input type="number" step=".01" v-model="row.prices[position]" class="form-control" :name="'printed-logo[' + index + '][prices][' + position + ']'">
										</td>
										<td class="text-center" >
											<i v-on:click="removeRow('printed-logo', index)" class="fa fa-times"></i>
										</td>
									</tr>
								</tbody>
							</table>
						</div>
						<hr v-if="!printingDataNotAvailable" class="my-4" />
						<button v-if="!printingDataNotAvailable" type="submit" class="btn btn-sm mb-3 mr-3 py-2 px-3 btn-primary float-right"><i class="fa fa-save"></i> Submit
						</button>
					</div>
				</div>
			</form>
		</div>
	</div>
</div>
@endsection