@extends('layouts.master')

@section('content')
<h1>Manual Post to FI</h1>

<form class="form-inline" id="deactivateForm" method="post" action="/manualposts/post/{{$manualpostheader->id}}">
    {{csrf_field()}}
  <input name="_method" type="hidden" value="PUT">
    <div class="col-sm-8 blog-main">
        
  <table class="table">
            <tr>
                <th>DocType</th>
                <th>Date</th>
                <th>Vendor</th>
                <th>Material</th>
                <th>Amount</th>
                <th>Remark</th>
                <th>Type</th>
                <th>Paid by</th>
                <th>B/A</th>
                <th>Check#</th>
            </tr>
            <tr>
                
                <td>
                    <input style="width:100px;" type="text" name="fromdoc"  value="{{ $fromdoc }}"  required readonly />
                </td>
                
                <td>
                    <input style="width:150px;" type="date" name="pdate" value="{{ $manualpostheader->pdate }}"  required  readonly />
                </td>

                <td>
                    <input style="width:100px;" type="text" list="vendors" name="vendor"  value="{{ $manualpostheader->vendor }}" required />
                    <datalist id="vendors">

                    @foreach($vendors as $val)

                      <option value="{{ $val->vendor }}">

                    @endforeach  
                </td>
                
                <td>
                    <input style="width:100px;" type="text" list="materials" name="material"  value="{{ $manualpostheader->material }}"  required />
                    <datalist id="materials">

                    @foreach($materials as $val)

                      <option value="{{ $val->material }}">

                    @endforeach  
                </td>
                <td>
                  <input type='number' name='amt' id='amt' value={{ $manualpostheader->amt }} readonly style="width:70px;" />
                </td>
                <td>
                    <input type="text" name="remark"  value="{{ $manualpostheader->remark }}" />
                </td>
                <td>
                    <input style="width:100px;" type="text" list="ttypes" name="ttype"  value="{{ $manualpostheader->ttype }}" />
                    <datalist id="ttypes">

                    @foreach($ttypes as $val)

                      <option value="{{ $val->ttype }}">

                    @endforeach  
                </td>

                <td>
                    <input style="width:50px;" type="text" list="paidbys" name="paidby"  value="{{ $manualpostheader->paidby }}" />
                    <datalist id="paidbys">

                    @foreach($paidbys as $val)

                      <option value="{{ $val->paidby }}">

                    @endforeach  
                </td>
                
                <td>
                    <input style="width:50px;" type="text" list="bas" name="ba"  value="{{ $manualpostheader->ba }}" />
                    <datalist id="bas">

                    @foreach($bas as $val)

                      <option value="{{ $val->ba }}">

                    @endforeach  
                </td>
                <td>
                    <input type="text" name="checkno"  value="{{ $manualpostheader->checkno }}" />
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
                    <th>Amount to post</th>
                    <th>Clearing Key</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
            
                <tr class="sample">
                    <td><input name=seq[] value="1"></td>

                    <td>
                        <input style="width:200px;" type="text" class="acc" list='accs' name="cr" value="{{ $manualpost->cr }}"  required />
                        <datalist id="accs">

                        @foreach($accs as $val)

                          <option value="{{ $val->accid }}">

                        @endforeach 
                    </td>

                    <td>
                        <input style="width:50px;" list="dirs" type="text" class="dir" name="cr_dir" value="{{ $manualpost->cr_dir or 1 }}"  required />
                        <datalist id="dirs">
                            <option value="1">
                            <option value="-1">
                    </td>
                    <td class="check">

                    </td>
                    <td class="amttopost">

                    </td>
                    <td>
                        <input type="text" name="cr_clearing"  value="{{ $manualpost->cr_clearing }}" />
                    </td>
                    <td><input type="button" name="add" value="+" class="tr_clone_add"><input type="button" name="del" value="-" class="tr_clone_del"></td>
                </tr>

                 <tr class="sample">
                    <td><input name=seq[] value="2"></td>

                    <td>
                        <input style="width:200px;" type="text" class="acc" list='accs' name="dr" value="{{ $manualpost->dr }}"  required  />
                        <datalist id="accs">

                        @foreach($accs as $val)

                          <option value="{{ $val->accid }}">

                        @endforeach 
                    </td>

                    <td>
                        <input style="width:50px;" list="dirs" type="text" class="dir" name="dr_dir" value="{{ $manualpost->dr_dir or 1 }}"  required />
                        <datalist id="dirs">
                            <option value="1">
                            <option value="-1">
                    </td>
                    <td class="check">

                    </td>
                    <td class="amttopost">

                    </td>
                    <td>
                        <input type="text" name="dr_clearing"  value="{{ $manualpost->dr_clearing }}" />
                    </td>
                    <td><input type="button" name="add" value="+" class="tr_clone_add"><input type="button" name="del" value="-" class="tr_clone_del"></td>
                </tr>
            
            </tbody>

        </table>
        
        <button type="submit" class="btn btn-primary">Post to FI</button>
    </form>

@endsection


@section('footer')

<script type="text/javascript">
// $(document).ready(function() {
  // console.log("initialized");
  var acc = {!! $accCalJSON !!};

  updateCheck();

  function updateCheck(){ 
    for(i = 0; i < $("input.acc").length; i++) { 
      if($("input.acc:eq(" + i  + ")").val()){
        $( "td.check:eq(" + i +")"  ).html(acc[ $("input.acc:eq(" + i  + ")").val() ].dir * acc[ $("input.acc:eq(" + i  + ")").val() ].gdir * $("input.dir:eq("+i+")").val() );
        $("td.amttopost:eq(" + i + ")" ).html($("input#amt").val() * $("input.dir:eq(" + i + ")").val() );
      }
    }
  }


    $("input.acc").focusout(function(){
        var zerosum = 0;
        for(i = 0; i < $("input.acc").length; i++) { 
          accid = $("input.acc:eq(" + i  + ")").val();
          if(acc[accid].dir){

          zerosum += acc[ $("input.acc:eq(" + i  + ")").val() ].dir * acc[ $("input.acc:eq(" + i  + ")").val() ].gdir * $("input.dir:eq("+i+")").val() ;

          }
          // console.log( accid );
            // +" * " + acc[$("input.acc:eq(" + i  + ")").val()].gdir + " * " + $("input.dir:eq(" + i + ")").val()) ;
        }
        updateCheck();
        console.log(zerosum);
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

// });
</script>

@endsection