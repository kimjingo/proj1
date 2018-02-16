@extends('layouts.master')

@section('content')

	<h1>Add recurring rule</h1>
	<form method='POST' action='/recurring/store'>
		{{csrf_field()}}


		@foreach($columns as $col)
			<?php
				$findme   = '(';
				$pos = strpos($col->Type, $findme);
				if($pos) {
					$fieldType = substr($col->Type, $pos);    // returns "f"
				} else {
					$fieldType = $col->Type;
				}

				switch ($fieldType) {
				    case 'int':
				        $inputtype = 'number';
				        break;
				    case 'varchar':
				        $inputtype = 'text';
				        break;
				    case 'date':
				        $inputtype = 'date';
				        break;
				    case 'enum':
				        $inputtype = 'text';
				        break;
					case 'decimal':
				        $inputtype = 'number';
				        break;
					case 'timestamp':
				        $inputtype = 'date';
				        break;
				    default:
				        $inputtype = 'text';
				}

			?>
	    <div class="form-group">
	    	<label for="{{$col->Field}}">{{$col->Field}}</label>
			@if($col->Field == 'cycle' || $col->Field == 'type')

				<select class="form-control" id="{{$col->Field}}" name="{{$col->Field}}">
	               <option value=>{{$col->Field}}</option>

		      	    @foreach($options[$col->Field] as $key => $val)

	               <option value="{{$val}}">{{$val}}</option>

		            @endforeach
		      	</select>

			@else
				<input type="{{$inputtype}}" class="form-control" id="{{$col->Field}}" name="{{$col->Field}}" placeholder="{{$col->Field}}" 
				@if($col->Field == 'enddate')
					value = {{ date('Y-m-d', time() + (10 * 365 * 24 * 60 * 60)) }}
				@elseif($inputtype == 'date')
					value = {{ date('Y-m-d', time()) }}
				@endif
				>
	      	@endif
	    </div>
    	@endforeach

		<div>
			<button type="button" id="verify" class="btn btn-warning">Verify</button>
		</div>
    	<table class="table" id="myTable">
            <thead>
                <tr>
                    <th>Seq</th>
                    <th>Account</th>
                    <th>Dir</th>
                    <th>Rate</th>
                    <th>Result</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
            
                <tr class="sample">
                    <td>
                        <input style="width:50px;" class="seq" type="number" name="seq[]" min="0" step="1" value="1" />
                    </td>

                    <td>
                        <input style="width:200px;" type="text" class="acc toverify" list='accs' name="acc[]" />
                        <datalist id="accs">

                        @foreach($accs as $val)

                          <option value="{{ $val->accid }}">

                        @endforeach 
                    </td>

                    <td>
                        <input style="width:50px;" list="dirs" type="text" class="dir toverify" name="dir[]" />
                        <datalist id="dirs">
                            <option value="1">
                            <option value="-1">
                    </td>
                    <td>
                        <input style="width:100px;" type="number" class="rate toverify" name="rate[]" min="0" max="1"  />
                    </td>
                    <td class="result">
                    </td>
                    <td><input type="button" name="add" value="+" class="tr_clone_add"><input type="button" name="del" value="-" class="tr_clone_del"></td>
                </tr>
            </tbody>

        </table>

		<button type="submit" class="btn btn-primary">Submit</button>
	</form>
@endsection

@section('footer')

<script type="text/javascript">
$(document).ready(function() {
    var acc = {!! $accCalJSON !!};

    function updateCheck(){
    	var zerosum = 0;
        for(i = 0; i < $("input.acc").length; i++) { 
          // if($("input.acc:eq(" + i  + ")").val()){
          	cal = acc[ $("input.acc:eq(" + i  + ")").val() ].dir * acc[ $("input.acc:eq(" + i  + ")").val() ].gdir * $("input.dir:eq("+i+")").val() * $("input.rate:eq("+i+")").val();
          	zerosum += cal;
          	// console.log(cal, zerosum);
            $( "td.result:eq(" + i +")"  ).html(cal);
          // }
        }

        if(zerosum) {
            $('input.toverify').css('background-color', '#f00');
        } else {
            $('input.toverify').css('background-color', '#fff');
        }
    }

	$('button#verify').click( function() {
        updateCheck();
    });

    $('.tr_clone_add').click( function() {
        $(this).closest ('tr').clone(true).insertAfter($('#myTable tbody>tr:last'));
    	// seq = 1;
    	for(i = 0; i < $("input.seq").length; i++) { 
          // if($("input.acc:eq(" + i  + ")").val()){
			// if( seq < $("input.seq:eq(" + i  + ")").val() ) {
				// seq = $("input.seq:eq(" + i  + ")").val() ;
				$("input.seq:eq(" + i  + ")").val(i+1) ;
			// }
        }
        // seq++;
        // $('#myTable tbody>tr:last td:first>input.seq').val(seq);

    });

    $('input.tr_clone_del').click( function() {
        var rowCount = $('#myTable tbody>tr').length;
        // console.log(rowCount);
        if(rowCount > 1) {
            $(this).closest ('tr').remove ();
        } 

    	for(i = 0; i < $("input.seq").length; i++) { 
          // if($("input.acc:eq(" + i  + ")").val()){
			// if( seq < $("input.seq:eq(" + i  + ")").val() ) {
				// seq = $("input.seq:eq(" + i  + ")").val() ;
				$("input.seq:eq(" + i  + ")").val(i+1) ;
			// }
        }
    });

    // $("input.acc").focusout(function(){
    //     var zerosum = 0;
    //     for(i = 0; i < $("input.acc").length; i++) { 
    //       zerosum += acc[ $("input.acc:eq(" + i  + ")").val() ].dir * acc[ $("input.acc:eq(" + i  + ")").val() ].gdir * $("input.dir:eq("+i+")").val() ;
    //     }

    //     // console.log(zerosum);
    //     updateCheck();
    //     if(zerosum) {
    //         $(this).closest('td').next('td').css('background-color', '#f00');
    //     } else {
    //         $(this).closest('td').next('td').css('background-color', '#fff');
    //     }

    // });

    // $("input.dir").focusout(function(){
    //     var zerosum = 0;
    //     for(i = 0; i < $("input.acc").length; i++) { 
    //       zerosum += acc[ $("input.acc:eq(" + i  + ")").val() ].dir * acc[ $("input.acc:eq(" + i  + ")").val() ].gdir * $("input.dir:eq("+i+")").val() ;
    //     }

    //     // console.log(zerosum);
    //     updateCheck();
    //     if(zerosum) {
    //         $(this).closest('td').css('background-color', '#f00');
    //     } else {
    //         $(this).closest('td').css('background-color', '#fff');
    //     }

    // });

});
</script>

@endsection