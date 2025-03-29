<x-app-layout>
    <x-slot name="title">
        {{ __('Profile Details') }} - {{ config('app.name', 'ATMS') }}
    </x-slot>

    <div class="py-6 mt-20 ml-4 sm:ml-64">
        <div class="w-full mx-auto max-w-7xl sm:px-6 lg:px-8">
            <x-bread-crumb-navigation />

            <div class="p-8 bg-gray-800 border border-gray-700 rounded-lg shadow-lg relative">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-3xl font-bold text-gray-200">Profile Details</h2>
                    <div class="flex gap-2">
                        <a href="{{ route('profile.edit') }}"
                            class="flex items-center px-4 py-2 text-white bg-green-500 rounded-lg hover:bg-green-600 transition">
                            ✏️ Edit Profile
                        </a>
                        <a href="{{ route('account.settings') }}"
                            class="flex items-center px-4 py-2 text-white bg-blue-500 rounded-lg hover:bg-blue-600 transition">
                            ⚙️ Settings
                        </a>
                    </div>
                </div>

                <hr class="my-6 border-gray-600">

                <div class="flex flex-col lg:flex-row gap-8">
                    <!-- Left Container: Profile Details -->
                    <div class="p-6 bg-gray-900 border border-gray-700 rounded-lg flex-1">
                        <h3 class="text-2xl font-bold text-gray-200 mb-4">Details</h3>
                        <div class="space-y-4 text-gray-300">
                            <p><strong>👤 Name:</strong> {{ $user->name }}</p>
                            <p><strong>🆔 Employee ID:</strong> {{ $user->employee_id }}</p>
                            <p><strong>🏢 Role:</strong> {{ $user->role }}</p>
                            <p><strong>💼 Designation:</strong> {{ $user->designation }}</p>
                            <p><strong>📞 Phone:</strong> {{ $user->phone }}</p>
                            <p><strong>📧 Email:</strong> {{ $user->email }}</p>
                            <p><strong>🗓️ Date of Joining:</strong> {{ $user->doj }}</p>
                            <p><strong>🌍 State:</strong> {{ $user->state }}</p>
                            <p><strong>🏠 Address:</strong> {{ $user->address }}</p>
                            <p><strong>🎂 Date of Birth:</strong> {{ $user->dob }}</p>
                            <p><strong>🩸 Blood Group:</strong> {{ $user->blood_group }}</p>
                            <p><strong>🚻 Gender:</strong> {{ $user->gender }}</p>
                        </div>
                    </div>

                    <!-- Right Container: Profile Picture -->
                    <div class="p-6 bg-gray-900 border border-gray-700 rounded-lg flex flex-col items-center">
                        <h3 class="text-2xl font-bold text-gray-200 mb-4">Profile Picture</h3>
                        <div class="relative w-44 h-44">
                            @if ($user->profile_photo)
                                <img id="profileImagePreview" src="{{ asset('storage/' . $user->profile_photo) }}"
                                    class="object-cover transition duration-300 border-4 border-blue-500 rounded-full shadow-lg w-44 h-44 hover:scale-105"
                                    alt="Profile Picture">
                            @else
                                <div
                                    class="flex items-center justify-center text-5xl font-bold text-blue-600 uppercase bg-blue-100 border-4 border-blue-500 rounded-full shadow-lg w-44 h-44">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </div>
                            @endif

                            <div
                                class="absolute inset-0 flex items-center justify-center transition duration-300 rounded-full opacity-0 bg-black/50 hover:opacity-100">
                                <label for="profile_photo"
                                    class="flex flex-col items-center justify-center cursor-pointer">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor" class="w-10 h-10 text-white">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 15l4-4m0 0l-4-4m4 4H8" />
                                    </svg>
                                    <span class="mt-2 text-sm text-white">Upload Photo</span>
                                </label>
                            </div>

                            <form method="POST" action="{{ route('profile.update.photo') }}"
                                enctype="multipart/form-data" class="hidden">
                                @csrf
                                <input type="file" id="profile_photo" name="profile_photo" accept="image/*"
                                    onchange="this.form.submit()">
                            </form>

                            <!-- Profile name below the image -->
                            <div class="absolute bottom-0 left-0 right-0 flex items-center justify-center p-2 bg-gray-900 rounded-b-full">
                                <span class="text-lg font-semibold text-gray-200">{{ $user->name }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
