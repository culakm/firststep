@extends('layouts.app')

@section('title', $post->title)

<!-- zaciatok sekcie ktora sa yielduje v layouts.app-->
@section('content') 

<h1>{{ $post->title }}</h1>
<p>{{ $post->content }}</p>
@updated(['date' => $post->created_at, 'name'=> $post->user->name])
@endupdated
@updated(['date' => $post->updated_at])
Updated
@endupdated
<h4>Comments</h4>
@forelse ($post->comments as $comment)
    @if ($loop->even)
        <p class="bg-primary text-white">Parny</p>
    @else
        <p class="bg-dark text-white">NEParny</p>
    @endif
    <p >{{ $loop->iteration }}. {{ $comment->content }}</p>
    @updated(['date' => $comment->created_at])
    @endupdated
@empty
    <p>No comments</p>
@endforelse

@endsection
<!-- koniec sekcie -->