<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class AdminAuthorization
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if(!Auth::guard('api')->user())
        {
            return response()->json(['message' => "Invalid token"], 401);
         } else if (Auth::guard('api')->user() && Auth::guard('api')->user()->role == 'admin') {
            return $next($request);
        } else {
            return response()->json(['message' => "This access for admin"], 401);
        }
    }
}