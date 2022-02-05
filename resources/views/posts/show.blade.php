@extends('layouts.app')

@section('title', $post->title)

<!-- zaciatok sekcie ktora sa yielduje v layouts.app-->
@section('content') 
<div class="row">
    <div class="col-8">

        <!-- NOrmalne zobrazenie obrazku
        <h1>{{ $post->title }}</h1>
        <p>{{ $post->content }}</p>

        @if ($post->image)
            {{-- <img src="{{ Storage::url($post->image->path) }}" /> --}}
            <img src="{{ $post->image->url() }}" />
        @endif
        -->

        @if($post->image)
        <div style="background-image: url('{{ $post->image->url() }}'); min-height: 500px; color: white; text-align: center; background-attachment: fixed;">
            <h1 style="padding-top: 100px; text-shadow: 1px 2px #000">
        @else
            <h1>
        @endif
            {{ $post->title }}
            @badgealias(['show' => now()->diffInMinutes($post->created_at) < 30])
                Brand new Post!
            @endbadgealias
        @if($post->image)    
            </h1>
        </div>
        @else
            </h1>
        @endif
        
        <p>{{ $post->content }}</p>

        @updated(['date' => $post->created_at, 'name'=> $post->user->name])
        @endupdated
        @updated(['date' => $post->updated_at])
        Updated
        @endupdated

        @tags(['tags' => $post->tags])
        @endtags

        {{-- cached value --}}
        {{--  <p>Currently read by {{ $counter }} people</p>  --}}
        <p>{{ trans_choice('messages.people.reading',$counter) }}</p>

        <h4>Comments</h4>
        @comment_form(['route' => route('posts.comments.store', ['post' => $post->id])])
        @endcomment_form

        @comment_list(['comments' => $post->comments])
        @endcomment_list
    </div>
    <div class="col-4">
        @include('posts.partials.cards')
    </div>
</div>
@endsection
<!-- koniec sekcie -->