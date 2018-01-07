@extends('layouts.master')

@section('content')
<div class="col-sm-8 blog-main">
    <table class="table">
        <thead>
            <th>Date</th><th>Parent</th><th>Child</th><th>Amount</th><th>Delete</th>
        </thead>
        <tbody>
            
            @foreach($bscheckpoints as $bscheckpoint)
                <tr>
                    
                <td>{{ $bscheckpoint->checkdate }}</td>
                <td>{{ $bscheckpoint->accid }}</td>
                <td>{{ $bscheckpoint->toreconcile }}</td>
                <td class="number-align">{{ $bscheckpoint->amt }}</td>
                <td><a href="/bscheckpoint/delete/{{ $bscheckpoint->id }}">X</a></td>

                </tr>
            @endforeach

        </tbody>

    </table>

    <a class="btn btn-primary" href="/bscheckpoint/add" role="button">Add</a>
</div>
@endsection


@section('layouts.footer')

<script type="text/javascript"></script>

@endsection
