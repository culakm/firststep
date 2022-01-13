<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use Illuminate\Http\Request;

class PostTagController extends Controller
{
    // chceme zobrazit vsetky post pre jeden tag
    public function index($tag_id)
    {
        $tag = Tag::findOrFail($tag_id);

        return view('posts.index', [
            'posts' => $tag->blogPosts()->latestWithRelations()->get(), //latestWithRelations je definovane v BlogPost modeli pretoze sa to este opakuje niekde inde
            // tieto data doda app/Http/ViewComposers/ActivityComposer.php
            // 'posts_most_commented' => [],
            // 'users_most_active' => [],
            // 'users_most_active_month' => []
        ]);
    }
}
