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
            'username' => 'example_username',
            'password' => 'example_password'
        ]);

        $response->assertStatus(200);
    }
}
