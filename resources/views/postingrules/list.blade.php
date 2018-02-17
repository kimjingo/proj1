@extends('layouts.master')

@section('content')
<h1>Posting Rules</h1>
<form class="form-inline" id="searchForm" method="get">
<!--<form class="form-inline" id="searchForm" method="get" onsubmit="return false;">-->
        <div class="form-group ">
                <input type="hidden" name="mode" value="search" />
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
                <select id="att" name="att" class="form-control">
                    <option value=>Type1</option>
                    @foreach($atts as $val)
                        <option value="{{$val->att}}"
                            @if($val->att == $att)
                                 selected
                            @endif
                        >
                            {{ $val->att }}
                        </option>
                    @endforeach
                </select>

                <select id="ttype" name="ttype" class="form-control">
                    <option value=>Type2</option>
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

                <select id="aat" name="aat" class="form-control">
                    <option value=>Vendor</option>
                    @foreach($aats as $val)
                        <option value="{{$val->aat}}"
                            @if($val->aat == $aat)
                                 selected
                            @endif
                        >
                            {{ $val->aat }}
                        </option>
                    @endforeach
                </select>

                <select id="aad" name="aad" class="form-control">
                    <option value=>Material</option>
                    @foreach($aads as $val)
                        <option value="{{$val->aad}}"
                            @if($val->aad == $aad)
                                 selected
                            @endif
                        >
                            {{ $val->aad }}
                        </option>
                    @endforeach
                </select>



                <input type="text" class="form-control" id="material" name="material" 
                @if($material ))
                    value="{{ $material }}"
                @else
                    placeholder="keyword"
                @endif
                />
                <input type="submit" id="submit" class="btn btn-default" value="SEARCH" />
        </div>
</form>

<div class="col-sm-8 blog-main">
    <a class="btn btn-primary" href="/postingrules/create" role="button">Add</a>
    <table class="table table-bordered table-striped">
        <thead>
            <th>DocType</th>
            <th>Type1</th>
            <th>Type2</th>
            <th>Vendor</th>
            <th>Material</th>
            <th>Seq</th>
            <th>Acc</th>
            <th>Dir</th>
            <th>Rate</th>
            <th>Action</th>
        </thead>
        <tbody>
            
            @foreach($rules as $rule)
                <tr>
                
                    <td>{{ $rule->fromdoc }}</td>
                    <td class="number-align">{{ $rule->transaction_type }}</td>
                    <td>{{ $rule->ttype }}</td>
                    <td>{{ $rule->amount_type }}</td>
                    <td>{{ $rule->amount_description }}</td>
                    <td>{{ $rule->aseq }}</td>
                    <td>{{ $rule->acc }}</td>
                    <td>{{ $rule->dir }}</td>
                    <td>{{ $rule->rate }}</td>
                    <td>
                        <a href="/postingrules/duplicate/{{ $rule->no }}">E3</a>
                        ||
                        <a href="/postingrules/edit/{{ $rule->no }}">=3</a> 
                    </td>

                </tr>
            @endforeach

        </tbody>

    </table>

</div>

<ul class="pagination">
    {{ $rules->appends([
        'fromdoc'=> $fromdoc,
        'att' => $att,
        'aat' => $aat,
        'aad' => $aad,
        'material' => $material,
        'ttype' => $ttype
        ])->links() }}
</ul>

@endsection


@section('layouts.footer')

<script type="text/javascript">
    
</script>

@endsection