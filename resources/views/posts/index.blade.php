@extends('layouts.app')

@section('title', 'Zoznam postov')

<!-- zaciatok sekcie -->
@section('content') 
   
    @forelse ( $posts as $key => $post )
        @include('posts.partials.post')
    @empty
        Posts not found forelse
    @endforelse

@endsection
<!-- koniec sekcie -->