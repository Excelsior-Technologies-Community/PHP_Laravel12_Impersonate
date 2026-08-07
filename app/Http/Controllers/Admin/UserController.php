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
            return back()->with(
                'error',
                'Only admin can impersonate.'
            );
        }

        if (!$user->canBeImpersonated()) {
            return back()->with(
                'error',
                'Cannot impersonate another admin.'
            );
        }

        /*
         * Prevent starting another impersonation
         * while already impersonating someone.
         */
        if (session()->has('impersonation_log_id')) {
            return back()->with(
                'error',
                'An impersonation session is already active.'
            );
        }

        /*
         * Create audit log.
         *
         * created_at = impersonation start time
         * updated_at = impersonation end time
         */
        $log = ImpersonationLog::create([
            'admin_id' => $admin->id,
            'user_id' => $user->id,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        /*
         * Store only the audit-log ID in the session.
         */
        session([
            'impersonation_log_id' => $log->id,
        ]);

        /*
         * Start impersonation.
         */
        $admin->impersonate($user);

        return redirect()
            ->route('dashboard')
            ->with(
                'success',
                "You are now impersonating {$user->name}"
            );
    }

    public function leaveImpersonate()
    {
        $logId = session('impersonation_log_id');

        /*
         * Update updated_at when impersonation ends.
         *
         * created_at = start
         * updated_at = end
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
        ]);

        return redirect()
            ->route('admin.users.index')
            ->with(
                'success',
                'Impersonation stopped successfully.'
            );
    }
}