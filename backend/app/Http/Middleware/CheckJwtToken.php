<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Facades\JWTAuth;

class CheckJwtToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        try {
            // Check if the token exists in the Authorization header
            $token = JWTAuth::parseToken()->check();

            // If the token is missing or invalid, throw an exception
            if (!$token) {
                return response()->json(['error' => 'Token not provided or invalid'], 401);
            }

            // Optionally authenticate the user from the token
            $user = JWTAuth::parseToken()->authenticate();
        } catch (TokenExpiredException $e) {
            // Token has expired
            return response()->json(['error' => 'Token has expired'], 401);
        } catch (JWTException $e) {
            // Token is invalid or missing
            return response()->json(['error' => 'Token is invalid or missing'], 401);
        }

        // Proceed to the next middleware or request handler
        return $next($request);
    }
}
