@extends('layouts.master')

@section('content')
<div class="blog-main">


  <h1>Add new posting rule</h1>

    <form method='POST' action='/postingrules/store' id="form_add" onsubmit="return confirm('Do you really want to submit the form?');">
    {{csrf_field()}}

    <table class="table" id="myTable">
        <thead>
            <tr>
                <th>DocType</th>
                <th>TrType</th>
                <th>Vendor</th>
                <th>Material</th>
                <th>Type</th>
                <th>Seq</th>
                <th>Account</th>
                <th>Dir</th>
                <th>B/A</th>
            </tr>
        </thead>
        <tbody>

        @foreach($rules as $rule)
            <tr class="sample">
                <td>
                    <input type="text" list="fromdocs" name="fromdoc[]"  value="{{ $rule->fromdoc }}" />
                    <datalist id="fromdocs">

                    @foreach($fromdocs as $val)

                      <option value="{{ $val->fromdoc }}">

                    @endforeach  
                </td>
                
                <td>
                    <input type="text" list="trtypes" name="trtype[]" value="{{ $rule->trtype }}" />
                    <datalist id="trtypes">
                    @foreach($trtypes as $val)

                      <option value="{{ $val->trtype }}">

                    @endforeach

                </td>

                                <td>
                    <input style="width:100px;" type="text" list="vendors" name="vendor[]"  value="{{ $rule->vendor }}" />
                    <datalist id="vendors">

                    @foreach($vendors as $val)

                      <option value="{{ $val->vendor }}">

                    @endforeach  
                </td>
                
                <td>
                    <input style="width:100px;" type="text" list="materials" name="material[]"  value="{{ $rule->material }}" />
                    <datalist id="materials">

                    @foreach($materials as $val)

                      <option value="{{ $val->material }}">

                    @endforeach  
                </td>
                
                <td>
                    <input style="width:100px;" type="text" list="ttypes" name="ttype[]"  value="{{ $rule->ttype }}" />
                    <datalist id="ttypes">

                    @foreach($ttypes as $val)

                      <option value="{{ $val->ttype }}">

                    @endforeach  
                </td>

                <td>
                    <input style="width:30px;" type="number" name="seq[]" step="1" value="{{ $rule->seq }}" />
                </td>

                <td>
                    <input style="width:100px;" type="text" list='accs' name="acc[]" value="{{ $rule->acc }}" />
                    <datalist id="accs">

                    @foreach($accs as $val)

                      <option value="{{ $val->acc }}">

                    @endforeach 
                </td>

                <td>
                    <input style="width:30px;" type="number" name="dir[]" step="1" value="{{ $rule->dir }}" />
                </td>
                
                <td>
                    <input style="width:30px;" type="text" list="bas" name="ba[]"  value="{{ $rule->ba }}" />
                    <datalist id="bas">

                    @foreach($bas as $val)

                      <option value="{{ $val->ba }}">

                    @endforeach  
                </td>

            </tr>
        @endforeach

        
        <tbody>

      </table>

    
        <button type="submit" class="btn btn-primary">Submit</button>
    </form>
</div>
@endsection

@section('footer')


@endsection