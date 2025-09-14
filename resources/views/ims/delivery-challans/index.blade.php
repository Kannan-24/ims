<x-app-layout>
    <x-slot name="title">
        {{ __('Delivery Challans') }} - {{ config('app.name', 'SKM') }}
    </x-slot>

    <div class="bg-white" x-data="deliveryChallanManager()" x-init="init()">
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
                    <li aria-current="page">
                        <div class="flex items-center">
                            <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                            <span class="text-sm font-medium text-gray-500">Delivery Challans</span>
                        </div>
                    </li>
                </ol>
            </nav>
        </div>

        <!-- Header -->
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">📦 Delivery Challans</h1>
                    <p class="text-sm text-gray-600 mt-1">Manage delivery challans for invoices and track shipments</p>
                </div>
                <div class="flex items-center space-x-3">
                    <!-- Help Button -->
                    <button @click="showHelpModal = true"
                        class="inline-flex items-center px-3 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium rounded-lg transition-colors">
                        <i class="fas fa-question-circle w-4 h-4 mr-2"></i>
                        Help
                    </button>
                    <!-- View Invoices Button -->
                    <a href="{{ route('invoices.index') }}" 
                        class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors">
                        <i class="fas fa-file-invoice w-4 h-4 mr-2"></i>
                        View Invoices
                    </a>
                </div>
            </div>
        </div>

        <!-- Content -->
        <div class="p-6">
            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                    <div class="flex items-center">
                        <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center mr-3">
                            <i class="fas fa-file-alt text-blue-600"></i>
                        </div>
                        <div>
                            <p class="text-sm text-blue-600 font-medium">Total Challans</p>
                            <p class="text-2xl font-bold text-blue-900">{{ $deliveryChallans->count() }}</p>
                        </div>
                    </div>
                </div>
                <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                    <div class="flex items-center">
                        <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center mr-3">
                            <i class="fas fa-check-circle text-green-600"></i>
                        </div>
                        <div>
                            <p class="text-sm text-green-600 font-medium">Delivered</p>
                            <p class="text-2xl font-bold text-green-900">{{ $deliveryChallans->where('status', 'delivered')->count() }}</p>
                        </div>
                    </div>
                </div>
                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                    <div class="flex items-center">
                        <div class="w-10 h-10 bg-yellow-100 rounded-lg flex items-center justify-center mr-3">
                            <i class="fas fa-clock text-yellow-600"></i>
                        </div>
                        <div>
                            <p class="text-sm text-yellow-600 font-medium">Pending</p>
                            <p class="text-2xl font-bold text-yellow-900">{{ $deliveryChallans->where('status', 'pending')->count() }}</p>
                        </div>
                    </div>
                </div>
                <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                    <div class="flex items-center">
                        <div class="w-10 h-10 bg-red-100 rounded-lg flex items-center justify-center mr-3">
                            <i class="fas fa-times-circle text-red-600"></i>
                        </div>
                        <div>
                            <p class="text-sm text-red-600 font-medium">Cancelled</p>
                            <p class="text-2xl font-bold text-red-900">{{ $deliveryChallans->where('status', 'cancelled')->count() }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Delivery Challans Table -->
            <div class="bg-white border border-gray-200 rounded-lg shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <div class="flex items-center justify-between">
                        <h2 class="text-lg font-semibold text-gray-900">Delivery Challans Directory</h2>
                        <div class="flex items-center space-x-2">
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                    <i class="fas fa-search text-gray-400"></i>
                                </div>
                                <form method="GET" action="{{ route('delivery-challans.index') }}" class="flex">
                                    <input type="text" name="search" value="{{ request('search') }}"
                                        placeholder="Search challans..." id="searchInput"
                                        class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                    <button type="submit"
                                        class="ml-2 px-4 py-2 bg-blue-600 text-white rounded-lg text-sm hover:bg-blue-700">Search</button>
                                    @if (request('search'))
                                        <a href="{{ route('delivery-challans.index') }}"
                                            class="ml-2 px-4 py-2 bg-gray-500 text-white rounded-lg text-sm hover:bg-gray-600">Reset</a>
                                    @endif
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                @if($deliveryChallans->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        #
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Challan Info
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Customer
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Delivery Date
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Status
                                    </th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Actions
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($deliveryChallans as $challan)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $loop->iteration }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center mr-3">
                                                <i class="fas fa-truck text-blue-600"></i>
                                            </div>
                                            <div>
                                                <div class="text-sm font-medium text-gray-900">{{ $challan->dc_no }}</div>
                                                <div class="text-sm text-gray-500">Invoice: {{ $challan->invoice->invoice_no }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ $challan->invoice->customer->company_name ?? $challan->invoice->customer->name }}</div>
                                        <div class="text-sm text-gray-500">Generated: {{ \Carbon\Carbon::parse($challan->generated_at)->format('d M Y H:i') }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ \Carbon\Carbon::parse($challan->delivery_date)->format('d M Y') }}</div>
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
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <div class="flex items-center justify-end space-x-2">
                                            <a href="{{ route('delivery-challans.show', $challan->id) }}" 
                                               class="text-blue-600 hover:text-blue-900 transition-colors" title="View">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('delivery-challans.pdf', $challan->id) }}" 
                                               class="text-purple-600 hover:text-purple-900 transition-colors" title="View PDF" target="_blank">
                                                <i class="fas fa-file-pdf"></i>
                                            </a>
                                            <a href="{{ route('delivery-challans.download', $challan->id) }}" 
                                               class="text-green-600 hover:text-green-900 transition-colors" title="Download PDF">
                                                <i class="fas fa-download"></i>
                                            </a>
                                            <select class="status-select text-xs border border-gray-300 bg-white text-gray-700 rounded px-2 py-1 focus:ring-2 focus:ring-blue-500 focus:border-transparent" 
                                                    data-challan-id="{{ $challan->id }}" 
                                                    data-current-status="{{ $challan->status }}">
                                                <option value="pending" {{ $challan->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                                <option value="generated" {{ $challan->status == 'generated' ? 'selected' : '' }}>Generated</option>
                                                <option value="delivered" {{ $challan->status == 'delivered' ? 'selected' : '' }}>Delivered</option>
                                                <option value="cancelled" {{ $challan->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                            </select>
                                            <button @click="deleteChallan({{ $challan->id }})"
                                                    class="text-red-600 hover:text-red-900 transition-colors" title="Delete">
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
                    <div class="px-6 py-12 text-center">
                        <div class="flex flex-col items-center justify-center">
                            <i class="fas fa-truck text-gray-300 text-4xl mb-4"></i>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">No delivery challans found</h3>
                            <p class="text-sm text-gray-500 mb-4">
                                @if (request('search'))
                                    Try adjusting your search terms.
                                @else
                                    Generate delivery challans from invoices to get started.
                                @endif
                            </p>
                            <a href="{{ route('invoices.index') }}" 
                                class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors">
                                <i class="fas fa-file-invoice w-4 h-4 mr-2"></i>
                                View Invoices
                            </a>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Help Modal -->
        <div x-show="showHelpModal" x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0" class="fixed inset-0 z-50 overflow-y-auto"
            @keydown.escape.window="closeHelpModal()">

            <!-- Backdrop -->
            <div class="fixed inset-0 bg-gray-900 bg-opacity-50" @click="closeHelpModal()"></div>

            <!-- Modal -->
            <div class="relative flex min-h-screen items-center justify-center p-4">
                <div x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                    x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 scale-100"
                    x-transition:leave-end="opacity-0 scale-95"
                    class="bg-white rounded-lg shadow-xl max-w-md w-full p-6">
                    
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-900">📦 Delivery Challans Help</h3>
                        <button @click="closeHelpModal()" class="text-gray-400 hover:text-gray-600">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>

                    <div class="space-y-4">
                        <div>
                            <h4 class="font-medium text-gray-900 mb-2">Quick Actions</h4>
                            <ul class="text-sm text-gray-600 space-y-1">
                                <li>• View delivery challan details by clicking the eye icon</li>
                                <li>• Download PDF documents for printing</li>
                                <li>• Update delivery status using the dropdown</li>
                                <li>• Delete challans that are no longer needed</li>
                            </ul>
                        </div>
                        
                        <div>
                            <h4 class="font-medium text-gray-900 mb-2">Status Types</h4>
                            <ul class="text-sm text-gray-600 space-y-1">
                                <li>• <span class="text-yellow-600">Pending:</span> Awaiting processing</li>
                                <li>• <span class="text-blue-600">Generated:</span> Challan created</li>
                                <li>• <span class="text-green-600">Delivered:</span> Successfully delivered</li>
                                <li>• <span class="text-red-600">Cancelled:</span> Delivery cancelled</li>
                            </ul>
                        </div>
                    </div>

                    <div class="mt-6 flex justify-end">
                        <button @click="closeHelpModal()" 
                                class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg transition-colors">
                            Got it
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
    function deliveryChallanManager() {
        return {
            showHelpModal: false,

            init() {
                // Initialize component
                this.setupStatusListeners();
            },

            setupStatusListeners() {
                // Handle status changes
                document.querySelectorAll('.status-select').forEach(select => {
                    select.addEventListener('change', (e) => {
                        const challanId = e.target.dataset.challanId;
                        const newStatus = e.target.value;
                        const currentStatus = e.target.dataset.currentStatus;
                        
                        if (newStatus === currentStatus) return;
                        
                        this.updateStatus(challanId, newStatus, e.target);
                    });
                });
            },

            updateStatus(challanId, status, selectElement) {
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
                        
                        this.showAlert('success', data.message);
                    } else {
                        this.showAlert('error', data.message || 'Failed to update status');
                        selectElement.value = selectElement.dataset.currentStatus; // Reset select
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    this.showAlert('error', 'An error occurred while updating status');
                    selectElement.value = selectElement.dataset.currentStatus; // Reset select
                });
            },

            deleteChallan(challanId) {
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
                            this.showAlert('success', data.message);
                            setTimeout(() => {
                                location.reload();
                            }, 1500);
                        } else {
                            this.showAlert('error', data.message || 'Failed to delete delivery challan');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        this.showAlert('error', 'An error occurred while deleting delivery challan');
                    });
                }
            },

            closeHelpModal() {
                this.showHelpModal = false;
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
