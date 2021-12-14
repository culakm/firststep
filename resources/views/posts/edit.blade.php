@extends('layouts.app')

@section('title', ' the post')

@section('content')
<form action="{{ route('posts.update', ['post' => $post->id]) }}" method="POST">
    @csrf
    @method('PUT')
    @include('posts.partials.form')
    <div><input type="submit" value="Update" class="btn btn-primary"></div>
</form>
@endsection