@extends('layouts.master')

@section('content')
<h1>Financial Transactions</h1>
<form class="form-inline" id="searchForm" method="get">
<!--<form class="form-inline" id="searchForm" method="get" onsubmit="return false;">-->
    <div class="form-group ">
        <input type="hidden" name="mode" value="search" />
        Happened at <input style="width: 130px;" type="date" class="form-control" id="fdate" name="fdate" value="{{ $fdate }}" > ~ 
        <input style="width: 130px;" type="date" class="form-control" id="tdate" name="tdate" value="{{ $tdate }}" >

        <select id="fromdoc" name="fromdoc" class="form-control">
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
        
        <select id="ttype" name="ttype" class="form-control">
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

        <select id="acc" name="acc" class="form-control">
            <option value=>Acount</option>
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

        <input type="text" class="form-control" id="amt" name="amt" 
        @if($amt ))
            value="{{ $amt }}"
        @else
            placeholder="amount"
        @endif

        />



        <input type="text" class="form-control" id="material" name="material" 
        
        @if($material ))
            value="{{ $material }}"
        @else
            placeholder="material"
        @endif

        />
        Created at <input style="width: 130px;" type="date" class="form-control" id="cfdate" name="cfdate" value="{{ $cfdate }}" > ~ 
        <input style="width: 130px;" type="date" class="form-control" id="ctdate" name="ctdate" value="{{ $ctdate }}" >

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
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
        
        @foreach($fitransactions as $fitransaction)
            <tr>
                <td><a href='/fitransactions/show/{{ $fitransaction->keyv }}'>{{ $fitransaction->keyv }}</a></td>
                <td>{{ $fitransaction->pdate }}</td>
                <td>{{ $fitransaction->fromdoc }}</td>
                <td>{{ $fitransaction->acc }}</td>
                <td>{{ $fitransaction->amt }}</td>
                <td>{{ $fitransaction->qty }}</td>
                <td>{{ $fitransaction->mp }}</td>
                <td>{{ $fitransaction->material }}</td>
                <td>{{ $fitransaction->orderid }}</td>
                <td>{{ $fitransaction->itemid }}</td>
                <td>{{ $fitransaction->clearing }}</td>
                <td>{{ $fitransaction->remark }}</td>
                <td>{{ $fitransaction->ttype }}</td>
                <td>{{ $fitransaction->brand }}</td>
                <td>{{ $fitransaction->ba }}</td>
                <td>
                    @if(!isset($fitransaction->aid))
                        <a href='//dev.irealook.com/dist_photocost.php?aid={{ $fitransaction->keyv }}' target='_blank'>Distribute?</a>
                    @elseif($fitransaction->posted_at)
                        Distributed
                    @elseif(isset($fitransaction->aid) && !isset($fitransaction->posted_at))
                        <a href='//dev.irealook.com/dist_photocost.php?aid={{ $fitransaction->keyv }}' target='_blank'>Distributing</a>
                    @endif

                </td>

            </tr>
        @endforeach

        </tbody>

    </table>

</div>

<ul class="pagination">
    {{ 
        $fitransactions->appends([
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

<script type="text/javascript"></script>

@endsection