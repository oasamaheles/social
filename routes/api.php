<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['prefix'=>'auth'],function (){
    Route::post('/register', [\App\Http\Controllers\Api\Auth\RegisterController::class, 'register']);
    Route::post('/login', [\App\Http\Controllers\Api\Auth\RegisterController::class, 'login']);
});

Route::group(['prefix' => 'v1','middleware'=>'auth:api'], function () {
    Route::resource('users', UserController::class);
    Route::resource('posts', \App\Http\Controllers\Api\PostController::class);
    Route::resource('comments', \App\Http\Controllers\Api\CommentController::class);
    Route::post('comments/post/{post_id}', [\App\Http\Controllers\Api\CommentController::class,'store']);
//    Route::put('comments/{id}', [\App\Http\Controllers\Api\CommentController::class,'update']);


//
//    Route::get('users',[\App\Http\Controllers\UserController::class,'index']);
//    Route::get('users/{id}',[\App\Http\Controllers\UserController::class,'show']);
//
//    Route::get('posts',[\App\Http\Controllers\PostController::class,'index']);
//    Route::get('posts/{post}',[\App\Http\Controllers\PostController::class,'show']);
});
