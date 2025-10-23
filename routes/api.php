<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\ReactionController;
use App\Http\Controllers\Api\UserController;

// Rota pública para o registro de usuário
Route::post('register', [AuthController::class, 'register']);

// Rota pública para login (gera o token JWT)
Route::post('login', [AuthController::class, 'login']);

// Rota pública para visualizar artigos
Route::apiResource('articles', ArticleController::class)->only(['index', 'show']);

// Rota pública para visualizar comentários de artigos
Route::get('articles/{article}/comments', [CommentController::class, 'index']);

// Rota pública para visualizar a lista de usuários
Route::get('users', [UserController::class, 'index']);

// Rota pública para obter dados de exemplo (agora com ExampleController) // Atualizado para ExampleController

// Rotas protegidas por JWT
Route::middleware('auth:api')->group(function () {
    // Rota protegida para criar, atualizar ou excluir artigos
    Route::apiResource('articles', ArticleController::class)->only(['store', 'update', 'destroy']);
    
    // Rota protegida para adicionar um comentário em um artigo
    Route::post('articles/{article}/comments', [CommentController::class, 'store']);
    
    // Rotas protegidas para gerenciar os comentários
    Route::apiResource('comments', CommentController::class)->only(['show', 'update', 'destroy']);
    
    // Rotas para curtir artigos e comentários
    Route::post('articles/{article}/like', [ReactionController::class, 'likeArticle']);
    Route::post('comments/{comment}/like', [ReactionController::class, 'likeComment']);
    
    // Rota para logout, invalidando o token
    Route::post('logout', [AuthController::class, 'logout']);
});

// Rota protegida para os comentários
Route::apiResource('comments', CommentController::class)->only(['store', 'show', 'update', 'destroy']);
