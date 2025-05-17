<?php

namespace App\Http\Middleware;

use Closure;

class CheckTokenExpiration
{
    /**
     * Handles the incoming request and checks if the user's access token has expired.
     *
     * This middleware checks if the authenticated user has a current access token.
     * If the token exists, it verifies its expiration date. If the token has expired,
     * a JSON response is returned indicating that the token is expired, along with a
     * 401 status code. If the token is valid, the request is forwarded to the next
     * middleware or request handler.
     *
     * @param \Illuminate\Http\Request $request The incoming request to handle.
     * @param \Closure $next The next middleware or request handler to call.
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response The JSON response indicating result
     */
    public function handle($request, Closure $next)
    {
        if ($request->user()->currentAccessToken()) {
            $expiration = $request->user()->currentAccessToken()->expires_at;

            if ($expiration && $expiration < now()) {
                return response()->json(['message' => 'Token expirado'], 401);
            }
        }

        return $next($request);
    }
}
