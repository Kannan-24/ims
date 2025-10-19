<x-app-layout>
    <x-slot name="title">
        Edit Stock Entry - {{ config('app.name', 'IMS') }}
    </x-slot>

    <div class="bg-white" x-data="stockEditManager()" x-init="init()">
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
                            <a href="{{ route('stocks.index') }}"
                                class="text-sm font-medium text-gray-700 hover:text-blue-600">Stock Management</a>
                        </div>
                    </li>
                    <li aria-current="page">
                        <div class="flex items-center">
                            <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                            <span class="text-sm font-medium text-gray-500">Edit Stock Entry</span>
                        </div>
                    </li>
                </ol>
            </nav>
        </div>

        <!-- Header -->
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Edit Stock Entry</h1>
                    <p class="text-sm text-gray-600 mt-1">Batch Code: <span
                            class="font-mono bg-gray-100 px-2 py-1 rounded">{{ $stock->batch_code }}</span></p>
                </div>
                <div class="flex items-center space-x-3">
                    <a href="{{ route('stocks.index') }}"
                        class="inline-flex items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium rounded-lg transition-colors">
                        <i class="fas fa-arrow-left w-4 h-4 mr-2"></i>
                        Back to List
                    </a>
                </div>
            </div>
        </div>

        <!-- Content -->
        <div class="p-6">
            @if ($stock->entry_type === 'purchase')
                <!-- Purchase Entry Warning -->
                <div class="mb-6 bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class="fas fa-exclamation-triangle text-yellow-400"></i>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-yellow-800">Purchase Entry - Limited Editing</h3>
                            <div class="mt-2 text-sm text-yellow-700">
                                <p>This stock entry was created from a purchase order. Only quantity and price can be
                                    modified to maintain data integrity.</p>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Main Form -->
                <div class="lg:col-span-2">
                    <form action="{{ route('stocks.update', $stock) }}" method="POST" id="stockEditForm">
                        @csrf
                        @method('PUT')

                        <div class="bg-white border border-gray-200 rounded-lg shadow-sm">
                            <div class="px-6 py-4 border-b border-gray-200">
                                <h2 class="text-lg font-semibold text-gray-900">Stock Information</h2>
                                <p class="text-sm text-gray-500 mt-1">Update the stock entry details</p>
                            </div>

                            <div class="p-6 space-y-6">
                                <!-- Product Selection -->
                                <div>
                                    <label for="product_id" class="block text-sm font-medium text-gray-700 mb-2">
                                        Product *
                                    </label>
                                    @if ($stock->entry_type === 'purchase')
                                        <div class="w-full px-3 py-2 bg-gray-50 border border-gray-300 rounded-lg">
                                            <div class="flex items-center">
                                                <div>
                                                    <p class="text-sm font-medium text-gray-900">
                                                        {{ $stock->product->name ?? 'N/A' }}</p>
                                                    <p class="text-sm text-gray-500">{{ $stock->product->sku ?? 'N/A' }}
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                        <input type="hidden" name="product_id" value="{{ $stock->product_id }}">
                                        <p class="mt-1 text-xs text-gray-500">
                                            <i class="fas fa-lock mr-1"></i>Product cannot be changed for purchase
                                            entries
                                        </p>
                                    @else
                                        <select name="product_id" id="product_id" x-model="selectedProduct"
                                            @change="updateProductInfo()"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                            required>
                                            <option value="">Select a product</option>
                                            @foreach ($products as $product)
                                                <option value="{{ $product->id }}"
                                                    data-unit-type="{{ $product->unit_type }}"
                                                    {{ $stock->product_id == $product->id ? 'selected' : '' }}>
                                                    {{ $product->name }} ({{ $product->hsn_code }})
                                                </option>
                                            @endforeach
                                        </select>
                                    @endif
                                    @error('product_id')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Supplier Selection -->
                                <div>
                                    <label for="supplier_id" class="block text-sm font-medium text-gray-700 mb-2">
                                        Supplier *
                                    </label>
                                    @if ($stock->entry_type === 'purchase')
                                        <div class="w-full px-3 py-2 bg-gray-50 border border-gray-300 rounded-lg">
                                            <div class="flex items-center">
                                                <div>
                                                    <p class="text-sm font-medium text-gray-900">
                                                        {{ $stock->supplier->name ?? 'N/A' }}</p>
                                                    <p class="text-sm text-gray-500">
                                                        {{ $stock->supplier->supplier_id ?? 'N/A' }}</p>
                                                </div>
                                            </div>
                                        </div>
                                        <input type="hidden" name="supplier_id" value="{{ $stock->supplier_id }}">
                                        <p class="mt-1 text-xs text-gray-500">
                                            <i class="fas fa-lock mr-1"></i>Supplier cannot be changed for purchase
                                            entries
                                        </p>
                                    @else
                                        <select name="supplier_id" id="supplier_id" x-model="selectedSupplier"
                                            @change="updateSupplierInfo()"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                            required>
                                            <option value="">Select a supplier</option>
                                            @foreach ($suppliers as $supplier)
                                                <option value="{{ $supplier->id }}"
                                                    {{ $stock->supplier_id == $supplier->id ? 'selected' : '' }}>
                                                    {{ $supplier->name }} ({{ $supplier->supplier_id }})
                                                </option>
                                            @endforeach
                                        </select>
                                    @endif
                                    @error('supplier_id')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Batch Code -->
                                <div>
                                    <label for="batch_code" class="block text-sm font-medium text-gray-700 mb-2">
                                        Batch Code
                                    </label>
                                    <input type="text" name="batch_code" id="batch_code"
                                        value="{{ old('batch_code', $stock->batch_code) }}"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg bg-gray-100 cursor-not-allowed font-mono"
                                        placeholder="Batch code" readonly>
                                    <p class="mt-1 text-xs text-gray-500">
                                        <i class="fas fa-info-circle mr-1"></i>
                                        Batch code cannot be modified after creation
                                    </p>
                                </div>

                                <!-- Unit Type -->
                                <div>
                                    <label for="unit_type" class="block text-sm font-medium text-gray-700 mb-2">
                                       Unit Type
                                    </label>
                                    <input type="text" name="unit_type" id="unit_type" x-model="unitType"
                                        required readonly
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg bg-gray-100 cursor-not-allowed"
                                        placeholder="Unit type is set from selected product">
                                    <p class="mt-1 text-xs text-gray-500">
                                        <i class="fas fa-info-circle mr-1"></i>
                                        Unit type is automatically set from the selected product
                                    </p>
                                    @error('unit_type')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Quantity and Price -->
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label for="quantity" class="block text-sm font-medium text-gray-700 mb-2">
                                            Quantity *
                                        </label>
                                        <input type="number" name="quantity" id="quantity"
                                            value="{{ old('quantity', $stock->quantity) }}"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                            placeholder="Enter quantity" min="1" required>
                                        @error('quantity')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div>
                                        <label for="price" class="block text-sm font-medium text-gray-700 mb-2">
                                           Price per Unit *
                                        </label>
                                        <input type="number" name="price" id="price" step="0.01"
                                            value="{{ old('price', $stock->price) }}"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                            placeholder="Enter price per unit" min="0" required>
                                        @error('price')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Entry Type (Read-only) -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Entry Type
                                    </label>
                                    <div class="w-full px-3 py-2 bg-gray-50 border border-gray-300 rounded-lg">
                                        <span
                                            class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium 
                                            {{ $stock->entry_type === 'purchase' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800' }}">
                                            <i
                                                class="fas {{ $stock->entry_type === 'purchase' ? 'fa-shopping-cart' : 'fa-edit' }} mr-2"></i>
                                            {{ ucfirst($stock->entry_type) }}
                                        </span>
                                    </div>
                                    <p class="mt-1 text-xs text-gray-500">
                                        <i class="fas fa-lock mr-1"></i>
                                        Entry type cannot be modified
                                    </p>
                                </div>
                            </div>

                            <!-- Form Actions -->
                            <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex justify-end space-x-3">
                                <button type="submit"
                                    class="inline-flex items-center px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors">
                                    <i class="fas fa-save w-4 h-4 mr-2"></i>
                                    Update Stock Entry
                                </button>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Sidebar -->
                <div class="space-y-4">
                    <!-- Current Stock Info -->
                    <div class="bg-white border border-gray-200 rounded-lg shadow-sm">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <h2 class="text-lg font-semibold text-gray-900">Current Stock Info</h2>
                        </div>
                        <div class="p-6 space-y-3">
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-gray-500">Original Quantity</span>
                                <span
                                    class="text-sm font-medium text-gray-900">{{ number_format($stock->quantity) }}</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-gray-500">Currently Sold</span>
                                <span
                                    class="text-sm font-medium text-orange-600">{{ number_format($stock->sold) }}</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-gray-500">Available</span>
                                @php
                                    $available = $stock->quantity - $stock->sold;
                                @endphp
                                <span
                                    class="text-sm font-medium {{ $available <= 0 ? 'text-red-600' : 'text-green-600' }}">
                                    {{ number_format($available) }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Product Information -->
                    <div class="bg-white border border-gray-200 rounded-lg shadow-sm">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <h2 class="text-lg font-semibold text-gray-900">Product Details</h2>
                        </div>
                        <div class="p-6 space-y-3">
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center mr-3">
                                    <i class="fas fa-box text-blue-600"></i>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-900">{{ $stock->product->name ?? 'N/A' }}
                                    </p>
                                    <p class="text-sm text-gray-500">{{ $stock->product->hsn_code ?? 'N/A' }}</p>
                                </div>
                            </div>
                            @if ($stock->product->description)
                                <div>
                                    <p class="text-sm text-gray-900"><strong>Description : </strong>
                                        {{ $stock->product->description }}</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Supplier Information -->
                    <div class="bg-white border border-gray-200 rounded-lg shadow-sm">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <h2 class="text-lg font-semibold text-gray-900">Supplier Details</h2>
                        </div>
                        <div class="p-6 space-y-3">
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center mr-3">
                                    <i class="fas fa-truck text-green-600"></i>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-900">{{ $stock->supplier->name ?? 'N/A' }}
                                    </p>
                                    <p class="text-sm text-gray-500">{{ $stock->supplier->supplier_id ?? 'N/A' }}</p>
                                </div>
                            </div>
                            @if ($stock->supplier->email)
                                <div>
                                    <p class="text-sm text-gray-900"> <strong>Email :</strong>
                                        {{ $stock->supplier->email }}</p>
                                </div>
                            @endif
                            @if ($stock->supplier->phone_number)
                                <div>
                                    <p class="text-sm text-gray-900"><strong>Phone Number :</strong>
                                        {{ $stock->supplier->phone_number }}</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Activity Log -->
                    <div class="bg-white border border-gray-200 rounded-lg shadow-sm">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <h2 class="text-lg font-semibold text-gray-900">Activity Log</h2>
                        </div>
                        <div class="p-6">
                            <div class="flow-root">
                                <ul class="-mb-8">
                                    <li>
                                        <div class="relative pb-8">
                                            <div class="relative flex space-x-3">
                                                <div>
                                                    <span
                                                        class="h-8 w-8 rounded-full bg-green-500 flex items-center justify-center ring-8 ring-white">
                                                        <i class="fas fa-plus text-white text-xs"></i>
                                                    </span>
                                                </div>
                                                <div class="min-w-0 flex-1 pt-1.5 flex justify-between space-x-4">
                                                    <div>
                                                        <p class="text-sm text-gray-500">Stock entry created</p>
                                                    </div>
                                                    <div class="text-right text-sm whitespace-nowrap text-gray-500">
                                                        {{ $stock->created_at->format('M d, Y') }}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                    @if ($stock->updated_at != $stock->created_at)
                                        <li>
                                            <div class="relative">
                                                <div class="relative flex space-x-3">
                                                    <div>
                                                        <span
                                                            class="h-8 w-8 rounded-full bg-blue-500 flex items-center justify-center ring-8 ring-white">
                                                            <i class="fas fa-edit text-white text-xs"></i>
                                                        </span>
                                                    </div>
                                                    <div class="min-w-0 flex-1 pt-1.5 flex justify-between space-x-4">
                                                        <div>
                                                            <p class="text-sm text-gray-500">Last updated</p>
                                                        </div>
                                                        <div
                                                            class="text-right text-sm whitespace-nowrap text-gray-500">
                                                            {{ $stock->updated_at->format('M d, Y') }}
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                    @endif
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            function stockEditManager() {
                return {
                    selectedProduct: '{{ $stock->product_id }}',
                    selectedSupplier: '{{ $stock->supplier_id }}',
                    unitType: '{{ $stock->unit_type }}',

                    init() {
                        if (this.selectedProduct) {
                            this.updateProductInfo();
                        }
                    },

                    updateProductInfo() {
                        const productSelect = document.getElementById('product_id');
                        if (productSelect) {
                            const selectedOption = productSelect.options[productSelect.selectedIndex];

                            if (selectedOption && selectedOption.value) {
                                this.unitType = selectedOption.getAttribute('data-unit-type') || '';
                            } else {
                                this.unitType = '';
                            }
                        }
                    },

                    updateSupplierInfo() {
                        // Additional supplier info updates can be added here
                        console.log('Supplier selected:', this.selectedSupplier);
                    },
                };
            }
        </script>
    @endpush
</x-app-layout>
