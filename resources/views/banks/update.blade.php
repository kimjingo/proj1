@extends('layouts.master')

@section('content')

<form class="form-inline" id="deactivateForm" method="post" action="/bank/post">
    {{csrf_field()}}
    <div class="col-sm-8 blog-main">
        <a class="btn btn-primary" href="/postingrules/create" role="button">Add</a>
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>Account</th>
                    <th>Dir</th>

                    <th>No</th>
                    <th>Date</th>
                    <th>Type</th>
                    <th>Amount</th>
                    <th>Vendor</th>
                    <th>Material</th>
                    <th>Ref#</th>
                    <th>Remark</th>
                    <th>Bank</th>
                    <th>B/A</th>
                    <th>ClearingKey</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
            
                <tr>
                    <td><input type='text' value='{{ $dr }}' /></td>
                    <td><input type='text' value=1 /></td>

                    <td rowspan=2><input type='text' value='{{ $bank->no }}' readonly  style="width: 50px;"/></td>
                    <td rowspan=2><input type='text' value='{{ $bank->tDate }}' readonly  style="width: 150px;" /></td>
                    <td rowspan=2><input type='text' value='{{ $bank->TType }}' readonly /></td>
                    <td rowspan=2 class="number-align"><input type='number' value={{ $bank->amt }} readonly style="width: 70px;" /></td>
                    <td rowspan=2><input type='text' value='{{ $bank->mp }}' readonly /></td>
                    <td rowspan=2><input type='text' value='{{ $bank->material }}' readonly /></td>
                    <td rowspan=2><input type='text' value='{{ $bank->Checkno }}' readonly /></td>
                    <td rowspan=2><input type='text' value='{{ $bank->tDesc }}' readonly /></td>
                    <td rowspan=2><input type='text' value='{{ $bank->accno }}' readonly /></td>
                    <td rowspan=2><input type='text' value='{{ $bank->ba }}' readonly  style="width: 30px;" /></td>
                    <td rowspan=2><input type='text' value='{{ $bank->clearingkey }}' readonly /></td>
                    <td>
                        <a href="/bank/edit/{{ $bank->no }}">Edit</a>
                        ||
                        <a href="/postingrules?fromdoc=bank&trtype={{$bank->TType}}&ttype={{$bank->mp}}&vendor={{$bank->mp}}&material={{$bank->material}}">Rule</a> 
                        ||
                        <input type="checkbox" id="checkBox" name="no[]" value='{{$bank->no}}'>
                    </td>

                </tr>
                    <td>{{ $bank->mp }}</td>
                    <td>{{ $bank->mp }}</td>
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