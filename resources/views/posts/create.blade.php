@extends('layouts.master')

@section('content')
  <div class="col-sm-8 blog-main">

    <h1>Create a post </h1>

    <form method="POST" action="/posts">
      {{ csrf_field() }}
      <div class="form-group">
        <label for="title">Title</label>
        <input type="text" class="form-control" id="title" name="title" aria-describedby="emailHelp" placeholder="Enter title">
        <small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone else.</small>
      </div>
      <div class="form-group">
        <label for="body">Your post here</label>
        <textarea class="form-control" id="body" name="body" placeholder="Input your thoughts"></textarea>
      </div>
      <button type="submit" class="btn btn-primary">Submit</button>
    </form>

  </div>

@endsection