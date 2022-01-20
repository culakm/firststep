<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreComment;
use App\Models\User;

class UserCommentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->only(['store']);   
    }

    public function store(User $user, StoreComment $request)
    {
        $user->commentsOn()->create([
            'content' => $request->input('content'), // z requestu naberie content
            'user_id' => $request->user()->id // z requestu naberie automaticky usera
        ]);

        return redirect()->back()->withStatus('Comment was added');
    }
}
