<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class LoginController extends Controller
{
    public function formLogin(): Response
    {
        return response()->view('form-login');
    }

    public function submitLogin(LoginRequest $request): Response
    {
        $data = $request->validated();
        // do something with data
        return response('OK', 200);
    }
}
