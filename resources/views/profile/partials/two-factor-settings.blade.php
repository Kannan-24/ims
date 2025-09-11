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

    @php($enabledCount = ($user->totp_enabled ? 1:0) + ($user->email_otp_enabled ? 1:0))

    <div class="grid lg:grid-cols-3 gap-6">
        <!-- TOTP Method -->
        <div class="bg-white border border-gray-200 rounded-lg p-6 space-y-4 shadow-sm hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center space-x-3">
                    <div>
                        <h4 class="text-lg font-semibold text-gray-900">Authenticator App</h4>
                        <p class="text-sm text-gray-600">TOTP Authentication</p>
                    </div>
                </div>
                @if($user->totp_enabled)
                    <span class="flex items-center px-3 py-1.5 rounded-full bg-green-100 text-green-700 text-xs font-medium">
                        ENABLED
                    </span>
                @else
                    <span class="flex items-center px-3 py-1.5 rounded-full bg-gray-100 text-gray-600 text-xs font-medium">
                        DISABLED
                    </span>
                @endif
            </div>
            
            <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                <p class="text-sm text-gray-700 mb-4 leading-relaxed">
                    Scan a QR code with Google Authenticator, Authy, 1Password, etc. to generate 30-second codes.
                </p>
                @if(!$user->totp_enabled)
                    <button onclick="openModal('enable-totp-modal')" class="w-full bg-blue-600 hover:bg-blue-700 px-4 py-3 rounded-lg text-white font-medium transition-colors">
                        Start TOTP Setup
                    </button>
                @endif
                
                @if ($user->preferred_2fa_method === 'totp' && !$user->two_factor_enabled && !empty($secret))
                    <div class="space-y-4 mt-4">
                        <div class="bg-white rounded-lg p-4 border border-gray-200">
                            <p class="text-sm font-medium text-gray-900 mb-3">
                                Scan QR or enter secret manually:
                            </p>
                            <div class="bg-gray-50 p-3 rounded border border-gray-200 font-mono text-xs break-all text-gray-700 mb-4">
                                {{ $secret }}
                            </div>
                            <div class="flex justify-center">
                                <img class="bg-white p-3 rounded-lg border border-gray-200 shadow-sm" 
                                     src="https://api.qrserver.com/v1/create-qr-code/?size=180x180&data={{ urlencode($otpauth) }}" 
                                     alt="QR Code">
                            </div>
                        </div>
                        <form method="POST" action="{{ route('2fa.confirm') }}" class="space-y-3">
                            @csrf
                            <div>
                                <label class="block text-sm font-medium text-gray-900 mb-2">
                                    Authenticator Code
                                </label>
                                <input type="text" name="code" maxlength="6" required 
                                       class="w-full bg-white border border-gray-300 rounded-lg p-3 text-gray-900 text-center text-lg font-mono tracking-wider focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-900 mb-2">
                                    Current Password
                                </label>
                                <input type="password" name="current_password" required 
                                       class="w-full bg-white border border-gray-300 rounded-lg p-3 text-gray-900 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            </div>
                            <button class="w-full bg-green-600 hover:bg-green-700 px-4 py-3 rounded-lg text-white font-medium transition-colors">
                                Confirm & Enable TOTP
                            </button>
                        </form>
                    </div>
                @endif
            </div>
        </div>

        <!-- Email OTP Method -->
        <div class="bg-white border border-gray-200 rounded-lg p-6 space-y-4 shadow-sm hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center space-x-3">
                    <div>
                        <h4 class="text-lg font-semibold text-gray-900">Email Verification</h4>
                        <p class="text-sm text-gray-600">One-Time Code</p>
                    </div>
                </div>
                @if($user->email_otp_enabled)
                    <span class="flex items-center px-3 py-1.5 rounded-full bg-green-100 text-green-700 text-xs font-medium">
                        ENABLED
                    </span>
                @else
                    <span class="flex items-center px-3 py-1.5 rounded-full bg-gray-100 text-gray-600 text-xs font-medium">
                        DISABLED
                    </span>
                @endif
            </div>
            
            <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                <p class="text-sm text-gray-700 mb-4 leading-relaxed">
                    Receive a 6-digit verification code via your email address each time you login.
                </p>
                @if(!$user->email_otp_enabled)
                    <button onclick="openModal('enable-email-otp-modal')" class="w-full bg-blue-600 hover:bg-blue-700 px-4 py-3 rounded-lg text-white font-medium transition-colors">
                        Enable Email OTP
                    </button>
                @endif
                
                @if($user->preferred_2fa_method === 'otp' && !$user->two_factor_enabled)
                    <form method="POST" action="{{ route('2fa.confirm') }}" class="space-y-3 mt-4">
                        @csrf
                        <div>
                            <label class="block text-sm font-medium text-gray-900 mb-2">
                                Enter Email Code
                            </label>
                            <input type="text" name="code" maxlength="6" required 
                                   class="w-full bg-white border border-gray-300 rounded-lg p-3 text-gray-900 text-center text-lg font-mono tracking-wider focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-900 mb-2">
                                Current Password
                            </label>
                            <input type="password" name="current_password" required 
                                   class="w-full bg-white border border-gray-300 rounded-lg p-3 text-gray-900 focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                        </div>
                        <div class="flex flex-col sm:flex-row gap-3">
                            <button class="flex-1 bg-green-600 hover:bg-green-700 px-4 py-3 rounded-lg text-white font-medium transition-colors">
                                Confirm & Enable
                            </button>
                            <button formaction="{{ route('2fa.challenge.resend') }}" formmethod="POST" 
                                    class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-lg font-medium transition-colors" 
                                    onclick="event.preventDefault(); this.closest('form').submit();">
                                Resend Code
                            </button>
                        </div>
                    </form>
                @endif
            </div>
        </div>

        <!-- Status & Management Section -->
        <div class="bg-white border border-gray-200 rounded-lg p-6 space-y-6 shadow-sm">
            <div class="text-center">
                <h4 class="text-xl font-bold text-gray-900 mb-2">Security Status</h4>
                <p class="text-sm text-gray-600 mb-4">Monitor and manage your 2FA methods</p>
                <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-semibold {{ $enabledCount ? 'bg-green-100 text-green-800 border border-green-200':'bg-red-100 text-red-800 border border-red-200' }}">
                    @if($enabledCount)
                        {{ $enabledCount }} Method{{ $enabledCount > 1 ? 's' : '' }} Active
                    @else
                        No Protection Active
                    @endif
                </span>
            </div>

            <!-- Security Methods Status -->
            <div class="grid grid-cols-2 gap-4">
                <div class="bg-gray-50 rounded-lg p-4 border {{ $user->totp_enabled ? 'border-green-200' : 'border-gray-200' }}">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="font-medium text-gray-900">Authenticator</p>
                            <p class="text-xs text-gray-500">TOTP App</p>
                        </div>
                        <span class="text-sm font-bold {{ $user->totp_enabled ? 'text-green-600' : 'text-gray-500' }}">
                            {{ $user->totp_enabled ? 'ON' : 'OFF' }}
                        </span>
                    </div>
                </div>
                
                <div class="bg-gray-50 rounded-lg p-4 border {{ $user->email_otp_enabled ? 'border-green-200' : 'border-gray-200' }}">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="font-medium text-gray-900">Email Code</p>
                            <p class="text-xs text-gray-500">OTP via Email</p>
                        </div>
                        <span class="text-sm font-bold {{ $user->email_otp_enabled ? 'text-green-600' : 'text-gray-500' }}">
                            {{ $user->email_otp_enabled ? 'ON' : 'OFF' }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            @if($user->totp_enabled || $user->email_otp_enabled)
                <div class="border-t border-gray-200 pt-6">
                    <h5 class="text-sm font-semibold text-gray-900 mb-4">Management Actions</h5>
                    <div class="grid grid-cols-1 gap-3">
                        @if($user->totp_enabled)
                            <button onclick="openModal('disable-totp-modal')" 
                                    class="flex items-center justify-center bg-red-600 hover:bg-red-700 text-white px-4 py-3 rounded-lg font-medium transition-colors">
                                Disable Authenticator
                            </button>
                        @endif
                        @if($user->email_otp_enabled)
                            <button onclick="openModal('disable-email-otp-modal')" 
                                    class="flex items-center justify-center bg-red-600 hover:bg-red-700 text-white px-4 py-3 rounded-lg font-medium transition-colors">
                                Disable Email OTP
                            </button>
                        @endif
                        @if($enabledCount > 1)
                            <button onclick="openModal('disable-all-2fa-modal')" 
                                    class="w-full mt-2 bg-red-700 hover:bg-red-800 px-4 py-3 rounded-lg text-white font-semibold transition-colors">
                                Disable All 2FA Methods
                            </button>
                        @endif
                    </div>
                </div>
            @endif
        </div>
    </div>
    <!-- Light Theme Modals -->
    <div id="enable-totp-modal" class="hidden fixed inset-0 z-50 items-center justify-center bg-black/50">
        <div class="bg-white rounded-lg w-full max-w-md p-6 space-y-4 shadow-lg">
            <div class="text-center mb-4">
                <h3 class="text-lg font-semibold text-gray-900">Enable TOTP</h3>
                <p class="text-sm text-gray-600 mt-1">Set up authenticator app protection</p>
            </div>
            <form method="POST" action="{{ route('2fa.start') }}" class="space-y-4">
                @csrf
                <input type="hidden" name="method" value="totp" />
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Current Password</label>
                    <input type="password" name="current_password" required 
                           class="w-full border border-gray-300 rounded-lg p-3 text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>
                <div class="flex gap-3">
                    <button class="flex-1 bg-blue-600 hover:bg-blue-700 px-4 py-2 rounded-lg text-white text-sm font-medium transition-colors">
                        Generate QR Code
                    </button>
                    <button type="button" onclick="closeModal('enable-totp-modal')" 
                            class="px-4 py-2 text-gray-600 hover:text-gray-800 text-sm font-medium">Cancel</button>
                </div>
            </form>
        </div>
    </div>

    <div id="enable-email-otp-modal" class="hidden fixed inset-0 z-50 items-center justify-center bg-black/50">
        <div class="bg-white rounded-lg w-full max-w-md p-6 space-y-4 shadow-lg">
            <div class="text-center mb-4">
                <h3 class="text-lg font-semibold text-gray-900">Enable Email OTP</h3>
                <p class="text-sm text-gray-600 mt-1">Set up email verification protection</p>
            </div>
            <form method="POST" action="{{ route('2fa.start') }}" class="space-y-4">
                @csrf
                <input type="hidden" name="method" value="otp" />
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Current Password</label>
                    <input type="password" name="current_password" required 
                           class="w-full border border-gray-300 rounded-lg p-3 text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>
                <div class="flex gap-3">
                    <button class="flex-1 bg-blue-600 hover:bg-blue-700 px-4 py-2 rounded-lg text-white text-sm font-medium transition-colors">
                        Send Code
                    </button>
                    <button type="button" onclick="closeModal('enable-email-otp-modal')" 
                            class="px-4 py-2 text-gray-600 hover:text-gray-800 text-sm font-medium">Cancel</button>
                </div>
            </form>
        </div>
    </div>

    <div id="disable-totp-modal" class="hidden fixed inset-0 z-50 items-center justify-center bg-black/50">
        <div class="bg-white rounded-lg w-full max-w-md p-6 space-y-4 shadow-lg">
            <div class="text-center mb-4">
                <h3 class="text-lg font-semibold text-gray-900">Disable TOTP</h3>
                <p class="text-sm text-gray-600 mt-1">Remove authenticator app protection</p>
            </div>
            <form method="POST" action="{{ route('2fa.method.disable') }}" class="space-y-4">
                @csrf
                <input type="hidden" name="method" value="totp" />
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Password</label>
                    <input type="password" name="current_password" required 
                           class="w-full border border-gray-300 rounded-lg p-3 text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">TOTP Code</label>
                    <input type="text" name="code" maxlength="6" required 
                           class="w-full border border-gray-300 rounded-lg p-3 text-center text-sm font-mono tracking-wider focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>
                <div class="flex gap-3">
                    <button class="flex-1 bg-red-600 hover:bg-red-700 px-4 py-2 rounded-lg text-white text-sm font-medium transition-colors">
                        Disable
                    </button>
                    <button type="button" onclick="closeModal('disable-totp-modal')" 
                            class="px-4 py-2 text-gray-600 hover:text-gray-800 text-sm font-medium">Cancel</button>
                </div>
            </form>
        </div>
    </div>

    <div id="disable-email-otp-modal" class="hidden fixed inset-0 z-50 items-center justify-center bg-black/50">
        <div class="bg-white rounded-lg w-full max-w-md p-6 space-y-4 shadow-lg">
            <div class="text-center mb-4">
                <h3 class="text-lg font-semibold text-gray-900">Disable Email OTP</h3>
                <p class="text-sm text-gray-600 mt-1">Remove email verification protection</p>
            </div>
            <form method="POST" action="{{ route('2fa.method.disable') }}" class="space-y-4" id="disable-email-otp-form">
                @csrf
                <input type="hidden" name="method" value="otp" />
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Password</label>
                    <input type="password" name="current_password" required 
                           class="w-full border border-gray-300 rounded-lg p-3 text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>
                <div class="flex gap-2">
                    <div class="flex-1">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Email Code</label>
                        <input type="text" name="code" maxlength="6" required 
                               class="w-full border border-gray-300 rounded-lg p-3 text-center text-sm font-mono tracking-wider focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>
                    <div class="flex flex-col justify-end">
                        <button type="button" onclick="sendMgmtEmailCode(this)" 
                                class="px-3 py-3 rounded-lg bg-gray-600 hover:bg-gray-700 text-white text-xs font-medium transition-colors whitespace-nowrap">
                            Send
                        </button>
                    </div>
                </div>
                <div class="flex gap-3">
                    <button class="flex-1 bg-red-600 hover:bg-red-700 px-4 py-2 rounded-lg text-white text-sm font-medium transition-colors">
                        Disable
                    </button>
                    <button type="button" onclick="closeModal('disable-email-otp-modal')" 
                            class="px-4 py-2 text-gray-600 hover:text-gray-800 text-sm font-medium">Cancel</button>
                </div>
            </form>
        </div>
    </div>

    <div id="disable-all-2fa-modal" class="hidden fixed inset-0 z-50 items-center justify-center bg-black/50">
        <div class="bg-white rounded-lg w-full max-w-lg p-6 space-y-4 shadow-lg">
            <div class="text-center mb-4">
                <h3 class="text-lg font-semibold text-gray-900">Disable All 2FA Methods</h3>
                <p class="text-sm text-gray-600 mt-1">This will remove all two-factor authentication protection</p>
            </div>
            <form method="POST" action="{{ route('2fa.disable') }}" class="space-y-4" id="disable-all-2fa-form">
                @csrf
                <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                    <div class="flex items-start">
                        <i class="fas fa-exclamation-triangle text-red-500 mt-0.5 mr-3"></i>
                        <div class="text-sm text-red-800">
                            <p class="font-medium mb-1">Security Warning</p>
                            <p>Disabling all 2FA methods will make your account less secure. Please verify with all active methods.</p>
                        </div>
                    </div>
                </div>
                
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Password</label>
                        <input type="password" name="current_password" required 
                               class="w-full border border-gray-300 rounded-lg p-3 text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>
                    @if($user->totp_enabled)
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Authenticator Code</label>
                            <input type="text" name="totp_code" maxlength="6" {{ $user->totp_enabled ? 'required' : '' }} 
                                   class="w-full border border-gray-300 rounded-lg p-3 text-center text-sm font-mono tracking-wider focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>
                    @endif
                    @if($user->email_otp_enabled)
                        <div class="flex gap-2">
                            <div class="flex-1">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Email Code</label>
                                <input type="text" name="email_code" maxlength="6" {{ $user->email_otp_enabled ? 'required' : '' }} 
                                       class="w-full border border-gray-300 rounded-lg p-3 text-center text-sm font-mono tracking-wider focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            </div>
                            <div class="flex flex-col justify-end">
                                <button type="button" onclick="sendMgmtEmailCode(this)" 
                                        class="px-3 py-3 rounded-lg bg-gray-600 hover:bg-gray-700 text-white text-xs font-medium transition-colors whitespace-nowrap">
                                    Send
                                </button>
                            </div>
                        </div>
                    @endif
                </div>
                
                <div class="flex gap-3">
                    <button class="flex-1 bg-red-700 hover:bg-red-800 px-4 py-3 rounded-lg text-white font-semibold transition-colors">
                        Disable All Security
                    </button>
                    <button type="button" onclick="closeModal('disable-all-2fa-modal')" 
                            class="px-4 py-3 text-gray-600 hover:text-gray-800 font-medium">Cancel</button>
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
