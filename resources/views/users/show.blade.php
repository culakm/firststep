@extends('layouts.app')

@section('content')
    @csrf

    @method('PUT')

    <div class="row">
        <div class="col-4">
            <img src="" class="img-thumbnail avatar"/>
        </div>
        <div class="col-8">
            <h3>{{ $user->name }}</h3>
        </div>
    </div>
@endsection