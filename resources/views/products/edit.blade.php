<x-app-layout>
    <x-slot name="title">
        {{ __('Edit Product') }} - {{ config('app.name', 'ATMS') }}
    </x-slot>

    <div class="py-6 mt-20 ml-4 sm:ml-64">
        <div class="w-full max-w-4xl px-6 mx-auto">
            <!-- Breadcrumb Navigation -->
            <x-bread-crumb-navigation />

            <!-- Edit Product Form -->
            <div class="p-8 bg-white border border-gray-200 rounded-lg shadow-lg">
                <h2 class="text-xl font-bold text-gray-700 mb-4">Edit Product</h2>

                <form action="{{ route('products.update', $product->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <!-- Product Name -->
                    <div class="mb-4">
                        <label for="name" class="block text-sm font-medium text-gray-700">Product Name</label>
                        <input type="text" name="name" id="name" value="{{ old('name', $product->name) }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring focus:ring-blue-200 focus:outline-none"
                            required>
                        @error('name')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Description -->
                    <div class="mb-4">
                        <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                        <textarea name="description" id="description"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring focus:ring-blue-200 focus:outline-none"
                            required>{{ old('description', $product->description) }}</textarea>
                        @error('description')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- HSN Code -->
                    <div class="mb-4">
                        <label for="hsn_code" class="block text-sm font-medium text-gray-700">HSN Code</label>
                        <input type="text" name="hsn_code" id="hsn_code" value="{{ old('hsn_code', $product->hsn_code) }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring focus:ring-blue-200 focus:outline-none"
                            required>
                        @error('hsn_code')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- GST / IGST Toggle -->
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Tax Type</label>
                        <div class="flex items-center space-x-4 mt-2">
                            <label class="flex items-center cursor-pointer">
                                <input type="radio" name="is_igst" value="0" 
                                    class="form-radio text-blue-600" 
                                    {{ !$product->is_igst ? 'checked' : '' }}>
                                <span class="ml-2 text-gray-700">GST</span>
                            </label>
                            <label class="flex items-center cursor-pointer">
                                <input type="radio" name="is_igst" value="1" 
                                    class="form-radio text-blue-600" 
                                    {{ $product->is_igst ? 'checked' : '' }}>
                                <span class="ml-2 text-gray-700">IGST</span>
                            </label>
                        </div>
                        @error('is_igst')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- GST Percentage -->
                    <div class="mb-4">
                        <label for="gst_percentage" class="block text-sm font-medium text-gray-700">GST Percentage</label>
                        <input type="number" name="gst_percentage" id="gst_percentage"
                            value="{{ old('gst_percentage', $product->gst_percentage) }}" step="0.01"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring focus:ring-blue-200 focus:outline-none"
                            required>
                        @error('gst_percentage')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Submit and Cancel Buttons -->
                    <div class="flex justify-end mt-6 space-x-4">
                        <a href="{{ route('products.index') }}"
                            class="px-4 py-2 text-gray-600 bg-gray-200 rounded-lg shadow hover:bg-gray-300 transition">
                            Cancel
                        </a>
                        <button type="submit"
                            class="px-4 py-2 text-white bg-blue-500 rounded-lg shadow hover:bg-blue-600 transition">
                            Update Product
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
