<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BlogPost extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = ['title','content'];
    // toto je defaultna hodnota ale prepise vsetko co posleme cez formular
    //protected $attributes = ['content' => 'Default value of content because mysql doesn\'t want to accept default for text datatype'];
    

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public static function boot(){
        parent::boot();

        static::deleting(function (BlogPost $post){
            $post->comments()->delete();
        });

        static::restoring(function (BlogPost $post){
            $post->comments()->restore();
        });
    }
}
