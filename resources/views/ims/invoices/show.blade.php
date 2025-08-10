<x-app-layout>
    <x-slot name="title">
        {{ __('Invoice Details') }} - {{ config('app.name', 'SKM') }}
    </x-slot>

    <div class="py-6 mt-20 ml-4 sm:ml-64">
        <div class="w-full mx-auto max-w-7xl sm:px-6 lg:px-8">
            <x-bread-crumb-navigation />

            <div class="p-8 bg-gray-800 border border-gray-700 rounded-lg shadow-lg relative">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-3xl font-bold text-gray-200">Invoice Details</h2>
                    <div class="flex gap-2">
                        <a href="{{ route('invoices.pdf', $invoice->id) }}" target="_blank"
                            class="flex items-center px-4 py-2 text-white bg-blue-500 rounded-lg hover:bg-blue-600 transition">
                            <i class="fas fa-file-pdf mr-2"></i>PDF
                        </a>
                        <a href="{{ route('invoices.qr-view', $invoice->id) }}" target="_blank"
                            class="flex items-center px-4 py-2 text-white bg-indigo-500 rounded-lg hover:bg-indigo-600 transition">
                            <i class="fas fa-qrcode mr-2"></i>QR View
                        </a>
                        <button onclick="generateDeliveryChallan({{ $invoice->id }})"
                            class="flex items-center px-4 py-2 text-white bg-purple-500 rounded-lg hover:bg-purple-600 transition">
                            <i class="fas fa-truck mr-2"></i>Delivery Challan
                        </button>
                        <a href="{{ route('invoices.edit', $invoice->id) }}"
                            class="flex items-center px-4 py-2 text-white bg-green-500 rounded-lg hover:bg-green-600 transition">
                            Edit
                        </a>
                        <form action="{{ route('invoices.destroy', $invoice->id) }}" method="POST"
                            onsubmit="return confirm('Are you sure you want to delete this invoice?');" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                class="flex items-center px-4 py-2 text-white bg-red-500 rounded-lg hover:bg-red-600 transition">
                                Delete
                            </button>
                        </form>
                    </div>
                </div>

                <hr class="my-6 border-gray-600">

                <div class="space-y-4 text-gray-300">
                    <p><strong>Order No:</strong> {{ $invoice->order_no ?? 'N/A' }}</p>
                    <p><strong>Invoice No:</strong> {{ $invoice->invoice_no }}</p>
                    <p><strong>Invoice Date:</strong> {{ $invoice->invoice_date }}</p>
                    <p><strong>Order Date:</strong> {{ $invoice->order_date }}</p>
                </div>

                <h3 class="text-2xl font-bold text-gray-200 mb-4 mt-8">Customer Details</h3>
                <div class="space-y-4 text-gray-300">
                    <p><strong>Name:</strong> {{ $invoice->customer->company_name }}</p>
                    <p><strong>GST Number:</strong> {{ $invoice->customer->gst_number ?? 'N/A' }}</p>
                    <p><strong>Address:</strong> {{ $invoice->customer->address }},
                        {{ $invoice->customer->city }} - {{ $invoice->customer->zip_code }},
                        {{ $invoice->customer->state }}, {{ $invoice->customer->country }}</p>
                        <h3 class="text-2xl font-bold text-gray-200 mb-4 mt-8">Contact Person Details</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @foreach ($invoice->customer->contactPersons as $contactPerson)
                                @if ($contactPerson->id == $invoice->contactperson_id)
                                    <div class="p-6 rounded-lg shadow-md bg-gray-700 border border-gray-600 text-gray-300 hover:bg-gray-600 transition">
                                        <p class="text-lg font-semibold">{{ $contactPerson->name }}</p>
                                        <p class="text-sm mt-1"><strong>Phone:</strong> {{ $contactPerson->phone_no }}</p>
                                        <p class="text-sm"><strong>Email:</strong> {{ $contactPerson->email ?? 'N/A' }}</p>
                                        <p><strong>Address:</strong> {{ $invoice->customer->address }},
                                            {{ $invoice->customer->city }} - {{ $invoice->customer->zip_code }},
                                            {{ $invoice->customer->state }}, {{ $invoice->customer->country }}</p>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                </div>

                <hr class="my-6 border-gray-600">

                @if ($invoice->items->where('type', 'product')->isNotEmpty())
                    <h3 class="text-2xl font-bold text-gray-200 mb-4">Products</h3>
                    <div class="overflow-x-auto mt-4">
                        <table class="min-w-full bg-gray-800 border border-gray-700 rounded-lg shadow-sm">
                            <thead class="bg-gray-700 text-gray-300">
                                <tr>
                                    <th class="px-6 py-4 text-left">#</th>
                                    <th class="px-6 py-4 text-left">Product Name</th>
                                    <th class="px-6 py-4 text-left">Quantity</th>
                                    <th class="px-6 py-4 text-left">Unit Type</th>
                                    <th class="px-6 py-4 text-left">Unit Price</th>
                                    <th class="px-6 py-4 text-left">CGST</th>
                                    <th class="px-6 py-4 text-left">SGST</th>
                                    <th class="px-6 py-4 text-left">IGST</th>
                                    <th class="px-6 py-4 text-left">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($invoice->items->where('type', 'product') as $product)
                                    <tr class="border-t border-gray-700 hover:bg-gray-700 text-gray-300">
                                        <td class="px-6 py-4">{{ $loop->iteration }}</td>
                                        <td class="px-6 py-4">{{ $product->product->name }}</td>
                                        <td class="px-6 py-4">{{ $product->quantity }}</td>
                                        <td class="px-6 py-4">{{ $product->unit_type }}</td>
                                        <td class="px-6 py-4">₹{{ number_format($product->unit_price, 2) }}</td>
                                        <td class="px-6 py-4">₹{{ number_format($product->cgst, 2) }}</td>
                                        <td class="px-6 py-4">₹{{ number_format($product->sgst, 2) }}</td>
                                        <td class="px-6 py-4">₹{{ number_format($product->igst, 2) }}</td>
                                        <td class="px-6 py-4">₹{{ number_format($product->total, 2) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif

                @if ($invoice->items->where('type', 'service')->isNotEmpty())
                    <h3 class="text-2xl font-bold text-gray-200 mb-4 mt-8">Services</h3>
                    <div class="overflow-x-auto mt-4">
                        <table class="min-w-full bg-gray-800 border border-gray-700 rounded-lg shadow-sm">
                            <thead class="bg-gray-700 text-gray-300">
                                <tr>
                                    <th class="px-6 py-4 text-left">#</th>
                                    <th class="px-6 py-4 text-left">Service Name</th>
                                    <th class="px-6 py-4 text-left">Quantity</th>
                                    <th class="px-6 py-4 text-left">Unit Price</th>
                                    <th class="px-6 py-4 text-left">CGST</th>
                                    <th class="px-6 py-4 text-left">SGST</th>
                                    <th class="px-6 py-4 text-left">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($invoice->items->where('type', 'service') as $service)
                                    <tr class="border-t border-gray-700 hover:bg-gray-700 text-gray-300">
                                        <td class="px-6 py-4">{{ $loop->iteration }}</td>
                                        <td class="px-6 py-4">{{ $service->service->name }}</td>
                                        <td class="px-6 py-4">{{ $service->quantity }}</td>
                                        <td class="px-6 py-4">₹{{ number_format($service->unit_price, 2) }}</td>
                                        <td class="px-6 py-4">₹{{ number_format($service->cgst, 2) }}</td>
                                        <td class="px-6 py-4">₹{{ number_format($service->sgst, 2) }}</td>
                                        <td class="px-6 py-4">₹{{ number_format($service->total, 2) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif

                <div class="mt-8">
                    <h3 class="text-2xl font-bold text-gray-200 mb-4">Summary</h3>
                    <table class="w-full bg-gray-800 border border-gray-700 rounded-lg shadow-sm text-gray-300">
                        <tbody>
                            <tr class="border-t border-gray-700">
                                <td class="px-4 py-2 font-semibold">Subtotal:</td>
                                <td class="px-4 py-2">₹{{ number_format($invoice->sub_total, 2) }}</td>
                            </tr>
                            <tr class="border-t border-gray-700">
                                <td class="px-4 py-2 font-semibold">CGST:</td>
                                <td class="px-4 py-2">₹{{ number_format($invoice->cgst, 2) }}</td>
                            </tr>
                            <tr class="border-t border-gray-700">
                                <td class="px-4 py-2 font-semibold">SGST:</td>
                                <td class="px-4 py-2">₹{{ number_format($invoice->sgst, 2) }}</td>
                            </tr>
                            <tr class="border-t border-gray-700">
                                <td class="px-4 py-2 font-semibold">IGST:</td>
                                <td class="px-4 py-2">₹{{ number_format($invoice->igst, 2) }}</td>
                            </tr>
                            <tr class="border-t border-gray-700">
                                <td class="px-4 py-2 font-bold text-xl">Grand Total:</td>
                                <td class="px-4 py-2 font-bold text-xl">₹{{ number_format($invoice->total, 2) }}</td>
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
        toastDiv.className = `toast-notification fixed top-4 right-4 p-4 rounded-lg shadow-lg z-50 max-w-sm transition-all duration-300 transform translate-x-0`;
        
        let bgColor, textColor, icon;
        switch(type) {
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
