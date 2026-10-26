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
                // Determine target timeout in seconds (custom duration per session)
                $durationMinutes = $log->duration_minutes ?: session('impersonation_duration_minutes', 15);
                $allowedSeconds = $durationMinutes * 60;

                // Elapsed time since start
                $elapsedSeconds = $log->created_at->diffInSeconds(now());
                $remainingSeconds = max(0, $allowedSeconds - $elapsedSeconds);

                view()->share('impersonationRemainingSeconds', $remainingSeconds);
                view()->share('impersonationDurationMinutes', $durationMinutes);
                view()->share('impersonationAccessMode', $log->access_mode ?: session('impersonation_access_mode', 'read_only'));

                if ($elapsedSeconds >= $allowedSeconds) {
                    // Update updated_at as end time
                    $log->touch();

                    // Terminate impersonation
                    Auth::user()->leaveImpersonation();

                    // Clear session data
                    session()->forget([
                        'impersonation_log_id',
                        'impersonation_access_mode',
                        'impersonation_duration_minutes',
                        'impersonation_ticket_id',
                        'impersonation_reason',
                    ]);

                    return redirect()
                        ->route('admin.users.index')
                        ->with(
                            'error',
                            "Impersonation session automatically expired after {$durationMinutes} minutes."
                        );
                }
            }
        }

        return $next($request);
    }
}
