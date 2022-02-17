<?php

namespace Tests;

use App\Models\BlogPost;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use App\Models\User;
abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected function user()
    {
        return User::factory()->create();
    }

    protected function blogPost()
    {
        // newTitle je funkcia ktora vytvori testovaci blog post
        return BlogPost::factory()->newTitle()->create(
            [
                // user sa automaticky vytvori tu
                // takze mame usera s id 1
                'user_id' => $this->user()->id
            ]
        );
    }
}
