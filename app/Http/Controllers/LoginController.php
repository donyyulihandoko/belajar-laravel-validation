<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

class LoginController extends Controller
{
    public function formLogin(): Response
    {
        return response()->view('form-login');
    }

    public function submitLogin(LoginRequest $request): Response
    {
        $data = $request->validated(); //bug
        // do something with data
        Log::info(json_encode($request->all(), JSON_PRETTY_PRINT));

        return response('OK', 200);
    }
}
