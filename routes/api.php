<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\User\UserController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\User\UserAuthController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/users', [UserController::class, 'store']);
Route::post('/login', [UserAuthController::class, 'login'])
    ->middleware('banned.user');

Route::post('/admins', [AdminController::class, 'store'])
    ->middleware('auth:sanctum', 'can:create-admin');

Route::post('/admins/login', [AdminAuthController::class, 'login']);
