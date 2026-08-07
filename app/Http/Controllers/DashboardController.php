<?php

namespace App\Http\Controllers;

use App\Models\ImpersonationLog;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if ($user->is_admin) {

            $stats = [

                'totalUsers' => \App\Models\User::where(
                    'is_admin',
                    false
                )->count(),

                'totalLogs' => ImpersonationLog::count(),

                'activeSessions' => ImpersonationLog::whereColumn(
                    'created_at',
                    'updated_at'
                )->count(),

                'todayLogs' => ImpersonationLog::whereDate(
                    'created_at',
                    today()
                )->count(),

            ];

            return view(
                'admin.dashboard',
                compact('stats')
            );
        }

        return view(
            'dashboard',
            compact('user')
        );
    }
}