@extends('layouts.app')

@section('title', 'User Profile - ' . $user->name)

@section('content')
<div class="max-w-4xl mx-auto p-6">
    <!-- Page Header -->
    <div class="mb-6">
        <nav class="flex items-center space-x-2 text-sm text-gray-600 mb-4">
            <a href="{{ route('users.index') }}" class="hover:text-blue-600">Users</a>
            <i class="fas fa-chevron-right text-xs"></i>
            <span class="text-gray-900 font-medium">{{ $user->name }}</span>
        </nav>
    </div>

    <!-- Profile Card -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <!-- Profile Header -->
        <div class="bg-gradient-to-r from-blue-600 to-blue-700 px-6 py-8">
            <div class="flex items-center space-x-6">
                <!-- Profile Photo -->
                <div class="w-24 h-24 rounded-full bg-white p-1 shadow-lg">
                    @if($user->profile_photo)
                        <img src="{{ asset('storage/' . $user->profile_photo) }}" 
                             alt="{{ $user->name }}" 
                             class="w-full h-full object-cover rounded-full">
                    @else
                        <div class="w-full h-full bg-gray-200 rounded-full flex items-center justify-center">
                            <i class="fas fa-user text-gray-500 text-2xl"></i>
                        </div>
                    @endif
                </div>

                <!-- Profile Info -->
                <div class="flex-1">
                    <h1 class="text-2xl font-bold text-white mb-2">{{ $user->name }}</h1>
                    <div class="flex items-center space-x-4 text-blue-100">
                        <span class="flex items-center">
                            <i class="fas fa-id-badge mr-2"></i>
                            {{ $user->designation ?? 'Team Member' }}
                        </span>
                        @if($user->role)
                        <span class="flex items-center">
                            <i class="fas fa-user-tag mr-2"></i>
                            {{ ucfirst($user->role) }}
                        </span>
                        @endif
                    </div>
                    <div class="flex items-center mt-2 text-blue-100">
                        <i class="fas fa-envelope mr-2"></i>
                        {{ $user->email }}
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex flex-col space-y-3">
                    @if($user->id !== Auth::id())
                        <a href="{{ route('chat.with', $user) }}" 
                           class="bg-white text-blue-600 px-6 py-2 rounded-lg font-medium hover:bg-blue-50 transition-colors flex items-center">
                            <i class="fas fa-comments mr-2"></i>
                            Start Chat
                        </a>
                    @endif
                    
                    @if($user->id === Auth::id())
                        <a href="{{ route('profile.edit') }}" 
                           class="bg-white text-blue-600 px-6 py-2 rounded-lg font-medium hover:bg-blue-50 transition-colors flex items-center">
                            <i class="fas fa-edit mr-2"></i>
                            Edit Profile
                        </a>
                    @endif
                    
                    <a href="{{ route('profile.qr', $user) }}" 
                       class="bg-blue-500 text-white px-6 py-2 rounded-lg font-medium hover:bg-blue-400 transition-colors flex items-center">
                        <i class="fas fa-qrcode mr-2"></i>
                        View QR
                    </a>
                </div>
            </div>
        </div>

        <!-- Profile Details -->
        <div class="p-6">
            <div class="grid md:grid-cols-2 gap-6">
                <!-- Personal Information -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                        <i class="fas fa-user-circle text-blue-600 mr-2"></i>
                        Personal Information
                    </h3>
                    <div class="space-y-3">
                        @if($user->phone)
                        <div class="flex items-center">
                            <i class="fas fa-phone text-gray-400 mr-3 w-5"></i>
                            <span class="text-gray-700">{{ $user->phone }}</span>
                        </div>
                        @endif

                        @if($user->dob)
                        <div class="flex items-center">
                            <i class="fas fa-birthday-cake text-gray-400 mr-3 w-5"></i>
                            <span class="text-gray-700">{{ \Carbon\Carbon::parse($user->dob)->format('F j, Y') }}</span>
                        </div>
                        @endif

                        @if($user->gender)
                        <div class="flex items-center">
                            <i class="fas fa-venus-mars text-gray-400 mr-3 w-5"></i>
                            <span class="text-gray-700">{{ $user->gender }}</span>
                        </div>
                        @endif

                        @if($user->blood_group)
                        <div class="flex items-center">
                            <i class="fas fa-tint text-gray-400 mr-3 w-5"></i>
                            <span class="text-gray-700">{{ $user->blood_group }}</span>
                        </div>
                        @endif

                        @if($user->address)
                        <div class="flex items-start">
                            <i class="fas fa-map-marker-alt text-gray-400 mr-3 w-5 mt-1"></i>
                            <span class="text-gray-700">{{ $user->address }}</span>
                        </div>
                        @endif

                        @if($user->state)
                        <div class="flex items-center">
                            <i class="fas fa-map text-gray-400 mr-3 w-5"></i>
                            <span class="text-gray-700">{{ $user->state }}</span>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Professional Information -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                        <i class="fas fa-briefcase text-blue-600 mr-2"></i>
                        Professional Information
                    </h3>
                    <div class="space-y-3">
                        @if($user->employee_id)
                        <div class="flex items-center">
                            <i class="fas fa-id-card text-gray-400 mr-3 w-5"></i>
                            <span class="text-gray-700">{{ $user->employee_id }}</span>
                        </div>
                        @endif

                        @if($user->designation)
                        <div class="flex items-center">
                            <i class="fas fa-user-tie text-gray-400 mr-3 w-5"></i>
                            <span class="text-gray-700">{{ $user->designation }}</span>
                        </div>
                        @endif

                        @if($user->role)
                        <div class="flex items-center">
                            <i class="fas fa-user-cog text-gray-400 mr-3 w-5"></i>
                            <span class="text-gray-700">{{ ucfirst($user->role) }}</span>
                        </div>
                        @endif

                        @if($user->doj)
                        <div class="flex items-center">
                            <i class="fas fa-calendar-check text-gray-400 mr-3 w-5"></i>
                            <span class="text-gray-700">Joined {{ \Carbon\Carbon::parse($user->doj)->format('F j, Y') }}</span>
                        </div>
                        @endif

                        <div class="flex items-center">
                            <i class="fas fa-clock text-gray-400 mr-3 w-5"></i>
                            <span class="text-gray-700">Member since {{ $user->created_at->format('F Y') }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Activity -->
            @if($user->id !== Auth::id())
            <div class="mt-8 pt-6 border-t border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                    <i class="fas fa-clock text-blue-600 mr-2"></i>
                    Quick Actions
                </h3>
                <div class="flex flex-wrap gap-3">
                    <a href="{{ route('chat.with', $user) }}" 
                       class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-blue-700 transition-colors flex items-center">
                        <i class="fas fa-comments mr-2"></i>
                        Send Message
                    </a>
                    <a href="mailto:{{ $user->email }}" 
                       class="bg-gray-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-gray-700 transition-colors flex items-center">
                        <i class="fas fa-envelope mr-2"></i>
                        Send Email
                    </a>
                    @if($user->phone)
                    <a href="tel:{{ $user->phone }}" 
                       class="bg-green-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-green-700 transition-colors flex items-center">
                        <i class="fas fa-phone mr-2"></i>
                        Call
                    </a>
                    @endif
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
