@extends('layouts.master')

@section('content')
<h1>Recurring</h1>
<div class="col-sm-8 blog-main">
<form class="form-inline" id="recurringForm" method="post" action="/recurring/post">
    {{csrf_field()}}
    <table class="table">
        <thead>
            <th>No</th>
            <th>Name</th>
            <th>Start Date</th>
            <th>End Date</th>
            <th>Day to Post</th>
            <th>Cycle</th>
            <th>Type</th>
            <th>Vendor</th>
            <th>Material</th>
            <th>amt</th>
            <th>clearing</th>
            <th>Lastposted_at</th>
            <th>created_at</th>
            <th>updated_at</th>
            <th>Action</th>
        </thead>
        <tbody>
            
            @foreach($recurrings as $recurring)
                <tr>
                <td>{{ $recurring->id }}</td>
                <td>{{ $recurring->name }}</td>
                <td>{{ $recurring->startdate }}</td>
                <td>{{ $recurring->enddate }}</td>
                <td>{{ $recurring->recurringdate }}</td>
                <td>{{ $recurring->cycle }}</td>
                <td>{{ $recurring->type }}</td>
                <td>{{ $recurring->vendor }}</td>
                <td>{{ $recurring->material }}</td>
                <td>{{ $recurring->amt }}</td>
                <td>{{ $recurring->clearing }}</td>
                <td>{{ $recurring->lastposted_date }}</td>
                <td>{{ $recurring->created_at }}</td>
                <td>{{ $recurring->updated_at }}</td>
                <td>
                    <a href="/recurring/delete/{{ $recurring->id }}">X</a> || 
                    <input type="checkbox" id="checkBox" name="id[]" value='{{ $recurring->id }}'>
                </td>

                </tr>
            @endforeach

        </tbody>

    </table>
    <button type="submit" class="btn btn-danger" name="mode" value="2">Post selected</button>
</form>

    <a class="btn btn-primary" href="/recurring/add" role="button">Add</a>
</div>
@endsection


@section('footer')

<script type="text/javascript">
    
</script>

@endsection
