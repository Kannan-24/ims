<section>
    <form method="post" action="{{ route('password.update') }}" class="space-y-4" id="update-password-form">
        @csrf
        @method('put')

        <!-- Current Password -->
        <div>
            <label for="update_password_current_password" class="block text-sm font-medium text-gray-700 mb-2">
                {{ __('Current Password') }}
            </label>
            <input id="update_password_current_password" name="current_password" type="password"
                class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                autocomplete="current-password" required placeholder="Enter your current password">
            <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-1" />
        </div>

        <!-- New Password -->
        <div>
            <label for="update_password_password" class="block text-sm font-medium text-gray-700 mb-2">
                {{ __('New Password') }}
            </label>
            <input id="update_password_password" name="password" type="password"
                class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                autocomplete="new-password" required placeholder="Enter your new password">
            <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-1" />
        </div>

        <!-- Confirm Password -->
        <div>
            <label for="update_password_password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
                {{ __('Confirm Password') }}
            </label>
            <input id="update_password_password_confirmation" name="password_confirmation" type="password"
                class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                autocomplete="new-password" required placeholder="Confirm your new password">
            <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-1" />
        </div>

        <!-- Password Requirements -->
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
            <div class="flex items-start">
                <div class="w-6 h-6 bg-blue-100 rounded-lg flex items-center justify-center mr-3 mt-0.5">
                    <i class="fas fa-info-circle text-blue-600 text-sm"></i>
                </div>
                <div>
                    <h4 class="text-sm font-medium text-blue-900 mb-2">Password Requirements</h4>
                    <ul class="text-xs text-blue-700 space-y-1">
                        <li class="flex items-center"><i class="fas fa-check text-green-500 mr-2"></i>At least 8 characters</li>
                        <li class="flex items-center"><i class="fas fa-check text-green-500 mr-2"></i>Mix of letters and numbers</li>
                        <li class="flex items-center"><i class="fas fa-check text-green-500 mr-2"></i>Include special characters</li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Save Button -->
        <div class="flex justify-between items-center pt-2">
            <button type="submit"
                class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors">
                <i class="fas fa-save mr-2"></i>{{ __('Update Password') }}
            </button>

            @if (session('status') === 'password-updated')
                <div x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 3000); logoutUser();"
                    class="flex items-center space-x-2 px-3 py-2 bg-green-50 text-green-700 rounded-lg border border-green-200">
                    <i class="fas fa-check-circle text-green-600"></i>
                    <p class="text-sm">{{ __('Password updated. Logging out...') }}</p>
                </div>
            @endif
        </div>
    </form>

    <!-- Hidden Logout Form -->
    <form method="post" action="{{ route('logout') }}" id="logout-form" class="hidden">
        @csrf
    </form>

    <script>
        function logoutUser() {
            setTimeout(() => {
                document.getElementById('logout-form').submit();
            }, 2000);
        }
    </script>
</section>
