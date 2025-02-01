<?php

namespace App\Http\Middleware;

use Closure;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Illuminate\Support\Facades\Auth;

class JwtAuthenticateRefresh
{
    public function handle(Request $request, Closure $next)
    {
        try {
            // Autentikasi user dari token yang ada
            JWTAuth::parseToken()->authenticate();

            $payload = JWTAuth::getPayload();
            $exp = $payload->get('exp');
            $now = now()->timestamp;
            //79.200 = 22 jam jika token masih 22 jam lagi maka refresh token
            if (($exp - $now) < 79.200) {
                $newToken = JWTAuth::refresh();
                JWTAuth::setToken($newToken);
                $user = JWTAuth::user();
                Auth::setUser($user);
                $request->headers->set('Authorization', 'Bearer ' . $newToken);
                $response = $next($request);
                return $response->header('Authorization', 'Bearer ' . $newToken);
            }

            return $next($request);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }
    }
}
