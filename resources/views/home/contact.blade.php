@extends('layouts.app')

@section('title', "Contact page")

@section('content') <!-- meno sekcie -->
<h1>Contact page</h1>
<p>This is contact</p>
@can('home.secret')
    <a href="{{ route('home.secret') }}">Go to special contacts details, it's visible just for admins</a>
@endcan
@endsection