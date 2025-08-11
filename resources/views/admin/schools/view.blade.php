@extends('layouts.adminlayout')
@section('content')
	<div class="header bg-primary pb-6">
		<div class="container-fluid">
			<div class="header-body">
				<div class="row align-items-center py-4">
					<div class="col-lg-6 col-7">
						<h6 class="h2 text-white d-inline-block mb-0">Manage School</h6>
					</div>
					<div class="col-lg-6 col-5 text-right">
						<a href="<?php echo route('admin.schools') ?>" class="btn btn-neutral"><i class="fa fa-arrow-left"></i> Back</a>
						<?php if(Permissions::hasPermission('schools', 'update') || Permissions::hasPermission('schools', 'delete')): ?>
							<div class="dropdown" data-toggle="tooltip" data-title="More Actions">
								<a class="btn btn-neutral" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
									<i class="fas fa-ellipsis-v"></i>
								</a>
								<div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
									<?php if(Permissions::hasPermission('schools', 'update')): ?>
										<a class="dropdown-item" href="<?php echo route('admin.schools.edit', ['id' => $shool->id]) ?>">
											<i class="fas fa-pencil-alt text-info"></i>
											<span class="status">Edit</span>
										</a>
										<?php endif; ?>
									<?php if(Permissions::hasPermission('schools', 'delete')): ?>
										<div class="dropdown-divider"></div>
										<a 
											class="dropdown-item _delete" 
											href="javascript:;"
											data-link="<?php echo route('admin.schools.delete', ['id' => $shool->id]) ?>"
										>
											<i class="fas fa-times text-danger"></i>
											<span class="status text-danger">Delete</span>
										</a>
									<?php endif; ?>
								</div>
							</div>
						<?php endif; ?>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- shool content -->
	<div class="container-fluid mt--6">
    <div class="row">
        <!-- Left Column -->
        <div class="col-xl-8 order-xl-1">
            <div class="card">
                @include('admin.partials.flash_messages')
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col-8">
                            <h3 class="mb-0">School Information</h3>
                        </div>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table align-items-center table-flush">
                        <tbody>
                            <tr><th>ID</th><td>{{ $shool->id }}</td></tr>
                            <tr><th>Type</th><td>{{ $shool->schooltype }}</td></tr>
                            <tr><th>Name</th><td>{{ $shool->name }}</td></tr>
                            <tr><th>Address</th><td>{{ $shool->address }}</td></tr>
                            <tr><th>City</th><td>{{ $shool->city }}</td></tr>
                            <tr><th>Country</th><td>{{ $shool->country }}</td></tr>
                            <tr><th>Phone</th><td>{{ $shool->phone }}</td></tr>
                            <tr><th>Email</th><td>{{ $shool->email }}</td></tr>
                            <tr><th>Shipping Charges</th><td>{{ number_format($shool->shipping_charges, 2) }}</td></tr>
                            <tr><th>House Names</th><td>{{ $shool->house_names }}</td></tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Right Column -->
        <div class="col-xl-4 order-xl-1">
            @if($shool->icon)
            <div class="card mb-3">
                <div class="card-header"><h4 class="mb-0">Icon</h4></div>
                <div class="card-body text-center">
                    <img src="{{ url($shool->icon) }}" class="img-fluid rounded" alt="Icon">
                </div>
            </div>
            @endif

            @if($shool->logo)
            <div class="card mb-3">
                <div class="card-header"><h4 class="mb-0">Logo</h4></div>
                <div class="card-body text-center">
                    <img src="{{ url($shool->logo) }}" class="img-fluid rounded" alt="Logo">
                </div>
            </div>
            @endif

            <div class="card">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col">
                            <h3 class="mb-0">Other Information</h3>
                        </div>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table align-items-center table-flush">
                        <tbody>
                            <tr>
                                <th>Status</th>
                                <td>
                                    {!! $shool->status 
                                        ? '<span class="badge badge-success">Published</span>' 
                                        : '<span class="badge badge-danger">Unpublished</span>' !!}
                                </td>
                            </tr>
                            <tr>
                                <th>Created By</th>
                                <td>
                                    {{ isset($shool->owner) 
                                        ? $shool->owner->first_name . ' ' . $shool->owner->last_name 
                                        : '-' }}
                                </td>
                            </tr>
                            <tr>
                                <th>Created On</th>
                                <td>{{ _dt($shool->created) }}</td>
                            </tr>
                            <tr>
                                <th>Last Modified</th>
                                <td>{{ _dt($shool->modified) }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection