@extends('layouts.frontendlayout')
@section('content')

<div class="page-header breadcrumb-wrap">
	<div class="container">
		<div class="breadcrumb">
			<a href="{{url('/')}}" rel="nofollow">Home</a>
			<span></span> <strong>By School Name</strong>
		</div>
	</div>
</div>
@include('frontend.school.schoolSection')
@endsection