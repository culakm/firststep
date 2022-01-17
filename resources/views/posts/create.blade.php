@extends('layouts.app')

@section('title', 'Vytvor post')

@section('content')
<form   method="POST"
        action="{{ route('posts.store') }}"
        enctype="multipart/form-data"
>
    @csrf
    @include('posts.partials.form')
    <div><input type="submit" value="Create" class="btn btn-primary"></div>
</form>
@endsection