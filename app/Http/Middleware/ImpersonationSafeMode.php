<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ImpersonationSafeMode
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if an impersonation session is active
        $isImpersonating = session()->has('impersonated_by') || session()->has('impersonation_log_id');

        if (!$isImpersonating) {
            return $next($request);
        }

        $accessMode = session('impersonation_access_mode', 'read_only');
        $routeName = $request->route() ? $request->route()->getName() : '';
        $path = $request->path();

        // 1. Strict Sensitive Route Guard (Blocked even in Full Access)
        $sensitiveRoutes = ['profile.destroy', 'password.update'];
        if (in_array($routeName, $sensitiveRoutes) || str_contains($path, 'password') && $request->isMethod('put')) {
            if ($request->expectsJson()) {
                return response()->json([
                    'error' => 'Security Alert: Modifying security credentials or deleting accounts is prohibited during impersonation.',
                ], 403);
            }

            return back()->with('error', 'Security Alert: Modifying security credentials or deleting accounts is prohibited during impersonation.');
        }

        // 2. Read-Only Safe Mode Action Blocker
        if ($accessMode === 'read_only') {
            $mutatingMethods = ['POST', 'PUT', 'PATCH', 'DELETE'];

            // Allow essential system actions like leaving impersonation or logging out
            $allowedRoutes = ['leave.impersonate', 'logout'];
            $isAllowedAction = in_array($routeName, $allowedRoutes) || str_contains($path, 'leave-impersonate');

            if (in_array($request->method(), $mutatingMethods) && !$isAllowedAction) {
                if ($request->expectsJson()) {
                    return response()->json([
                        'error' => 'Action Blocked: You are in Read-Only Safe Mode. Data modification is disabled to protect customer data.',
                    ], 403);
                }

                return back()->with('error', 'Action Blocked: You are in Read-Only Safe Mode. Data modification is disabled to protect customer data.');
            }
        }

        return $next($request);
    }
}
