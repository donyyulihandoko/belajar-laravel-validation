<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class FormController extends Controller
{
    public function login(Request $request)
    {
        try {

            $rules = [
                'username' => ['required'],
                'password' => ['required']
            ];

            $validator = $request->validate($rules);
            return response('OK', 200);
        } catch (ValidationException $exception) {
            return response($exception->errors(), 400);
        }
    }
}
