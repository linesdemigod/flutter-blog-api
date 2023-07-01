<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\PostController;
use Illuminate\Support\Facades\Route;

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {

	//user
	Route::controller(AuthController::class)->group(function () {
		Route::get('/user', 'user');
		Route::put('/user', 'update');
		Route::post('/logout', 'logout');

	});

	//Post
	Route::controller(PostController::class)->group(function () {
		Route::get('/posts', 'index');
		Route::post('/posts', 'store');
		Route::get('/posts/{id}', 'show');
		Route::put('/posts/{id}', 'update');
		Route::delete('/posts/{id}', 'destroy');
	});

	//Comment
	Route::controller(CommentController::class)->group(function () {
		Route::get('/posts/{id}/comments', 'index');
		Route::post('/posts/{id}/comments', 'store');
		// Route::get('/comments/{id}', 'show');
		Route::put('/comments/{id}', 'update');
		Route::delete('/comments/{id}', 'destroy');
	});

	//Like
	Route::controller(LikeController::class)->group(function () {
		Route::post('/posts/{id}/likes', 'likeOrUnlike');
		// Route::post('/posts/{id}/comments', 'store');
		// Route::get('/comments/{id}', 'show');
		// Route::put('/comments/{id}', 'update');
		// Route::delete('/posts/{id}', 'destroy');
	});

});
