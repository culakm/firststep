<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
//use Illuminate\Support\Facades\DB;
//use Illuminate\Support\Str;
use App\Models\User;
use App\Models\BlogPost;
use App\Models\Comment;
use Illuminate\Support\Facades\Cache;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        
        // Manual insert
        // DB::table('users')->insert([
        //     'name' => 'John Brown33',
        //     'email' => 'johnov@emailovac.sk',
        //     'email_verified_at' => now(),
        //     'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
        //     'remember_token' => Str::random(10),
        // ]);

        // Factory insert
        // Create users
        // $default_user = User::factory()->defaultUser()->create();
        // $other_users = User::factory()->count(20)->create();
        // $users = $other_users->concat([$default_user]);

        // Create BlogPosts
        // $bps = BlogPost::factory()->count(50)->make()->each( function ($post) use ($users) {
        //     $post->user_id = $users->random()->id;
        //     $post->save();
        // });

        // Create Comments
        // $comments = Comment::factory()->count(150)->make()->each( function ($comment) use ($bps) {
        //     $comment->blog_post_id = $bps->random()->id;
        //     $comment->save();
        // });

        // User Classes insert
        

        // Interactive
        if ($this->command->confirm('Do you want to refresh DB?', true)) {
            $this->command->call('migrate:refresh');
            $this->command->info('Database was refreshed'); // tu moze byt aj line()
        }
        
        // odstani cache ktore maju tag blog_post a boli naplnene starymi datami
        Cache::tags('blog_post')->flush();

        $this->call([
            UsersTableSeeder::class,
            BlogPostsTableSeeder::class,
            CommentsTableSeeder::class,
            TagsTableSeeder::class,
            BlogPostTagTableSeeder::class
        ]);
    }
}
