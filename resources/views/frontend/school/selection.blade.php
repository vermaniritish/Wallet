@extends('layouts.frontendlayout')
@push('meta')
<title>Shop School Uniform by School | Find Your School | Pinders</title>
<meta name="description" content="Find your school's official uniform at Pinders Schoolwear. Browse hundreds of schools across Sheffield, Rotherham, Chesterfield and the UK to shop the right items for your child.">
<meta property="og:title" content="Shop School Uniform by School | Find Your School | Pinders">
<meta property="og:type" content="website">
@endpush
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