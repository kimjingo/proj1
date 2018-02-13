@extends('layouts.master')

@section('content')
<h1>Distribute Financial Transactions</h1>
<form class="form-inline" id="searchForm" method="get">
<!--<form class="form-inline" id="searchForm" method="get" onsubmit="return false;">-->
    <div class="form-group ">
        <input type="hidden" name="mode" value="search" />
        Happened at <input style="width: 130px;" type="date" class="form-control" id="fdate" name="fdate" value="{{ $fdate }}" > ~ 
        <input style="width: 130px;" type="date" class="form-control" id="tdate" name="tdate" value="{{ $tdate }}" >

        <select style="width: 130px;" id="fromdoc" name="fromdoc" class="form-control">
            <option value=>DocType</option>
            @foreach($fromdocs as $val)
                <option value="{{$val->fromdoc}}"
                    @if($val->fromdoc == $fromdoc)
                         selected
                    @endif
                >
                    {{ $val->fromdoc }}
                </option>
            @endforeach
        </select>

        <select id="ba" name="ba" class="form-control" style="width: 70px;">
            <option value=>B/A</option style="width: 70px;">
            @foreach($bas as $val)
                <option style="width: 70px;" value="{{$val}}"
                    @if($val == $ba)
                         selected
                    @endif
                >
                    {{ $val }}
                </option>
            @endforeach
        </select>
        
        <select style="width: 150px;" id="ttype" name="ttype" class="form-control">
            <option value=>Type</option>
            @foreach($ttypes as $val)
                <option value="{{$val->ttype}}"
                    @if($val->ttype == $ttype)
                         selected
                    @endif
                >
                    {{ $val->ttype }}
                </option>
            @endforeach
        </select>

        <select style="width: 150px;" id="acc" name="acc" class="form-control">
            <option value=>Account</option>
            @foreach($accs as $val)
                <option value="{{$val->accid}}"
                    @if($val->accid == $acc)
                         selected
                    @endif
                >
                    {{ $val->accid }}
                </option>
            @endforeach
        </select>


        <select id="vendor" name="vendor" class="form-control">
            <option value=>Vendor</option>
            @foreach($vendors as $val)
                <option value="{{$val->vendor}}"
                    @if($val->vendor == $vendor)
                         selected
                    @endif
                >
                    {{ $val->vendor }}
                </option>
            @endforeach
        </select>

        <input style="width: 100px;" type="text" class="form-control" id="amt" name="amt" 
        @if($amt ))
            value="{{ $amt }}"
        @else
            placeholder="amount"
        @endif

        />

        <input style="width: 130px;" type="text" class="form-control" id="material" name="material" 
        
        @if($material ))
            value="{{ $material }}"
        @else
            placeholder="material"
        @endif

        />
        Created at <input style="width: 130px;" type="date" class="form-control" id="cfdate" name="cfdate" value="{{ $cfdate }}" > ~ 
        <input style="width: 130px;" type="date" class="form-control" id="ctdate" name="ctdate" value="{{ $ctdate }}" >

        <input type="radio" name="isPosted" value=0
        @if($isPosted==0)
         checked 
        @endif
        >All <input type="radio" name="isPosted" value=1 
        @if($isPosted==1)
         checked 
        @endif
        >Posted <input type="radio" name="isPosted" value=2
        @if($isPosted==2)
         checked 
        @endif
        >Not Posted
        
        <input type="submit" id="submit" class="btn btn-default" value="SEARCH" />

    </div>
</form>

<div class="col-sm-8 blog-main">
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>No</th>
                <th>Date</th>
                <th>DocType</th>
                <th>ACC_id</th>
                <th>amt</th>
                <th>qty</th>
                <th>Vendor</th>
                <th>Material</th>
                <th>order_id</th>
                <th>item_id</th>
                <th>clearing</th>
                <th>Remark</th>
                <th>Type</th>
                <th>Brand</th>
                <th>B/A</th>
                <th>Mode</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
        
        @foreach($distribute as $row)
            @php
                $ddd = json_decode($row->dd);
            @endphp
            <tr>
                <td><a href='/distribute/show/{{ $row->aid }}'>{{ $row->aid }}</a></td>
                <td>{{ $row->pdate }}</td>
                <td>{{ $row->fromdoc }}</td>
                <td>{{ $row->acc }}</td>
                <td>{{ $row->amt }}</td>
                <td>{{ $row->qty }}</td>
                <td>{{ $row->mp }}</td>
                <td>{{ $row->material }}</td>
                <td>{{ $row->orderid }}</td>
                <td>{{ $row->itemid }}</td>
                <td>{{ $row->clearing }}</td>
                <td>{{ $row->remark }}</td>
                <td>{{ $row->ttype }}</td>
                <td>{{ $row->brand }}</td>
                <td>{{ $row->ba }}</td>
                <td>{{ $ddd->table }}</td>
                <td>
                    {{ $row->posted_at }}
                    <!-- @if(isset($row->posted_at))
                        Distributed!
                    @elseif(isset($row->aid) && !isset($row->posted_at))
                        <a href='/distribute/show/{{ $row->aid }}'>
                            Distribute
                        </a> 
                    @endif -->

                </td>

            </tr>
        @endforeach

        </tbody>

    </table>

</div>

<ul class="pagination">
    {{ 
        $distribute->appends([
            'fdate' => $fdate,
            'tdate' => $tdate,
            'fromdoc' => $fromdoc,
            'ba' => $ba,
            'ttype' => $ttype,
            'vendor' => $vendor,
            'cfdate' => $cfdate,
            'ctdate' => $ctdate,
            'acc' => $acc,
            'amt' => $amt]
        )->links() 
    }}
</ul>

@endsection


@section('footer')

<script type="text/javascript">
    $("input[type=text]").focus(function(){
        $(this).width(200);
    });

    $("input[type=text]").focusout(function(){
        $(this).width(100);
    });

    //     fuction() { 
    //     console.log("aa");
    // });
</script>

@endsection