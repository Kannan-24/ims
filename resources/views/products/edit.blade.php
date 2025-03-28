<x-app-layout>
    <x-slot name="title">
        {{ __('Edit Product') }} - {{ config('app.name', 'ATMS') }}
    </x-slot>

    <div class="py-6 mt-20 ml-4 sm:ml-64">
        <div class="w-full mx-auto max-w-7xl sm:px-6 lg:px-8">
            <!-- Breadcrumb Navigation -->
            <x-bread-crumb-navigation />

            <div class="p-8 bg-gray-800 border border-gray-700 rounded-lg shadow-lg">
                <h2 class="text-3xl font-bold text-gray-200 mb-6">Edit Product</h2>

                <form action="{{ route('products.update', $product->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <!-- Product Name -->
                    <div class="mb-6">
                        <label for="name" class="block text-gray-300 font-semibold mb-2">Product Name:</label>
                        <input type="text" name="name" id="name" value="{{ old('name', $product->name) }}"
                            class="w-full px-4 py-3 border border-gray-700 bg-gray-800 text-gray-300 rounded-lg shadow-md focus:outline-none focus:ring-2 focus:ring-blue-500 transition"
                            required>
                        @error('name')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Description -->
                    <div class="mb-6">
                        <label for="description" class="block text-gray-300 font-semibold mb-2">Description:</label>
                        <textarea name="description" id="description"
                            class="w-full px-4 py-3 border border-gray-700 bg-gray-800 text-gray-300 rounded-lg shadow-md focus:outline-none focus:ring-2 focus:ring-blue-500 transition"
                            required>{{ old('description', $product->description) }}</textarea>
                        @error('description')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- HSN Code -->
                    <div class="mb-6">
                        <label for="hsn_code" class="block text-gray-300 font-semibold mb-2">HSN Code:</label>
                        <input type="text" name="hsn_code" id="hsn_code" value="{{ old('hsn_code', $product->hsn_code) }}"
                            class="w-full px-4 py-3 border border-gray-700 bg-gray-800 text-gray-300 rounded-lg shadow-md focus:outline-none focus:ring-2 focus:ring-blue-500 transition"
                            required>
                        @error('hsn_code')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- GST / IGST Toggle -->
                    <div class="mb-6">
                        <label class="block text-gray-300 font-semibold mb-2">Tax Type:</label>
                        <div class="flex items-center space-x-4 mt-2">
                            <label class="flex items-center cursor-pointer">
                                <input type="radio" name="is_igst" value="0" 
                                    class="form-radio text-blue-500" 
                                    {{ !$product->is_igst ? 'checked' : '' }}>
                                <span class="ml-2 text-gray-300">GST</span>
                            </label>
                            <label class="flex items-center cursor-pointer">
                                <input type="radio" name="is_igst" value="1" 
                                    class="form-radio text-blue-500" 
                                    {{ $product->is_igst ? 'checked' : '' }}>
                                <span class="ml-2 text-gray-300">IGST</span>
                            </label>
                        </div>
                        @error('is_igst')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- GST Percentage -->
                    <div class="mb-6">
                        <label for="gst_percentage" class="block text-gray-300 font-semibold mb-2">GST Percentage:</label>
                        <input type="number" name="gst_percentage" id="gst_percentage"
                            value="{{ old('gst_percentage', $product->gst_percentage) }}" step="0.01"
                            class="w-full px-4 py-3 border border-gray-700 bg-gray-800 text-gray-300 rounded-lg shadow-md focus:outline-none focus:ring-2 focus:ring-blue-500 transition"
                            required>
                        @error('gst_percentage')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Submit and Cancel Buttons -->
                    <div class="flex justify-end mt-6 space-x-4">
                        <a href="{{ route('products.index') }}"
                            class="px-6 py-3 text-gray-300 bg-gray-700 rounded-lg shadow-md hover:bg-gray-600 transition">
                            Cancel
                        </a>
                        <button type="submit"
                            class="px-6 py-3 text-white bg-blue-500 rounded-lg shadow-md hover:bg-blue-600 transition">
                            Update Product
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
