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
                            <span class="text-sm font-medium text-gray-500">Invoice #{{ $invoice->invoice_no }}</span>
                        </div>
                    </li>
                </ol>
            </nav>
        </div>

        <!-- Header -->
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <div class="flex flex-col">
                    <h1 class="text-2xl font-bold text-gray-900">Invoice #{{ $invoice->invoice_no }}</h1>
                    <span class="text-sm text-gray-600">Created: {{ $invoice->created_at->format('M d, Y') }}</span>
                    <span class="text-sm text-gray-600">Order: {{ $invoice->order_no ?? 'N/A' }}</span>
                </div>
                <div class="flex items-center space-x-3">
                    <!-- Edit Button -->
                    <a href="{{ route('invoices.edit', $invoice->id) }}"
                        class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-lg transition-colors">
                        <i class="fas fa-edit w-4 h-4 mr-2"></i>
                        Edit
                    </a>

                    <!-- Delete Button -->
                    <button @click="deleteInvoice()"
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

        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex items-center space-x-3 justify-end flex-wrap">
                <!-- Print Button -->
                <button @click="printInvoice()"
                    class="inline-flex items-center px-3 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium rounded-lg transition-colors">
                    <i class="fas fa-print w-4 h-4 mr-2"></i>
                    Print
                </button>

                <!-- PDF Button -->
                <a href="{{ route('invoices.pdf', $invoice->id) }}" target="_blank"
                    class="inline-flex items-center px-3 py-2 bg-red-100 hover:bg-red-200 text-red-700 text-sm font-medium rounded-lg transition-colors">
                    <i class="fas fa-file-pdf w-4 h-4 mr-2"></i>
                    PDF
                </a>

                <!-- QR View Button -->
                <a href="{{ route('invoices.qr-view', $invoice->id) }}" target="_blank"
                    class="inline-flex items-center px-3 py-2 bg-indigo-100 hover:bg-indigo-200 text-indigo-700 text-sm font-medium rounded-lg transition-colors">
                    <i class="fas fa-qrcode w-4 h-4 mr-2"></i>
                    QR View
                </a>

                <!-- Delivery Challan Button -->
                <button @click="generateDeliveryChallan()"
                    class="inline-flex items-center px-3 py-2 bg-purple-100 hover:bg-purple-200 text-purple-700 text-sm font-medium rounded-lg transition-colors">
                    <i class="fas fa-truck w-4 h-4 mr-2"></i>
                    Delivery Challan
                </button>

                <!-- Email Button -->
                <a href="{{ route('emails.create') }}?invoice_id={{ $invoice->id }}"
                    class="inline-flex items-center px-3 py-2 bg-orange-100 hover:bg-orange-200 text-orange-700 text-sm font-medium rounded-lg transition-colors">
                    <i class="fas fa-envelope w-4 h-4 mr-2"></i>
                    Email
                </a>
            </div>
        </div>

        <!-- Top Overview Strip -->
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <div class="bg-white border border-gray-200 rounded-lg p-4">
                    <div class="flex items-center">
                        <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-hashtag text-blue-600"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Invoice No</p>
                            <p class="text-lg font-semibold text-gray-900">{{ $invoice->invoice_no }}</p>
                        </div>
                    </div>
                </div>
                <div class="bg-white border border-gray-200 rounded-lg p-4">
                    <div class="flex items-center">
                        <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-calendar text-green-600"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Invoice Date</p>
                            <p class="text-lg font-semibold text-gray-900">
                                {{ \Carbon\Carbon::parse($invoice->invoice_date)->format('M d, Y') }}</p>
                        </div>
                    </div>
                </div>
                <div class="bg-white border border-gray-200 rounded-lg p-4">
                    <div class="flex items-center">
                        <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-shopping-cart text-purple-600"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Order No</p>
                            <p class="text-lg font-semibold text-gray-900">{{ $invoice->order_no ?? 'N/A' }}</p>
                        </div>
                    </div>
                </div>
                <div class="bg-white border border-gray-200 rounded-lg p-4">
                    <div class="flex items-center">
                        <div class="w-10 h-10 bg-orange-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-rupee-sign text-orange-600"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Total Amount</p>
                            <p class="text-lg font-semibold text-gray-900">₹{{ number_format($invoice->total, 2) }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tab Navigation -->
        <div class="px-6 border-b border-gray-200">
            <nav class="-mb-px flex space-x-8">
                <button type="button" @click="activeTab = 'overview'"
                    :class="activeTab === 'overview' ? 'border-blue-500 text-blue-600' :
                        'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                    class="py-2 px-1 border-b-2 font-medium text-sm">
                    <i class="fas fa-info-circle mr-2"></i>
                    Overview
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
        <div class="p-6">
            <!-- Overview Tab -->
            <div x-show="activeTab === 'overview'" x-transition:enter="transition ease-out duration-200"
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
                                <p class="text-sm text-gray-900">{{ $invoice->customer->company_name ?? 'N/A' }}</p>
                            </div>
                            <div>
                                <span class="text-sm font-medium text-gray-500">GST Number:</span>
                                <p class="text-sm text-gray-900">{{ $invoice->customer->gst_number ?? 'N/A' }}</p>
                            </div>
                            <div>
                                <span class="text-sm font-medium text-gray-500">Address:</span>
                                <p class="text-sm text-gray-900">
                                    {{ $invoice->customer->address ?? 'N/A' }}
                                    @if ($invoice->customer->city)
                                        , {{ $invoice->customer->city }}
                                    @endif
                                    @if ($invoice->customer->zip_code)
                                        - {{ $invoice->customer->zip_code }}
                                    @endif
                                    @if ($invoice->customer->state)
                                        , {{ $invoice->customer->state }}
                                    @endif
                                    @if ($invoice->customer->country)
                                        , {{ $invoice->customer->country }}
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Contact Person Information -->
                    <div class="bg-white border border-gray-200 rounded-lg p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">
                            <i class="fas fa-user text-green-600 mr-2"></i>
                            Contact Person
                        </h3>
                        @php
                            $contactPerson = $invoice->customer->contactPersons
                                ->where('id', $invoice->contactperson_id)
                                ->first();
                        @endphp
                        @if ($contactPerson)
                            <div class="space-y-3">
                                <div>
                                    <span class="text-sm font-medium text-gray-500">Name:</span>
                                    <p class="text-sm text-gray-900">{{ $contactPerson->name }}</p>
                                </div>
                                <div>
                                    <span class="text-sm font-medium text-gray-500">Phone:</span>
                                    <p class="text-sm text-gray-900">{{ $contactPerson->phone_no ?? 'N/A' }}</p>
                                </div>
                                <div>
                                    <span class="text-sm font-medium text-gray-500">Email:</span>
                                    <p class="text-sm text-gray-900">{{ $contactPerson->email ?? 'N/A' }}</p>
                                </div>
                            </div>
                        @else
                            <p class="text-sm text-gray-500">No contact person assigned</p>
                        @endif
                    </div>

                    <!-- Invoice Details -->
                    <div class="bg-white border border-gray-200 rounded-lg p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">
                            <i class="fas fa-file-invoice text-purple-600 mr-2"></i>
                            Invoice Details
                        </h3>
                        <div class="space-y-3">
                            <div>
                                <span class="text-sm font-medium text-gray-500">Invoice Date:</span>
                                <p class="text-sm text-gray-900">
                                    {{ \Carbon\Carbon::parse($invoice->invoice_date)->format('F d, Y') }}</p>
                            </div>
                            <div>
                                <span class="text-sm font-medium text-gray-500">Order Date:</span>
                                <p class="text-sm text-gray-900">
                                    {{ \Carbon\Carbon::parse($invoice->order_date)->format('F d, Y') }}</p>
                            </div>
                            <div>
                                <span class="text-sm font-medium text-gray-500">Due Date:</span>
                                <p class="text-sm text-gray-900">
                                    {{ \Carbon\Carbon::parse($invoice->invoice_date)->addDays(30)->format('F d, Y') }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Information -->
                    <div class="bg-white border border-gray-200 rounded-lg p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">
                            <i class="fas fa-credit-card text-orange-600 mr-2"></i>
                            Payment Information
                        </h3>
                        <div class="space-y-3">
                            <div>
                                <span class="text-sm font-medium text-gray-500">Status:</span>
                                <span
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                    {{ $invoice->status === 'paid'
                                        ? 'bg-green-100 text-green-800'
                                        : ($invoice->status === 'pending'
                                            ? 'bg-yellow-100 text-yellow-800'
                                            : ($invoice->status === 'overdue'
                                                ? 'bg-red-100 text-red-800'
                                                : 'bg-gray-100 text-gray-800')) }}">
                                    {{ ucfirst($invoice->status ?? 'Pending') }}
                                </span>
                            </div>
                            <div>
                                <span class="text-sm font-medium text-gray-500">Total Amount:</span>
                                <p class="text-lg font-bold text-gray-900">₹{{ number_format($invoice->total, 2) }}
                                </p>
                            </div>
                            @if ($invoice->payment_date)
                                <div>
                                    <span class="text-sm font-medium text-gray-500">Payment Date:</span>
                                    <p class="text-sm text-gray-900">
                                        {{ \Carbon\Carbon::parse($invoice->payment_date)->format('F d, Y') }}</p>
                                </div>
                            @endif
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
                                    @foreach ($invoice->items->where('type', 'product') as $item)
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-6 py-4 text-sm text-gray-900">{{ $loop->iteration }}</td>
                                            <td class="px-6 py-4">
                                                <div class="text-sm font-medium text-gray-900">
                                                    {{ $item->product->name ?? 'Product not found' }}</div>
                                            </td>
                                            <td class="px-6 py-4 text-sm text-gray-900">
                                                {{ number_format($item->quantity) }}</td>
                                            <td class="px-6 py-4 text-sm text-gray-900">
                                                ₹{{ number_format($item->unit_price, 2) }}</td>
                                            <td class="px-6 py-4 text-sm text-gray-900">
                                                @if ($item->discount_amount > 0)
                                                    <span
                                                        class="text-red-600">₹{{ number_format($item->discount_amount, 2) }}</span>
                                                @else
                                                    ₹0.00
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 text-sm text-gray-900">
                                                @php
                                                    $calculatedTaxableAmount =
                                                        $item->taxable_amount ??
                                                        $item->quantity * $item->unit_price -
                                                            ($item->discount_amount ?? 0);
                                                @endphp
                                                ₹{{ number_format($calculatedTaxableAmount, 2) }}</td>
                                            <td class="px-6 py-4 text-sm text-gray-900">
                                                ₹{{ number_format($item->cgst, 2) }}</td>
                                            <td class="px-6 py-4 text-sm text-gray-900">
                                                ₹{{ number_format($item->sgst, 2) }}</td>
                                            <td class="px-6 py-4 text-sm text-gray-900">
                                                ₹{{ number_format($item->igst, 2) }}</td>
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
                                            <td class="px-6 py-4 text-sm text-gray-900">{{ $loop->iteration }}</td>
                                            <td class="px-6 py-4">
                                                <div class="text-sm font-medium text-gray-900">
                                                    {{ $item->service->name ?? 'Service not found' }}</div>
                                            </td>
                                            <td class="px-6 py-4 text-sm text-gray-900">
                                                {{ number_format($item->quantity) }}</td>
                                            <td class="px-6 py-4 text-sm text-gray-900">
                                                ₹{{ number_format($item->unit_price, 2) }}</td>
                                            <td class="px-6 py-4 text-sm text-gray-900">
                                                @if ($item->discount_amount > 0)
                                                    <span
                                                        class="text-red-600">₹{{ number_format($item->discount_amount, 2) }}</span>
                                                @else
                                                    ₹0.00
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 text-sm text-gray-900">
                                                @php
                                                    $calculatedTaxableAmount =
                                                        $item->taxable_amount ??
                                                        $item->quantity * $item->unit_price -
                                                            ($item->discount_amount ?? 0);
                                                @endphp
                                                ₹{{ number_format($calculatedTaxableAmount, 2) }}</td>
                                            <td class="px-6 py-4 text-sm text-gray-900">
                                                ₹{{ number_format($item->cgst, 2) }}</td>
                                            <td class="px-6 py-4 text-sm text-gray-900">
                                                ₹{{ number_format($item->sgst, 2) }}</td>
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
                <div class="space-y-6">
                    <!-- Product Summary -->
                    @if ($invoice->items->where('type', 'product')->count() > 0)
                        <div class="bg-white border border-gray-200 rounded-lg p-6">
                            <h3 class="text-lg font-semibold text-blue-600 mb-4">
                                <i class="fas fa-box mr-2"></i>
                                Product Summary
                            </h3>
                            @php
                                $productItems = $invoice->items->where('type', 'product');
                                $productSubtotal = $productItems->sum(function ($item) {
                                    return $item->quantity * $item->unit_price;
                                });
                                $productDiscount = $productItems->sum('discount_amount');
                                $productTaxableAmount = $productItems->sum(function ($item) {
                                    return $item->taxable_amount ??
                                        $item->quantity * $item->unit_price - ($item->discount_amount ?? 0);
                                });
                                $productCgst = $productItems->sum('cgst');
                                $productSgst = $productItems->sum('sgst');
                                $productIgst = $productItems->sum('igst');
                                $productTotal = $productItems->sum('total');
                            @endphp
                            <div class="space-y-3">
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Product Subtotal:</span>
                                    <span class="font-medium">₹{{ number_format($productSubtotal, 2) }}</span>
                                </div>
                                @if ($productDiscount > 0)
                                    <div class="flex justify-between">
                                        <span class="text-red-600">Product Discount:</span>
                                        <span
                                            class="font-medium text-red-600">-₹{{ number_format($productDiscount, 2) }}</span>
                                    </div>
                                @endif
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Product Taxable Amount:</span>
                                    <span class="font-medium">₹{{ number_format($productTaxableAmount, 2) }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">CGST:</span>
                                    <span class="font-medium">₹{{ number_format($productCgst, 2) }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">SGST:</span>
                                    <span class="font-medium">₹{{ number_format($productSgst, 2) }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">IGST:</span>
                                    <span class="font-medium">₹{{ number_format($productIgst, 2) }}</span>
                                </div>
                                <div class="border-t border-gray-200 pt-3 flex justify-between text-lg font-bold">
                                    <span class="text-gray-900">Product Total:</span>
                                    <span class="text-blue-600">₹{{ number_format($productTotal, 2) }}</span>
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
                            @php
                                $serviceItems = $invoice->items->where('type', 'service');
                                $serviceSubtotal = $serviceItems->sum(function ($item) {
                                    return $item->quantity * $item->unit_price;
                                });
                                $serviceDiscount = $serviceItems->sum('discount_amount');
                                $serviceTaxableAmount = $serviceItems->sum(function ($item) {
                                    return $item->taxable_amount ??
                                        $item->quantity * $item->unit_price - ($item->discount_amount ?? 0);
                                });
                                $serviceCgst = $serviceItems->sum('cgst');
                                $serviceSgst = $serviceItems->sum('sgst');
                                $serviceIgst = $serviceItems->sum('igst');
                                $serviceTotal = $serviceItems->sum('total');
                            @endphp
                            <div class="space-y-3">
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Service Subtotal:</span>
                                    <span class="font-medium">₹{{ number_format($serviceSubtotal, 2) }}</span>
                                </div>
                                @if ($serviceDiscount > 0)
                                    <div class="flex justify-between">
                                        <span class="text-red-600">Service Discount:</span>
                                        <span
                                            class="font-medium text-red-600">-₹{{ number_format($serviceDiscount, 2) }}</span>
                                    </div>
                                @endif
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Service Taxable Amount:</span>
                                    <span class="font-medium">₹{{ number_format($serviceTaxableAmount, 2) }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">CGST:</span>
                                    <span class="font-medium">₹{{ number_format($serviceCgst, 2) }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">SGST:</span>
                                    <span class="font-medium">₹{{ number_format($serviceSgst, 2) }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">IGST:</span>
                                    <span class="font-medium">₹{{ number_format($serviceIgst, 2) }}</span>
                                </div>
                                <div class="border-t border-gray-200 pt-3 flex justify-between text-lg font-bold">
                                    <span class="text-gray-900">Service Total:</span>
                                    <span class="text-green-600">₹{{ number_format($serviceTotal, 2) }}</span>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Grand Total -->
                    <div class="bg-white border border-gray-200 rounded-lg p-6">
                        <h3 class="text-lg font-semibold text-yellow-600 mb-4">
                            <i class="fas fa-calculator mr-2"></i>
                            Grand Total
                        </h3>
                        <div class="space-y-4">
                            @php
                                $allItems = $invoice->items;
                                $grandSubtotal = $allItems->sum(function ($item) {
                                    return $item->quantity * $item->unit_price;
                                });
                                $totalDiscount = $allItems->sum('discount_amount');
                                $grandTaxableAmount = $allItems->sum(function ($item) {
                                    return $item->taxable_amount ??
                                        $item->quantity * $item->unit_price - ($item->discount_amount ?? 0);
                                });
                                $totalGst = $invoice->cgst + $invoice->sgst + $invoice->igst;
                                $courierCharges = $invoice->courier_charges ?? 0;
                                $finalTotal = $invoice->grand_total ?? $invoice->total;
                            @endphp
                            <div class="flex justify-between text-lg">
                                <span class="text-gray-700">Grand Subtotal:</span>
                                <span class="font-semibold">₹{{ number_format($grandSubtotal, 2) }}</span>
                            </div>
                            @if ($totalDiscount > 0)
                                <div class="flex justify-between text-lg">
                                    <span class="text-red-600">Total Discount:</span>
                                    <span
                                        class="font-semibold text-red-600">-₹{{ number_format($totalDiscount, 2) }}</span>
                                </div>
                            @endif
                            <div class="flex justify-between text-lg">
                                <span class="text-gray-700">Taxable Amount:</span>
                                <span class="font-semibold">₹{{ number_format($grandTaxableAmount, 2) }}</span>
                            </div>
                            <div class="flex justify-between text-lg">
                                <span class="text-gray-700">Total GST:</span>
                                <span class="font-semibold">₹{{ number_format($totalGst, 2) }}</span>
                            </div>
                            <div class="flex justify-between text-lg">
                                <span class="text-gray-700">Subtotal + GST:</span>
                                <span class="font-semibold">₹{{ number_format($invoice->total, 2) }}</span>
                            </div>
                            @if ($courierCharges > 0)
                                <div class="flex justify-between text-lg">
                                    <span class="text-gray-700">Courier Charges:</span>
                                    <span class="font-semibold">₹{{ number_format($courierCharges, 2) }}</span>
                                </div>
                            @endif
                            <div class="border-t border-gray-300 pt-4 flex justify-between text-2xl font-bold">
                                <span class="text-gray-900">Final Total:</span>
                                <span class="text-yellow-600">₹{{ number_format($finalTotal, 2) }}</span>
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
                                <button @click="printInvoice()"
                                    class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm rounded-lg transition-colors">
                                    <i class="fas fa-print mr-2"></i>Print
                                </button>
                                <a href="{{ route('invoices.pdf', $invoice->id) }}" target="_blank"
                                    class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm rounded-lg transition-colors">
                                    <i class="fas fa-download mr-2"></i>Download PDF
                                </a>
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
                            <p class="text-gray-600">GST: 29XXXXXXXXXXXXX</p>
                            <div class="mt-4 pt-4 border-t border-gray-300">
                                <h2 class="text-2xl font-semibold text-gray-800">TAX INVOICE</h2>
                            </div>
                        </div>

                        <!-- Invoice Info -->
                        <div class="grid grid-cols-2 gap-8 mb-8">
                            <div>
                                <h3 class="font-semibold text-gray-900 mb-3">Bill To:</h3>
                                <div class="text-gray-700">
                                    <p class="font-medium">{{ $invoice->customer->company_name ?? 'N/A' }}</p>
                                    @if ($contactPerson)
                                        <p>{{ $contactPerson->name }}</p>
                                        <p>{{ $contactPerson->phone_no ?? 'N/A' }}</p>
                                        <p>{{ $contactPerson->email ?? 'N/A' }}</p>
                                    @endif
                                    <p>{{ $invoice->customer->address ?? 'N/A' }}</p>
                                    @if ($invoice->customer->gst_number)
                                        <p>GST: {{ $invoice->customer->gst_number }}</p>
                                    @endif
                                </div>
                            </div>
                            <div class="text-right">
                                <div class="space-y-2">
                                    <div>
                                        <span class="font-medium">Invoice #:</span>
                                        <span>{{ $invoice->invoice_no }}</span>
                                    </div>
                                    <div>
                                        <span class="font-medium">Order #:</span>
                                        <span>{{ $invoice->order_no ?? 'N/A' }}</span>
                                    </div>
                                    <div>
                                        <span class="font-medium">Invoice Date:</span>
                                        <span>{{ \Carbon\Carbon::parse($invoice->invoice_date)->format('F d, Y') }}</span>
                                    </div>
                                    <div>
                                        <span class="font-medium">Order Date:</span>
                                        <span>{{ \Carbon\Carbon::parse($invoice->order_date)->format('F d, Y') }}</span>
                                    </div>
                                    <div>
                                        <span class="font-medium">Due Date:</span>
                                        <span>{{ \Carbon\Carbon::parse($invoice->invoice_date)->addDays(30)->format('F d, Y') }}</span>
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
                                        <th class="border border-gray-300 px-4 py-2 text-right">CGST</th>
                                        <th class="border border-gray-300 px-4 py-2 text-right">SGST</th>
                                        <th class="border border-gray-300 px-4 py-2 text-right">IGST</th>
                                        <th class="border border-gray-300 px-4 py-2 text-right">Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($invoice->items as $item)
                                        <tr>
                                            <td class="border border-gray-300 px-4 py-2">
                                                <div class="font-medium">
                                                    {{ $item->type === 'product' ? $item->product->name ?? 'Product not found' : $item->service->name ?? 'Service not found' }}
                                                </div>
                                            </td>
                                            <td class="border border-gray-300 px-4 py-2 text-center">
                                                {{ number_format($item->quantity) }}</td>
                                            <td class="border border-gray-300 px-4 py-2 text-right">
                                                ₹{{ number_format($item->unit_price, 2) }}</td>
                                            <td class="border border-gray-300 px-4 py-2 text-right">
                                                ₹{{ number_format($item->cgst, 2) }}</td>
                                            <td class="border border-gray-300 px-4 py-2 text-right">
                                                ₹{{ number_format($item->sgst, 2) }}</td>
                                            <td class="border border-gray-300 px-4 py-2 text-right">
                                                ₹{{ number_format($item->igst, 2) }}</td>
                                            <td class="border border-gray-300 px-4 py-2 text-right font-medium">
                                                ₹{{ number_format($item->total, 2) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr class="bg-gray-50">
                                        <td colspan="6"
                                            class="border border-gray-300 px-4 py-2 text-right font-bold">Grand Total:
                                        </td>
                                        <td class="border border-gray-300 px-4 py-2 text-right font-bold text-lg">
                                            ₹{{ number_format($invoice->total, 2) }}</td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>

                        <!-- Footer -->
                        <div class="text-center text-gray-600 text-sm border-t border-gray-300 pt-4">
                            <p>Thank you for your business!</p>
                            <p class="mt-2">This is a computer generated invoice and does not require signature.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function invoiceShowManager() {
            return {
                activeTab: 'overview',

                init() {
                    console.log('Invoice show page initialized');
                    this.bindKeyboardEvents();
                },

                bindKeyboardEvents() {
                    document.addEventListener('keydown', (e) => {
                        if (e.ctrlKey && e.key === 'p') {
                            e.preventDefault();
                            this.printInvoice();
                        }
                    });
                },

                printInvoice() {
                    window.print();
                },

                generateDeliveryChallan() {
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
                                    invoice_id: {{ $invoice->id }}
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

                deleteInvoice() {
                    if (confirm('Are you sure you want to delete this invoice? This action cannot be undone.')) {
                        // Create form and submit
                        const form = document.createElement('form');
                        form.method = 'POST';
                        form.action = '{{ route('invoices.destroy', $invoice->id) }}';

                        const csrfToken = document.createElement('input');
                        csrfToken.type = 'hidden';
                        csrfToken.name = '_token';
                        csrfToken.value = '{{ csrf_token() }}';
                        form.appendChild(csrfToken);

                        const methodField = document.createElement('input');
                        methodField.type = 'hidden';
                        methodField.name = '_method';
                        methodField.value = 'DELETE';
                        form.appendChild(methodField);

                        document.body.appendChild(form);
                        form.submit();
                    }
                },

                showToast(type, message) {
                    const existingToasts = document.querySelectorAll('.toast-notification');
                    existingToasts.forEach(toast => toast.remove());

                    const toastDiv = document.createElement('div');
                    toastDiv.className =
                        `toast-notification fixed top-4 right-4 p-4 rounded-lg shadow-lg z-50 max-w-sm transition-all duration-300 transform translate-x-0`;

                    let bgColor, textColor, icon;
                    switch (type) {
                        case 'success':
                            bgColor = 'bg-green-100 border border-green-200';
                            textColor = 'text-green-800';
                            icon = 'fa-check-circle';
                            break;
                        case 'error':
                            bgColor = 'bg-red-100 border border-red-200';
                            textColor = 'text-red-800';
                            icon = 'fa-exclamation-circle';
                            break;
                        case 'info':
                            bgColor = 'bg-blue-100 border border-blue-200';
                            textColor = 'text-blue-800';
                            icon = 'fa-info-circle';
                            break;
                        default:
                            bgColor = 'bg-gray-100 border border-gray-200';
                            textColor = 'text-gray-800';
                            icon = 'fa-bell';
                    }

                    toastDiv.className += ` ${bgColor} ${textColor}`;
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

                    document.body.appendChild(toastDiv);

                    if (type !== 'info') {
                        setTimeout(() => {
                            if (toastDiv.parentNode) {
                                toastDiv.remove();
                            }
                        }, 5000);
                    }
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
