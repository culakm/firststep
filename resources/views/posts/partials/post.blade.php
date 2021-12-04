{{-- @if ($loop->even)
    <h3 style="background-color: red">{{ $post->title }} neparny</h3>
@else
    <h3 style="background-color: silver">{{ $post->title }} parny</h3>
@endif --}}

<h3><a href="{{ route('posts.show', ['post' => $post->id]) }}" class="btn bth-primary">{{ $post->title }}</a></h3>

<div class="mb-3">
    <a href="{{ route('posts.edit', ['post' => $post->id]) }}" class="btn btn-primary">Edit</a>
    <form class="d-inline" action="{{ route('posts.destroy', ['post' => $post->id]) }}" method="POST">
        @csrf
        @method('DELETE')
        <input type="submit" class="btn btn-primary" value="Delete">
    </form>
</div>
