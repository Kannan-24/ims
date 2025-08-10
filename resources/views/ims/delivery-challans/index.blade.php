<x-app-layout>
    <x-slot name="title">
        {{ __('Delivery Challans') }} - {{ config('app.name', 'SKM') }}
    </x-slot>

    <div class="py-6 mt-20 ml-4 sm:ml-64">
        <div class="w-full mx-auto max-w-7xl sm:px-6 lg:px-8">
            <x-bread-crumb-navigation />

            <div class="p-8 bg-gray-800 border border-gray-700 rounded-lg shadow-lg relative">
                <div class="flex justify-between items-center mb-6">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-200">Delivery Challans</h1>
                        <p class="text-gray-400 mt-1">Manage delivery challans for invoices</p>
                    </div>
                    <div class="flex space-x-3">
                        <a href="{{ route('invoices.index') }}" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg transition duration-200">
                            <i class="fas fa-file-invoice mr-2"></i>View Invoices
                        </a>
                    </div>
                </div>

                <hr class="my-6 border-gray-600">

                @if($deliveryChallans->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-600">
                            <thead class="bg-gray-700">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">
                                        DC No
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">
                                        Invoice No
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">
                                        Customer
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">
                                        Delivery Date
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">
                                        Status
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">
                                        Generated At
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">
                                        Actions
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-gray-800 divide-y divide-gray-600">
                                @foreach($deliveryChallans as $challan)
                                <tr class="hover:bg-gray-700">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-200">{{ $challan->dc_no }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-300">{{ $challan->invoice->invoice_no }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-300">{{ $challan->invoice->customer->company_name ?? $challan->invoice->customer->name }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-300">{{ \Carbon\Carbon::parse($challan->delivery_date)->format('d M Y') }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                            @if($challan->status == 'delivered') bg-green-100 text-green-800
                                            @elseif($challan->status == 'generated') bg-blue-100 text-blue-800
                                            @elseif($challan->status == 'cancelled') bg-red-100 text-red-800
                                            @else bg-yellow-100 text-yellow-800 @endif">
                                            {{ ucfirst($challan->status) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-300">{{ \Carbon\Carbon::parse($challan->generated_at)->format('d M Y H:i') }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <div class="flex space-x-2">
                                            <a href="{{ route('delivery-challans.show', $challan->id) }}" 
                                               class="text-indigo-400 hover:text-indigo-300" title="View">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('delivery-challans.pdf', $challan->id) }}" 
                                               class="text-blue-400 hover:text-blue-300" title="View PDF" target="_blank">
                                                <i class="fas fa-file-pdf"></i>
                                            </a>
                                            <a href="{{ route('delivery-challans.download', $challan->id) }}" 
                                               class="text-green-400 hover:text-green-300" title="Download PDF">
                                                <i class="fas fa-download"></i>
                                            </a>
                                            <select class="status-select text-xs border border-gray-600 bg-gray-700 text-gray-200 rounded px-1 py-1" 
                                                    data-challan-id="{{ $challan->id }}" 
                                                    data-current-status="{{ $challan->status }}">
                                                <option value="pending" {{ $challan->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                                <option value="generated" {{ $challan->status == 'generated' ? 'selected' : '' }}>Generated</option>
                                                <option value="delivered" {{ $challan->status == 'delivered' ? 'selected' : '' }}>Delivered</option>
                                                <option value="cancelled" {{ $challan->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                            </select>
                                            <button onclick="deleteChallan({{ $challan->id }})" 
                                                    class="text-red-400 hover:text-red-300" title="Delete">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-12">
                        <i class="fas fa-file-alt text-6xl text-gray-500 mb-4"></i>
                        <h3 class="text-lg font-medium text-gray-200 mb-2">No delivery challans found</h3>
                        <p class="text-gray-400 mb-6">Generate delivery challans from invoices to get started.</p>
                        <a href="{{ route('invoices.index') }}" class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-3 rounded-lg transition duration-200">
                            <i class="fas fa-file-invoice mr-2"></i>View Invoices
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Handle status changes
        document.querySelectorAll('.status-select').forEach(select => {
            select.addEventListener('change', function() {
                const challanId = this.dataset.challanId;
                const newStatus = this.value;
                const currentStatus = this.dataset.currentStatus;
                
                if (newStatus === currentStatus) return;
                
                updateStatus(challanId, newStatus, this);
            });
        });
    });

    function updateStatus(challanId, status, selectElement) {
        fetch(`/ims/delivery-challans/${challanId}/status`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                status: status
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                selectElement.dataset.currentStatus = status;
                
                // Update status badge
                const statusBadge = selectElement.closest('tr').querySelector('.rounded-full');
                statusBadge.className = 'px-2 inline-flex text-xs leading-5 font-semibold rounded-full ';
                
                if (status === 'delivered') {
                    statusBadge.className += 'bg-green-100 text-green-800';
                } else if (status === 'generated') {
                    statusBadge.className += 'bg-blue-100 text-blue-800';
                } else if (status === 'cancelled') {
                    statusBadge.className += 'bg-red-100 text-red-800';
                } else {
                    statusBadge.className += 'bg-yellow-100 text-yellow-800';
                }
                
                statusBadge.textContent = status.charAt(0).toUpperCase() + status.slice(1);
                
                showAlert('success', data.message);
            } else {
                showAlert('error', data.message || 'Failed to update status');
                selectElement.value = selectElement.dataset.currentStatus; // Reset select
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showAlert('error', 'An error occurred while updating status');
            selectElement.value = selectElement.dataset.currentStatus; // Reset select
        });
    }

    function deleteChallan(challanId) {
        if (confirm('Are you sure you want to delete this delivery challan?')) {
            fetch(`/ims/delivery-challans/${challanId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showAlert('success', data.message);
                    setTimeout(() => {
                        location.reload();
                    }, 1500);
                } else {
                    showAlert('error', data.message || 'Failed to delete delivery challan');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showAlert('error', 'An error occurred while deleting delivery challan');
            });
        }
    }

    function showAlert(type, message) {
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
    </script>
</x-app-layout>
