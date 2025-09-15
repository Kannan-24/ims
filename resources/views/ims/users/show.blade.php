<x-app-layout>
    <x-slot name="title">
        {{ __('User Details') }} - {{ config('app.name', 'IMS') }}
    </x-slot>

    <div class="bg-white min-h-screen" x-data="{ activeTab: 'overview' }">
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
                    <li>
                        <div class="flex items-center">
                            <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                            <a href="{{ route('users.index') }}"
                                class="text-sm font-medium text-gray-700 hover:text-blue-600">
                                Users
                            </a>
                        </div>
                    </li>
                    <li aria-current="page">
                        <div class="flex items-center">
                            <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                            <span class="text-sm font-medium text-gray-500">{{ $user->name }}</span>
                        </div>
                    </li>
                </ol>
            </nav>
        </div>

        <!-- Header -->
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">User Details</h1>
                    <p class="text-sm text-gray-600 mt-1">View user information and employment details</p>
                </div>
                <div class="flex items-center space-x-3">
                    <!-- Help Button -->
                    <a href="#"
                        class="inline-flex items-center px-3 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium rounded-lg transition-colors">
                        <i class="fas fa-question-circle w-4 h-4 mr-2"></i>
                        Help
                    </a>
                    <a href="{{ route('users.edit', $user->id) }}"
                        class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors">
                        <i class="fas fa-edit w-4 h-4 mr-2"></i>
                        Edit User
                    </a>
                    <form action="{{ route('users.destroy', $user->id) }}" method="POST" class="inline"
                          onsubmit="return confirm('Are you sure you want to delete this user? This action cannot be undone.')">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                            class="inline-flex items-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg transition-colors">
                            <i class="fas fa-trash w-4 h-4 mr-2"></i>
                            Delete
                        </button>
                    </form>
                    <a href="{{ route('users.index') }}"
                        class="inline-flex items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium rounded-lg transition-colors">
                        <i class="fas fa-arrow-left w-4 h-4 mr-2"></i>
                        Back to List
                    </a>
                </div>
            </div>
        </div>

        <!-- Content -->
        <div class="p-6">
            <!-- User Profile Card -->
            <div class="bg-white border border-gray-200 rounded-lg shadow-sm overflow-hidden mb-6">
                <div class="bg-gradient-to-r from-blue-500 to-blue-600 px-6 py-8 text-white">
                    <div class="flex items-center">
                        <div class="w-16 h-16 bg-white bg-opacity-20 rounded-xl flex items-center justify-center mr-4">
                            <i class="fas fa-user text-3xl"></i>
                        </div>
                        <div>
                            <h2 class="text-2xl font-bold">{{ $user->name }}</h2>
                            <p class="text-blue-100 mt-1">Employee ID: {{ $user->employee_id }}</p>
                            <div class="mt-2">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-white bg-opacity-20 text-white">
                                    <i class="fas fa-user-tag mr-1"></i>
                                    {{ $user->role }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-4">
                            <span class="text-sm font-medium text-gray-700">Quick Actions:</span>
                            <a href="{{ route('users.edit', $user) }}"
                                class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                <i class="fas fa-edit mr-1"></i>Edit
                            </a>
                            <form action="{{ route('users.destroy', $user->id) }}" method="POST" class="inline"
                                  onsubmit="return confirm('Are you sure you want to delete this user?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-800 text-sm font-medium">
                                    <i class="fas fa-trash mr-1"></i>Delete
                                </button>
                            </form>
                        </div>
                        
                        {{-- <!-- Navigation -->
                        <div class="flex items-center space-x-2">
                            @if($previousUser)
                                <a href="{{ route('users.show', $previousUser->id) }}"
                                    class="inline-flex items-center px-3 py-1 bg-gray-200 hover:bg-gray-300 text-gray-700 text-sm font-medium rounded-lg transition-colors">
                                    <i class="fas fa-chevron-left mr-1"></i>
                                    Previous
                                </a>
                            @endif
                            
                            @if($nextUser)
                                <a href="{{ route('users.show', $nextUser->id) }}"
                                    class="inline-flex items-center px-3 py-1 bg-gray-200 hover:bg-gray-300 text-gray-700 text-sm font-medium rounded-lg transition-colors">
                                    Next
                                    <i class="fas fa-chevron-right ml-1"></i>
                                </a>
                            @endif
                        </div> --}}
                    </div>
                </div>

                <!-- User Details Grid -->
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <!-- Contact Information -->
                        <div class="md:col-span-2">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Contact Information</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-500 mb-1">Email Address</label>
                                    <p class="text-sm text-gray-900">{{ $user->email }}</p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-500 mb-1">Phone Number</label>
                                    <p class="text-sm text-gray-900">{{ $user->phone }}</p>
                                </div>
                                <div class="md:col-span-2">
                                    <label class="block text-sm font-medium text-gray-500 mb-1">Address</label>
                                    <p class="text-sm text-gray-900">{{ $user->address }}, {{ $user->state }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Account Status -->
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Account Status</h3>
                            <div class="space-y-3">
                                <div class="flex justify-between items-center">
                                    <span class="text-sm font-medium text-gray-500">Status</span>
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        <i class="fas fa-circle w-2 h-2 mr-1"></i>
                                        Active
                                    </span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-sm font-medium text-gray-500">Role</span>
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium
                                        @if($user->role === 'Admin') bg-red-100 text-red-800
                                        @elseif($user->role === 'Manager') bg-blue-100 text-blue-800
                                        @else bg-green-100 text-green-800 @endif">
                                        {{ $user->role }}
                                    </span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-sm font-medium text-gray-500">Member Since</span>
                                    <span class="text-sm text-gray-900">{{ \Carbon\Carbon::parse($user->created_at)->format('M Y') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
        <!-- Tab Navigation -->
        <div class="px-6 border-b border-gray-200">
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
                <button @click="activeTab = 'employment'" 
                    :class="activeTab === 'employment' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                    class="py-4 px-1 border-b-2 font-medium text-sm">
                    <i class="fas fa-briefcase mr-2"></i>
                    Employment
                </button>
            </nav>
        </div>

        <!-- Tab Content -->
        <div class="p-6">
            <!-- Overview Tab -->
            <div x-show="activeTab === 'overview'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Personal Details -->
                    <div class="bg-white border border-gray-200 rounded-lg p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Personal Details</h3>
                        <div class="space-y-4">
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
                        </div>
                    </div>

                    <!-- Employment Details -->
                    <div class="bg-white border border-gray-200 rounded-lg p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Employment Details</h3>
                        <div class="space-y-4">
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
                                <label class="block text-sm font-medium text-gray-500 mb-1">Last Updated</label>
                                <p class="text-sm text-gray-900">{{ \Carbon\Carbon::parse($user->updated_at)->diffForHumans() }}</p>
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
                            <p class="text-sm text-gray-900">{{ \Carbon\Carbon::parse($user->dob)->format('M d, Y') }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Age</label>
                            <p class="text-sm text-gray-900">{{ \Carbon\Carbon::parse($user->dob)->age }} years</p>
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
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Phone Number</label>
                            <p class="text-sm text-gray-900">{{ $user->phone }}</p>
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-500 mb-1">Address</label>
                            <p class="text-sm text-gray-900">{{ $user->address }}, {{ $user->state }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Employment Tab -->
            <div x-show="activeTab === 'employment'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
                <div class="bg-white border border-gray-200 rounded-lg p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Employment Information</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Employee ID</label>
                            <p class="text-sm text-gray-900 font-mono">{{ $user->employee_id ?? 'Not assigned' }}</p>
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
        </div>
    </div>

    <script>
        // No additional JavaScript needed for this static view
    </script>
</x-app-layout>