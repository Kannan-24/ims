<x-guest-layout>
    <x-slot name="title">
        {{ __('Confirm Password') }} - {{ config('app.name', 'ATMS') }}
    </x-slot>

    <!-- Right Section: Confirm Password Form -->
    <div class="max-w-md mx-auto">
        <div class="mb-8">
            <h3 class="text-3xl font-bold text-gray-100">Confirm Password</h3>
            <p class="mt-2 text-sm text-gray-400">
                {{ __('This is a secure area of the application. Please confirm your password before continuing.') }}
            </p>
        </div>

        <form method="POST" action="{{ route('password.confirm') }}" class="space-y-4">
            @csrf

            <!-- Password -->
            <div class="relative mt-4" x-data="{ show: false }">
                <label class="block text-sm font-medium text-gray-200" for="password">Password</label>
                <input
                    class="w-full py-3 pl-4 pr-10 text-sm text-gray-200 bg-gray-800 border border-gray-700 rounded-lg outline-blue-500 placeholder-gray-500"
                    id="password" x-bind:type="show ? 'text' : 'password'" name="password" required="required"
                    autocomplete="current-password" placeholder="Enter your password">
                <span class="absolute w-5 h-5" id="password-toggle" @click="show = !show"
                    style="top: 50%; right: 15px;">
                    <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"
                        :class="{ 'hidden': show }">
                        <path
                            d="M3 14C3 9.02944 7.02944 5 12 5C16.9706 5 21 9.02944 21 14M17 14C17 16.7614 14.7614 19 12 19C9.23858 19 7 16.7614 7 14C7 11.2386 9.23858 9 12 9C14.7614 9 17 11.2386 17 14Z"
                            stroke="#959595" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                    <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"
                        :class="{ 'hidden': !show }">
                        <path
                            d="M9.60997 9.60714C8.05503 10.4549 7 12.1043 7 14C7 16.7614 9.23858 19 12 19C13.8966 19 15.5466 17.944 16.3941 16.3878M21 14C21 9.02944 16.9706 5 12 5C11.5582 5 11.1238 5.03184 10.699 5.09334M3 14C3 11.0069 4.46104 8.35513 6.70883 6.71886M3 3L21 21"
                            stroke="#959595" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </span>
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <!-- Submit Button -->
            <div class="!mt-8">
                <button type="submit"
                    class="w-full shadow-xl py-2.5 px-4 text-sm tracking-wide rounded-lg text-white bg-blue-600 hover:bg-blue-700 focus:outline-none">
                    {{ __('Confirm') }}
                </button>
            </div>
        </form>
    </div>
</x-guest-layout>
