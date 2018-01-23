@extends('layouts.master')

@section('content')

<form class="form-inline" id="searchForm" method="get">
<!--<form class="form-inline" id="searchForm" method="get" onsubmit="return false;">-->
    <div class="form-group ">
        <input type="hidden" name="mode" value="search" />
        <input style="width: 130px;" type="date" class="form-control" id="fdate" name="fdate" value="{{ $fdate }}" > ~ 
        <input style="width: 130px;" type="date" class="form-control" id="tdate" name="tdate" value="{{ $tdate }}" >
        <select id="ba" name="ba" class="form-control" style="width: 70px;">
            <option value=>B/A</option style="width: 70px;">
            @foreach($bas as $val)
                <option style="width: 70px;" value="{{$val->ba}}"
                    @if($val->ba == $ba)
                         selected
                    @endif
                >
                    {{ $val->ba }}
                </option>
            @endforeach
        </select>

        
        <select id="ttype" name="ttype" class="form-control">
            <option value=>T.Type</option>
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

        <select id="atype" name="atype" class="form-control">
            <option value=>Amount Type</option>
            @foreach($atypes as $val)
                <option value="{{$val->atype}}"
                    @if($val->atype == $atype)
                         selected
                    @endif
                >
                    {{ $val->atype }}
                </option>
            @endforeach
        </select>

        <select id="adesc" name="adesc" class="form-control">
            <option value=>Description</option>
            @foreach($adescs as $val)
                <option value="{{$val->adesc}}"
                    @if($val->adesc == $adesc)
                         selected
                    @endif
                >
                    {{ $val->adesc }}
                </option>
            @endforeach
        </select>

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

        <input type="text" class="form-control" id="amt" name="amt" 
        @if($amt ))
            value="{{ $amt }}"
        @else
            placeholder="amount"
        @endif

        />

        <input type="text" class="form-control" id="sku" name="sku" 
        
        @if($sku ))
            value="{{ $sku }}"
        @else
            placeholder="sku"
        @endif

        />
        <input type="submit" id="submit" class="btn btn-default" value="SEARCH" />

    </div>
</form>

<form class="form-inline" id="deactivateForm" method="post" action="/apay/post">
    {{csrf_field()}}
<div class="col-sm-8 blog-main">
    <a class="btn btn-primary" href="/postingrules/create" role="button">Add</a>
    <a class="btn btn-warning" href="/apay/showall" role="button">Show all</a>
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>Settlement_id</th>
                <th>B/A</th>
                <th>Date</th>
                <th>Type</th>
                <th>Atype</th>
                <th>Description</th>
                <th>amt</th>
                <th>sku</th>
                <th>qty</th>
                <th>order_id</th>
                <th>item_id</th>
                <th>CNT</th>

                <th>Action</th>
            </tr>
        </thead>
        <tbody>
        
        @foreach($apays as $apay)
            <tr
            @if($apay->cnt)
                 class='success'
            @endif
            >
                <td>{{ $apay->settlement_id }}</td>
                <td>{{ $apay->ba }}</td>
                <td>{{ $apay->posted_date }}</td>
                <td>{{ $apay->transaction_type }}</td>
                <td>{{ $apay->amount_type }}</td>
                <td>{{ $apay->amount_description }}</td>
                <td>{{ $apay->amount }}</td>
                <td>{{ $apay->sku }}</td>
                <td>{{ $apay->quantity_purchased }}</td>
                <td>{{ $apay->order_id }}</td>
                <td>{{ $apay->order_item_code }}</td>
                <td>{{ $apay->cnt}}</td>

                <td>
                    <a href="/apay/edit/{{ $apay->no }}">Single post</a>
                    ||
                    <a href="/postingrules/addwithdata?fromdoc=apay2&ttype={{$apay->transaction_type}}&atype={{$apay->amount_type}}&adesc={{$apay->amount_description}}">Make a rule</a> 
                    ||
                    <input type="checkbox" id="checkBox" name="no[]" value='{{$apay->no}}'>
                </td>

            </tr>
        @endforeach

        </tbody>

    </table>

</div>
<button type="submit" class="btn btn-default" name="mode" value="1">Deactivate</button>
<button type="submit" class="btn btn-danger" name="mode" value="2">Post selected</button>
<button type="submit" class="btn btn-warning" name="mode" value="3">Post by rule</button>
</form>

<ul class="pagination">
    {{ 
        $apays->appends([
            'fdate' => $fdate,
            'tdate' => $tdate,
            'ba' => $ba,
            'ttype' => $ttype,
            'isPosted' => $isPosted,
            'amt' => $amt]
            )->links() 
    }}
</ul>

@endsection


@section('layouts.footer')

<script type="text/javascript"></script>

@endsection