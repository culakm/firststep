<?php

namespace Database\Seeders;

use App\Models\BlogPost;
use App\Models\Tag;
use Illuminate\Database\Seeder;

class BlogPostTagTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $tag_count = Tag::all()->count();

        if ($tag_count === 0) {
            $this->command->info('No tags found, skipping assigning tags to blog post');
            return;
        }

        // zadanie min a max poctu tagov pre jeden blog post
        $tags_min = (int) $this->command->ask('Minimum tags on blog post?', 0);
        // aksa zada nieco vacsie ako je $tag_count, pojde tam aj tak $tag_count
        $tags_max = min(
            (int) $this->command->ask('Maximum tags on blog post?', $tag_count),
            $tag_count
        );


        BlogPost::all()->each(function (BlogPost $post) use($tags_min,$tags_max) {
            // Vyberieme nahodne kolko ma byt tagov priradenych
            $take = random_int($tags_min,$tags_max);
            // Vyberieme id tago v nahodnom poradi ale len toko kolko ich chceme
            $tags = Tag::inRandomOrder()->take($take)->get()->pluck('id');
            // sync ulozi vsetky tag_id do pivot table
            $post->tags()->sync($tags);
        });
    }
}
