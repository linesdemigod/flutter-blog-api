<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
	//register
	public function register(Request $request)
	{
		$attr = $request->validate([
			'name' => 'required|string',
			'email' => 'required|unique:users,email',
			'password' => 'required|min:6|confirmed',
		]);

		//hash password
		$attr['password'] = Hash::make($attr['password']);

		//create user
		$user = User::create($attr);

		//return user and token
		return response([
			'user' => $user,
			'token' => $user->createToken('secret')->plainTextToken,
		], 200);
	}

	public function login(Request $request)
	{
		$attr = $request->validate([
			'email' => 'required|email',
			'password' => 'required|min:6',
		]);

		//attempt login
		if (!Auth::attempt($attr)) {
			return response([
				"message" => "Invalid Credentials",
			], 403);
		}
		$user = auth()->user();

		//return user and token
		return response([
			'user' => $user,
			'token' => $user->createToken('secret')->plainTextToken,
		], 200);
	}

	//logout
	public function logout()
	{

		auth()->user()->tokens()->delete();

		return response([
			"message" => "Logout success",
		], 200);
	}

	//get user details
	public function user()
	{

		return response([
			"user" => auth()->user(),
		], 200);
	}

	//update user
	public function update(Request $request)
	{
		$attr = $request->validate([
			'name' => 'required|string',
		]);

		$user = auth()->user();

		// $attrs['image'] = $this->saveImage($request->image, 'profiles');

		if ($request->hasFile('image')) {
			$attrs['image'] = $request->file('image')->store('profiless', 'public');
		}

		$user->update($attr);

		return response([
			"message" => "user updated",
			"user" => auth()->user(),
		], 200);

	}
}
