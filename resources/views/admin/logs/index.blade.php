<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="text-2xl font-bold text-gray-800">
                📋 Impersonation Audit Logs
            </h2>

            <div class="space-x-2">
                <a href="{{ route('admin.users.index') }}"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">
                    Manage Users
                </a>

                <a href="{{ route('dashboard') }}"
                    class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg">
                    Dashboard
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-10">

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- Statistics --}}
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">

                <div class="bg-white rounded-xl shadow p-6">
                    <p class="text-gray-500 text-sm">
                        Total Logs
                    </p>

                    <h2 class="text-3xl font-bold text-indigo-600">
                        {{ $stats['totalLogs'] }}
                    </h2>
                </div>

                <div class="bg-white rounded-xl shadow p-6">
                    <p class="text-gray-500 text-sm">
                        Active Sessions
                    </p>

                    <h2 class="text-3xl font-bold text-yellow-600">
                        {{ $stats['activeSessions'] }}
                    </h2>
                </div>

                <div class="bg-white rounded-xl shadow p-6">
                    <p class="text-gray-500 text-sm">
                        Completed
                    </p>

                    <h2 class="text-3xl font-bold text-green-600">
                        {{ $stats['completedSessions'] }}
                    </h2>
                </div>

                <div class="bg-white rounded-xl shadow p-6">
                    <p class="text-gray-500 text-sm">
                        Today's Logs
                    </p>

                    <h2 class="text-3xl font-bold text-purple-600">
                        {{ $stats['todayLogs'] }}
                    </h2>
                </div>

            </div>

            {{-- Search --}}
            <div class="bg-white rounded-xl shadow p-6 mb-6">

                <form method="GET"
                    action="{{ route('admin.audit.logs') }}">

                    <div class="grid grid-cols-1 md:grid-cols-5 gap-4">

                        <input
                            type="text"
                            name="search"
                            value="{{ request('search') }}"
                            placeholder="Search..."
                            class="rounded-lg border-gray-300">

                        <select
                            name="status"
                            class="rounded-lg border-gray-300">

                            <option value="">
                                All Status
                            </option>

                            <option value="active"
                                {{ request('status')=='active' ? 'selected' : '' }}>
                                Active
                            </option>

                            <option value="completed"
                                {{ request('status')=='completed' ? 'selected' : '' }}>
                                Completed
                            </option>

                        </select>

                        <input
                            type="date"
                            name="start_date"
                            value="{{ request('start_date') }}"
                            class="rounded-lg border-gray-300">

                        <input
                            type="date"
                            name="end_date"
                            value="{{ request('end_date') }}"
                            class="rounded-lg border-gray-300">

                        <div class="flex gap-2">

                            <button
                                class="bg-indigo-600 text-white px-5 py-2 rounded-lg w-full">

                                Search

                            </button>

                            <a href="{{ route('admin.audit.logs') }}"
                                class="bg-gray-500 text-white px-5 py-2 rounded-lg">

                                Reset

                            </a>

                        </div>

                    </div>

                </form>

            </div>

            {{-- Audit Log Table --}}
            <div class="bg-white rounded-xl shadow overflow-hidden">

                <div class="overflow-x-auto">

                    <table class="min-w-full divide-y divide-gray-200">

                        <thead class="bg-gray-100">

                            <tr>

                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase">
                                    ID
                                </th>

                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase">
                                    Admin
                                </th>

                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase">
                                    User
                                </th>

                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase">
                                    Start Time
                                </th>

                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase">
                                    End Time
                                </th>

                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase">
                                    Duration
                                </th>

                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase">
                                    IP Address
                                </th>

                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase">
                                    Browser
                                </th>

                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase">
                                    Status
                                </th>

                            </tr>

                        </thead>

                        <tbody class="divide-y divide-gray-200">

                            @forelse($logs as $log)

                            <tr class="hover:bg-gray-50">

                                <td class="px-6 py-4">
                                    {{ $log->id }}
                                </td>

                                <td class="px-6 py-4">

                                    <div class="font-semibold">
                                        {{ $log->admin?->name }}
                                    </div>

                                    <div class="text-xs text-gray-500">
                                        {{ $log->admin?->email }}
                                    </div>

                                </td>

                                <td class="px-6 py-4">

                                    <div class="font-semibold">
                                        {{ $log->user?->name }}
                                    </div>

                                    <div class="text-xs text-gray-500">
                                        {{ $log->user?->email }}
                                    </div>

                                </td>

                                <td class="px-6 py-4">

                                    {{ $log->created_at->format('d M Y') }}

                                    <br>

                                    <small class="text-gray-500">
                                        {{ $log->created_at->format('h:i:s A') }}
                                    </small>

                                </td>

                                <td class="px-6 py-4">

                                    {{ $log->updated_at->format('d M Y') }}

                                    <br>

                                    <small class="text-gray-500">
                                        {{ $log->updated_at->format('h:i:s A') }}
                                    </small>

                                </td>

                                <td class="px-6 py-4">

                                    {{ $log->duration }}

                                </td>

                                <td class="px-6 py-4">

                                    {{ $log->ip_address ?? 'N/A' }}

                                </td>

                                <td class="px-6 py-4 max-w-xs">

                                    <span class="text-xs text-gray-500">

                                        {{ \Illuminate\Support\Str::limit($log->user_agent, 50) }}

                                    </span>

                                </td>

                                <td class="px-6 py-4">

                                    @if($log->created_at->equalTo($log->updated_at))

                                    <span class="px-3 py-1 rounded-full text-xs bg-yellow-100 text-yellow-700">

                                        Active

                                    </span>

                                    @else

                                    <span class="px-3 py-1 rounded-full text-xs bg-green-100 text-green-700">

                                        Completed

                                    </span>

                                    @endif

                                </td>

                            </tr>

                            @empty

                            <tr>

                                <td colspan="9"
                                    class="text-center py-10 text-gray-500">

                                    <div class="flex flex-col items-center">

                                        <span class="text-5xl mb-3">
                                            📂
                                        </span>

                                        <h3 class="text-lg font-semibold">

                                            No Audit Logs Found

                                        </h3>

                                        <p class="text-sm text-gray-500 mt-1">

                                            No impersonation activity has been recorded yet.

                                        </p>

                                    </div>

                                </td>

                            </tr>

                            @endforelse

                        </tbody>

                    </table>

                </div>

            </div>

            {{-- Pagination --}}
            <div class="mt-6">

                {{ $logs->links() }}

            </div>

        </div>

    </div>

</x-app-layout>