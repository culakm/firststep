<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\BlogPost;
use App\Models\Comment;
use App\Models\User;

class CommentsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // toto je bez polymorfizmu, pretoze polymorfyzmus nepozna blog_post_id


        // $bps=BlogPost::all();
        // $users=User::all();

        // if($bps->count() < 1 || $users->count() < 1){
        //     $this->command->info('There are no blog posts or users, so no comments will be added!');
        //     return;
        // }

        // $comments_count = (int) $this->command->ask('How many comments to generate?', 100);
        
        
        // Comment::factory()->count($comments_count)->make()->each(
        //     function ($comment) use ($bps, $users) {
        //         $comment->blog_post_id = $bps->random()->id;
        //         $comment->user_id = $users->random()->id;
        //         $comment->save();
        //     }
        // );

        $bps=BlogPost::all();
        $users=User::all();

        if($bps->count() < 1 || $users->count() < 1){
            $this->command->info('There are no blog posts or users, so no comments will be added!');
            return;
        }

        $comments_count = (int) $this->command->ask('How many comments to generate?', 100);
        
        // polymorfne vytvori komenty pre blogposty 
        Comment::factory()->count($comments_count)->make()->each(
            function ($comment) use ($bps, $users) {
                $comment->commentable_id = $bps->random()->id;
                $comment->commentable_type = BlogPost::class;
                $comment->user_id = $users->random()->id;
                $comment->save();
            }
        );

        // polymorfne vytvori komenty pre userov 
        Comment::factory()->count($comments_count)->make()->each(
            function ($comment) use ($users) {
                $comment->commentable_id = $users->random()->id; // ktory user bol komentovany
                $comment->commentable_type = User::class;
                $comment->user_id = $users->random()->id; // kto komentoval usera
                $comment->save();
            }
        );
    }
}
