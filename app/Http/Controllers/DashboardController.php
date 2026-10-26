<?php

namespace App\Http\Controllers;

use App\Models\ImpersonationLog;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if ($user->is_admin) {
            $stats = [
                'totalUsers' => User::where('is_admin', false)->count(),
                'totalLogs' => ImpersonationLog::count(),
                'activeSessions' => ImpersonationLog::whereColumn('created_at', 'updated_at')->count(),
                'todayLogs' => ImpersonationLog::whereDate('created_at', today())->count(),
                'readOnlyCount' => ImpersonationLog::where('access_mode', 'read_only')->count(),
                'fullAccessCount' => ImpersonationLog::where('access_mode', 'full_access')->count(),
            ];

            // Chart 1: Daily Impersonation Timeline (Last 30 days)
            $timelineChart = ImpersonationLog::selectRaw("DATE(created_at) as day, COUNT(*) as total")
                ->groupBy('day')
                ->orderBy('day', 'asc')
                ->take(30)
                ->get();

            // Chart 2: Justification Reason Breakdown
            $reasonChart = ImpersonationLog::select('reason', DB::raw('COUNT(*) as total'))
                ->whereNotNull('reason')
                ->groupBy('reason')
                ->orderByDesc('total')
                ->take(6)
                ->get();

            // Chart 3: Top Impersonated Users
            $topUsers = ImpersonationLog::select('user_id', DB::raw('COUNT(*) as total'))
                ->groupBy('user_id')
                ->orderByDesc('total')
                ->take(5)
                ->with('user')
                ->get();

            // Chart 4: Top Support Admins
            $topAdmins = ImpersonationLog::select('admin_id', DB::raw('COUNT(*) as total'))
                ->groupBy('admin_id')
                ->orderByDesc('total')
                ->take(5)
                ->with('admin')
                ->get();

            // Recent Sessions
            $recentSessions = ImpersonationLog::with(['admin', 'user'])
                ->latest('id')
                ->take(6)
                ->get();

            return view('admin.dashboard', compact(
                'stats',
                'timelineChart',
                'reasonChart',
                'topUsers',
                'topAdmins',
                'recentSessions'
            ));
        }

        return view('dashboard', compact('user'));
    }
}
