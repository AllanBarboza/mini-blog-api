<?php

namespace App\Http\Middleware;

use App\Exceptions\ConflictException;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RejectBannedUser
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if ($user && $user->banned_at !== null) {
            throw new ConflictException('User already banned.');
        }

        return $next($request);
    }
}
