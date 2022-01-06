<div class="container"></div>
            <div class="row">
                @card(['title' => 'Most commented'])
                    @slot('subtitle')
                    What people are currently talking about.
                    @endslot
                    @slot('items')
                        @foreach ( $posts_most_commented as $post)
                            <li class="list-group-item">
                                <a href="{{ route('posts.show', ['post' => $post->id]) }}" >
                                    {{ $post->comments_count }} {{ $post->title }}
                                </a>
                            </li>
                        @endforeach
                    @endslot
                @endcard
            </div>

            <div class="row mt-4">
                @card(['title' => 'Most active user'])
                    @slot('subtitle')
                    Users with most posts written.
                    @endslot
                    @slot('items', collect($users_most_active)->pluck('name'))
                @endcard
            </div>

            <div class="row mt-4">
                @card(['title' => 'Most active users last month'])
                @slot('subtitle')
                Users with most posts written for last month.
                @endslot
                @slot('items', collect($users_most_active_month)->pluck('name'))
                @endcard
            </div>
        </div>