<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ImpersonationLog;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class AuditLogController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->search;
        $status = $request->status;
        $accessMode = $request->access_mode;
        $startDate = $request->start_date;
        $endDate = $request->end_date;

        $logs = ImpersonationLog::with(['admin', 'user'])
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->whereHas('admin', function ($adminQ) use ($search) {
                        $adminQ->where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                    })
                    ->orWhereHas('user', function ($userQ) use ($search) {
                        $userQ->where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                    })
                    ->orWhere('ticket_id', 'like', "%{$search}%")
                    ->orWhere('reason', 'like', "%{$search}%");
                });
            })
            ->when($status === 'active', function ($query) {
                $query->whereColumn('created_at', 'updated_at');
            })
            ->when($status === 'completed', function ($query) {
                $query->whereColumn('created_at', '<', 'updated_at');
            })
            ->when($accessMode, function ($query) use ($accessMode) {
                $query->where('access_mode', $accessMode);
            })
            ->when($startDate, function ($query) use ($startDate) {
                $query->whereDate('created_at', '>=', $startDate);
            })
            ->when($endDate, function ($query) use ($endDate) {
                $query->whereDate('created_at', '<=', $endDate);
            })
            ->latest('id')
            ->paginate(10)
            ->withQueryString();

        $stats = [
            'totalLogs' => ImpersonationLog::count(),
            'activeSessions' => ImpersonationLog::whereColumn('created_at', 'updated_at')->count(),
            'completedSessions' => ImpersonationLog::whereColumn('created_at', '<', 'updated_at')->count(),
            'todayLogs' => ImpersonationLog::whereDate('created_at', today())->count(),
        ];

        return view('admin.logs.index', compact(
            'logs',
            'search',
            'status',
            'accessMode',
            'startDate',
            'endDate',
            'stats'
        ));
    }

    public function exportCsv(Request $request)
    {
        $search = $request->search;
        $status = $request->status;
        $accessMode = $request->access_mode;
        $startDate = $request->start_date;
        $endDate = $request->end_date;

        $fileName = 'impersonation_audit_logs_' . date('Y_m_d_His') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
        ];

        $callback = function () use ($search, $status, $accessMode, $startDate, $endDate) {
            $file = fopen('php://output', 'w');

            // CSV Column Headers
            fputcsv($file, [
                'Log ID',
                'Started At',
                'Ended At',
                'Duration',
                'Admin Name',
                'Admin Email',
                'Target User Name',
                'Target User Email',
                'Access Mode',
                'Ticket Reference',
                'Justification Reason',
                'IP Address',
                'Status',
            ]);

            $query = ImpersonationLog::with(['admin', 'user'])
                ->when($search, function ($query) use ($search) {
                    $query->where(function ($q) use ($search) {
                        $q->whereHas('admin', function ($adminQ) use ($search) {
                            $adminQ->where('name', 'like', "%{$search}%")
                                ->orWhere('email', 'like', "%{$search}%");
                        })
                        ->orWhereHas('user', function ($userQ) use ($search) {
                            $userQ->where('name', 'like', "%{$search}%")
                                ->orWhere('email', 'like', "%{$search}%");
                        })
                        ->orWhere('ticket_id', 'like', "%{$search}%")
                        ->orWhere('reason', 'like', "%{$search}%");
                    });
                })
                ->when($status === 'active', function ($query) {
                    $query->whereColumn('created_at', 'updated_at');
                })
                ->when($status === 'completed', function ($query) {
                    $query->whereColumn('created_at', '<', 'updated_at');
                })
                ->when($accessMode, function ($query) use ($accessMode) {
                    $query->where('access_mode', $accessMode);
                })
                ->when($startDate, function ($query) use ($startDate) {
                    $query->whereDate('created_at', '>=', $startDate);
                })
                ->when($endDate, function ($query) use ($endDate) {
                    $query->whereDate('created_at', '<=', $endDate);
                })
                ->latest('id');

            $query->chunk(200, function ($logs) use ($file) {
                foreach ($logs as $log) {
                    $isActive = ($log->created_at == $log->updated_at);
                    fputcsv($file, [
                        $log->id,
                        $log->created_at->format('Y-m-d H:i:s'),
                        $isActive ? 'Active Now' : $log->updated_at->format('Y-m-d H:i:s'),
                        $log->duration,
                        $log->admin ? $log->admin->name : 'N/A',
                        $log->admin ? $log->admin->email : 'N/A',
                        $log->user ? $log->user->name : 'N/A',
                        $log->user ? $log->user->email : 'N/A',
                        $log->access_mode === 'read_only' ? 'Read-Only Safe Mode' : 'Full Access',
                        $log->ticket_id ?: 'N/A',
                        $log->reason ?: 'Customer Support',
                        $log->ip_address ?: 'N/A',
                        $isActive ? 'Active' : 'Completed',
                    ]);
                }
            });

            fclose($file);
        };

        return new StreamedResponse($callback, 200, $headers);
    }
}
