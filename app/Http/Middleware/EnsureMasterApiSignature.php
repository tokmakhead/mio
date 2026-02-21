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
        $signature = $request->header('X-Mio-Signature');
        $timestamp = $request->header('X-Mio-Timestamp');
        $nonce = $request->header('X-Mio-Nonce');

        if (!$signature || !$timestamp || !$nonce) {
            return response()->json([
                'status' => 'error',
                'message' => 'Missing Security Headers',
                'code' => 'MISSING_HEADERS'
            ], 401);
        }

        // Check timestamp (allow 5 min drift)
        if (abs(time() - (int) $timestamp) > 300) {
            return response()->json([
                'status' => 'error',
                'message' => 'Request Expired',
                'code' => 'EXPIRED_REQUEST'
            ], 401);
        }

        $masterSecret = config('app.master_api_key', 'mionex_master_secret_2026');

        // signature = hash_hmac('sha256', timestamp . nonce . payload, secret)
        $payload = $request->getContent();
        $expectedSignature = hash_hmac('sha256', $timestamp . $nonce . $payload, $masterSecret);

        if (!hash_equals($expectedSignature, $signature)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid Security Signature',
                'code' => 'INVALID_SIGNATURE'
            ], 401);
        }

        return $next($request);
    }
}
