@php
    $impersonationLog = null;

    if (session()->has('impersonation_log_id')) {
        $impersonationLog = \App\Models\ImpersonationLog::find(
            session('impersonation_log_id')
        );
    }
@endphp

<nav class="bg-white border-b border-gray-200 shadow-sm">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">

            {{-- Left Side --}}
            <div class="flex items-center">
                <a href="{{ route('dashboard') }}" class="text-xl font-bold text-indigo-600 flex items-center gap-2">
                    <span>⚡</span>
                    <span>Laravel Impersonate</span>
                </a>

                <div class="hidden sm:flex ml-10 space-x-6">
                    <a href="{{ route('dashboard') }}" class="text-gray-700 hover:text-indigo-600 font-medium text-sm transition">
                        Dashboard
                    </a>

                    @if(Auth::user()->is_admin)
                        <a href="{{ route('admin.users.index') }}" class="text-gray-700 hover:text-indigo-600 font-medium text-sm transition">
                            Manage Users
                        </a>

                        <a href="{{ route('admin.audit.logs') }}" class="text-gray-700 hover:text-indigo-600 font-medium text-sm transition">
                            Audit Logs
                        </a>
                    @endif
                </div>
            </div>

            {{-- Right Side --}}
            <div class="flex items-center space-x-4">

                {{-- Impersonation Active Banner --}}
                @if($impersonationLog)
                    <div class="bg-amber-50 border border-amber-300 rounded-xl px-3 py-1.5 flex items-center gap-3 shadow-sm">
                        <div>
                            <div class="text-xs flex items-center gap-1.5">
                                <span class="text-amber-800 font-medium">Impersonating:</span>
                                <strong class="text-gray-900 font-bold">{{ Auth::user()->name }}</strong>

                                @if($impersonationLog->access_mode === 'read_only')
                                    <span class="bg-indigo-600 text-white px-2 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider">
                                        🛡️ Read-Only
                                    </span>
                                @else
                                    <span class="bg-amber-600 text-white px-2 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider">
                                        ⚠️ Full Write
                                    </span>
                                @endif

                                @if($impersonationLog->ticket_id)
                                    <span class="text-[11px] text-gray-500 font-mono bg-white px-1.5 py-0.5 rounded border border-gray-200">
                                        {{ $impersonationLog->ticket_id }}
                                    </span>
                                @endif
                            </div>

                            <div class="text-[11px] text-amber-700 mt-0.5 flex items-center gap-1">
                                <span>Session expires in:</span>
                                <span id="countdown" class="font-mono font-bold text-amber-900">--:--</span>
                            </div>
                        </div>

                        {{-- Leave Impersonation Button --}}
                        <a href="{{ route('leave.impersonate') }}"
                            class="bg-amber-500 hover:bg-amber-600 text-white text-xs font-bold px-3 py-1.5 rounded-lg shadow-sm transition">
                            Leave
                        </a>
                    </div>
                @endif

                {{-- Current User --}}
                <div class="text-right hidden sm:block">
                    <div class="text-sm font-semibold text-gray-800">{{ Auth::user()->name }}</div>
                    <div class="text-xs text-gray-400">{{ Auth::user()->is_admin ? 'Super Admin' : 'User' }}</div>
                </div>

                {{-- Logout --}}
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="bg-gray-100 hover:bg-red-50 text-gray-700 hover:text-red-600 text-xs font-semibold px-3 py-2 rounded-lg border border-gray-200 transition">
                        Logout
                    </button>
                </form>

            </div>

        </div>
    </div>
</nav>

@if($impersonationLog)
<script>
    const startedAt = new Date("{{ $impersonationLog->created_at->toIso8601String() }}");
    const timeoutSeconds = {{ ($impersonationLog->duration_minutes ?: 15) * 60 }};

    function updateTimer() {
        const countdown = document.getElementById('countdown');
        if (!countdown) return;

        const currentTime = new Date();
        const elapsedSeconds = Math.floor((currentTime - startedAt) / 1000);
        const remainingSeconds = timeoutSeconds - elapsedSeconds;

        if (remainingSeconds <= 0) {
            countdown.innerHTML = "00:00 (Expired)";
            window.location.reload();
            return;
        }

        const minutes = Math.floor(remainingSeconds / 60);
        const seconds = remainingSeconds % 60;

        countdown.innerHTML = String(minutes).padStart(2, '0') + ":" + String(seconds).padStart(2, '0');
    }

    updateTimer();
    setInterval(updateTimer, 1000);
</script>
@endif
