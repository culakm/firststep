<?php

namespace App\Models;

use App\Traits\Taggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Cache;

class Comment extends Model
{
    use HasFactory;
    use SoftDeletes, Taggable;

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

    // toto nepotrebujeme pretoze je to v observeri
    // public static function boot(){
    //     parent::boot();

    //     // static::creating(function (Comment $comment){
    //     //     // funkcia je implementovana v observeri            
    //     // });
    // }
}
