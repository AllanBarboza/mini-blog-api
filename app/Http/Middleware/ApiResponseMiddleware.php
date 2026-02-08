<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ApiResponseMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        if (! $response instanceof JsonResponse) {
            return $response;
        }

        $status = $response->getStatusCode();

        if ($status >= 400) {
            return $response;
        }

        return response()->json([
            'success' => true,
            'data' => $response->getData(true),
            'message' => null,
        ], $status);
    }
}
