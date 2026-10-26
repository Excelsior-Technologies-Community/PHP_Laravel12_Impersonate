<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h2 class="text-2xl font-bold text-gray-800 flex items-center gap-2">
                    <span>📋</span>
                    <span>Impersonation Audit & Compliance Ledger</span>
                </h2>
                <p class="text-sm text-gray-500 mt-1">
                    Immutable SOC2 / GDPR forensic audit records and session access tracking.
                </p>
            </div>

            <div class="flex items-center flex-wrap gap-2">
                <a href="{{ route('admin.audit.logs.export', request()->query()) }}"
                    class="inline-flex items-center gap-1.5 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-medium px-4 py-2 rounded-lg shadow-sm transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                    </svg>
                    Export CSV
                </a>

                <a href="{{ route('admin.users.index') }}"
                    class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-4 py-2 rounded-lg transition">
                    Manage Users
                </a>

                <a href="{{ route('dashboard') }}"
                    class="bg-gray-600 hover:bg-gray-700 text-white text-sm font-medium px-4 py-2 rounded-lg transition">
                    Dashboard
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Statistics Cards --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">
                        Total Audit Logs
                    </p>
                    <h3 class="text-2xl font-black text-indigo-600 mt-2">
                        {{ number_format($stats['totalLogs']) }}
                    </h3>
                </div>

                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">
                        Active Sessions
                    </p>
                    <h3 class="text-2xl font-black text-amber-500 mt-2">
                        {{ number_format($stats['activeSessions']) }}
                    </h3>
                </div>

                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">
                        Completed Sessions
                    </p>
                    <h3 class="text-2xl font-black text-emerald-600 mt-2">
                        {{ number_format($stats['completedSessions']) }}
                    </h3>
                </div>

                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">
                        Today's Activity
                    </p>
                    <h3 class="text-2xl font-black text-purple-600 mt-2">
                        {{ number_format($stats['todayLogs']) }}
                    </h3>
                </div>
            </div>

            {{-- Search & Filtering Toolbar --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
                <form method="GET" action="{{ route('admin.audit.logs') }}">
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-6 gap-3">
                        <div class="lg:col-span-2">
                            <label class="block text-xs font-semibold text-gray-500 uppercase mb-1">Search Keyword / Ticket</label>
                            <input
                                type="text"
                                name="search"
                                value="{{ request('search') }}"
                                placeholder="Admin, user, ticket # or reason..."
                                class="w-full text-sm rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-500 uppercase mb-1">Session Status</label>
                            <select
                                name="status"
                                class="w-full text-sm rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">All Statuses</option>
                                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active Now</option>
                                <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-500 uppercase mb-1">Security Mode</label>
                            <select
                                name="access_mode"
                                class="w-full text-sm rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">All Modes</option>
                                <option value="read_only" {{ request('access_mode') == 'read_only' ? 'selected' : '' }}>🛡️ Read-Only Safe Mode</option>
                                <option value="full_access" {{ request('access_mode') == 'full_access' ? 'selected' : '' }}>⚡ Full Access</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-500 uppercase mb-1">From Date</label>
                            <input
                                type="date"
                                name="start_date"
                                value="{{ request('start_date') }}"
                                class="w-full text-sm rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-500 uppercase mb-1">To Date</label>
                            <input
                                type="date"
                                name="end_date"
                                value="{{ request('end_date') }}"
                                class="w-full text-sm rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                        </div>
                    </div>

                    <div class="mt-4 flex justify-end gap-2">
                        <a href="{{ route('admin.audit.logs') }}"
                            class="px-4 py-2 text-sm text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-lg transition font-medium">
                            Clear Filters
                        </a>
                        <button type="submit"
                            class="px-5 py-2 text-sm text-white bg-indigo-600 hover:bg-indigo-700 rounded-lg transition font-medium shadow-sm">
                            Apply Filters
                        </button>
                    </div>
                </form>
            </div>

            {{-- Audit Log Table --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                    Session / Mode
                                </th>
                                <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                    Ticket & Justification
                                </th>
                                <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                    Admin (Operator)
                                </th>
                                <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                    Target User
                                </th>
                                <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                    Timing & Duration
                                </th>
                                <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                    Status
                                </th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-gray-200 bg-white text-sm">
                            @forelse($logs as $log)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-5 py-4 whitespace-nowrap">
                                    <div class="font-bold text-gray-900">#{{ $log->id }}</div>
                                    <div class="mt-1">
                                        @if(($log->access_mode ?? 'read_only') === 'read_only')
                                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800 border border-emerald-200">
                                                <span>🛡️</span> Read-Only
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-medium bg-amber-100 text-amber-800 border border-amber-200">
                                                <span>⚡</span> Full Access
                                            </span>
                                        @endif
                                    </div>
                                    <div class="text-xs text-gray-400 mt-1 font-mono">
                                        IP: {{ $log->ip_address ?? '127.0.0.1' }}
                                    </div>
                                </td>

                                <td class="px-5 py-4">
                                    @if($log->ticket_id)
                                        <div class="inline-flex items-center font-mono text-xs font-bold text-indigo-700 bg-indigo-50 border border-indigo-200 px-2 py-0.5 rounded mb-1">
                                            🎫 {{ $log->ticket_id }}
                                        </div>
                                    @endif
                                    <div class="font-medium text-gray-800 text-xs">
                                        {{ $log->reason ?: 'Customer Support Troubleshooting' }}
                                    </div>
                                    <div class="text-xs text-gray-400 mt-1">
                                        Max Allowed: {{ $log->duration_minutes ?? 15 }} mins
                                    </div>
                                </td>

                                <td class="px-5 py-4 whitespace-nowrap">
                                    <div class="font-semibold text-gray-800">
                                        {{ $log->admin?->name ?? 'System Admin' }}
                                    </div>
                                    <div class="text-xs text-gray-500">
                                        {{ $log->admin?->email ?? 'admin@example.com' }}
                                    </div>
                                </td>

                                <td class="px-5 py-4 whitespace-nowrap">
                                    <div class="font-semibold text-gray-800">
                                        {{ $log->user?->name ?? 'Deleted User' }}
                                    </div>
                                    <div class="text-xs text-gray-500">
                                        {{ $log->user?->email ?? 'N/A' }}
                                    </div>
                                </td>

                                <td class="px-5 py-4 whitespace-nowrap">
                                    <div class="text-xs text-gray-700 font-medium">
                                        {{ $log->created_at->format('d M Y, h:i A') }}
                                    </div>
                                    <div class="text-xs text-gray-500 mt-0.5">
                                        Duration: <span class="font-semibold text-gray-800">{{ $log->duration }}</span>
                                    </div>
                                </td>

                                <td class="px-5 py-4 whitespace-nowrap">
                                    @if($log->created_at->equalTo($log->updated_at))
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-amber-100 text-amber-700">
                                            <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span>
                                            Active Session
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-700">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                            Completed
                                        </span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center py-12 text-gray-500">
                                    <div class="flex flex-col items-center">
                                        <span class="text-5xl mb-3">📂</span>
                                        <h3 class="text-base font-semibold text-gray-700">No Audit Logs Found</h3>
                                        <p class="text-xs text-gray-400 mt-1 max-w-sm text-center">
                                            No impersonation sessions match your current filter criteria or have been recorded yet.
                                        </p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                @if($logs->hasPages())
                    <div class="p-4 border-t border-gray-100 bg-gray-50">
                        {{ $logs->links() }}
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
