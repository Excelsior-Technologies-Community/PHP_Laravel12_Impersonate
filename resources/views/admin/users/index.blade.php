<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h2 class="font-bold text-2xl text-gray-800 leading-tight">
                    👥 {{ __('User Management & Impersonation Hub') }}
                </h2>
                <p class="text-sm text-gray-500 mt-1">Impersonate user accounts securely with mandatory compliance justifications and Safe Mode protection.</p>
            </div>

            <div class="flex gap-2">
                <a href="{{ route('admin.audit.logs') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 transition">
                    📋 Audit Logs
                </a>
                <a href="{{ route('dashboard') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 transition">
                    Dashboard
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <!-- Search Filter Bar -->
            <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100 mb-6">
                <form method="GET" action="{{ route('admin.users.index') }}" class="flex flex-col sm:flex-row gap-3">
                    <div class="relative flex-1">
                        <input type="text"
                               name="search"
                               value="{{ request('search') }}"
                               placeholder="Search user by name or email address..."
                               class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg text-sm focus:ring-indigo-500 focus:border-indigo-500">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                            🔍
                        </div>
                    </div>
                    <button type="submit" class="px-5 py-2 bg-indigo-600 text-white font-medium text-sm rounded-lg hover:bg-indigo-700 transition">
                        Search
                    </button>
                    @if(request('search'))
                    <a href="{{ route('admin.users.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 font-medium text-sm rounded-lg hover:bg-gray-300 transition text-center">
                        Reset
                    </a>
                    @endif
                </form>
            </div>

            <!-- Users Table Card -->
            <div class="bg-white overflow-hidden shadow-sm rounded-xl border border-gray-100">
                <div class="p-6 text-gray-900">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">ID</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">User Details</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Role</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Registered</th>
                                    <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-100">
                                @forelse($users as $user)
                                    <tr class="hover:bg-gray-50 transition">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 font-mono">#{{ $user->id }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="h-9 w-9 rounded-full bg-indigo-100 text-indigo-700 flex items-center justify-center font-bold text-sm mr-3">
                                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                                </div>
                                                <div>
                                                    <div class="text-sm font-semibold text-gray-900">{{ $user->name }}</div>
                                                    <div class="text-xs text-gray-500">{{ $user->email }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if($user->is_admin)
                                                <span class="px-2.5 py-1 inline-flex text-xs leading-4 font-semibold rounded-full bg-purple-100 text-purple-800 border border-purple-200">
                                                    👑 Admin
                                                </span>
                                            @else
                                                <span class="px-2.5 py-1 inline-flex text-xs leading-4 font-semibold rounded-full bg-emerald-100 text-emerald-800 border border-emerald-200">
                                                    👤 Customer / User
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-xs text-gray-500">
                                            {{ $user->created_at ? $user->created_at->format('d M Y') : 'N/A' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            @if(!$user->is_admin && Auth::user()->canImpersonate())
                                                <button type="button"
                                                        onclick="openImpersonateModal({{ $user->id }}, '{{ addslashes($user->name) }}', '{{ addslashes($user->email) }}')"
                                                        class="inline-flex items-center px-3 py-1.5 bg-indigo-50 border border-indigo-200 rounded-lg text-xs font-semibold text-indigo-700 hover:bg-indigo-600 hover:text-white transition">
                                                    ⚡ Impersonate Account
                                                </button>
                                            @elseif($user->is_admin)
                                                <span class="text-xs text-gray-400 italic">Protected (Admin)</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-8 text-center text-gray-500 text-sm">
                                            No user accounts found matching your query.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-6">
                        {{ $users->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ============================================================== -->
    <!-- PRE-IMPERSONATION COMPLIANCE MODAL (Features 1 & 2)           -->
    <!-- ============================================================== -->
    <div id="impersonateModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 hidden transition-opacity">
        <div class="bg-white rounded-2xl shadow-2xl max-w-lg w-full mx-4 overflow-hidden transform transition-all">
            <form id="impersonateForm" method="POST" action="">
                @csrf
                <!-- Modal Header -->
                <div class="bg-gradient-to-r from-indigo-600 to-blue-600 p-6 text-white">
                    <div class="flex justify-between items-start">
                        <div>
                            <span class="bg-white bg-opacity-20 text-xs px-2.5 py-1 rounded-full font-semibold uppercase tracking-wider">
                                Compliance & Access Verification
                            </span>
                            <h3 class="text-xl font-bold mt-2">Start Impersonation Session</h3>
                        </div>
                        <button type="button" onclick="closeImpersonateModal()" class="text-white hover:text-gray-200 text-2xl font-bold leading-none">
                            &times;
                        </button>
                    </div>

                    <!-- Target User Pill -->
                    <div class="mt-4 bg-white bg-opacity-15 p-3 rounded-xl flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-white text-indigo-600 flex items-center justify-center font-bold text-base">
                            👤
                        </div>
                        <div>
                            <div class="font-semibold text-sm" id="modalUserName">User Name</div>
                            <div class="text-xs text-indigo-100" id="modalUserEmail">user@example.com</div>
                        </div>
                    </div>
                </div>

                <!-- Modal Body -->
                <div class="p-6 space-y-4">

                    <!-- Feature 1: Access Mode Selector -->
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">
                            Access Security Mode
                        </label>
                        <div class="grid grid-cols-2 gap-3">
                            <label class="relative flex flex-col p-3 border-2 border-indigo-600 rounded-xl cursor-pointer bg-indigo-50 hover:bg-indigo-100 transition" id="labelReadOnly">
                                <div class="flex items-center justify-between">
                                    <span class="font-bold text-sm text-indigo-900">🛡️ Read-Only</span>
                                    <input type="radio" name="access_mode" value="read_only" checked class="text-indigo-600" onchange="toggleModeStyle(this)">
                                </div>
                                <span class="text-xs text-gray-600 mt-1">Safe Mode. Blocks all mutating writes & deletions.</span>
                            </label>

                            <label class="relative flex flex-col p-3 border border-gray-200 rounded-xl cursor-pointer hover:bg-gray-50 transition" id="labelFullAccess">
                                <div class="flex items-center justify-between">
                                    <span class="font-bold text-sm text-gray-800">⚠️ Full Write</span>
                                    <input type="radio" name="access_mode" value="full_access" class="text-indigo-600" onchange="toggleModeStyle(this)">
                                </div>
                                <span class="text-xs text-gray-500 mt-1">Allows modifying data (for reproduction).</span>
                            </label>
                        </div>
                    </div>

                    <!-- Feature 2: Support Ticket & Justification -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">
                                Ticket / Incident ID
                            </label>
                            <input type="text"
                                   name="ticket_id"
                                   placeholder="e.g. SUP-10492"
                                   class="w-full text-sm border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                            <span class="text-xs text-gray-400">Required for SOC2 auditing</span>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">
                                Session Duration
                            </label>
                            <select name="duration_minutes" class="w-full text-sm border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                                <option value="2">2 Minutes (Fast Check)</option>
                                <option value="5">5 Minutes</option>
                                <option value="15" selected>15 Minutes (Default)</option>
                                <option value="30">30 Minutes</option>
                                <option value="60">60 Minutes (Deep Debug)</option>
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">
                            Justification Reason <span class="text-red-500">*</span>
                        </label>
                        <select name="reason" required class="w-full text-sm border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 mb-2">
                            <option value="Customer Support & Inquiry">Customer Support & Inquiry</option>
                            <option value="Investigating Reported Bug / Error">Investigating Reported Bug / Error</option>
                            <option value="Billing & Subscription Investigation">Billing & Subscription Investigation</option>
                            <option value="Account Setup & Onboarding Assistance">Account Setup & Onboarding Assistance</option>
                            <option value="Role & Permissions Verification">Role & Permissions Verification</option>
                            <option value="Other / Security Verification">Other / Security Verification</option>
                        </select>
                        <input type="text"
                               name="custom_notes"
                               placeholder="Additional context or notes..."
                               class="w-full text-sm border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                    </div>

                    <div class="bg-gray-50 border border-gray-200 p-3 rounded-lg text-xs text-gray-600 flex items-start gap-2">
                        <span>ℹ️</span>
                        <span>This access will be logged with your administrator ID, IP address, and browser agent for security auditing.</span>
                    </div>

                </div>

                <!-- Modal Footer -->
                <div class="bg-gray-100 px-6 py-4 flex justify-end gap-3 rounded-b-2xl">
                    <button type="button" onclick="closeImpersonateModal()" class="px-4 py-2 bg-white border border-gray-300 rounded-lg text-sm font-semibold text-gray-700 hover:bg-gray-50 transition">
                        Cancel
                    </button>
                    <button type="submit" class="px-5 py-2 bg-indigo-600 text-white rounded-lg text-sm font-bold hover:bg-indigo-700 transition shadow">
                        🚀 Authorize & Enter Session
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        const modal = document.getElementById('impersonateModal');
        const form = document.getElementById('impersonateForm');
        const modalUserName = document.getElementById('modalUserName');
        const modalUserEmail = document.getElementById('modalUserEmail');

        function openImpersonateModal(userId, name, email) {
            form.action = `/admin/users/${userId}/impersonate`;
            modalUserName.innerText = name;
            modalUserEmail.innerText = email;
            modal.classList.remove('hidden');
        }

        function closeImpersonateModal() {
            modal.classList.add('hidden');
        }

        function toggleModeStyle(radio) {
            const labelReadOnly = document.getElementById('labelReadOnly');
            const labelFullAccess = document.getElementById('labelFullAccess');

            if (radio.value === 'read_only') {
                labelReadOnly.className = 'relative flex flex-col p-3 border-2 border-indigo-600 rounded-xl cursor-pointer bg-indigo-50 transition';
                labelFullAccess.className = 'relative flex flex-col p-3 border border-gray-200 rounded-xl cursor-pointer hover:bg-gray-50 transition';
            } else {
                labelFullAccess.className = 'relative flex flex-col p-3 border-2 border-amber-500 rounded-xl cursor-pointer bg-amber-50 transition';
                labelReadOnly.className = 'relative flex flex-col p-3 border border-gray-200 rounded-xl cursor-pointer hover:bg-gray-50 transition';
            }
        }

        // Close on ESC
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && !modal.classList.contains('hidden')) {
                closeImpersonateModal();
            }
        });
    </script>
</x-app-layout>
