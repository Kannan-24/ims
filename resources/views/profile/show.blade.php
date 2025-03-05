<x-app-layout>
    <x-slot name="title">
        {{ __('Profile') }} - {{ config('app.name', 'ATMS') }}
    </x-slot>

    <div class="py-6 mt-20 ml-4 sm:ml-64">
        <div class="w-full max-w-4xl px-6 mx-auto">

            <!-- Breadcrumb Navigation -->
            <x-bread-crumb-navigation />

            <div class="grid grid-cols-1 gap-6 sm:grid-cols-3">
                <!-- User Details Card -->
                <div class="p-6 bg-white border border-gray-200 rounded-lg shadow-lg sm:col-span-2">
                    <h2 class="mb-4 text-xl font-bold text-gray-800">Profile Details</h2>
                    <div class="space-y-4">
                        <div class="flex">
                            <span class="text-gray-600">👤 Name:</span>
                            <span class="font-semibold text-gray-800">{{ $user->name }}</span>
                        </div>
                        <div class="flex">
                            <span class="text-gray-600">🆔 Employee ID:</span>
                            <span class="font-semibold text-gray-800">{{ $user->employee_id }}</span>
                        </div>
                        <div class="flex">
                            <span class="text-gray-600">🏢 Role:</span>
                            <span class="font-semibold text-gray-800">{{ $user->role }}</span>
                        </div>
                        <div class="flex">
                            <span class="text-gray-600">💼 Designation:</span>
                            <span class="font-semibold text-gray-800">{{ $user->designation }}</span>
                        </div>
                        <div class="flex">
                            <span class="text-gray-600">📞 Phone:</span>
                            <span class="font-semibold text-gray-800">{{ $user->phone }}</span>
                        </div>
                        <div class="flex">
                            <span class="text-gray-600">📧 Email:</span>
                            <span class="font-semibold text-gray-800">{{ $user->email }}</span>
                        </div>
                        <div class="flex">
                            <span class="text-gray-600">🗓️ Date of Joining:</span>
                            <span class="font-semibold text-gray-800">{{ $user->doj }}</span>
                        </div>
                        <div class="flex">
                            <span class="text-gray-600">🌍 State:</span>
                            <span class="font-semibold text-gray-800">{{ $user->state }}</span>
                        </div>
                        <div class="flex">
                            <span class="text-gray-600">🏠 Address:</span>
                            <span class="font-semibold text-gray-800">{{ $user->address }}</span>
                        </div>
                        <div class="flex">
                            <span class="text-gray-600">🎂 Date of Birth:</span>
                            <span class="font-semibold text-gray-800">{{ $user->dob }}</span>
                        </div>
                        <div class="flex">
                            <span class="text-gray-600">🩸 Blood Group:</span>
                            <span class="font-semibold text-gray-800">{{ $user->blood_group }}</span>
                        </div>
                        <div class="flex">
                            <span class="text-gray-600">🚻 Gender:</span>
                            <span class="font-semibold text-gray-800">{{ $user->gender }}</span>
                        </div>
                    </div>

                    <!-- Buttons Inside the Card -->
                    <div class="flex justify-center mt-6 space-x-4">
                        <a href="{{ route('profile.edit') }}"
                            class="px-6 py-2 text-white transition bg-blue-600 rounded-lg shadow-md hover:bg-blue-700">
                            ✏️ Edit Profile
                        </a>
                        <a href="{{ route('account.settings') }}"
                            class="px-6 py-2 text-blue-600 transition bg-gray-100 rounded-lg shadow-md hover:bg-gray-200">
                            ⚙️ Settings
                        </a>
                    </div>
                </div>
                <div
                    class="relative flex flex-col items-center p-5 text-center bg-white border border-gray-200 rounded-lg shadow-lg sm:col-span-1">

                    <h2 class="text-xl font-bold">Profile Picture</h2>
                    <div class="flex flex-col items-center mt-10 space-y-4">
                        <div class="relative w-44 h-44">
                            <!-- Profile Image -->
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

                            <!-- Overlay with Upload Icon -->
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

                            <!-- Hidden Upload Form -->
                            <form method="POST" action="{{ route('profile.update.photo') }}"
                                enctype="multipart/form-data" class="hidden">
                                @csrf
                                <input type="file" id="profile_photo" name="profile_photo" accept="image/*"
                                    onchange="this.form.submit()">
                            </form>
                        </div>

                        <div class="p-6">
                            <h2 class="text-xl font-bold">{{ $user->name }}</h2>
                            <p class="text-gray-600">{{ $user->role }}</p>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>
</x-app-layout>
