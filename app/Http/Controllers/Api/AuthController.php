<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests\LoginRequest;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(LoginRequest $request)
    {
        $credentials = $request->only(['mobile', 'password']);
        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            $token = $user->createToken('authToken')->accessToken;
            $user = User::where('mobile', $request->mobile)
                ->first();
            return prepareResult(true, ['token' => $token, 'user' => $user], [], 'User Logged in successfully', 200, []);
        } else {
            return prepareResult(false, [], ['email' => 'Unable to find user'], 'User not found', 401, []);
        }
    }
}
