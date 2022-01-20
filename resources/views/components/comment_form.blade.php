<div class="mb-2 mt-2">
    @auth
        {{-- <form action="{{ route('posts.comments.store', ['post' => $post->id]) }}" method="POST"> --}}
        <form action="{{ $route }}" method="POST">
            @csrf
            <div class="form-group">
                <textarea id="content" name="content" class="form-control" cols="30" rows="4" ></textarea>
            </div>
            @errors @enderrors
            <button type="submit" class="btn btn-primary mb-2 mt-2">Create comment</button>
        </form>
        
    @else
        <a href="{{ route('login') }}">Sing in to post comments!</a>
    @endauth
    </div>
    <hr/>