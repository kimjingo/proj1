@extends('layouts.master')

@section('content')
<h1>Add auto-posting rule</h1>

<form class="form-inline" id="deactivateForm" method="post" action="/postingrules/store">
    {{csrf_field()}}
    <input type='hidden' name='fromdoc' value='{{ $fromdoc }}' />

    <div class="col-sm-8 blog-main">
        
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>DR Account</th>
                    <th>DR Dir</th>
                    <th rowspan="2">SEQ</th>

                    <th>TType</th>
                    <th>AType</th>
                    <th rowspan="2">Action</th>
                </tr>
                <tr>
                    <th>CR Account</th>
                    <th>CR Dir</th>
                    <th>ADesc</th>
                    <th>Type</th>
                </tr>
            </thead>
            <tbody>
            
                <tr>
                    <td><input type='text' id="acc1" name='acc[]' value='{{ $dr }}' style="width: 100px;" readonly /></td>
                    <td><input type='text' id='dir1' name='dir[]' value=1 style="width: 30px;" readonly /></td>
                    <td><input type='text' id='seq1' name='seq[]' value=1 style="width: 30px;" readonly /></td>

                    <td><input type='text' name='ttype' value='{{ $ttype }}' readonly /></td>
                    <td><input type='text' name='atype' value='{{ $atype }}' readonly /></td>

                    <td rowspan="2">
                        <input type="checkbox" id="toggleReadonly">
                    </td>

                </tr>

                <tr>
                    <td>
                        <input type='text' id='acc2' class="acc" list='accs' name='acc[]' required />
                        <datalist id="accs">
                        @foreach($accs as $val)

                            <option value="{{ $val->accid }}">

                        @endforeach 
                    </td>
                    <td>
                        <input type='text' id='dir2' list='dirs' name='dir[]' style="width: 30px;" required />
                        <datalist id="dirs">
                        @foreach($dirs as $dir)

                            <option value="{{ $dir }}">

                        @endforeach 

                    </td>

                    <td><input type='text' id='seq2' name='seq[]' value="2" style="width: 30px;" /></td>

                    <td><input type='text' name='adesc' value='{{ $adesc }}' readonly /></td>
                    <td>
                        <input type='text' id='atrtype' list='atrtypes' name='atrtype' required />
                        <datalist id="atrtypes">
                        @foreach($atrtypes as $val)

                            <option value="{{ $val->ttype }}">

                        @endforeach 

                    </td>
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