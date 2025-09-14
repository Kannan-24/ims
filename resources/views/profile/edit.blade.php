<x-app-layout>
    <x-slot name="title">
        {{ __('Edit Profile') }} - {{ config('app.name', 'IMS') }}
    </x-slot>

    <div class="bg-white min-h-screen" x-data="profileEditManager()" x-init="init()">
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
                            <a href="{{ route('profile.show') }}"
                                class="text-sm font-medium text-gray-700 hover:text-blue-600">
                                Profile
                            </a>
                        </div>
                    </li>
                    <li aria-current="page">
                        <div class="flex items-center">
                            <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                            <span class="text-sm font-medium text-gray-500">Edit Profile</span>
                        </div>
                    </li>
                </ol>
            </nav>
        </div>

        <!-- Header -->
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Edit Profile</h1>
                    <p class="text-sm text-gray-600 mt-1">Update your personal information and settings</p>
                </div>
                <div class="flex items-center space-x-3">
                    <!-- Help Button -->
                    <a href="#"
                        class="inline-flex items-center px-3 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium rounded-lg transition-colors">
                        <i class="fas fa-question-circle w-4 h-4 mr-2"></i>
                        Help
                    </a>
                    <a href="{{ route('profile.show') }}"
                        class="inline-flex items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium rounded-lg transition-colors">
                        <i class="fas fa-arrow-left w-4 h-4 mr-2"></i>
                        Back to Profile
                    </a>
                </div>
            </div>
        </div>

        <!-- Content -->
        <div class="p-6">
            <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data" id="profileForm">
                @csrf
                @method('PUT')

                <!-- Tab Navigation -->
                <div class="border-b border-gray-200 mb-6">
                    <nav class="-mb-px flex space-x-8">
                        <button type="button" @click="activeTab = 'personal'"
                            :class="activeTab === 'personal' ? 'border-blue-500 text-blue-600' :
                                'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                            class="py-2 px-1 border-b-2 font-medium text-sm">
                            <i class="fas fa-user mr-2"></i>
                            Personal Information
                        </button>
                        <button type="button" @click="activeTab = 'contact'"
                            :class="activeTab === 'contact' ? 'border-blue-500 text-blue-600' :
                                'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                            class="py-2 px-1 border-b-2 font-medium text-sm">
                            <i class="fas fa-address-card mr-2"></i>
                            Contact Details
                        </button>
                        <button type="button" @click="activeTab = 'work'"
                            :class="activeTab === 'work' ? 'border-blue-500 text-blue-600' :
                                'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                            class="py-2 px-1 border-b-2 font-medium text-sm">
                            <i class="fas fa-briefcase mr-2"></i>
                            Work Information
                        </button>
                        <button type="button" @click="activeTab = 'avatar'"
                            :class="activeTab === 'avatar' ? 'border-blue-500 text-blue-600' :
                                'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                            class="py-2 px-1 border-b-2 font-medium text-sm">
                            <i class="fas fa-camera mr-2"></i>
                            Profile Picture
                        </button>
                    </nav>
                </div>

                <!-- Tab Content -->
                <div class="space-y-6">
                    <!-- Personal Information Tab -->
                    <div x-show="activeTab === 'personal'" x-transition:enter="transition ease-out duration-200"
                        x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
                        <div class="bg-white border border-gray-200 rounded-lg p-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Personal Information</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Full Name <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" required
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                    @error('name')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Employee ID
                                    </label>
                                    <input type="text" value="{{ $user->employee_id }}" readonly
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg bg-gray-50 text-gray-500 cursor-not-allowed">
                                    <p class="mt-1 text-sm text-gray-500">Employee ID cannot be changed</p>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Date of Birth <span class="text-red-500">*</span>
                                    </label>
                                    <input type="date" name="dob" id="dob" value="{{ old('dob', $user->dob) }}" required
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                    @error('dob')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Gender <span class="text-red-500">*</span>
                                    </label>
                                    <select name="gender" id="gender" required
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                        <option value="">Select Gender</option>
                                        @foreach (['Male', 'Female', 'Other'] as $gender)
                                            <option value="{{ $gender }}" {{ old('gender', $user->gender) == $gender ? 'selected' : '' }}>
                                                {{ $gender }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('gender')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Blood Group <span class="text-red-500">*</span>
                                    </label>
                                    <select name="blood_group" id="blood_group" required
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                        <option value="">Select Blood Group</option>
                                        @foreach (['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-', 'A1+'] as $group)
                                            <option value="{{ $group }}" {{ old('blood_group', $user->blood_group) == $group ? 'selected' : '' }}>
                                                {{ $group }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('blood_group')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Contact Details Tab -->
                    <div x-show="activeTab === 'contact'" x-transition:enter="transition ease-out duration-200"
                        x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
                        <div class="bg-white border border-gray-200 rounded-lg p-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Contact Information</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Email Address <span class="text-red-500">*</span>
                                    </label>
                                    <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" required
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                    @error('email')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Phone Number <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" name="phone" id="phone" value="{{ old('phone', $user->phone) }}" required
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                    @error('phone')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="md:col-span-2">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Address <span class="text-red-500">*</span>
                                    </label>
                                    <textarea name="address" id="address" rows="3" required
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">{{ old('address', $user->address) }}</textarea>
                                    @error('address')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        State <span class="text-red-500">*</span>
                                    </label>
                                    <select name="state" id="state" required
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                        <option value="">Select State</option>
                                        @php
                                            $states = ['Tamil Nadu', 'Andhra Pradesh', 'Arunachal Pradesh', 'Assam', 'Bihar', 'Chhattisgarh', 'Goa', 'Gujarat', 'Haryana', 'Himachal Pradesh', 'Jharkhand', 'Karnataka', 'Kerala', 'Madhya Pradesh', 'Maharashtra', 'Manipur', 'Meghalaya', 'Mizoram', 'Nagaland', 'Odisha', 'Punjab', 'Rajasthan', 'Sikkim', 'Telangana', 'Tripura', 'Uttar Pradesh', 'Uttarakhand', 'West Bengal', 'Andaman and Nicobar Islands', 'Chandigarh', 'Dadra and Nagar Haveli', 'Daman and Diu', 'Lakshadweep', 'Delhi', 'Puducherry'];
                                        @endphp
                                        @foreach ($states as $state)
                                            <option value="{{ $state }}" {{ old('state', $user->state) == $state ? 'selected' : '' }}>
                                                {{ $state }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('state')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Work Information Tab -->
                    <div x-show="activeTab === 'work'" x-transition:enter="transition ease-out duration-200"
                        x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
                        <div class="bg-white border border-gray-200 rounded-lg p-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Work Information</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Role <span class="text-red-500">*</span>
                                    </label>
                                    <select name="role" id="role" required
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                        <option value="">Select Role</option>
                                        @foreach (['Admin', 'Manager', 'Employee'] as $role)
                                            <option value="{{ $role }}" {{ old('role', $user->role) == $role ? 'selected' : '' }}>
                                                {{ $role }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('role')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Date of Joining <span class="text-red-500">*</span>
                                    </label>
                                    <input type="date" name="doj" id="doj" value="{{ old('doj', $user->doj) }}" required
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                    @error('doj')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Profile Picture Tab -->
                    <div x-show="activeTab === 'avatar'" x-transition:enter="transition ease-out duration-200"
                        x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
                        <div class="bg-white border border-gray-200 rounded-lg p-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Profile Picture</h3>
                            <div class="flex items-center space-x-6">
                                <!-- Current Profile Picture -->
                                <div class="flex-shrink-0">
                                    @if ($user->profile_photo)
                                        <img src="{{ asset('storage/' . $user->profile_photo) }}"
                                            class="object-cover w-24 h-24 rounded-full border-4 border-gray-300"
                                            alt="Current Profile Picture">
                                    @else
                                        <div class="flex items-center justify-center w-24 h-24 text-2xl font-bold text-blue-600 bg-blue-100 rounded-full border-4 border-gray-300 uppercase">
                                            {{ strtoupper(substr($user->name, 0, 2)) }}
                                        </div>
                                    @endif
                                </div>
                                
                                <!-- Upload New Picture -->
                                <div class="flex-1">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Upload New Picture
                                    </label>
                                    <input type="file" name="profile_photo" id="profile_photo" accept="image/*"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                    <p class="mt-1 text-sm text-gray-500">JPG, PNG or GIF. Max size: 2MB</p>
                                    @error('profile_photo')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="flex items-center justify-between pt-6 border-t border-gray-200">
                    <!-- Left side - Previous button -->
                    <div>
                        <button type="button" @click="previousTab()" x-show="activeTab !== 'personal'"
                            class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">
                            <i class="fas fa-chevron-left mr-2"></i>
                            Previous
                        </button>
                    </div>

                    <!-- Right side - Next/Submit buttons -->
                    <div class="flex items-center space-x-3">
                        <a href="{{ route('profile.show') }}"
                            class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">
                            Cancel
                        </a>
                        
                        <!-- Next Button (shown when not on last tab) -->
                        <button type="button" @click="nextTab()" x-show="activeTab !== 'avatar'"
                            class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                            Next
                            <i class="fas fa-chevron-right ml-2"></i>
                        </button>
                        
                        <!-- Submit Button (shown only on last tab) -->
                        <button type="submit" x-show="activeTab === 'avatar'"
                            class="px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                            <i class="fas fa-save mr-2"></i>
                            Save Changes
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
        function profileEditManager() {
            return {
                activeTab: 'personal',

                init() {
                    // Initialize functionality
                },

                nextTab() {
                    const tabs = ['personal', 'contact', 'work', 'avatar'];
                    const currentIndex = tabs.indexOf(this.activeTab);
                    if (currentIndex < tabs.length - 1) {
                        this.activeTab = tabs[currentIndex + 1];
                    }
                },

                previousTab() {
                    const tabs = ['personal', 'contact', 'work', 'avatar'];
                    const currentIndex = tabs.indexOf(this.activeTab);
                    if (currentIndex > 0) {
                        this.activeTab = tabs[currentIndex - 1];
                    }
                }
            }
        }
    </script>
</x-app-layout>
