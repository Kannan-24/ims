<x-app-layout>
    <x-slot name="title">
        {{ __('Dashboard') }} - {{ config('app.name', 'SKM') }}
    </x-slot>

    <div class="mt-20 ml-4 py-9 sm:ml-64 sm:me-4 lg:me-0">
        <div class="w-full mx-auto max-w-7xl sm:px-6 lg:px-8">

            <!-- Welcome Section -->
            <div class="p-6 mb-6 text-center text-white rounded-lg shadow-lg bg-gradient-to-r from-teal-400 to-blue-500">
                <h1 class="text-3xl font-bold">Welcome Back! 👋</h1>
                <p class="mt-2 text-lg">Hello, Admin! Here’s what’s happening in your system today.</p>
            </div>

</x-app-layout>
