<x-guest-layout>
    <x-slot name="title">
        {{ __('Login') }} - {{ config('app.name', 'ATMS') }}
    </x-slot>

    <!-- Login Form -->
    <div class="w-full" x-data="loginFlow()" x-init="init()">
        <!-- Header -->
        <div class="text-center mb-8">
            <h2 class="text-2xl font-bold text-gray-900">Sign in to your account</h2>
            <p class="mt-2 text-sm text-gray-600">
                <span x-show="step === 1">Welcome back! Please enter your email to continue.</span>
                <span x-show="step === 2 && user">
                    <span x-text="getWelcomeMessage()"></span> 
                    <strong x-text="user.name"></strong>! 👋
                </span>
            </p>
        </div>

        <!-- Session Status -->
        <x-auth-session-status class="mb-4" :status="session('status')" />

        <!-- Step 1: Email -->
        <div x-show="step === 1" x-transition:enter="transition ease-out duration-300" 
             x-transition:enter-start="opacity-0 transform translate-x-4" 
             x-transition:enter-end="opacity-100 transform translate-x-0">
            
            <form @submit.prevent="handleEmailSubmit" class="space-y-6">
                <!-- Email Address -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                    <input id="email" x-model="email" type="email" required autofocus
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg text-gray-900 placeholder-gray-500 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors"
                        placeholder="Enter your email address" autocomplete="username" />
                    <div x-show="emailError" class="mt-2 text-sm text-red-600" x-text="emailError"></div>
                </div>

                <!-- Continue Button -->
                <button type="submit" :disabled="loading || !email"
                    class="w-full bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:ring-blue-200 text-white py-3 px-4 rounded-lg font-medium transition-colors disabled:opacity-50 disabled:cursor-not-allowed">
                    <span x-show="!loading">Continue</span>
                    <span x-show="loading" class="flex items-center justify-center">
                        <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Looking up your account...
                    </span>
                </button>

                <!-- Divider -->
                @if (Route::has('auth.google'))
                    <div class="relative my-6">
                        <div class="absolute inset-0 flex items-center">
                            <div class="w-full border-t border-gray-300"></div>
                        </div>
                        <div class="relative flex justify-center text-sm">
                            <span class="px-2 bg-white text-gray-500">Or continue with</span>
                        </div>
                    </div>

                    <!-- Google Login Button -->
                    <a href="{{ route('auth.google') }}"
                        class="w-full flex justify-center items-center gap-3 py-3 px-4 border border-gray-300 rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:ring-4 focus:ring-gray-200 transition-colors">
                        <svg class="w-5 h-5" viewBox="0 0 24 24">
                            <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" />
                            <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" />
                            <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" />
                            <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" />
                        </svg>
                        Sign in with Google
                    </a>
                @endif
            </form>
        </div>

        <!-- Step 2: Password -->
        <div x-show="step === 2" x-transition:enter="transition ease-out duration-300" 
             x-transition:enter-start="opacity-0 transform translate-x-4" 
             x-transition:enter-end="opacity-100 transform translate-x-0">
            
            <form method="POST" action="{{ route('login') }}" class="space-y-6">
                @csrf

                <!-- Hidden Email Field -->
                <input type="hidden" name="email" :value="email">

                <!-- User Profile Card -->
                <div class="flex items-center justify-between p-4 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl border border-blue-100">
                    <div class="flex items-center space-x-4">
                        <!-- Profile Photo -->
                        <div class="relative">
                            <img x-show="user?.avatar" 
                                 :src="user?.avatar" 
                                 :alt="user?.name || 'User Avatar'" 
                                 class="w-14 h-14 rounded-full object-cover border-3 border-white shadow-md"
                                 onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                            <div x-show="!user?.avatar" 
                                 class="w-14 h-14 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-full flex items-center justify-center border-3 border-white shadow-md">
                                <i class="fas fa-user text-white text-lg"></i>
                            </div>
                            <!-- Online Status Indicator -->
                            <div class="absolute -bottom-1 -right-1 w-4 h-4 bg-green-400 rounded-full border-2 border-white shadow-sm"></div>
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-900" x-text="user?.name || 'User'"></h3>
                            <p class="text-sm text-gray-600" x-text="user?.email || email"></p>
                        </div>
                    </div>
                    <button type="button" @click="step = 1" class="text-sm text-blue-600 hover:text-blue-500 font-medium flex items-center space-x-1">
                        <i class="fas fa-edit text-xs"></i>
                        <span>Change</span>
                    </button>
                </div>

                <!-- Password -->
                <div x-data="{ show: false }">
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Password</label>
                    <div class="relative">
                        <input id="password" name="password" required autocomplete="current-password"
                            :type="show ? 'text' : 'password'"
                            class="w-full px-4 py-3 pr-12 border border-gray-300 rounded-lg text-gray-900 placeholder-gray-500 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors"
                            placeholder="Enter your password" x-ref="passwordInput">
                        <button type="button" @click="show = !show" class="absolute inset-y-0 right-0 pr-3 flex items-center">
                            <svg class="h-5 w-5 text-gray-400" :class="{'hidden': show}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                            <svg class="h-5 w-5 text-gray-400" :class="{'hidden': !show}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L3 3m6.878 6.878L21 21" />
                            </svg>
                        </button>
                    </div>
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>

                <!-- Remember Me & Forgot Password -->
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <input id="remember_me" type="checkbox" name="remember"
                            class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                        <label for="remember_me" class="ml-2 text-sm text-gray-600">Remember me</label>
                    </div>

                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="text-sm text-blue-600 hover:text-blue-500">
                            Forgot password?
                        </a>
                    @endif
                </div>

                <!-- Submit Button -->
                <button type="submit"
                    class="w-full bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:ring-blue-200 text-white py-3 px-4 rounded-lg font-medium transition-colors">
                    Sign in
                </button>
            </form>
        </div>

        <!-- Registration Link -->
        <div class="text-center pt-6 border-t border-gray-200 mt-8">
            <p class="text-sm text-gray-600">
                Don't have an account?
                <a href="{{ route('register') }}" class="text-blue-600 hover:text-blue-500 font-medium">
                    Sign up
                </a>
            </p>
        </div>
    </div>

    <script>
        function loginFlow() {
            return {
                step: 1,
                email: '{{ old('email', '') }}',
                user: null,
                loading: false,
                emailError: '',

                init() {
                    // If there's an email from old input (validation error), fetch user and go to step 2
                    if (this.email && '{{ $errors->has('password') }}') {
                        this.fetchUserDetails(this.email);
                    }
                },

                getWelcomeMessage() {
                    const hour = new Date().getHours();
                    if (hour < 12) return 'Good morning,';
                    if (hour < 17) return 'Good afternoon,';
                    return 'Good evening,';
                },

                async handleEmailSubmit() {
                    if (!this.email || this.loading) return;
                    
                    this.loading = true;
                    this.emailError = '';

                    try {
                        // Validate email format
                        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                        if (!emailRegex.test(this.email)) {
                            throw new Error('Please enter a valid email address');
                        }

                        // Fetch user details from API
                        await this.fetchUserDetails(this.email);
                        
                    } catch (error) {
                        this.emailError = error.message;
                    } finally {
                        this.loading = false;
                    }
                },

                async fetchUserDetails(email) {
                    try {
                        const response = await fetch('{{ route('login.check-email') }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            },
                            body: JSON.stringify({ email: email })
                        });

                        const data = await response.json();
                        
                        if (data.success) {
                            this.user = data.user;
                            this.step = 2;
                            this.$nextTick(() => {
                                if (this.$refs.passwordInput) {
                                    this.$refs.passwordInput.focus();
                                }
                            });
                        } else {
                            throw new Error(data.message || 'Account not found');
                        }
                    } catch (error) {
                        if (error.name === 'TypeError') {
                            throw new Error('Unable to connect to server. Please try again.');
                        }
                        throw error;
                    }
                }
            };
        }
    </script>
</x-guest-layout>
