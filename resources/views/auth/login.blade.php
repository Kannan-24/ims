<x-guest-layout>
    <div class="w-full max-w-md bg-gray-800 mx-auto p-6">
        <!-- Session Status -->
        <x-auth-session-status class="mb-4 text-green-400" :status="session('status')" />

        <h3 class="text-3xl font-bold text-white-800 text-center">Welcome Back</h3>
        <p class="mt-2 text-sm text-gray-500">
            Sign in to manage invoices, quotations, and customer details effortlessly.
        </p>

        <form method="POST" action="{{ route('login') }}" class="mt-6">
            @csrf

            <!-- Email Address -->
            <div>
                <x-input-label for="email" class="text-gray-100" :value="__('Email')" />
                <x-text-input id="email" class="block mt-1 w-full bg-gray-700 border-gray-600 text-white placeholder-gray-400 focus:ring-indigo-500 focus:border-indigo-500" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
                <x-input-error :messages="$errors->get('email')" class="mt-2 text-red-400" />
            </div>

            <!-- Password -->
            <div class="mt-4">
                <x-input-label for="password" class="text-gray-100" :value="__('Password')" />
                <x-text-input id="password" class="block mt-1 w-full bg-gray-700 border-gray-600 text-white placeholder-gray-400 focus:ring-indigo-500 focus:border-indigo-500" type="password" name="password" required autocomplete="current-password" />
                <x-input-error :messages="$errors->get('password')" class="mt-2 text-red-400" />
            </div>

            <!-- Remember Me -->
            <div class="block mt-4">
                <label for="remember_me" class="inline-flex items-center">
                    <input id="remember_me" type="checkbox" class="rounded border-gray-600 text-indigo-400 bg-gray-700 shadow-sm focus:ring-indigo-500" name="remember">
                    <span class="ms-2 text-sm text-gray-300">{{ __('Remember me') }}</span>
                </label>
            </div>

            <div class="flex items-center justify-between mt-6">
                @if (Route::has('password.request'))
                    <a class="underline text-sm text-indigo-400 hover:text-indigo-300" href="{{ route('password.request') }}">
                        {{ __('Forgot your password?') }}
                    </a>
                @endif

                <x-primary-button class="bg-indigo-600 hover:bg-indigo-500 text-white px-4 py-2 rounded-lg shadow-md">
                    {{ __('Log in') }}
                </x-primary-button>
            </div>
        </form>
    </div>
</x-guest-layout>
