<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PreventDemoWrites
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if user is authenticated and has demo_readonly flag
        // EXPLICIT BYPASS: admin@mioly.test is never restricted
        if (\Illuminate\Support\Facades\Schema::hasTable('users') && auth()->check()) {
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

        return $next($request);
    }
}
