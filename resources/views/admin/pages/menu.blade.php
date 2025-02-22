<?php use App\Models\Admin\HomePage; ?>
@extends('layouts.adminlayout')
@section('content')
<div class="header bg-primary pb-6">
	<div class="container-fluid">
		<div class="header-body">
			<div class="row align-items-center py-4">
				<div class="col-lg-6 col-7">
					<h6 class="h2 text-white d-inline-block mb-0">Menu</h6>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- Page content -->
<div class="container-fluid mt--6">
	<div class="row">
		<div class="col-xl-12 order-xl-1">
			<div class="card">
				<!--!! FLAST MESSAGES !!-->
				@include('admin.partials.flash_messages')
				<div class="card-header">
					<div class="row align-items-center">
						<div class="col-8">
							<h3 class="mb-0">Menu Content.</h3>
						</div>
					</div>
				</div>
				<div class="card-body">
					<form method="post" action="<?php echo route('admin.pages.menu') ?>" class="form-validation">
						<!--!! CSRF FIELD !!-->
						{{ @csrf_field() }}
						<h6 class="heading-small text-muted mb-4">Page information</h6>
						<div class="pl-lg-4">
                            @foreach($menu as $m)
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label class="form-control-label" for="input-first-name">Title</label>
                                    <input type="text" class="form-control" name="title" required placeholder="Title" value="{{$m->title}}">
                                </div>
                                <div class="form-group col-md-6">
                                    <label class="form-control-label" for="input-first-name">URL</label>
                                    <input type="text" class="form-control" name="link" required placeholder="URL" value="{{isset($m->link) ? $m->link : ''}}">
                                </div>
							</div>
                            @endforeach
                            <hr class="my-4" />
                            <button href="#" class="btn btn-sm py-2 px-3 btn-primary float-right">
                                <i class="fa fa-save"></i> Submit
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
