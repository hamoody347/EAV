<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'first_name' => 'required|max:55',
            'last_name' => 'required|max:55',
            'email' => 'required|email|unique:users',
            'password' => 'required|confirmed|min:8',
        ]);

        $user = User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $accessToken = $user->createToken('authToken')->accessToken;

        return response()->json([
            'user' => $user,
            'access_token' => $accessToken,
        ], 201);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        $token = $user->createToken('token')->accessToken;

        return response()->json(['token' => $token]);
    }

    public function logout(Request $request)
    {
        $user = Auth::user();

        $user->tokens->each(function ($token) {
            $token->delete();
        });

        return response()->json([
            'message' => 'Successfully logged out.',
        ]);
    }
}
