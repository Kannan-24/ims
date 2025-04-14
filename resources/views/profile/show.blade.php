<x-app-layout>
    <x-slot name="title">
        {{ __('Profile Details') }} - {{ config('app.name', 'ATMS') }}
    </x-slot>

    <div class="py-6 mt-20 ml-4 sm:ml-64">
        <div class="w-full mx-auto max-w-7xl sm:px-6 lg:px-8">
            <x-bread-crumb-navigation />

            <div class="p-8 bg-gray-900 border border-gray-800 rounded-xl shadow-2xl relative">
                <div class="flex justify-between items-center mb-8">
                    <div>
                        <h2 class="text-3xl font-extrabold text-white">👤 Profile Overview</h2>
                        <p class="text-sm text-gray-400">Manage your personal info and settings</p>
                    </div>
                    <div class="flex gap-3">
                        <a href="{{ route('profile.edit') }}"
                            class="flex items-center gap-2 px-4 py-2 text-sm font-semibold text-white bg-green-600 rounded-full hover:bg-green-700 transition">
                            Edit Profile
                        </a>
                        <a href="{{ route('account.settings') }}"
                            class="flex items-center gap-2 px-4 py-2 text-sm font-semibold text-white bg-blue-600 rounded-full hover:bg-blue-700 transition">
                            Settings
                        </a>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    <!-- Profile Picture Card -->
                    <div class="col-span-1 bg-gray-800 border border-gray-700 rounded-xl p-6 text-center shadow-lg flex flex-col items-center justify-center">
                        <div class="relative mx-auto w-36 h-36">
                            @if ($user->profile_photo)
                                <img src="{{ asset('storage/' . $user->profile_photo) }}"
                                    class="object-cover w-36 h-36 rounded-full border-4 border-blue-500 shadow-xl transition hover:scale-105"
                                    alt="Profile Picture">
                            @else
                                <div class="flex items-center justify-center w-36 h-36 text-5xl font-bold text-blue-600 bg-blue-100 rounded-full border-4 border-blue-500 shadow-xl uppercase">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </div>
                            @endif

                            <div class="absolute inset-0 flex items-center justify-center bg-black bg-opacity-40 rounded-full opacity-0 hover:opacity-100 transition">
                                <label for="profile_photo" class="cursor-pointer flex flex-col items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke="white" class="w-8 h-8">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 15l4-4m0 0l-4-4m4 4H8" />
                                    </svg>
                                    <span class="text-sm text-white mt-1">Change</span>
                                </label>
                            </div>
                            <form method="POST" action="{{ route('profile.update.photo') }}"
                                enctype="multipart/form-data" class="hidden">
                                @csrf
                                <input type="file" id="profile_photo" name="profile_photo" accept="image/*"
                                    onchange="this.form.submit()">
                            </form>
                        </div>

                        <div class="mt-4 text-center">
                            <h3 class="text-xl font-semibold text-white">{{ $user->name }}</h3>
                            <p class="text-sm text-gray-400">{{ $user->email }}</p>
                        </div>
                    </div>

                    <!-- Profile Info -->
                    <div class="col-span-2 bg-gray-800 border border-gray-700 rounded-xl p-6 shadow-lg">
                        <h3 class="text-2xl font-semibold text-white mb-6">Personal Information</h3>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 text-gray-300 text-sm">
                            <p><strong class="text-gray-400">Employee ID:</strong> {{ $user->employee_id }}</p>
                            <p><strong class="text-gray-400">Role:</strong> {{ $user->role }}</p>
                            <p><strong class="text-gray-400">Designation:</strong> {{ $user->designation }}</p>
                            <p><strong class="text-gray-400">Phone:</strong> {{ $user->phone }}</p>
                            <p><strong class="text-gray-400">Date of Joining:</strong> {{ $user->doj }}</p>
                            <p><strong class="text-gray-400">Date of Birth:</strong> {{ $user->dob }}</p>
                            <p><strong class="text-gray-400">Blood Group:</strong> {{ $user->blood_group }}</p>
                            <p><strong class="text-gray-400">Gender:</strong> {{ $user->gender }}</p>
                            <p class="sm:col-span-2"><strong class="text-gray-400">Address:</strong> {{ $user->address }}</p>
                            <p class="sm:col-span-2"><strong class="text-gray-400">State:</strong> {{ $user->state }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</x-app-layout>
