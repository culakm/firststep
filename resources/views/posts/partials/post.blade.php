{{-- @if ($loop->even)
    <h3 style="background-color: red">{{ $post->title }} neparny</h3>
@else
    <h3 style="background-color: silver">{{ $post->title }} parny</h3>
@endif --}}


@if ($post->trashed())
    <del>
@endif

<h1>
    <a class="{{ $post->trashed() ? 'text-muted' : '' }}" href="{{ route('posts.show', ['post' => $post->id]) }}">
        {{ $post->title }}

        {{-- ak je nieco novsie ako 50 min --}}
        {{-- <div class="alert alert-info">NEW post!</div> --}}
        @badgealias(['another_parameter' => 'primary', 'show' => now()->diffInMinutes($post->created_at) < 160])
            Uplne novy post 6
        @endbadgealias
    </a>
</h1>

@if ($post->trashed())
    </del>
@endif

@updated(['date' => $post->created_at, 'name'=> $post->user->name, 'user_id' => $post->user->id])
@endupdated

@tags(['tags' => $post->tags])
@endtags

{{--  {{ trans_choice('messages.comments',$post->comments_count, ['count' => $post->comments_count]) }}  --}}
{{ trans_choice('messages.comments',$post->comments_count) }}

{{-- <a href="{{ route('posts.edit', ['post' => $post->id]) }}" class="btn btn-primary me-3 my-3">Add comment</a> --}}
<div class="mb-3">
    @auth
        @can('update', $post)
            <a href="{{ route('posts.edit', ['post' => $post->id]) }}" class="btn btn-primary">Edit</a>
        @endcan
    @endauth
    @auth
        @if (! $post->trashed())
            @can('delete', $post)
                <form class="d-inline" action="{{ route('posts.destroy', ['post' => $post->id]) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <input type="submit" class="btn btn-primary" value="Delete">
                </form>
            @endcan
        @endif
    @endauth
</div>
