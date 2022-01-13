<p>
    @foreach ($tags as $tag)
    {{-- span class="lead" da vacsi font --}}
    <span class="lead"><a href="{{ route('posts.tags.index', ['tag_id' => $tag->id])}}" class="badge badge-lg rounded-pill bg-success">{{ $tag->name }}</a><span>
    @endforeach
</p>