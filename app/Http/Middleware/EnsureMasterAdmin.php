<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureMasterAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ?string $permission = null): Response
    {
        $user = $request->user();

        if (!$user || !$user->is_master) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Unauthorized Master Access'], 403);
            }
            return redirect()->route('dashboard')->with('error', 'Bu alana erişim yetkiniz bulunmamaktadır.');
        }

        // If a specific permission is required, check it
        if ($permission && !$user->hasMasterPermission($permission)) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Permission Denied'], 403);
            }
            return back()->with('error', 'Bu işlemi gerçekleştirmek için yetkiniz bulunmamaktadır.');
        }

        return $next($request);
    }
}
