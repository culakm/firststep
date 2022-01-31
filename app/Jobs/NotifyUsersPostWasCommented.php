<?php

namespace App\Jobs;

use App\Mail\CommentPostedOnPostWatchedMarkdown;
use App\Models\Comment;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class NotifyUsersPostWasCommented implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $comment;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Comment $comment)
    {
        $this->comment = $comment;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        
        User::thatHasCommentedOnPost(
            // Najdeme vsetkych userov ktori niekedy komentovali rovnaky blog post
            $this->comment->commentable)
            ->get()
            // vyfiltrujeme vsetkych userov okrem toho kto tento konkretny komentar vytvoril, jemu posielat tento mail nechceme
            ->filter(function (User $user){
                return $user->id !== $this->comment->user_id;
            })
                // pre vsetkych userov posleme mail
                // pretoze mail CommentPostedOnPostWatchedMarkdown implements ShouldQueue nevolame Mail::to($user)->queue ale len send
                ->map(function (User $user){
                    // vytvaranie mailov cez redis queue
                    ThrottledMail::dispatch(new CommentPostedOnPostWatchedMarkdown($this->comment, $user), $user);
                    // vytvaranie mailov napriamo
                    //Mail::to($user)->send(new CommentPostedOnPostWatchedMarkdown($this->comment, $user));
                });
    }
}
