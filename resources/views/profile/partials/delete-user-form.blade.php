<section class="space-y-6">
    <header>
        <h2 class="text-3xl font-bold text-gray-200">
            {{ __('Delete Account') }}
        </h2>

        <p class="mt-1 text-sm text-gray-400">
            {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Before deleting your account, please download any data or information that you wish to retain.') }}
        </p>
    </header>

    <x-danger-button x-data=""
        x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')" class="px-6 py-3 bg-red-500 hover:bg-red-600 text-white font-semibold rounded-lg shadow-md transition">
        {{ __('Delete Account') }}
    </x-danger-button>

    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <form method="post" action="{{ route('account.destroy') }}" class="px-6 py-6 bg-gray-800 rounded-lg shadow-md">
            @csrf
            @method('delete')

            <h2 class="text-2xl font-bold text-gray-200">
                {{ __('Are you sure you want to delete your account?') }}
            </h2>

            <p class="mt-4 text-sm text-gray-400">
                {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Please enter your password to confirm you would like to permanently delete your account.') }}
            </p>

            <div class="mt-6">
                <label for="password" class="block text-gray-300 font-semibold mb-2">{{ __('Password') }}</label>
                <input id="password" name="password" type="password" class="w-full px-4 py-3 border border-gray-700 bg-gray-800 text-gray-300 rounded-lg shadow-md focus:outline-none focus:ring-2 focus:ring-red-500 transition" placeholder="{{ __('Password') }}" required>
                <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-2" />
            </div>

            <div class="flex justify-end mt-6">
                <button type="button" x-on:click="$dispatch('close')" class="px-6 py-3 bg-gray-700 hover:bg-gray-600 text-gray-300 font-semibold rounded-lg shadow-md transition">
                    {{ __('Cancel') }}
                </button>

                <button type="submit" class="ms-3 px-6 py-3 bg-red-500 hover:bg-red-600 text-white font-semibold rounded-lg shadow-md transition">
                    {{ __('Delete Account') }}
                </button>
            </div>
        </form>
    </x-modal>
</section>
