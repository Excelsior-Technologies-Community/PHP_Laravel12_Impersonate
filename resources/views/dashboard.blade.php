<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('User Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-xl font-bold mb-2">Welcome, {{ $user->name }}!</h3>
                    <p class="text-sm text-gray-600 mb-1"><span class="font-semibold">Email:</span> {{ $user->email }}</p>
                    <p class="text-sm text-gray-600 mb-4"><span class="font-semibold">Role:</span> Regular Customer Account</p>
                    
                    <div class="p-4 bg-blue-50 border border-blue-200 rounded-lg text-sm text-blue-900 flex items-start gap-3">
                        <span class="text-xl">ℹ️</span>
                        <div>
                            <p class="font-semibold">Looking for the Impersonation & Security Intelligence Dashboard?</p>
                            <p class="text-xs text-blue-700 mt-1">
                                The interactive Chart.js analytics studio, audit logs, and impersonation controls are located on the Administrator Portal. To access them, sign in with the administrator credentials: <code class="bg-blue-100 text-blue-800 px-1 py-0.5 rounded font-mono">admin@example.com</code> / <code class="bg-blue-100 text-blue-800 px-1 py-0.5 rounded font-mono">password</code>.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
