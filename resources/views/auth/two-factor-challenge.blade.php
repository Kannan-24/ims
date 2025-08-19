<x-app-layout>
    <x-slot name="title">Two-Factor Verification</x-slot>
    <div class="py-6 mt-24 ml-4 sm:ml-64">
        <div class="max-w-md mx-auto bg-gray-900 border border-gray-800 rounded-xl shadow-2xl p-8">
            <h1 class="text-2xl font-bold mb-4 text-white">Two-Factor Authentication</h1>
            <p class="text-sm text-gray-400 mb-6">@php($u = auth()->user())
                @if($u && $u->totp_enabled && $u->email_otp_enabled)
                    Enter both the authenticator code and the email code.
                @elseif($u && $u->totp_enabled)
                    Enter the 6-digit code from your authenticator app.
                @elseif($u && $u->email_otp_enabled)
                    Enter the 6-digit code sent to your email.
                @endif
            </p>
            @if (session('error'))
                <div class="text-red-400 text-sm mb-4">{{ session('error') }}</div>
            @endif
            @if (session('success'))
                <div class="text-green-400 text-sm mb-4">{{ session('success') }}</div>
            @endif
            <form method="POST" action="{{ route('2fa.challenge.verify') }}" class="space-y-5">
                @csrf
                @if($u && $u->totp_enabled)
                    <div>
                        <label class="block text-sm font-medium mb-1 text-gray-300">Authenticator Code</label>
                        <input type="text" name="totp_code" maxlength="6" pattern="[0-9]*" inputmode="numeric" autofocus class="w-full bg-gray-800 border border-gray-700 rounded p-3 text-white focus:outline-none focus:ring-2 focus:ring-blue-600" {{ $u->totp_enabled ? 'required' : '' }}>
                    </div>
                @endif
                @if($u && $u->email_otp_enabled)
                    <div>
                        <label class="block text-sm font-medium mb-1 text-gray-300">Email Code</label>
                        <input type="text" name="email_code" maxlength="6" pattern="[0-9]*" inputmode="numeric" class="w-full bg-gray-800 border border-gray-700 rounded p-3 text-white focus:outline-none focus:ring-2 focus:ring-blue-600" {{ $u->email_otp_enabled ? 'required' : '' }}>
                    </div>
                @endif
                <div class="flex items-center gap-3 flex-wrap">
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded font-semibold transition">Verify</button>
                    @if($u && $u->email_otp_enabled)
                        <form method="POST" action="{{ route('2fa.challenge.resend') }}" class="inline">
                            @csrf
                            <button class="text-xs text-blue-400 hover:text-blue-300" formmethod="POST" formaction="{{ route('2fa.challenge.resend') }}">Resend Email Code</button>
                        </form>
                    @endif
                    <a href="{{ route('logout') }}" class="text-xs text-gray-400 hover:text-gray-200" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a>
                </div>
            </form>
            @if(auth()->user() && auth()->user()->preferred_2fa_method === 'otp')
                <form method="POST" action="{{ route('2fa.challenge.resend') }}" class="mt-6 text-center">
                    @csrf
                    <button class="text-xs text-blue-400 hover:text-blue-300">Resend Email Code</button>
                </form>
            @elseif(auth()->user() && auth()->user()->preferred_2fa_method === 'totp')
                <div class="mt-6 text-xs text-gray-500 text-center">Open your authenticator app. If you reconfigured, remove and re-add using the secret from account settings.</div>
            @endif
            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">@csrf</form>
        </div>
    </div>
</x-app-layout>
