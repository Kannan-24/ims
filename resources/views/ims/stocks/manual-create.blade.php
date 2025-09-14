<x-app-layout>
    <x-slot name="title">
        {{ __('Manual Stock Entry') }} - {{ config('app.name', 'IMS') }}
    </x-slot>

    <div class="bg-gray-900 min-h-screen text-white">
        <!-- Breadcrumb -->
        <div class="px-6 py-3 bg-gray-800">
            <nav class="flex" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-3">
                    <li class="inline-flex items-center">
                        <a href="{{ route('dashboard') }}" class="inline-flex items-center text-sm font-medium text-gray-300 hover:text-white">
                            <i class="fas fa-home mr-2"></i>
                            Dashboard
                        </a>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <i class="fas fa-chevron-right text-gray-500 mx-2"></i>
                            <a href="{{ route('stocks.index') }}" class="text-sm font-medium text-gray-300 hover:text-white">
                                Stock Management
                            </a>
                        </div>
                    </li>
                    <li aria-current="page">
                        <div class="flex items-center">
                            <i class="fas fa-chevron-right text-gray-500 mx-2"></i>
                            <span class="text-sm font-medium text-gray-400">Manual Stock Entry</span>
                        </div>
                    </li>
                </ol>
            </nav>
        </div>

        <!-- Header -->
        <div class="px-6 py-6 border-b border-gray-700">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg flex items-center justify-center">
                        <i class="fas fa-plus text-white text-xl"></i>
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold text-white">Manual Stock Entry</h1>
                        <p class="text-gray-400">Add stock manually without purchase order</p>
                    </div>
                </div>
                <a href="{{ route('stocks.index') }}" 
                   class="inline-flex items-center px-4 py-2 bg-gray-700 hover:bg-gray-600 text-white rounded-lg transition-colors">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Back to Stock List
                </a>
            </div>
        </div>

        <!-- Main Content -->
        <div class="px-6 py-8">
            <div class="max-w-4xl mx-auto">
                <div class="bg-gray-800 rounded-lg shadow-lg">
                    <div class="p-6">
                        <!-- Success/Error Messages -->
                        @if (session('success'))
                            <div class="mb-6 bg-green-500/10 border border-green-500/20 text-green-400 px-4 py-3 rounded-lg">
                                <div class="flex items-center">
                                    <i class="fas fa-check-circle mr-2"></i>
                                    {{ session('success') }}
                                </div>
                            </div>
                        @endif

                        @if (session('error'))
                            <div class="mb-6 bg-red-500/10 border border-red-500/20 text-red-400 px-4 py-3 rounded-lg">
                                <div class="flex items-center">
                                    <i class="fas fa-exclamation-circle mr-2"></i>
                                    {{ session('error') }}
                                </div>
                            </div>
                        @endif

                        @if ($errors->any())
                            <div class="mb-6 bg-red-500/10 border border-red-500/20 text-red-400 px-4 py-3 rounded-lg">
                                <div class="flex items-center mb-2">
                                    <i class="fas fa-exclamation-triangle mr-2"></i>
                                    <span class="font-medium">Please fix the following errors:</span>
                                </div>
                                <ul class="list-disc list-inside space-y-1">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <!-- Manual Stock Entry Form -->
                        <form action="{{ route('stocks.manual.store') }}" method="POST" class="space-y-6">
                            @csrf

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Product Selection -->
                                <div>
                                    <label for="product_id" class="block text-sm font-medium text-gray-300 mb-2">
                                        Product <span class="text-red-400">*</span>
                                    </label>
                                    <select name="product_id" id="product_id" required
                                            class="w-full px-4 py-3 bg-gray-700 border border-gray-600 rounded-lg text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                        <option value="">Select Product</option>
                                        @foreach($products as $product)
                                            <option value="{{ $product->id }}" {{ old('product_id') == $product->id ? 'selected' : '' }}>
                                                {{ $product->name }}
                                                @if($product->sku)
                                                    (SKU: {{ $product->sku }})
                                                @endif
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('product_id')
                                        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Supplier Selection -->
                                <div>
                                    <label for="supplier_id" class="block text-sm font-medium text-gray-300 mb-2">
                                        Supplier <span class="text-red-400">*</span>
                                    </label>
                                    <select name="supplier_id" id="supplier_id" required
                                            class="w-full px-4 py-3 bg-gray-700 border border-gray-600 rounded-lg text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent">
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
                                        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Unit Type -->
                                <div>
                                    <label for="unit_type" class="block text-sm font-medium text-gray-300 mb-2">
                                        Unit Type <span class="text-red-400">*</span>
                                    </label>
                                    <input type="text" name="unit_type" id="unit_type" value="{{ old('unit_type') }}" required
                                           placeholder="e.g., kg, litre, pieces, box"
                                           class="w-full px-4 py-3 bg-gray-700 border border-gray-600 rounded-lg text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                    @error('unit_type')
                                        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Quantity -->
                                <div>
                                    <label for="quantity" class="block text-sm font-medium text-gray-300 mb-2">
                                        Quantity <span class="text-red-400">*</span>
                                    </label>
                                    <input type="number" name="quantity" id="quantity" value="{{ old('quantity') }}" required min="1"
                                           placeholder="Enter quantity"
                                           class="w-full px-4 py-3 bg-gray-700 border border-gray-600 rounded-lg text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                    @error('quantity')
                                        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Batch Code (Auto-generated) -->
                                <div class="md:col-span-2">
                                    <label class="block text-sm font-medium text-gray-300 mb-2">
                                        Batch Code
                                    </label>
                                    <div class="w-full px-4 py-3 bg-gray-700/50 border border-gray-600 rounded-lg text-gray-400 flex items-center">
                                        <i class="fas fa-magic mr-2"></i>
                                        Auto-generated upon creation
                                    </div>
                                    <p class="mt-1 text-sm text-gray-400">Batch codes are automatically generated using sequential numbering.</p>
                                </div>
                            </div>

                            <!-- Form Actions -->
                            <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-700">
                                <a href="{{ route('stocks.index') }}" 
                                   class="px-6 py-3 bg-gray-600 hover:bg-gray-500 text-white font-medium rounded-lg transition-colors">
                                    Cancel
                                </a>
                                <button type="submit" 
                                        class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors flex items-center">
                                    <i class="fas fa-save mr-2"></i>
                                    Add Stock Entry
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Information Panel -->
                <div class="mt-8 bg-blue-500/10 border border-blue-500/20 rounded-lg p-4">
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <i class="fas fa-info-circle text-blue-400 text-lg"></i>
                        </div>
                        <div class="ml-3">
                            <h4 class="text-sm font-medium text-blue-400 mb-1">About Manual Stock Entry</h4>
                            <div class="text-sm text-blue-200 space-y-1">
                                <p>• Manual stock entries are independent of purchase orders</p>
                                <p>• Use this for inventory adjustments, opening stock, or stock transfers</p>
                                <p>• All fields are required to maintain inventory accuracy</p>
                                <p>• The batch code helps track and identify specific stock lots</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
