<?php

namespace App\Exceptions;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Illuminate\Http\JsonResponse;
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

        $exceptions->renderable(function (AuthenticationException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage() ?: __('api.errors.unauthenticated'),
                'data' => null,
            ], 401);
        });

        $exceptions->renderable(function (AuthorizationException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage() ?: __('api.errors.unauthorized'),
                'data' => null,
            ], 403);
        });

        $exceptions->renderable(function (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage() ?: __('api.errors.not_found'),
                'data' => null,
            ], 404);
        });

        $exceptions->renderable(function (HttpExceptionInterface $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage() ?: __('api.errors.http_error'),
                'data' => null,
            ], $e->getStatusCode());
        });

        $exceptions->renderable(function (Throwable $e) {
            report($e);

            return response()->json([
                'success' => false,
                'message' => $e->getMessage() ?: __('api.errors.internal_server_error'),
                'data' => null,
            ], 500);
        });
    }
}
