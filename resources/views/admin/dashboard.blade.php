<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h2 class="text-2xl font-bold text-gray-800">
                    📊 Security Intelligence & Impersonation Studio
                </h2>
                <p class="text-xs text-gray-500 mt-1">Real-time session monitoring, justification analytics, and access guardrails.</p>
            </div>

            <div class="flex gap-2">
                <a href="{{ route('admin.users.index') }}"
                   class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg text-sm font-semibold transition shadow-sm">
                    👥 Manage Users
                </a>

                <a href="{{ route('admin.audit.logs') }}"
                   class="bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2 rounded-lg text-sm font-semibold transition shadow-sm">
                    📋 Audit Logs
                </a>

                <a href="{{ route('admin.audit.logs.export') }}"
                   class="bg-gray-800 hover:bg-gray-900 text-white px-4 py-2 rounded-lg text-sm font-semibold transition shadow-sm">
                    📥 Export CSV
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- Welcome Hero Banner -->
            <div class="bg-gradient-to-r from-indigo-700 via-indigo-600 to-blue-600 rounded-2xl shadow-lg p-6 sm:p-8 text-white relative overflow-hidden">
                <div class="relative z-10 max-w-2xl">
                    <span class="bg-white bg-opacity-20 text-xs px-3 py-1 rounded-full font-semibold uppercase tracking-wider">
                        Security Overview
                    </span>
                    <h1 class="text-2xl sm:text-3xl font-extrabold mt-3">
                        Welcome back, {{ Auth::user()->name }}
                    </h1>
                    <p class="mt-2 text-indigo-100 text-sm leading-relaxed">
                        Monitor active user impersonations, enforce Read-Only Safe Mode protections, and analyze justification tickets for SOC2 and GDPR compliance.
                    </p>
                </div>
                <div class="absolute right-6 -bottom-6 text-9xl opacity-10 select-none pointer-events-none">
                    🛡️
                </div>
            </div>

            <!-- KPI Metric Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-500 text-xs font-bold uppercase tracking-wider">Total Users</p>
                            <h2 class="text-3xl font-extrabold mt-1 text-gray-900">{{ $stats['totalUsers'] }}</h2>
                        </div>
                        <div class="w-12 h-12 bg-indigo-50 text-indigo-600 rounded-xl flex items-center justify-center text-xl font-bold">
                            👥
                        </div>
                    </div>
                    <div class="mt-3 text-xs text-gray-500">Registered non-admin accounts</div>
                </div>

                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-500 text-xs font-bold uppercase tracking-wider">Total Audit Logs</p>
                            <h2 class="text-3xl font-extrabold mt-1 text-emerald-600">{{ $stats['totalLogs'] }}</h2>
                        </div>
                        <div class="w-12 h-12 bg-emerald-50 text-emerald-600 rounded-xl flex items-center justify-center text-xl font-bold">
                            📋
                        </div>
                    </div>
                    <div class="mt-3 text-xs text-gray-500">Forensic sessions recorded</div>
                </div>

                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-500 text-xs font-bold uppercase tracking-wider">Active Sessions</p>
                            <h2 class="text-3xl font-extrabold mt-1 text-amber-500">{{ $stats['activeSessions'] }}</h2>
                        </div>
                        <div class="w-12 h-12 bg-amber-50 text-amber-500 rounded-xl flex items-center justify-center text-xl font-bold">
                            ⚡
                        </div>
                    </div>
                    <div class="mt-3 text-xs text-gray-500">Live active sessions right now</div>
                </div>

                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-500 text-xs font-bold uppercase tracking-wider">Safe Mode Enforced</p>
                            <h2 class="text-3xl font-extrabold mt-1 text-blue-600">{{ $stats['readOnlyCount'] }}</h2>
                        </div>
                        <div class="w-12 h-12 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center text-xl font-bold">
                            🛡️
                        </div>
                    </div>
                    <div class="mt-3 text-xs text-gray-500">Protected in Read-Only Mode</div>
                </div>
            </div>

            <!-- Chart.js Section (Feature 6) -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Chart 1: Activity Volume Timeline -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 lg:col-span-2 flex flex-col justify-between">
                    <div class="flex justify-between items-center mb-4">
                        <div>
                            <h3 class="font-bold text-gray-800 text-base">📈 Impersonation Activity Timeline</h3>
                            <p class="text-xs text-gray-400">Daily frequency of impersonation access over time</p>
                        </div>
                        <span class="bg-indigo-50 text-indigo-700 text-xs px-2.5 py-1 rounded-full font-semibold">
                            30 Days
                        </span>
                    </div>
                    <div class="h-64">
                        <canvas id="timelineChart"></canvas>
                    </div>
                </div>

                <!-- Chart 2: Justification Reason Breakdown -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 flex flex-col justify-between">
                    <div class="mb-4">
                        <h3 class="font-bold text-gray-800 text-base">🍩 Justification Distribution</h3>
                        <p class="text-xs text-gray-400">Breakdown of support justifications</p>
                    </div>
                    <div class="h-64 flex items-center justify-center">
                        <canvas id="reasonDonutChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- Second Row: Leaderboards & Quick Access -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Top Impersonated Accounts -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="font-bold text-gray-800 text-base">🎯 Top Impersonated Accounts</h3>
                        <span class="text-xs text-gray-400">High Churn / Support Inquiries</span>
                    </div>
                    <div class="h-56">
                        <canvas id="topUsersChart"></canvas>
                    </div>
                </div>

                <!-- Top Support Admins -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="font-bold text-gray-800 text-base">🛡️ Top Support Administrators</h3>
                        <span class="text-xs text-gray-400">Total sessions initiated</span>
                    </div>
                    <div class="h-56">
                        <canvas id="topAdminsChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- Recent Sessions Ledger Table -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-6 border-b border-gray-100 flex justify-between items-center">
                    <div>
                        <h3 class="font-bold text-gray-800 text-base">📋 Recent Audit Log Activity</h3>
                        <p class="text-xs text-gray-400">Latest sessions with justification notes and ticket references</p>
                    </div>
                    <a href="{{ route('admin.audit.logs') }}" class="text-xs text-indigo-600 font-bold hover:text-indigo-800 transition">
                        View Complete Audit Trail &rarr;
                    </a>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50 text-xs font-semibold text-gray-500 uppercase">
                            <tr>
                                <th class="px-6 py-3 text-left">Timestamp</th>
                                <th class="px-6 py-3 text-left">Admin & Target User</th>
                                <th class="px-6 py-3 text-left">Security Mode</th>
                                <th class="px-6 py-3 text-left">Ticket Ref</th>
                                <th class="px-6 py-3 text-left">Reason</th>
                                <th class="px-6 py-3 text-right">Duration</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 text-sm">
                            @forelse($recentSessions as $session)
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="px-6 py-3 text-xs text-gray-500 font-mono">
                                        {{ $session->created_at->format('d M Y, H:i') }}
                                    </td>
                                    <td class="px-6 py-3">
                                        <div class="text-xs font-bold text-gray-800">
                                            {{ $session->admin ? $session->admin->name : 'System' }}
                                            <span class="text-gray-400 font-normal">impersonated</span>
                                            {{ $session->user ? $session->user->name : 'N/A' }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-3">
                                        @if($session->access_mode === 'read_only')
                                            <span class="px-2 py-0.5 rounded-full text-[11px] font-bold bg-indigo-100 text-indigo-800">
                                                🛡️ Read-Only
                                            </span>
                                        @else
                                            <span class="px-2 py-0.5 rounded-full text-[11px] font-bold bg-amber-100 text-amber-800">
                                                ⚠️ Full Write
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-3 text-xs font-mono text-gray-600">
                                        {{ $session->ticket_id ?: '-' }}
                                    </td>
                                    <td class="px-6 py-3 text-xs text-gray-600 max-w-xs truncate" title="{{ $session->reason }}">
                                        {{ $session->reason ?: 'Customer Support' }}
                                    </td>
                                    <td class="px-6 py-3 text-xs text-gray-500 text-right font-medium">
                                        {{ $session->duration }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-6 text-center text-gray-400 text-sm">
                                        No recent impersonation sessions recorded.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>

    <!-- Chart.js Scripts (Feature 6) -->
    <script>
        (function() {
            function initCharts() {
                if (typeof Chart === 'undefined') {
                    console.warn('Chart.js is still loading, retrying...');
                    setTimeout(initCharts, 100);
                    return;
                }

                // 1. Timeline Chart
                try {
                    const timelineData = @json($timelineChart);
                    const lineLabels = (timelineData || []).map(item => item.day);
                    const lineValues = (timelineData || []).map(item => item.total);

                    const ctxLine = document.getElementById('timelineChart');
                    if (ctxLine) {
                        new Chart(ctxLine, {
                            type: 'line',
                            data: {
                                labels: lineLabels.length ? lineLabels : ['No Sessions Recorded'],
                                datasets: [{
                                    label: 'Sessions',
                                    data: lineValues.length ? lineValues : [0],
                                    borderColor: '#4f46e5',
                                    backgroundColor: 'rgba(79, 70, 229, 0.08)',
                                    borderWidth: 2.5,
                                    fill: true,
                                    tension: 0.35,
                                    pointBackgroundColor: '#4f46e5',
                                    pointRadius: 4,
                                    pointHoverRadius: 6
                                }]
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                plugins: { legend: { display: false } },
                                scales: {
                                    y: {
                                        beginAtZero: true,
                                        ticks: { precision: 0 }
                                    }
                                }
                            }
                        });
                    }
                } catch (e) {
                    console.error('Error initializing Timeline Chart:', e);
                }

                // 2. Justification Donut Chart
                try {
                    const reasonData = @json($reasonChart);
                    const donutLabels = (reasonData || []).map(item => item && item.reason ? String(item.reason).split('(')[0].trim() : 'General Support');
                    const donutValues = (reasonData || []).map(item => item ? item.total : 0);

                    const ctxDonut = document.getElementById('reasonDonutChart');
                    if (ctxDonut) {
                        new Chart(ctxDonut, {
                            type: 'doughnut',
                            data: {
                                labels: donutLabels.length ? donutLabels : ['Standard Support'],
                                datasets: [{
                                    data: donutValues.length ? donutValues : [1],
                                    backgroundColor: ['#4f46e5', '#10b981', '#f59e0b', '#06b6d4', '#ec4899', '#8b5cf6'],
                                    borderWidth: 2,
                                    hoverOffset: 6
                                }]
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                plugins: {
                                    legend: { position: 'bottom', labels: { boxWidth: 12, font: { size: 11 } } }
                                }
                            }
                        });
                    }
                } catch (e) {
                    console.error('Error initializing Donut Chart:', e);
                }

                // 3. Top Users Bar Chart
                try {
                    const topUserData = @json($topUsers);
                    const userLabels = (topUserData || []).map(item => item && item.user ? item.user.name : `User #${item.user_id}`);
                    const userValues = (topUserData || []).map(item => item ? item.total : 0);

                    const ctxUser = document.getElementById('topUsersChart');
                    if (ctxUser) {
                        new Chart(ctxUser, {
                            type: 'bar',
                            data: {
                                labels: userLabels.length ? userLabels : ['No Data'],
                                datasets: [{
                                    label: 'Sessions',
                                    data: userValues.length ? userValues : [0],
                                    backgroundColor: '#3b82f6',
                                    borderRadius: 6
                                }]
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                plugins: { legend: { display: false } },
                                scales: {
                                    y: { beginAtZero: true, ticks: { precision: 0 } }
                                }
                            }
                        });
                    }
                } catch (e) {
                    console.error('Error initializing Top Users Chart:', e);
                }

                // 4. Top Admins Bar Chart
                try {
                    const topAdminData = @json($topAdmins);
                    const adminLabels = (topAdminData || []).map(item => item && item.admin ? item.admin.name : `Admin #${item.admin_id}`);
                    const adminValues = (topAdminData || []).map(item => item ? item.total : 0);

                    const ctxAdmin = document.getElementById('topAdminsChart');
                    if (ctxAdmin) {
                        new Chart(ctxAdmin, {
                            type: 'bar',
                            data: {
                                labels: adminLabels.length ? adminLabels : ['No Data'],
                                datasets: [{
                                    label: 'Sessions',
                                    data: adminValues.length ? adminValues : [0],
                                    backgroundColor: '#10b981',
                                    borderRadius: 6
                                }]
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                plugins: { legend: { display: false } },
                                scales: {
                                    y: { beginAtZero: true, ticks: { precision: 0 } }
                                }
                            }
                        });
                    }
                } catch (e) {
                    console.error('Error initializing Top Admins Chart:', e);
                }
            }

            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', initCharts);
            } else {
                initCharts();
            }
        })();
    </script>
</x-app-layout>
