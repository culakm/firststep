<?php

namespace App\Observers;

use App\Models\BlogPost;
use App\Models\Comment;
use Illuminate\Support\Facades\Cache;

class CommentObserver
{
    /**
     * Handle the Comment "created" event.
     *
     * @param  \App\Models\Comment  $comment
     * @return void
     */
    public function creating(Comment $comment)
    {
        // pred polymorph
        // Cache::tags(['blog_post'])->forget("blog_post_{$comment->blog_post_id}");
        // polymorph
        if ($comment->commentable_type === BlogPost::class ) {
            Cache::tags(['blog_post'])->forget("blog_post_{$comment->commentable_id}");
            // toto je aj v pripade ze nie je polymorph
            Cache::tags(['blog_post'])->forget('posts_most_commented');
        }
    }
}
