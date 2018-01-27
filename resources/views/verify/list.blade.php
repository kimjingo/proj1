@extends('layouts.master')

@section('content')

<div class="col-sm-8 blog-main">
@if(isset($zerosum))
    @if($zerosum->result)
    <div class="alert alert-primary" role="alert">
    @else
    <div class="alert alert-danger" role="alert">
    @endif
      Zero Sum : 
        @if($zerosum->result != 0)
            <a href='/verify/bal'> {{$zerosum->result}} </a>
        @else
            {{$zerosum->result}}
        @endif

    </div>
@endif

@if(isset($bals))
    <div class="jumbotron">
        <table class="table">
            <tr>
                <th>Doc Type</th><th>Zero Sum</th>
            </tr>
            
            @foreach($bals as $val)
            <tr>
                <td>
                    {{$val->fromdoc}}
                </td>
                <td>
                    {{$val->amt}}
                </td>
            </tr>
            @endforeach
        
        </table>
    </div>
@endif
</div>


@endsection


@section('layouts.footer')

<script type="text/javascript"></script>

@endsection
