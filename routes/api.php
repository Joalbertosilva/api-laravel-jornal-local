<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\ReactionController;
use App\Http\Controllers\Api\UserController;

Route::post('register', [AuthController::class, 'register']);
Route::post('login',    [AuthController::class, 'login']);

Route::apiResource('articles', ArticleController::class)->only(['index','show']);
Route::get('articles/{article}/comments', [CommentController::class, 'index']);
Route::get('users', [UserController::class, 'index']);

Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('articles', ArticleController::class)->only(['store','update','destroy']);

    Route::post('articles/{article}/comments', [CommentController::class, 'store']);
    Route::apiResource('comments', CommentController::class)->only(['show','update','destroy']);

    Route::post('articles/{article}/like',  [ReactionController::class, 'likeArticle']);
    Route::post('comments/{comment}/like',  [ReactionController::class, 'likeComment']);

    Route::post('logout', [AuthController::class, 'logout']);
    
});









