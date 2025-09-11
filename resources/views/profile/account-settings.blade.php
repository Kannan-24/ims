<x-app-layout>
    <x-slot name="title">
        {{ __('Account Settings') }} - {{ config('app.name', 'IMS') }}
    </x-slot>

    <div class="bg-white" x-data="accountSettings()" x-init="init()">
        <!-- Breadcrumbs -->
        <div class="px-6 py-3 bg-gray-50 border-b border-gray-200">
            <nav class="flex" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-3">
                    <li class="inline-flex items-center">
                        <a href="{{ route('dashboard') }}"
                            class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-blue-600">
                            <i class="fas fa-home mr-2"></i>
                            Dashboard
                        </a>
                    </li>
                    <li aria-current="page">
                        <div class="flex items-center">
                            <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                            <span class="text-sm font-medium text-gray-500">Account Settings</span>
                        </div>
                    </li>
                </ol>
            </nav>
        </div>

        <!-- Header -->
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Account Settings</h1>
                    <p class="text-sm text-gray-600 mt-1">Manage your account security and preferences</p>
                </div>
                <div class="flex items-center space-x-3">
                    <!-- Help Button -->
                    <button @click="showHelpModal = true"
                        class="inline-flex items-center px-3 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium rounded-lg transition-colors">
                        <i class="fas fa-question-circle w-4 h-4 mr-2"></i>
                        Help
                    </button>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="bg-gray-50 min-h-screen">
            <div class="p-6">

                @if (!$confirmed)
                    <!-- Password Confirmation Card -->
                    <div class="max-w-md mx-auto">
                        <div class="bg-white border border-gray-200 rounded-lg shadow-sm p-6">
                            <div class="text-center mb-6">
                                <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <i class="fas fa-shield-alt text-blue-600 text-2xl"></i>
                                </div>
                                <h2 class="text-xl font-bold text-gray-900 mb-2">Secure Access Required</h2>
                                <p class="text-gray-600">For your security, please re-enter your password to access account settings.</p>
                            </div>

                            @if (session('error'))
                                <div class="mb-4 p-3 bg-red-50 border border-red-200 rounded-lg">
                                    <div class="flex items-center">
                                        <i class="fas fa-exclamation-circle text-red-500 mr-2"></i>
                                        <p class="text-red-700 text-sm">{{ session('error') }}</p>
                                    </div>
                                </div>
                            @endif

                            <form method="POST" action="{{ route('account.settings.confirm') }}" class="space-y-4">
                                @csrf
                                <div>
                                    <label for="current_password" class="block text-sm font-medium text-gray-700 mb-2">
                                        Password
                                    </label>
                                    <input type="password" name="current_password" id="current_password" required autofocus
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" 
                                        placeholder="Enter your password" />
                                </div>
                                <button type="submit"
                                    class="w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium transition-colors">
                                    Confirm Access
                                </button>
                            </form>
                        </div>
                    </div>
                @else
                    <!-- Settings Content -->
                    <div class="max-w-7xl mx-auto space-y-6">

                        <!-- Account Overview Card -->
                        <div class="bg-white border border-gray-200 rounded-lg shadow-sm p-6">
                            <div class="flex items-center justify-between mb-6">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center mr-3">
                                        <i class="fas fa-user-circle text-blue-600"></i>
                                    </div>
                                    <div>
                                        <h3 class="text-lg font-semibold text-gray-900">Account Overview</h3>
                                        <p class="text-sm text-gray-600">Your account information and status</p>
                                    </div>
                                </div>
                                <span class="inline-flex items-center px-3 py-1 bg-green-100 text-green-800 text-sm font-medium rounded-full">
                                    <div class="w-2 h-2 bg-green-500 rounded-full mr-2"></div>
                                    Active
                                </span>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div class="text-center p-4 bg-gray-50 rounded-lg border border-gray-200">
                                    <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-3">
                                        <i class="fas fa-user text-blue-600"></i>
                                    </div>
                                    <div class="text-lg font-semibold text-gray-900">{{ Auth::user()->name }}</div>
                                    <div class="text-sm text-gray-600">Account Holder</div>
                                </div>
                                <div class="text-center p-4 bg-gray-50 rounded-lg border border-gray-200">
                                    <div class="w-10 h-10 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-3">
                                        <i class="fas fa-envelope text-purple-600"></i>
                                    </div>
                                    <div class="text-lg font-semibold text-gray-900">{{ Auth::user()->email }}</div>
                                    <div class="text-sm text-gray-600">Email Address</div>
                                </div>
                                <div class="text-center p-4 bg-gray-50 rounded-lg border border-gray-200">
                                    <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-3">
                                        <i class="fas fa-calendar text-green-600"></i>
                                    </div>
                                    <div class="text-lg font-semibold text-gray-900">{{ Auth::user()->created_at->format('M Y') }}</div>
                                    <div class="text-sm text-gray-600">Member Since</div>
                                </div>
                            </div>
                        </div>

                        <!-- Update Password Form -->
                        <div class="bg-white border border-gray-200 rounded-lg shadow-sm p-6">
                            <div class="flex items-center mb-6">
                                <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center mr-3">
                                    <i class="fas fa-lock text-blue-600"></i>
                                </div>
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900">Update Password</h3>
                                    <p class="text-sm text-gray-600">Ensure your account is using a strong password to stay secure</p>
                                </div>
                            </div>
                            <div class="max-w-2xl">
                                @include('profile.partials.update-password-form')
                            </div>
                        </div>

                        <!-- Two-Factor Authentication -->
                        <div class="bg-white border border-gray-200 rounded-lg shadow-sm p-6">
                            <div class="flex items-center mb-6">
                                <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center mr-3">
                                    <i class="fas fa-mobile-alt text-green-600"></i>
                                </div>
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900">Two-Factor Authentication</h3>
                                    <p class="text-sm text-gray-600">Add additional security to your account using
                                        two-factor authentication</p>
                                </div>
                            </div>
                            <div class="w-full">
                                @php($secret = $user->two_factor_secret)
                                @include('profile.partials.two-factor-settings')
                            </div>
                        </div>

                        <!-- Active Sessions -->
                        <div class="bg-white border border-gray-200 rounded-lg shadow-sm p-6">
                            <div class="flex items-center justify-between mb-4">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center mr-3">
                                        <i class="fas fa-desktop text-purple-600"></i>
                                    </div>
                                    <div>
                                        <h3 class="text-lg font-semibold text-gray-900">Active Sessions</h3>
                                        <p class="text-sm text-gray-600">These are the devices currently logged into your account</p>
                                    </div>
                                </div>
                                <span class="inline-flex items-center px-3 py-1 bg-blue-100 text-blue-800 text-sm font-medium rounded-full">
                                    {{ count($sessions) }} Active
                                </span>
                            </div>

                            <div class="overflow-hidden border border-gray-200 rounded-lg">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th
                                                class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Device / Agent</th>
                                            <th
                                                class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                IP Address</th>
                                            <th
                                                class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Last Activity</th>
                                            <th
                                                class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Action</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @forelse($sessions as $session)
                                            <tr class="hover:bg-gray-50">
                                                <td class="px-4 py-4">
                                                    <div class="flex items-center">
                                                        @if ($session['is_current'])
                                                            <div class="w-2 h-2 bg-green-500 rounded-full mr-3"></div>
                                                        @else
                                                            <div class="w-2 h-2 bg-gray-300 rounded-full mr-3"></div>
                                                        @endif
                                                        <div>
                                                            <div class="font-medium text-gray-900 {{ $session['is_current'] ? 'text-green-600' : '' }}">
                                                                {{ $session['is_current'] ? 'This Device' : 'Other Device' }}
                                                            </div>
                                                            <div class="text-xs text-gray-500 break-words max-w-xs">
                                                                {{ Str::limit($session['user_agent'] ?? 'Unknown Device', 60) }}
                                                            </div>
                                                            @if ($session['is_current'])
                                                                <div class="text-xs text-green-600">Current session</div>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="px-4 py-4 text-sm text-gray-900">
                                                    {{ $session['ip'] ?? 'Unknown' }}
                                                </td>
                                                <td class="px-4 py-4 text-sm text-gray-500">
                                                    {{ isset($session['last_activity']) ? $session['last_activity']->diffForHumans() : 'Unknown' }}
                                                </td>
                                                <td class="px-4 py-4 text-right text-sm">
                                                    @if (!$session['is_current'])
                                                        <form method="POST"
                                                            action="{{ route('account.settings.sessions.destroy', $session['id']) }}"
                                                            class="inline">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit"
                                                                onclick="return confirm('Log out this session?')"
                                                                class="text-red-600 hover:text-red-800 font-medium">
                                                                Log Out
                                                            </button>
                                                        </form>
                                                    @else
                                                        <span class="text-gray-400">Current</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="4" class="px-4 py-8 text-center text-gray-500">
                                                    <i class="fas fa-desktop text-gray-300 text-3xl mb-3"></i>
                                                    <p>No active sessions found</p>
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>

                            @if (count($sessions) > 1)
                                <div class="mt-4 pt-4 border-t border-gray-200">
                                    <form method="POST"
                                        action="{{ route('account.settings.sessions.destroy.others') }}"
                                        onsubmit="return confirm('Log out all other sessions?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="inline-flex items-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg transition-colors">
                                            <i class="fas fa-sign-out-alt w-4 h-4 mr-2"></i>
                                            Log out other sessions
                                        </button>
                                    </form>
                                </div>
                            @endif
                        </div>

                        <!-- Delete Account -->
                        <div class="bg-white border border-red-200 rounded-lg shadow-sm p-6">
                            <div class="flex items-center mb-4">
                                <div class="w-10 h-10 bg-red-100 rounded-lg flex items-center justify-center mr-3">
                                    <i class="fas fa-trash text-red-600"></i>
                                </div>
                                <div>
                                    <h3 class="text-lg font-semibold text-red-900">Delete Account</h3>
                                    <p class="text-sm text-red-600">Permanently delete your account and all of its data</p>
                                </div>
                            </div>
                            <div class="max-w-xl">
                                @include('profile.partials.delete-user-form')
                            </div>
                        </div>

                    </div>
                @endif
            </div>
        </div>

        <!-- Help Modal -->
        <div x-show="showHelpModal" x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
            <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75"
                    @click="showHelpModal = false"></div>

                <div class="inline-block w-full max-w-2xl my-8 overflow-hidden text-left align-middle transition-all transform bg-white shadow-xl rounded-lg">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">Account Settings Help</h3>
                    </div>

                    <div class="px-6 py-6 space-y-4">
                        <div>
                            <h4 class="font-medium text-gray-900 mb-2">Security Features</h4>
                            <ul class="text-sm text-gray-600 space-y-1">
                                <li>• <strong>Password Update:</strong> Change your password regularly for better security</li>
                                <li>• <strong>Two-Factor Authentication:</strong> Add extra security layer with mobile app authentication</li>
                                <li>• <strong>Active Sessions:</strong> Monitor and manage devices logged into your account</li>
                            </ul>
                        </div>

                        <div>
                            <h4 class="font-medium text-gray-900 mb-2">Best Practices</h4>
                            <ul class="text-sm text-gray-600 space-y-1">
                                <li>• Use strong passwords with at least 12 characters</li>
                                <li>• Enable two-factor authentication for added security</li>
                                <li>• Log out unused sessions from unknown devices</li>
                                <li>• Review account activity regularly</li>
                            </ul>
                        </div>
                    </div>

                    <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
                        <button @click="showHelpModal = false"
                            class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors">
                            Got it!
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function accountSettings() {
            return {
                showHelpModal: false,

                init() {
                    // Initialize account settings
                }
            };
        }
    </script>
</x-app-layout>
