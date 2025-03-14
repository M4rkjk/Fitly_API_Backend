<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $fields = $request->validate([
            'name' => 'required|max:255',
            'email' => 'required|email|unique:users',
            'gender' => 'required|in:male,female,other',
            'birthday' => 'required|date|before:today',
            'password' => 'required|confirmed'
        ]);

        $fields['password'] = bcrypt($request->password);

        $user = User::create($fields);

        $token = $user->createToken($request->name);

        return [
            'user' => $user,
            'token' => $token->plainTextToken
        ];
    }

    public function updateProfile(Request $request){
        $user = Auth::user();

        $fields = $request->validate([
            'age' => 'nullable|integer',
            'height' => 'nullable|numeric',
            'weight' => 'nullable|numeric',
            'recommended_calories' => 'nullable|numeric',
            'lose_or_gain' => 'nullable|in:lose,gain',
            'goal_weight' => 'nullable|numeric',
        ]);

        $user->update($fields);

        return response()->json([
            'message' => 'Profile updated successfully',
            'user' => $user
        ]);
    }

    public function login(Request $request)
    {
        $fields = $request->validate([
            'email' => 'required|email|exists:users',
            'password' => 'required'
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'errors' => [
                    'email' => ['The provided credentials are inconrrect.']
                ]
            ], 401);
        }

        $token = $user->createToken($user->name);

        return [
            'user' => $user,
            'token' => $token->plainTextToken
        ];
    }

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();

        return[
            'message' => 'You are logged out.'
        ];
    }
}
