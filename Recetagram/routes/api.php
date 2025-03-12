<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\IngredientController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\FollowController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::prefix('v1')->group(function () {
    // Rutas públicas
    Route::post('/register', [AuthController::class, 'store']);
    Route::post('/login', [AuthController::class, 'login'])->name('login');
    
    // Rutas de posts públicas - NOTA EL ORDEN
    Route::get('/posts/public', [PostController::class, 'getPublicPosts']); // Primero rutas específicas públicas
    Route::get('/posts', [PostController::class, 'index']);
    
    Route::middleware('auth:sanctum')->group(function () {
        // Rutas de posts autenticadas
        Route::get('/posts/following', [PostController::class, 'getFollowingPosts']); // Primero rutas específicas autenticadas
        Route::post('/posts', [PostController::class, 'store']);
        Route::put('/posts/{post}', [PostController::class, 'update']);
        Route::delete('/posts/{post}', [PostController::class, 'destroy']);
    });

    Route::get('/posts/{post}', [PostController::class, 'show']); // ÚLTIMO las rutas con parámetros

    // Rutas protegidas
    Route::middleware('auth:sanctum')->group(function () {
        // Rutas de autenticación existentes
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::get('/users', [AuthController::class, 'index']);
        Route::get('/users/{user}', [AuthController::class, 'show']);
        Route::post('/users', [AuthController::class, 'store']);
        Route::put('/users/{user}', [AuthController::class, 'update']);
        Route::delete('/users/{user}', [AuthController::class, 'destroy']);
        Route::get('/me', [AuthController::class, 'me']);

        // Rutas de Posts
        Route::get('/users/{user}/posts', [PostController::class, 'getUserPosts']);

        // Rutas de comentarios
        Route::get('/posts/{post}/comments', [CommentController::class, 'index']);
        Route::get('/posts/{post}/comments/{comment}', [CommentController::class, 'show']);
        Route::post('/posts/{post}/comments', [CommentController::class, 'store']);
        Route::put('/posts/{post}/comments/{comment}', [CommentController::class, 'update']);
        Route::delete('/posts/{post}/comments/{comment}', [CommentController::class, 'destroy']);

        // Rutas de likes
        Route::post('/posts/{post}/like', [LikeController::class, 'toggle']);

        // Rutas de notificaciones
        Route::get('/notifications', [NotificationController::class, 'index']);
        Route::patch('/notifications/{id}/read', [NotificationController::class, 'markAsRead']);

        // Rutas de follows
        Route::post('/users/{user}/follow', [FollowController::class, 'follow']);
        Route::post('/follows/{followId}/accept', [FollowController::class, 'acceptFollow']);
        Route::post('/follows/{followId}/reject', [FollowController::class, 'rejectFollow']);
        Route::delete('/users/{user}/unfollow', [FollowController::class, 'unfollow']);
        Route::get('/users/{user}/followers', [FollowController::class, 'getFollowers']);
        Route::get('/users/{user}/following', [FollowController::class, 'getFollowing']);
        Route::get('/follows/pending', [FollowController::class, 'getPendingRequests']);
        Route::get('/follows/check/{user}', [FollowController::class, 'checkStatus']);
    });
});
