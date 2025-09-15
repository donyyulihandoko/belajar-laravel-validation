<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Validator;
use Tests\TestCase;

class ValidatorTest extends TestCase
{
    public function testValidation()
    {
        $data = [
            'username' => 'donyyulihandoko',
            'password' => 'password'
        ];
        $rules = [
            'username' => ['required'],
            'password' => ['required']
        ];

        $validation = Validator::make($data, $rules);

        self::assertNotNull($validation);
    }
}
