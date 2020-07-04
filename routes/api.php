<?php

use Illuminate\Http\Request;

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
Route::post('register', 'UserController@register');
Route::post('login', 'UserController@authenticate');

//#crud
//Route::get('products', 'ProductController@index');
//Route::get('products/{id}', 'ProductController@show');
//Route::post('products/', 'ProductController@store');
//Route::put('products/{id}', 'ProductController@update');
//Route::delete('products/{id}', 'ProductController@destroy');
//
//
//Route::group(['middleware' => ['jwt.verify']], function() {
//    Route::get('user', 'UserController@getAuthenticatedUser');
//    Route::get('closed', 'DataController@closed');
//    });


