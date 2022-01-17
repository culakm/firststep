@extends('layouts.app')

@section('content')
    <form 
        method="POST"
        enctype="multipart/form-data"
        action="{{ route('users.update', ['user' => $user->id]) }}"
        class="form-horizontal"
    >
        @csrf

        @method('PUT')

        <div class="row">
            <div class="col-4">
                <img src="" class="img-thumbnail avatar"/>
                <div class="card mt-4">
                    <div class="card-body">
                        <h6>Upload a different photo.</h6>
                        <div class="form-group mt-2 mb-2">
                            <label for="avatar">Thumbnail</label>
                            <input id="avatar" type="file" name="avatar" class="form-control">
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-8">
                <div class="form-group">
                    <label for="name">Name:</label>
                    <input name="name" class="form-control" value="" type="text" />
                </div>
                <div class="form-group">
                    <input class="button button-primary" value="Save changes" type="submit" />
                </div>
            </div>
        </div>
    </form>
@endsection