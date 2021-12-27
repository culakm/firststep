<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\BlogPost;
use App\Models\Comment;

class CommentsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $bps=BlogPost::all();

        if($bps->count() < 1){
            $this->command->info('There are no blog posts. so no comments will be added!');
            return;
        }
        $comments_count = (int) $this->command->ask('How many comments to generate?', 200);

        
        Comment::factory()->count($comments_count)->make()->each(
            function ($comment) use ($bps) {
                $comment->blog_post_id = $bps->random()->id;
                $comment->save();
            }
        );
    }
}
