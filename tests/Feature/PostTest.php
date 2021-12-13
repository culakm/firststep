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

        $response->assertSeeText('Posts not found');
    }

    public function testSee1BlogPostWhenThereIs1WithNoComments()
    {
        // Arrange part
        $post = $this->createDummyBlogPost();

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

        $response->assertSeeText('Comments: 4');
    }

    public function testStoreValid()
    {
        $params = [
            'title' => 'Valid title',
            'content' => 'At least 10 characters.'
        ];

        $this->post('/posts', $params) //
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

        $this->post('/posts', $params) //
            ->assertStatus(302) //redirect success
            ->assertSessionHas('errors'); //flash status is shown

        $messages = session('errors')->getMessages();
        $this->assertEquals($messages['title'][0], 'The title must be at least 3 characters.');
        $this->assertEquals($messages['content'][0], 'The content must be at least 5 characters.');
    }

    public function testUpdateValid()
    {

        $post = $this->createDummyBlogPost();

        $this->assertDatabaseHas('blog_posts',$post->getAttributes());


        $params = [
            'title' => 'Updated title',
            'content' => 'updated content'
        ];

        $this->put("/posts/{$post->id}", $params) // najde id novovytvoreneho postu
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
        // vytvorime testovacu post
        $post = $this->createDummyBlogPost();

        $this->assertDatabaseHas('blog_posts',$post->getAttributes());

        $this->delete("/posts/{$post->id}") // najde id novovytvoreneho postu
            ->assertStatus(302) //redirect success
            ->assertSessionHas('status'); //flash status is shown

            $this->assertEquals(session('status'), 'BlogPost was deleted');

            $this->assertDatabaseMissing('blog_posts',$post->getAttributes());
    }

    private function createDummyBlogPost(): BlogPost
    {
        // vytvorime testovacu post
        // $post = new BlogPost();
        // $post->title = '1Post title';
        // $post->content = '1content';
        // $post->save();
        $post = BlogPost::factory()->newTitle()->create();
        return $post;
    }
}
