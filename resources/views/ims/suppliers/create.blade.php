<x-app-layout>

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
                        <a href="{{ route('suppliers.index') }}"
                            class="text-sm font-medium text-gray-700 hover:text-blue-600">
                            Suppliers
                        </a>
                    </div>
                </li>
                <li aria-current="page">
                    <div class="flex items-center">
                        <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                        <span class="text-sm font-medium text-gray-500">Create Supplier</span>
                    </div>
                </li>
            </ol>
        </nav>
    </div>

    <!-- Header -->
    <div class="px-6 py-4 border-b border-gray-200">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Create New Supplier</h1>
                <p class="text-sm text-gray-600 mt-1">Add supplier information and contact details</p>
            </div>
            <div class="flex items-center space-x-3">
                <a href="{{ route('suppliers.index') }}"
                    class="inline-flex items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium rounded-lg transition-colors">
                    <i class="fas fa-arrow-left w-4 h-4 mr-2"></i>
                    Back to List
                </a>
            </div>
        </div>
    </div>

    <!-- Content -->
    <div class="p-6">
        <form action="{{ route('suppliers.store') }}" method="POST" id="supplierForm" x-data="supplierCreateManager()"
            x-init="init()" @submit.prevent="submitForm">
            @csrf

            <!-- Tab Navigation -->
            <div class="border-b border-gray-200 mb-6">
                <nav class="-mb-px flex space-x-8">
                    <button type="button" @click="activeTab = 'info'"
                        :class="activeTab === 'info' ? 'border-blue-500 text-blue-600' :
                            'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                        class="py-2 px-1 border-b-2 font-medium text-sm">
                        <i class="fas fa-building mr-2"></i>
                        Supplier Information
                    </button>
                    <button type="button" @click="activeTab = 'address'"
                        :class="activeTab === 'address' ? 'border-blue-500 text-blue-600' :
                            'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                        class="py-2 px-1 border-b-2 font-medium text-sm">
                        <i class="fas fa-map-marker-alt mr-2"></i>
                        Address Details
                    </button>
                </nav>
            </div>

            <!-- Tab Content -->
            <div class="space-y-6">
                <!-- Supplier Information Tab -->
                <div x-show="activeTab === 'info'" x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
                    <div class="bg-white border border-gray-200 rounded-lg p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Basic Information</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Company Name -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Company Name <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="company_name" id="company_name"
                                    class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent {{ $errors->has('company_name') ? 'border-red-500' : 'border-gray-300' }}"
                                    value="{{ old('company_name') }}" required>
                                @error('company_name')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Supplier ID -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Supplier ID
                                    <span class="text-sm text-gray-500">(Auto-generated)</span>
                                </label>
                                <input type="text" name="supplier_id" id="supplier_id"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg bg-gray-50 text-gray-600 cursor-not-allowed"
                                    x-model="nextSupplierId" readonly>
                                <p class="mt-1 text-xs text-gray-500">Supplier ID will be auto-generated when you save
                                </p>
                            </div>

                            <!-- Contact Person -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Contact Person <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="contact_person" id="contact_person"
                                    class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent {{ $errors->has('contact_person') ? 'border-red-500' : 'border-gray-300' }}"
                                    value="{{ old('contact_person') }}" required>
                                @error('contact_person')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Email Address -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Email Address <span class="text-red-500">*</span>
                                </label>
                                <input type="email" name="email" id="email"
                                    class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent {{ $errors->has('email') ? 'border-red-500' : 'border-gray-300' }}"
                                    value="{{ old('email') }}" required>
                                @error('email')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Phone Number -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Phone Number <span class="text-red-500">*</span>
                                </label>
                                <input type="tel" name="phone" id="phone"
                                    class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent {{ $errors->has('phone') ? 'border-red-500' : 'border-gray-300' }}"
                                    value="{{ old('phone') }}" required>
                                @error('phone')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Website -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Website
                                </label>
                                <input type="url" name="website" id="website"
                                    class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent {{ $errors->has('website') ? 'border-red-500' : 'border-gray-300' }}"
                                    value="{{ old('website') }}" placeholder="https://example.com">
                                @error('website')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- GST Number -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    GST Number
                                    <span class="text-sm text-gray-500">(Optional)</span>
                                </label>
                                <input type="text" name="gst" id="gst"
                                    class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent {{ $errors->has('gst') ? 'border-red-500' : 'border-gray-300' }}"
                                    value="{{ old('gst') }}" placeholder="Enter GST number if available">
                                @error('gst')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Address Details Tab -->
                <div x-show="activeTab === 'address'" x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
                    <div class="bg-white border border-gray-200 rounded-lg p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Address Information</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Street Address <span class="text-red-500">*</span>
                                </label>
                                <textarea name="address" id="address" rows="3"
                                    class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent {{ $errors->has('address') ? 'border-red-500' : 'border-gray-300' }}"
                                    required>{{ old('address') }}</textarea>
                                @error('address')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    City <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="city" id="city"
                                    class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent {{ $errors->has('city') ? 'border-red-500' : 'border-gray-300' }}"
                                    value="{{ old('city') }}" required>
                                @error('city')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    State/Province <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="state" id="state"
                                    class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent {{ $errors->has('state') ? 'border-red-500' : 'border-gray-300' }}"
                                    value="{{ old('state') }}" required>
                                @error('state')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Postal Code
                                </label>
                                <input type="text" name="postal_code" id="postal_code"
                                    class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent {{ $errors->has('postal_code') ? 'border-red-500' : 'border-gray-300' }}"
                                    value="{{ old('postal_code') }}">
                                @error('postal_code')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Country <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="country" id="country"
                                    class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent {{ $errors->has('country') ? 'border-red-500' : 'border-gray-300' }}"
                                    value="{{ old('country') }}" required>
                                @error('country')
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
                    <button type="button" @click="previousTab()" x-show="activeTab !== 'info'"
                        class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">
                        <i class="fas fa-chevron-left mr-2"></i>
                        Previous
                    </button>
                </div>

                <!-- Right side - Next/Submit buttons -->
                <div class="flex items-center space-x-3">
                    <a href="{{ route('suppliers.index') }}"
                        class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">
                        Cancel
                    </a>

                    <!-- Next Button (shown when not on last tab) -->
                    <button type="button" @click="nextTab()" x-show="activeTab === 'info'"
                        class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                        Next
                        <i class="fas fa-chevron-right ml-2"></i>
                    </button>

                    <!-- Submit Button (shown only on last tab) -->
                    <button type="submit" x-show="activeTab === 'address'"
                        class="px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                        <i class="fas fa-save mr-2"></i>
                        Create Supplier
                    </button>
                </div>
            </div>
        </form>
    </div>

    @push('scripts')
        <script>
            function supplierCreateManager() {
                return {
                    activeTab: 'info',
                    nextSupplierId: 'Loading...',

                    init() {
                        this.generateNextSupplierId();

                    },

                    async generateNextSupplierId() {
                        try {
                            // Get the last supplier ID from the server
                            const response = await fetch('/ims/suppliers/next-id');
                            const data = await response.json();
                            this.nextSupplierId = data.nextId;
                        } catch (error) {
                            console.error('Error generating supplier ID:', error);
                            this.nextSupplierId = 'Error loading ID';
                        }
                    },

                    nextTab() {
                        if (this.activeTab === 'info') {
                            this.activeTab = 'address';
                        }
                    },

                    previousTab() {
                        if (this.activeTab === 'address') {
                            this.activeTab = 'info';
                        }
                    },

                    submitForm() {
                        // Submit the form
                        document.getElementById('supplierForm').submit();
                    }
                };
            }
        </script>
    @endpush
</x-app-layout>
