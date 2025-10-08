<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $user->name }} - Employee Profile | SKM and Company</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcode-generator/1.4.4/qrcode.min.js"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        'inter': ['Inter', 'sans-serif'],
                    },
                    colors: {
                        primary: '#1e293b',
                        secondary: '#64748b',
                        accent: '#0ea5e9',
                        light: '#f8fafc'
                    }
                }
            }
        }
    </script>
</head>
<body class="font-inter bg-gray-50 text-gray-900 min-h-screen">
    <!-- Header -->
    <header class="bg-white shadow-sm border-b border-gray-200">
        <div class="w-full px-6 py-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <div class="w-10 h-10 bg-accent rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-xl font-semibold text-primary">SKM and Company</h1>
                        <p class="text-sm text-secondary">Employee Profile Card</p>
                    </div>
                </div>
                <div class="flex items-center space-x-2">
                    <span class="px-3 py-1 bg-green-100 text-green-700 text-xs font-medium rounded-full">
                        ✓ Verified
                    </span>
                    <span class="px-3 py-1 bg-blue-100 text-blue-700 text-xs font-medium rounded-full">
                        Digital Card
                    </span>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="w-full">
        <!-- Profile Header Section -->
        <section class="bg-white border-b border-gray-200">
            <div class="w-full px-6 py-8">
                <div class="flex flex-col lg:flex-row items-start gap-8">
                    <!-- Left: Profile Information -->
                    <div class="flex-1">
                        <div class="flex flex-col sm:flex-row items-start gap-6">
                            <!-- Profile Photo -->
                            <div class="flex-shrink-0">
                                @if($user->profile_photo)
                                    <img src="{{ asset('storage/' . $user->profile_photo) }}" 
                                         alt="{{ $user->name }}" 
                                         class="w-32 h-32 rounded-2xl border-2 border-gray-200 object-cover shadow-sm">
                                @else
                                    <div class="w-32 h-32 rounded-2xl border-2 border-gray-200 bg-gradient-to-br from-blue-100 to-blue-200 flex items-center justify-center shadow-sm">
                                        <span class="text-blue-700 text-4xl font-bold">
                                            {{ substr($user->name, 0, 1) }}
                                        </span>
                                    </div>
                                @endif
                            </div>

                            <!-- Name and Basic Info -->
                            <div class="flex-1 min-w-0">
                                <h2 class="text-3xl font-bold text-primary mb-2">{{ $user->name }}</h2>
                                @if($user->designation)
                                    <p class="text-xl text-accent font-semibold mb-3">{{ $user->designation }}</p>
                                @endif
                                
                                <div class="space-y-2">
                                    @if($user->employee_id)
                                        <div class="flex items-center">
                                            <div class="w-6 h-6 bg-gray-100 rounded-md flex items-center justify-center mr-3">
                                                <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"/>
                                                </svg>
                                            </div>
                                            <span class="text-sm font-medium text-secondary">Employee ID:</span>
                                            <span class="ml-2 text-sm font-semibold text-primary">{{ $user->employee_id }}</span>
                                        </div>
                                    @endif
                                    
                                    <div class="flex items-center">
                                        <div class="w-6 h-6 bg-gray-100 rounded-md flex items-center justify-center mr-3">
                                            <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                            </svg>
                                        </div>
                                        <span class="text-sm font-medium text-secondary">Company:</span>
                                        <span class="ml-2 text-sm font-semibold text-primary">SKM and Company</span>
                                    </div>
                                    
                                    @if($user->doj)
                                        <div class="flex items-center">
                                            <div class="w-6 h-6 bg-gray-100 rounded-md flex items-center justify-center mr-3">
                                                <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                </svg>
                                            </div>
                                            <span class="text-sm font-medium text-secondary">Joined:</span>
                                            <span class="ml-2 text-sm font-semibold text-primary">{{ \Carbon\Carbon::parse($user->doj)->format('M d, Y') }}</span>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Right: QR Code -->
                    <div class="flex-shrink-0">
                        <div class="bg-gray-50 p-6 rounded-2xl border border-gray-200 text-center">
                            <div id="qrcode" class="mx-auto mb-3"></div>
                            <p class="text-xs font-medium text-secondary">Scan for vCard</p>
                            <p class="text-xs text-gray-400 mt-1">Save to Contacts</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Information Grid -->
        <section class="w-full px-6 py-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Contact Information -->
                <div class="bg-white rounded-xl border border-gray-200 p-6 shadow-sm">
                    <h3 class="text-lg font-semibold text-primary mb-4 flex items-center">
                        <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center mr-3">
                            <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                        </div>
                        Contact Details
                    </h3>
                    <div class="space-y-4">
                        <div class="flex items-center p-3 bg-gray-50 rounded-lg">
                            <svg class="w-5 h-5 mr-3 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                            <div>
                                <p class="text-xs text-gray-500 font-medium">Email</p>
                                <p class="text-sm font-semibold text-primary">{{ $user->email }}</p>
                            </div>
                        </div>
                        
                        @if($user->phone)
                            <div class="flex items-center p-3 bg-gray-50 rounded-lg">
                                <svg class="w-5 h-5 mr-3 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                </svg>
                                <div>
                                    <p class="text-xs text-gray-500 font-medium">Phone</p>
                                    <p class="text-sm font-semibold text-primary">{{ $user->phone }}</p>
                                </div>
                            </div>
                        @endif
                        
                        @if($user->address)
                            <div class="flex items-start p-3 bg-gray-50 rounded-lg">
                                <svg class="w-5 h-5 mr-3 mt-1 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                                <div>
                                    <p class="text-xs text-gray-500 font-medium">Address</p>
                                    <p class="text-sm font-semibold text-primary">{{ $user->address }}</p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Professional Information -->
                <div class="bg-white rounded-xl border border-gray-200 p-6 shadow-sm">
                    <h3 class="text-lg font-semibold text-primary mb-4 flex items-center">
                        <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center mr-3">
                            <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2-2v2m8 0V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m8 0H8m0 0v2a2 2 0 002 2h4a2 2 0 002-2V6m-8 0h8"/>
                            </svg>
                        </div>
                        Professional Info
                    </h3>
                    <div class="space-y-4">
                        @if($user->designation)
                            <div class="p-3 bg-gray-50 rounded-lg">
                                <p class="text-xs text-gray-500 font-medium">Position</p>
                                <p class="text-sm font-semibold text-primary">{{ $user->designation }}</p>
                            </div>
                        @endif
                        
                        @if($user->role)
                            <div class="p-3 bg-gray-50 rounded-lg">
                                <p class="text-xs text-gray-500 font-medium">Department</p>
                                <p class="text-sm font-semibold text-primary">{{ $user->role }}</p>
                            </div>
                        @endif
                        
                        @if($user->state)
                            <div class="p-3 bg-gray-50 rounded-lg">
                                <p class="text-xs text-gray-500 font-medium">Location</p>
                                <p class="text-sm font-semibold text-primary">{{ $user->state }}</p>
                            </div>
                        @endif
                        
                        @if($user->doj)
                            <div class="p-3 bg-gray-50 rounded-lg">
                                <p class="text-xs text-gray-500 font-medium">Experience</p>
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
                        @endif
                    </div>
                </div>

                <!-- Personal Information -->
                @if($user->gender || $user->blood_group || $user->dob)
                    <div class="bg-white rounded-xl border border-gray-200 p-6 shadow-sm">
                        <h3 class="text-lg font-semibold text-primary mb-4 flex items-center">
                            <div class="w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center mr-3">
                                <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                            </div>
                            Personal Info
                        </h3>
                        <div class="space-y-4">
                            @if($user->gender)
                                <div class="p-3 bg-gray-50 rounded-lg">
                                    <p class="text-xs text-gray-500 font-medium">Gender</p>
                                    <p class="text-sm font-semibold text-primary">{{ $user->gender }}</p>
                                </div>
                            @endif
                            
                            @if($user->blood_group)
                                <div class="p-3 bg-gray-50 rounded-lg">
                                    <p class="text-xs text-gray-500 font-medium">Blood Group</p>
                                    <p class="text-sm font-semibold text-primary">{{ $user->blood_group }}</p>
                                </div>
                            @endif
                            
                            @if($user->dob)
                                <div class="p-3 bg-gray-50 rounded-lg">
                                    <p class="text-xs text-gray-500 font-medium">Date of Birth</p>
                                    <p class="text-sm font-semibold text-primary">{{ \Carbon\Carbon::parse($user->dob)->format('M d, Y') }}</p>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif
            </div>
        </section>

        <!-- Simple Footer -->
        <section class="w-full px-6 py-8">
            <div class="bg-white rounded-xl border border-gray-200 p-6 text-center shadow-sm">
                <div class="flex items-center justify-center mb-3">
                    <svg class="w-5 h-5 text-accent mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                    </svg>
                    <span class="text-lg font-semibold text-primary">Employee Profile Verified</span>
                </div>
                <p class="text-secondary mb-3">
                    This digital employee profile is verified and maintained by SKM and Company.
                </p>
                <div class="text-xs text-gray-400">
                    Generated on {{ now()->format('M d, Y \a\t g:i A') }}
                </div>
            </div>
        </section>
    </main>

    <!-- QR Code Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Create vCard data
            const vCardData = `BEGIN:VCARD
VERSION:3.0
FN:{{ $user->name }}
ORG:SKM and Company
EMAIL:{{ $user->email }}
TEL:{{ $user->phone ?? '' }}
ADR:;;{{ $user->address ?? '' }};;;;
URL:www.skmandcompany.com
NOTE:Employee ID: {{ $user->employee_id ?? '' }}
END:VCARD`;

            // Generate QR code using qrcode-generator library
            const qr = qrcode(0, 'M');
            qr.addData(vCardData);
            qr.make();
            
            // Create QR code element
            const qrElement = document.getElementById('qrcode');
            qrElement.innerHTML = qr.createImgTag(4, 8);
            
            // Style the QR code image
            const img = qrElement.querySelector('img');
            if (img) {
                img.style.width = '120px';
                img.style.height = '120px';
                img.style.border = '2px solid #e5e7eb';
                img.style.borderRadius = '8px';
            }
        });
    </script>
</body>
</html>