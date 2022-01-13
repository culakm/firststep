<?php

namespace App\Http\ViewComposers;

use App\Models\BlogPost;
use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;

class ActivityComposer{
    public function compose(View $view)
    {
        // Cache
        $posts_most_commented = Cache::tags(['blog_post'])->remember('posts_most_commented', now()->addSeconds(10000), function() {
            return BlogPost::mostCommented()->take(5)->get(); // radenie postov podla poctu komentarov
        });

        $users_most_active = Cache::remember('users_most_active', now()->addSeconds(10), function() {
            return User::withMostBlogPosts()->take(5)->get(); // zoznam 5 userov s najviac komentami
        });


        $users_most_active_month = Cache::remember('users_most_active_month', now()->addSeconds(10), function() {
            return User::withMostBlogPostsLastDay()->take(5)->get(); // zoznam 5 userov s najviac komentami
        });

        $view->with('posts_most_commented', $posts_most_commented);
        $view->with('users_most_active', $users_most_active);
        $view->with('users_most_active_month', $users_most_active_month);
    }
}