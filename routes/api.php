<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\LikeController;

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

Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::middleware(['auth:sanctum', 'token'])->group(function () {
    Route::get('/blogs', [BlogController::class, 'index']);
    Route::post('/blogs', [BlogController::class, 'store']);
    Route::get('/blogs/{id}', [BlogController::class, 'show']);
    Route::post('/blogs/{id}', [BlogController::class, 'update']);
    Route::delete('/blogs/{id}', [BlogController::class, 'destroy']);


    Route::get('/blogs/{blogId}/posts', [PostController::class, 'index']);
    Route::post('/blogs/{blogId}/posts', [PostController::class, 'store']);
    Route::get('/blogs/{blogId}/posts/{postId}', [PostController::class, 'show']);
    Route::post('/blogs/{blogId}/posts/{postId}', [PostController::class, 'update']);
    Route::delete('/blogs/{blogId}/posts/{postId}', [PostController::class, 'destroy']);

    Route::post('/posts/{postId}/like', [LikeController::class, 'store']);
    Route::post('/posts/{postId}/comment',[CommentController::class, 'store']);

});
