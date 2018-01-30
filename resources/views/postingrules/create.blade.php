@extends('layouts.master')

@section('content')
<div class="blog-main">


  <h1>Add new posting rule</h1>

    <form method='POST' action='/postingrules/store' id="form_add" onsubmit="return confirm('Do you really want to submit the form?');">
    {{csrf_field()}}
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
                    <input type="text" list="fromdocs" name="fromdoc" value={{ $fromdoc or ''}} />
                    <datalist id="fromdocs">

                    @foreach($fromdocs as $val)

                      <option value="{{ $val->fromdoc }}">

                    @endforeach  
                </td>
                
                <td>
                    <input type="text" list="atts" name="att" value={{ $att or ''}} />
                    <datalist id="atts">
                    @foreach($atts as $val)

                      <option value="{{ $val->att }}">

                    @endforeach

                </td>

                <td>
                    <input type="text" list="aats" name="aat" value="{{ $aat or ''}}" />
                    <datalist id="aats">

                    @foreach($aats as $val)

                      <option value="{{ $val->aat }}">

                    @endforeach  
                </td>
                
                <td>
                    <input type="text" list="aads" name="aad" value="{{ $aad or ''}}" />
                    <datalist id="aads">

                    @foreach($aads as $val)

                      <option value="{{ $val->aad }}">

                    @endforeach  
                </td>
                
                <td>
                    <input style="width:50px;" type="text" list="bas" name="ba" value={{ $ba or ''}} />
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
                    <th>Check</th>
                    <th>Type</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
            
                <tr class="sample">
                    <td>
                        <input style="width:30px;" type="number" name="seq[]" min="0" step="1"  />
                    </td>

                    <td>
                        <input style="width:200px;" type="text" class="acc" list='accs' name="acc[]" />
                        <datalist id="accs">

                        @foreach($accs as $val)

                          <option value="{{ $val->accid }}">

                        @endforeach 
                    </td>

                    <td>
                        <input style="width:50px;" list="dirs" type="text" class="dir" name="dir[]" />
                        <datalist id="dirs">
                            <option value="1">
                            <option value="-1">
                    </td>
                    <td class="check">

                    </td>
                    <td>
                        <input type="text" list="ttypes" name="ttype[]" value="{{ $ttype or ''}}" />
                        <datalist id="ttypes">

                        @foreach($ttypes as $val)

                          <option value="{{ $val->ttype }}">

                        @endforeach  
                    </td>
                    <td><input type="button" name="add" value="+" class="tr_clone_add"><input type="button" name="del" value="-" class="tr_clone_del"></td>
                </tr>
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

    function updateCheck(){ 
        for(i = 0; i < $("input.acc").length; i++) { 
          if($("input.acc:eq(" + i  + ")").val()){
            $( "td.check:eq(" + i +")"  ).html(acc[ $("input.acc:eq(" + i  + ")").val() ].dir * acc[ $("input.acc:eq(" + i  + ")").val() ].gdir * $("input.dir:eq("+i+")").val() );
            $("td.amttopost:eq(" + i + ")" ).html($("input#amt").val() * $("input.dir:eq(" + i + ")").val() );
          }
        }
    }
    updateCheck();

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

        // console.log(zerosum);
        updateCheck();
        if(zerosum) {
            $(this).closest('td').next('td').css('background-color', '#f00');
        } else {
            $(this).closest('td').next('td').css('background-color', '#fff');
        }

    });

    $("input.dir").focusout(function(){
        var zerosum = 0;
        for(i = 0; i < $("input.acc").length; i++) { 
          zerosum += acc[ $("input.acc:eq(" + i  + ")").val() ].dir * acc[ $("input.acc:eq(" + i  + ")").val() ].gdir * $("input.dir:eq("+i+")").val() ;
        }

        // console.log(zerosum);
        updateCheck();
        if(zerosum) {
            $(this).closest('td').css('background-color', '#f00');
        } else {
            $(this).closest('td').css('background-color', '#fff');
        }

    });

});
</script>

@endsection