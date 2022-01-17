@extends('layouts.app')

@section('title', ' the post')

@section('content')
<form   method="POST"
        action="{{ route('posts.update', ['post' => $post->id]) }}"
        enctype="multipart/form-data"
>
    @csrf
    @method('PUT')
    @include('posts.partials.form')
    <div><input type="submit" value="Update" class="btn btn-primary"></div>
</form>
@endsection