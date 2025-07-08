<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Exception;
use Illuminate\Support\Facades\Auth;

class AuthenticateController extends Controller
{
    public function login(LoginRequest $request)
    {
        try {
            $credential = $request->validated();

            if (!Auth::attempt($credential)) {
                return response()->json([
                    'message' => 'Invalid email or password'
                ], 401);
            }

            $user = Auth::user();
            $user->tokens()->delete();
            $token = $user->createToken('user-token')->plainTextToken;

            return response()->json([
                'message' => 'Login successfully.',
                'user' => $user,
                'token' => $token
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Login failed.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function logout(){
        request()->user()->currentAccessToken()->delete();

        return response()->json([
            'message'=> 'Logout successfully.'
        ], 200);
    }
}
