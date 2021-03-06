<?php

namespace App\Models;

use App\Scopes\LatestScope;
use App\Scopes\DeletedAdminScope;
use App\Traits\Taggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Cache;

class BlogPost extends Model
{
    use HasFactory;
    use SoftDeletes, Taggable;

    protected $fillable = ['title','content','user_id'];
    // toto je defaultna hodnota ale prepise vsetko co posleme cez formular
    //protected $attributes = ['content' => 'Default value of content because mysql doesn\'t want to accept default for text datatype'];
    

    public function comments()
    {
        // commenty to pomocou lokalnej scope funkcie modelu comment
        // pred polymorph
        // return $this->hasMany(Comment::class)->latestFunc();
        return $this->morphMany(Comment::class,'commentable')->latestFunc();
    }

    // tato funkcia je spolocna aj pre comment a je definovana v app/Traits/Taggable.php
    // public function tags(){
    //     return $this->morphToMany(Tag::class, 'taggable')->withTimestamps();
    // }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function image()
    {
        //return $this->hasOne(Image::class);
        // pre polymorfizmus
        return $this->morphOne(Image::class, 'imageable');
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

        // static::deleting(function (BlogPost $blogPost){
        //     // obsluha cache sa premiestnila do observeru app/Observers/BlogPostObserver.php
        // });

        // static::updating(function (BlogPost $blogPost){
        //     // obsluha cache sa premiestnila do observeru app/Observers/BlogPostObserver.php
        // });

        // static::restoring(function (BlogPost $blogPost){
        //     // obsluha cache sa premiestnila do observeru app/Observers/BlogPostObserver.php
        // });
    }
}
