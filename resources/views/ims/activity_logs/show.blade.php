<x-app-layout>
    <x-slot name="title">
        {{ __('Activity Log Details') }} - {{ config('app.name', 'IMS') }}
    </x-slot>

    <div class="bg-white min-h-screen" x-data="activityLogManager()" x-init="init()">
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
                            <a href="{{ route('activity-logs.index') }}"
                                class="text-sm font-medium text-gray-700 hover:text-blue-600">
                                Activity Logs
                            </a>
                        </div>
                    </li>
                    <li aria-current="page">
                        <div class="flex items-center">
                            <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                            <span class="text-sm font-medium text-gray-500">Log #{{ $log->id }}</span>
                        </div>
                    </li>
                </ol>
            </nav>
        </div>

        <!-- Header -->
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Activity Log Details</h1>
                    <p class="text-sm text-gray-600 mt-1">Detailed information about this activity</p>
                </div>
                <div class="flex items-center space-x-3">
                    <a href="{{ route('activity-logs.index') }}"
                        class="inline-flex items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium rounded-lg transition-colors">
                        <i class="fas fa-arrow-left w-4 h-4 mr-2"></i>
                        Back to Logs
                    </a>
                </div>
            </div>
        </div>

        <!-- Content -->
        <div class="p-6">
            <!-- Activity Profile Card -->
            <div class="bg-white border border-gray-200 rounded-lg shadow-sm overflow-hidden mb-6">
                <div class="bg-gradient-to-r from-indigo-500 to-purple-600 px-6 py-8 text-white">
                    <div class="flex items-center">
                        <div class="w-16 h-16 bg-white bg-opacity-20 rounded-xl flex items-center justify-center mr-4">
                            <i class="fas fa-history text-3xl"></i>
                        </div>
                        <div>
                            <h2 class="text-2xl font-bold">{{ $log->action_type }}</h2>
                            <p class="text-indigo-100 mt-1">Log ID: {{ $log->id }}</p>
                            <div class="mt-2">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-white bg-opacity-20 text-white">
                                    <i class="fas fa-cogs mr-1"></i>
                                    {{ $log->module ?? 'System' }} Module
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                    <div class="flex items-center space-x-4">
                        <span class="text-sm font-medium text-gray-700">Quick Actions:</span>
                        <a href="{{ route('activity-logs.index') }}"
                            class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                            <i class="fas fa-list mr-1"></i>View All Logs
                        </a>
                        <div class="text-gray-300">|</div>
                        <span class="text-xs text-gray-500">
                            <kbd class="px-1 py-0.5 bg-gray-200 rounded text-xs">Esc</kbd> to go back
                        </span>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Activity Information -->
                <div class="lg:col-span-2">
                    <div class="bg-white border border-gray-200 rounded-lg p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">
                            <i class="fas fa-info-circle mr-2 text-blue-500"></i>
                            Activity Information
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-500 mb-1">Action Type</label>
                                    <p class="text-gray-900 font-medium">{{ $log->action_type }}</p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-500 mb-1">Log ID</label>
                                    <p class="text-gray-900 font-mono">{{ $log->id }}</p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-500 mb-1">Module</label>
                                    <p class="text-gray-900">{{ $log->module ?? 'System' }}</p>
                                </div>
                            </div>
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-500 mb-1">User</label>
                                    <p class="text-gray-900">{{ $log->user->name ?? 'System User' }}</p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-500 mb-1">User Type</label>
                                    <p class="text-gray-900">{{ $log->user_type ?? 'System' }}</p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-500 mb-1">IP Address</label>
                                    <p class="text-gray-900 font-mono">{{ $log->ip_address ?? 'Not Available' }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="mt-6 pt-6 border-t border-gray-200">
                            <label class="block text-sm font-medium text-gray-500 mb-2">Timestamp</label>
                            <p class="text-gray-900 font-mono">{{ $log->created_at->format('M d, Y h:i A') }}</p>
                        </div>

                        @if($log->description)
                        <div class="mt-6 pt-6 border-t border-gray-200">
                            <label class="block text-sm font-medium text-gray-500 mb-2">Description</label>
                            <p class="text-gray-900 leading-relaxed">{{ $log->description }}</p>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Summary Stats -->
                <div class="space-y-6">
                    <div class="bg-white border border-gray-200 rounded-lg p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">
                            <i class="fas fa-chart-bar mr-2 text-green-500"></i>
                            Quick Stats
                        </h3>
                        <div class="space-y-4">
                            <div class="flex items-center justify-between p-3 bg-blue-50 rounded-lg">
                                <div class="flex items-center">
                                    <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center mr-3">
                                        <i class="fas fa-user text-blue-600 text-sm"></i>
                                    </div>
                                    <span class="text-sm font-medium text-gray-700">User Type</span>
                                </div>
                                <span class="text-sm font-medium text-blue-600">{{ $log->user_type ?? 'System' }}</span>
                            </div>

                            <div class="flex items-center justify-between p-3 bg-green-50 rounded-lg">
                                <div class="flex items-center">
                                    <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center mr-3">
                                        <i class="fas fa-clock text-green-600 text-sm"></i>
                                    </div>
                                    <span class="text-sm font-medium text-gray-700">Time Ago</span>
                                </div>
                                <span class="text-sm font-medium text-green-600">{{ $log->created_at->diffForHumans() }}</span>
                            </div>

                            @if($log->ip_address)
                            <div class="flex items-center justify-between p-3 bg-purple-50 rounded-lg">
                                <div class="flex items-center">
                                    <div class="w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center mr-3">
                                        <i class="fas fa-network-wired text-purple-600 text-sm"></i>
                                    </div>
                                    <span class="text-sm font-medium text-gray-700">IP Address</span>
                                </div>
                                <span class="text-sm font-medium text-purple-600 font-mono">{{ $log->ip_address }}</span>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Timeline -->
            <div class="mt-8">
                <div class="bg-white border border-gray-200 rounded-lg overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">
                            <i class="fas fa-clock mr-2 text-orange-500"></i>
                            Activity Timeline
                        </h3>
                    </div>

                    <div class="p-6">
                        <div class="relative">
                            <div class="absolute left-4 top-0 bottom-0 w-0.5 bg-gradient-to-b from-indigo-500 to-purple-600"></div>
                            <div class="flex items-center space-x-4 relative">
                                <div class="w-8 h-8 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-full flex items-center justify-center shadow-lg z-10">
                                    <i class="fas fa-play text-white text-xs"></i>
                                </div>
                                <div class="flex-1 bg-gray-50 rounded-lg p-4 border border-gray-200">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <p class="font-semibold text-gray-900">Activity Logged</p>
                                            <p class="text-sm text-gray-600">{{ $log->action_type }} performed in {{ $log->module ?? 'System' }}</p>
                                            @if($log->user)
                                            <p class="text-xs text-gray-500 mt-1">by {{ $log->user->name }}</p>
                                            @endif
                                        </div>
                                        <div class="text-right text-xs text-gray-500">
                                            <p>{{ $log->created_at->format('H:i:s') }}</p>
                                            <p>{{ $log->created_at->format('M d, Y') }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function activityLogManager() {
            return {
                init() {
                    this.bindKeyboardEvents();
                },

                bindKeyboardEvents() {
                    document.addEventListener('keydown', (e) => {
                        // Ignore keyboard shortcuts when typing in input fields
                        if (e.target.tagName === 'INPUT' || e.target.tagName === 'TEXTAREA') {
                            return;
                        }

                        switch (e.key.toLowerCase()) {
                            case 'escape':
                                e.preventDefault();
                                window.location.href = '{{ route('activity-logs.index') }}';
                                break;
                        }
                    });
                }
            }
        }
    </script>
</x-app-layout>
