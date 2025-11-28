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
        <section class="mt-50 mb-50">
            <div class="container">
                <div class="row">
                    <div class="col-md-5">
                        <h3 style="color:#2A694F"><b>CHOOSE YOUR SCHOOL NAME ALPHABET</b></h3>										
						<p>Chose the alphabet of your school name</p>
						
						<h4>
							<a href="{{route('school.index', ['slug' => 'A'])}}" class="alphabetlinks">A</a>
							<a href="{{route('school.index', ['slug' => 'B'])}}" class="alphabetlinks">B</a>
							<a href="{{route('school.index', ['slug' => 'C'])}}" class="alphabetlinks">C</a>
							<a href="{{route('school.index', ['slug' => 'D'])}}" class="alphabetlinks">D</a>
							<a href="{{route('school.index', ['slug' => 'E'])}}" class="alphabetlinks">E</a>
							<a href="{{route('school.index', ['slug' => 'F'])}}" class="alphabetlinks">F</a>
							<a href="{{route('school.index', ['slug' => 'G'])}}" class="alphabetlinks">G</a>
							<a href="{{route('school.index', ['slug' => 'H'])}}" class="alphabetlinks">H</a>
							<a href="{{route('school.index', ['slug' => 'I'])}}" class="alphabetlinks">I</a>
							<a href="{{route('school.index', ['slug' => 'J'])}}" class="alphabetlinks">J</a>
							<a href="{{route('school.index', ['slug' => 'K'])}}" class="alphabetlinks">K</a>
							<a href="{{route('school.index', ['slug' => 'L'])}}" class="alphabetlinks">L</a>
							<a href="{{route('school.index', ['slug' => 'M'])}}" class="alphabetlinks">M</a>
							<a href="{{route('school.index', ['slug' => 'N'])}}" class="alphabetlinks">N</a>
							<a href="{{route('school.index', ['slug' => 'O'])}}" class="alphabetlinks">O</a>
							<a href="{{route('school.index', ['slug' => 'P'])}}" class="alphabetlinks">P</a>
							<a href="{{route('school.index', ['slug' => 'Q'])}}" class="alphabetlinks">Q</a>
							<a href="{{route('school.index', ['slug' => 'R'])}}" class="alphabetlinks">R</a>
							<a href="{{route('school.index', ['slug' => 'S'])}}" class="alphabetlinks">S</a>
							<a href="{{route('school.index', ['slug' => 'T'])}}" class="alphabetlinks">T</a>
							<a href="{{route('school.index', ['slug' => 'U'])}}" class="alphabetlinks">U</a>
							<a href="{{route('school.index', ['slug' => 'V'])}}" class="alphabetlinks">V</a>
							<a href="{{route('school.index', ['slug' => 'W'])}}" class="alphabetlinks">W</a>
							<a href="{{route('school.index', ['slug' => 'X'])}}" class="alphabetlinks">X</a>
							<a href="{{route('school.index', ['slug' => 'Y'])}}" class="alphabetlinks">Y</a>
							<a href="{{route('school.index', ['slug' => 'Z'])}}" class="alphabetlinks">Z</a>
						</h4>
                        
                    </div>
					<div class="col-md-2"><h2>OR</h2></div>
					<div class="col-md-5">
						<h3 style="color:#2A694F"><b>SEARCH YOUR SCHOOL NAME</b></h3>										
						<p>Search your school name by typing below in the box</p>
						<div>
							<input type="text" class="form-control" placeholder="Search..." id="school-search" />
						</div>
					</div>
					
                   
                </div>
            </div>
        </section>
@endsection