<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BlogPost extends Model
{
    protected $fillable = ['title','content'];
    // toto je defaultna hodnota ale prepise vsetko co posleme cez formular
    //protected $attributes = ['content' => 'Default value of content because mysql doesn\'t want to accept default for text datatype'];
    use HasFactory;

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
}
