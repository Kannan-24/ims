<x-app-layout>
    <x-slot name="title">
        {{ __('Create Stock Entry') }} - {{ config('app.name', 'IMS') }}
    </x-slot>

    <div class="bg-white" x-data="stockCreateManager()" x-init="init()">
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
                            <span class="text-sm font-medium text-gray-500">Create Stock Entry</span>
                        </div>
                    </li>
                </ol>
            </nav>
        </div>

        <!-- Header -->
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Create Stock Entry</h1>
                    <p class="text-sm text-gray-600 mt-1">Add new stock entry to inventory</p>
                </div>
                <div class="flex items-center space-x-3">
                    <!-- Help Button -->
                    <a href="{{ route('stocks.help') }}"
                        class="inline-flex items-center px-3 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium rounded-lg transition-colors">
                        <i class="fas fa-question-circle w-4 h-4 mr-2"></i>
                        Help
                    </a>
                    <!-- Back Button -->
                    <a href="{{ route('stocks.index') }}"
                        class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white text-sm font-medium rounded-lg transition-colors">
                        <i class="fas fa-arrow-left w-4 h-4 mr-2"></i>
                        Back to Stocks
                    </a>
                </div>
            </div>
        </div>

        <!-- Content -->
        <div class="p-6">
            <div class="max-w-7xl mx-auto">
                <!-- Success/Error Messages -->
                @if (session('success'))
                    <div class="mb-6 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg">
                        <div class="flex items-center">
                            <i class="fas fa-check-circle mr-2"></i>
                            {{ session('success') }}
                        </div>
                    </div>
                @endif

                @if (session('error'))
                    <div class="mb-6 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg">
                        <div class="flex items-center">
                            <i class="fas fa-exclamation-circle mr-2"></i>
                            {{ session('error') }}
                        </div>
                    </div>
                @endif

                <!-- Stock Entry Form -->
                <div class="bg-white border border-gray-200 rounded-lg shadow-sm">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h2 class="text-lg font-semibold text-gray-900">Stock Entry Details</h2>
                        <p class="text-sm text-gray-600">Fill in the information below to create a new stock entry</p>
                    </div>

                    <form action="{{ route('stocks.store') }}" method="POST" class="p-6 space-y-6">
                        @csrf

                        <!-- Product Selection -->
                        <div>
                            <label for="product_id" class="block text-sm font-medium text-gray-700 mb-2">
                                Product <span class="text-red-500">*</span>
                            </label>
                            <select name="product_id" id="product_id" required x-model="selectedProduct" @change="updateProductInfo()"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option value="">Select Product</option>
                                @foreach($products as $product)
                                    <option value="{{ $product->id }}" 
                                            data-unit-type="{{ $product->unit_type }}"
                                            {{ old('product_id') == $product->id ? 'selected' : '' }}>
                                        {{ $product->name }}
                                        @if($product->sku)
                                            (SKU: {{ $product->sku }})
                                        @endif
                                    </option>
                                @endforeach
                            </select>
                            @error('product_id')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Supplier Selection -->
                        <div>
                            <label for="supplier_id" class="block text-sm font-medium text-gray-700 mb-2">
                                Supplier <span class="text-red-500">*</span>
                            </label>
                            <select name="supplier_id" id="supplier_id" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option value="">Select Supplier</option>
                                @foreach($suppliers as $supplier)
                                    <option value="{{ $supplier->id }}" {{ old('supplier_id') == $supplier->id ? 'selected' : '' }}>
                                        {{ $supplier->name }}
                                        @if($supplier->company_name && $supplier->company_name !== $supplier->name)
                                            - {{ $supplier->company_name }}
                                        @endif
                                    </option>
                                @endforeach
                            </select>
                            @error('supplier_id')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Unit Type -->
                        <div>
                            <label for="unit_type" class="block text-sm font-medium text-gray-700 mb-2">
                                Unit Type <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="unit_type" id="unit_type" x-model="unitType" required readonly
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg bg-gray-100 cursor-not-allowed"
                                placeholder="Unit type will be set based on selected product">
                            <p class="mt-1 text-xs text-gray-500">
                                <i class="fas fa-info-circle mr-1"></i>
                                Unit type is automatically set from the selected product
                            </p>
                            @error('unit_type')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Quantity -->
                            <div>
                                <label for="quantity" class="block text-sm font-medium text-gray-700 mb-2">
                                    Quantity <span class="text-red-500">*</span>
                                </label>
                                <input type="number" name="quantity" id="quantity" value="{{ old('quantity') }}" required min="1"
                                    placeholder="Enter quantity"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                @error('quantity')
                                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Price per Unit -->
                            <div>
                                <label for="price" class="block text-sm font-medium text-gray-700 mb-2">
                                    Price per Unit <span class="text-red-500">*</span>
                                </label>
                                <input type="number" name="price" id="price" step="0.01" value="{{ old('price') }}" required min="0"
                                    placeholder="Enter price per unit"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                @error('price')
                                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Batch Code (Auto-generated) -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Batch Code
                            </label>
                            <div class="w-full px-3 py-2 bg-gray-50 border border-gray-300 rounded-lg text-gray-500 flex items-center">
                                <i class="fas fa-magic mr-2"></i>
                                Auto-generated upon creation
                            </div>
                            <p class="mt-1 text-sm text-gray-500">Batch codes are automatically generated using sequential numbering.</p>
                        </div>

                        <!-- Form Actions -->
                        <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-200">
                            <a href="{{ route('stocks.index') }}"
                                class="px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white font-medium rounded-lg transition-colors">
                                Cancel
                            </a>
                            <button type="submit"
                                class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors flex items-center">
                                <i class="fas fa-save mr-2"></i>
                                Create Stock Entry
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>

    <script>
        function stockCreateManager() {
            return {
                selectedProduct: '{{ old("product_id") }}',
                unitType: '{{ old("unit_type") }}',
                activeTab: 'basic',

                init() {
                    this.setupKeyboardShortcuts();
                    // Set initial unit type if product is pre-selected
                    if (this.selectedProduct) {
                        this.updateProductInfo();
                    }
                },

                updateProductInfo() {
                    const productSelect = document.getElementById('product_id');
                    const selectedOption = productSelect.options[productSelect.selectedIndex];
                    
                    if (selectedOption && selectedOption.value) {
                        this.unitType = selectedOption.getAttribute('data-unit-type') || '';
                    } else {
                        this.unitType = '';
                    }
                },

                setupKeyboardShortcuts() {
                    document.addEventListener('keydown', (e) => {
                        // Ignore if user is typing in an input field
                        if (e.target.tagName === 'INPUT' || e.target.tagName === 'TEXTAREA' || e.target.tagName === 'SELECT') {
                            if (e.key === 'Escape') {
                                e.target.blur();
                            }
                            return;
                        }

                        switch (e.key.toLowerCase()) {
                            case 'escape':
                                window.location.href = '{{ route("stocks.index") }}';
                                break;
                        }

                        // Ctrl+S to save (only if not in input field)
                        if (e.ctrlKey && e.key === 's') {
                            e.preventDefault();
                            document.querySelector('form').submit();
                        }
                    });
                }
            }
        }
    </script>
</x-app-layout>
