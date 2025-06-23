<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        // Cek apakah user adalah admin (role_id = 1)
        if (!auth()->check() || auth()->user()->id_roles !== 1) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Unauthorized'], 403);
            }

            return redirect()->route('home')->with('error', 'Akses ditolak. Anda tidak memiliki izin sebagai admin.');
        }

        return $next($request);
    }
}
