<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreComment;
use App\Models\BlogPost;
use Illuminate\Http\Request;

class PostCommentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')
         ->only(['store']);   
    }

    public function store(BlogPost $post, StoreComment $request)
    {
        $post->comments()->create([
            'content' => $request->input('content'), // z requestu naberie content
            'user_id' => $request->user()->id // z requestu naberie automaticky usera
        ]);

        $request->session()->flash('status', 'Comment was added');

        return redirect()->back();
    }
}
