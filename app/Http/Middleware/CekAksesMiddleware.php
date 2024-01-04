<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CekAksesMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle($request, $next, ...$abilities)
    {
        if (!$request->user() || !$request->user()->currentAccessToken()) {
            throw new AuthenticationException;
        }
        $roles = cekAkses($request->user()->id);
        $request->merge(array("roles_akun" => $roles));
        foreach ($abilities as $ability) {
            if (in_array($ability, $roles)) {
                return $next($request);
            }
        }
        return response()->json([
            'error' => 'Akses ditolak',
        ], 401);
    }
}
