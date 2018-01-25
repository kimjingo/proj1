@extends('layouts.master')

@section('content')

<form class="form-inline" id="searchForm" method="get">
<!--<form class="form-inline" id="searchForm" method="get" onsubmit="return false;">-->
        <div class="form-group ">
                <input type="hidden" name="mode" value="search" />
                <select id="fromdoc" name="fromdoc" class="form-control">
                    <option value=>Menus</option>
                    @foreach($sidemenus as $val)
                        <option value="{{$val->menu}}"
                            @if($val->menu == $menu)
                                 selected
                            @endif
                        >
                            {{ $val->menu }}
                        </option>
                    @endforeach

                </select>
                <input type="submit" id="submit" class="btn btn-default" value="SEARCH" />
        </div>
</form>

<div class="col-sm-8 blog-main">
    <a class="btn btn-primary" href="/sidemenus/create" role="button">Add</a>
    <table class="table table-bordered table-striped">
        <thead>
            <th>ID</th>
            <th>Menu</th>
            <th>Display Name</th>
            <th>Link</th>
            <th>Seq</th>
            <th>Action</th>
        </thead>
        <tbody>
            @foreach($sidemenus as $sidemenu)
                <tr>
                
                    <td>{{ $sidemenu->id }}</td>
                    <td>{{ $sidemenu->menu }}</td>
                    <td>{{ $sidemenu->displayname }}</td>
                    <td>{{ $sidemenu->link }}</td>
                    <td>{{ $sidemenu->seq }}</td>
                    <td>
                        <a href="/sidemenus/delete/{{$sidemenu->id}}">X</a>
                        ||
                        <a href="/sidemenus/duplicate/{{$sidemenu->id}}">=3</a> 
                    </td>

                </tr>
            @endforeach


        </tbody>

    </table>

</div>

<ul class="pagination">
    {{ $sidemenus->appends([
        'menu'=> $menu
        ])->links() }}
</ul>

@endsection


@section('layouts.footer')

<script type="text/javascript"></script>

@endsection