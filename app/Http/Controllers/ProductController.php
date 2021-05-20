<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    public function addProduct(Request $req)
    {
    	$validator = Validator::make($req->all(), [
			"name" => "required",
			"quantity" => "required",
			"price" => "required",
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

		if(!$user->api_token)
			return response()->json(["message" => "Вам нужно авторизоваться"]);

		if($user->admin == "no")
			return response()->json(["message" => "Вы не обладаете нужными правами"]);

		Product::create($req->all());
		return response()->json([
			"message" => "Вы успешно добавили товар",
		]);
    }

    public function deleteProduct(Request $req)
    {
    	$user = User::where("login", $req->login)->first();

		if(!$user->api_token)
			return response()->json(["message" => "Вам нужно авторизоваться"]);

		if($user->admin == "no")
			return response()->json(["message" => "Вы не обладаете нужными правами"]);

    	$product = Product::where("id", $req->id)->first();

    	if(!$product)
	    	return response()->json(["message" => "Товар не существовал или был удалён ранее"], 201);

    	Product::where("id", $req->id)->delete();
    	return response()->json([
			"message" => "Вы успешно удалили товар",
		]);
    }

	public function changeProduct(Request $req)
	{
    	$validator = Validator::make($req->all(), [
			"name" => "required",
			"quantity" => "required",
			"price" => "required",
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

		if(!$user->api_token)
			return response()->json(["message" => "Вам нужно авторизоваться"]);

		if($user->admin == "no")
			return response()->json(["message" => "Вы не обладаете нужными правами"]);
		
		$product = Product::where("id", $req->id)->first();

		if(!$product)
			return response()->json(["message" => "Товар не существует или неверно указан id"], 301);

		$product->name = $req->name;
		$product->quantity = $req->quantity;
		$product->price = $req->price;
		$product->save();
		return response()->json(
			[
				"message" => "Вы успешно изменили данные товара"
			]);
    }

    public function searchProduct(Request $req)
    {
    	if(!$req)
    		return response()->json(
    			[
    				"message" => "Вы ничего не ввели в строку поиска"
    			]);

    	$search = $req->name;

    	$massearch = Product::where('name', 'LIKE', "%{$search}%")->get();

    	if(empty($massearch))
    		return response()->json(
    			[
    				"message" => "Ничего не найдено"
    			]);

    	return response()->json($massearch);
    }
}
