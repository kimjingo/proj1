@extends('layouts.master')

@section('content')

<form class="form-inline" id="searchForm" method="get">
<!--<form class="form-inline" id="searchForm" method="get" onsubmit="return false;">-->
        <div class="form-group ">
                <input type="hidden" name="mode" value="search" />
                <select id="fromdoc" name="fromdoc" class="form-control">
                    <option>FromDoc</option>
                    @foreach($fromdocs as $fdoc)
                        <option value="{{$fdoc->fromdoc}}"
                            @if($fdoc->fromdoc == $fromdoc)
                                 selected
                            @endif
                        >
                        
                            {{ $fdoc->fromdoc }}

                        </option>
                    @endforeach

                </select>
                <select id="trtype" name="trtype" class="form-control">
                    <option>Tr.Type</option>
                    @foreach($trtypes as $tr)
                        <option value="{{$tr->transaction_type}}"
                            @if($tr->transaction_type == $trtype)
                                 selected
                            @endif
                        >
                        
                            {{ $tr->transaction_type }}

                        </option>
                    @endforeach
                </select>
                <select id="ttype" name="ttype" class="form-control">
                    <option>T.Type</option>
                    @foreach($ttypes as $tt)
                        <option value="{{$tt->ttype}}"
                            @if($tt->ttype == $ttype)
                                 selected
                            @endif
                        >
                        
                            {{ $tt->ttype }}

                        </option>
                    @endforeach
                </select>
                <select id="vendor" name="vendor" class="form-control">
                    <option>Vendor</option>
                    @foreach($vendors as $vr)
                        <option value="{{$vr->amount_type}}"
                            @if($vr->amount_type == $vendor)
                                 selected
                            @endif
                        >
                        
                            {{ $vr->amount_type }}

                        </option>
                    @endforeach
                </select>
                <input type="text" list="styles" class="form-control" id="style" name="style" <? echo ( isset( $material ) && !empty( $material )) ? "value='".$material."'" : "placeholder='Style#'"; autofocus />
<!--                <input type="text" list="colors" class="form-control" id="color" name="color" <? //echo ( isset( $color ) && !empty( $color )) ? "value='".$color."'" : "placeholder='Color#'"; ?> autofocus />-->
<!--                <input type="text" list="sizes" class="form-control" id="size" name="size" <? //echo ( isset( $size ) && !empty( $size )) ? "value='".$size."'" : "placeholder='Size#'"; ?> autofocus />-->
                <input type="submit" id="submit" class="btn btn-default" value="SEARCH" />
        </div>
</form>

<div class="col-sm-8 blog-main">
    <a class="btn btn-primary" href="/manualposts/create" role="button">Add</a>
    <table class="table table-bordered table-striped">
        <thead>
            <th>DocType</th>
            <th>Type1</th>
            <th>Type2</th>
            <th>Vendor</th>
            <th>Material</th>
            <th>Acc</th>
            <th>Dic</th>
            <th>Seq</th>
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
                    <td>{{ $rule->acc }}</td>
                    <td>{{ $rule->dir }}</td>
                    <td>{{ $rule->aseq }}</td>
                    <td>
                        <a href="/manualposts/delete/{{ $rule->no }}">X</a>
                        ||
                        <a href="/manualposts/edit/{{ $rule->no }}">=3</a> 
                    </td>

                </tr>
            @endforeach

        </tbody>

    </table>

</div>

<ul class="pagination">
    {{ $rules->links() }}
</ul>

@endsection


@section('layouts.footer')

<script type="text/javascript"></script>

@endsection