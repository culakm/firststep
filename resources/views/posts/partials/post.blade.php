{{-- @if ($loop->even)
    <h3 style="background-color: red">{{ $post->title }} neparny</h3>
@else
    <h3 style="background-color: silver">{{ $post->title }} parny</h3>
@endif --}}


@if ($post->trashed())
    <del>
@endif
<a class="{{ $post->trashed() ? 'text-muted' : '' }}" href="{{ route('posts.show', ['post' => $post->id]) }}"><h1>{{ $post->title }}</h1></a>
@if ($post->trashed())
    </del>
@endif
<p class="text-muted">
    Posted {{ $post->created_at->diffForHumans() }}
    by {{ $post->user->name }}
</p>

<p>Comments: {{ $post->comments_count }}</p>
<a href="{{ route('posts.edit', ['post' => $post->id]) }}" class="btn btn-primary me-3 my-3">Add comment</a>
<div class="mb-3">
    @can('update', $post)
        <a href="{{ route('posts.edit', ['post' => $post->id]) }}" class="btn btn-primary">Edit</a>
    @endcan

    @if (! $post->trashed())
        @can('delete', $post)
            <form class="d-inline" action="{{ route('posts.destroy', ['post' => $post->id]) }}" method="POST">
                @csrf
                @method('DELETE')
                <input type="submit" class="btn btn-primary" value="Delete">
            </form>
        @endcan
    @endif
</div>
