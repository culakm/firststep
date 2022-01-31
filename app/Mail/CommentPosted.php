<?php

namespace App\Mail;

use App\Models\Comment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

class CommentPosted extends Mailable
{
    use Queueable, SerializesModels;

    // tato premenna bude dostupna v template
    public $comment;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Comment $comment)
    {
        $this->comment = $comment;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        // toto z commentu najde title blog postu ktoremu comment patri
        $subject = "Comment was posted on your {$this->comment->commentable->title} blog post";

        return $this
            // toto je nepovinne, davaju sa tam defaultne hodnoty z conf/mail.php
            ->from('admin@fiststep.hellbilling.com', 'Vesely admin')
            // defultne by tam pre tuto triedu malo byt Comment Posted
            ->subject($subject)
            // // pridanie prilohy do emailu s plnou cestou
            // ->attach(
            //     // cesta k suboru
            //     storage_path('app/public') . '/' . $this->comment->user->image->path,
            //     // ine parametre pre posielany subor
            //     [
            //         // meno suboru v prilohe
            //         'as' => 'meno_prilozeneho_suboru.jpg',
            //         'mime' => 'image/jpeg'

            //     ]
            // )
            // // pridanie prilohy do emailu so Storage facade
            // ->attachFromStorage($this->comment->user->image->path, 'meno_prilozeneho_suboru.jpg')
            // // // pridanie prilohy do emailu so Storage facade pre konkretny disk
            // ->attachFromStorageDisk('public', $this->comment->user->image->path, 'meno_prilozeneho_suboru.jpg', [
            // 'mime' => 'image/jpeg'
            // ])
            // // pridanie prilohy do emailu z pamate - napr ked niekto uploadoval file a rovno ho chceme preposlat
            // ->attachData(Storage::get($this->comment->user->image->path), 'meno_prilozeneho_suboru.jpg', [
            //     'mime' => 'image/jpeg'
            // ])
            // template ktoru dame do emailu
            ->view('emails.posts.commented');
    }
}
