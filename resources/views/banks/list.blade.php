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

        <select id="vendor" name="vendor" class="form-control">
            <option value=>Vendor</option>
            @foreach($mps as $val)
                <option value="{{$val->mp}}"
                    @if($val->mp == $vendor)
                         selected
                    @endif
                >
                    {{ $val->mp }}
                </option>
            @endforeach
        </select>
        
        <select id="ttype" name="ttype" class="form-control">
            <option value=>T.Type</option>
            @foreach($ttypes as $val)
                <option value="{{$val->TType}}"
                    @if($val->TType == $ttype)
                         selected
                    @endif
                >
                    {{ $val->TType }}
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
        <input type="text" class="form-control" id="keyword" name="keyword" 
        @if(isset( $keyword ))
            value="{{ $keyword }}"
        @else
            placeholder="keyword"
        @endif
         autofocus />
        <input type="text" class="form-control" id="amt" name="amt" 
        @if($amt ))
            value="{{ $amt }}"
        @else
            placeholder="amount"
        @endif

        />
        <input type="submit" id="submit" class="btn btn-default" value="SEARCH" />

    </div>
</form>

<form class="form-inline" id="deactivateForm" method="post" action="/bank/deactivate">
    {{csrf_field()}}
<div class="col-sm-8 blog-main">
    <a class="btn btn-primary" href="/postingrules/create" role="button">Add</a>
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>Date</th>
                <th>Amount</th>
                <th>Type</th>
                <th>Vendor</th>
                <th>Material</th>
                <th>B/A</th>
                <th>Bank Acc</th>
                <th>Remark</th>
                <th>Ref#</th>
                <th>Posted at</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
        
        @foreach($banks as $bank)
            <tr>
            
                <td>{{ $bank->tDate }}</td>
                <td class="number-align">{{ $bank->amt }}</td>
                <td>{{ $bank->TType }}</td>
                <td>{{ $bank->mp }}</td>
                <td>{{ $bank->material }}</td>
                <td>{{ $bank->ba }}</td>
                <td>{{ $bank->accno }}</td>
                <td>{{ $bank->tDesc }}</td>
                <td>{{ $bank->Checkno }}</td>
                <td>{{ $bank->postingflag }}</td>
                <td>
                    <a href="/banks/delete/{{ $bank->no }}">X</a>
                    ||
                    <a href="/postingrules?fromdoc=bank&trtype={{$bank->TType}}&ttype={{$bank->mp}}&vendor={{$bank->mp}}&material={{$bank->material}}">=3</a> 
                    ||
                    <input type="checkbox" id="checkBox" name="no[]" value='{{$bank->no}}'>
                </td>

            </tr>
        @endforeach

        </tbody>

    </table>

</div>
<input type="submit" id="submit" class="btn btn-default" value="Deactivate" />
</form>

<ul class="pagination">
    {{ $banks->appends(['fdate' => $fdate,
'tdate' => $tdate,
'ba' => $ba,
'vendor' => $vendor,
'ttype' => $ttype,
'isPosted' => $isPosted,
'keyword' => $keyword,
'amt' => $amt]
        )->links() }}
</ul>

@endsection


@section('layouts.footer')

<script type="text/javascript"></script>

@endsection