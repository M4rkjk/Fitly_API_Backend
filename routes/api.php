<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\PostReactionController;
use App\Http\Controllers\PostReactionsController;
use App\Http\Controllers\RecipeController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


//Protected Routes
Route::group(['middleware' => ['auth:sanctum']], function() {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::post('/logout', [AuthController::class, 'logout']);

    Route::post('/posts/{post}/comments', [CommentController::class, 'store']);

    Route::put('/users/profile', [AuthController::class, 'updateProfile']);


});


//Public Routes

Route::post('/register', [AuthController::class, 'register']);

Route::post('/login', [AuthController::class, 'login']);

Route::apiResource('posts', PostController::class);

Route::post('posts/{post}/reactions', [PostReactionController::class, 'storeReaction']);

Route::get('posts/{post}/reactions', [PostReactionController::class, 'getReactionsForPost']);

Route::get('/posts/{post}/comments', [CommentController::class, 'index']);

Route::apiResource('recipes', RecipeController::class);



