<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;

class AuthController extends Controller
{
    /**
     * Attempt to authenticate a new session.
     *
     * @param  \App\Http\Requests\LoginRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function login(LoginRequest $request)
    {
        $validated = $request->validated();

        $user = auth()->user();
        $token = $user->createToken($validated['token_name'] ?? 'auth_token');

        return response()->json([
            'user' => $user,
            'token' => $token->plainTextToken,
        ]);
    }
}
