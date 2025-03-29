<section>
    <header>
        <h2 class="text-3xl font-bold text-gray-200">
            {{ __('Update Password') }}
        </h2>

        <p class="mt-2 text-sm text-gray-400">
            {{ __('Ensure your account is using a long, random password to stay secure.') }}
        </p>
    </header>

    <form method="post" action="{{ route('password.update') }}" class="mt-6 space-y-6" id="update-password-form">
        @csrf
        @method('put')

        <div class="mb-6">
            <label for="update_password_current_password" class="block text-gray-300 font-semibold mb-2">
                {{ __('Current Password') }}
            </label>
            <input id="update_password_current_password" name="current_password" type="password"
                class="w-full px-4 py-3 border border-gray-700 bg-gray-800 text-gray-300 rounded-lg shadow-md focus:outline-none focus:ring-2 focus:ring-blue-500 transition"
                autocomplete="current-password" required>
            <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2" />
        </div>

        <div class="mb-6">
            <label for="update_password_password" class="block text-gray-300 font-semibold mb-2">
                {{ __('New Password') }}
            </label>
            <input id="update_password_password" name="password" type="password"
                class="w-full px-4 py-3 border border-gray-700 bg-gray-800 text-gray-300 rounded-lg shadow-md focus:outline-none focus:ring-2 focus:ring-blue-500 transition"
                autocomplete="new-password" required>
            <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2" />
        </div>

        <div class="mb-6">
            <label for="update_password_password_confirmation" class="block text-gray-300 font-semibold mb-2">
                {{ __('Confirm Password') }}
            </label>
            <input id="update_password_password_confirmation" name="password_confirmation" type="password"
                class="w-full px-4 py-3 border border-gray-700 bg-gray-800 text-gray-300 rounded-lg shadow-md focus:outline-none focus:ring-2 focus:ring-blue-500 transition"
                autocomplete="new-password" required>
            <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex justify-end items-center gap-4">
            <button type="submit"
                class="px-6 py-3 bg-blue-500 hover:bg-blue-600 text-white font-semibold rounded-lg shadow-md transition">
                {{ __('Save') }}
            </button>

            @if (session('status') === 'password-updated')
                <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000); logoutUser();"
                    class="text-sm text-gray-400">{{ __('Saved.') }}</p>
            @endif
        </div>
    </form>

    <form method="post" action="{{ route('logout') }}" id="logout-form" class="hidden">
        @csrf
    </form>

    <script>
        function logoutUser() {
            document.getElementById('logout-form').submit();
        }
    </script>
</section>
