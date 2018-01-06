@extends('layouts.master')

@section('content')

	<h1>Add account to reconcile</h1>

	<form method='POST' action='/add'>
		{{csrf_field()}}

    <div class="form-group">
      <label for="acc">Select Accout</label>
      <select class="form-control" id="acc" name="acc">
      	    @foreach($parent as $acc)

               <option value="{{$acc->accid}}">{{$acc->accid}}</option>

            @endforeach
      </select>
      <br>
    </div>

		<div class="form-group">
			<label for="child">Child Account</label>
			<input type="Text" class="form-control" id="child" name="child" placeholder="Child">
		</div>


		<button type="submit" class="btn btn-primary">Submit</button>
	</form>
</div>
@endsection