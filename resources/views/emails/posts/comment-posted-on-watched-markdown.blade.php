@component('mail::message')
# Comment was posted on post you are watching

Hi {{ $user->name }}

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
