@extends('layouts.app')

@section('title', 'Zoznam postov')

<!-- zaciatok sekcie -->
@section('content') 
<div class="row">
    <div class="col-8">
        @foreach ( $posts as $key => $post )
            @include('posts.partials.post')
        @endforeach
    </div>
    <div class="col-4">
        @include('posts.partials.cards')
    </div>
</div>
@endsection
<!-- koniec sekcie -->