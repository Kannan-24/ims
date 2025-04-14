<section>
    <header class="mb-6">
        <h2 class="text-3xl font-bold text-white flex items-center gap-2">
            {{ __('Update Password') }}
        </h2>
        <p class="mt-2 text-sm text-gray-400">
            {{ __('Make sure your new password is strong and secure.') }}
        </p>
    </header>

    <form method="post" action="{{ route('password.update') }}" class="space-y-6 animate-fade-in" id="update-password-form">
        @csrf
        @method('put')

        <!-- Current Password -->
        <div>
            <label for="update_password_current_password" class="block text-sm font-semibold text-gray-300 mb-1">
                {{ __('Current Password') }}
            </label>
            <div class="relative">
                <input id="update_password_current_password" name="current_password" type="password"
                    class="w-full px-4 py-3 pr-12 border border-gray-700 bg-gray-800 text-gray-200 rounded-lg shadow focus:ring-2 focus:ring-blue-500 focus:outline-none"
                    autocomplete="current-password" required>
            </div>
            <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2" />
        </div>

        <!-- New Password -->
        <div>
            <label for="update_password_password" class="block text-sm font-semibold text-gray-300 mb-1">
                {{ __('New Password') }}
            </label>
            <div class="relative">
                <input id="update_password_password" name="password" type="password"
                    class="w-full px-4 py-3 pr-12 border border-gray-700 bg-gray-800 text-gray-200 rounded-lg shadow focus:ring-2 focus:ring-blue-500 focus:outline-none"
                    autocomplete="new-password" required>
            </div>
            <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div>
            <label for="update_password_password_confirmation" class="block text-sm font-semibold text-gray-300 mb-1">
                {{ __('Confirm Password') }}
            </label>
            <div class="relative">
                <input id="update_password_password_confirmation" name="password_confirmation" type="password"
                    class="w-full px-4 py-3 pr-12 border border-gray-700 bg-gray-800 text-gray-200 rounded-lg shadow focus:ring-2 focus:ring-blue-500 focus:outline-none"
                    autocomplete="new-password" required>
            </div>
            <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2" />
        </div>

        <!-- Save Button -->
        <div class="flex justify-between items-center">
            <button type="submit"
                class="px-6 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-semibold rounded-lg shadow-md transition transform hover:scale-105 duration-200">
                {{ __('Save Changes') }}
            </button>

            @if (session('status') === 'password-updated')
                <p x-data="{ show: true }" x-show="show" x-transition
                    x-init="setTimeout(() => show = false, 3000); logoutUser();"
                    class="text-sm text-green-400 font-medium ml-4 animate-pulse">
                    {{ __('Password updated. Logging out...') }}
                </p>
            @endif
        </div>
    </form>

    <!-- Hidden Logout Form -->
    <form method="post" action="{{ route('logout') }}" id="logout-form" class="hidden">
        @csrf
    </form>

    <script>
        function logoutUser() {
            document.getElementById('logout-form').submit();
        }
    </script>
</section>
