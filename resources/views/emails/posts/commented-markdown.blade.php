@component('mail::message')
Hi {{ $comment->commentable->user->name }} new comment was sent on you Post

@component('mail::button', ['url' => route('posts.show', ['post' => $comment->commentable->id])])
Check  blog post
@endcomponent

@component('mail::button', ['url' => route('users.show', ['user' => $comment->user->id])])
Visit a user {{ $comment->user->name }} who commented
@endcomponent

@component('mail::panel')
{{ $comment->content }}
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
