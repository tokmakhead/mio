<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Log;

class PreventDemoWrites
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        try {
            // Check if user is authenticated and has demo_readonly flag
            // EXPLICIT BYPASS: admin@mioly.test is never restricted
            if (Schema::hasTable('users') && auth()->check()) {
                $user = auth()->user();
                if ($user->email === 'admin@mioly.test' || $user->email === 'admin@mionex.com') {
                    return $next($request);
                }

                if ($user->demo_readonly) {
                    // Allow logout
                    if ($request->routeIs('logout')) {
                        return $next($request);
                    }

                    // Block write operations (POST, PUT, PATCH, DELETE)
                    if (in_array($request->method(), ['POST', 'PUT', 'PATCH', 'DELETE'])) {
                        return redirect()->back()->with('error', 'Demo kullanıcılar değişiklik yapamaz. Sadece görüntüleme yetkisine sahipsiniz.');
                    }
                }
            }
        } catch (\Exception $e) {
            Log::error('PreventDemoWrites middleware error: ' . $e->getMessage());
            // Fallback: allow request to proceed if DB check fails
            return $next($request);
        }

        return $next($request);
    }
}
