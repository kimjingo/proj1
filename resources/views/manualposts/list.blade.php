@extends('layouts.master')

@section('content')
<form class="form-inline" id="searchForm" method="get">
<!--<form class="form-inline" id="searchForm" method="get" onsubmit="return false;">-->
    <div class="form-group ">
        <input type="hidden" name="mode" value="search" />
        Happened at <input style="width: 130px;" type="date" class="form-control" id="fdate" name="fdate" value="{{ $fdate }}" > ~ 
        <input style="width: 130px;" type="date" class="form-control" id="tdate" name="tdate" value="{{ $tdate }}" >

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

        <select id="vendor" name="vendor" class="form-control">
            <option value=>Type</option>
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

        <input type="text" class="form-control" id="remark" name="remark" 
        
        @if($remark ))
            value="{{ $remark }}"
        @else
            placeholder="remark"
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
    <form class="form-inline" id="searchForm" method="post" action="manualposts/postbybatch">
    <a class="btn btn-primary" href="/manualposts/create" role="button">Add</a>
    <table class="table table-bordered table-striped">
        <thead>
            <th>Date</th>
            <th>Amount</th>
            <th>Where</th>
            <th>What</th>
            <th>Why</th>
            <th>Type</th>
            <th>Ref#</th>
            <th>Posted at</th>
            <th>Who paid</th>
            <th>BA</th>
            <th>Created at</th>
            <th>Updated at</th>
            <th>Action</th>
        </thead>
        <tbody>
            
            @foreach($manualinputs as $manualinput)
                <tr>
                
                    <td>{{ $manualinput->pdate }}</td>
                    <td class="number-align">{{ $manualinput->amt }}</td>
                    <td>{{ $manualinput->mp }}</td>
                    <td>{{ $manualinput->material }}</td>
                    <td>{{ $manualinput->remark }}</td>
                    <td>{{ $manualinput->ttype }}</td>
                    <td>{{ $manualinput->checkno }}</td>
                    <td>{{ $manualinput->posting }}</td>
                    <td>{{ $manualinput->paidby }}</td>
                    <td>{{ $manualinput->ba }}</td>
                    <td>{{ $manualinput->created_at }}</td>
                    <td>{{ $manualinput->updated_at }}</td>
                    <td>
                        <a href="/manualposts/delete/{{ $manualinput->id }}">X</a>
                        ||
                        <a href="/manualposts/edit/{{ $manualinput->id }}">=3</a> ||
                        <a href="/manualposts/post/{{ $manualinput->id }}">=></a> ||
                        <input type="checkbox" id="checkBox" name="id[]" value='{{$manualinput->id}}'>
                    </td>

                </tr>
            @endforeach

        </tbody>

    </table>
    <input type="submit" id="submit" class="btn btn-default" value="Post by Batch" />
    </form>

</div>

<ul class="pagination">
    {{ $manualinputs->appends([
        'fdate' => $fdate,
        'tdate' => $tdate,
        'ba' => $ba,
        'ttype' => $ttype,
        'amt' => $amt,
        'remark' => $remark,
        'vendor' => $vendor,
        'material' => $material,
        'isPosted' => $isPosted
        ])->links() }}
</ul>

@endsection


@section('footer')

<script type="text/javascript"></script>

@endsection
