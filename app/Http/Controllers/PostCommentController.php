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

class PostCommentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')
         ->only(['store']);   
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
