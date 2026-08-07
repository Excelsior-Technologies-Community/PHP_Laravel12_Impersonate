<x-app-layout>

    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="text-2xl font-bold text-gray-800">
                📊 Admin Dashboard
            </h2>

            <div class="space-x-2">
                <a href="{{ route('admin.users.index') }}"
                   class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg">
                    Manage Users
                </a>

                <a href="{{ route('admin.audit.logs') }}"
                   class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg">
                    Audit Logs
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-10">

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-gradient-to-r from-indigo-600 to-blue-600 rounded-xl shadow-lg p-8 text-white mb-8">

                <h1 class="text-3xl font-bold">
                    Welcome, {{ Auth::user()->name }}
                </h1>

                <p class="mt-2 opacity-90">
                    Manage users, monitor impersonation activity, and review security logs.
                </p>

            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">

                <div class="bg-white rounded-xl shadow p-6">
                    <p class="text-gray-500 text-sm uppercase">
                        Total Users
                    </p>

                    <h2 class="text-4xl font-bold mt-2 text-indigo-600">
                        {{ $stats['totalUsers'] }}
                    </h2>
                </div>

                <div class="bg-white rounded-xl shadow p-6">
                    <p class="text-gray-500 text-sm uppercase">
                        Audit Logs
                    </p>

                    <h2 class="text-4xl font-bold mt-2 text-green-600">
                        {{ $stats['totalLogs'] }}
                    </h2>
                </div>

                <div class="bg-white rounded-xl shadow p-6">
                    <p class="text-gray-500 text-sm uppercase">
                        Active Sessions
                    </p>

                    <h2 class="text-4xl font-bold mt-2 text-yellow-600">
                        {{ $stats['activeSessions'] }}
                    </h2>
                </div>

                <div class="bg-white rounded-xl shadow p-6">
                    <p class="text-gray-500 text-sm uppercase">
                        Today's Logs
                    </p>

                    <h2 class="text-4xl font-bold mt-2 text-red-600">
                        {{ $stats['todayLogs'] }}
                    </h2>
                </div>

            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">

                <a href="{{ route('admin.users.index') }}"
                   class="bg-white rounded-xl shadow hover:shadow-lg transition p-6">

                    <h3 class="text-lg font-semibold text-indigo-600">
                        👥 Manage Users
                    </h3>

                    <p class="mt-2 text-gray-600">
                        View users and impersonate accounts for customer support.
                    </p>

                </a>

                <a href="{{ route('admin.audit.logs') }}"
                   class="bg-white rounded-xl shadow hover:shadow-lg transition p-6">

                    <h3 class="text-lg font-semibold text-green-600">
                        📋 Audit Logs
                    </h3>

                    <p class="mt-2 text-gray-600">
                        Review every impersonation session with timestamps and status.
                    </p>

                </a>

                <div class="bg-white rounded-xl shadow p-6">

                    <h3 class="text-lg font-semibold text-orange-600">
                        🔒 Security
                    </h3>

                    <p class="mt-2 text-gray-600">
                        Maximum impersonation session:
                    </p>

                    <span class="inline-block mt-3 bg-orange-100 text-orange-700 px-4 py-2 rounded-lg font-semibold">
                         2 Minutes
                    </span>

                </div>

            </div>

            <div class="bg-white rounded-xl shadow">

                <div class="border-b px-6 py-4">

                    <h3 class="text-xl font-semibold">
                        System Features
                    </h3>

                </div>

                <div class="p-6">

                    <div class="grid md:grid-cols-2 gap-6">

                        <div>

                            <ul class="space-y-3 text-gray-700">

                                <li>✅ Secure Admin Login</li>
                                <li>✅ User Impersonation</li>
                                <li>✅ Session Tracking</li>
                                <li>✅ Audit Logs</li>

                            </ul>

                        </div>

                        <div>

                            <ul class="space-y-3 text-gray-700">

                                <li>✅ Auto Timeout (2 Minutes)</li>
                                <li>✅ Search Users</li>
                                <li>✅ Search Audit Logs</li>
                                <li>✅ Security Monitoring</li>

                            </ul>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

</x-app-layout>