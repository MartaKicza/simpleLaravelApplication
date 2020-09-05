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

Route::middleware('api')->get('/users', 'UserController@index')->name('user.index');

Route::middleware('api')->post('/user', 'UserController@create')->name('user.create');

Route::middleware('api')->get('/user/{user}', 'UserController@show')->where('user', '[0-9]+')->name('user.show');

Route::middleware('api')->put('/user/{user}', 'UserController@update')->where('user', '[0-9]+')->name('user.update');

Route::middleware('api')->delete('/user/{user}', 'UserController@delete')->where('user', '[0-9]+')->name('user.delete');

Route::fallback(function(){
    return response()->json([
		'error' => 404,
        'message' => 'Page Not Found.'], 404);
});
