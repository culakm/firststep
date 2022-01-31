<style>
    body {
        font-family:Arial, Helvetica, sans-serif
    }
</style>
<p>
    Hi {{ $comment->commentable->user->name }}
</p>
<p>
    Someone has commented on your blog post
    <a href="{{ route('posts.show', ['post' => $comment->commentable->id]) }}">
        {{ $comment->commentable->title }}
    </a>
</p>

<hr/>

<p>
    <img src="{{ $message->embed(public_path('storage/' . $comment->user->image->path)) }}"/>
    <a href="{{ route('users.show', ['user' => $comment->user->id]) }}">
        {{ $comment->user->name }}
    </a> said:
</p>

<p>
    {{ $comment->content }}
    <hr/>
    {{-- <p>url: {{ $comment->user->image->url() }}</p>
    <p>image->path: {{ $comment->user->image->path }}</p>
    <p>public_path: {{ public_path('storage/' . $comment->user->image->path) }}</p>
    <p>URL::asset: {{ Illuminate\Support\Facades\URL::asset('/storage/' . $comment->user->image->path) }}   </p> --}}
    
</p>