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
                    <a href="{{ route('products.edit', $product->id) }}"
                        class="flex items-center px-4 py-2 text-white bg-green-500 rounded-lg hover:bg-green-600 transition">
                        Edit
                    </a>
                    <form action="{{ route('products.destroy', $product->id) }}" method="POST"
                        onsubmit="return confirm('Are you sure you want to delete this product?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                            class="flex items-center px-4 py-2 text-white bg-red-500 rounded-lg hover:bg-red-600 transition">
                            Delete
                        </button>
                    </form>
                    <a href="{{ route('products.assignSuppliersForm', $product->id) }}"
                        class="flex items-center px-4 py-2 text-white bg-blue-500 rounded-lg hover:bg-blue-600 transition">
                        Assign Suppliers
                    </a>
                </div>
            </div>

            <!-- Product Info -->
            <div class="space-y-4 text-gray-700">
                <p><strong>Product Name:</strong> {{ $product->name }}</p>
                <p><strong>Description:</strong> {{ $product->description }}</p>
                <p><strong>HSN Code:</strong> {{ $product->hsn_code }}</p>
                
                <!-- GST / IGST Toggle -->
                <p><strong>Tax Type:</strong> 
                    @if($product->is_igst)
                        <span class="text-red-600 font-semibold">IGST</span>
                    @else
                        <span class="text-green-600 font-semibold">GST</span>
                    @endif
                </p>

                <p><strong>{{ $product->is_igst ? 'IGST' : 'GST' }} Percentage:</strong> 
                    {{ $product->gst_percentage }}%
                </p>
            </div>
        </div>
    </div>

    <!-- Assigned Suppliers Section -->
    <div class="w-full max-w-4xl px-6 mx-auto mt-8">
        <div class="p-8 bg-white border border-gray-200 rounded-lg shadow-lg">
            <h3 class="text-lg font-bold text-gray-700 mb-4">Assigned Suppliers</h3>
            @if ($product->suppliers->isEmpty())
                <p class="text-gray-600">No suppliers assigned to this product.</p>
            @else
                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-2">
                    @foreach ($product->suppliers as $supplier)
                        <div class="relative overflow-hidden bg-gradient-to-r from-blue-500 to-indigo-600 rounded-2xl shadow-lg transition-transform transform hover:scale-105 cursor-pointer"
                            onclick="window.location='{{ route('suppliers.show', $supplier->id) }}'">
                            <div class="p-6">
                                <h2 class="text-lg font-semibold text-white">{{ $supplier->supplier_id }}</h2>
                                <p class="text-sm text-gray-200">{{ $supplier->supplier_name }}</h2>
                                <p class="text-sm text-gray-200">{{ $supplier->state }}</p>
                                <div class="mt-4">
                                    <form
                                        action="{{ route('suppliers.remove', ['product' => $product->id, 'supplier' => $supplier->id]) }}"
                                        method="POST" 
                                        onsubmit="return confirm('Are you sure you want to remove this supplier?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="inline-block px-4 py-2 text-sm font-medium text-red-600 bg-white rounded-md shadow-md hover:bg-gray-100">
                                            Remove
                                        </button>
                                    </form>
                                </div>
                            </div>
                            <div class="absolute top-0 right-0 p-3">
                                <span
                                    class="inline-block px-3 py-1 text-xs font-semibold text-white bg-black bg-opacity-30 rounded-full">
                                    {{ $loop->iteration }}
                                </span>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>
</x-app-layout>
