@extends('layouts.app')

@section('title', $post->title)

<!-- zaciatok sekcie ktora sa yielduje v layouts.app-->
@section('content') 

<h1>{{ $post->title }}</h1>
<p>{{ $post->content }}</p>
<p>Added: {{ $post->created_at->diffForHumans() }}</p>
@if(now()->diffInMinutes($post->created_at) < 5)
{{-- ak je nieco novsie ako 5 min --}}
<div class="alert alert-info">NEW post!</div>
@endif
@endsection
<!-- koniec sekcie -->