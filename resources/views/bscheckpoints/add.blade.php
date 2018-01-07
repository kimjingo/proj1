@extends('layouts.master')

@section('content')
<div class="col-sm-8 blog-main">
    <table class="table">
        <thead>
            <th>Date</th><th>Parent</th><th>Child</th><th>Amount</th><th>Delete</th>
        </thead>
        <tbody>
            
            @foreach($bscheckpoints as $bscheckpoint)
                <tr>
                    
                <td>{{ $bscheckpoint->checkdate }}</td>
                <td>{{ $bscheckpoint->accid }}</td>
                <td>{{ $bscheckpoint->toreconcile }}</td>
                <td class="number-align">{{ $bscheckpoint->amt }}</td>
                <td><a href="/bscheckpoint/delete/{{ $bscheckpoint->id }}">X</a></td>

                </tr>
            @endforeach

        </tbody>

    </table>

</div>

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
      	    @foreach($bstochecks as $bstocheck)

               <option value="{{$bstocheck->id}}">{{$bstocheck->accid}} + {{$bstocheck->toreconcile}}</option>

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