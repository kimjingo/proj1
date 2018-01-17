@extends('layouts.master')

@section('content')

<form class="form-inline" id="searchForm" method="get" action=/postingrules/store>
<!--<form class="form-inline" id="searchForm" method="get" onsubmit="return false;">-->
        <div class="form-group ">
                <input type="hidden" name="mode" value="search" />
                <select id="fromdoc" name="fromdoc" class="form-control">
                    <option>FromDoc</option>
                    @foreach($fromdocs as $fdoc)
                        <option value="{{$fdoc->fromdoc}}">
                        
                            {{ $fdoc->fromdoc }}

                        </option>
                    @endforeach

                </select>
                <select id="trtype" name="trtype" class="form-control">
                    <option>Tr.Type</option>
                    @foreach($trtypes as $tr)
                        <option value="{{$tr->transaction_type}}">
                        
                            {{ $tr->transaction_type }}

                        </option>
                    @endforeach
                </select>
                <select id="ttype" name="ttype" class="form-control">
                    <option>T.Type</option>
                    @foreach($ttypes as $tt)
                        <option value="{{$tt->ttype}}">
                        
                            {{ $tt->ttype }}

                        </option>
                    @endforeach
                </select>
                <select id="vendor" name="vendor" class="form-control">
                    <option>Vendor</option>
                    @foreach($vendors as $vr)
                        <option value="{{$vr->amount_type}}">
                        
                            {{ $vr->amount_type }}

                        </option>
                    @endforeach
                </select>
                <input type="text" list="styles" class="form-control" id="style" name="style" <? echo ( isset( $material ) && !empty( $material )) ? "value='".$material."'" : "placeholder='Style#'"; autofocus />
<!--                <input type="text" list="colors" class="form-control" id="color" name="color" <? //echo ( isset( $color ) && !empty( $color )) ? "value='".$color."'" : "placeholder='Color#'"; ?> autofocus />-->
<!--                <input type="text" list="sizes" class="form-control" id="size" name="size" <? //echo ( isset( $size ) && !empty( $size )) ? "value='".$size."'" : "placeholder='Size#'"; ?> autofocus />-->
                <input type="submit" id="submit" class="btn btn-default" value="SAVE" />
        </div>
</form>

@endsection


@section('layouts.footer')

<script type="text/javascript"></script>

@endsection