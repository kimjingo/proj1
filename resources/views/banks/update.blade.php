@extends('layouts.master')

@section('content')
<h1>Post bank manully</h1>
<form class="form-inline" id="deactivateForm" method="post" action="/bank/singlepost">
    {{csrf_field()}}

    <div class="col-sm-8 blog-main">
        
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>DR Account</th>
                    <th>DR Dir</th>

                    <th>No</th>
                    <th>Date</th>
                    <th>Vendor</th>
                    <th>Ref#</th>
                    <th>Bank</th>
                    <th>ClearingKey</th>
                </tr>
                <tr>
                    <th>CR Account</th>
                    <th>CR Dir</th>

                    <th>Type</th>
                    <th>Amount</th>
                    <th>Material</th>
                    <th>Remark</th>
                    <th>B/A</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
            
                <tr>
                    <td><input type='text' name='acc[]' value='{{ $dr }}' style="width: 100px;" readonly /></td>
                    <td><input type='text' name='dir[]' value=1 style="width: 30px;" readonly /></td>

                    <td><input type='text' name='no' value='{{ $bank->no }}' readonly  style="width:50px;"/></td>
                    <td><input type='text' name='pdate' value='{{ $bank->tDate }}' readonly style="width:150px;" /></td>
                    <td><input type='text' name='vendor' value='{{ $bank->mp }}' readonly style="width: 100px;" /></td>
                    <td><input type='text' name='reference' value='{{ $bank->Checkno }}' readonly style="width: 100px;" /></td>
                    <td><input type='text' name='' value='{{ $bank->accno }}' readonly style="width: 100px;" /></td>
                    <td><input type='text' name='ckey' value='{{ $bank->clearingkey }}' readonly style="width: 100px;" /></td>

                </tr>
                    <td>
                        <input type='text' list='accs' name='acc[]' />
                        <datalist id="accs">
                        @foreach($accs as $acc)

                            <option value="{{ $acc->acc }}">

                        @endforeach 
                    </td>
                    <td>
                        <input type='text' list='dirs' name='dir[]' style="width: 30px;" />
                        <datalist id="dirs">
                        @foreach($dirs as $dir)

                            <option value="{{ $dir }}">

                        @endforeach 

                    </td>
                    <td><input type='text' name='ttype' value='{{ $bank->TType }}' readonly style="width: 100px;"/></td>
                    <td class="number-align"><input type='number' name='amt' value={{ $bank->amt }} readonly style="width:70px;" /></td>
                    <td><input type='text' name='material' value='{{ $bank->material }}' readonly style="width: 100px;" /></td>
                    <td><input type='text' name='remark' value='{{ $bank->tDesc }}' readonly /></td>
                    <td><input type='text' name='ba' value='{{ $bank->ba }}' readonly  style="width: 30px;" /></td>
                    <td>
                        <a href="/bank/edit/{{ $bank->no }}">Edit</a>
                        ||
                        <a href="/postingrules?fromdoc=bank&trtype={{$bank->TType}}&ttype={{$bank->mp}}&vendor={{$bank->mp}}&material={{$bank->material}}">Rule</a> 
                        ||
                        <input type="checkbox" id="checkBox" name="no" value='{{$bank->no}}'>
                    </td>
                <tr>
                    
                </tr>

            </tbody>

        </table>

    </div>

    <input type="submit" id="submit" class="btn btn-danger" name='submit' value="Post" />
</form>

@endsection


@section('layouts.footer')

<script type="text/javascript"></script>

@endsection