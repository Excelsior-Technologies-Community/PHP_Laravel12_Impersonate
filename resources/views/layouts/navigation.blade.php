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

                <a href="{{ route('dashboard') }}"
                    class="text-xl font-bold text-indigo-600">
                    Laravel Impersonate
                </a>

                <div class="hidden sm:flex ml-10 space-x-8">

                    <a href="{{ route('dashboard') }}"
                        class="text-gray-700 hover:text-indigo-600 font-medium">
                        Dashboard
                    </a>

                    @if(Auth::user()->is_admin)

                        <a href="{{ route('admin.users.index') }}"
                            class="text-gray-700 hover:text-indigo-600 font-medium">
                            Manage Users
                        </a>

                        <a href="{{ route('admin.audit.logs') }}"
                            class="text-gray-700 hover:text-indigo-600 font-medium">
                            Audit Logs
                        </a>

                    @endif

                </div>

            </div>

            {{-- Right Side --}}
            <div class="flex items-center space-x-4">

                {{-- Impersonation Information --}}
               @if($impersonationLog)

                    <div class="bg-yellow-100 border border-yellow-300 rounded-lg px-4 py-2">

                        <div class="text-sm">
                            <strong>
                                You are impersonating
                            </strong>

                            {{ Auth::user()->name }}
                        </div>

                        <div class="text-xs text-yellow-700">

                            Auto logout in

                            <span
                                id="countdown"
                                class="font-bold">
                                02:00
                            </span>

                        </div>

                    </div>

                    {{-- Leave Impersonation --}}
                    <a href="{{ route('leave.impersonate') }}"
                        class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded-lg">

                        Leave

                    </a>

                @endif

                {{-- Current User --}}
                <span class="font-semibold text-gray-800">
                    {{ Auth::user()->name }}
                </span>

                {{-- Logout --}}
                <form
                    action="{{ route('logout') }}"
                    method="POST">

                    @csrf

                    <button
                        type="submit"
                        class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg">

                        Logout

                    </button>

                </form>

            </div>

        </div>

    </div>

</nav>

@if($impersonationLog)


<script>

    const startedAt = new Date(
        "{{ $impersonationLog->created_at->toIso8601String() }}"
    );


    const timeoutSeconds = 120;


    function updateTimer() {

        const countdown = document.getElementById('countdown');

        if (!countdown) {
            return;
        }


        const currentTime = new Date();


        const elapsedSeconds = Math.floor(
            (currentTime - startedAt) / 1000
        );


        const remainingSeconds = timeoutSeconds - elapsedSeconds;


        if (remainingSeconds <= 0) {

            countdown.innerHTML = "00:00";

            window.location.reload();

            return;
        }


        const minutes = Math.floor(
            remainingSeconds / 60
        );


        const seconds = remainingSeconds % 60;


        countdown.innerHTML =
            String(minutes).padStart(2, '0') +
            ":" +
            String(seconds).padStart(2, '0');

    }


    updateTimer();

    setInterval(updateTimer, 1000);


</script>


@endif