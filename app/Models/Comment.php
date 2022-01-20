<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Cache;

class Comment extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = ['user_id','content'];

    // pred polymorph
    // public function blogPost()
    // {
    //     return $this->belongsTo(BlogPost::class);
    // }

    public function commentable()
    {
        return $this->morphTo();
    }


    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function scopeLatestFunc(Builder $query)
    {
        return $query->orderBy(static::CREATED_AT, 'desc');
    }

    public static function boot(){
        parent::boot();

        static::creating(function (Comment $comment){
            // pred polymorph
            // Cache::tags(['blog_post'])->forget("blog_post_{$comment->blog_post_id}");

            // polymorph
            if ($comment->commentable_type === BlogPost::class ) {
                Cache::tags(['blog_post'])->forget("blog_post_{$comment->commentable_id}");
                // toto je aj v pripade ze nie je polymorph
                Cache::tags(['blog_post'])->forget('posts_most_commented');
            }
            
        });
    }
}
