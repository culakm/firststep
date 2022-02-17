<?php

namespace Tests\Feature;

use App\Models\BlogPost;
use App\Models\Comment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ApiPostCommentsTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    // public function test_example()
    // {
    //     $response = $this->get('/');

    //     $response->assertStatus(200);
    // }

    public function testtestNewBlogPostDoesNotHaveComments_example()
    {
        // blogpost sa automaticky vytvori v TestCase (extends TestCase)
        // tam sa tiez vytvori $this->user()
        $this->blogPost();

        // POZOR, pre tento test je to prvy vytvoreny BlogPost v tomto scripte takze id v ceste je 1
        $response = $this->json('GET', 'api/v1/posts/1/comments');

        $response->assertStatus(200)
            ->assertJsonStructure(['data','links','meta']) // struktura obsahuje tieto keys
            ->assertJsonCount(0,'data') // key 'data' nema ziadne polozky
        ;
    }

    public function testBlogPostHas10Comments()
    {

        // vytvorime BlogPost a create ho hned ulozi do DB
        $this->blogPost()->each(function (BlogPost $post){
                // pre kazdy BlogPost ulozime do DB commenty ktore sa vytvoria nizsie
                $post->comments()->saveMany(
                    //vytvorime 10 commentov
                    Comment::factory(10)->make([
                        'user_id' => $this->user()->id
                    ])
                );
            }
        );

        // POZOR, pre tento test je to druhy vytvoreny BlogPost v tomto scripte takze id v ceste je 2
        // aj ked je pouzite use RefreshDatabase; a vtedy sa pred kazdym testom DB vymaze, ale id sa posuvaju dalej
        $response = $this->json('GET', 'api/v1/posts/2/comments');

        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => [ // kazdy z elementov ulozenych v 'data' obsahuje nasledovene kluce
                        'zmeneny_nazov_id',
                        'content',
                        'created_at',
                        'updated_at',
                        'user_napriamo' => [
                            'id',
                            'name'
                        ]
                    ]
                ],
            'links',
            'meta'
            ]) // struktura obsahuje tieto keys
            ->assertJsonCount(10,'data') // key 'data' nema ziadne polozky
        ;
    }

    public function testAddingCommentsWhenNotAuthenticated()
    {
        $this->blogPost();

        $response = $this->json('POST', 'api/v1/posts/3/comments', [
            'content' => 'Hallo'
        ]);

        // status 401 - user is not authorized
        //$response->assertStatus(401); //toto je rovnake
        $response->assertUnauthorized();
    }

    public function testAddingCommentsWhenAuthenticated()
    {
        $this->blogPost();

        $response = $this->actingAs($this->user(),'api')->json('POST', 'api/v1/posts/4/comments', [
            'content' => 'Hallo'
        ]);


        $response
            ->assertStatus(201)
            ->assertJsonStructure([
                'zmeneny_nazov_id',
            ]);
    }

    public function testAddingCommentWithInvalidData()
    {
        $this->blogPost();

        $response = $this->actingAs($this->user(),'api')->json('POST', 'api/v1/posts/5/comments', []);


        $response
            ->assertStatus(422) // invalid json data error code
            ->assertJsonMissing(['zmeneny_nazov_id']) // toto je tam len pri uspesnom loadovani
            ->assertJsonStructure(['message','errors']) // toto je neuspech
            ->assertJson([                             // toto je presne to, co sa ma vratit
                'message' => 'The given data was invalid.',
                'errors' => [
                    'content' => [
                        'The content field is required.'
                    ]
                ]
            ])
        ;
    }
}
