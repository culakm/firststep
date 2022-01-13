{{-- povodny pristup
    <form action="" method="POST">
    @csrf
    <div><input type="submit" value="Create comment" class="btn btn-primary"></div>
</form> --}}

<div class="mb-2 mt-2">
@auth
    <form action="{{ route('posts.comments.store', ['post' => $post->id]) }}" method="POST">
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