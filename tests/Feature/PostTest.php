<?php

namespace Tests\Feature;

use App\Models\BlogPost;
use App\Models\Comment;

use Illuminate\Foundation\Testing\RefreshDatabase;

use Tests\TestCase;

class PostTest extends TestCase
{

    use RefreshDatabase;

    public function testNoBlogPostWhenNothingInDB()
    {
        $response = $this->get('/posts');

        $response->assertSeeText('No blog posts yet!');
    }

    public function testSee1BlogPostWhenThereIs1WithNoComments()
    {
        // Arrange part
        $bp = $this->createDummyBlogPost();

        // Pretoze BlogPost::factory() vdaka afterCreating vytvara 3 Comment tu ich treba vybazat aby sme mali BlogPost bez komentarov
        Comment::where('blog_post_id', '=', $bp->id)->delete();
        
        // Act
        $response = $this->get('/posts');

        // Assert
        // Site
        $response->assertSeeText('1Post title');
        $response->assertSeeText('Add comment');

        // DB
        $this->assertDatabaseHas('blog_posts',[
            'title' => '1Post title'
        ]);
    }

    public function testSee1BlogPostWithComments()
    {
        $post = $this->createDummyBlogPost();
        
        //Comment::factory(4)->create([
        Comment::factory()->count(4)->create([
            'blog_post_id' => $post->id
        ]);

        $response = $this->get('/posts');

        $response->assertSeeText('Comments: 7');
    }

    public function testStoreValid()
    {
        $params = [
            'title' => 'Valid title',
            'content' => 'At least 10 characters.'
        ];

        $this->actingAs($this->user())
            ->post('/posts', $params) //
            ->assertStatus(302) //redirect success
            ->assertSessionHas('status'); //flash status is shown

        $this->assertEquals(session('status'), 'BlogPost was created');
    }

    public function testStoreFail()
    {
        $params = [
            'title' => 'xx',
            'content' => 'x'
        ];

        $this->actingAs($this->user())
            ->post('/posts', $params) //
            ->assertStatus(302) //redirect success
            ->assertSessionHas('errors'); //flash status is shown

        $messages = session('errors')->getMessages();
        $this->assertEquals($messages['title'][0], 'The title must be at least 3 characters.');
        $this->assertEquals($messages['content'][0], 'The content must be at least 5 characters.');
    }

    public function testUpdateValid()
    {

        $user = $this->user();
        $post = $this->createDummyBlogPost($user->id);

        $this->assertDatabaseHas('blog_posts',$post->getAttributes());


        $params = [
            'title' => 'Updated title',
            'content' => 'updated content'
        ];

        $this->actingAs($user) //$this->user() je definovana v tests/TestCase.php
            ->put("/posts/{$post->id}", $params) // najde id novovytvoreneho postu
            ->assertStatus(302) //redirect success
            ->assertSessionHas('status'); //flash status is shown

        $this->assertEquals(session('status'), 'BlogPost was updated');
        
        // stary title neexistuje
        $this->assertDatabaseMissing('blog_posts',['title' => '1Post title']);

        // novy title existuje
        $this->assertDatabaseHas('blog_posts',['title' => 'Updated title']);

    }

    public function testDelete()
    {
        // vytvorime testovacieho usera
        $user = $this->user();
        // vytvorime testovacu post
        $post = $this->createDummyBlogPost($user->id);

        $this->assertDatabaseHas('blog_posts',$post->getAttributes());

        $this->actingAs($user)
            ->delete("/posts/{$post->id}") // najde id novovytvoreneho postu
            ->assertStatus(302) //redirect success
            ->assertSessionHas('status'); //flash status is shown

            $this->assertEquals(session('status'), 'BlogPost was deleted');

            //$this->assertDatabaseMissing('blog_posts',$post->getAttributes());
            $this->assertSoftDeleted('blog_posts',$post->getAttributes());
    }

    private function createDummyBlogPost( $user_id = null ): BlogPost
    {
        // vytvorime testovacu post
        // $post = new BlogPost();
        // $post->title = '1Post title';
        // $post->content = '1content';
        // $post->save();

        $post = BlogPost::factory()->newTitle()->create(
            [
                'user_id' => $user_id ?? $this->user()->id,
            ]
        );

        return $post;
    }
}
