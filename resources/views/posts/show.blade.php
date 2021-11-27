@extends('layouts.app')

@section('title', $post['title'])

<!-- zaciatok sekcie -->
@section('content') 

@if($post['is_new'] === 1)
<div>A new blog post using if</div>
@elseif ($post['is_new'] === 2)
<div>velmi stary post</div>
@else
<div>neznamy post</div>
@endif

@unless ($post['is_new'] === 1)
<div>Post is_new nie je 1</div>
@endunless

<h1>{{ $post['title'] }}</h1>
<p>{{ $post['content'] }}</p>

@isset($post['has_comments'])
<p>MAME comment</p>
@endisset

@endsection
<!-- koniec sekcie -->