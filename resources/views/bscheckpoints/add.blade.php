@extends('layouts.master')

@section('content')

	<h1>Add check point</h1>

	<form method='POST' action='/bscheckpoint/store'>
		{{csrf_field()}}

    <div class="form-group">

		<div class="form-group">
			<label for="checkdate">Date</label>
			<input type="date" class="form-control" id="checkdate" name="checkdate" value="{{ $cdate->toDateString() }}" >
		</div>

      <label for="accid">Select a checkpoint</label>
      <select class="form-control" id="accid" name="accid">
      	    @foreach($bscheckpoints as $bscheckpoint)

               <option value="{{$bscheckpoint->id}}">{{$bscheckpoint->accid}} + {{$bscheckpoint->toreconcile}}</option>

            @endforeach
      </select>
      <br>
    </div>

		<div class="form-group">
			<label for="amt">Amount</label>
			<input type="number" class="form-control" id="amt" name="amt" step="0.01" placeholder="Amount">
		</div>


		<button type="submit" class="btn btn-primary">Submit</button>
	</form>
</div>
@endsection