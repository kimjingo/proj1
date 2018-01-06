@extends('layouts.master')

@section('content')
<div class="col-sm-8 blog-main">
	
	<h1>Add account to reconcile</h1>
	<hr>

	<form method='POST' action='/add'>
		{{csrf_field()}}


		<div class="dropdown">
		  <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
		    Dropdown button
		  </button>
		  <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
		    <a class="dropdown-item" href="#">Action</a>
		    <a class="dropdown-item" href="#">Another action</a>
		    <a class="dropdown-item" href="#">Something else here</a>
		  </div>
		</div>

		  <div class="form-group">
		  	<div class="dropdown">
			  <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
			    Dropdown button
			  </button>
			  <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">

			  	@foreach($parent as $acc)

			    <a class="dropdown-item" value="{{$acc->accid}}">{{$acc->accid}}</a>

			    @endforeach

			  </div>
			</div>


		<div class="form-group">
			<label for="child">Child Account</label>
			<input type="Text" class="form-control" id="child" name="child" placeholder="Child">
		</div>

		<button type="submit" class="btn btn-primary">Submit</button>
	</form>
</div>

@endsection


@section('layouts.footer')

<script type="text/javascript"></script>

@endsection
