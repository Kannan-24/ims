<x-app-layout>
    <x-slot name="title">
        {{ __('Quotation Details') }} - {{ config('app.name', 'SKM') }}
    </x-slot>

    <div class="bg-white min-h-screen" x-data="quotationShowManager()" x-init="init()">
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
                            <a href="{{ route('quotations.index') }}"
                                class="text-sm font-medium text-gray-700 hover:text-blue-600">
                                Quotations
                            </a>
                        </div>
                    </li>
                    <li aria-current="page">
                        <div class="flex items-center">
                            <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                            <span class="text-sm font-medium text-gray-500">Quotation
                                #{{ $quotation->quotation_no ?? $quotation->id }}</span>
                        </div>
                    </li>
                </ol>
            </nav>
        </div>

        <!-- Header -->
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Quotation
                        #{{ $quotation->quotation_no ?? $quotation->id }}</h1>
                    <div class="flex items-center space-x-4 mt-1">
                        <span class="text-sm text-gray-600">Created:
                            {{ $quotation->created_at->format('M d, Y') }}</span>
                        <span
                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                            {{ $quotation->status === 'pending'
                                ? 'bg-yellow-100 text-yellow-800'
                                : ($quotation->status === 'approved'
                                    ? 'bg-green-100 text-green-800'
                                    : ($quotation->status === 'rejected'
                                        ? 'bg-red-100 text-red-800'
                                        : 'bg-gray-100 text-gray-800')) }}">
                            {{ ucfirst($quotation->status ?? 'Draft') }}
                        </span>
                    </div>
                </div>
                <div class="flex items-center space-x-3">
                    <!-- Print Button -->
                    <button @click="printQuotation()"
                        class="inline-flex items-center px-3 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium rounded-lg transition-colors">
                        <i class="fas fa-print w-4 h-4 mr-2"></i>
                        Print
                    </button>

                    <!-- PDF Download Button -->
                    <button @click="downloadPDF()"
                        class="inline-flex items-center px-3 py-2 bg-red-100 hover:bg-red-200 text-red-700 text-sm font-medium rounded-lg transition-colors">
                        <i class="fas fa-file-pdf w-4 h-4 mr-2"></i>
                        PDF
                    </button>

                    <!-- Convert to Invoice Button -->
                    @if (!$quotation->converted_to_invoice)
                        <button @click="showConvertModal = true"
                            class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors">
                            <i class="fas fa-file-invoice w-4 h-4 mr-2"></i>
                            Convert to Invoice
                        </button>
                    @endif

                    <!-- Edit Button -->
                    <a href="{{ route('quotations.edit', $quotation->id) }}"
                        class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-lg transition-colors">
                        <i class="fas fa-edit w-4 h-4 mr-2"></i>
                        Edit
                    </a>

                    <!-- Back Button -->
                    <a href="{{ route('quotations.index') }}"
                        class="inline-flex items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium rounded-lg transition-colors">
                        <i class="fas fa-arrow-left w-4 h-4 mr-2"></i>
                        Back to List
                    </a>
                </div>
            </div>
        </div>

        <!-- Content -->
        <div class="p-6">
            <!-- Tab Navigation -->
            <div class="border-b border-gray-200 mb-6">
                <nav class="-mb-px flex space-x-8">
                    <button type="button" @click="activeTab = 'details'"
                        :class="activeTab === 'details' ? 'border-blue-500 text-blue-600' :
                            'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                        class="py-2 px-1 border-b-2 font-medium text-sm">
                        <i class="fas fa-info-circle mr-2"></i>
                        Quotation Details
                    </button>
                    <button type="button" @click="activeTab = 'items'"
                        :class="activeTab === 'items' ? 'border-blue-500 text-blue-600' :
                            'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                        class="py-2 px-1 border-b-2 font-medium text-sm">
                        <i class="fas fa-list mr-2"></i>
                        Items & Pricing
                        <span
                            class="ml-1 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-blue-100 bg-blue-600 rounded-full">
                            {{ $quotation->items->count() }}
                        </span>
                    </button>
                    <button type="button" @click="activeTab = 'summary'"
                        :class="activeTab === 'summary' ? 'border-blue-500 text-blue-600' :
                            'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                        class="py-2 px-1 border-b-2 font-medium text-sm">
                        <i class="fas fa-calculator mr-2"></i>
                        Summary
                    </button>
                    <button type="button" @click="activeTab = 'preview'"
                        :class="activeTab === 'preview' ? 'border-blue-500 text-blue-600' :
                            'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                        class="py-2 px-1 border-b-2 font-medium text-sm">
                        <i class="fas fa-eye mr-2"></i>
                        Print Preview
                    </button>
                </nav>
            </div>

            <!-- Tab Content -->
            <div class="space-y-6">
                <!-- Details Tab -->
                <div x-show="activeTab === 'details'" x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <!-- Customer Information -->
                        <div class="bg-white border border-gray-200 rounded-lg p-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">
                                <i class="fas fa-building text-blue-600 mr-2"></i>
                                Customer Information
                            </h3>
                            <div class="space-y-3">
                                <div>
                                    <span class="text-sm font-medium text-gray-500">Company Name:</span>
                                    <p class="text-sm text-gray-900">{{ $quotation->customer->company_name ?? 'N/A' }}
                                    </p>
                                </div>
                                <div>
                                    <span class="text-sm font-medium text-gray-500">Contact Person:</span>
                                    <p class="text-sm text-gray-900">{{ $quotation->contactPerson->name ?? 'N/A' }}</p>
                                </div>
                                <div>
                                    <span class="text-sm font-medium text-gray-500">Email:</span>
                                    <p class="text-sm text-gray-900">
                                        {{ $quotation->contactPerson->email ?? ($quotation->customer->email ?? 'N/A') }}
                                    </p>
                                </div>
                                <div>
                                    <span class="text-sm font-medium text-gray-500">Phone:</span>
                                    <p class="text-sm text-gray-900">
                                        {{ $quotation->contactPerson->phone ?? ($quotation->customer->phone ?? 'N/A') }}
                                    </p>
                                </div>
                                <div>
                                    <span class="text-sm font-medium text-gray-500">Address:</span>
                                    <p class="text-sm text-gray-900">{{ $quotation->customer->address ?? 'N/A' }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Quotation Information -->
                        <div class="bg-white border border-gray-200 rounded-lg p-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">
                                <i class="fas fa-file-alt text-green-600 mr-2"></i>
                                Quotation Information
                            </h3>
                            <div class="space-y-3">
                                <div>
                                    <span class="text-sm font-medium text-gray-500">Quotation Number:</span>
                                    <p class="text-sm text-gray-900 font-mono">
                                        {{ $quotation->quotation_no ?? 'QUO-' . str_pad($quotation->id, 4, '0', STR_PAD_LEFT) }}
                                    </p>
                                </div>
                                <div>
                                    <span class="text-sm font-medium text-gray-500">Date:</span>
                                    <p class="text-sm text-gray-900">
                                        {{ \Carbon\Carbon::parse($quotation->quotation_date)->format('F d, Y') }}</p>
                                </div>
                                <div>
                                    <span class="text-sm font-medium text-gray-500">Valid Until:</span>
                                    <p class="text-sm text-gray-900">
                                        {{ \Carbon\Carbon::parse($quotation->quotation_date)->addDays(30)->format('F d, Y') }}
                                    </p>
                                </div>
                                <div>
                                    <span class="text-sm font-medium text-gray-500">Status:</span>
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                        {{ $quotation->status === 'pending'
                                            ? 'bg-yellow-100 text-yellow-800'
                                            : ($quotation->status === 'approved'
                                                ? 'bg-green-100 text-green-800'
                                                : ($quotation->status === 'rejected'
                                                    ? 'bg-red-100 text-red-800'
                                                    : 'bg-gray-100 text-gray-800')) }}">
                                        {{ ucfirst($quotation->status ?? 'Draft') }}
                                    </span>
                                </div>
                                @if ($quotation->converted_to_invoice)
                                    <div>
                                        <span class="text-sm font-medium text-gray-500">Converted to Invoice:</span>
                                        <p class="text-sm text-green-600 font-medium">Yes</p>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Terms and Conditions -->
                        @if ($quotation->terms_condition)
                            <div class="bg-white border border-gray-200 rounded-lg p-6 lg:col-span-2">
                                <h3 class="text-lg font-semibold text-gray-900 mb-4">
                                    <i class="fas fa-file-contract text-purple-600 mr-2"></i>
                                    Terms and Conditions
                                </h3>
                                <div class="prose prose-sm max-w-none">
                                    <p class="text-gray-700 whitespace-pre-line">{{ $quotation->terms_condition }}</p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Items Tab -->
                <div x-show="activeTab === 'items'" x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">

                    <!-- Products Section -->
                    @if ($quotation->items->where('type', 'product')->count() > 0)
                        <div class="bg-white border border-gray-200 rounded-lg p-6 mb-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">
                                <i class="fas fa-box text-blue-600 mr-2"></i>
                                Products ({{ $quotation->items->where('type', 'product')->count() }} items)
                            </h3>
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th
                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Product</th>
                                            <th
                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                HSN</th>
                                            <th
                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Qty</th>
                                            <th
                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Unit Price</th>
                                            <th
                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Discount</th>
                                            <th
                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Taxable Amt</th>
                                            <th
                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                CGST</th>
                                            <th
                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                SGST</th>
                                            <th
                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                IGST</th>
                                            <th
                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Total</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @foreach ($quotation->items->where('type', 'product') as $item)
                                            <tr class="hover:bg-gray-50">
                                                <td class="px-6 py-4">
                                                    <div class="text-sm font-medium text-gray-900">
                                                        {{ $item->product->name ?? 'Product not found' }}</div>
                                                    @if ($item->product->description)
                                                        <div class="text-sm text-gray-500">
                                                            {{ Str::limit($item->product->description, 50) }}</div>
                                                    @endif
                                                </td>
                                                <td class="px-6 py-4 text-sm text-gray-900">
                                                    {{ $item->product->hsn_code ?? 'N/A' }}</td>
                                                <td class="px-6 py-4 text-sm text-gray-900">
                                                    {{ number_format($item->quantity) }}</td>
                                                <td class="px-6 py-4 text-sm text-gray-900">
                                                    ₹{{ number_format($item->unit_price, 2) }}</td>
                                                <td class="px-6 py-4 text-sm text-gray-900">
                                                    @if ($item->discount_amount > 0)
                                                        <span
                                                            class="text-red-600">-₹{{ number_format($item->discount_amount, 2) }}</span>
                                                    @else
                                                        ₹0.00
                                                    @endif
                                                </td>
                                                <td class="px-6 py-4 text-sm text-gray-900">
                                                    ₹{{ number_format($item->taxable_amount ?? $item->unit_price * $item->quantity, 2) }}
                                                </td>
                                                <td class="px-6 py-4 text-sm text-gray-900">
                                                    @if ($item->product && !$item->product->is_igst)
                                                        {{ $item->product->gst_percentage / 2 }}% =
                                                        ₹{{ number_format($item->cgst, 2) }}
                                                    @else
                                                        0% = ₹0.00
                                                    @endif
                                                </td>
                                                <td class="px-6 py-4 text-sm text-gray-900">
                                                    @if ($item->product && !$item->product->is_igst)
                                                        {{ $item->product->gst_percentage / 2 }}% =
                                                        ₹{{ number_format($item->sgst, 2) }}
                                                    @else
                                                        0% = ₹0.00
                                                    @endif
                                                </td>
                                                <td class="px-6 py-4 text-sm text-gray-900">
                                                    @if ($item->product && $item->product->is_igst)
                                                        {{ $item->product->gst_percentage }}% =
                                                        ₹{{ number_format($item->igst, 2) }}
                                                    @else
                                                        0% = ₹0.00
                                                    @endif
                                                </td>
                                                <td class="px-6 py-4 text-sm font-medium text-gray-900">
                                                    ₹{{ number_format($item->total, 2) }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @endif

                    <!-- Services Section -->
                    @if ($quotation->items->where('type', 'service')->count() > 0)
                        <div class="bg-white border border-gray-200 rounded-lg p-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">
                                <i class="fas fa-cogs text-green-600 mr-2"></i>
                                Services ({{ $quotation->items->where('type', 'service')->count() }} items)
                            </h3>
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th
                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Service</th>
                                            <th
                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Qty</th>
                                            <th
                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Unit Price</th>
                                            <th
                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Discount</th>
                                            <th
                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Taxable Amt</th>
                                            <th
                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                GST</th>
                                            <th
                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                GST Amount</th>
                                            <th
                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Total</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @foreach ($quotation->items->where('type', 'service') as $item)
                                            <tr class="hover:bg-gray-50">
                                                <td class="px-6 py-4">
                                                    <div class="text-sm font-medium text-gray-900">
                                                        {{ $item->service->name ?? 'Service not found' }}</div>
                                                    @if ($item->service->description)
                                                        <div class="text-sm text-gray-500">
                                                            {{ Str::limit($item->service->description, 50) }}</div>
                                                    @endif
                                                </td>
                                                <td class="px-6 py-4 text-sm text-gray-900">
                                                    {{ number_format($item->quantity) }}</td>
                                                <td class="px-6 py-4 text-sm text-gray-900">
                                                    ₹{{ number_format($item->unit_price, 2) }}</td>
                                                <td class="px-6 py-4 text-sm text-gray-900">
                                                    @if ($item->discount_amount > 0)
                                                        <span
                                                            class="text-red-600">-₹{{ number_format($item->discount_amount, 2) }}</span>
                                                    @else
                                                        ₹0.00
                                                    @endif
                                                </td>
                                                <td class="px-6 py-4 text-sm text-gray-900">
                                                    ₹{{ number_format($item->taxable_amount ?? $item->unit_price * $item->quantity, 2) }}
                                                </td>
                                                <td class="px-6 py-4 text-sm text-gray-900">
                                                    {{ $item->service->gst_percentage ?? 0 }}%</td>
                                                <td class="px-6 py-4 text-sm text-gray-900">
                                                    ₹{{ number_format($item->gst, 2) }}</td>
                                                <td class="px-6 py-4 text-sm font-medium text-gray-900">
                                                    ₹{{ number_format($item->total, 2) }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @endif

                    @if ($quotation->items->count() === 0)
                        <div class="text-center py-12">
                            <i class="fas fa-inbox text-gray-300 text-6xl mb-4"></i>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">No items found</h3>
                            <p class="text-gray-500">This quotation doesn't have any products or services.</p>
                        </div>
                    @endif
                </div>

                <!-- Summary Tab -->
                <div x-show="activeTab === 'summary'" x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <!-- Product Summary -->
                        @if ($quotation->items->where('type', 'product')->count() > 0)
                            <div class="bg-white border border-gray-200 rounded-lg p-6">
                                <h3 class="text-lg font-semibold text-blue-600 mb-4">
                                    <i class="fas fa-box mr-2"></i>
                                    Product Summary
                                </h3>
                                <div class="space-y-3">
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Subtotal:</span>
                                        <span
                                            class="font-medium">₹{{ number_format($quotation->items->where('type', 'product')->sum(function ($item) {return $item->quantity * $item->unit_price;}),2) }}</span>
                                    </div>
                                    @if ($quotation->items->where('type', 'product')->sum('discount_amount') > 0)
                                        <div class="flex justify-between">
                                            <span class="text-red-600">Discount:</span>
                                            <span
                                                class="font-medium text-red-600">-₹{{ number_format($quotation->items->where('type', 'product')->sum('discount_amount'), 2) }}</span>
                                        </div>
                                    @endif
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Taxable Amount:</span>
                                        <span
                                            class="font-medium">₹{{ number_format($quotation->items->where('type', 'product')->sum('taxable_amount'), 2) }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">CGST:</span>
                                        <span
                                            class="font-medium">₹{{ number_format($quotation->items->where('type', 'product')->sum('cgst'), 2) }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">SGST:</span>
                                        <span
                                            class="font-medium">₹{{ number_format($quotation->items->where('type', 'product')->sum('sgst'), 2) }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">IGST:</span>
                                        <span
                                            class="font-medium">₹{{ number_format($quotation->items->where('type', 'product')->sum('igst'), 2) }}</span>
                                    </div>
                                    <div class="border-t border-gray-200 pt-3 flex justify-between text-lg font-bold">
                                        <span class="text-gray-900">Product Total:</span>
                                        <span
                                            class="text-blue-600">₹{{ number_format($quotation->items->where('type', 'product')->sum('total'), 2) }}</span>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <!-- Service Summary -->
                        @if ($quotation->items->where('type', 'service')->count() > 0)
                            <div class="bg-white border border-gray-200 rounded-lg p-6">
                                <h3 class="text-lg font-semibold text-green-600 mb-4">
                                    <i class="fas fa-cogs mr-2"></i>
                                    Service Summary
                                </h3>
                                <div class="space-y-3">
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Subtotal:</span>
                                        <span
                                            class="font-medium">₹{{ number_format($quotation->items->where('type', 'service')->sum(function ($item) {return $item->quantity * $item->unit_price;}),2) }}</span>
                                    </div>
                                    @if ($quotation->items->where('type', 'service')->sum('discount_amount') > 0)
                                        <div class="flex justify-between">
                                            <span class="text-red-600">Discount:</span>
                                            <span
                                                class="font-medium text-red-600">-₹{{ number_format($quotation->items->where('type', 'service')->sum('discount_amount'), 2) }}</span>
                                        </div>
                                    @endif
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Taxable Amount:</span>
                                        <span
                                            class="font-medium">₹{{ number_format($quotation->items->where('type', 'service')->sum('taxable_amount'), 2) }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">GST Total:</span>
                                        <span
                                            class="font-medium">₹{{ number_format($quotation->items->where('type', 'service')->sum('gst'), 2) }}</span>
                                    </div>
                                    <div class="border-t border-gray-200 pt-3 flex justify-between text-lg font-bold">
                                        <span class="text-gray-900">Service Total:</span>
                                        <span
                                            class="text-green-600">₹{{ number_format($quotation->items->where('type', 'service')->sum('total'), 2) }}</span>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <!-- Grand Total -->
                        <div
                            class="bg-white border border-gray-200 rounded-lg p-6 {{ $quotation->items->where('type', 'product')->count() > 0 && $quotation->items->where('type', 'service')->count() > 0 ? 'lg:col-span-2' : '' }}">
                            <h3 class="text-lg font-semibold text-yellow-600 mb-4">
                                <i class="fas fa-calculator mr-2"></i>
                                Grand Total
                            </h3>
                            <div class="space-y-4">
                                <div class="flex justify-between text-lg">
                                    <span class="text-gray-700">Grand Subtotal:</span>
                                    <span
                                        class="font-semibold">₹{{ number_format($quotation->sub_total ??$quotation->items->sum(function ($item) {return $item->quantity * $item->unit_price;}),2) }}</span>
                                </div>
                                @if ($quotation->items->sum('discount_amount') > 0)
                                    <div class="flex justify-between text-lg">
                                        <span class="text-red-600">Total Discount:</span>
                                        <span
                                            class="font-semibold text-red-600">-₹{{ number_format($quotation->items->sum('discount_amount'), 2) }}</span>
                                    </div>
                                @endif
                                <div class="flex justify-between text-lg">
                                    <span class="text-gray-700">Taxable Amount:</span>
                                    <span
                                        class="font-semibold">₹{{ number_format($quotation->items->sum('taxable_amount'), 2) }}</span>
                                </div>
                                <div class="flex justify-between text-lg">
                                    <span class="text-gray-700">Total GST:</span>
                                    <span
                                        class="font-semibold">₹{{ number_format(($quotation->cgst ?? 0) + ($quotation->sgst ?? 0) + ($quotation->igst ?? 0) + ($quotation->gst ?? 0), 2) }}</span>
                                </div>
                                <div class="border-t border-gray-300 pt-4 flex justify-between text-2xl font-bold">
                                    <span class="text-gray-900">Grand Total:</span>
                                    <span
                                        class="text-yellow-600">₹{{ number_format($quotation->total ?? $quotation->items->sum('total'), 2) }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Print Preview Tab -->
                <div x-show="activeTab === 'preview'" x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
                    <div class="bg-white border border-gray-200 rounded-lg overflow-hidden" id="printable-content">
                        <!-- Print Header -->
                        <div class="bg-gray-50 px-6 py-4 border-b border-gray-200 print:hidden">
                            <div class="flex items-center justify-between">
                                <h3 class="text-lg font-semibold text-gray-900">Print Preview</h3>
                                <div class="flex space-x-2">
                                    <button @click="printQuotation()"
                                        class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm rounded-lg transition-colors">
                                        <i class="fas fa-print mr-2"></i>Print
                                    </button>
                                    <button @click="downloadPDF()"
                                        class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm rounded-lg transition-colors">
                                        <i class="fas fa-download mr-2"></i>Download PDF
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Printable Content -->
                        <div class="p-8 print:p-0">
                            <!-- Company Header -->
                            <div class="text-center mb-8">
                                <h1 class="text-3xl font-bold text-gray-900 mb-2">{{ config('app.name', 'SKM') }}</h1>
                                <p class="text-gray-600">Your Company Address Line 1</p>
                                <p class="text-gray-600">Your Company Address Line 2</p>
                                <p class="text-gray-600">Phone: +91 XXXXX XXXXX | Email: info@company.com</p>
                                <div class="mt-4 pt-4 border-t border-gray-300">
                                    <h2 class="text-2xl font-semibold text-gray-800">QUOTATION</h2>
                                </div>
                            </div>

                            <!-- Quotation Info -->
                            <div class="grid grid-cols-2 gap-8 mb-8">
                                <div>
                                    <h3 class="font-semibold text-gray-900 mb-3">Bill To:</h3>
                                    <div class="text-gray-700">
                                        <p class="font-medium">{{ $quotation->customer->company_name ?? 'N/A' }}</p>
                                        <p>{{ $quotation->contactPerson->name ?? 'N/A' }}</p>
                                        <p>{{ $quotation->customer->address ?? 'N/A' }}</p>
                                        <p>{{ $quotation->contactPerson->phone ?? ($quotation->customer->phone ?? 'N/A') }}
                                        </p>
                                        <p>{{ $quotation->contactPerson->email ?? ($quotation->customer->email ?? 'N/A') }}
                                        </p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <div class="space-y-2">
                                        <div>
                                            <span class="font-medium">Quotation #:</span>
                                            <span>{{ $quotation->quotation_no ?? 'QUO-' . str_pad($quotation->id, 4, '0', STR_PAD_LEFT) }}</span>
                                        </div>
                                        <div>
                                            <span class="font-medium">Date:</span>
                                            <span>{{ \Carbon\Carbon::parse($quotation->quotation_date)->format('F d, Y') }}</span>
                                        </div>
                                        <div>
                                            <span class="font-medium">Valid Until:</span>
                                            <span>{{ \Carbon\Carbon::parse($quotation->quotation_date)->addDays(30)->format('F d, Y') }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Items Table -->
                            <div class="mb-8">
                                <table class="w-full border-collapse border border-gray-300">
                                    <thead>
                                        <tr class="bg-gray-50">
                                            <th class="border border-gray-300 px-4 py-2 text-left">Item</th>
                                            <th class="border border-gray-300 px-4 py-2 text-center">Qty</th>
                                            <th class="border border-gray-300 px-4 py-2 text-right">Unit Price</th>
                                            <th class="border border-gray-300 px-4 py-2 text-right">GST</th>
                                            <th class="border border-gray-300 px-4 py-2 text-right">Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($quotation->items as $item)
                                            <tr>
                                                <td class="border border-gray-300 px-4 py-2">
                                                    <div class="font-medium">
                                                        {{ $item->type === 'product' ? $item->product->name ?? 'Product not found' : $item->service->name ?? 'Service not found' }}
                                                    </div>
                                                    @if ($item->type === 'product' && $item->product)
                                                        <div class="text-sm text-gray-500">HSN:
                                                            {{ $item->product->hsn_code }}</div>
                                                    @endif
                                                </td>
                                                <td class="border border-gray-300 px-4 py-2 text-center">
                                                    {{ number_format($item->quantity) }}</td>
                                                <td class="border border-gray-300 px-4 py-2 text-right">
                                                    ₹{{ number_format($item->unit_price, 2) }}</td>
                                                <td class="border border-gray-300 px-4 py-2 text-right">
                                                    @if ($item->type === 'product')
                                                        ₹{{ number_format($item->cgst + $item->sgst + $item->igst, 2) }}
                                                    @else
                                                        ₹{{ number_format($item->gst, 2) }}
                                                    @endif
                                                </td>
                                                <td class="border border-gray-300 px-4 py-2 text-right font-medium">
                                                    ₹{{ number_format($item->total, 2) }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr class="bg-gray-50">
                                            <td colspan="4"
                                                class="border border-gray-300 px-4 py-2 text-right font-bold">Grand
                                                Total:</td>
                                            <td class="border border-gray-300 px-4 py-2 text-right font-bold text-lg">
                                                ₹{{ number_format($quotation->total ?? $quotation->items->sum('total'), 2) }}
                                            </td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>

                            <!-- Terms and Conditions -->
                            @if ($quotation->terms_condition)
                                <div class="mb-8">
                                    <h3 class="font-semibold text-gray-900 mb-3">Terms and Conditions:</h3>
                                    <div class="text-gray-700 text-sm whitespace-pre-line">
                                        {{ $quotation->terms_condition }}</div>
                                </div>
                            @endif

                            <!-- Footer -->
                            <div class="text-center text-gray-600 text-sm border-t border-gray-300 pt-4">
                                <p>Thank you for your business!</p>
                                <p class="mt-2">This is a computer generated quotation and does not require
                                    signature.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Convert to Invoice Modal -->
        @if (!$quotation->converted_to_invoice)
            <div x-show="showConvertModal" x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
                <div class="bg-white rounded-lg shadow-xl max-w-md w-full mx-4">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div
                                class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-blue-100">
                                <i class="fas fa-file-invoice text-blue-600"></i>
                            </div>
                        </div>
                        <div class="mt-3 text-center">
                            <h3 class="text-lg leading-6 font-medium text-gray-900">Convert to Invoice</h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500">
                                    Are you sure you want to convert this quotation to an invoice? This action cannot be
                                    undone.
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <form action="{{ route('quotations.convert-to-invoice', $quotation->id) }}" method="POST"
                            class="inline">
                            @csrf
                            <button type="submit"
                                class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm">
                                <i class="fas fa-file-invoice mr-2"></i>
                                Convert to Invoice
                            </button>
                        </form>
                        <button type="button" @click="showConvertModal = false"
                            class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                            Cancel
                        </button>
                    </div>
                </div>
            </div>
        @endif
    </div>

    <script>
        function quotationShowManager() {
            return {
                activeTab: 'details',
                showConvertModal: false,

                init() {
                    console.log('Quotation show page initialized');
                },

                printQuotation() {
                    window.print();
                },

                downloadPDF() {
                    // You can implement PDF generation here
                    // For now, we'll use the browser's print to PDF functionality
                    window.print();
                }
            }
        }

        // Print styles
        document.addEventListener('DOMContentLoaded', function() {
            const style = document.createElement('style');
            style.textContent = `
                @media print {
                    body * {
                        visibility: hidden;
                    }
                    #printable-content,
                    #printable-content * {
                        visibility: visible;
                    }
                    #printable-content {
                        position: absolute;
                        left: 0;
                        top: 0;
                        width: 100%;
                    }
                    .print\\:hidden {
                        display: none !important;
                    }
                    .print\\:p-0 {
                        padding: 0 !important;
                    }
                }
            `;
            document.head.appendChild(style);
        });
    </script>
</x-app-layout>
