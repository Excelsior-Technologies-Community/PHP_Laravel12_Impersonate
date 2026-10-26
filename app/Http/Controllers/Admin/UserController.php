<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ImpersonationLog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->search;

        $users = User::where('id', '!=', Auth::id())
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->paginate(10)
            ->withQueryString();

        return view('admin.users.index', compact(
            'users',
            'search'
        ));
    }

    public function impersonate(Request $request, $id)
    {
        $admin = Auth::user();
        $user = User::findOrFail($id);

        if (!$admin->canImpersonate()) {
            return back()->with('error', 'Only admin can impersonate.');
        }

        if (!$user->canBeImpersonated()) {
            return back()->with('error', 'Cannot impersonate another admin.');
        }

        // Prevent nested impersonation
        if (session()->has('impersonation_log_id')) {
            return back()->with('error', 'An impersonation session is already active.');
        }

        // Validate optional compliance parameters
        $accessMode = $request->input('access_mode', 'read_only');
        if (!in_array($accessMode, ['read_only', 'full_access'])) {
            $accessMode = 'read_only';
        }

        $durationMinutes = (int) $request->input('duration_minutes', 15);
        if ($durationMinutes < 1 || $durationMinutes > 120) {
            $durationMinutes = 15;
        }

        $reason = $request->input('reason') ?: 'Customer Support & Troubleshooting';
        $customNotes = $request->input('custom_notes');
        if ($customNotes) {
            $reason .= ' (' . trim($customNotes) . ')';
        }

        $ticketId = $request->input('ticket_id');

        /*
         * Create audit log with compliance parameters.
         */
        $log = ImpersonationLog::create([
            'admin_id' => $admin->id,
            'user_id' => $user->id,
            'ticket_id' => $ticketId,
            'reason' => $reason,
            'access_mode' => $accessMode,
            'duration_minutes' => $durationMinutes,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        /*
         * Store compliance parameters in session.
         */
        session([
            'impersonation_log_id' => $log->id,
            'impersonation_access_mode' => $accessMode,
            'impersonation_duration_minutes' => $durationMinutes,
            'impersonation_ticket_id' => $ticketId,
            'impersonation_reason' => $reason,
        ]);

        /*
         * Start impersonation.
         */
        $admin->impersonate($user);

        $modeLabel = $accessMode === 'read_only' ? 'Read-Only Safe Mode' : 'Full Write Access';

        return redirect()
            ->route('dashboard')
            ->with(
                'success',
                "Now impersonating {$user->name} in {$modeLabel} (Session limit: {$durationMinutes} mins)."
            );
    }

    public function leaveImpersonate()
    {
        $logId = session('impersonation_log_id');

        /*
         * Update updated_at when impersonation ends.
         */
        if ($logId) {
            $log = ImpersonationLog::find($logId);
            if ($log) {
                $log->touch();
            }
        }

        /*
         * Leave impersonation.
         */
        Auth::user()->leaveImpersonation();

        /*
         * Clear impersonation session data.
         */
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
                'success',
                'Impersonation session ended successfully.'
            );
    }
}
