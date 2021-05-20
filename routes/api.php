<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
	return $request->user();
});
Route::post('/signup/', 'App\Http\Controllers\UserController@SignUp');
Route::post('/signin/', 'App\Http\Controllers\UserController@SignIn');
Route::post('/recover/', 'App\Http\Controllers\UserController@recoverPassword');
Route::post('/logout/', 'App\Http\Controllers\UserController@logout');
Route::post('/prodadd/', 'App\Http\Controllers\ProductController@addProduct');
Route::post('/proddel/', 'App\Http\Controllers\ProductController@deleteProduct');
Route::post('/prodchange/', 'App\Http\Controllers\ProductController@changeProduct');
Route::post('/prodsearch/', 'App\Http\Controllers\ProductController@searchProduct');




