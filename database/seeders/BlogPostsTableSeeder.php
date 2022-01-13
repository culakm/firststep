<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\BlogPost;
use App\Models\User;

class BlogPostsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $bps_count = (int) $this->command->ask('How many blog posts to generate?', 50);
        $users=User::all();
        
        BlogPost::factory()->count($bps_count)->make()->each(
            function ($post) use ($users) {
                $post->user_id=$users->random()->id;
                $post->save();
            }
        );
    }
}
