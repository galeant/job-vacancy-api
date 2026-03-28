<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class NullAbleTokenMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->bearerToken() && Auth::guard('sanctum')->check()) {
            $user = Auth::guard('company-api')->user();

            if (Auth::guard('company-api')->user()) {
                Auth::shouldUse('auth:company-api');
            }

            if (Auth::guard('applicant-api')->user()) {
                Auth::shouldUse('auth:applicant-api');
            }
        }

        return $next($request);
    }
}
