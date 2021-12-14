@extends('layouts.app')

@section('title', 'Vytvor post')

@section('content')
<form action="{{ route('posts.store') }}" method="POST">
    @csrf
    @include('posts.partials.form')
    <div><input type="submit" value="Create" class="btn btn-primary"></div>
</form>
@endsection