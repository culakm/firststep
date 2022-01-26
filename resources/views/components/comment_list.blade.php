@forelse ($comments as $comment)
    <p>{{ $loop->iteration }}. {{ $comment->content }}</p>
    @tags(['tags' => $comment->tags])
    @endtags
    @updated(['date' => $comment->created_at, 'name'=> $comment->user->name, 'user_id' => $comment->user->id])
    @endupdated
    @empty
    <p>No comments yes</p>
@endforelse