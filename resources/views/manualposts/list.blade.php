@extends('layouts.master')

@section('content')
<div class="col-sm-8 blog-main">
    <a class="btn btn-primary" href="/manualposts/create" role="button">Add</a>
    <table class="table">
        <thead>
            <th>Date</th>
            <th>Amount</th>
            <th>Where</th>
            <th>What</th>
            <th>Why</th>
            <th>Type</th>
            <th>Ref#</th>
            <th>Posted at</th>
            <th>Who paid</th>
            <th>BA</th>
            <th>Created at</th>
            <th>Updated at</th>
            <th>Action</th>
        </thead>
        <tbody>
            
            @foreach($manualinputs as $manualinput)
                <tr>
                
                    <td>{{ $manualinput->pdate }}</td>
                    <td class="number-align">{{ $manualinput->amt }}</td>
                    <td>{{ $manualinput->mp }}</td>
                    <td>{{ $manualinput->material }}</td>
                    <td>{{ $manualinput->remark }}</td>
                    <td>{{ $manualinput->ttype }}</td>
                    <td>{{ $manualinput->checkno }}</td>
                    <td>{{ $manualinput->posting }}</td>
                    <td>{{ $manualinput->paidby }}</td>
                    <td>{{ $manualinput->ba }}</td>
                    <td>{{ $manualinput->created_at }}</td>
                    <td>{{ $manualinput->updated_at }}</td>
                    <td>
                        <a href="/manualposts/delete/{{ $manualinput->id }}">X</a>
                        ||
                        <a href="/manualposts/edit/{{ $manualinput->id }}">=3</a> 
                    </td>

                </tr>
            @endforeach

        </tbody>

    </table>

</div>
@endsection


@section('layouts.footer')

<script type="text/javascript"></script>

@endsection
