<x-guest-layout>
    <x-slot name="title">
        {{ __('Verify Email') }} - {{ config('app.name', 'ATMS') }}
    </x-slot>

    <!-- Right Section: Verify Email -->
    <div class="max-w-md mx-auto">
        <div class="mb-8">
            <h3 class="text-3xl font-bold text-gray-100">Verify Your Email</h3>
            <p class="mt-2 text-sm text-gray-400">
                {{ __('Thanks for signing up! Before getting started, could you verify your email address by clicking on the link we just emailed to you? If you didn\'t receive the email, we will gladly send you another.') }}
            </p>
        </div>

        @if (session('status') == 'verification-link-sent')
            <div class="mb-4 font-medium text-sm text-green-500">
                {{ __('A new verification link has been sent to the email address you provided during registration.') }}
            </div>
        @endif

        <form method="POST" action="{{ route('verification.send') }}" class="space-y-4">
            @csrf

            <!-- Resend Verification Email Button -->
            <div class="!mt-8">
                <button type="submit"
                    class="w-full shadow-xl py-2.5 px-4 text-sm tracking-wide rounded-lg text-white bg-blue-600 hover:bg-blue-700 focus:outline-none">
                    {{ __('Resend Verification Email') }}
                </button>
            </div>
        </form>

        <form method="POST" action="{{ route('logout') }}" class="mt-4">
            @csrf

            <!-- Log Out Button -->
            <button type="submit"
                class="w-full shadow-xl py-2.5 px-4 text-sm tracking-wide rounded-lg text-white bg-gray-600 hover:bg-gray-700 focus:outline-none">
                {{ __('Log Out') }}
            </button>
        </form>
    </div>
</x-guest-layout>
