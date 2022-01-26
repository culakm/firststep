<?php

namespace App\Traits;

use App\Models\Tag;

trait Taggable {

    // definuje vztah k tabulke tags pre vsetky triedy ktore tento trait implementuju
    public function tags(){
        return $this->morphToMany(Tag::class, 'taggable')->withTimestamps();
    }

    // najde tagy v contente (blogpostu alebo commentu) podla regexpu a vrati to
    private static function findTagsInContent($content){

        // tag v contente ma format @meno@
        preg_match_all('/@([^@]+)@/m', $content, $tags);
        
        // ak sa nenaslo nic do pola tags tak to vrati prazdne pole
        return Tag::whereIn('name', $tags[1] ?? [])->get();

        
    }


    protected static function bootTaggable(){
        // Pri kazdom update alebo create (pozor na ing / ed!!) triedy ktora trait implementuje
        // updatneme alebo vytvorime zoznam tagov podla toho co sme nasli v contente ako @tag....@
        static::updating(function ($model) {
            // static:: je pretoze ju volame zo statickej metody
            $model->tags()->sync(static::findTagsInContent($model->content));
        });

        static::created(function ($model) {
            $model->tags()->sync(static::findTagsInContent($model->content));
        });
    }
}