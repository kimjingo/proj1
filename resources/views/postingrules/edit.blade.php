@extends('layouts.master')

@section('content')
<div class="blog-main">


  <h1>Edit posting rule</h1>

    <form method='POST' action='/postingrules/update/{{$id}}' id="form_add" onsubmit="return confirm('Do you really want to submit the form?');">

    {{csrf_field()}}
    <input name="_method" type="hidden" value="PUT">

<!-- Rendered blade HTML form use this hidden. Dont forget to put the form method to POST -->

        <table class="table">
            <tr>
                <th>DocType</th>
                <th>TransType</th>
                <th>Vendor</th>
                <th>Material</th>
                <th>B/A</th>
            </tr>
            <tr>
                
                <td>
                    <input type="text" list="fromdocs" name="fromdoc"  value="{{ $ruleheader->fromdoc }}" />
                    <datalist id="fromdocs">

                    @foreach($fromdocs as $val)

                      <option value="{{ $val->fromdoc }}">

                    @endforeach  
                </td>
                
                <td>
                    <input type="text" list="atts" name="att" value="{{ $ruleheader->att }}" />
                    <datalist id="atts">
                    @foreach($atts as $val)

                      <option value="{{ $val->att }}">

                    @endforeach

                </td>

                <td>
                    <input style="width:100px;" type="text" list="aats" name="aat"  value="{{ $ruleheader->aat }}" />
                    <datalist id="aats">

                    @foreach($aats as $val)

                      <option value="{{ $val->aat }}">

                    @endforeach  
                </td>
                
                <td>
                    <input style="width:100px;" type="text" list="aads" name="aad"  value="{{ $ruleheader->aad }}" />
                    <datalist id="aads">

                    @foreach($aads as $val)

                      <option value="{{ $val->aad }}">

                    @endforeach  
                </td>
                
                <td>
                    <input style="width:50px;" type="text" list="bas" name="ba"  value="{{ $ruleheader->ba }}" />
                    <datalist id="bas">

                    @foreach($bas as $val)

                      <option value="{{ $val->ba }}">

                    @endforeach  
                </td>
            </tr>
        </table>
    

        <table class="table" id="myTable">
            <thead>
                <tr>
                    <th>Seq</th>
                    <th>Credit</th>
                    <th>Dir</th>
                    <th>Rate</th>
                    <th>Check</th>
                    <th>Type</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
            
            @foreach($rules as $rule)
                <tr class="sample">
                    <td>
                        <input style="width:30px;" type="number" name="seq[]" min="0" step="1" value="{{ $rule->seq }}" />
                    </td>

                    <td>
                        <input style="width:200px;" type="text" class="acc" list='accs' name="acc[]" value="{{ $rule->acc }}" />
                        <datalist id="accs">

                        @foreach($accs as $val)

                          <option value="{{ $val->accid }}">

                        @endforeach 
                    </td>

                    <td>
                        <input style="width:50px;" list="dirs" type="text" class="dir" name="dir[]" value="{{ $rule->dir }}" />
                        <datalist id="dirs">
                            <option value="1">
                            <option value="-1">
                    </td>
                    <td>
                        <input style="width:100px;" type="number" class="rate toverify" name="rate[]" step="0.0001" min="0" max="1" value="{{ $rule->rate }}" />
                    </td>
                    <td class="check">

                    </td>
                    <td>
                        <input style="width:100px;" type="text" list="ttypes" name="ttype[]"  value="{{ $rule->ttype }}" />
                        <datalist id="ttypes">

                        @foreach($ttypes as $val)

                          <option value="{{ $val->ttype }}">

                        @endforeach  
                    </td>
                    <td><input type="button" name="add" value="+" class="tr_clone_add"><input type="button" name="del" value="-" class="tr_clone_del"></td>
                </tr>
            @endforeach

            
            </tbody>

        </table>
        
        <button type="submit" class="btn btn-primary">Submit</button>
    </form>
</div>
@endsection

@section('footer')

<script type="text/javascript">
$(document).ready(function() {
    var acc = {!! $accCalJSON !!};

    for(i = 0; i < $("input.acc").length; i++) { 
        $( "td.check:eq(" + i +")"  ).html(acc[ $("input.acc:eq(" + i  + ")").val() ].dir * acc[ $("input.acc:eq(" + i  + ")").val() ].gdir * $("input.dir:eq("+i+")").val() );
    }


    $('.tr_clone_add').click( function() {
        $(this).closest ('tr').clone(true).insertAfter($('#myTable tbody>tr:last'));
    });

    $('input.tr_clone_del').click( function() {
        var rowCount = $('#myTable tbody>tr').length;
        // console.log(rowCount);
        if(rowCount > 1) {
            $(this).closest ('tr').remove ();
        } 
    });

    $("input.acc").focusout(function(){
        var zerosum = 0;
        for(i = 0; i < $("input.acc").length; i++) { 
          zerosum += acc[ $("input.acc:eq(" + i  + ")").val() ].dir * acc[ $("input.acc:eq(" + i  + ")").val() ].gdir * $("input.dir:eq("+i+")").val() ;
        }

        console.log(zerosum);
        if(zerosum) {
            $(this).closest('td').next('td').css('background-color', '#f00');
        } else {
            $(this).closest('td').next('td').css('background-color', '#fff');
        }

        // console.log($("input#acc1").val());

        // var dir2 = $("input#dir1").val() * acc[$("input#acc1").val()].dir * acc[$("input#acc1").val()].gdir * -1 / (acc[$("input#acc2").val()].dir * acc[$("input#acc2").val()].gdir) ; 
        // console.log(dir2);
        // $("input#dir2").val(dir2);
        
        // $("input.acc").each(function(){
        //       TotalValue += Number($(this).val());
        // });
        // $("tr." + className).html(TotalValue);
    });

    $("input.dir").focusout(function(){
        var zerosum = 0;
        for(i = 0; i < $("input.acc").length; i++) { 
          zerosum += acc[ $("input.acc:eq(" + i  + ")").val() ].dir * acc[ $("input.acc:eq(" + i  + ")").val() ].gdir * $("input.dir:eq("+i+")").val() ;
        }

        // console.log(zerosum);
        if(zerosum) {
            $(this).closest('td').css('background-color', '#f00');
        } else {
            $(this).closest('td').css('background-color', '#fff');
        }

    });

});
</script>

@endsection