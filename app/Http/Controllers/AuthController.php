<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class AuthController extends Controller
{
    public function register(Request $request)
    {

        $data =  $request->validate([
            'avatar'    => 'image|mimes:jpeg,png,jpg,svg|max:2048|nullable',
            'name'      => 'required|string|max:255',
            'email'     => 'required|email|unique:users,email',
            'password'  => 'required|min:8|confirmed'
        ]);


        if ($request->file('avatar')) {
            $data['avatar'] = $request->file('avatar')->store('avatar');
        }

        $data['password'] = Hash::make($data['password']);
        $data['role'] = 'user';

        $user = User::create($data);

        if ($user) {
            return response()->json(['user' => $user], 201);
        }

        return response()->json(['error' => 'User not created'], 400);
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $data = $request->validate([
            'avatar'   => 'image|mimes:jpeg,png,jpg,svg|max:2048|nullable',
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email,' . $user->id, // biar boleh pakai email lama
            'password' => 'nullable|min:8|confirmed'
        ]);


        if ($request->file('avatar')) {
            if ($user->avatar && Storage::exists($user->avatar)) {
                Storage::delete($user->avatar);
            }
            $data['avatar'] = $request->file('avatar')->store('avatar');
        }

        // Handle password baru
        if (!empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']); // jangan update kalau kosong
        }

        $user->update($data);

        return response()->json([
            'message' => 'User updated successfully',
            'user'    => $user
        ], 200);
    }



    public function login(Request $request)
    {
        $data =  $request->validate([
            'email'     => 'required|email',
            'password'  => 'required|min:8'
        ]);

        if (auth()->guard()->attempt($data)) {
            $user = auth()->user();
            $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json([
                'user' => $user,
                'token' => $token
            ], 200);
        } else {
            return response()->json([
                'message' => 'Email Atau Password Salah',
            ], 401);
        }
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Logout successful',
        ], 200);
    }


    public function user()
    {
        $user = User::all();

        return response()->json($user);
    }

    public function getUser(Request $request)
    {
        return response()->json($request->user());
    }
}
