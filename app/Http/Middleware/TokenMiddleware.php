<?php

namespace App\Http\Middleware;

use App\Helpers\blogHelper;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TokenMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check for the token in the request headers
        $token = $request->header('Token');

        if ($token !== 'CourierPlus@321') {
            return blogHelper::errorResponse('Unathorized', '', 401);
        }

        return $next($request);
    }
}
