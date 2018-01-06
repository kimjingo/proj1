@extends('layouts.master')

@section('content')
<div class="col-sm-8 blog-main">
    <table class="table">
        <thead>
            <th>Parent</th><th>Child</th><th>Delete</th>
        </thead>
        <tbody>
            
            @foreach($reconciles as $reconcile)
                <tr>
                    
                <td>{{ $reconcile->accid }}</td>
                <td>{{ $reconcile->toreconcile }}</td>
                <td><a href="/reconcile/delete/{{ $reconcile->id }}">X</a></td>

                </tr>
            @endforeach

        </tbody>

    </table>

    <a class="btn btn-primary" href="/reconcile/add" role="button">Add</a>
</div>
@endsection


@section('layouts.footer')

<script type="text/javascript"></script>

@endsection
