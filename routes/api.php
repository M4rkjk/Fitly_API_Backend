<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\IngredientController;
use App\Http\Controllers\MealController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\PostReactionController;
use App\Http\Controllers\PostReactionsController;
use App\Http\Controllers\RecipeController;
use App\Http\Controllers\UserController;
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

    Route::get('/users', [UserController::class, 'index']);

    Route::get('/users/{id}', [UserController::class, 'show']);

    Route::get('/meals', [MealController::class, 'index']);

    Route::post('/meals/user', [MealController::class, 'addMealToUser']);

    Route::get('/users/{userId}/calories', [MealController::class, 'getUserTotalCalories']);

    Route::delete('/meals/user/{mealId}', [MealController::class, 'removeMealFromUser']);

});


//Public Routes

Route::post('/register', [AuthController::class, 'register']);

Route::post('/login', [AuthController::class, 'login']);

Route::apiResource('/posts', PostController::class);

Route::post('/posts/{post}/reactions', [PostReactionController::class, 'storeReaction']);

Route::get('/posts/{post}/reactions', [PostReactionController::class, 'getReactionsForPost']);

Route::get('/posts/{post}/comments', [CommentController::class, 'index']);

Route::apiResource('/recipes', RecipeController::class);






