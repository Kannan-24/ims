<x-app-layout>
    <x-slot name="title">
        {{ __('Delivery Challan Details') }} - {{ config('app.name', 'SKM') }}
    </x-slot>

    <div class="py-6 mt-20 ml-4 sm:ml-64">
        <div class="w-full mx-auto max-w-7xl sm:px-6 lg:px-8">
            <x-bread-crumb-navigation />

            <div class="p-8 bg-gray-800 border border-gray-700 rounded-lg shadow-lg relative">
                <div class="flex justify-between items-center mb-6">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-200">Delivery Challan Details</h1>
                        <p class="text-gray-400 mt-1">{{ $deliveryChallan->dc_no }}</p>
                    </div>
                    <div class="flex space-x-3">
                        <a href="{{ route('delivery-challans.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition duration-200">
                            <i class="fas fa-arrow-left mr-2"></i>Back to List
                        </a>
                        <a href="{{ route('delivery-challans.pdf', $deliveryChallan->id) }}" target="_blank" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg transition duration-200">
                            <i class="fas fa-file-pdf mr-2"></i>View PDF
                        </a>
                        <a href="{{ route('delivery-challans.download', $deliveryChallan->id) }}" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg transition duration-200">
                            <i class="fas fa-download mr-2"></i>Download PDF
                        </a>
                    </div>
                </div>

                <hr class="my-6 border-gray-600">

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    <!-- Delivery Challan Information -->
                    <div class="bg-gray-700 rounded-lg p-6">
                        <h3 class="text-lg font-semibold text-gray-200 mb-4">Delivery Challan Information</h3>
                        <div class="space-y-3">
                            <div class="flex justify-between">
                                <span class="text-gray-400">DC Number:</span>
                                <span class="font-medium text-gray-200">{{ $deliveryChallan->dc_no }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-400">Delivery Date:</span>
                                <span class="font-medium text-gray-200">{{ \Carbon\Carbon::parse($deliveryChallan->delivery_date)->format('d M Y') }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-400">Status:</span>
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                    @if($deliveryChallan->status == 'delivered') bg-green-100 text-green-800
                                    @elseif($deliveryChallan->status == 'generated') bg-blue-100 text-blue-800
                                    @elseif($deliveryChallan->status == 'cancelled') bg-red-100 text-red-800
                                    @else bg-yellow-100 text-yellow-800 @endif">
                                    {{ ucfirst($deliveryChallan->status) }}
                                </span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-400">Generated At:</span>
                                <span class="font-medium text-gray-200">{{ \Carbon\Carbon::parse($deliveryChallan->generated_at)->format('d M Y H:i A') }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-400">Last Updated:</span>
                                <span class="font-medium text-gray-200">{{ \Carbon\Carbon::parse($deliveryChallan->updated_at)->format('d M Y H:i A') }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Invoice Information -->
                    <div class="bg-gray-700 rounded-lg p-6">
                        <h3 class="text-lg font-semibold text-gray-200 mb-4">Related Invoice</h3>
                        <div class="space-y-3">
                            <div class="flex justify-between">
                                <span class="text-gray-400">Invoice Number:</span>
                                <span class="font-medium text-gray-200">{{ $deliveryChallan->invoice->invoice_no }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-400">Invoice Date:</span>
                                <span class="font-medium text-gray-200">{{ \Carbon\Carbon::parse($deliveryChallan->invoice->created_at)->format('d M Y') }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-400">Total Amount:</span>
                                <span class="font-medium text-gray-200">₹{{ number_format($deliveryChallan->invoice->total_amount, 2) }}</span>
                            </div>
                            <div class="mt-4">
                                <a href="{{ route('invoices.show', $deliveryChallan->invoice->id) }}" class="text-blue-400 hover:text-blue-300 text-sm">
                                    <i class="fas fa-external-link-alt mr-1"></i>View Full Invoice
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Customer Information -->
                <div class="bg-gray-700 rounded-lg p-6 mt-8">
                    <h3 class="text-lg font-semibold text-gray-200 mb-4">Customer Information</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h4 class="font-medium text-gray-200 mb-2">Contact Details</h4>
                            <div class="space-y-2 text-sm">
                                <div class="text-gray-300"><strong class="text-gray-200">Name:</strong> {{ $deliveryChallan->invoice->customer->company_name ?? $deliveryChallan->invoice->customer->name }}</div>
                                @if($deliveryChallan->invoice->customer->phone)
                                    <div class="text-gray-300"><strong class="text-gray-200">Phone:</strong> {{ $deliveryChallan->invoice->customer->phone }}</div>
                                @endif
                                @if($deliveryChallan->invoice->customer->email)
                                    <div class="text-gray-300"><strong class="text-gray-200">Email:</strong> {{ $deliveryChallan->invoice->customer->email }}</div>
                                @endif
                                @if($deliveryChallan->invoice->customer->gst)
                                    <div class="text-gray-300"><strong class="text-gray-200">GST:</strong> {{ $deliveryChallan->invoice->customer->gst }}</div>
                                @endif
                            </div>
                        </div>
                        <div>
                            <h4 class="font-medium text-gray-200 mb-2">Address</h4>
                            <div class="text-sm text-gray-300">
                                {{ $deliveryChallan->invoice->customer->address }}<br>
                                @if($deliveryChallan->invoice->customer->city)
                                    {{ $deliveryChallan->invoice->customer->city }}
                                    @if($deliveryChallan->invoice->customer->postal_code)
                                        - {{ $deliveryChallan->invoice->customer->postal_code }}
                                    @endif
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Items Information -->
                <div class="bg-gray-700 rounded-lg p-6 mt-8">
                    <h3 class="text-lg font-semibold text-gray-200 mb-4">Products for Delivery</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full bg-gray-800 border border-gray-600">
                            <thead class="bg-gray-700">
                                <tr>
                                    <th class="px-6 py-3 border-b border-gray-600 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">S.No</th>
                                    <th class="px-6 py-3 border-b border-gray-600 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Product</th>
                                    <th class="px-6 py-3 border-b border-gray-600 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Description</th>
                                    <th class="px-6 py-3 border-b border-gray-600 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">HSN Code</th>
                                    <th class="px-6 py-3 border-b border-gray-600 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Quantity</th>
                                    <th class="px-6 py-3 border-b border-gray-600 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Unit</th>
                                    <th class="px-6 py-3 border-b border-gray-600 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Rate</th>
                                    <th class="px-6 py-3 border-b border-gray-600 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Amount</th>
                                </tr>
                            </thead>
                            <tbody class="bg-gray-800 divide-y divide-gray-600">
                                @php
                                    // Only show product items for delivery challan
                                    $productItems = $deliveryChallan->invoice->items->filter(function($item) {
                                        return $item->type === 'product' && 
                                               !is_null($item->product_id) && 
                                               !is_null($item->product);
                                    });
                                @endphp
                                
                                @if($productItems->count() > 0)
                                    @foreach($productItems as $item)
                                    <tr class="hover:bg-gray-700">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-200">{{ $loop->iteration }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-200">{{ $item->product->name ?? 'N/A' }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-400">{{ $item->product->description ?? '-' }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-400 text-center">{{ $item->product->hsn_code ?? '-' }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-200">{{ $item->quantity ?? 0 }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-400">{{ $item->unit_type ?? $item->product->unit ?? 'Nos' }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-200">₹{{ number_format($item->unit_price ?? 0, 2) }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-200">₹{{ number_format($item->total ?? ($item->quantity * $item->unit_price), 2) }}</td>
                                    </tr>
                                    @endforeach
                                @else
                                    <tr class="hover:bg-gray-700">
                                        <td colspan="8" class="px-6 py-4 text-center text-gray-400">No products available for delivery</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Status Update Section -->
                <div class="bg-yellow-800 border border-yellow-600 rounded-lg p-6 mt-8">
                    <h3 class="text-lg font-semibold text-gray-200 mb-4">Update Status</h3>
                    <div class="flex items-center space-x-4">
                        <label for="status-select" class="text-sm font-medium text-gray-200">Current Status:</label>
                        <select id="status-select" class="bg-gray-600 border-gray-500 text-gray-200 rounded-md px-3 py-2 text-sm focus:border-blue-500 focus:ring-blue-500" data-challan-id="{{ $deliveryChallan->id }}">
                            <option value="pending" {{ $deliveryChallan->status == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="generated" {{ $deliveryChallan->status == 'generated' ? 'selected' : '' }}>Generated</option>
                            <option value="delivered" {{ $deliveryChallan->status == 'delivered' ? 'selected' : '' }}>Delivered</option>
                            <option value="cancelled" {{ $deliveryChallan->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        </select>
                        <button onclick="updateStatus()" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg text-sm transition duration-200">
                            Update Status
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
    function updateStatus() {
        const select = document.getElementById('status-select');
        const challanId = select.dataset.challanId;
        const newStatus = select.value;
        
        fetch(`/ims/delivery-challans/${challanId}/status`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                status: newStatus
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                showAlert('error', 'Failed to update status');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showAlert('error', 'An error occurred while updating status');
        });
    }

    function showAlert(type, message) {
        const alertDiv = document.createElement('div');
        alertDiv.className = `fixed top-4 right-4 p-4 rounded-lg shadow-lg z-50 ${
            type === 'success' ? 'bg-green-800 text-green-200 border border-green-600' : 'bg-red-800 text-red-200 border border-red-600'
        }`;
        alertDiv.innerHTML = `
            <div class="flex items-center">
                <i class="fas ${type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle'} mr-2"></i>
                ${message}
            </div>
        `;
        
        document.body.appendChild(alertDiv);
        
        setTimeout(() => {
            alertDiv.remove();
        }, 5000);
    }
    </script>
</x-app-layout>
