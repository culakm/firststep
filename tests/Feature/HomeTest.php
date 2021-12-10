<?php

namespace Tests\Feature;

use Tests\TestCase;

class HomeTest extends TestCase
{

    public function testHomePageIsWorkingCorrectly()
    {
        $response = $this->get('/');

        $response->assertStatus(200);

        $response->assertSeeText('Hello world2, ja som home page,index');
    }

    public function testContactPageIsWorkingCorrectly()
    {
        $response = $this->get('/contact');

        $response->assertStatus(200);

        $response->assertSeeText('Contact page');
    }
}
