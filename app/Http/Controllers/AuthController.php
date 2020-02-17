<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Token;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(Request $request) {
        $request = $request->validate([
            'name' => 'required',
            'password' => 'required'
        ]);

        if(Auth::attempt($request)) {
            $userId = Auth::user()->id;
            $userName = Auth::user()->name;
            $userRole = Auth::user()->role;

            $token = md5($userId);

            Token::create([
                'token' => $token,
                'user_id' => $userId
            ]);

            return response()->json(['message' => 'success', 'token' => $token, 'role' => $userRole], 200);
        }

        return response()->json(['message' => 'error'], 401);
    }

    public function logout($token) {
        $isToken = Token::where('token', $token)->first();

        if(!$isToken) {
            return response()->json(['message' => 'invalid token'], 401);
        }

        $isToken->delete();
        return response()->json([
            'message' => 'success'
        ], 200);
    }
}
