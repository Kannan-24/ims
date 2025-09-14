<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $user->name }} - Professional Profile</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        'inter': ['Inter', 'sans-serif'],
                    },
                    colors: {
                        primary: '#1f2937',
                        secondary: '#6b7280',
                        accent: '#3b82f6',
                        light: '#f8fafc'
                    }
                }
            }
        }
    </script>
</head>
<body class="font-inter bg-gray-50 text-gray-900">
    <!-- Header -->
    <header class="bg-white shadow-sm border-b border-gray-200">
        <div class="max-w-4xl mx-auto px-4 py-6 text-center">
            <h1 class="text-2xl font-semibold text-primary">Professional Profile</h1>
            <p class="text-sm text-secondary mt-1">Shared via QR Code</p>
        </div>
    </header>

    <!-- Main Content -->
    <main class="max-w-4xl mx-auto px-4 py-8">
        <!-- Profile Section -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-8 mb-8">
            <div class="flex flex-col items-center text-center">
                <!-- Profile Image -->
                @if($user->profile_photo)
                    <img src="{{ asset('storage/' . $user->profile_photo) }}" 
                         alt="{{ $user->name }}" 
                         class="w-24 h-24 rounded-full border-2 border-gray-200 object-cover mb-4">
                @else
                    <div class="w-24 h-24 rounded-full border-2 border-gray-200 bg-gray-100 flex items-center justify-center mb-4">
                        <span class="text-gray-600 text-2xl font-semibold">
                            {{ substr($user->name, 0, 1) }}
                        </span>
                    </div>
                @endif

                <!-- Name and Title -->
                <h2 class="text-2xl font-bold text-primary mb-2">{{ $user->name }}</h2>
                @if($user->designation)
                    <p class="text-lg text-accent font-medium mb-1">{{ $user->designation }}</p>
                @endif
                @if($user->employee_id)
                    <p class="text-sm text-secondary">Employee ID: {{ $user->employee_id }}</p>
                @endif
            </div>
        </div>

        <!-- Information Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            <!-- Contact Information -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-primary mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                    Contact Information
                </h3>
                <div class="space-y-3">
                    <div class="flex items-center">
                        <svg class="w-4 h-4 mr-3 text-secondary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                        <span class="text-gray-700">{{ $user->email }}</span>
                    </div>
                    @if($user->phone)
                        <div class="flex items-center">
                            <svg class="w-4 h-4 mr-3 text-secondary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                            </svg>
                            <span class="text-gray-700">{{ $user->phone }}</span>
                        </div>
                    @endif
                    @if($user->address)
                        <div class="flex items-start">
                            <svg class="w-4 h-4 mr-3 mt-1 text-secondary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            <span class="text-gray-700">{{ $user->address }}</span>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Professional Information -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-primary mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2-2v2m8 0V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m8 0H8m0 0v2a2 2 0 002 2h4a2 2 0 002-2V6m-8 0h8"/>
                    </svg>
                    Professional Details
                </h3>
                <div class="space-y-3">
                    @if($user->designation)
                        <div>
                            <span class="text-sm font-medium text-secondary">Position:</span>
                            <p class="text-gray-700">{{ $user->designation }}</p>
                        </div>
                    @endif
                    @if($user->role)
                        <div>
                            <span class="text-sm font-medium text-secondary">Department:</span>
                            <p class="text-gray-700">{{ $user->role }}</p>
                        </div>
                    @endif
                    @if($user->doj)
                        <div>
                            <span class="text-sm font-medium text-secondary">Date Joined:</span>
                            <p class="text-gray-700">{{ \Carbon\Carbon::parse($user->doj)->format('M d, Y') }}</p>
                        </div>
                    @endif
                    @if($user->state)
                        <div>
                            <span class="text-sm font-medium text-secondary">Location:</span>
                            <p class="text-gray-700">{{ $user->state }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Additional Information -->
        @if($user->gender || $user->blood_group || $user->dob)
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-8">
                <h3 class="text-lg font-semibold text-primary mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Additional Information
                </h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                    @if($user->gender)
                        <div>
                            <span class="text-sm font-medium text-secondary">Gender:</span>
                            <p class="text-gray-700">{{ $user->gender }}</p>
                        </div>
                    @endif
                    @if($user->blood_group)
                        <div>
                            <span class="text-sm font-medium text-secondary">Blood Group:</span>
                            <p class="text-gray-700">{{ $user->blood_group }}</p>
                        </div>
                    @endif
                    @if($user->dob)
                        <div>
                            <span class="text-sm font-medium text-secondary">Date of Birth:</span>
                            <p class="text-gray-700">{{ \Carbon\Carbon::parse($user->dob)->format('M d, Y') }}</p>
                        </div>
                    @endif
                </div>
            </div>
        @endif

        <!-- Footer -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 text-center">
            <div class="flex items-center justify-center mb-3">
                <svg class="w-5 h-5 text-accent mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                </svg>
                <span class="text-lg font-semibold text-primary">Profile Shared Successfully</span>
            </div>
            <p class="text-secondary mb-3">
                This profile was shared via QR code. For more information, please contact the individual directly.
            </p>
            <div class="text-xs text-secondary">
                Generated on {{ now()->format('M d, Y \a\t g:i A') }}
            </div>
        </div>
    </main>
</body>
</html>