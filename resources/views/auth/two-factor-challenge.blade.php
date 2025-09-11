<x-guest-layout>
    <x-slot name="title">
        {{ __('Two-Factor Authentication') }} - {{ config('app.name', 'ATMS') }}
    </x-slot>

    <div class="w-full">
        <!-- Header -->
        <div class="text-center mb-8">
            <h2 class="text-2xl font-bold text-gray-900">Two-Factor Authentication</h2>
            <p class="mt-2 text-sm text-gray-600">
                @php($u = auth()->user())
                @if($u && $u->totp_enabled && $u->email_otp_enabled)
                    Please enter both your authenticator code and email code to continue.
                @elseif($u && $u->totp_enabled)
                    Please enter the 6-digit code from your authenticator app.
                @elseif($u && $u->email_otp_enabled)
                    Please enter the 6-digit code sent to your email.
                @endif
            </p>
        </div>

        <!-- Status Messages -->
        @if (session('error'))
            <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg">
                <p class="text-sm text-red-600">{{ session('error') }}</p>
            </div>
        @endif

        @if (session('success'))
            <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg">
                <p class="text-sm text-green-600">{{ session('success') }}</p>
            </div>
        @endif

        <form method="POST" action="{{ route('2fa.challenge.verify') }}" class="space-y-6">
            @csrf
            
            @if($u && $u->totp_enabled)
                <div>
                    <label for="totp_code" class="block text-sm font-medium text-gray-700 mb-2">Authenticator Code</label>
                    <input type="text" name="totp_code" id="totp_code" maxlength="6" pattern="[0-9]*" inputmode="numeric" autofocus
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg text-gray-900 placeholder-gray-500 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors text-center text-lg tracking-widest"
                        placeholder="000000" {{ $u->totp_enabled ? 'required' : '' }}>
                </div>
            @endif

            @if($u && $u->email_otp_enabled)
                <div>
                    <label for="email_code" class="block text-sm font-medium text-gray-700 mb-2">Email Code</label>
                    <input type="text" name="email_code" id="email_code" maxlength="6" pattern="[0-9]*" inputmode="numeric"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg text-gray-900 placeholder-gray-500 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors text-center text-lg tracking-widest"
                        placeholder="000000" {{ $u->email_otp_enabled ? 'required' : '' }}>
                </div>
            @endif

            <!-- Submit Button -->
            <button type="submit"
                class="w-full bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:ring-blue-200 text-white py-3 px-4 rounded-lg font-medium transition-colors">
                Verify
            </button>
        </form>

        <!-- Additional Actions -->
        <div class="mt-6 space-y-4">
            @if($u && $u->email_otp_enabled)
                <form method="POST" action="{{ route('2fa.challenge.resend') }}" class="text-center">
                    @csrf
                    <button type="submit" class="text-sm text-blue-600 hover:text-blue-500 font-medium">
                        Resend Email Code
                    </button>
                </form>
            @endif

            @if($u && $u->preferred_2fa_method === 'totp')
                <div class="text-center">
                    <p class="text-xs text-gray-500">
                        Open your authenticator app to get the code. If you need to reconfigure, please contact support.
                    </p>
                </div>
            @endif
        </div>

        <!-- Logout Option -->
        <div class="text-center pt-6 border-t border-gray-200 mt-8">
            <p class="text-sm text-gray-600">
                Need to switch accounts?
                <button type="button" onclick="document.getElementById('logout-form').submit();" 
                    class="text-blue-600 hover:text-blue-500 font-medium">
                    Logout
                </button>
            </p>
        </div>

        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
            @csrf
        </form>
    </div>
</x-guest-layout>
