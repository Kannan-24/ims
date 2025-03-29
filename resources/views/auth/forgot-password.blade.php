<x-guest-layout>
    <x-slot name="title">
        {{ __('Forgot Password') }} - {{ config('app.name', 'ATMS') }}
    </x-slot>

    <div class="max-w-md mx-auto">
        <div class="mb-8">
            <h3 class="text-3xl font-bold text-gray-100">Forgot your password?</h3>
            <p class="mt-2 text-sm text-gray-400">
                No problem. Just let us know your email address, and we will email you a password reset link that will allow you to choose a new one.
            </p>
        </div>

        <!-- Session Status -->
        <x-auth-session-status class="mb-4" :status="session('status')" />

        <form method="POST" action="{{ route('password.email') }}" class="space-y-4">
            @csrf

            <!-- Email Address -->
            <div>
                <label class="block mb-2 text-sm text-gray-200">Email</label>
                <div class="relative flex items-center">
                    <input id="email" name="email" type="email" required
                        class="w-full py-3 pl-4 pr-10 text-sm text-gray-200 bg-gray-800 border border-gray-700 rounded-lg outline-blue-500 placeholder-gray-500"
                        placeholder="Enter your email" value="{{ old('email') }}" autofocus
                        autocomplete="username" />
                </div>
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <!-- Submit Button -->
            <div class="!mt-8">
                <button type="submit"
                    class="w-full shadow-xl py-2.5 px-4 text-sm tracking-wide rounded-lg text-white bg-blue-600 hover:bg-blue-700 focus:outline-none">
                    {{ __('Email Password Reset Link') }}
                </button>
            </div>
        </form>
    </div>
</x-guest-layout>
