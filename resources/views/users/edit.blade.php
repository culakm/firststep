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
                <img src="{{ $user->image ? $user->image->url() : '' }}" class="img-thumbnail"/>
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
                    <input id="name" name="name" class="form-control" value="{{ $user->name }}" type="text" />
                </div>

                <div class="form-group">
                    <label for="name">Language:</label>
                    <select id="locale" name="locale" class="form-control" >
                        <option value="pako" >pako</option>
                        @foreach ( App\Models\User::LOCALES as $locale => $label)
                            <option value="{{ $locale }}" {{ $user->locale !== $locale ? '' : 'selected' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                </div>

                @errors @enderrors

                <div class="form-group mt-2">
                    <input class="button button-primary" value="Save changes" type="submit" />
                </div>
            </div>
        </div>
    </form>
@endsection