<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\Auth;
use Illuminate\Http\Request;

class AuthController
{
    public function authenticate(Request $request)
    {
        $credentials = $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        if (! auth()->once($credentials)) {
            return response()->json('Unauthorized', 401);
        }

        return new Auth(auth()->user());
    }
}