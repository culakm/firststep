<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    // The attributes that are mass assignable.
    protected $fillable = ['name','email','password'];

    // The attributes that should be hidden for arrays.
    protected $hidden = ['password','remember_token'];

    // The attributes that should be cast to native types.
    protected $casts = ['email_verified_at' => 'datetime'];

    public const LOCALES = [
        'en' => 'English',
        'es' => 'Espanol',
        'de' => 'Deutsch'
    ];

    public function blogPosts()
    {
        return $this->hasMany(BlogPost::class);
    }

    public function image()
    {
        return $this->morphOne(Image::class, 'imageable');
    }

    // toto su komentare na usera
    public function commentsOn()
    {
        return $this->morphMany(Comment::class,'commentable')->latestFunc();
    }

    // toto su vsetky komentare ktore user vytvoril
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function scopeWithMostBlogPosts(Builder $query)
    {
        return $query->withCount('blogPosts')
        //->has('blogPosts', '>=', 7) // ma viac ako 6 postov
        //->withoutGlobalScope('Illuminate\Database\Eloquent\SoftDeletingScope') toto by malo odstavit globalny scope admina ktory ukazuje aj delete veci ale nejako to nefunguje
        ->orderBy('blog_posts_count', 'desc');
    }

    public function scopeWithMostBlogPostsLastDay(Builder $query)
    {
        return $query->withCount(
            // vytvori sa nieco ako sub query ktor vyfiltruje blogposty ktore su mladsie ako den a tie az preveruje na pocet postov
            ['blogPosts' => function (Builder $query ){
                    $query->whereBetween(static::CREATED_AT, [now()->subDays(1), now()]);
                }
            ]
        )
        ->has('blogPosts', '>=', 2) // doesn't work on web page
        ->orderBy('blog_posts_count', 'desc');
    }

    // scope pre vyhladanie vsetkych userov ktori komentovali zadany blog post 
    public function scopeThatHasCommentedOnPost(Builder $query, BlogPost $post){
        return $query->whereHas('comments', function ($query) use ($post) {
            return $query->where('commentable_id', '=', $post->id)
                ->where('commentable_type', '=', BlogPost::class);
        });
    }

    // scope pre vyhladanie vsetkych admonov
    public function scopeThatIsAnAdmin(Builder $query){
        return $query->where('is_admin', true);
    }
}
