@extends('layouts.app')

@section('title', $post->title)

<!-- zaciatok sekcie ktora sa yielduje v layouts.app-->
@section('content') 
<div class="row">
    <div class="col-8">
        <h1>{{ $post->title }}</h1>
        <p>{{ $post->content }}</p>
        @updated(['date' => $post->created_at, 'name'=> $post->user->name])
        @endupdated
        @updated(['date' => $post->updated_at])
        Updated
        @endupdated

        @tags(['tags' => $post->tags])
        @endtags

        {{-- cached value --}}
        <p>Currently read by {{ $counter }} people</p>

        <h4>Comments</h4>
        @include('comments.partials.form')
        @forelse ($post->comments as $comment)
            @if ($loop->even)
                <p class="bg-primary text-white">Parny</p>
            @else
                <p class="bg-dark text-white">NEParny</p>
            @endif
            <p >{{ $loop->iteration }}. {{ $comment->content }}</p>
            @updated(['date' => $comment->created_at, 'name'=> $comment->user->name])
            @endupdated
        @empty
            <p>No comments</p>
        @endforelse
    </div>
    <div class="col-4">
        @include('posts.partials.cards')
    </div>
</div>
@endsection
<!-- koniec sekcie -->