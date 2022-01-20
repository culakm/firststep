@extends('layouts.app')

@section('content')
    @csrf

    @method('PUT')

    <div class="row">
        <div class="col-4">
            <img src="{{ $user->image ? $user->image->url() : '' }}" class="img-thumbnail"/>
        </div>
        <div class="col-8">
            <h3>{{ $user->name }}</h3>
            @comment_form(['route' => route('users.comments.store', ['user' => $user->id])])
            @endcomment_form
    
            @comment_list(['comments' => $user->commentsOn])
            @endcomment_list
        </div>
    </div>
@endsection