<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
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
		]);

		if($validator->fails()) 
		{
			return response()->json(
				[
					"message" => $validator->errors(),
				]);
		}

		Product::create($req->all());
		return response()->json([
			"message" => "Вы успешно добавили товар",
		]);
    }

    public function deleteProduct(Request $req)
    {
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
		]);

		if($validator->fails()) 
		{
			return response()->json(
				[
					"message" => $validator->errors(),
				]);
		}

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
}
