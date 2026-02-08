<?php

namespace App\Exceptions;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Throwable;

class ExceptionRegister
{
    public static function register($exceptions): void
    {
        $exceptions->renderable(function (ValidationException $e) {
            return response()->json([
                'message' => $e->getMessage() ?: __('api.errors.validation_failed'),
                'errors'  => $e->errors(),
            ], 422);
        });

        $exceptions->renderable(function (InvalidCredentialsException $e) {
            return response()->json([
                'message' => $e->getMessage() ?: __('auth.invalid_credentials'),
            ], 401);
        });

        $exceptions->renderable(function (UserBannedException $e) {
            return response()->json([
                'message' => $e->getMessage() ?: __('auth.user_banned'),
            ], 403);
        });

        $exceptions->renderable(function (AuthenticationException $e) {
            return response()->json([
                'message' => $e->getMessage() ?: __('api.errors.unauthenticated'),
            ], 401);
        });

        $exceptions->renderable(function (AuthorizationException $e) {
            return response()->json([
                'message' => $e->getMessage() ?: __('api.errors.unauthorized'),
            ], 403);
        });

        $exceptions->renderable(function (ModelNotFoundException $e) {
            return response()->json([
                'message' => $e->getMessage() ?: __('api.errors.not_found'),
            ], 404);
        });

        $exceptions->renderable(function (HttpExceptionInterface $e) {
            return response()->json([
                'message' => $e->getMessage() ?: __('api.errors.http_error'),
            ], $e->getStatusCode());
        });

        $exceptions->renderable(function (Throwable $e) {
            report($e);

            return response()->json([
                'message' => $e->getMessage() ?: __('api.errors.internal_server_error'),
            ], 500);
        });
    }
}
