<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckImpersonation
{
    public function handle(Request $request, Closure $next): Response
    {
        if (session()->has('impersonated_by')) {
            view()->share('impersonating', true);
        }
        
        return $next($request);
    }
}