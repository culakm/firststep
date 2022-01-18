<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Image extends Model
{
    use HasFactory;

    protected $fillable = ['path'];

    // do fillable treba pridat ,'blog_post_id'
    // public function blogPost()
    // {
    //     return $this->belongsTo(BlogPost::class);
    // }

    // pre polymorfizmus
    public function imagable()
    {
        return $this->morphTo();
    }

    public function url(){
        return Storage::url($this->path);
    }
}
