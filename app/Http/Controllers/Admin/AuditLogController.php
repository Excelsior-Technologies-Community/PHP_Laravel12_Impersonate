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
        $status = $request->status;
        $startDate = $request->start_date;
        $endDate = $request->end_date;

        $logs = ImpersonationLog::with(['admin', 'user'])

            ->when($search, function ($query) use ($search) {

                $query->where(function ($query) use ($search) {

                    $query->whereHas('admin', function ($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                    })

                        ->orWhereHas('user', function ($q) use ($search) {
                            $q->where('name', 'like', "%{$search}%")
                                ->orWhere('email', 'like', "%{$search}%");
                        });
                });
            })

            ->when($status == 'active', function ($query) {
                $query->whereColumn('created_at', 'updated_at');
            })

            ->when($status == 'completed', function ($query) {
                $query->whereColumn('created_at', '<', 'updated_at');
            })

            ->when($startDate, function ($query) use ($startDate) {
                $query->whereDate('created_at', '>=', $startDate);
            })

            ->when($endDate, function ($query) use ($endDate) {
                $query->whereDate('created_at', '<=', $endDate);
            })

            ->oldest()
            ->paginate(5)
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
            'status',
            'startDate',
            'endDate',
            'stats'
        ));
    }
}
