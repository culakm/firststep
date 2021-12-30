@extends('layouts.app')

@section('title', $post->title)

<!-- zaciatok sekcie ktora sa yielduje v layouts.app-->
@section('content') 

<h1>{{ $post->title }}</h1>
<p>{{ $post->content }}</p>
<p>Post Added: {{ $post->created_at->diffForHumans() }}</p>
@if(now()->diffInMinutes($post->created_at) < 5)
{{-- ak je nieco novsie ako 5 min --}}
<div class="alert alert-info">NEW post!</div>
@endif
<h4>Comments</h4>
@forelse ($post->comments as $comment)
    @if ($loop->even)
        <p class="bg-primary text-white">Parny</p>
    @else
        <p class="bg-dark text-white">NEParny</p>
    @endif
    <p >{{ $loop->iteration }}. {{ $comment->content }}</p>
    <p class="text-muted">Comment Added: {{ $comment->created_at->diffForHumans() }}</p>
@empty
    <p>No comments</p>
@endforelse

@endsection
<!-- koniec sekcie -->