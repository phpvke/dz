<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Str;

class UserController extends Controller
{
	public function signUp(Request $req)
	{
		$validator = Validator::make($req->all(), [
			"name" => "required",
			"lastname" => "required",
			"login" => "required|unique:users",
			"password" => "required|min:8",
		]);

		if($validator->fails()) 
		{
			return response()->json(
				[
					"message" => $validator->errors(),
				]);
		}
		$mas_users = $req->all();
		$mas_users += 
		[
			'recovery_code' => random_int(1000, 9999),
			'admin' => 'no',
		];
		User::create($mas_users);
		return response()->json([
			"message" => "Вы зарегистрированы, сохраните данный код на случай потери пароля",
			"recovery_code" => $mas_users["recovery_code"]
		]);
	}

	public function signIn(Request $req)
	{
		$validator = Validator::make($req->all(), [
			"login" => "required",
			"password" => "required",
		]);

		if($validator->fails()) 
		{
			return response()->json(
				[
					"message" => $validator->errors(),
				]);
		}

		$user = User::where("login", $req->login)->first();

		if($user)
		{
			if($req->password && $user->password)
			{
				$user->api_token = Str::random(50);
				$user->save();
				return response()->json(
					[
						"message" => "Вы успешно авторизовались",
						"api_token" => $user->api_token, 
					]
				);
			}
		}

		return response()->json(
			[
				"message" => "Вы не зарегистрированы!"
			]
		);
	}

	public function recoverPassword(Request $req)
	{
		$validator = Validator::make($req->all(), [
			"login" => "required",
			"recovery_code" => "required",
			"new_password" => "required|min:8"
		]);

		if($validator->fails()) 
		{
			return response()->json(
				[
					"message" => $validator->errors(),
				]);
		}

		$user = User::where("login", $req->login)->first();

		if($user)
		{
			if($req->recovery_code && $user->recovery_code)
			{
				$user->password = $req->new_password;
				$user->save();
				return response()->json(
					[
						"message" => "Вы успешно изменили пароль"
					]
				);
			}
		}
	}

	public function logout(Request $req)
	{
		$validator = Validator::make($req->all(), [
			"login" => "required",
		]);

		if($validator->fails()) 
		{
			return response()->json(
				[
					"message" => $validator->errors(),
				]);
		}

		$user = User::where("login", $req->login)->first();

		if($user)
		{
			if($user->api_token)
			{
				$user->api_token = NULL;
				$user->save();
				return response()->json(
					[
						"message" => "Вы успешно вышли из аккаунта", 
					]
				);
			}
		}
	}
}