@extends('layouts.master')

@section('content')

<form class="form-inline" id="searchForm" method="get">
<!--<form class="form-inline" id="searchForm" method="get" onsubmit="return false;">-->
        <div class="form-group ">
                <input type="hidden" name="mode" value="search" />
                <select id="fromdoc" name="fromdoc" class="form-control">
                    <option value=>FromDoc</option>
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
                <select id="trtype" name="trtype" class="form-control">
                    <option value=>Tr.Type</option>
                    @foreach($trtypes as $val)
                        <option value="{{$val->transaction_type}}"
                            @if($val->transaction_type == $trtype)
                                 selected
                            @endif
                        >
                            {{ $val->transaction_type }}
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
                <select id="vendor" name="vendor" class="form-control">
                    <option value=>Vendor</option>
                    @foreach($vendors as $val)
                        <option value="{{$val->amount_type}}"
                            @if($val->amount_type == $vendor)
                                 selected
                            @endif
                        >
                            {{ $val->amount_type }}
                        </option>
                    @endforeach
                </select>
                <input type="text" class="form-control" id="material" name="material" 
                @if($material ))
                    value="{{ $material }}"
                @else
                    placeholder="material"
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
            <th>Dic</th>
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
        'trtype' => $trtype,
        'vendor' => $vendor,
        'material' => $material,
        'ttype' => $ttype
        ])->links() }}
</ul>

@endsection


@section('layouts.footer')

<script type="text/javascript"></script>

@endsection