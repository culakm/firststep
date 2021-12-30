<div class="container"></div>
            <div class="row">
                <div class="card" style="width: 100%;">
                    <div class="card-body">
                    <h5 class="card-title">Most commented</h5>
                    <h6 class="card-subtitle mb-2 text-muted">What people are currently talking about.</h6>
                    </div>
                    <ul class="list-gropu list-group-flush">
                        @foreach ( $posts_most_commented as $post)
                            <li class="list-group-item">
                                <a href="{{ route('posts.show', ['post' => $post->id]) }}" >
                                    {{ $post->comments_count }} {{ $post->title }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>

            <div class="row mt-4">
                <div class="card" style="width: 100%;">
                    <div class="card-body">
                    <h5 class="card-title">Most active users</h5>
                    <h6 class="card-subtitle mb-2 text-muted">Users with most posts written.</h6>
                    </div>
                    <ul class="list-gropu list-group-flush">
                        @foreach ( $users_most_active as $user)
                            <li class="list-group-item">
                                {{-- <a href="{{ route('posts.show', ['post' => $post->id]) }}" > --}}
                                    {{ $user->blog_posts_count }} {{ $user->name }}
                                {{-- </a> --}}
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>

            <div class="row mt-4">
                <div class="card" style="width: 100%;">
                    <div class="card-body">
                    <h5 class="card-title">Most active users last month</h5>
                    <h6 class="card-subtitle mb-2 text-muted">Users with most posts written.</h6>
                    </div>
                    <ul class="list-gropu list-group-flush">
                        @foreach ( $users_most_active_month as $user)
                            <li class="list-group-item">
                                {{-- <a href="{{ route('posts.show', ['post' => $post->id]) }}" > --}}
                                    {{ $user->blog_posts_count }} {{ $user->name }}
                                {{-- </a> --}}
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>