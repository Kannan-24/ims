<x-app-layout>
    <x-slot name="title">
        Stock Details - {{ $stock->product->name ?? 'N/A' }} - {{ config('app.name', 'IMS') }}
    </x-slot>

    <div class="bg-white">
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
                            <span class="text-sm font-medium text-gray-500">Stock Details</span>
                        </div>
                    </li>
                </ol>
            </nav>
        </div>

        <!-- Header -->
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Stock Entry Details</h1>
                    <p class="text-sm text-gray-600 mt-1">Batch Code: <span class="font-mono bg-gray-100 px-2 py-1 rounded">{{ $stock->batch_code }}</span></p>
                </div>
                <div class="flex items-center space-x-3">
                    <a href="{{ route('stocks.edit', $stock) }}"
                        class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg transition-colors">
                        <i class="fas fa-edit w-4 h-4 mr-2"></i>
                        Edit Stock
                    </a>
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
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Main Details -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Stock Information -->
                    <div class="bg-white border border-gray-200 rounded-lg shadow-sm">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <h2 class="text-lg font-semibold text-gray-900">Stock Information</h2>
                        </div>
                        <div class="p-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Product Details -->
                                <div class="space-y-4">
                                    <div>
                                        <label class="text-sm font-medium text-gray-500">Product</label>
                                        <div class="mt-1 flex items-center">
                                            <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center mr-3">
                                                <i class="fas fa-box text-blue-600"></i>
                                            </div>
                                            <div>
                                                <p class="text-sm font-medium text-gray-900">{{ $stock->product->name ?? 'N/A' }}</p>
                                                <p class="text-sm text-gray-500">{{ $stock->product->sku ?? 'N/A' }}</p>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div>
                                        <label class="text-sm font-medium text-gray-500">Supplier</label>
                                        <div class="mt-1 flex items-center">
                                            <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center mr-3">
                                                <i class="fas fa-truck text-green-600"></i>
                                            </div>
                                            <div>
                                                <p class="text-sm font-medium text-gray-900">{{ $stock->supplier->name ?? 'N/A' }}</p>
                                                <p class="text-sm text-gray-500">{{ $stock->supplier->supplier_id ?? 'N/A' }}</p>
                                            </div>
                                        </div>
                                    </div>

                                    <div>
                                        <label class="text-sm font-medium text-gray-500">Entry Type</label>
                                        <div class="mt-1">
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium 
                                                {{ $stock->entry_type === 'purchase' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800' }}">
                                                <i class="fas {{ $stock->entry_type === 'purchase' ? 'fa-shopping-cart' : 'fa-edit' }} mr-2"></i>
                                                {{ ucfirst($stock->entry_type) }}
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Quantity Details -->
                                <div class="space-y-4">
                                    <div>
                                        <label class="text-sm font-medium text-gray-500">Quantity</label>
                                        <p class="mt-1 text-2xl font-bold text-gray-900">{{ number_format($stock->quantity) }}</p>
                                    </div>
                                    
                                    <div>
                                        <label class="text-sm font-medium text-gray-500">Sold</label>
                                        <p class="mt-1 text-2xl font-bold text-orange-600">{{ number_format($stock->sold) }}</p>
                                    </div>
                                    
                                    <div>
                                        <label class="text-sm font-medium text-gray-500">Available</label>
                                        @php
                                            $available = $stock->quantity - $stock->sold;
                                        @endphp
                                        <p class="mt-1 text-2xl font-bold {{ $available <= 0 ? 'text-red-600' : ($available <= 10 ? 'text-yellow-600' : 'text-green-600') }}">
                                            {{ number_format($available) }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Purchase Details (if applicable) -->
                    @if($stock->purchase)
                    <div class="bg-white border border-gray-200 rounded-lg shadow-sm">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <h2 class="text-lg font-semibold text-gray-900">Purchase Information</h2>
                        </div>
                        <div class="p-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="text-sm font-medium text-gray-500">Purchase ID</label>
                                    <p class="mt-1 text-sm font-medium text-gray-900">{{ $stock->purchase->purchase_id ?? 'N/A' }}</p>
                                </div>
                                <div>
                                    <label class="text-sm font-medium text-gray-500">Purchase Date</label>
                                    <p class="mt-1 text-sm text-gray-900">{{ $stock->purchase->created_at->format('M d, Y h:i A') ?? 'N/A' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Related Stock Entries -->
                    @if($relatedStocks->count() > 0)
                    <div class="bg-white border border-gray-200 rounded-lg shadow-sm">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <h2 class="text-lg font-semibold text-gray-900">Other Stock Entries for this Product</h2>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Batch Code</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Supplier</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantity</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Available</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($relatedStocks as $relatedStock)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="font-mono text-sm">{{ $relatedStock->batch_code }}</span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ $relatedStock->supplier->name ?? 'N/A' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ number_format($relatedStock->quantity) }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @php
                                                $relatedAvailable = $relatedStock->quantity - $relatedStock->sold;
                                            @endphp
                                            <span class="text-sm font-medium {{ $relatedAvailable <= 0 ? 'text-red-600' : 'text-green-600' }}">
                                                {{ number_format($relatedAvailable) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <a href="{{ route('stocks.show', $relatedStock) }}" 
                                               class="text-blue-600 hover:text-blue-900">
                                                View
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    @endif
                </div>

                <!-- Sidebar -->
                <div class="space-y-6">
                    <!-- Quick Stats -->
                    <div class="bg-white border border-gray-200 rounded-lg shadow-sm">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <h2 class="text-lg font-semibold text-gray-900">Quick Stats</h2>
                        </div>
                        <div class="p-6 space-y-4">
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-gray-500">Price per Unit</span>
                                <span class="text-sm font-medium text-gray-900">₹{{ number_format($stock->price, 2) }}</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-gray-500">Total Value</span>
                                <span class="text-sm font-medium text-gray-900">₹{{ number_format($stock->quantity * $stock->price, 2) }}</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-gray-500">Sold Value</span>
                                <span class="text-sm font-medium text-gray-900">₹{{ number_format($stock->sold * $stock->price, 2) }}</span>
                            </div>
                            <div class="flex items-center justify-between border-t pt-4">
                                <span class="text-sm text-gray-500">Remaining Value</span>
                                <span class="text-sm font-bold text-green-600">₹{{ number_format(($stock->quantity - $stock->sold) * $stock->price, 2) }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Product Summary -->
                    @if($productTotals)
                    <div class="bg-white border border-gray-200 rounded-lg shadow-sm">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <h2 class="text-lg font-semibold text-gray-900">Product Summary</h2>
                            <p class="text-sm text-gray-500">All entries for {{ $stock->product->name ?? 'this product' }}</p>
                        </div>
                        <div class="p-6 space-y-4">
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-gray-500">Total Quantity</span>
                                <span class="text-sm font-medium text-gray-900">{{ number_format($productTotals->total_quantity) }}</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-gray-500">Total Sold</span>
                                <span class="text-sm font-medium text-gray-900">{{ number_format($productTotals->total_sold) }}</span>
                            </div>
                            <div class="flex items-center justify-between border-t pt-4">
                                <span class="text-sm text-gray-500">Total Available</span>
                                <span class="text-sm font-bold text-green-600">{{ number_format($productTotals->total_quantity - $productTotals->total_sold) }}</span>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Activity Timeline -->
                    <div class="bg-white border border-gray-200 rounded-lg shadow-sm">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <h2 class="text-lg font-semibold text-gray-900">Activity Timeline</h2>
                        </div>
                        <div class="p-6">
                            <div class="flow-root">
                                <ul class="-mb-8">
                                    <li>
                                        <div class="relative pb-8">
                                            <div class="relative flex space-x-3">
                                                <div>
                                                    <span class="h-8 w-8 rounded-full bg-green-500 flex items-center justify-center ring-8 ring-white">
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
                                    @if($stock->updated_at != $stock->created_at)
                                    <li>
                                        <div class="relative">
                                            <div class="relative flex space-x-3">
                                                <div>
                                                    <span class="h-8 w-8 rounded-full bg-blue-500 flex items-center justify-center ring-8 ring-white">
                                                        <i class="fas fa-edit text-white text-xs"></i>
                                                    </span>
                                                </div>
                                                <div class="min-w-0 flex-1 pt-1.5 flex justify-between space-x-4">
                                                    <div>
                                                        <p class="text-sm text-gray-500">Stock entry updated</p>
                                                    </div>
                                                    <div class="text-right text-sm whitespace-nowrap text-gray-500">
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
</x-app-layout>
