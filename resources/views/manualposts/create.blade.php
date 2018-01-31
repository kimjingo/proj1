@extends('layouts.master')

@section('content')
<div class="blog-main">


  <h1>Manual Post : Add receipt, check, invoice and etc</h1>

	<form method='POST' action='/manualposts/store' id="form_add" onsubmit="return confirm('Do you really want to submit the form?');">
    {{csrf_field()}}

    <table class="table" id="myTable">
        <thead>
            <th>Date*</th>
            <th>Amount*</th>
            <th>Type*</th>
            <th>Where*</th>
            <th>What</th>
            <th>Why</th>
            <th>Ref#</th>
            <th>Who paid*</th>
            <th>BA</th>
            <th>+/-</th>
        </thead>
        <tbody>
        <tr class="sample">
          <td class="col-md-5">
            <input style="width:150px;" type="date" class="form-control" id="pdate" name="pdate[]" value="{{ $pdate->toDateString() }}" >
          </td>
          <td>
            <input style="width:80px;" type="number" class="form-control" id="amt" name="amt[]" step="0.01" placeholder="Amount">
          </td>
          <td>
            <input style="width:100px;" type="text" list="ttypes" name="ttype[]" />
            <datalist id="ttypes">

            @foreach($ttypes as $ttype)

              <option value="{{ $ttype->ttype }}">

            @endforeach  
          </td>
          <td>
            <input style="width:100px;" type="text" list="mps" name="mp[]" />
            <datalist id="mps">

            @foreach($mps as $mp)

              <option value="{{ $mp->mp }}">

            @endforeach  
          </td>

          <td>
            <input style="width:100px;" type="text" name="material[]" />
          </td>
          <td>
            <input type="text" name="remark[]" />
          </td>
          <td>
            <input style="width:50px;" type="text" class="form-control" id="checkno" name="checkno[]" placeholder="Ref#:check# or invoice#" />
          </td>
          <td>
            <input style="width:100px;" type="text" list="paidbys" name="paidby[]" />
            <datalist id="paidbys">

            @foreach($paidbys as $paidby)

              <option value="{{ $paidby->paidby }}">

            @endforeach  
          </td>
          <td>
            <input style="width:20px;" type="text" list="bas" name="ba[]" />
            <datalist id="bas">

            @foreach($bas as $ba)

              <option value="{{ $ba }}">

            @endforeach  
          </td>
          <td><input type="button" name="add" value="+" class="tr_clone_add"><input type="button" name="del" value="-" class="tr_clone_del"></td>

        </tr>

        
        <tbody>

      </table>

	
		<button type="submit" class="btn btn-primary">Submit</button>
	</form>
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