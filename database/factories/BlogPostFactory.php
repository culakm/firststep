<?php

namespace Database\Factories;

use App\Models\BlogPost;
use App\Models\Comment;
use Illuminate\Database\Eloquent\Factories\Factory;

class BlogPostFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'title' => $this->faker->sentence(10),
            'content' => $this->faker->paragraphs(1,true)
        ];
    }

    public function newTitle()
    {
        return $this->state([
            'title' => '1Post title',
            'content' => '1content',
        ]);
    }

    public function configure()
    {
        // return $this->afterCreating(function (Author $author) {
        //     $author->profile()->save(Profile::factory()->make());
        // });

        return $this->afterMaking(function (BlogPost $bp) {
            //
        })->afterCreating(function (BlogPost $bp) {
            //$bp->comments()->save(Comment::factory()->make());
            
            
            // foreach (Comment::factory()->count(3)->make() as $comment){
            //     $bp->comments()->save($comment);
            // }

            Comment::factory()->count(3)->for($bp)->create();

        });
    }
}
