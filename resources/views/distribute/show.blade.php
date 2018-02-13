@extends('layouts.master')

@section('content')

<div class="col-sm-8 blog-main">
    <h1>A Financial Transaction to Distribute</h1>

<form class="form-inline" id="deactivateForm" method="post" action="/distribute/post/{{ $d2d['todistribute']->aid }}">
    {{csrf_field()}}

    <table class="table">
        <tr>
            <th>Doc Type</th><th>Value</th>
        </tr>
        
        @foreach($d2d['todistribute'] as $key => $val)
        <tr>
            <td>
                {{ $key }}
            </td>
            <td>
                {{ $val }}
            </td>
        </tr>
        @endforeach
    
    </table>

    <table class="table">
        <thead>
            <tr>
                <th>No</th><th>Brand</th><th>MAT</th><th>QTY</th><th>Rate</th><th>Amt</th>
            </tr>
        </thead>
        <tbody>
            @php
                $total = 0;
            @endphp

                @foreach($d2d['matdata']['mats'] as $key => $mat)
                    <tr>
                        <td>
                            {{ $key }}
                        </td>
                        <td>
                            {{ $mat['brand'] }}
                        </td>
                        <td>
                            {{ $mat['matid'] }}
                        </td>
                        <td>
                            {{ $mat['qty'] }}
                        </td>
                        <td>
                            {{ $mat['rate'] }}
                        </td>
                        <td>
                            {{ $mat['amt'] }}
                        </td>
                    </tr>
                    @php
                        $total = $total + $mat['amt'];
                    @endphp
                @endforeach

                <tr><td colspan=2>Total</td><td>{{$d2d['todistribute']->amt}}</td><td></td><td>{{ $d2d['matdata']['rtotal'] }}</td><td>{{$total}}</td></tr>
        </tbody>    
    
    </table>

    @if( round($total,3) == round($d2d['todistribute']->amt,3) )
        <button type="submit" class="btn btn-primary">Post to Distribute</button>
    @endif
  
</form>
</div>


@endsection


@section('footer')

<script type="text/javascript"></script>

@endsection
