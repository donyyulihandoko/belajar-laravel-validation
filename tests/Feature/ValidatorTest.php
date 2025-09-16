<?php

namespace Tests\Feature;

use App\Rules\RegistrationRule;
use App\Rules\Uppercase;
use Closure;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\In;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;
use Illuminate\Validation\Validator as ValidationValidator;
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

    public function testValidatorAdditional()
    {
        $data = [
            'username' => 'dony@gmail.com',
            'password' => 'dony@gmail.com',
            'admin' => true,
            'others' => 'xxxxx'
        ];

        $rules = [
            'username' => ['required', 'email', 'max:255'],
            'password' => ['required', 'min:6', 'max:20']
        ];

        $validation = Validator::make($data, $rules);
        $validation->after(function (ValidationValidator $validator) {
            $data = $validator->getData();
            if ($data['username'] === $data['password']) {
                $validator->errors()->add('password', 'Password Tidak Boleh Sama Dengan Userneame');
            }
        });

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

    public function testValidatorCustomRule()
    {
        $data = [
            'username' => 'dony@gmail.com',
            'password' => 'dony@gmail.com',
            'admin' => true,
            'others' => 'xxxxx'
        ];

        $rules = [
            'username' => ['required', 'email', 'max:255', new Uppercase()],
            'password' => ['required', 'min:6', 'max:20']
        ];

        $validation = Validator::make($data, $rules);
        // $validation->after(function (ValidationValidator $validator) {
        //     $data = $validator->getData();
        //     if ($data['username'] === $data['password']) {
        //         $validator->errors()->add('password', 'Password Tidak Boleh Sama Dengan Userneame');
        //     }
        // });

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

    public function testValidatorCustomRuleDataAware()
    {
        $data = [
            'username' => 'dony@gmail.com',
            'password' => 'dony@gmail.com',
            'admin' => true,
            'others' => 'xxxxx'
        ];

        $rules = [
            'username' => ['required', 'email', 'max:255', new Uppercase()],
            'password' => ['required', 'min:6', 'max:20', new RegistrationRule()]
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

    public function testValidatorCustomFunctionRule()
    {
        $data = [
            'username' => 'dony@gmail.com',
            'password' => 'dony@gmail.com',
            'admin' => true,
            'others' => 'xxxxx'
        ];

        $rules =
            [
                'username' => ['required', 'email', 'max:255', function (string $attribute, string $value, Closure $fail) {
                    if ($value !== strtoupper($value)) $fail("$attribute must be UPPERCASE custom function rule");
                }],
                'password' => ['required', 'min:6', 'max:20', new RegistrationRule()]
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

    public function testValidatorRuleClasses()
    {
        $data = [
            'username' => 'dony@gmail.com',
            'password' => 'dony@gmail.com',
            'admin' => true,
            'others' => 'xxxxx'
        ];

        $rules =
            [
                'username' => ['required', new In(['Eko', 'Budi', 'Joko'])],
                'password' => ['required', Password::min(6)->letters()->numbers()->symbols()]
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

    public function testNestedArray()
    {
        $data = [
            "name" => [
                "first" => "Eko",
                "last" => "Kurniawan"
            ],
            "address" => [
                "street" => "Jalan. Mangga",
                "city" => "Jakarta",
                "country" => "Indonesia"
            ]
        ];

        $rules = [
            "name.first" => ["required", "max:100"],
            "name.last" => ["max:100"],
            "address.street" => ["max:200"],
            "address.city" => ["required", "max:100"],
            "address.country" => ["required", "max:100"],
        ];

        $validator = Validator::make($data, $rules);
        self::assertTrue($validator->passes());
    }

    public function testNestedIndexedArray()
    {
        $data = [
            "name" => [
                "first" => "Eko",
                "last" => "Kurniawan"
            ],
            "address" => [
                [
                    "street" => "Jalan. Mangga",
                    "city" => "Jakarta",
                    "country" => "Indonesia"
                ],
                [
                    "street" => "Jalan. Manggis",
                    "city" => "Jakarta",
                    "country" => "Indonesia"
                ]
            ]
        ];

        $rules = [
            "name.first" => ["required", "max:100"],
            "name.last" => ["max:100"],
            "address.*.street" => ["max:200"],
            "address.*.city" => ["required", "max:100"],
            "address.*.country" => ["required", "max:100"],
        ];

        $validator = Validator::make($data, $rules);
        self::assertTrue($validator->passes());
    }
}
