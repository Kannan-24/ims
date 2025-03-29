<x-guest-layout>
    <div class="w-full max-w-md bg-gray-800">
        <h3 class="text-3xl font-bold text-center text-white">Create an Account</h3>
        <p class="mt-2 text-sm text-center text-gray-400">
            Register to manage invoices, quotations, and customer details effortlessly.
        </p>

        <form method="POST" action="{{ route('register') }}" class="mt-6">
            @csrf

            <!-- Name -->
            <div>
                <x-input-label for="name" class="text-gray-100" :value="__('Name')" />
                <x-text-input id="name" class="block mt-1 w-full bg-gray-700 border-gray-600 text-white placeholder-gray-400 focus:ring-indigo-500 focus:border-indigo-500" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
                <x-input-error :messages="$errors->get('name')" class="mt-2 text-red-400" />
            </div>

            <!-- Email Address -->
            <div class="mt-4">
                <x-input-label for="email" class="text-gray-100" :value="__('Email')" />
                <x-text-input id="email" class="block mt-1 w-full bg-gray-700 border-gray-600 text-white placeholder-gray-400 focus:ring-indigo-500 focus:border-indigo-500" type="email" name="email" :value="old('email')" required autocomplete="username" />
                <x-input-error :messages="$errors->get('email')" class="mt-2 text-red-400" />
            </div>

            <!-- Password -->
            <div class="mt-4">
                <x-input-label for="password" class="text-gray-100" :value="__('Password')" />
                <x-text-input id="password" class="block mt-1 w-full bg-gray-700 border-gray-600 text-white placeholder-gray-400 focus:ring-indigo-500 focus:border-indigo-500" type="password" name="password" required autocomplete="new-password" />
                <x-input-error :messages="$errors->get('password')" class="mt-2 text-red-400" />
            </div>

            <!-- Confirm Password -->
            <div class="mt-4">
                <x-input-label for="password_confirmation" class="text-gray-100" :value="__('Confirm Password')" />
                <x-text-input id="password_confirmation" class="block mt-1 w-full bg-gray-700 border-gray-600 text-white placeholder-gray-400 focus:ring-indigo-500 focus:border-indigo-500" type="password" name="password_confirmation" required autocomplete="new-password" />
                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2 text-red-400" />
            </div>

            <!-- Already Registered -->
            <div class="flex items-center justify-between mt-6">
                <a class="underline text-sm text-indigo-400 hover:text-indigo-300" href="{{ route('login') }}">
                    {{ __('Already registered?') }}
                </a>

                <x-primary-button class="bg-indigo-600 hover:bg-indigo-500 text-white px-4 py-2 rounded-lg shadow-md">
                    {{ __('Register') }}
                </x-primary-button>
            </div>
        </form>
    </div>
</x-guest-layout>
