<div class="space-y-4">
    @if (session('success'))
        <div class="p-3 bg-green-50 border border-green-200 rounded-lg">
            <div class="flex items-center">
                <i class="fas fa-check-circle text-green-500 mr-2"></i>
                <p class="text-green-700 text-sm">{{ session('success') }}</p>
            </div>
        </div>
    @endif
    @if (session('error'))
        <div class="p-3 bg-red-50 border border-red-200 rounded-lg">
            <div class="flex items-center">
                <i class="fas fa-exclamation-circle text-red-500 mr-2"></i>
                <p class="text-red-700 text-sm">{{ session('error') }}</p>
            </div>
        </div>
    @endif

    @php
        // Ensure $secret and $otpauth are available when the partial is included from pages
        // that didn't explicitly pass them (e.g. account settings index view)
        if (!isset($secret)) {
            $secret = $user->two_factor_secret; // may be null
        }
        if (!isset($otpauth) && !empty($secret) && $user->two_factor_secret) {
            try {
                $otpauth = app(\App\Services\TotpService::class)->getOtpAuthUrl(config('app.name'), $user->email, $user->two_factor_secret);
            } catch (Throwable $e) {
                $otpauth = null; // fail silently; QR just won't render
            }
        }
    @endphp

    <div class="grid md:grid-cols-2 gap-4">
        <!-- TOTP Method -->
        <div class="bg-gray-50 border border-gray-200 rounded-lg p-4 space-y-3">
            <div class="flex items-center justify-between">
                <h4 class="text-sm font-medium text-gray-900">Authenticator App (TOTP)</h4>
                @if($user->totp_enabled)
                    <span class="text-xs px-2 py-1 rounded-full bg-green-100 text-green-700">ENABLED</span>
                @endif
            </div>
            <p class="text-xs text-gray-600">Scan a QR code with Google Authenticator, Authy, 1Password, etc. to generate 30s codes.</p>
            @if(!$user->totp_enabled)
                <button onclick="openModal('enable-totp-modal')" class="bg-blue-600 hover:bg-blue-700 px-3 py-1.5 rounded-lg text-white text-xs transition-colors">Start TOTP Setup</button>
            @endif
            @if ($user->preferred_2fa_method === 'totp' && !$user->two_factor_enabled && !empty($secret))
                <div class="space-y-3 text-xs">
                    <p class="text-gray-700">Scan the QR or enter the secret manually:</p>
                    <p class="font-mono break-all bg-white border border-gray-200 p-2 rounded text-xs">{{ $secret }}</p>
                    <img class="bg-white p-2 rounded border border-gray-200" src="https://api.qrserver.com/v1/create-qr-code/?size=160x160&data={{ urlencode($otpauth) }}" alt="QR Code">
                    <form method="POST" action="{{ route('2fa.confirm') }}" class="space-y-2">
                        @csrf
                        <div>
                            <label class="block text-xs text-gray-700 mb-1">Authenticator Code</label>
                            <input type="text" name="code" maxlength="6" required class="w-full bg-white border border-gray-300 rounded-lg p-2 text-gray-900 text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>
                        <div>
                            <label class="block text-xs text-gray-700 mb-1">Current Password</label>
                            <input type="password" name="current_password" required class="w-full bg-white border border-gray-300 rounded-lg p-2 text-gray-900 text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>
                        <button class="bg-green-600 hover:bg-green-700 px-3 py-1.5 rounded-lg text-white text-xs transition-colors">Confirm & Enable TOTP</button>
                    </form>
                </div>
            @endif
        </div>

        <!-- Email OTP Method -->
        <div class="bg-gray-50 border border-gray-200 rounded-lg p-4 space-y-3">
            <div class="flex items-center justify-between">
                <h4 class="text-sm font-medium text-gray-900">Email One-Time Code</h4>
                @if($user->email_otp_enabled)
                    <span class="text-xs px-2 py-1 rounded-full bg-green-100 text-green-700">ENABLED</span>
                @endif
            </div>
            <p class="text-xs text-gray-600">Receive a 6-digit code via your email address each time you login.</p>
            @if(!$user->email_otp_enabled)
                <button onclick="openModal('enable-email-otp-modal')" class="bg-blue-600 hover:bg-blue-700 px-3 py-1.5 rounded-lg text-white text-xs transition-colors">Enable Email OTP</button>
            @endif
            @if($user->preferred_2fa_method === 'otp' && !$user->two_factor_enabled)
                <form method="POST" action="{{ route('2fa.confirm') }}" class="space-y-2">
                    @csrf
                    <div>
                        <label class="block text-xs text-gray-700 mb-1">Enter Email Code</label>
                        <input type="text" name="code" maxlength="6" required class="w-full bg-white border border-gray-300 rounded-lg p-2 text-gray-900 text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>
                    <div>
                        <label class="block text-xs text-gray-700 mb-1">Current Password</label>
                        <input type="password" name="current_password" required class="w-full bg-white border border-gray-300 rounded-lg p-2 text-gray-900 text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>
                    <div class="flex items-center justify-between">
                        <button class="bg-green-600 hover:bg-green-700 px-3 py-1.5 rounded-lg text-white text-xs transition-colors">Confirm & Enable Email OTP</button>
                        <button formaction="{{ route('2fa.challenge.resend') }}" formmethod="POST" class="text-blue-600 hover:text-blue-700 text-xs" onclick="event.preventDefault(); this.closest('form').submit();">Resend Code</button>
                    </div>
                </form>
            @endif
        </div>
    </div>
    @php($enabledCount = ($user->totp_enabled ? 1:0) + ($user->email_otp_enabled ? 1:0))
    <div class="bg-gray-50 border border-gray-200 rounded-lg p-4 space-y-4">
        <div class="flex items-center justify-between">
            <h4 class="text-sm font-medium text-gray-900">Status & Management</h4>
            <span class="text-xs px-2 py-1 rounded-full {{ $enabledCount ? 'bg-green-100 text-green-700':'bg-gray-100 text-gray-600' }}">{{ $enabledCount ? $enabledCount.' Active' : 'None Active' }}</span>
        </div>
        <ul class="text-xs text-gray-700 grid sm:grid-cols-2 gap-2">
            <li class="bg-white border border-gray-200 rounded-lg p-3 flex items-center justify-between">
                <span>TOTP</span>
                <span class="font-medium {{ $user->totp_enabled ? 'text-green-600' : 'text-red-500' }}">{{ $user->totp_enabled ? 'Enabled' : 'Disabled' }}</span>
            </li>
            <li class="bg-white border border-gray-200 rounded-lg p-3 flex items-center justify-between">
                <span>Email OTP</span>
                <span class="font-medium {{ $user->email_otp_enabled ? 'text-green-600' : 'text-red-500' }}">{{ $user->email_otp_enabled ? 'Enabled' : 'Disabled' }}</span>
            </li>
        </ul>
        <div class="grid md:grid-cols-{{ $enabledCount > 1 ? '2' : '1' }} gap-3">
            @if($user->totp_enabled)
                <button onclick="openModal('disable-totp-modal')" class="bg-red-600 hover:bg-red-700 text-white text-xs py-2 rounded-lg transition-colors">Disable TOTP</button>
            @endif
            @if($user->email_otp_enabled)
                <button onclick="openModal('disable-email-otp-modal')" class="bg-red-600 hover:bg-red-700 text-white text-xs py-2 rounded-lg transition-colors">Disable Email OTP</button>
            @endif
        </div>
        @if($enabledCount > 1)
            <button onclick="openModal('disable-all-2fa-modal')" class="bg-red-700 hover:bg-red-800 px-4 py-2 rounded-lg text-white text-xs w-full transition-colors">Disable All 2FA</button>
        @endif
    </div>
    <!-- Modals -->
    <div id="enable-totp-modal" class="hidden fixed inset-0 z-50 items-center justify-center bg-black/50">
        <div class="bg-white border border-gray-200 rounded-lg w-full max-w-sm p-6 space-y-4">
            <h3 class="text-lg font-semibold text-gray-900">Enable TOTP</h3>
            <form method="POST" action="{{ route('2fa.start') }}" class="space-y-4">
                @csrf
                <input type="hidden" name="method" value="totp" />
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Current Password</label>
                    <input type="password" name="current_password" required class="w-full bg-white border border-gray-300 rounded-lg p-2 text-gray-900 text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>
                <div class="flex gap-3">
                    <button class="bg-blue-600 hover:bg-blue-700 px-4 py-2 rounded-lg text-white text-sm transition-colors">Generate QR</button>
                    <button type="button" onclick="closeModal('enable-totp-modal')" class="text-sm text-gray-500 hover:text-gray-700">Cancel</button>
                </div>
            </form>
        </div>
    </div>
    <div id="enable-email-otp-modal" class="hidden fixed inset-0 z-50 items-center justify-center bg-black/50">
        <div class="bg-white border border-gray-200 rounded-lg w-full max-w-sm p-6 space-y-4">
            <h3 class="text-lg font-semibold text-gray-900">Enable Email OTP</h3>
            <form method="POST" action="{{ route('2fa.start') }}" class="space-y-4">
                @csrf
                <input type="hidden" name="method" value="otp" />
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Current Password</label>
                    <input type="password" name="current_password" required class="w-full bg-white border border-gray-300 rounded-lg p-2 text-gray-900 text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>
                <div class="flex gap-3">
                    <button class="bg-blue-600 hover:bg-blue-700 px-4 py-2 rounded-lg text-white text-sm transition-colors">Send Code</button>
                    <button type="button" onclick="closeModal('enable-email-otp-modal')" class="text-sm text-gray-500 hover:text-gray-700">Cancel</button>
                </div>
            </form>
        </div>
    </div>
    <div id="disable-totp-modal" class="hidden fixed inset-0 z-50 items-center justify-center bg-black/50">
        <div class="bg-white border border-gray-200 rounded-lg w-full max-w-sm p-6 space-y-4">
            <h3 class="text-lg font-semibold text-gray-900">Disable TOTP</h3>
            <form method="POST" action="{{ route('2fa.method.disable') }}" class="space-y-4">
                @csrf
                <input type="hidden" name="method" value="totp" />
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Password</label>
                    <input type="password" name="current_password" required class="w-full bg-white border border-gray-300 rounded-lg p-2 text-gray-900 text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">TOTP Code</label>
                    <input type="text" name="code" maxlength="6" required class="w-full bg-white border border-gray-300 rounded-lg p-2 text-gray-900 text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>
                <div class="flex gap-3">
                    <button class="bg-red-600 hover:bg-red-700 px-4 py-2 rounded-lg text-white text-sm transition-colors">Disable</button>
                    <button type="button" onclick="closeModal('disable-totp-modal')" class="text-sm text-gray-500 hover:text-gray-700">Cancel</button>
                </div>
            </form>
        </div>
    </div>
    <div id="disable-email-otp-modal" class="hidden fixed inset-0 z-50 items-center justify-center bg-black/50">
        <div class="bg-white border border-gray-200 rounded-lg w-full max-w-sm p-6 space-y-4">
            <h3 class="text-lg font-semibold text-gray-900">Disable Email OTP</h3>
            <form method="POST" action="{{ route('2fa.method.disable') }}" class="space-y-4" id="disable-email-otp-form">
                @csrf
                <input type="hidden" name="method" value="otp" />
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Password</label>
                    <input type="password" name="current_password" required class="w-full bg-white border border-gray-300 rounded-lg p-2 text-gray-900 text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>
                <div class="flex items-end gap-2">
                    <div class="flex-1">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Email Code</label>
                        <input type="text" name="code" maxlength="6" required class="w-full bg-white border border-gray-300 rounded-lg p-2 text-gray-900 text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>
                    <button type="button" onclick="sendMgmtEmailCode(this)" class="mt-auto text-xs px-3 py-2 rounded-lg bg-blue-600 hover:bg-blue-700 text-white transition-colors">Send Code</button>
                </div>
                <div class="flex gap-3">
                    <button class="bg-red-600 hover:bg-red-700 px-4 py-2 rounded-lg text-white text-sm transition-colors">Disable</button>
                    <button type="button" onclick="closeModal('disable-email-otp-modal')" class="text-sm text-gray-500 hover:text-gray-700">Cancel</button>
                </div>
            </form>
        </div>
    </div>
    <div id="disable-all-2fa-modal" class="hidden fixed inset-0 z-50 items-center justify-center bg-black/50">
        <div class="bg-white border border-gray-200 rounded-lg w-full max-w-sm p-6 space-y-4">
            <h3 class="text-lg font-semibold text-gray-900">Disable All 2FA Methods</h3>
            <form method="POST" action="{{ route('2fa.disable') }}" class="space-y-4" id="disable-all-2fa-form">
                @csrf
                <div class="grid gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Password</label>
                        <input type="password" name="current_password" required class="w-full bg-white border border-gray-300 rounded-lg p-2 text-gray-900 text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>
                    @if($user->totp_enabled)
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Authenticator Code</label>
                            <input type="text" name="totp_code" maxlength="6" {{ $user->totp_enabled ? 'required' : '' }} class="w-full bg-white border border-gray-300 rounded-lg p-2 text-gray-900 text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>
                    @endif
                    @if($user->email_otp_enabled)
                        <div class="flex items-end gap-2">
                            <div class="flex-1">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Email Code</label>
                                <input type="text" name="email_code" maxlength="6" {{ $user->email_otp_enabled ? 'required' : '' }} class="w-full bg-white border border-gray-300 rounded-lg p-2 text-gray-900 text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            </div>
                            <button type="button" onclick="sendMgmtEmailCode(this)" class="mt-auto text-xs px-3 py-2 rounded-lg bg-blue-600 hover:bg-blue-700 text-white transition-colors">Send Code</button>
                        </div>
                    @endif
                </div>
                <div class="flex gap-3">
                    <button class="bg-red-700 hover:bg-red-800 px-4 py-2 rounded-lg text-white text-sm transition-colors">Disable All</button>
                    <button type="button" onclick="closeModal('disable-all-2fa-modal')" class="text-sm text-gray-500 hover:text-gray-700">Cancel</button>
                </div>
            </form>
        </div>
    </div>
    <script>
        function openModal(id){ const m=document.getElementById(id); if(!m) return; m.classList.remove('hidden'); m.classList.add('flex'); }
        function closeModal(id){ const m=document.getElementById(id); if(!m) return; m.classList.add('hidden'); m.classList.remove('flex'); }
        document.addEventListener('keydown',e=>{ if(e.key==='Escape'){ document.querySelectorAll('[id$="-modal"]').forEach(el=> closeModal(el.id)); }});
        async function sendMgmtEmailCode(btn){
            btn.disabled = true; const original=btn.textContent; btn.textContent='Sending...';
            try {
                const res = await fetch("{{ route('2fa.management.email') }}", {method:'POST', headers:{'X-CSRF-TOKEN':'{{ csrf_token() }}','Accept':'application/json'}});
                if(!res.ok) throw new Error();
                btn.textContent='Sent';
                setTimeout(()=>{btn.textContent=original; btn.disabled=false;},4000);
            } catch(e){ btn.textContent='Error'; setTimeout(()=>{btn.textContent=original; btn.disabled=false;},3000); }
        }
    </script>
</div>
