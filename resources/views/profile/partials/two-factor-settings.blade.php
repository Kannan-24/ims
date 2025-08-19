<div class="space-y-6">
    <h2 class="text-xl font-semibold text-white">Two-Factor Authentication</h2>
    <p class="text-sm text-gray-400">Add an extra layer of security to your account.</p>

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

    @if (session('success'))
        <div class="text-green-400 text-sm">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="text-red-400 text-sm">{{ session('error') }}</div>
    @endif

    <div class="grid md:grid-cols-2 gap-6">
        <!-- TOTP Method -->
        <div class="bg-gray-900 p-5 rounded border border-gray-700 space-y-4">
            <div class="flex items-center justify-between">
                <h3 class="text-sm font-semibold text-white">Authenticator App (TOTP)</h3>
                @if($user->totp_enabled)
                    <span class="text-xs px-2 py-1 rounded bg-green-700 text-green-100">ENABLED</span>
                @endif
            </div>
            <p class="text-xs text-gray-400">Scan a QR code with Google Authenticator, Authy, 1Password, etc. to generate 30s codes.</p>
            @if(!$user->totp_enabled)
                <button onclick="openModal('enable-totp-modal')" class="bg-blue-600 hover:bg-blue-700 px-3 py-1.5 rounded text-white text-xs">Start TOTP Setup</button>
            @endif
            @if ($user->preferred_2fa_method === 'totp' && !$user->two_factor_enabled && !empty($secret))
                <div class="space-y-3 text-xs">
                    <p class="text-gray-300">Scan the QR or enter the secret manually:</p>
                    <p class="font-mono break-all bg-gray-800 p-2 rounded text-[11px]">{{ $secret }}</p>
                    <img class="bg-white p-2 rounded" src="https://api.qrserver.com/v1/create-qr-code/?size=160x160&data={{ urlencode($otpauth) }}" alt="QR Code">
                    <form method="POST" action="{{ route('2fa.confirm') }}" class="space-y-2">
                        @csrf
                        <div>
                            <label class="block text-xs text-gray-300 mb-1">Authenticator Code</label>
                            <input type="text" name="code" maxlength="6" required class="w-full bg-gray-800 border border-gray-700 rounded p-2 text-white text-sm">
                        </div>
                        <div>
                            <label class="block text-xs text-gray-300 mb-1">Current Password</label>
                            <input type="password" name="current_password" required class="w-full bg-gray-800 border border-gray-700 rounded p-2 text-white text-sm">
                        </div>
                        <button class="bg-green-600 hover:bg-green-700 px-3 py-1.5 rounded text-white text-xs">Confirm & Enable TOTP</button>
                    </form>
                </div>
            @endif
        </div>

        <!-- Email OTP Method -->
        <div class="bg-gray-900 p-5 rounded border border-gray-700 space-y-4">
            <div class="flex items-center justify-between">
                <h3 class="text-sm font-semibold text-white">Email One-Time Code</h3>
                @if($user->email_otp_enabled)
                    <span class="text-xs px-2 py-1 rounded bg-green-700 text-green-100">ENABLED</span>
                @endif
            </div>
            <p class="text-xs text-gray-400">Receive a 6-digit code via your email address each time you login.</p>
            @if(!$user->email_otp_enabled)
                <button onclick="openModal('enable-email-otp-modal')" class="bg-blue-600 hover:bg-blue-700 px-3 py-1.5 rounded text-white text-xs">Enable Email OTP</button>
            @endif
            @if($user->preferred_2fa_method === 'otp' && !$user->two_factor_enabled)
                <form method="POST" action="{{ route('2fa.confirm') }}" class="space-y-2">
                    @csrf
                    <div>
                        <label class="block text-xs text-gray-300 mb-1">Enter Email Code</label>
                        <input type="text" name="code" maxlength="6" required class="w-full bg-gray-800 border border-gray-700 rounded p-2 text-white text-sm">
                    </div>
                    <div>
                        <label class="block text-xs text-gray-300 mb-1">Current Password</label>
                        <input type="password" name="current_password" required class="w-full bg-gray-800 border border-gray-700 rounded p-2 text-white text-sm">
                    </div>
                    <div class="flex items-center justify-between">
                        <button class="bg-green-600 hover:bg-green-700 px-3 py-1.5 rounded text-white text-xs">Confirm & Enable Email OTP</button>
                        <button formaction="{{ route('2fa.challenge.resend') }}" formmethod="POST" class="text-blue-400 hover:text-blue-300 text-[11px]" onclick="event.preventDefault(); this.closest('form').submit();">Resend Code</button>
                    </div>
                </form>
            @endif
        </div>
    </div>
    @php($enabledCount = ($user->totp_enabled ? 1:0) + ($user->email_otp_enabled ? 1:0))
    <div class="bg-gray-900 p-5 rounded border border-gray-700 space-y-4 mt-6">
        <div class="flex items-center justify-between">
            <h3 class="text-sm font-semibold text-white">Status & Management</h3>
            <span class="text-[11px] px-2 py-0.5 rounded {{ $enabledCount ? 'bg-green-700 text-green-100':'bg-gray-700 text-gray-300' }}">{{ $enabledCount ? $enabledCount.' Active' : 'None Active' }}</span>
        </div>
        <ul class="text-xs text-gray-300 grid sm:grid-cols-2 gap-2">
            <li class="bg-gray-800 rounded p-2 flex items-center justify-between">
                <span>TOTP</span>
                <span class="font-medium {{ $user->totp_enabled ? 'text-green-400' : 'text-red-400' }}">{{ $user->totp_enabled ? 'Enabled' : 'Disabled' }}</span>
            </li>
            <li class="bg-gray-800 rounded p-2 flex items-center justify-between">
                <span>Email OTP</span>
                <span class="font-medium {{ $user->email_otp_enabled ? 'text-green-400' : 'text-red-400' }}">{{ $user->email_otp_enabled ? 'Enabled' : 'Disabled' }}</span>
            </li>
        </ul>
        <div class="grid md:grid-cols-{{ $enabledCount > 1 ? '2' : '1' }} gap-4">
            @if($user->totp_enabled)
                <button onclick="openModal('disable-totp-modal')" class="bg-red-600 hover:bg-red-700 text-white text-[11px] py-2 rounded">Disable TOTP</button>
            @endif
            @if($user->email_otp_enabled)
                <button onclick="openModal('disable-email-otp-modal')" class="bg-red-600 hover:bg-red-700 text-white text-[11px] py-2 rounded">Disable Email OTP</button>
            @endif
        </div>
        @if($enabledCount > 1)
            <button onclick="openModal('disable-all-2fa-modal')" class="bg-red-700 hover:bg-red-800 px-4 py-2 rounded text-white text-xs w-full mt-4">Disable All 2FA</button>
        @endif
    </div>
    <!-- Modals -->
    <div id="enable-totp-modal" class="hidden fixed inset-0 z-50 items-center justify-center bg-black/60">
        <div class="bg-gray-900 border border-gray-700 rounded-lg w-full max-w-sm p-6 space-y-5">
            <h3 class="text-sm font-semibold text-white">Enable TOTP</h3>
            <form method="POST" action="{{ route('2fa.start') }}" class="space-y-4">
                @csrf
                <input type="hidden" name="method" value="totp" />
                <div>
                    <label class="block text-xs text-gray-300 mb-1">Current Password</label>
                    <input type="password" name="current_password" required class="w-full bg-gray-800 border border-gray-700 rounded p-2 text-white text-sm">
                </div>
                <div class="flex gap-3">
                    <button class="bg-blue-600 hover:bg-blue-700 px-4 py-2 rounded text-white text-xs">Generate QR</button>
                    <button type="button" onclick="closeModal('enable-totp-modal')" class="text-xs text-gray-400 hover:text-gray-200">Cancel</button>
                </div>
            </form>
        </div>
    </div>
    <div id="enable-email-otp-modal" class="hidden fixed inset-0 z-50 items-center justify-center bg-black/60">
        <div class="bg-gray-900 border border-gray-700 rounded-lg w-full max-w-sm p-6 space-y-5">
            <h3 class="text-sm font-semibold text-white">Enable Email OTP</h3>
            <form method="POST" action="{{ route('2fa.start') }}" class="space-y-4">
                @csrf
                <input type="hidden" name="method" value="otp" />
                <div>
                    <label class="block text-xs text-gray-300 mb-1">Current Password</label>
                    <input type="password" name="current_password" required class="w-full bg-gray-800 border border-gray-700 rounded p-2 text-white text-sm">
                </div>
                <div class="flex gap-3">
                    <button class="bg-blue-600 hover:bg-blue-700 px-4 py-2 rounded text-white text-xs">Send Code</button>
                    <button type="button" onclick="closeModal('enable-email-otp-modal')" class="text-xs text-gray-400 hover:text-gray-200">Cancel</button>
                </div>
            </form>
        </div>
    </div>
    <div id="disable-totp-modal" class="hidden fixed inset-0 z-50 items-center justify-center bg-black/60">
        <div class="bg-gray-900 border border-gray-700 rounded-lg w-full max-w-sm p-6 space-y-5">
            <h3 class="text-sm font-semibold text-white">Disable TOTP</h3>
            <form method="POST" action="{{ route('2fa.method.disable') }}" class="space-y-4">
                @csrf
                <input type="hidden" name="method" value="totp" />
                <div>
                    <label class="block text-xs text-gray-300 mb-1">Password</label>
                    <input type="password" name="current_password" required class="w-full bg-gray-800 border border-gray-700 rounded p-2 text-white text-sm">
                </div>
                <div>
                    <label class="block text-xs text-gray-300 mb-1">TOTP Code</label>
                    <input type="text" name="code" maxlength="6" required class="w-full bg-gray-800 border border-gray-700 rounded p-2 text-white text-sm">
                </div>
                <div class="flex gap-3">
                    <button class="bg-red-600 hover:bg-red-700 px-4 py-2 rounded text-white text-xs">Disable</button>
                    <button type="button" onclick="closeModal('disable-totp-modal')" class="text-xs text-gray-400 hover:text-gray-200">Cancel</button>
                </div>
            </form>
        </div>
    </div>
    <div id="disable-email-otp-modal" class="hidden fixed inset-0 z-50 items-center justify-center bg-black/60">
        <div class="bg-gray-900 border border-gray-700 rounded-lg w-full max-w-sm p-6 space-y-5">
            <h3 class="text-sm font-semibold text-white">Disable Email OTP</h3>
            <form method="POST" action="{{ route('2fa.method.disable') }}" class="space-y-4" id="disable-email-otp-form">
                @csrf
                <input type="hidden" name="method" value="otp" />
                <div>
                    <label class="block text-xs text-gray-300 mb-1">Password</label>
                    <input type="password" name="current_password" required class="w-full bg-gray-800 border border-gray-700 rounded p-2 text-white text-sm">
                </div>
                <div class="flex items-end gap-2">
                    <div class="flex-1">
                        <label class="block text-xs text-gray-300 mb-1">Email Code</label>
                        <input type="text" name="code" maxlength="6" required class="w-full bg-gray-800 border border-gray-700 rounded p-2 text-white text-sm">
                    </div>
                    <button type="button" onclick="sendMgmtEmailCode(this)" class="mt-auto text-[11px] px-2 py-1 rounded bg-blue-600 hover:bg-blue-700 text-white">Send Code</button>
                </div>
                <div class="flex gap-3">
                    <button class="bg-red-600 hover:bg-red-700 px-4 py-2 rounded text-white text-xs">Disable</button>
                    <button type="button" onclick="closeModal('disable-email-otp-modal')" class="text-xs text-gray-400 hover:text-gray-200">Cancel</button>
                </div>
            </form>
        </div>
    </div>
    <div id="disable-all-2fa-modal" class="hidden fixed inset-0 z-50 items-center justify-center bg-black/60">
        <div class="bg-gray-900 border border-gray-700 rounded-lg w-full max-w-sm p-6 space-y-5">
            <h3 class="text-sm font-semibold text-white">Disable All 2FA Methods</h3>
            <form method="POST" action="{{ route('2fa.disable') }}" class="space-y-4" id="disable-all-2fa-form">
                @csrf
                <div class="grid gap-4">
                    <div>
                        <label class="block text-xs text-gray-300 mb-1">Password</label>
                        <input type="password" name="current_password" required class="w-full bg-gray-800 border border-gray-700 rounded p-2 text-white text-sm">
                    </div>
                    @if($user->totp_enabled)
                        <div>
                            <label class="block text-xs text-gray-300 mb-1">Authenticator Code</label>
                            <input type="text" name="totp_code" maxlength="6" {{ $user->totp_enabled ? 'required' : '' }} class="w-full bg-gray-800 border border-gray-700 rounded p-2 text-white text-sm">
                        </div>
                    @endif
                    @if($user->email_otp_enabled)
                        <div class="flex items-end gap-2">
                            <div class="flex-1">
                                <label class="block text-xs text-gray-300 mb-1">Email Code</label>
                                <input type="text" name="email_code" maxlength="6" {{ $user->email_otp_enabled ? 'required' : '' }} class="w-full bg-gray-800 border border-gray-700 rounded p-2 text-white text-sm">
                            </div>
                            <button type="button" onclick="sendMgmtEmailCode(this)" class="mt-auto text-[11px] px-2 py-1 rounded bg-blue-600 hover:bg-blue-700 text-white">Send Code</button>
                        </div>
                    @endif
                </div>
                <div class="flex gap-3">
                    <button class="bg-red-700 hover:bg-red-800 px-4 py-2 rounded text-white text-xs">Disable All</button>
                    <button type="button" onclick="closeModal('disable-all-2fa-modal')" class="text-xs text-gray-400 hover:text-gray-200">Cancel</button>
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
