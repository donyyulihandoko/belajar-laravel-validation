<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class LoginControllerTest extends TestCase
{
    public function testLoginFailed()
    {
        $response = $this->post('/form', [
            'username' => '',
            'password' => ''
        ]);

        $response->assertStatus(302);
    }

    public function testLoginSuccess()
    {
        $response = $this->post('/form', [
            'username' => 'user1@example.comp',
            'password' => 'example_password1'
        ]);

        $response->assertStatus(200);
    }
}
