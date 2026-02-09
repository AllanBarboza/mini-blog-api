<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\User\UserController;
use App\Http\Controllers\User\UserAuthController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\CommentController;


Route::post('/users', [UserController::class, 'store']);
Route::post('/login', [UserAuthController::class, 'login']);
Route::post('/admins/login', [AdminAuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {

    Route::get('/me', function (Request $request) {
        return $request->user();
    });

    Route::middleware('can:user')->group(function () {
        Route::post('/posts', [PostController::class, 'store']);
    });

    Route::prefix('admins')
        ->middleware('can:admin')
        ->group(function () {

            Route::post('/', [AdminController::class, 'store']);
            Route::patch('/users/{id}/ban', [AdminController::class, 'banUser']);
        });


    Route::post('/posts/{id}/comments', [CommentController::class, 'store']);
    Route::delete('/posts/{post}/comments/{comment}', [CommentController::class, 'destroy']);
});
