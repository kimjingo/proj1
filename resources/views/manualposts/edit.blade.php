@extends('layouts.master')

@section('content')
<div class="blog-main">


  <h1>Add receipt, check, invoice and etc</h1>

	<form method='POST' action='/manualposts/update' id="form_add" onsubmit="return confirm('Do you really want to submit the form?');">
    {{csrf_field()}}
    <input type="hidden" name="id" value="{{ $id }}" >

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
        </thead>
        <tbody>
        <tr class="sample">
          <td class="col-md-5">
            <input style="width:150px;" type="date" class="form-control" id="pdate" name="pdate" value="{{ $manualinput->pdate }}" >
          </td>
          <td>
            <input style="width:80px;" type="number" class="form-control" id="amt" name="amt" step="0.01"  value="{{ $manualinput->amt }}">
          </td>
          <td>
            <input style="width:100px;" type="text" list="ttypes" name="ttype"  value="{{ $manualinput->ttype }}" />
            <datalist id="ttypes">

            @foreach($ttypes as $ttype)

              <option value="{{ $ttype->ttype }}">

            @endforeach  
          </td>
          <td>
            <input style="width:100px;" type="text" list="mps" name="mp"  value="{{ $manualinput->mp }}" />
            <datalist id="mps">

            @foreach($mps as $mp)

              <option value="{{ $mp->mp }}">

            @endforeach  
          </td>

          <td>
            <input style="width:100px;" type="text" name="material" value="{{ $manualinput->material }}" />
          </td>
          <td>
            <input type="text" name="remark" value="{{ $manualinput->remark }}" />
          </td>
          <td>
            <input style="width:50px;" type="text" class="form-control" id="checkno" name="checkno"  value="{{ $manualinput->checkno }}" />
          </td>
          <td>
            <input style="width:100px;" type="text" list="paidbys" name="paidby"  value="{{ $manualinput->paidby }}" />
            <datalist id="paidbys">

            @foreach($paidbys as $paidby)

              <option value="{{ $paidby->paidby }}">

            @endforeach  
          </td>
          <td>
            <input style="width:20px;" type="text" list="bas" name="ba"  value="{{ $manualinput->ba }}" />
            <datalist id="bas">

            @foreach($bas as $ba)

              <option value="{{ $ba }}">

            @endforeach  
          </td>
          

        </tr>

        
        <tbody>

      </table>

	
		<button type="submit" class="btn btn-primary">Submit</button>
	</form>
</div>
@endsection

@section('footer')


@endsection