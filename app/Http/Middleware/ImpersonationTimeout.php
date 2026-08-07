<?php

namespace App\Http\Middleware;

use App\Models\ImpersonationLog;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class ImpersonationTimeout
{
    public function handle(Request $request, Closure $next): Response
    {
        $logId = session('impersonation_log_id');

        if ($logId) {

            $log = ImpersonationLog::find($logId);

            if ($log) {

                // created_at is the impersonation start time
                $elapsedSeconds = $log->created_at->diffInSeconds(now());

                // Testing timeout: 2 minutes
                if ($elapsedSeconds >= 120) {

                    /*
                     * updated_at becomes the impersonation end time.
                     */
                    $log->touch();

                    /*
                     * Leave impersonation.
                     */
                    Auth::user()->leaveImpersonation();

                    /*
                     * Clear impersonation session.
                     */
                    session()->forget([
                        'impersonation_log_id',
                    ]);

                    return redirect()
                        ->route('admin.users.index')
                        ->with(
                            'error',
                            'Impersonation automatically ended after 2 minutes.'
                        );
                }
            }
        }

        return $next($request);
    }
}