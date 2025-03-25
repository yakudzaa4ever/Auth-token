<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
// use Illuminate\Support\Facades\Validator;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\LoginRequest;


class AuthController extends Controller
{
    public function register(RegisterRequest $request)
    {
        $user = User::create([  
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json(['status' => 201, 'user' => $user, 'token' => $token, 'message' => 'Foydalanuvchi yaratildi'], 201);
    }

    public function login(LoginRequest $request)
    {

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['status' => 401, 'message' => 'Notogri email yoki parol'], 401);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json(['status' => 200, 'user' => $user, 'token' => $token, 'message' => 'Muvaffaqiyatli tizimga kirdingiz'], 200);
    }

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();

        return response()->json(['status' => 200, 'message' => 'Chiqdingiz'], 200);
    }
}
