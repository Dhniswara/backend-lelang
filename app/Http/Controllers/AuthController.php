<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register (Request $request) {

        $data =  $request->validate([
            'avatar' => 'image|mimes:jpeg,png,jpg,svg|max:2048|nullable',
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8|confirmed'
        ]);

        $data['password'] = Hash::make($data['password']);
        $data['role'] = 'user';

        $user = User::create($data);
        
        if ($user) {
            return response()->json(['user' => $user], 201);
        }

        return response()->json(['error' => 'User not created'], 400);
    }

    public function login (Request $request) {
        $data =  $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:8'
        ]);

        if (auth()->guard()->attempt($data)) {
             $user = auth()->user();
             $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json([
                'user' => $user,
                'token' => $token
            ], 200);
        }
         else {
            return response()->json([
                'message' => 'Login failed',
            ], 401);
        }
    }

    public function logout (Request $request) {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Logout successful',
        ], 200);
    }

    
    public function user() {
        $farrel = User::all();

        return response()->json($farrel);
    }

}
