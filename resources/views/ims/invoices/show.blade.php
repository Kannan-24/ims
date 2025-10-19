<x-app-layout>
    <x-slot name="title">
        {{ __('Invoice Details') }} - {{ config('app.name', 'SKM') }}
    </x-slot>

    <div class="bg-white min-h-screen" x-data="invoiceShowManager()" x-init="init()">
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
                            <a href="{{ route('invoices.index') }}"
                                class="text-sm font-medium text-gray-700 hover:text-blue-600">
                                Invoices
                            </a>
                        </div>
                    </li>
                    <li aria-current="page">
                        <div class="flex items-center">
                            <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                            <span class="text-sm font-medium text-gray-500">Invoice
                                #{{ $invoice->invoice_no ?? $invoice->id }}</span>
                        </div>
                    </li>
                </ol>
            </nav>
        </div>

        <!-- Header -->
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Invoice #{{ $invoice->invoice_no ?? $invoice->id }}
                    </h1>
                    <div class="flex items-center space-x-4 mt-1">
                        <span class="text-sm text-gray-600">Created: {{ $invoice->created_at->format('M d, Y') }}</span>
                    </div>
                </div>
                <div class="flex items-center space-x-3">
                    <!-- Download PDF Button -->
                    <a href="{{ route('invoices.pdf', $invoice->id) }}" target="_blank"
                        class="inline-flex items-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg transition-colors">
                        <i class="fas fa-file-pdf w-4 h-4 mr-2"></i>
                        Download PDF
                    </a>

                    <!-- QR View Button -->
                    <a href="{{ route('invoices.qr-view', $invoice->id) }}" target="_blank"
                        class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg transition-colors">
                        <i class="fas fa-qrcode w-4 h-4 mr-2"></i>
                        QR View
                    </a>

                    <!-- Delivery Challan Button -->
                    <button @click="generateDeliveryChallan({{ $invoice->id }})"
                        class="inline-flex items-center px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white text-sm font-medium rounded-lg transition-colors">
                        <i class="fas fa-truck w-4 h-4 mr-2"></i>
                        Delivery Challan
                    </button>

                    <!-- Edit Button -->
                    <a href="{{ route('invoices.edit', $invoice->id) }}"
                        class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-lg transition-colors">
                        <i class="fas fa-edit w-4 h-4 mr-2"></i>
                        Edit
                    </a>

                    <!-- Delete Button -->
                    <button @click="showDeleteModal = true"
                        class="inline-flex items-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg transition-colors">
                        <i class="fas fa-trash w-4 h-4 mr-2"></i>
                        Delete
                    </button>

                    <!-- Back Button -->
                    <a href="{{ route('invoices.index') }}"
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
                        Invoice Details
                    </button>
                    <button type="button" @click="activeTab = 'items'"
                        :class="activeTab === 'items' ? 'border-blue-500 text-blue-600' :
                            'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                        class="py-2 px-1 border-b-2 font-medium text-sm">
                        <i class="fas fa-list mr-2"></i>
                        Items & Pricing
                        <span
                            class="ml-1 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-blue-100 bg-blue-600 rounded-full">
                            {{ $invoice->items->count() }}
                        </span>
                    </button>
                    <button type="button" @click="activeTab = 'summary'"
                        :class="activeTab === 'summary' ? 'border-blue-500 text-blue-600' :
                            'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                        class="py-2 px-1 border-b-2 font-medium text-sm">
                        <i class="fas fa-calculator mr-2"></i>
                        Summary
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
                                    <p class="text-sm text-gray-900">{{ $invoice->customer->company_name ?? 'N/A' }}
                                    </p>
                                </div>
                                <div>
                                    <span class="text-sm font-medium text-gray-500">GST Number:</span>
                                    <p class="text-sm text-gray-900 font-mono">
                                        {{ $invoice->customer->gst_number ?? 'N/A' }}</p>
                                </div>
                                <div>
                                    <span class="text-sm font-medium text-gray-500">Contact Person:</span>
                                    <p class="text-sm text-gray-900">
                                        {{ $invoice->customer->contactPersons->firstWhere('id', $invoice->contactperson_id)?->name ?? 'N/A' }}
                                    </p>
                                </div>
                                <div>
                                    <span class="text-sm font-medium text-gray-500">Contact Phone:</span>
                                    <p class="text-sm text-gray-900">
                                        {{ $invoice->customer->contactPersons->firstWhere('id', $invoice->contactperson_id)?->phone_no ?? 'N/A' }}
                                    </p>
                                </div>
                                <div>
                                    <span class="text-sm font-medium text-gray-500">Contact Email:</span>
                                    <p class="text-sm text-gray-900">
                                        {{ $invoice->customer->contactPersons->firstWhere('id', $invoice->contactperson_id)?->email ?? 'N/A' }}
                                    </p>
                                </div>
                                <div>
                                    <span class="text-sm font-medium text-gray-500">Address:</span>
                                    <p class="text-sm text-gray-900">
                                        {{ $invoice->customer->address ?? 'N/A' }}
                                        {{ $invoice->customer->city ?? '' }} -
                                        {{ $invoice->customer->zip_code ?? '' }}
                                        {{ $invoice->customer->state ?? '' }}, {{ $invoice->customer->country ?? '' }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Invoice Information -->
                        <div class="bg-white border border-gray-200 rounded-lg p-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">
                                <i class="fas fa-file-invoice text-green-600 mr-2"></i>
                                Invoice Information
                            </h3>
                            <div class="space-y-3">
                                <div>
                                    <span class="text-sm font-medium text-gray-500">Invoice Number:</span>
                                    <p class="text-sm text-gray-900 font-mono">
                                        @if (!empty($invoice->invoice_no))
                                            {{ $invoice->invoice_no }}
                                        @else
                                            <span class="text-red-500">Not Set -
                                                INV-{{ str_pad($invoice->id, 4, '0', STR_PAD_LEFT) }}</span>
                                        @endif
                                    </p>
                                </div>
                                <div>
                                    <span class="text-sm font-medium text-gray-500">Order Number:</span>
                                    <p class="text-sm text-gray-900 font-mono">
                                        @if (!empty($invoice->order_no))
                                            {{ $invoice->order_no }}
                                        @else
                                            <span class="text-red-500">Not Set</span>
                                        @endif
                                    </p>
                                </div>
                                <div>
                                    <span class="text-sm font-medium text-gray-500">Invoice Date:</span>
                                    <p class="text-sm text-gray-900">
                                        @if (!empty($invoice->invoice_date))
                                            {{ \Carbon\Carbon::parse($invoice->invoice_date)->format('F d, Y') }}
                                        @else
                                            <span class="text-red-500">Not Set</span>
                                        @endif
                                    </p>
                                </div>
                                <div>
                                    <span class="text-sm font-medium text-gray-500">Order Date:</span>
                                    <p class="text-sm text-gray-900">
                                        @if (!empty($invoice->order_date))
                                            {{ \Carbon\Carbon::parse($invoice->order_date)->format('F d, Y') }}
                                        @else
                                            <span class="text-red-500">Not Set</span>
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Items Tab -->
                <div x-show="activeTab === 'items'" x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">

                    <!-- Products Section -->
                    @if ($invoice->items->where('type', 'product')->count() > 0)
                        <div class="bg-white border border-gray-200 rounded-lg p-6 mb-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">
                                <i class="fas fa-box text-blue-600 mr-2"></i>
                                Products ({{ $invoice->items->where('type', 'product')->count() }} items)
                            </h3>
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th
                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                #</th>
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
                                                Unit Type</th>
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
                                        @foreach ($invoice->items->where('type', 'product') as $item)
                                            <tr class="hover:bg-gray-50">
                                                <td class="px-6 py-4 text-sm text-gray-900">{{ $loop->iteration }}
                                                </td>
                                                <td class="px-6 py-4">
                                                    <div class="text-sm font-medium text-gray-900">
                                                        {{ $item->product->name ?? 'Product not found' }}</div>
                                                    @if ($item->product->description ?? false)
                                                        <div class="text-sm text-gray-500">
                                                            {{ Str::limit($item->product->description, 50) }}</div>
                                                    @endif
                                                </td>
                                                <td class="px-6 py-4 text-sm text-gray-900">
                                                    {{ $item->product->hsn_code ?? 'N/A' }}</td>
                                                <td class="px-6 py-4 text-sm text-gray-900">
                                                    {{ number_format($item->quantity) }}</td>
                                                <td class="px-6 py-4 text-sm text-gray-900">
                                                    {{ $item->unit_type ?? 'N/A' }}</td>
                                                <td class="px-6 py-4 text-sm text-gray-900">
                                                    ₹{{ number_format($item->unit_price, 2) }}</td>
                                                <td class="px-6 py-4 text-sm text-gray-900">
                                                    @if (($item->discount_amount ?? 0) > 0)
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
                    @if ($invoice->items->where('type', 'service')->count() > 0)
                        <div class="bg-white border border-gray-200 rounded-lg p-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">
                                <i class="fas fa-cogs text-green-600 mr-2"></i>
                                Services ({{ $invoice->items->where('type', 'service')->count() }} items)
                            </h3>
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th
                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                #</th>
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
                                                CGST</th>
                                            <th
                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                SGST</th>
                                            <th
                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Total</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @foreach ($invoice->items->where('type', 'service') as $item)
                                            <tr class="hover:bg-gray-50">
                                                <td class="px-6 py-4 text-sm text-gray-900">{{ $loop->iteration }}
                                                </td>
                                                <td class="px-6 py-4">
                                                    <div class="text-sm font-medium text-gray-900">
                                                        {{ $item->service->name ?? 'Service not found' }}</div>
                                                    @if ($item->service->description ?? false)
                                                        <div class="text-sm text-gray-500">
                                                            {{ Str::limit($item->service->description, 50) }}</div>
                                                    @endif
                                                </td>
                                                <td class="px-6 py-4 text-sm text-gray-900">
                                                    {{ number_format($item->quantity) }}</td>
                                                <td class="px-6 py-4 text-sm text-gray-900">
                                                    ₹{{ number_format($item->unit_price, 2) }}</td>
                                                <td class="px-6 py-4 text-sm text-gray-900">
                                                    @if (($item->discount_amount ?? 0) > 0)
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
                                                    {{ $item->service->gst_percentage / 2 ?? 0 }}% =
                                                    ₹{{ number_format($item->cgst, 2) }}
                                                </td>
                                                <td class="px-6 py-4 text-sm text-gray-900">
                                                    {{ $item->service->gst_percentage / 2 ?? 0 }}% =
                                                    ₹{{ number_format($item->sgst, 2) }}
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

                    @if ($invoice->items->count() === 0)
                        <div class="text-center py-12">
                            <i class="fas fa-inbox text-gray-300 text-6xl mb-4"></i>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">No items found</h3>
                            <p class="text-gray-500">This invoice doesn't have any products or services.</p>
                        </div>
                    @endif
                </div>

                <!-- Summary Tab -->
                <div x-show="activeTab === 'summary'" x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <!-- Product Summary -->
                        @if ($invoice->items->where('type', 'product')->count() > 0)
                            <div class="bg-white border border-gray-200 rounded-lg p-6">
                                <h3 class="text-lg font-semibold text-blue-600 mb-4">
                                    <i class="fas fa-box mr-2"></i>
                                    Product Summary
                                </h3>
                                <div class="space-y-3">
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Subtotal:</span>
                                        <span
                                            class="font-medium">₹{{ number_format($invoice->items->where('type', 'product')->sum(function ($item) {return $item->quantity * $item->unit_price;}),2) }}</span>
                                    </div>
                                    @if ($invoice->items->where('type', 'product')->sum('discount_amount') > 0)
                                        <div class="flex justify-between">
                                            <span class="text-red-600">Discount:</span>
                                            <span
                                                class="font-medium text-red-600">-₹{{ number_format($invoice->items->where('type', 'product')->sum('discount_amount'), 2) }}</span>
                                        </div>
                                    @endif
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Taxable Amount:</span>
                                        <span
                                            class="font-medium">₹{{ number_format($invoice->items->where('type', 'product')->sum('taxable_amount'), 2) }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">CGST:</span>
                                        <span
                                            class="font-medium">₹{{ number_format($invoice->items->where('type', 'product')->sum('cgst'), 2) }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">SGST:</span>
                                        <span
                                            class="font-medium">₹{{ number_format($invoice->items->where('type', 'product')->sum('sgst'), 2) }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">IGST:</span>
                                        <span
                                            class="font-medium">₹{{ number_format($invoice->items->where('type', 'product')->sum('igst'), 2) }}</span>
                                    </div>
                                    <div class="border-t border-gray-200 pt-3 flex justify-between text-lg font-bold">
                                        <span class="text-gray-900">Product Total:</span>
                                        <span
                                            class="text-blue-600">₹{{ number_format($invoice->items->where('type', 'product')->sum('total'), 2) }}</span>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <!-- Service Summary -->
                        @if ($invoice->items->where('type', 'service')->count() > 0)
                            <div class="bg-white border border-gray-200 rounded-lg p-6">
                                <h3 class="text-lg font-semibold text-green-600 mb-4">
                                    <i class="fas fa-cogs mr-2"></i>
                                    Service Summary
                                </h3>
                                <div class="space-y-3">
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Subtotal:</span>
                                        <span
                                            class="font-medium">₹{{ number_format($invoice->items->where('type', 'service')->sum(function ($item) {return $item->quantity * $item->unit_price;}),2) }}</span>
                                    </div>
                                    @if ($invoice->items->where('type', 'service')->sum('discount_amount') > 0)
                                        <div class="flex justify-between">
                                            <span class="text-red-600">Discount:</span>
                                            <span
                                                class="font-medium text-red-600">-₹{{ number_format($invoice->items->where('type', 'service')->sum('discount_amount'), 2) }}</span>
                                        </div>
                                    @endif
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Taxable Amount:</span>
                                        <span
                                            class="font-medium">₹{{ number_format($invoice->items->where('type', 'service')->sum('taxable_amount'), 2) }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">CGST:</span>
                                        <span
                                            class="font-medium">₹{{ number_format($invoice->items->where('type', 'service')->sum('cgst'), 2) }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">SGST:</span>
                                        <span
                                            class="font-medium">₹{{ number_format($invoice->items->where('type', 'service')->sum('sgst'), 2) }}</span>
                                    </div>
                                    <div class="border-t border-gray-200 pt-3 flex justify-between text-lg font-bold">
                                        <span class="text-gray-900">Service Total:</span>
                                        <span
                                            class="text-green-600">₹{{ number_format($invoice->items->where('type', 'service')->sum('total'), 2) }}</span>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <!-- Grand Total -->
                        <div
                            class="bg-white border border-gray-200 rounded-lg p-6 {{ $invoice->items->where('type', 'product')->count() > 0 && $invoice->items->where('type', 'service')->count() > 0 ? 'lg:col-span-2' : '' }}">
                            <h3 class="text-lg font-semibold text-yellow-600 mb-4">
                                <i class="fas fa-calculator mr-2"></i>
                                Grand Total
                            </h3>
                            <div class="space-y-4">
                                <div class="flex justify-between text-lg">
                                    <span class="text-gray-700">Grand Subtotal:</span>
                                    <span
                                        class="font-semibold">₹{{ number_format($invoice->sub_total ??$invoice->items->sum(function ($item) {return $item->quantity * $item->unit_price;}),2) }}</span>
                                </div>
                                @if ($invoice->items->sum('discount_amount') > 0)
                                    <div class="flex justify-between text-lg">
                                        <span class="text-red-600">Total Discount:</span>
                                        <span
                                            class="font-semibold text-red-600">-₹{{ number_format($invoice->items->sum('discount_amount'), 2) }}</span>
                                    </div>
                                @endif
                                <div class="flex justify-between text-lg">
                                    <span class="text-gray-700">Taxable Amount:</span>
                                    <span
                                        class="font-semibold">₹{{ number_format($invoice->items->sum('taxable_amount'), 2) }}</span>
                                </div>
                                <div class="flex justify-between text-lg">
                                    <span class="text-gray-700">Total CGST:</span>
                                    <span class="font-semibold">₹{{ number_format($invoice->cgst ?? 0, 2) }}</span>
                                </div>
                                <div class="flex justify-between text-lg">
                                    <span class="text-gray-700">Total SGST:</span>
                                    <span class="font-semibold">₹{{ number_format($invoice->sgst ?? 0, 2) }}</span>
                                </div>
                                <div class="flex justify-between text-lg">
                                    <span class="text-gray-700">Total IGST:</span>
                                    <span class="font-semibold">₹{{ number_format($invoice->igst ?? 0, 2) }}</span>
                                </div>
                                <div class="border-t border-gray-300 pt-4 flex justify-between text-2xl font-bold">
                                    <span class="text-gray-900">Grand Total:</span>
                                    <span
                                        class="text-yellow-600">₹{{ number_format($invoice->total ?? $invoice->items->sum('total'), 2) }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Delete Confirmation Modal -->
        <div x-show="showDeleteModal" x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-white rounded-lg shadow-xl max-w-md w-full mx-4">
                <div class="p-6">
                    <div class="flex items-center">
                        <div
                            class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100">
                            <i class="fas fa-exclamation-triangle text-red-600"></i>
                        </div>
                    </div>
                    <div class="mt-3 text-center">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">Delete Invoice</h3>
                        <div class="mt-2">
                            <p class="text-sm text-gray-500">
                                Are you sure you want to delete this invoice? This action cannot be undone and all
                                associated data will be permanently removed.
                            </p>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <form action="{{ route('invoices.destroy', $invoice->id) }}" method="POST" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                            class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">
                            <i class="fas fa-trash mr-2"></i>
                            Delete Invoice
                        </button>
                    </form>
                    <button type="button" @click="showDeleteModal = false"
                        class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        Cancel
                    </button>
                </div>
            </div>
        </div>

        <!-- Toast Notification Container -->
        <div id="toast-container" class="fixed top-4 right-4 z-50 space-y-2"></div>
    </div>

    <script>
        function invoiceShowManager() {
            return {
                activeTab: 'details',
                showDeleteModal: false,

                init() {

                },

                generateDeliveryChallan(invoiceId) {
                    if (confirm('Generate delivery challan for this invoice?')) {
                        this.showToast('info', 'Generating delivery challan...');

                        fetch('/ims/delivery-challans/generate', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                                        'content')
                                },
                                body: JSON.stringify({
                                    invoice_id: invoiceId
                                })
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    this.showToast('success', data.message);
                                    if (confirm('Would you like to view the generated delivery challan?')) {
                                        window.open(data.pdf_url, '_blank');
                                    }
                                } else {
                                    this.showToast('error', data.message || 'Failed to generate delivery challan');
                                }
                            })
                            .catch(error => {
                                console.error('Error:', error);
                                this.showToast('error', 'An error occurred while generating delivery challan');
                            });
                    }
                },

                showToast(type, message) {
                    const toastContainer = document.getElementById('toast-container');
                    const toastDiv = document.createElement('div');

                    let bgColor, textColor, icon;
                    switch (type) {
                        case 'success':
                            bgColor = 'bg-green-50 border-green-200';
                            textColor = 'text-green-800';
                            icon = 'fa-check-circle text-green-600';
                            break;
                        case 'error':
                            bgColor = 'bg-red-50 border-red-200';
                            textColor = 'text-red-800';
                            icon = 'fa-exclamation-circle text-red-600';
                            break;
                        case 'info':
                            bgColor = 'bg-blue-50 border-blue-200';
                            textColor = 'text-blue-800';
                            icon = 'fa-info-circle text-blue-600';
                            break;
                        default:
                            bgColor = 'bg-gray-50 border-gray-200';
                            textColor = 'text-gray-800';
                            icon = 'fa-bell text-gray-600';
                    }

                    toastDiv.className =
                        `${bgColor} ${textColor} border rounded-lg shadow-lg p-4 max-w-sm transition-all duration-300 transform`;
                    toastDiv.innerHTML = `
                        <div class="flex items-center">
                            <i class="fas ${icon} mr-3 text-lg"></i>
                            <div class="flex-1">
                                <p class="font-medium">${message}</p>
                            </div>
                            <button onclick="this.parentElement.parentElement.remove()" class="ml-3 text-lg hover:opacity-70">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    `;

                    toastContainer.appendChild(toastDiv);

                    if (type !== 'info') {
                        setTimeout(() => {
                            if (toastDiv.parentNode) {
                                toastDiv.remove();
                            }
                        }, 5000);
                    }
                },

                printInvoice() {
                    window.print();
                }
            }
        }

        // Initialize Alpine.js
        document.addEventListener('alpine:init', () => {
            console.log('Alpine.js initialized for invoice show page');
            console.log('System ready - User: Kannan-24');
            console.log('Timestamp (UTC): 2025-10-19 14:03:52');
        });

        // Page load event
        document.addEventListener('DOMContentLoaded', function() {
            console.log('Invoice show page loaded successfully');
            console.log('Current User: Kannan-24');
            console.log('Timestamp (UTC): 2025-10-19 14:03:52');
        });

        // Print styles
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
    </script>
</x-app-layout>
