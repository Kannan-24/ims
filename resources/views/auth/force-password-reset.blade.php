<x-app-layout>
    <x-slot name="title">Force Password Reset - {{ config('app.name') }}</x-slot>
    <div class="py-6 mt-24 ml-4 sm:ml-64">
        <div class="max-w-xl mx-auto bg-gray-900 border border-gray-800 rounded-xl shadow-2xl p-8">
            <h1 class="text-2xl font-bold mb-4 text-white">Update Your Password</h1>
            <p class="mb-6 text-sm text-gray-400">For security reasons you must set a new password before continuing.</p>
            <form method="POST" action="{{ route('password.force.update') }}" class="space-y-5">
                @csrf
                @method('PUT')
                <div>
                    <label class="block text-sm font-medium mb-1 text-gray-300">New Password</label>
                    <input type="password" name="password" required class="w-full bg-gray-800 border border-gray-700 rounded p-3 text-white focus:outline-none focus:ring-2 focus:ring-blue-600" autofocus>
                    @error('password')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1 text-gray-300">Confirm Password</label>
                    <input type="password" name="password_confirmation" required class="w-full bg-gray-800 border border-gray-700 rounded p-3 text-white focus:outline-none focus:ring-2 focus:ring-blue-600">
                </div>
                <div class="flex items-center gap-4">
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded font-semibold transition">Save Password</button>
                    <a href="{{ route('logout') }}" class="text-sm text-gray-400 hover:text-gray-200" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a>
                </div>
            </form>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
                @csrf
            </form>
            <p class="text-xs text-gray-500 mt-6">Password must meet policy: min {{ config('password_policy.min_length') }} chars, include upper/lower, number & symbol.</p>
        </div>
    </div>
</x-app-layout>
