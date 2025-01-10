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
    public function handle(Request $request, Closure $next, $role)
    {
        if (!izinkanAkses($role)) {
            return response()->json(['success' => false, 'message' => 'akses ditolak'], 403);
        }
        return $next($request);
    }
}
