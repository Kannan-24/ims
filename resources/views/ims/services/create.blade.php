@php
    $title = 'Create Service';
@endphp

<x-app-layout :title="$title">
    <div class="bg-white min-h-screen" x-data="serviceCreateManager()" x-init="init()">
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
                            <span class="text-sm font-medium text-gray-500">Create</span>
                        </div>
                    </li>
                </ol>
            </nav>
        </div>

        <!-- Header -->
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Create New Service</h1>
                    <p class="text-sm text-gray-600 mt-1">Add a new service to your offerings</p>
                </div>
                <div class="flex items-center space-x-3">
                    <!-- Help Button -->
                    <button @click="showHelpModal = true"
                        class="inline-flex items-center px-3 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium rounded-lg transition-colors">
                        <i class="fas fa-question-circle w-4 h-4 mr-2"></i>
                        Help
                    </button>
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
            <form action="{{ route('services.store') }}" method="POST" id="serviceForm" @submit.prevent="submitForm">
                @csrf

                <!-- Service Information -->
                <div class="bg-white border border-gray-200 rounded-lg p-6 mb-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Service Information</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Service Name <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="name" required value="{{ old('name') }}"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                placeholder="Enter service name">
                            @error('name')
                                <div class="mt-1 text-sm text-red-600">{{ $message }}</div>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                HSN Code <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="hsn_code" required value="{{ old('hsn_code') }}"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                placeholder="Enter HSN code">
                            @error('hsn_code')
                                <div class="mt-1 text-sm text-red-600">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Description
                            </label>
                            <textarea name="description" rows="3"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                placeholder="Enter service description">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="mt-1 text-sm text-red-600">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Tax Information -->
                <div class="bg-white border border-gray-200 rounded-lg p-6 mb-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Tax Information</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                GST Percentage (%) <span class="text-red-500">*</span>
                            </label>
                            <input type="number" step="0.01" name="gst_percentage" required value="{{ old('gst_percentage') }}"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                placeholder="Enter GST percentage">
                            @error('gst_percentage')
                                <div class="mt-1 text-sm text-red-600">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="flex items-center justify-between">
                    <div class="text-sm text-gray-500">
                        <kbd class="px-2 py-1 bg-gray-100 rounded text-xs">Ctrl+S</kbd> to save •
                        <kbd class="px-2 py-1 bg-gray-100 rounded text-xs">Esc</kbd> to cancel
                    </div>
                    <div class="flex items-center space-x-3">
                        <a href="{{ route('services.index') }}"
                            class="px-6 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 font-medium transition-colors">
                            Cancel
                        </a>
                        <button type="submit" :disabled="isSubmitting"
                            class="px-6 py-3 bg-blue-600 hover:bg-blue-700 disabled:bg-blue-400 text-white rounded-lg font-medium transition-colors inline-flex items-center">
                            <span x-show="!isSubmitting">
                                <i class="fas fa-save mr-2"></i>
                                Create Service
                            </span>
                            <span x-show="isSubmitting" class="inline-flex items-center">
                                <svg class="animate-spin -ml-1 mr-3 h-4 w-4 text-white"
                                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10"
                                        stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor"
                                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                    </path>
                                </svg>
                                Creating...
                            </span>
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Help Modal -->
        <div x-show="showHelpModal" x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-white rounded-lg shadow-xl max-w-4xl w-full mx-4 max-h-[90vh] overflow-y-auto">
                <div class="flex items-center justify-between p-6 border-b border-gray-200">
                    <h2 class="text-xl font-bold text-gray-900">Create Service Help</h2>
                    <button @click="showHelpModal = false" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>

                <div class="p-6 space-y-6">
                    <!-- Required Fields -->
                    <div class="border border-gray-200 rounded-lg p-4">
                        <h3 class="text-lg font-semibold text-gray-900 mb-3">
                            <i class="fas fa-exclamation-circle text-red-600 mr-2"></i>Required Fields
                        </h3>
                        <div class="space-y-2 text-sm text-gray-700">
                            <div>• <strong>Service Name:</strong> Unique name for the service</div>
                            <div>• <strong>HSN Code:</strong> Harmonized System of Nomenclature code for tax purposes</div>
                            <div>• <strong>GST Percentage:</strong> Tax rate applicable to this service</div>
                        </div>
                    </div>

                    <!-- Field Guidelines -->
                    <div class="border border-gray-200 rounded-lg p-4">
                        <h3 class="text-lg font-semibold text-gray-900 mb-3">
                            <i class="fas fa-info-circle text-blue-600 mr-2"></i>Field Guidelines
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm text-gray-700">
                            <div class="space-y-2">
                                <div><strong>Service Name:</strong> Use descriptive, unique names</div>
                                <div><strong>Description:</strong> Optional but recommended for clarity</div>
                                <div><strong>HSN Code:</strong> Verify accuracy for tax compliance</div>
                            </div>
                            <div class="space-y-2">
                                <div><strong>GST Rate:</strong> Standard rates: 0%, 5%, 12%, 18%, 28%</div>
                            </div>
                        </div>
                    </div>

                    <!-- Keyboard Shortcuts -->
                    <div class="border border-gray-200 rounded-lg p-4">
                        <h3 class="text-lg font-semibold text-gray-900 mb-3">
                            <i class="fas fa-keyboard text-green-600 mr-2"></i>Keyboard Shortcuts
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="space-y-2">
                                <div class="flex items-center justify-between">
                                    <span class="text-gray-700">Save Service</span>
                                    <kbd class="px-2 py-1 bg-gray-100 border border-gray-300 rounded text-sm">Ctrl + S</kbd>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="text-gray-700">Cancel & Exit</span>
                                    <kbd class="px-2 py-1 bg-gray-100 border border-gray-300 rounded text-sm">Esc</kbd>
                                </div>
                            </div>
                            <div class="space-y-2">
                                <div class="flex items-center justify-between">
                                    <span class="text-gray-700">Show Help</span>
                                    <kbd class="px-2 py-1 bg-gray-100 border border-gray-300 rounded text-sm">H</kbd>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="text-gray-700">Focus Next Field</span>
                                    <kbd class="px-2 py-1 bg-gray-100 border border-gray-300 rounded text-sm">Tab</kbd>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex items-center justify-end px-6 py-4 bg-gray-50 border-t border-gray-200">
                    <button @click="showHelpModal = false"
                        class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                        Got it!
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        function serviceCreateManager() {
            return {
                showHelpModal: false,
                isSubmitting: false,

                init() {
                    this.bindKeyboardEvents();
                },

                bindKeyboardEvents() {
                    document.addEventListener('keydown', (e) => {
                        if ((e.ctrlKey || e.metaKey) && e.key.toLowerCase() === 's') {
                            e.preventDefault();
                            this.submitForm();
                        }

                        if (e.key.toLowerCase() === 'h' && !e.ctrlKey && !e.altKey) {
                            e.preventDefault();
                            this.showHelpModal = true;
                        }

                        if (e.key === 'Escape') {
                            e.preventDefault();
                            if (this.showHelpModal) {
                                this.showHelpModal = false;
                            } else if (confirm('Are you sure you want to cancel? All data will be lost.')) {
                                window.location.href = '{{ route('services.index') }}';
                            }
                        }
                    });
                },

                submitForm() {
                    if (this.isSubmitting) return;

                    this.isSubmitting = true;
                    document.getElementById('serviceForm').submit();
                }
            }
        }
    </script>
</x-app-layout>
