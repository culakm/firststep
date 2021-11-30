@if ($loop->even)
    <div style="background-color: red">{{ $key . ' - ' . $post->title }} neparny</div>
@else
    <div style="background-color: silver">{{ $key . ' - ' . $post->title }} parny</div>
@endif

<div>
    <form action="{{ route('posts.destroy', ['post' => $post->id]) }}" method="POST">
        @csrf
        @method('DELETE')
        <input type="submit" value="Delete">
    </form>
</div>