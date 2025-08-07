<x-guest-layout>
    <x-slot name="title">
        {{ __('Login') }} - {{ config('app.name', 'ATMS') }}
    </x-slot>

    <!-- Right Section: Login Form -->
    <div class="max-w-md mx-auto">
        <div class="mb-8">
            <h3 class="text-3xl font-bold text-gray-100">Welcome Back</h3>
            <p class="mt-2 text-sm text-gray-400">
                Sign in to manage invoices, quotations, and customer details effortlessly.
            </p>
        </div> 

        <form method="POST" action="{{ route('login') }}" class="space-y-4">
            @csrf

            <!-- Email Address -->
            <div>
                <label class="block mb-2 text-sm text-gray-200">Email</label>
                <div class="relative flex items-center">
                    <input id="email" name="email" type="email" required
                        class="w-full py-3 pl-4 pr-10 text-sm text-gray-200 bg-gray-800 border border-gray-700 rounded-lg outline-blue-500 placeholder-gray-500"
                        placeholder="Enter your email" value="{{ old('email') }}" autofocus autocomplete="username" />
                </div>
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <!-- Password -->
            <div class="relative mt-4" x-data="{ show: false }">
                <label class="block text-sm mb-2 font-medium text-gray-200" for="password">Password</label>
                <input
                    class="w-full py-3 pl-4 pr-10 text-sm text-gray-200 bg-gray-800 border border-gray-700 rounded-lg outline-blue-500 placeholder-gray-500"
                    id="password" x-bind:type="show ? 'text' : 'password'" name="password" required="required"
                    autocomplete="current-password" placeholder="Enter your password">
                <span class="absolute w-5 h-5" id="password-toggle" @click="show = !show"
                    style="top: 55%; right: 15px;">
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

            <!-- Remember Me & Forgot Password -->
            <div class="flex flex-wrap items-center justify-between gap-4">
                <div class="flex items-center">
                    <input id="remember_me" type="checkbox"
                        class="w-4 h-4 text-blue-500 bg-gray-800 border-gray-700 rounded shrink-0 focus:ring-blue-500"
                        name="remember">
                    <label for="remember_me" class="block ml-3 text-sm text-gray-200">Remember me</label>
                </div>

                <div class="text-sm">
                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="font-semibold text-blue-500 hover:underline">
                            Forgot your password?
                        </a>
                    @endif
                </div>
            </div>

            <!-- Submit Button -->
            <div class="!mt-8">
                <button type="submit"
                    class="w-full shadow-xl py-2.5 px-4 text-sm tracking-wide rounded-lg text-white bg-blue-600 hover:bg-blue-700 focus:outline-none">
                    Sign in
                </button>
            </div>

            <!-- Divider -->
            <div class="!mt-8">
                <div class="relative">
                    <div class="absolute inset-0 flex items-center">
                        <div class="w-full border-t border-gray-700"></div>
                    </div>
                    <div class="relative flex justify-center text-sm">
                        <span class="px-2 text-gray-400 bg-gray-900">Or continue with</span>
                    </div>
                </div>
            </div>

            <!-- Google Login Button -->
            <div class="!mt-6">
                <button type="button" id="googleLoginBtn"
                    class="w-full flex justify-center items-center gap-3 py-2.5 px-4 text-sm tracking-wide rounded-lg text-gray-200 bg-gray-800 border border-gray-700 hover:bg-gray-700 focus:outline-none transition-colors duration-200 disabled:opacity-50 disabled:cursor-not-allowed">
                    <svg class="w-5 h-5" viewBox="0 0 24 24">
                        <path fill="#4285F4"
                            d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" />
                        <path fill="#34A853"
                            d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" />
                        <path fill="#FBBC05"
                            d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" />
                        <path fill="#EA4335"
                            d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" />
                    </svg>
                    <span id="googleBtnText">Sign in with Google</span>
                    <div id="googleLoader" class="hidden animate-spin rounded-full h-4 w-4 border-b-2 border-white"></div>
                </button>
            </div>
        </form>

        <!-- Success/Error Alert -->
        <div id="alertMessage" class="hidden mt-4 p-4 rounded-lg">
            <div class="flex items-center">
                <div id="alertIcon" class="flex-shrink-0 w-5 h-5 mr-3"></div>
                <div id="alertText" class="text-sm font-medium"></div>
            </div>
        </div>
    </div>

    <!-- Google Login Popup Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const googleLoginBtn = document.getElementById('googleLoginBtn');
            const googleBtnText = document.getElementById('googleBtnText');
            const googleLoader = document.getElementById('googleLoader');
            const alertMessage = document.getElementById('alertMessage');
            const alertIcon = document.getElementById('alertIcon');
            const alertText = document.getElementById('alertText');

            function showAlert(type, message) {
                alertMessage.className = `mt-4 p-4 rounded-lg ${type === 'success' ? 'bg-green-100 border border-green-400 text-green-700' : 'bg-red-100 border border-red-400 text-red-700'}`;
                
                alertIcon.innerHTML = type === 'success' 
                    ? '<svg class="w-5 h-5 text-green-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>'
                    : '<svg class="w-5 h-5 text-red-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path></svg>';
                
                alertText.textContent = message;
                alertMessage.classList.remove('hidden');
                
                // Auto hide success message after 3 seconds
                if (type === 'success') {
                    setTimeout(() => {
                        alertMessage.classList.add('hidden');
                    }, 3000);
                }
            }

            function setButtonLoading(loading) {
                googleLoginBtn.disabled = loading;
                if (loading) {
                    googleBtnText.textContent = 'Signing in...';
                    googleLoader.classList.remove('hidden');
                } else {
                    googleBtnText.textContent = 'Sign in with Google';
                    googleLoader.classList.add('hidden');
                }
            }

            googleLoginBtn.addEventListener('click', function() {
                // Hide any previous alerts
                alertMessage.classList.add('hidden');
                
                // Show loading state
                setButtonLoading(true);

                // Open Google OAuth popup
                const popup = window.open(
                    '{{ route("auth.google") }}?popup=1',
                    'google-login',
                    'width=500,height=600,scrollbars=yes,resizable=yes,status=yes,location=yes,toolbar=no,menubar=no'
                );

                // Check if popup was blocked
                if (!popup || popup.closed || typeof popup.closed == 'undefined') {
                    setButtonLoading(false);
                    showAlert('error', 'Popup was blocked. Please allow popups and try again.');
                    return;
                }

                // Poll for popup closure
                const checkClosed = setInterval(function() {
                    if (popup.closed) {
                        clearInterval(checkClosed);
                        setButtonLoading(false);
                        
                        // Check if there's a message from the popup
                        const urlParams = new URLSearchParams(window.location.search);
                        if (urlParams.has('login_success')) {
                            showAlert('success', 'Successfully logged in with Google!');
                            setTimeout(() => {
                                window.location.href = '{{ route("dashboard") }}';
                            }, 1500);
                        } else if (urlParams.has('login_error')) {
                            showAlert('error', decodeURIComponent(urlParams.get('login_error')));
                        }
                    }
                }, 1000);

                // Handle message from popup
                window.addEventListener('message', function(event) {
                    if (event.origin !== window.location.origin) return;
                    
                    if (event.data.type === 'GOOGLE_LOGIN_RESULT') {
                        popup.close();
                        clearInterval(checkClosed);
                        setButtonLoading(false);
                        
                        if (event.data.success) {
                            showAlert('success', event.data.message);
                            setTimeout(() => {
                                window.location.href = event.data.redirect;
                            }, 1500);
                        } else {
                            showAlert('error', event.data.message);
                        }
                    }
                });
            });
        });
    </script>
</x-guest-layout>
