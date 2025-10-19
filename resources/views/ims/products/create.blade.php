@php
    $title = 'Create Product';
@endphp

<x-app-layout :title="$title">
    <div class="bg-white min-h-screen" x-data="productCreateManager()" x-init="init()">
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
                            <a href="{{ route('products.index') }}"
                                class="text-sm font-medium text-gray-700 hover:text-blue-600">
                                Products
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
                    <h1 class="text-2xl font-bold text-gray-900">Create New Product</h1>
                    <p class="text-sm text-gray-600 mt-1">Add a new product to your inventory</p>
                </div>
                <div class="flex items-center space-x-3">
                    <a href="{{ route('products.index') }}"
                        class="inline-flex items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium rounded-lg transition-colors">
                        <i class="fas fa-arrow-left w-4 h-4 mr-2"></i>
                        Back to Products
                    </a>
                </div>
            </div>
        </div>

        <!-- Content -->
        <div class="p-6">
            <form action="{{ route('products.store') }}" method="POST" id="productForm" @submit.prevent="submitForm">
                @csrf

                <!-- Product Information -->
                <div class="bg-white border border-gray-200 rounded-lg p-6 mb-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Product Information</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Product Name <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="name" required value="{{ old('name') }}"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                placeholder="Enter product name">
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
                                Description <span class="text-red-500">*</span>
                            </label>
                            <textarea name="description" rows="3"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                placeholder="Enter product description">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="mt-1 text-sm text-red-600">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Unit & Tax Information -->
                <div class="bg-white border border-gray-200 rounded-lg p-6 mb-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Unit & Tax Information</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Unit Type <span class="text-red-500">*</span>
                            </label>
                            <select name="unit_type" required
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                <option value="" disabled selected>Select Unit Type</option>
                                <option value="kg" {{ old('unit_type') == 'kg' ? 'selected' : '' }}>Kilogram (kg)
                                </option>
                                <option value="ltr" {{ old('unit_type') == 'ltr' ? 'selected' : '' }}>Liter (ltr)
                                </option>
                                <option value="pcs" {{ old('unit_type') == 'pcs' ? 'selected' : '' }}>Pieces (pcs)
                                </option>
                                <option value="box" {{ old('unit_type') == 'box' ? 'selected' : '' }}>Box</option>
                                <option value="meter" {{ old('unit_type') == 'meter' ? 'selected' : '' }}>Meter
                                </option>
                                <option value="feet" {{ old('unit_type') == 'feet' ? 'selected' : '' }}>Feet</option>
                            </select>
                            @error('unit_type')
                                <div class="mt-1 text-sm text-red-600">{{ $message }}</div>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                GST Percentage (%) <span class="text-red-500">*</span>
                            </label>
                            <input type="number" step="0.01" name="gst_percentage" required
                                value="{{ old('gst_percentage') }}"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                placeholder="Enter GST percentage">
                            @error('gst_percentage')
                                <div class="mt-1 text-sm text-red-600">{{ $message }}</div>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                IGST Applicable
                            </label>
                            <div class="flex items-center mt-3">
                                <input type="checkbox" name="is_igst" id="is_igst" value="1"
                                    {{ old('is_igst') ? 'checked' : '' }}
                                    class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 focus:ring-2">
                                <label for="is_igst" class="ml-2 text-sm text-gray-700">Apply IGST instead of
                                    CGST/SGST</label>
                            </div>
                            @error('is_igst')
                                <div class="mt-1 text-sm text-red-600">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="flex items-center justify-end pt-6 border-t border-gray-200">
                    <a href="{{ route('products.index') }}"
                        class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">
                        Cancel
                    </a>
                    <button type="submit"
                        class="ml-3 px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                        <i class="fas fa-save mr-2"></i>
                        Create Product
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function productCreateManager() {
            return {
                init() {
                    // Initialization logic if needed
                },
                submitForm() {
                    document.getElementById('productForm').submit();
                }
            }
        }   
    </script>
</x-app-layout>
