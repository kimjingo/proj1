@extends('layouts.master')

@section('content')

<div class="col-sm-8 blog-main">
    <h1>A Financial Transaction</h1>

    <?php $len = count($columns) ; ?>

    
    <table class="table">
        <tr>
            <th>Doc Type</th><th>Value</th>
        </tr>
        
        @for($i=0;$i<$len;$i++)
        <tr>
            <td>
                {{ $columns[$i] }}
            </td>
            <td>
                {{ $fitransactions[0]->{$columns[$i]} }}
            </td>
        </tr>
        @endfor
    
    </table>
<a class="button" href="//dev.irealook.com/dist_photocost.php?{{fitransactions[0]->{$columns[20]}}">Distribute</a>
</div>


@endsection


@section('footer')

<script type="text/javascript"></script>

@endsection
