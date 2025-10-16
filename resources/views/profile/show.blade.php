<x-app-layout>
    <x-slot name="title">
        {{ __('Profile Details') }} - {{ config('app.name', 'IMS') }}
    </x-slot>

    <div class="bg-white min-h-screen" x-data="{ activeTab: 'overview', showQRModal: false }">
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
                            <span class="text-sm font-medium text-gray-500">My Profile</span>
                        </div>
                    </li>
                </ol>
            </nav>
        </div>

        <!-- Header -->
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">My Profile</h1>
                    <p class="text-sm text-gray-600 mt-1">Manage your personal information and settings</p>
                </div>
                <div class="flex items-center space-x-3">
                    <!-- QR Code Button -->
                    <button @click="showQRModal = true"
                        class="inline-flex items-center px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white text-sm font-medium rounded-lg transition-colors">
                        <i class="fas fa-qrcode w-4 h-4 mr-2"></i>
                        Share QR Code
                    </button>
                    <a href="{{ route('profile.edit') }}"
                        class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors">
                        <i class="fas fa-edit w-4 h-4 mr-2"></i>
                        Edit Profile
                    </a>
                    @if(auth()->user()->role === 'Admin')
                        <a href="{{ route('account.settings') }}"
                            class="inline-flex items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium rounded-lg transition-colors">
                            <i class="fas fa-cog w-4 h-4 mr-2"></i>
                            Settings
                        </a>
                    @endif
                </div>
            </div>
        </div>

        <!-- Content -->
        <div class="p-6">
            <!-- Profile Header Card -->
            <div class="bg-white border border-gray-200 rounded-lg shadow-sm overflow-hidden mb-6">
                <div class="bg-gradient-to-r from-blue-500 to-blue-600 px-6 py-8 text-white">
                    <div class="flex items-center">
                        <div class="relative">
                            @if ($user->profile_photo)
                                <img src="{{ asset('storage/' . $user->profile_photo) }}"
                                    class="object-cover w-20 h-20 rounded-full border-4 border-white border-opacity-30"
                                    alt="Profile Picture">
                            @else
                                <div class="flex items-center justify-center w-20 h-20 text-2xl font-bold text-blue-600 bg-white rounded-full border-4 border-white border-opacity-30 uppercase">
                                    {{ strtoupper(substr($user->name, 0, 2)) }}
                                </div>
                            @endif
                        </div>
                        <div class="ml-6">
                            <h2 class="text-3xl font-bold">{{ $user->name }}</h2>
                            <p class="text-blue-100 mt-1">{{ $user->email }}</p>
                            <div class="mt-3 flex items-center space-x-4">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-white bg-opacity-20 text-white">
                                    <i class="fas fa-id-badge mr-1"></i>
                                    {{ $user->employee_id }}
                                </span>
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-white bg-opacity-20 text-white">
                                    <i class="fas fa-user-tag mr-1"></i>
                                    {{ $user->role }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quick Stats -->
                <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div class="text-center">
                            @php
                                $doj = \Carbon\Carbon::parse($user->doj);
                                $diff = $doj->diff(\Carbon\Carbon::now());
                                $parts = [];
                                if ($diff->y) $parts[] = $diff->y . ' year' . ($diff->y > 1 ? 's' : '');
                                if ($diff->m) $parts[] = $diff->m . ' month' . ($diff->m > 1 ? 's' : '');
                                if ($diff->d) $parts[] = $diff->d . ' day' . ($diff->d > 1 ? 's' : '');
                            @endphp
                            <p class="text-sm text-gray-900">{{ count($parts) ? implode(', ', $parts) : '0 days' }}</p>
                            <div class="text-xs text-gray-500">Years Experience</div>
                        </div>
                        <div class="text-center">
                            <div class="text-2xl font-bold text-green-600">{{ \Carbon\Carbon::parse($user->dob)->age }}</div>
                            <div class="text-xs text-gray-500">Age</div>
                        </div>
                        <div class="text-center">
                            <div class="text-2xl font-bold text-purple-600">{{ \Carbon\Carbon::parse($user->created_at)->format('M Y') }}</div>
                            <div class="text-xs text-gray-500">Member Since</div>
                        </div>
                        <div class="text-center">
                            <div class="text-2xl font-bold text-orange-600">Active</div>
                            <div class="text-xs text-gray-500">Status</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tab Navigation -->
            <div class="border-b border-gray-200 mb-6">
                <nav class="-mb-px flex space-x-8">
                    <button @click="activeTab = 'overview'" 
                        :class="activeTab === 'overview' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                        class="py-4 px-1 border-b-2 font-medium text-sm">
                        <i class="fas fa-user mr-2"></i>
                        Overview
                    </button>
                    <button @click="activeTab = 'personal'" 
                        :class="activeTab === 'personal' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                        class="py-4 px-1 border-b-2 font-medium text-sm">
                        <i class="fas fa-id-card mr-2"></i>
                        Personal Info
                    </button>
                    <button @click="activeTab = 'work'" 
                        :class="activeTab === 'work' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                        class="py-4 px-1 border-b-2 font-medium text-sm">
                        <i class="fas fa-briefcase mr-2"></i>
                        Work Details
                    </button>
                    <button @click="activeTab = 'security'" 
                        :class="activeTab === 'security' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                        class="py-4 px-1 border-b-2 font-medium text-sm">
                        <i class="fas fa-shield-alt mr-2"></i>
                        Security
                    </button>
                </nav>
            </div>

            <!-- Tab Content -->
            <div class="space-y-6">
                <!-- Overview Tab -->
                <div x-show="activeTab === 'overview'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <!-- Contact Information -->
                        <div class="bg-white border border-gray-200 rounded-lg p-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Contact Information</h3>
                            <div class="space-y-3">
                                <div class="flex justify-between">
                                    <span class="text-sm font-medium text-gray-500">Email</span>
                                    <span class="text-sm text-gray-900">{{ $user->email }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-sm font-medium text-gray-500">Phone</span>
                                    <span class="text-sm text-gray-900">{{ $user->phone }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-sm font-medium text-gray-500">Address</span>
                                    <span class="text-sm text-gray-900 text-right">{{ $user->address }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-sm font-medium text-gray-500">State</span>
                                    <span class="text-sm text-gray-900">{{ $user->state }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Account Status -->
                        <div class="bg-white border border-gray-200 rounded-lg p-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Account Status</h3>
                            <div class="space-y-3">
                                <div class="flex justify-between">
                                    <span class="text-sm font-medium text-gray-500">Role</span>
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium
                                        @if($user->role === 'Admin') bg-red-100 text-red-800
                                        @elseif($user->role === 'Manager') bg-blue-100 text-blue-800
                                        @else bg-green-100 text-green-800 @endif">
                                        {{ $user->role }}
                                    </span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-sm font-medium text-gray-500">Member Since</span>
                                    <span class="text-sm text-gray-900">{{ \Carbon\Carbon::parse($user->created_at)->format('M d, Y') }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-sm font-medium text-gray-500">Last Updated</span>
                                    <span class="text-sm text-gray-900">{{ \Carbon\Carbon::parse($user->updated_at)->diffForHumans() }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Personal Info Tab -->
                <div x-show="activeTab === 'personal'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
                    <div class="bg-white border border-gray-200 rounded-lg p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Personal Information</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-500 mb-1">Full Name</label>
                                <p class="text-sm text-gray-900">{{ $user->name }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-500 mb-1">Date of Birth</label>
                                <p class="text-sm text-gray-900">{{ \Carbon\Carbon::parse($user->dob)->format('M d, Y') }} ({{ \Carbon\Carbon::parse($user->dob)->age }} years)</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-500 mb-1">Gender</label>
                                <p class="text-sm text-gray-900">{{ $user->gender }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-500 mb-1">Blood Group</label>
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                    {{ $user->blood_group }}
                                </span>
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-500 mb-1">Address</label>
                                <p class="text-sm text-gray-900">{{ $user->address }}, {{ $user->state }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Work Details Tab -->
                <div x-show="activeTab === 'work'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
                    <div class="bg-white border border-gray-200 rounded-lg p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Employment Information</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-500 mb-1">Employee ID</label>
                                <p class="text-sm text-gray-900 font-mono">{{ $user->employee_id }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-500 mb-1">Role</label>
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium
                                    @if($user->role === 'Admin') bg-red-100 text-red-800
                                    @elseif($user->role === 'Manager') bg-blue-100 text-blue-800
                                    @else bg-green-100 text-green-800 @endif">
                                    {{ $user->role }}
                                </span>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-500 mb-1">Date of Joining</label>
                                <p class="text-sm text-gray-900">{{ \Carbon\Carbon::parse($user->doj)->format('M d, Y') }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-500 mb-1">Years of Service</label>
                                @php
                                    $doj = \Carbon\Carbon::parse($user->doj);
                                    $diff = $doj->diff(\Carbon\Carbon::now());
                                    $parts = [];
                                    if ($diff->y) $parts[] = $diff->y . ' year' . ($diff->y > 1 ? 's' : '');
                                    if ($diff->m) $parts[] = $diff->m . ' month' . ($diff->m > 1 ? 's' : '');
                                    if ($diff->d) $parts[] = $diff->d . ' day' . ($diff->d > 1 ? 's' : '');
                                @endphp
                                <p class="text-sm text-gray-900">{{ count($parts) ? implode(', ', $parts) : '0 days' }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-500 mb-1">Work Email</label>
                                <p class="text-sm text-gray-900">{{ $user->email }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-500 mb-1">Contact Number</label>
                                <p class="text-sm text-gray-900">{{ $user->phone }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Security Tab -->
                <div x-show="activeTab === 'security'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <!-- Password Security -->
                        <div class="bg-white border border-gray-200 rounded-lg p-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Password Security</h3>
                            <div class="space-y-4">
                                <div class="flex justify-between items-center">
                                    <span class="text-sm font-medium text-gray-500">Last Changed</span>
                                    <span class="text-sm text-gray-900">{{ $user->last_password_changed_at ? \Carbon\Carbon::parse($user->last_password_changed_at)->diffForHumans() : 'Never' }}</span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-sm font-medium text-gray-500">Expires</span>
                                    <span class="text-sm text-gray-900">{{ $user->password_expires_at ? \Carbon\Carbon::parse($user->password_expires_at)->format('M d, Y') : 'Never' }}</span>
                                </div>
                                <div class="pt-2">
                                    <a href="{{ route('password.request') }}" 
                                        class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors">
                                        <i class="fas fa-key mr-2"></i>
                                        Change Password
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- QR Code Section -->
                        <div class="bg-white border border-gray-200 rounded-lg p-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Profile QR Code</h3>
                            <div class="text-center">
                                <div class="mx-auto w-32 h-32 bg-gray-100 border border-gray-300 rounded-lg flex items-center justify-center mb-4">
                                    {!! QrCode::size(120)->generate(route('profile.public', $user->id)) !!}
                                </div>
                                <p class="text-sm text-gray-600 mb-4">Scan this QR code to view your public profile</p>
                                <div class="flex justify-center space-x-2">
                                    <button @click="showQRModal = true"
                                        class="inline-flex items-center px-3 py-2 bg-purple-600 text-white text-sm font-medium rounded-lg hover:bg-purple-700 transition-colors">
                                        <i class="fas fa-expand mr-1"></i>
                                        Enlarge
                                    </button>
                                    <a href="{{ route('profile.qr.download', $user->id) }}" 
                                        class="inline-flex items-center px-3 py-2 bg-green-600 text-white text-sm font-medium rounded-lg hover:bg-green-700 transition-colors">
                                        <i class="fas fa-download mr-1"></i>
                                        Download
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- QR Code Modal -->
        <div x-show="showQRModal" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
            <div class="flex items-center justify-center min-h-screen px-4 text-center">
                <div class="fixed inset-0 bg-black bg-opacity-50" @click="showQRModal = false"></div>
                <div class="relative bg-white rounded-lg p-8 max-w-md w-full">
                    <div class="text-center">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Share Your Profile</h3>
                        <div class="mx-auto w-64 h-64 bg-gray-100 border border-gray-300 rounded-lg flex items-center justify-center mb-4">
                            {!! QrCode::size(240)->generate(route('profile.public', $user->id)) !!}
                        </div>
                        <p class="text-sm text-gray-600 mb-6">Scan this QR code to view the public profile</p>
                        <div class="flex justify-center space-x-3">
                            <a href="{{ route('profile.qr.download', $user->id) }}" 
                                class="inline-flex items-center px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-lg hover:bg-green-700 transition-colors">
                                <i class="fas fa-download mr-2"></i>
                                Download
                            </a>
                            <button @click="navigator.clipboard.writeText('{{ route('profile.public', $user->id) }}')" 
                                class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors">
                                <i class="fas fa-link mr-2"></i>
                                Copy Link
                            </button>
                            <button @click="showQRModal = false" 
                                class="inline-flex items-center px-4 py-2 bg-gray-300 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-400 transition-colors">
                                Close
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
