@extends('layouts.master')

@section('content')
<div class="col-sm-8 blog-main">
    <div class="alert alert-
    @if($total)
        danger
    @else
        primary
    @endif
    " role="alert">
      Zero Sum : {{$total}}
    </div>

    <div class="jumbotron">
        <table class="table">
            <tr>
                <th>Doc Type</th><th>Zero Sum</th>
            </tr>

            @foreach($bals as val)
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

</div>

@endsection


@section('layouts.footer')

<script type="text/javascript"></script>

@endsection
