<x-app-layout>
    <div class="bg-white min-h-screen" x-data="supplierEditManager()" x-init="init()">
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
                            <a href="{{ route('suppliers.index') }}"
                                class="text-sm font-medium text-gray-700 hover:text-blue-600">
                                Suppliers
                            </a>
                        </div>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                            <a href="{{ route('suppliers.show', $supplier) }}"
                                class="text-sm font-medium text-gray-700 hover:text-blue-600">
                                {{ $supplier->company_name ?? $supplier->name }}
                            </a>
                        </div>
                    </li>
                    <li aria-current="page">
                        <div class="flex items-center">
                            <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                            <span class="text-sm font-medium text-gray-500">Edit</span>
                        </div>
                    </li>
                </ol>
            </nav>
        </div>

        <!-- Header -->
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Edit Supplier</h1>
                    <p class="text-sm text-gray-600 mt-1">Update supplier information and contact details</p>
                </div>
                <div class="flex items-center space-x-3">
                    <a href="{{ route('suppliers.show', $supplier) }}"
                        class="inline-flex items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium rounded-lg transition-colors">
                        <i class="fas fa-arrow-left w-4 h-4 mr-2"></i>
                        Back to Supplier
                    </a>
                </div>
            </div>
        </div>

        <!-- Content -->
        <div class="p-6">
            <form action="{{ route('suppliers.update', $supplier) }}" method="POST" id="supplierForm"
                @submit.prevent="submitForm">
                @csrf
                @method('PUT')

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
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Company Name <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" name="company_name" id="company_name"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('company_name') border-red-500 @enderror"
                                        value="{{ old('company_name', $supplier->company_name ?? $supplier->name) }}" required>
                                    @error('company_name')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Supplier ID
                                    </label>
                                    <input type="text" name="supplier_id" id="supplier_id"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg bg-gray-100 cursor-not-allowed focus:ring-0 focus:border-gray-300 @error('supplier_id') border-red-500 @enderror"
                                        value="{{ old('supplier_id', $supplier->supplier_id) }}" readonly>
                                    @error('supplier_id')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Contact Person <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" name="contact_person" id="contact_person"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('contact_person') border-red-500 @enderror"
                                        value="{{ old('contact_person', $supplier->contact_person) }}" required>
                                    @error('contact_person')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Email Address <span class="text-red-500">*</span>
                                    </label>
                                    <input type="email" name="email" id="email"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('email') border-red-500 @enderror"
                                        value="{{ old('email', $supplier->email) }}" required>
                                    @error('email')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Phone Number <span class="text-red-500">*</span>
                                    </label>
                                    <input type="tel" name="phone" id="phone"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('phone') border-red-500 @enderror"
                                        value="{{ old('phone', $supplier->phone ?? $supplier->phone_number) }}" required>
                                    @error('phone')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Website
                                    </label>
                                    <input type="url" name="website" id="website"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('website') border-red-500 @enderror"
                                        value="{{ old('website', $supplier->website) }}" placeholder="https://example.com">
                                    @error('website')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        GST Number
                                        <span class="text-sm text-gray-500">(Optional)</span>
                                    </label>
                                    <input type="text" name="gst" id="gst"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('gst') border-red-500 @enderror"
                                        value="{{ old('gst', $supplier->gst) }}" placeholder="Enter GST number if available">
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
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('address') border-red-500 @enderror"
                                        required>{{ old('address', $supplier->address) }}</textarea>
                                    @error('address')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        City <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" name="city" id="city"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('city') border-red-500 @enderror"
                                        value="{{ old('city', $supplier->city) }}" required>
                                    @error('city')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        State/Province <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" name="state" id="state"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('state') border-red-500 @enderror"
                                        value="{{ old('state', $supplier->state) }}" required>
                                    @error('state')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Postal Code
                                    </label>
                                    <input type="text" name="postal_code" id="postal_code"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('postal_code') border-red-500 @enderror"
                                        value="{{ old('postal_code', $supplier->postal_code) }}">
                                    @error('postal_code')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Country <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" name="country" id="country"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('country') border-red-500 @enderror"
                                        value="{{ old('country', $supplier->country) }}" required>
                                    @error('country')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>


                </div>

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
                        <a href="{{ route('suppliers.show', $supplier) }}"
                            class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">
                            Cancel
                        </a>
                        
                        <!-- Next Button (shown when not on last tab) -->
                        <button type="button" @click="nextTab()" x-show="activeTab !== 'address'"
                            class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                            Next
                            <i class="fas fa-chevron-right ml-2"></i>
                        </button>
                        
                        <!-- Submit Button (shown only on last tab) -->
                        <button type="submit" x-show="activeTab === 'address'"
                            class="px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                            <i class="fas fa-save mr-2"></i>
                            Update Supplier
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
    <script>
        function supplierEditManager() {
            return {
                activeTab: 'info',
                init() {
                    // Initialization logic if needed
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
                    document.getElementById('supplierForm').submit();
                }
            }
        }   
    </script>
    @endpush
</x-app-layout>
                       