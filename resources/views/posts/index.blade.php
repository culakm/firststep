@extends('layouts.app')

@section('title', 'Zoznam postov')

<!-- zaciatok sekcie -->
@section('content') 
<div class="row">
    <div class="col-8">
        {{--  @foreach ( $posts as $key => $post )
            @include('posts.partials.post')
        @endforeach
        <p>No blog posts yet!</p>  --}}
        @forelse ( $posts as $key => $post )
            @include('posts.partials.post')
        @empty
            <p>{{ __('No blog posts yet!') }}</p>
        @endforelse
    </div>
    <div class="col-4">
        @include('posts.partials.cards')
    </div>
</div>
@endsection
<!-- koniec sekcie -->