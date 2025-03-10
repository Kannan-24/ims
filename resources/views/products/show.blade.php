<x-app-layout>
    <x-slot name="title">
        {{ __('Product Details') }} - {{ config('app.name', 'ATMS') }}
    </x-slot>

    <!-- Main Content Section -->
    <div class="py-6 mt-20 ml-4 sm:ml-64">
        <div class="w-full max-w-4xl px-6 mx-auto">
            <!-- Breadcrumb Navigation -->
            <x-bread-crumb-navigation />

            <!-- Product Details Container -->
            <div class="p-8 bg-white border border-gray-200 rounded-lg shadow-lg relative">
                <!-- Header Section -->
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-xl font-bold text-gray-700">Product Details</h2>
                    <div class="flex gap-2">
                        <a href="{{ route('products.edit', $product->id) }}" class="flex items-center px-4 py-2 text-white bg-green-500 rounded-lg hover:bg-green-600 transition">
                            ✏️ Edit
                        </a>
                        <form action="{{ route('products.destroy', $product->id) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="flex items-center px-4 py-2 text-white bg-red-500 rounded-lg hover:bg-red-600 transition">
                                ❌ Delete
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Product Info -->
                <div class="space-y-4 text-gray-700">
                    <p><strong>Product Name:</strong> {{ $product->name }}</p>
                    <p><strong>Description:</strong> {{ $product->description }}</p>
                    <p><strong>HSN Code:</strong> {{ $product->hsn_code }}</p>
                    <p><strong>GST Percentage:</strong> {{ $product->gst_percentage }}%</p>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
