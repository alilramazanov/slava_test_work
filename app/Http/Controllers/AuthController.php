<?php

namespace App\Http\Controllers;

use App\Http\Requests\User\LoginUserRequest;
use App\Http\Requests\User\RegisterUserRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
	public function register(RegisterUserRequest $request)
	{
		User::create($request->toArray());

		return response()->json(['message' => 'Registration successful'], 201);
	}

	public function login(LoginUserRequest $request)
	{
		$credentials = request(['email', 'password']);

		if (!Auth::attempt($credentials)) {
			return response()->json(['message' => 'Invalid email or password'], 401);
		}

		$user = $request->user();
		$token = $user->createToken('Personal Access Token')->plainTextToken;

		return response()->json(['token' => $token]);
	}

	public function logout(Request $request)
	{
		$request->user()->tokens()->delete();

		return response()->json(['message' => 'Logged out']);
	}
}
