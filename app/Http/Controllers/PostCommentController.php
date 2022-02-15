<?php

namespace App\Http\Controllers;

use App\Events\CommentPosted as EventsCommentPosted;
use App\Http\Requests\StoreComment;
use App\Jobs\NotifyUsersPostWasCommented;
use App\Jobs\ThrottledMail;
use App\Mail\CommentPosted;
use App\Mail\CommentPostedMarkdown;
use App\Models\BlogPost;
use Illuminate\Support\Facades\Mail;
use App\Http\Resources\Comment as CommentResource;

class PostCommentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')
         ->only(['store']);   
    }

    // toto je na testovanie API s json
    public function index(BlogPost $post)
    {
        // dump(gettype($post->comments));
        // dump(get_class($post->comments));
        // dd(is_array($post->comments));

        // vracia to cez resources cele collection
        return CommentResource::collection($post->comments);
        // pokial by to neslo moze sa natvrdo hladat relationship
        // return CommentResource::collection($post->comments()->with('user')->get());

        // vracia to s cez resources s jednou polozkou
        // return new CommentResource($post->comments->first());

        // vracia to bez resources
        // len komenty
        // return $post->comments;
        // komenty aj s userom
        // return $post->comments()->with('user')->get();
    }

    public function store(BlogPost $post, StoreComment $request)
    {
        // definujeme comment a ulozime ho do premennej pre email
        $comment = $post->comments()->create([
            'content' => $request->input('content'), // z requestu naberie content
            'user_id' => $request->user()->id // z requestu naberie automaticky usera
        ]);

        // facade Mail posle mail, $post->user by mal dodat automaticky emailovu adresu zo stlpca email
        // bez queue alebo s implementovanym implements ShouldQueue
        // Mail::to($post->user)->send(
        //     // new CommentPosted($comment)
        //     new CommentPostedMarkdown($comment)
        // );
        
        // s queue napriamo
        // posle mail tomu kto vytvoril post
        //Mail::to($post->user)->queue(new CommentPostedMarkdown($comment));
        // toto je to iste ale ideme cez Redis queue
        // toto je volane v listeneri
        //ThrottledMail::dispatch(new CommentPostedMarkdown($comment), $post->user)->onQueue('high');


        // s delayom 
        // $when = now()->addMinutes(1);
        // Mail::to($post->user)->later($when,new CommentPostedMarkdown($comment));

        // volame Job ktory posle mail notifikaciu vsetkym userom ktori kedy komentovali post
        // toto je volane v listeneri
        // NotifyUsersPostWasCommented::dispatch($comment)->onQueue('low');


        event(new EventsCommentPosted($comment));

        return redirect()->back()->withStatus('Comment was added');
    }
}
