@extends('layouts.master')

@section('content')
<div class="col-sm-8 blog-main">


  <h1>Add B/S Check point</h1>

  <form method='POST' action='/bscheckpoint/store' id="form_add" onsubmit="return confirm('Do you really want to submit the form?');">
    {{csrf_field()}}

    <table class="table">
      <thead>
        <tr>
          <th>Date</th>
          <th>Checkpoint</th>
          <th>Amount</th>
        </tr>
      </thead>
        <tbody>
<tr>
              <td>
      <input type="date" class="form-control" id="checkdate" name="checkdate" value="{{ $cdate->toDateString() }}" >
                
              </td>
              <td>
      <select class="form-control" id="accid" name="accid">
            @foreach($bstochecks as $bstocheck)

               <option value="{{$bstocheck->id}}">{{$bstocheck->accid}} + {{$bstocheck->toreconcile}}</option>

            @endforeach
      </select>
                
              </td>
              <td>
      <input type="number" class="form-control" id="amt" name="amt" step="0.01" placeholder="Amount">
                
        </tbody>
    </table>

    <button type="submit" class="btn btn-primary">Submit</button>

  </form>
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
@endsection

@section('footer')

<script type="text/javascript">
$(document).ready(function() {
  // $form = $('#form_add');
  // $trsample = $form.find('.sample');

  $('.tr_clone_add').click( function() {
    // console.log("aa");
    // $ttr = $('#myTable tbody>tr:first').clone(true).insertAfter($('#myTable tbody>tr:last'));
    $(this).closest ('tr').clone(true).insertAfter($('#myTable tbody>tr:last'));
    // $ttr.show();
    // $htmldelbutton = '<input type="button" name="del" value="-" class="tr_clone_del">';
    // $tt = $('#myTable tbody>tr:last td:last').html();
    // console.log( $tt );
    // $('#myTable tbody>tr:last td:last').html($tt + $htmldelbutton);
    // $justInserted = $trsample.find(':last');
    // $justInserted.hide();
    // $justInserted.find('input').val(''); // it may copy values from first one
    // $justInserted.slideDown(500);
  });

  $('input.tr_clone_del').click( function() {
    var rowCount = $('#myTable tbody>tr').length;
    console.log(rowCount);
    if(rowCount > 1) $(this).closest ('tr').remove ();
    // $justInserted = $trsample.find(':last');
    // $justInserted.hide();
    // $justInserted.find('input').val(''); // it may copy values from first one
    // $justInserted.slideDown(500);
  });
});
</script>

@endsection