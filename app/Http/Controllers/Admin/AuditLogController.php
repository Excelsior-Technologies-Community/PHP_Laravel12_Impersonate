<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ImpersonationLog;
use Illuminate\Http\Request;

class AuditLogController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->search;

        $logs = ImpersonationLog::with(['admin', 'user'])
            ->when($search, function ($query) use ($search) {

                $query->whereHas('admin', function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                })
                ->orWhereHas('user', function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $stats = [
            'totalLogs' => ImpersonationLog::count(),

            'activeSessions' => ImpersonationLog::whereColumn(
                'created_at',
                'updated_at'
            )->count(),

            'completedSessions' => ImpersonationLog::whereColumn(
                'created_at',
                '<',
                'updated_at'
            )->count(),

            'todayLogs' => ImpersonationLog::whereDate(
                'created_at',
                today()
            )->count(),
        ];

        return view('admin.logs.index', compact(
            'logs',
            'search',
            'stats'
        ));
    }
}