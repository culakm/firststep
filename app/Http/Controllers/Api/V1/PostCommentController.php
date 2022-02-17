<?php

namespace App\Http\Controllers\Api\V1;

use App\Events\CommentPosted;
use App\Models\BlogPost;
use Illuminate\Http\Request;
use App\Http\Requests\StoreComment;
use App\Http\Controllers\Controller;
use App\Http\Resources\Comment as CommentResource;
use App\Models\Comment;

class PostCommentController extends Controller
{

    public function __construct()
    {
        // auth:api urcuje ze pre auth pouzijeme guard api z config/auth.php
        $this->middleware('auth:api')->only(['store', 'update', 'destroy']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(BlogPost $post, Request $request)
    {
        $per_page = $request->input('per_page') ?? 15; // 15 je aj laravelovy default
        // return CommentResource::collection($post->comments); // pri tomto nefunguje paginate, inak to ide
        return CommentResource::collection(
            $post->comments()->with('user')
            ->paginate($per_page)
            ->appends(
                [
                    'per_page' => $per_page //aby laravel generoval v JSON linku aj s parametrom per_page, tu 
                ]
            )
        ); //5 je pocet poloziek na stranku
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(BlogPost $post, StoreComment $request)
    {
        $comment = $post->comments()->create([
            'content' => $request->input('content'), // z requestu naberie content
            'user_id' => $request->user()->id // z requestu naberie automaticky usera
        ]);
        event(new CommentPosted($comment));

        // vystup je definovany cez App\Http\Resources\Comment
        return new CommentResource($comment);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(BlogPost $post, Comment $comment)
    {
        return new CommentResource($comment);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(BlogPost $post, Comment $comment, StoreComment $request)
    {
        // pozreme policy pre update action/ability
        $this->authorize($comment);
        // save content
        $comment->content = $request->input('content');
        $comment->save();
        // vrati zmeneny comment
        return new CommentResource($comment);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(BlogPost $post, Comment $comment)
    {
        $this->authorize($comment);

        $comment->delete();
        return response()->noContent();
    }
}
