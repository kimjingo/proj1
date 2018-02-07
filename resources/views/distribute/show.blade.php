@extends('layouts.master')

@section('content')

<div class="col-sm-8 blog-main">
    <h1>A Financial Transaction</h1>

<form class="form-inline" id="deactivateForm" method="post" action="/distribute/post/{{$id}}">
    {{csrf_field()}}


    <?php $len = count($data2distribute['columns']) ; ?>

    
    <table class="table">
        <tr>
            <th>Doc Type</th><th>Value</th>
        </tr>
        
        @for($i=0;$i<$len;$i++)
        <tr>
            <td>
                {{ $data2distribute['columns'][$i] }}
            </td>
            <td>
                {{ $data2distribute['todistribute']->{$data2distribute['columns'][$i]} }}
            </td>
        </tr>
        @endfor
    
    </table>

    <table class="table">
        <thead>
            <tr>
                <th>Brand</th><th>WSKU</th><th>WAmt</th><th>Matid</th><th>Amt</th>
            </tr>
        </thead>
        <tbody>
            @php
                $total = 0;
            @endphp

                @foreach($data2distribute['matdata'] as $key => $mat)
                    <tr>
                        <td>
                            {{ $mat['vendor'] }}
                        </td>
                        <td>
                            {{ $mat['wsku'] }}
                        </td>
                        <td>
                            {{ $mat['wamt'] }}
                        </td>
                        <td>
                            {{ $mat['matid'] }}
                        </td>
                        <td>
                            {{ $mat['amt'] }}
                        </td>
                    </tr>
                    @php
                        $total = $total + $mat['amt'];
                    @endphp
                @endforeach

                <tr><td colspan=2>Total</td><td>{{$data2distribute['originaltotal']}}</td><td></td><td>{{$total}}</td></tr>
        </tbody>    
    
    </table>

    @if( round($total,3) == round($data2distribute['originaltotal'],3) )
        <button type="submit" class="btn btn-primary">Post to Distribute</button>
    @endif
  
</form>
</div>


@endsection


@section('footer')

<script type="text/javascript"></script>

@endsection
