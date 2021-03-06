@extends('layouts.master')

@section('content')
<h1>Financial Transactions - update</h1>
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
                    <td><input type='text' id="acc1" name='acc[]' value='{{ $dr }}' style="width: 100px;" readonly /></td>
                    <td><input type='text' id='dir1' name='dir[]' value=1 style="width: 30px;" readonly /></td>

                    <td><input type='text' name='no' value='{{ $bank->no }}' readonly  style="width:50px;"/></td>
                    <td><input type='text' name='pdate' value='{{ $bank->tDate }}' readonly style="width:150px;" /></td>
                    <td><input type='text' name='vendor' value='{{ $bank->mp }}' readonly style="width: 100px;" /></td>
                    <td><input type='text' name='reference' value='{{ $bank->Checkno }}' readonly style="width: 100px;" /></td>
                    <td><input type='text' name='' value='{{ $bank->accno }}' readonly style="width: 100px;" /></td>
                    <td><input type='text' name='ckey' value='{{ $bank->clearingkey }}' readonly style="width: 100px;" /></td>

                </tr>
                    <td>
                        <input type='text' id='acc2' class="acc" list='accs' name='acc[]' required />
                        <datalist id="accs">
                        @foreach($accs as $acc)

                            <option value="{{ $acc->acc }}">

                        @endforeach 
                    </td>
                    <td>
                        <input type='text' id='dir2' list='dirs' name='dir[]' style="width: 30px;" required />
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
                        <input type="checkbox" id="toggleReadonly" name="no" value='{{$bank->no}}'>
                    </td>
                <tr>
                    
                </tr>

            </tbody>

        </table>

    </div>

    <input type="submit" id="submit" class="btn btn-danger" name='submit' value="Post" />
</form>

@endsection


@section('footer')

<script type="text/javascript">
    // $("input.acc").focusout(function(){
    var acc = {!! $accCalJSON !!};
    $("input#acc2").focusout(function(){

        // console.log($("input#acc1").val());

        var dir2 = $("input#dir1").val() * acc[$("input#acc1").val()].dir * acc[$("input#acc1").val()].gdir * -1 / (acc[$("input#acc2").val()].dir * acc[$("input#acc2").val()].gdir) ; 
        // console.log(dir2);
        $("input#dir2").val(dir2);
        // $("input.acc").each(function(){
        //       TotalValue += Number($(this).val());
        // });
        // $("td#" + className).html(TotalValue);
    });

    $('input#toggleReadonly').on('click', function() {
        // var prev = $(this).prev('input'),
        //     ro   = prev.prop('readonly');
        // prev.prop('readonly', !ro).focus();
        // $(this).val(ro ? 'Save' : 'Edit');

        $("input[type='text']").each(function(){
            ro   = $(this).prop('readonly');
            $(this).prop('readonly', !ro);
        });

    });
</script>

@endsection