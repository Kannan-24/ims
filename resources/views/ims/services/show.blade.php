@php
    $title = 'Service Details';
@endphp

<x-app-layout :title="$title">
    <div class="bg-white min-h-screen" x-data="serviceShowManager()" x-init="init()">
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
                            <a href="{{ route('services.index') }}"
                                class="text-sm font-medium text-gray-700 hover:text-blue-600">
                                Services
                            </a>
                        </div>
                    </li>
                    <li aria-current="page">
                        <div class="flex items-center">
                            <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                            <span class="text-sm font-medium text-gray-500">{{ $service->name }}</span>
                        </div>
                    </li>
                </ol>
            </nav>
        </div>

        <!-- Header -->
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">{{ $service->name }}</h1>
                    <p class="text-sm text-gray-600 mt-1">Service details and tax information</p>
                </div>
                <div class="flex items-center space-x-3">

                    <!-- Action Buttons -->
                    <a href="{{ route('services.edit', $service) }}"
                        class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors">
                        <i class="fas fa-edit w-4 h-4 mr-2"></i>
                        Edit Service
                    </a>
                    <form action="{{ route('services.destroy', $service) }}" method="POST" class="inline"
                        onsubmit="return confirm('Are you sure you want to delete this service? This action cannot be undone.')">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                            class="inline-flex items-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg transition-colors">
                            <i class="fas fa-trash w-4 h-4 mr-2"></i>
                            Delete Service
                        </button>
                    </form>
                    <a href="{{ route('services.index') }}"
                        class="inline-flex items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium rounded-lg transition-colors">
                        <i class="fas fa-arrow-left w-4 h-4 mr-2"></i>
                        Back to Services
                    </a>
                </div>
            </div>
        </div>

        <!-- Content -->
        <div class="p-6">
            <!-- Service Information Card -->
            <div class="bg-white border border-gray-200 rounded-lg p-6 mb-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Service Information</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-1">Service Name</label>
                        <p class="text-lg font-semibold text-gray-900">{{ $service->name }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-1">HSN Code</label>
                        <p class="text-lg font-semibold text-gray-900">{{ $service->hsn_code ?? 'N/A' }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-1">Tax Type</label>
                        <span
                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                            GST
                        </span>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-1">Tax Percentage</label>
                        <p class="text-lg font-semibold text-gray-900">{{ $service->gst_percentage ?? '0' }}%</p>
                    </div>

                    <div class="md:col-span-2 lg:col-span-1">
                        <label class="block text-sm font-medium text-gray-500 mb-1">Description</label>
                        <p class="text-gray-900">{{ $service->description ?: 'No description available' }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function serviceShowManager() {
            return {
                init() {
                    // Initialization code if needed
                },
            };
        }
    </script>
</x-app-layout>
