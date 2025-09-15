<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class ValidatorTest extends TestCase
{
    public function testValidator()
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
        self::assertTrue($validation->passes());
        self::assertFalse($validation->fails());
    }


    public function testValidatorInvalid()
    {
        $data = [
            'username' => '',
            'password' => ''
        ];
        $rules = [
            'username' => ['required'],
            'password' => ['required']
        ];

        $validation = Validator::make($data, $rules);

        self::assertNotNull($validation);
        self::assertFalse($validation->passes());
        self::assertTrue($validation->fails());

        $message = $validation->getMessageBag();
        Log::info($message->toJson(JSON_PRETTY_PRINT));
    }

    public function testValidatorValidationException()
    {
        $data = [
            'username' => '',
            'password' => ''
        ];
        $rules = [
            'username' => ['required'],
            'password' => ['required']
        ];

        $validation = Validator::make($data, $rules);

        try {
            $validation->validate();
            self::fail('Validation error is not thrown');
        } catch (ValidationException $exception) {
            self::assertNotNull($exception->validator);
            $message = $exception->validator->errors();
            Log::error($message->toJson(JSON_PRETTY_PRINT));
        }
    }

    public function testValidatorMultipleRules()
    {
        $data = [
            'username' => 'dony',
            'password' => 'dony'
        ];
        $rules = [
            'username' => "required|email|max:100",
            'password' => ['required', 'min:6', 'max:20']
        ];

        $validation = Validator::make($data, $rules);

        self::assertNotNull($validation);
        self::assertFalse($validation->passes());
        self::assertTrue($validation->fails());

        $message = $validation->getMessageBag();
        Log::info($message->toJson(JSON_PRETTY_PRINT));
    }

    public function testValidatorValidData()
    {
        $data = [
            'username' => 'dony@gmail.com',
            'password' => 'password',
            'admin' => true,
            'others' => 'xxxxx'
        ];

        $rules = [
            'username' => ['required', 'email', 'max:255'],
            'password' => ['required', 'min:6', 'max:20']
        ];

        $validation = Validator::make($data, $rules);

        try {
            $data = $validation->validate();
            Log::info(json_encode($data, JSON_PRETTY_PRINT));
            self::assertNotNull($data);
        } catch (ValidationException $exception) {
            self::assertNotNull($exception->validator);
            $message = $exception->validator->errors();
            Log::error($message->toJson(JSON_PRETTY_PRINT));
        }
    }
}
