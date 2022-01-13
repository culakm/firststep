<?php

namespace App\Models;

use App\Scopes\LatestScope;
use App\Scopes\DeletedAdminScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Cache;

class BlogPost extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = ['title','content','user_id'];
    // toto je defaultna hodnota ale prepise vsetko co posleme cez formular
    //protected $attributes = ['content' => 'Default value of content because mysql doesn\'t want to accept default for text datatype'];
    

    public function comments()
    {
        // commenty to pomocou lokalnej scope funkcie modelu comment
        return $this->hasMany(Comment::class)->latestFunc();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function tags(){
        return $this->belongsToMany(Tag::class)->withTimestamps();
    }

    public function scopeMostCommented(Builder $query)
    {
        // Modelu prida stlpec comments_count
        return $query->withCount('comments')->orderBy('comments_count', 'desc');
    }

    public function scopeLatest(Builder $query)
    {
        // Modelu prida stlpec comments_count
        return $query->orderBy($model::CREATED_AT, 'desc');
    }

    public function scopeLatestWithRelations(Builder $query)
    {
        // toto sa pridava do PostTagController.show a tiez PostController.index
        return $query->Latest()->withCount('comments')->with('user','tags');
    }

    public static function boot(){
        
        // zoradi blogposty pomocou globalnej triedy LatestScope v app/Scopes/LatestScope.php
        // toto nefunguje pre starsie verzie mysql kvoli tomu, ze v LatestScope je order by
        //static::addGlobalScope(new LatestScope);
        // povoli adminovi videt aj veci s flagom deleted_at
        static::addGlobalScope(new DeletedAdminScope);
        
        parent::boot();

        static::deleting(function (BlogPost $post){
            $post->comments()->delete();
            Cache::tags(['blog_post'])->forget("blog_post_{$post->id}");
        });

        static::updating(function (BlogPost $post){
            Cache::tags(['blog_post'])->forget("blog_post_{$post->id}");
        });

        static::restoring(function (BlogPost $post){
            $post->comments()->restore();
        });
    }
}
