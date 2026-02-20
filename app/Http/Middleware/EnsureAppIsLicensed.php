<?php

namespace App\Http\Middleware;

use App\Models\License;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Log;

class EnsureAppIsLicensed
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        try {
            // Exclude license routes, assets, and install routes
            if (
                app()->environment('local') ||
                $request->routeIs('license.*') ||
                $request->routeIs('install.*') ||
                $request->is('storage/*') ||
                $request->is('build/*') ||
                $request->is('up') ||
                $request->is('ping')
            ) {
                return $next($request);
            }

            if (!Schema::hasTable('licenses')) {
                return $next($request);
            }

            // Check verification (simple check for now)
            $license = License::first();

            if (!$license || !$license->isActive()) {
                return redirect()->route('license.show');
            }
        } catch (\Exception $e) {
            Log::error('License middleware error: ' . $e->getMessage());
            // Fallback: allow request to proceed if DB/License check fails during 503-like crash
            return $next($request);
        }

        return $next($request);
    }
}
