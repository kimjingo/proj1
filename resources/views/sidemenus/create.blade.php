@extends('layouts.master')

@section('content')
<h1>Add auto-posting rule</h1>

<form class="form-inline" id="deactivateForm" method="post" action="/sidemenus/store">
    {{csrf_field()}}

    <div class="col-sm-8 blog-main">
        <div class="form-group">
            <label for="menu">Top Menu</label>
            <input type='text' id="menu" name='menu' placeholder="Top Menu" />
        </div>
        <div class="form-group">
            <label for="displaymenu">Display Menu</label>
            <input type='text' id="displaymenu" name='displayname' placeholder="Name to display" />
        </div>
        <div class="form-group">
            <label for="link">Link</label>
            <input type='text' id="link" name='link' placeholder="url" />
        </div>
        <div class="form-group">
            <label for="seq">Seq</label>
            <input type='text' id="seq" name='seq' placeholder="order to display" />
        </div>

    </div>

    <input type="submit" id="submit" class="btn btn-danger" name='submit' value="Save" />
</form>

@endsection


@section('footer')

<script type="text/javascript">
</script>

@endsection