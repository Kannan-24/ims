<x-app-layout>
    <x-slot name="title">
        {{ __('Invoice Details') }} - {{ config('app.name', 'SKM') }}
    </x-slot>

        <div class="py-6 mt-20 ml-4 sm:ml-64">
        <div class="w-full mx-auto max-w-7xl sm:px-6 lg:px-8">
            <!-- Breadcrumb Navigation -->
            <nav class="flex items-center space-x-2 text-sm text-gray-600 mb-6">
                <a href="{{ route('dashboard') }}" class="hover:text-blue-600 transition-colors">
                    <i class="fas fa-home"></i> Dashboard
                </a>
                <i class="fas fa-chevron-right text-gray-400"></i>
                <a href="{{ route('invoices.index') }}" class="hover:text-blue-600 transition-colors">
                    📄 Invoices
                </a>
                <i class="fas fa-chevron-right text-gray-400"></i>
                <span class="text-gray-800 font-medium">Invoice Details</span>
            </nav>

            <!-- Header Card -->
            <div class="bg-white rounded-lg shadow-md border border-gray-200 p-6 mb-6">
                <div class="flex justify-between items-center">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-800 flex items-center">
                            📄 Invoice Details
                        </h1>
                        <p class="text-gray-600 mt-1">View and manage invoice information</p>
                    </div>
                    <div class="flex gap-2 flex-wrap">
                        <a href="{{ route('invoices.pdf', $invoice->id) }}" target="_blank"
                            class="flex items-center px-4 py-2 text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition-colors shadow-md">
                            <i class="fas fa-file-pdf mr-2"></i>PDF
                        </a>
                        <a href="{{ route('invoices.qr-view', $invoice->id) }}" target="_blank"
                            class="flex items-center px-4 py-2 text-white bg-indigo-600 rounded-lg hover:bg-indigo-700 transition-colors shadow-md">
                            <i class="fas fa-qrcode mr-2"></i>QR View
                        </a>
                        <button onclick="generateDeliveryChallan({{ $invoice->id }})"
                            class="flex items-center px-4 py-2 text-white bg-purple-600 rounded-lg hover:bg-purple-700 transition-colors shadow-md">
                            <i class="fas fa-truck mr-2"></i>Delivery Challan
                        </button>
                        <a href="{{ route('emails.create') }}?invoice_id={{ $invoice->id }}"
                            class="flex items-center px-4 py-2 text-white bg-orange-600 rounded-lg hover:bg-orange-700 transition-colors shadow-md">
                            <i class="fas fa-envelope mr-2"></i>Email Invoice
                        </a>
                        <a href="{{ route('invoices.edit', $invoice->id) }}"
                            class="flex items-center px-4 py-2 text-white bg-green-600 rounded-lg hover:bg-green-700 transition-colors shadow-md">
                            <i class="fas fa-edit mr-2"></i>Edit
                        </a>
                        <form action="{{ route('invoices.destroy', $invoice->id) }}" method="POST"
                            onsubmit="return confirm('Are you sure you want to delete this invoice?');" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                class="flex items-center px-4 py-2 text-white bg-red-600 rounded-lg hover:bg-red-700 transition-colors shadow-md">
                                <i class="fas fa-trash mr-2"></i>Delete
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Invoice Overview Cards -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
                <div class="bg-white rounded-lg shadow-md border border-gray-200 p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
                                <i class="fas fa-hashtag text-blue-600"></i>
                            </div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Invoice No</p>
                            <p class="text-lg font-semibold text-gray-900">{{ $invoice->invoice_no }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow-md border border-gray-200 p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center">
                                <i class="fas fa-calendar text-green-600"></i>
                            </div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Invoice Date</p>
                            <p class="text-lg font-semibold text-gray-900">{{ $invoice->invoice_date }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow-md border border-gray-200 p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center">
                                <i class="fas fa-shopping-cart text-purple-600"></i>
                            </div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Order No</p>
                            <p class="text-lg font-semibold text-gray-900">{{ $invoice->order_no ?? 'N/A' }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow-md border border-gray-200 p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-orange-100 rounded-lg flex items-center justify-center">
                                <i class="fas fa-calendar-alt text-orange-600"></i>
                            </div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Order Date</p>
                            <p class="text-lg font-semibold text-gray-900">{{ $invoice->order_date }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Customer Information Card -->
            <div class="bg-white rounded-lg shadow-md border border-gray-200 p-6 mb-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                    <i class="fas fa-building text-blue-600 mr-2"></i>
                    Customer Details
                </h3>
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
                            <p class="text-gray-900">{{ $invoice->customer->address }}, {{ $invoice->customer->city }} - {{ $invoice->customer->zip_code }}, {{ $invoice->customer->state }}, {{ $invoice->customer->country }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Contact Person Card -->
            <div class="bg-white rounded-lg shadow-md border border-gray-200 p-6 mb-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                    <i class="fas fa-user text-green-600 mr-2"></i>
                    Contact Person Details
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach ($invoice->customer->contactPersons as $contactPerson)
                        @if ($contactPerson->id == $invoice->contactperson_id)
                            <div class="p-4 bg-gray-50 rounded-lg border border-gray-200">
                                <p class="text-lg font-semibold text-gray-900">{{ $contactPerson->name }}</p>
                                <p class="text-sm text-gray-600 mt-1"><strong>Phone:</strong> {{ $contactPerson->phone_no }}</p>
                                <p class="text-sm text-gray-600"><strong>Email:</strong> {{ $contactPerson->email ?? 'N/A' }}</p>
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>

            @if ($invoice->items->where('type', 'product')->isNotEmpty())
                <!-- Products Card -->
                <div class="bg-white rounded-lg shadow-md border border-gray-200 p-6 mb-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                        <i class="fas fa-box text-blue-600 mr-2"></i>
                        Products
                    </h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full bg-white border border-gray-200 rounded-lg shadow-sm">
                            <thead class="bg-gray-50 text-gray-700">
                                <tr>
                                    <th class="px-6 py-4 text-left font-medium">#</th>
                                    <th class="px-6 py-4 text-left font-medium">Product Name</th>
                                    <th class="px-6 py-4 text-left font-medium">Quantity</th>
                                    <th class="px-6 py-4 text-left font-medium">Unit Type</th>
                                    <th class="px-6 py-4 text-left font-medium">Unit Price</th>
                                    <th class="px-6 py-4 text-left font-medium">CGST</th>
                                    <th class="px-6 py-4 text-left font-medium">SGST</th>
                                    <th class="px-6 py-4 text-left font-medium">IGST</th>
                                    <th class="px-6 py-4 text-left font-medium">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($invoice->items->where('type', 'product') as $product)
                                    <tr class="border-t border-gray-200 hover:bg-gray-50 text-gray-900">
                                        <td class="px-6 py-4">{{ $loop->iteration }}</td>
                                        <td class="px-6 py-4 font-medium">{{ $product->product->name }}</td>
                                        <td class="px-6 py-4">{{ $product->quantity }}</td>
                                        <td class="px-6 py-4">{{ $product->unit_type }}</td>
                                        <td class="px-6 py-4">₹{{ number_format($product->unit_price, 2) }}</td>
                                        <td class="px-6 py-4">₹{{ number_format($product->cgst, 2) }}</td>
                                        <td class="px-6 py-4">₹{{ number_format($product->sgst, 2) }}</td>
                                        <td class="px-6 py-4">₹{{ number_format($product->igst, 2) }}</td>
                                        <td class="px-6 py-4 font-semibold">₹{{ number_format($product->total, 2) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif

            @if ($invoice->items->where('type', 'service')->isNotEmpty())
                <!-- Services Card -->
                <div class="bg-white rounded-lg shadow-md border border-gray-200 p-6 mb-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                        <i class="fas fa-cogs text-green-600 mr-2"></i>
                        Services
                    </h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full bg-white border border-gray-200 rounded-lg shadow-sm">
                            <thead class="bg-gray-50 text-gray-700">
                                <tr>
                                    <th class="px-6 py-4 text-left font-medium">#</th>
                                    <th class="px-6 py-4 text-left font-medium">Service Name</th>
                                    <th class="px-6 py-4 text-left font-medium">Quantity</th>
                                    <th class="px-6 py-4 text-left font-medium">Unit Price</th>
                                    <th class="px-6 py-4 text-left font-medium">CGST</th>
                                    <th class="px-6 py-4 text-left font-medium">SGST</th>
                                    <th class="px-6 py-4 text-left font-medium">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($invoice->items->where('type', 'service') as $service)
                                    <tr class="border-t border-gray-200 hover:bg-gray-50 text-gray-900">
                                        <td class="px-6 py-4">{{ $loop->iteration }}</td>
                                        <td class="px-6 py-4 font-medium">{{ $service->service->name }}</td>
                                        <td class="px-6 py-4">{{ $service->quantity }}</td>
                                        <td class="px-6 py-4">₹{{ number_format($service->unit_price, 2) }}</td>
                                        <td class="px-6 py-4">₹{{ number_format($service->cgst, 2) }}</td>
                                        <td class="px-6 py-4">₹{{ number_format($service->sgst, 2) }}</td>
                                        <td class="px-6 py-4 font-semibold">₹{{ number_format($service->total, 2) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif

            <!-- Invoice Summary Card -->
            <div class="bg-white rounded-lg shadow-md border border-gray-200 p-6 mb-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                    <i class="fas fa-calculator text-purple-600 mr-2"></i>
                    Invoice Summary
                </h3>
                <div class="bg-gray-50 rounded-lg p-4">
                    <table class="w-full">
                        <tbody class="text-gray-700">
                            <tr class="border-b border-gray-200">
                                <td class="py-3 font-medium">Subtotal:</td>
                                <td class="py-3 text-right">₹{{ number_format($invoice->sub_total, 2) }}</td>
                            </tr>
                            <tr class="border-b border-gray-200">
                                <td class="py-3 font-medium">CGST:</td>
                                <td class="py-3 text-right">₹{{ number_format($invoice->cgst, 2) }}</td>
                            </tr>
                            <tr class="border-b border-gray-200">
                                <td class="py-3 font-medium">SGST:</td>
                                <td class="py-3 text-right">₹{{ number_format($invoice->sgst, 2) }}</td>
                            </tr>
                            <tr class="border-b border-gray-200">
                                <td class="py-3 font-medium">IGST:</td>
                                <td class="py-3 text-right">₹{{ number_format($invoice->igst, 2) }}</td>
                            </tr>
                            <tr class="border-t-2 border-gray-300">
                                <td class="py-4 font-bold text-lg text-gray-900">Grand Total:</td>
                                <td class="py-4 font-bold text-lg text-gray-900 text-right">₹{{ number_format($invoice->total, 2) }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script>
        function generateDeliveryChallan(invoiceId) {
            if (confirm('Generate delivery challan for this invoice?')) {
                // Show loading state
                showToast('info', 'Generating delivery challan...');

                fetch('/ims/delivery-challans/generate', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({
                            invoice_id: invoiceId
                        })
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
            // Remove existing toasts
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

            // Auto-remove after 5 seconds (except for info messages which are manually removed)
            if (type !== 'info') {
                setTimeout(() => {
                    if (toastDiv.parentNode) {
                        toastDiv.remove();
                    }
                }, 5000);
            }
        }
    </script>
</x-app-layout>
