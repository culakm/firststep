<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    use HasFactory;

    public function blogPosts(){
        // bez polymorfizmu
        //return $this->belongsToMany(BlogPost::class)->withTimestamps()->latest();
        return $this->morphedByMany(BlogPost::class,'taggable')->withTimestamps()->latest();
    }

    public function comments(){
        return $this->morphedByMany(Comment::class,'taggable')->withTimestamps()->latest();
    }
}
