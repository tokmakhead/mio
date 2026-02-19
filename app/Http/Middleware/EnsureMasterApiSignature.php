<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureMasterApiSignature
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // For development/simulation, we'll check for a 'X-Master-Key' header
        // In production, this would be a HMAC signature.
        $masterKey = config('app.master_api_key', 'mionex_master_secret_2026');

        if ($request->header('X-Master-Key') !== $masterKey) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized API Access',
                'code' => 'UNAUTHORIZED_API'
            ], 401);
        }

        return $next($request);
    }
}
