<x-app-layout>
    <x-slot name="title">
        {{ __('Delivery Challan Details') }} - {{ config('app.name', 'SKM') }}
    </x-slot>

    <div class="bg-white" x-data="deliveryChallanDetails()" x-init="init()">
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
                            <a href="{{ route('delivery-challans.index') }}"
                                class="text-sm font-medium text-gray-700 hover:text-blue-600">
                                Delivery Challans
                            </a>
                        </div>
                    </li>
                    <li aria-current="page">
                        <div class="flex items-center">
                            <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                            <span class="text-sm font-medium text-gray-500">{{ $deliveryChallan->dc_no }}</span>
                        </div>
                    </li>
                </ol>
            </nav>
        </div>

        <!-- Header -->
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">📦 Delivery Challan Details</h1>
                    <p class="text-sm text-gray-600 mt-1">{{ $deliveryChallan->dc_no }} - {{ $deliveryChallan->invoice->customer->company_name ?? $deliveryChallan->invoice->customer->name }}</p>
                </div>
                <div class="flex items-center space-x-3">
                    <a href="{{ route('delivery-challans.index') }}" 
                        class="inline-flex items-center px-3 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium rounded-lg transition-colors">
                        <i class="fas fa-arrow-left w-4 h-4 mr-2"></i>
                        Back to List
                    </a>
                    <a href="{{ route('delivery-challans.pdf', $deliveryChallan->id) }}" target="_blank" 
                        class="inline-flex items-center px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white text-sm font-medium rounded-lg transition-colors">
                        <i class="fas fa-file-pdf w-4 h-4 mr-2"></i>
                        View PDF
                    </a>
                    <a href="{{ route('delivery-challans.download', $deliveryChallan->id) }}" 
                        class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-lg transition-colors">
                        <i class="fas fa-download w-4 h-4 mr-2"></i>
                        Download PDF
                    </a>
                </div>
            </div>
        </div>

        <!-- Content -->
        <div class="p-6">
            <!-- Quick Overview Cards -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                    <div class="flex items-center">
                        <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center mr-3">
                            <i class="fas fa-file-alt text-blue-600"></i>
                        </div>
                        <div>
                            <p class="text-sm text-blue-600 font-medium">DC Number</p>
                            <p class="text-lg font-bold text-blue-900">{{ $deliveryChallan->dc_no }}</p>
                        </div>
                    </div>
                </div>
                <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                    <div class="flex items-center">
                        <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center mr-3">
                            <i class="fas fa-calendar text-green-600"></i>
                        </div>
                        <div>
                            <p class="text-sm text-green-600 font-medium">Delivery Date</p>
                            <p class="text-lg font-bold text-green-900">{{ \Carbon\Carbon::parse($deliveryChallan->delivery_date)->format('d M Y') }}</p>
                        </div>
                    </div>
                </div>
                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                    <div class="flex items-center">
                        <div class="w-10 h-10 bg-yellow-100 rounded-lg flex items-center justify-center mr-3">
                            <i class="fas fa-info-circle text-yellow-600"></i>
                        </div>
                        <div>
                            <p class="text-sm text-yellow-600 font-medium">Status</p>
                            <p class="text-lg font-bold text-yellow-900">{{ ucfirst($deliveryChallan->status) }}</p>
                        </div>
                    </div>
                </div>
                <div class="bg-purple-50 border border-purple-200 rounded-lg p-4">
                    <div class="flex items-center">
                        <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center mr-3">
                            <i class="fas fa-file-invoice text-purple-600"></i>
                        </div>
                        <div>
                            <p class="text-sm text-purple-600 font-medium">Invoice</p>
                            <p class="text-lg font-bold text-purple-900">{{ $deliveryChallan->invoice->invoice_no }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Content Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
                <!-- Delivery Challan Information -->
                <div class="bg-white border border-gray-200 rounded-lg shadow-sm overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">📋 Challan Information</h3>
                    </div>
                    <div class="p-6">
                        <div class="space-y-4">
                            <div class="flex justify-between items-center">
                                <span class="text-sm font-medium text-gray-500">DC Number:</span>
                                <span class="text-sm font-medium text-gray-900">{{ $deliveryChallan->dc_no }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-sm font-medium text-gray-500">Delivery Date:</span>
                                <span class="text-sm font-medium text-gray-900">{{ \Carbon\Carbon::parse($deliveryChallan->delivery_date)->format('d M Y') }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-sm font-medium text-gray-500">Status:</span>
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                    @if($deliveryChallan->status == 'delivered') bg-green-100 text-green-800
                                    @elseif($deliveryChallan->status == 'generated') bg-blue-100 text-blue-800
                                    @elseif($deliveryChallan->status == 'cancelled') bg-red-100 text-red-800
                                    @else bg-yellow-100 text-yellow-800 @endif">
                                    {{ ucfirst($deliveryChallan->status) }}
                                </span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-sm font-medium text-gray-500">Generated At:</span>
                                <span class="text-sm font-medium text-gray-900">{{ \Carbon\Carbon::parse($deliveryChallan->generated_at)->format('d M Y H:i A') }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-sm font-medium text-gray-500">Last Updated:</span>
                                <span class="text-sm font-medium text-gray-900">{{ \Carbon\Carbon::parse($deliveryChallan->updated_at)->format('d M Y H:i A') }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Invoice Information -->
                <div class="bg-white border border-gray-200 rounded-lg shadow-sm overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">📄 Related Invoice</h3>
                    </div>
                    <div class="p-6">
                        <div class="space-y-4">
                            <div class="flex justify-between items-center">
                                <span class="text-sm font-medium text-gray-500">Invoice Number:</span>
                                <span class="text-sm font-medium text-gray-900">{{ $deliveryChallan->invoice->invoice_no }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-sm font-medium text-gray-500">Invoice Date:</span>
                                <span class="text-sm font-medium text-gray-900">{{ \Carbon\Carbon::parse($deliveryChallan->invoice->created_at)->format('d M Y') }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-sm font-medium text-gray-500">Total Amount:</span>
                                <span class="text-sm font-medium text-gray-900">₹{{ number_format($deliveryChallan->invoice->total_amount, 2) }}</span>
                            </div>
                            <div class="pt-4">
                                <a href="{{ route('invoices.show', $deliveryChallan->invoice->id) }}" 
                                    class="inline-flex items-center text-blue-600 hover:text-blue-900 text-sm font-medium">
                                    <i class="fas fa-external-link-alt mr-2"></i>View Full Invoice
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Customer Information -->
            <div class="bg-white border border-gray-200 rounded-lg shadow-sm overflow-hidden mb-8">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">👤 Customer Information</h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h4 class="font-medium text-gray-900 mb-3">Contact Details</h4>
                            <div class="space-y-3">
                                <div class="flex items-center">
                                    <i class="fas fa-building w-4 h-4 mr-3 text-gray-400"></i>
                                    <span class="text-sm text-gray-900">{{ $deliveryChallan->invoice->customer->company_name ?? $deliveryChallan->invoice->customer->name }}</span>
                                </div>
                                @if($deliveryChallan->invoice->customer->phone)
                                    <div class="flex items-center">
                                        <i class="fas fa-phone w-4 h-4 mr-3 text-gray-400"></i>
                                        <span class="text-sm text-gray-900">{{ $deliveryChallan->invoice->customer->phone }}</span>
                                    </div>
                                @endif
                                @if($deliveryChallan->invoice->customer->email)
                                    <div class="flex items-center">
                                        <i class="fas fa-envelope w-4 h-4 mr-3 text-gray-400"></i>
                                        <span class="text-sm text-gray-900">{{ $deliveryChallan->invoice->customer->email }}</span>
                                    </div>
                                @endif
                                @if($deliveryChallan->invoice->customer->gst)
                                    <div class="flex items-center">
                                        <i class="fas fa-file-contract w-4 h-4 mr-3 text-gray-400"></i>
                                        <span class="text-sm text-gray-900">GST: {{ $deliveryChallan->invoice->customer->gst }}</span>
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div>
                            <h4 class="font-medium text-gray-900 mb-3">Address</h4>
                            <div class="flex items-start">
                                <i class="fas fa-map-marker-alt w-4 h-4 mr-3 mt-1 text-gray-400"></i>
                                <div class="text-sm text-gray-900">
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
                </div>
            </div>

            <!-- Items Information -->
            <div class="bg-white border border-gray-200 rounded-lg shadow-sm overflow-hidden mb-8">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">📦 Products for Delivery</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">S.No</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">HSN Code</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantity</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Unit</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Rate</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
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
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $loop->iteration }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">{{ $item->product->name ?? 'N/A' }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $item->product->description ?? '-' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">{{ $item->product->hsn_code ?? '-' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $item->quantity ?? 0 }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $item->unit_type ?? $item->product->unit ?? 'Nos' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">₹{{ number_format($item->unit_price ?? 0, 2) }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">₹{{ number_format($item->total ?? ($item->quantity * $item->unit_price), 2) }}</td>
                                </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="8" class="px-6 py-8 text-center">
                                        <div class="flex flex-col items-center justify-center">
                                            <i class="fas fa-box-open text-gray-300 text-3xl mb-3"></i>
                                            <p class="text-gray-500 text-sm">No products available for delivery</p>
                                        </div>
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Status Update Section -->
            <div class="bg-white border border-gray-200 rounded-lg shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">🔄 Update Status</h3>
                </div>
                <div class="p-6">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:space-x-4 space-y-4 sm:space-y-0">
                        <label for="status-select" class="text-sm font-medium text-gray-700">Current Status:</label>
                        <select id="status-select" 
                                class="flex-1 sm:flex-none bg-white border border-gray-300 text-gray-900 rounded-lg px-4 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent" 
                                data-challan-id="{{ $deliveryChallan->id }}">
                            <option value="pending" {{ $deliveryChallan->status == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="generated" {{ $deliveryChallan->status == 'generated' ? 'selected' : '' }}>Generated</option>
                            <option value="delivered" {{ $deliveryChallan->status == 'delivered' ? 'selected' : '' }}>Delivered</option>
                            <option value="cancelled" {{ $deliveryChallan->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        </select>
                        <button @click="updateStatus()" 
                                class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors">
                            <i class="fas fa-save mr-2"></i>Update Status
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
    function deliveryChallanDetails() {
        return {
            init() {
                // Initialize component
            },

            updateStatus() {
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
                        this.showAlert('success', data.message);
                        setTimeout(() => {
                            location.reload();
                        }, 1500);
                    } else {
                        this.showAlert('error', 'Failed to update status');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    this.showAlert('error', 'An error occurred while updating status');
                });
            },

            showAlert(type, message) {
                const alertDiv = document.createElement('div');
                alertDiv.className = `fixed top-4 right-4 p-4 rounded-lg shadow-lg z-50 ${
                    type === 'success' ? 'bg-green-100 text-green-800 border border-green-200' : 'bg-red-100 text-red-800 border border-red-200'
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
        }
    }
    </script>
</x-app-layout>
