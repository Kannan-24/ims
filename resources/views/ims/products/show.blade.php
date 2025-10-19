@php
    $title = 'Product Details';
@endphp

<x-app-layout :title="$title">
    <div class="bg-white min-h-screen" x-data="productShowManager()" x-init="init()">
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
                            <span class="text-sm font-medium text-gray-500">{{ $product->name }}</span>
                        </div>
                    </li>
                </ol>
            </nav>
        </div>

        <!-- Header -->
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">{{ $product->name }}</h1>
                    <p class="text-sm text-gray-600 mt-1">Product details and supplier information</p>
                </div>
                <div class="flex items-center space-x-3">
                    <!-- Action Buttons -->
                    <a href="{{ route('products.edit', $product) }}"
                        class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors">
                        <i class="fas fa-edit w-4 h-4 mr-2"></i>
                        Edit Product
                    </a>
                    <!-- Delete Button -->
                    <form action="{{ route('products.destroy', $product) }}" method="POST" class="inline"
                        onsubmit="return confirm('Are you sure you want to delete this product? This action cannot be undone.')">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                            class="inline-flex items-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg transition-colors">
                            <i class="fas fa-trash w-4 h-4 mr-2"></i>
                            Delete Product
                        </button>
                    </form>
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
            <!-- Product Information Card -->
            <div class="bg-white border border-gray-200 rounded-lg p-6 mb-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Product Information</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-1">Product Name</label>
                        <p class="text-lg font-semibold text-gray-900">{{ $product->name }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-1">HSN Code</label>
                        <p class="text-lg font-semibold text-gray-900">{{ $product->hsn_code }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-1">Unit Type</label>
                        <span
                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                            {{ strtoupper($product->unit_type) }}
                        </span>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-1">Tax Type</label>
                        @if ($product->is_igst)
                            <span
                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                IGST
                            </span>
                        @else
                            <span
                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                GST
                            </span>
                        @endif
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-1">Tax Percentage</label>
                        <p class="text-lg font-semibold text-gray-900">{{ $product->gst_percentage }}%</p>
                    </div>
                    <div class="md:col-span-2 lg:col-span-1">
                        <label class="block text-sm font-medium text-gray-500 mb-1">Description</label>
                        <p class="text-gray-900">{{ $product->description ?: 'No description available' }}</p>
                    </div>
                </div>
            </div>

            <!-- Assigned Suppliers Card -->
            <div class="bg-white border border-gray-200 rounded-lg p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-900">Assigned Suppliers</h3>
                    @if (!$product->suppliers->isEmpty())
                        <a href="{{ route('products.assignSuppliersForm', $product) }}"
                            class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-lg transition-colors">
                            <i class="fas fa-plus w-4 h-4 mr-2"></i>
                            Assign Suppliers
                        </a>
                    @endif
                </div>

                @if ($product->suppliers->isEmpty())
                    <div class="text-center py-8">
                        <i class="fas fa-truck text-gray-400 text-4xl mb-4"></i>
                        <p class="text-gray-500 mb-4">No suppliers assigned to this product yet.</p>
                        <a href="{{ route('products.assignSuppliersForm', $product) }}"
                            class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors">
                            <i class="fas fa-plus w-4 h-4 mr-2"></i>
                            Assign First Supplier
                        </a>
                    </div>
                @else
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach ($product->suppliers as $supplier)
                            <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow">
                                <div class="flex items-start justify-between">
                                    <div class="flex-1">
                                        <h4 class="font-semibold text-gray-900">{{ $supplier->supplier_name }}</h4>
                                        <p class="text-sm text-gray-600">ID: {{ $supplier->supplier_id }}</p>
                                        <p class="text-sm text-gray-600">{{ $supplier->state }}</p>
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        <a href="{{ route('suppliers.show', $supplier) }}"
                                            class="text-blue-600 hover:text-blue-800 p-1" title="View Supplier">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <form
                                            action="{{ route('suppliers.remove', ['product' => $product, 'supplier' => $supplier]) }}"
                                            method="POST" class="inline"
                                            onsubmit="return confirm('Are you sure you want to remove this supplier?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-800 p-1"
                                                title="Remove Supplier">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>

    <script>
        function productShowManager() {
            return {
                init() {
                    // Initialization logic if needed
                },
            };
        }
    </script>
</x-app-layout>
