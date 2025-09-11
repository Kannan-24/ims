<x-guest-layout>
    <x-slot name="title">
        {{ __('Verify Email') }} - {{ config('app.name', 'ATMS') }}
    </x-slot>

    <div class="w-full">
        <!-- Header -->
        <div class="text-center mb-8">
            <h2 class="text-2xl font-bold text-gray-900">Verify your email</h2>
            <p class="mt-2 text-sm text-gray-600">
                {{ __('Thanks for signing up! Before getting started, please verify your email address by clicking on the link we just emailed to you.') }}
            </p>
        </div>

        @if (session('status') == 'verification-link-sent')
            <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg">
                <p class="text-sm text-green-600">
                    {{ __('A new verification link has been sent to your email address.') }}
                </p>
            </div>
        @endif

        <div class="space-y-4">
            <form method="POST" action="{{ route('verification.send') }}">
                @csrf
                <!-- Resend Verification Email Button -->
                <button type="submit"
                    class="w-full bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:ring-blue-200 text-white py-3 px-4 rounded-lg font-medium transition-colors">
                    {{ __('Resend Verification Email') }}
                </button>
            </form>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <!-- Log Out Button -->
                <button type="submit"
                    class="w-full bg-gray-100 hover:bg-gray-200 focus:ring-4 focus:ring-gray-200 text-gray-700 py-3 px-4 rounded-lg font-medium transition-colors border border-gray-300">
                    {{ __('Log Out') }}
                </button>
            </form>
        </div>

        <!-- Help Text -->
        <div class="text-center pt-6 border-t border-gray-200 mt-8">
            <p class="text-sm text-gray-600">
                Having trouble receiving emails?
                <a href="mailto:support@example.com" class="text-blue-600 hover:text-blue-500 font-medium">
                    Contact support
                </a>
            </p>
        </div>
    </div>
</x-guest-layout>
