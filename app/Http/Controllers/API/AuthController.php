<?php

namespace App\Http\Controllers\API;

use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
  public function login(Request $request) {
    $validatedData = $request->validate([
      'email' => 'required|email',
      'password' => 'required'
    ]);

    $user = User::where('email', $validatedData['email'])->first();

    if (!$user || !Hash::check($validatedData['password'], $user->password)) {
        return response([
            'message' => ['These credentials do not match our records.']
        ], 422);
    }

    $token = $user->createToken('access-token')->plainTextToken;

    return response([
      'token' => $token,
      'user' => $user
    ], 201);
  }

  public function register(Request $request) {
    $validatedData = $request->validate([
      'email' => 'required|email|unique:users,email',
      'password' => 'required|confirmed|string|min:8',
      'name' => 'required|string'
    ]);

    $validatedData['password'] = Hash::make($validatedData['password']);

    $user = User::create($validatedData);

    $token = $user->createToken('access-token')->plainTextToken;

    return response([
      'token' => $token,
      'user' => $user
    ], 201);
  }
}
