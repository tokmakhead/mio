<?php

namespace App\Http\Middleware;

use App\Models\License;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureAppIsLicensed
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Exclude license routes, assets, and install routes
        if (
            $request->routeIs('license.*') ||
            $request->routeIs('install.*') ||
            $request->is('storage/*') ||
            $request->is('build/*') ||
            $request->is('up')
        ) {
            return $next($request);
        }

        if (!\Illuminate\Support\Facades\Schema::hasTable('licenses')) {
            return $next($request);
        }

        // Check verification (simple check for now)
        $license = License::first();

        if (!$license || !$license->isActive()) {
            return redirect()->route('license.show');
        }

        return $next($request);
    }
}
