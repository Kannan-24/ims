<x-app-layout>
    <x-slot name="title">
        {{ __('Invoice Details') }} - {{ config('app.name', 'SKM') }}
    </x-slot>

    <div class="bg-white min-h-screen" x-data="{ activeTab: 'overview' }">
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
                            <span class="text-sm font-medium text-gray-500">Invoice Details</span>
                        </div>
                    </li>
                </ol>
            </nav>
        </div>

        <!-- Header -->
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Invoice Details</h1>
                    <p class="text-sm text-gray-600 mt-1">View and manage invoice information</p>
                </div>
                <div class="flex items-center space-x-3">
                    <a href="{{ route('invoices.pdf', $invoice->id) }}" target="_blank"
                       class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors">
                        <i class="fas fa-file-pdf w-4 h-4 mr-2"></i>
                        PDF
                    </a>
                    <a href="{{ route('invoices.qr-view', $invoice->id) }}" target="_blank"
                       class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg transition-colors">
                        <i class="fas fa-qrcode w-4 h-4 mr-2"></i>
                        QR View
                    </a>
                    <button onclick="generateDeliveryChallan({{ $invoice->id }})"
                       class="inline-flex items-center px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white text-sm font-medium rounded-lg transition-colors">
                        <i class="fas fa-truck w-4 h-4 mr-2"></i>
                        Delivery Challan
                    </button>
                    <a href="{{ route('emails.create') }}?invoice_id={{ $invoice->id }}"
                       class="inline-flex items-center px-4 py-2 bg-orange-600 hover:bg-orange-700 text-white text-sm font-medium rounded-lg transition-colors">
                        <i class="fas fa-envelope w-4 h-4 mr-2"></i>
                        Email Invoice
                    </a>
                    <a href="{{ route('invoices.edit', $invoice->id) }}"
                       class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-lg transition-colors">
                        <i class="fas fa-edit w-4 h-4 mr-2"></i>
                        Edit
                    </a>
                    <form action="{{ route('invoices.destroy', $invoice->id) }}" method="POST" class="inline"
                          onsubmit="return confirm('Are you sure you want to delete this invoice?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                                class="inline-flex items-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg transition-colors">
                            <i class="fas fa-trash w-4 h-4 mr-2"></i>
                            Delete
                        </button>
                    </form>
                </div>
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
                            <p class="text-lg font-semibold text-gray-900">{{ $invoice->invoice_date }}</p>
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
                            <i class="fas fa-calendar-alt text-orange-600"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Order Date</p>
                            <p class="text-lg font-semibold text-gray-900">{{ $invoice->order_date }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tab Navigation -->
        <div class="px-6 border-b border-gray-200">
            <nav class="-mb-px flex space-x-8">
                <button @click="activeTab = 'overview'"
                        :class="activeTab === 'overview' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                        class="py-4 px-1 border-b-2 font-medium text-sm">
                    <i class="fas fa-info-circle mr-2"></i>
                    Overview
                </button>
                <button @click="activeTab = 'items'"
                        :class="activeTab === 'items' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                        class="py-4 px-1 border-b-2 font-medium text-sm">
                    <i class="fas fa-boxes mr-2"></i>
                    Items
                </button>
                <button @click="activeTab = 'summary'"
                        :class="activeTab === 'summary' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                        class="py-4 px-1 border-b-2 font-medium text-sm">
                    <i class="fas fa-calculator mr-2"></i>
                    Summary
                </button>
            </nav>
        </div>

        <!-- Tab Content -->
        <div class="p-6">
            <!-- Overview Tab -->
            <div x-show="activeTab === 'overview'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
                <!-- Customer Information -->
                <div class="bg-white border border-gray-200 rounded-lg shadow-sm overflow-hidden mb-6">
                    <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-800 flex items-center">
                            <i class="fas fa-building text-blue-600 mr-2"></i>
                            Customer Details
                        </h3>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-3">
                                <div>
                                    <p class="text-sm font-medium text-gray-600">Company Name</p>
                                    <p class="text-gray-900">{{ $invoice->customer->company_name }}</p>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-600">GST Number</p>
                                    <p class="text-gray-900">{{ $invoice->customer->gst_number ?? 'N/A' }}</p>
                                </div>
                            </div>
                            <div class="space-y-3">
                                <div>
                                    <p class="text-sm font-medium text-gray-600">Address</p>
                                    <p class="text-gray-900">
                                        {{ $invoice->customer->address }},
                                        {{ $invoice->customer->city }} - {{ $invoice->customer->zip_code }},
                                        {{ $invoice->customer->state }}, {{ $invoice->customer->country }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Contact Person -->
                <div class="bg-white border border-gray-200 rounded-lg shadow-sm overflow-hidden">
                    <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-800 flex items-center">
                            <i class="fas fa-user text-green-600 mr-2"></i>
                            Contact Person Details
                        </h3>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @foreach ($invoice->customer->contactPersons as $contactPerson)
                                @if ($contactPerson->id == $invoice->contactperson_id)
                                    <div class="p-4 bg-white rounded-lg border border-gray-200">
                                        <p class="text-lg font-semibold text-gray-900">{{ $contactPerson->name }}</p>
                                        <p class="text-sm text-gray-600 mt-1"><strong>Phone:</strong> {{ $contactPerson->phone_no }}</p>
                                        <p class="text-sm text-gray-600"><strong>Email:</strong> {{ $contactPerson->email ?? 'N/A' }}</p>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <!-- Items Tab -->
            <div x-show="activeTab === 'items'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
                @if ($invoice->items->where('type', 'product')->isNotEmpty())
                    <div class="bg-white border border-gray-200 rounded-lg shadow-sm overflow-hidden mb-6">
                        <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                            <h3 class="text-lg font-semibold text-gray-800 flex items-center">
                                <i class="fas fa-box text-blue-600 mr-2"></i>
                                Products
                            </h3>
                        </div>
                        <div class="p-6 overflow-x-auto">
                            <table class="min-w-full bg-white border border-gray-200 rounded-lg">
                                <thead class="bg-gray-50 text-gray-700">
                                    <tr>
                                        <th class="px-6 py-3 text-left font-medium">#</th>
                                        <th class="px-6 py-3 text-left font-medium">Product Name</th>
                                        <th class="px-6 py-3 text-left font-medium">Quantity</th>
                                        <th class="px-6 py-3 text-left font-medium">Unit Type</th>
                                        <th class="px-6 py-3 text-left font-medium">Unit Price</th>
                                        <th class="px-6 py-3 text-left font-medium">CGST</th>
                                        <th class="px-6 py-3 text-left font-medium">SGST</th>
                                        <th class="px-6 py-3 text-left font-medium">IGST</th>
                                        <th class="px-6 py-3 text-left font-medium">Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($invoice->items->where('type', 'product') as $product)
                                        <tr class="border-t border-gray-200 hover:bg-gray-50 text-gray-900">
                                            <td class="px-6 py-3">{{ $loop->iteration }}</td>
                                            <td class="px-6 py-3 font-medium">{{ $product->product->name }}</td>
                                            <td class="px-6 py-3">{{ $product->quantity }}</td>
                                            <td class="px-6 py-3">{{ $product->unit_type }}</td>
                                            <td class="px-6 py-3">₹{{ number_format($product->unit_price, 2) }}</td>
                                            <td class="px-6 py-3">₹{{ number_format($product->cgst, 2) }}</td>
                                            <td class="px-6 py-3">₹{{ number_format($product->sgst, 2) }}</td>
                                            <td class="px-6 py-3">₹{{ number_format($product->igst, 2) }}</td>
                                            <td class="px-6 py-3 font-semibold">₹{{ number_format($product->total, 2) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endif

                @if ($invoice->items->where('type', 'service')->isNotEmpty())
                    <div class="bg-white border border-gray-200 rounded-lg shadow-sm overflow-hidden">
                        <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                            <h3 class="text-lg font-semibold text-gray-800 flex items-center">
                                <i class="fas fa-cogs text-green-600 mr-2"></i>
                                Services
                            </h3>
                        </div>
                        <div class="p-6 overflow-x-auto">
                            <table class="min-w-full bg-white border border-gray-200 rounded-lg">
                                <thead class="bg-gray-50 text-gray-700">
                                    <tr>
                                        <th class="px-6 py-3 text-left font-medium">#</th>
                                        <th class="px-6 py-3 text-left font-medium">Service Name</th>
                                        <th class="px-6 py-3 text-left font-medium">Quantity</th>
                                        <th class="px-6 py-3 text-left font-medium">Unit Price</th>
                                        <th class="px-6 py-3 text-left font-medium">CGST</th>
                                        <th class="px-6 py-3 text-left font-medium">SGST</th>
                                        <th class="px-6 py-3 text-left font-medium">Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($invoice->items->where('type', 'service') as $service)
                                        <tr class="border-t border-gray-200 hover:bg-gray-50 text-gray-900">
                                            <td class="px-6 py-3">{{ $loop->iteration }}</td>
                                            <td class="px-6 py-3 font-medium">{{ $service->service->name }}</td>
                                            <td class="px-6 py-3">{{ $service->quantity }}</td>
                                            <td class="px-6 py-3">₹{{ number_format($service->unit_price, 2) }}</td>
                                            <td class="px-6 py-3">₹{{ number_format($service->cgst, 2) }}</td>
                                            <td class="px-6 py-3">₹{{ number_format($service->sgst, 2) }}</td>
                                            <td class="px-6 py-3 font-semibold">₹{{ number_format($service->total, 2) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endif
            </div>

            {{-- summary --}}
            <div x-show="activeTab === 'summary'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
                <div class="bg-white border border-gray-200 rounded-lg shadow-sm overflow-hidden">
                    <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-800 flex items-center">
                            <i class="fas fa-calculator text-gray-600 mr-2"></i>
                            Product and Service Summary
                        </h3>
                    </div>
                    <div class="p-6 bg-white">
                        <div class="space-y-6">
                            <!-- Product Summary -->
                            @if ($invoice->items->where('type', 'product')->isNotEmpty())
                                <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                                    <h4 class="text-md font-semibold text-gray-800 mb-3 flex items-center">
                                        <i class="fas fa-box text-gray-600 mr-2"></i>
                                        Product Summary
                                    </h4>
                                    @php
                                        $productItems = $invoice->items->where('type', 'product');
                                        $productSubtotal = $productItems->sum(function($item) {
                                            return $item->quantity * $item->unit_price;
                                        });
                                        $productCgst = $productItems->sum('cgst');
                                        $productSgst = $productItems->sum('sgst');
                                        $productIgst = $productItems->sum('igst');
                                        $productTotal = $productItems->sum('total');
                                    @endphp
                                    <table class="w-full">
                                        <tbody class="text-gray-700">
                                            <tr class="border-b border-gray-200">
                                                <td class="py-2 font-medium">Product Subtotal:</td>
                                                <td class="py-2 text-right">₹{{ number_format($productSubtotal, 2) }}</td>
                                            </tr>
                                            <tr class="border-b border-gray-200">
                                                <td class="py-2 font-medium">CGST:</td>
                                                <td class="py-2 text-right">₹{{ number_format($productCgst, 2) }}</td>
                                            </tr>
                                            <tr class="border-b border-gray-200">
                                                <td class="py-2 font-medium">SGST:</td>
                                                <td class="py-2 text-right">₹{{ number_format($productSgst, 2) }}</td>
                                            </tr>
                                            <tr class="border-b border-gray-200">
                                                <td class="py-2 font-medium">IGST:</td>
                                                <td class="py-2 text-right">₹{{ number_format($productIgst, 2) }}</td>
                                            </tr>
                                            <tr class="border-t-2 border-gray-400 bg-gray-100">
                                                <td class="py-3 font-bold text-lg text-gray-900">Product Total:</td>
                                                <td class="py-3 font-bold text-lg text-gray-900 text-right">₹{{ number_format($productTotal, 2) }}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            @endif

                            <!-- Service Summary -->
                            @if ($invoice->items->where('type', 'service')->isNotEmpty())
                                <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                                    <h4 class="text-md font-semibold text-gray-800 mb-3 flex items-center">
                                        <i class="fas fa-cogs text-gray-600 mr-2"></i>
                                        Service Summary
                                    </h4>
                                    @php
                                        $serviceItems = $invoice->items->where('type', 'service');
                                        $serviceSubtotal = $serviceItems->sum(function($item) {
                                            return $item->quantity * $item->unit_price;
                                        });
                                        $serviceCgst = $serviceItems->sum('cgst');
                                        $serviceSgst = $serviceItems->sum('sgst');
                                        $serviceIgst = $serviceItems->sum('igst');
                                        $serviceTotal = $serviceItems->sum('total');
                                    @endphp
                                    <table class="w-full">
                                        <tbody class="text-gray-700">
                                            <tr class="border-b border-gray-200">
                                                <td class="py-2 font-medium">Service Subtotal:</td>
                                                <td class="py-2 text-right">₹{{ number_format($serviceSubtotal, 2) }}</td>
                                            </tr>
                                            <tr class="border-b border-gray-200">
                                                <td class="py-2 font-medium">CGST:</td>
                                                <td class="py-2 text-right">₹{{ number_format($serviceCgst, 2) }}</td>
                                            </tr>
                                            <tr class="border-b border-gray-200">
                                                <td class="py-2 font-medium">SGST:</td>
                                                <td class="py-2 text-right">₹{{ number_format($serviceSgst, 2) }}</td>
                                            </tr>
                                            <tr class="border-b border-gray-200">
                                                <td class="py-2 font-medium">IGST:</td>
                                                <td class="py-2 text-right">₹{{ number_format($serviceIgst, 2) }}</td>
                                            </tr>
                                            <tr class="border-t-2 border-gray-400 bg-gray-100">
                                                <td class="py-3 font-bold text-lg text-gray-900">Service Total:</td>
                                                <td class="py-3 font-bold text-lg text-gray-900 text-right">₹{{ number_format($serviceTotal, 2) }}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            @endif

                            <!-- Combined Summary -->
                            <div class="bg-gray-50 rounded-lg p-4 border border-gray-300">
                                <h4 class="text-md font-semibold text-gray-800 mb-3 flex items-center">
                                    <i class="fas fa-calculator text-gray-600 mr-2"></i>
                                    Combined Summary
                                </h4>
                                <table class="w-full">
                                    <tbody class="text-gray-700">
                                        <tr class="border-b border-gray-200">
                                            <td class="py-2 font-medium">Total Subtotal:</td>
                                            <td class="py-2 text-right">₹{{ number_format($invoice->sub_total, 2) }}</td>
                                        </tr>
                                        <tr class="border-b border-gray-200">
                                            <td class="py-2 font-medium">Total CGST:</td>
                                            <td class="py-2 text-right">₹{{ number_format($invoice->cgst, 2) }}</td>
                                        </tr>
                                        <tr class="border-b border-gray-200">
                                            <td class="py-2 font-medium">Total SGST:</td>
                                            <td class="py-2 text-right">₹{{ number_format($invoice->sgst, 2) }}</td>
                                        </tr>
                                        <tr class="border-b border-gray-200">
                                            <td class="py-2 font-medium">Total IGST:</td>
                                            <td class="py-2 text-right">₹{{ number_format($invoice->igst, 2) }}</td>
                                        </tr>
                                        <tr class="border-t-4 border-gray-500 bg-gray-200">
                                            <td class="py-4 font-bold text-xl text-gray-900">Grand Total:</td>
                                            <td class="py-4 font-bold text-xl text-gray-900 text-right">₹{{ number_format($invoice->total, 2) }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <script>
            function generateDeliveryChallan(invoiceId) {
                if (confirm('Generate delivery challan for this invoice?')) {
                    showToast('info', 'Generating delivery challan...');

                    fetch('/ims/delivery-challans/generate', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({ invoice_id: invoiceId })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            showToast('success', data.message);
                            if (confirm('Would you like to view the generated delivery challan?')) {
                                window.open(data.pdf_url, '_blank');
                            }
                        } else {
                            showToast('error', data.message || 'Failed to generate delivery challan');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        showToast('error', 'An error occurred while generating delivery challan');
                    });
                }
            }

            function showToast(type, message) {
                const existingToasts = document.querySelectorAll('.toast-notification');
                existingToasts.forEach(toast => toast.remove());

                const toastDiv = document.createElement('div');
                toastDiv.className = `toast-notification fixed top-4 right-4 p-4 rounded-lg shadow-lg z-50 max-w-sm transition-all duration-300 transform translate-x-0`;

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
        </script>
    </div>
</x-app-layout>

