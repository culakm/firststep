@extends('layouts.app')

@section('title', 'Zoznam postov')

<!-- zaciatok sekcie -->
@section('content') 
<div class="row">
    <div class="col-8">
        @forelse ( $posts as $key => $post )
            @include('posts.partials.post')
        @empty
            <p>No blog posts yet!</p>
        @endforelse
    </div>
    <div class="col-4">
        @include('posts.partials.cards')
    </div>
</div>
@endsection
<!-- koniec sekcie -->