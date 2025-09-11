<x-app-layout>
    <x-slot name="title">
        {{ __('Supplier Details') }} - {{ config('app.name', 'ATMS') }}
    </x-slot>

    <div class="bg-white min-h-screen" x-data="supplierDetailManager()" x-init="init()">
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
                            <a href="{{ route('suppliers.index') }}"
                                class="text-sm font-medium text-gray-700 hover:text-blue-600">
                                Suppliers
                            </a>
                        </div>
                    </li>
                    <li aria-current="page">
                        <div class="flex items-center">
                            <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                            <span class="text-sm font-medium text-gray-500">{{ $supplier->company_name ?? $supplier->name }}</span>
                        </div>
                    </li>
                </ol>
            </nav>
        </div>

        <!-- Header -->
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">{{ $supplier->company_name ?? $supplier->name }}</h1>
                    <p class="text-sm text-gray-600 mt-1">Supplier Details and Information</p>
                </div>
                <div class="flex items-center space-x-3">
                    {{-- Help Button --}}
                    <a href="{{ route('suppliers.help', $supplier) }}"
                        class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-lg transition-colors">
                        <i class="fas fa-question-circle w-4 h-4 mr-2"></i>
                        Help
                    </a>
                    <!-- Edit Button -->
                    <a href="{{ route('suppliers.edit', $supplier) }}"
                        class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors">
                        <i class="fas fa-edit w-4 h-4 mr-2"></i>
                        Edit Supplier
                    </a>
                    <!-- Delete Button -->
                    <button @click="showDeleteConfirm = true"
                        class="inline-flex items-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg transition-colors">
                        <i class="fas fa-trash w-4 h-4 mr-2"></i>
                        Delete
                    </button>
                </div>
            </div>
        </div>

        <!-- Content -->
        <div class="p-6">
            <!-- Tab Navigation -->
            <div class="border-b border-gray-200 mb-6">
                <nav class="-mb-px flex space-x-8">
                    <button type="button" @click="activeTab = 'overview'"
                        :class="activeTab === 'overview' ? 'border-blue-500 text-blue-600' :
                            'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                        class="py-2 px-1 border-b-2 font-medium text-sm">
                        <i class="fas fa-info-circle mr-2"></i>
                        Overview
                    </button>
                    <button type="button" @click="activeTab = 'products'"
                        :class="activeTab === 'products' ? 'border-blue-500 text-blue-600' :
                            'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                        class="py-2 px-1 border-b-2 font-medium text-sm">
                        <i class="fas fa-box mr-2"></i>
                        Products
                        <span class="ml-1 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-blue-100 bg-blue-600 rounded-full">
                            {{ $supplier->products->count() }}
                        </span>
                    </button>
                    <button type="button" @click="activeTab = 'transactions'"
                        :class="activeTab === 'transactions' ? 'border-blue-500 text-blue-600' :
                            'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                        class="py-2 px-1 border-b-2 font-medium text-sm">
                        <i class="fas fa-receipt mr-2"></i>
                        Transactions
                    </button>
                </nav>
            </div>

            <!-- Tab Content -->
            <div class="space-y-6">
                <!-- Overview Tab -->
                <div x-show="activeTab === 'overview'" x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <!-- Supplier Information -->
                        <div class="bg-white border border-gray-200 rounded-lg p-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">
                                <i class="fas fa-building text-blue-600 mr-2"></i>
                                Supplier Information
                            </h3>
                            <div class="space-y-3">
                                <div>
                                    <span class="text-sm font-medium text-gray-500">Company Name:</span>
                                    <p class="text-gray-900">{{ $supplier->company_name ?? $supplier->name }}</p>
                                </div>
                                @if($supplier->supplier_id)
                                <div>
                                    <span class="text-sm font-medium text-gray-500">Supplier ID:</span>
                                    <p class="text-gray-900">{{ $supplier->supplier_id }}</p>
                                </div>
                                @endif
                                <div>
                                    <span class="text-sm font-medium text-gray-500">Contact Person:</span>
                                    <p class="text-gray-900">{{ $supplier->contact_person }}</p>
                                </div>
                                <div>
                                    <span class="text-sm font-medium text-gray-500">Email:</span>
                                    <p class="text-gray-900">
                                        <a href="mailto:{{ $supplier->email }}" class="text-blue-600 hover:underline">
                                            {{ $supplier->email }}
                                        </a>
                                    </p>
                                </div>
                                <div>
                                    <span class="text-sm font-medium text-gray-500">Phone:</span>
                                    <p class="text-gray-900">
                                        <a href="tel:{{ $supplier->phone ?? $supplier->phone_number }}" class="text-blue-600 hover:underline">
                                            {{ $supplier->phone ?? $supplier->phone_number }}
                                        </a>
                                    </p>
                                </div>
                                @if($supplier->website)
                                <div>
                                    <span class="text-sm font-medium text-gray-500">Website:</span>
                                    <p class="text-gray-900">
                                        <a href="{{ $supplier->website }}" target="_blank" class="text-blue-600 hover:underline">
                                            {{ $supplier->website }}
                                        </a>
                                    </p>
                                </div>
                                @endif
                                @if($supplier->gst)
                                <div>
                                    <span class="text-sm font-medium text-gray-500">GST Number:</span>
                                    <p class="text-gray-900">{{ $supplier->gst }}</p>
                                </div>
                                @endif
                            </div>
                        </div>

                        <!-- Address Information -->
                        <div class="bg-white border border-gray-200 rounded-lg p-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">
                                <i class="fas fa-map-marker-alt text-blue-600 mr-2"></i>
                                Address Information
                            </h3>
                            <div class="space-y-3">
                                <div>
                                    <span class="text-sm font-medium text-gray-500">Street Address:</span>
                                    <p class="text-gray-900">{{ $supplier->address }}</p>
                                </div>
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <span class="text-sm font-medium text-gray-500">City:</span>
                                        <p class="text-gray-900">{{ $supplier->city }}</p>
                                    </div>
                                    <div>
                                        <span class="text-sm font-medium text-gray-500">State:</span>
                                        <p class="text-gray-900">{{ $supplier->state }}</p>
                                    </div>
                                </div>
                                <div class="grid grid-cols-2 gap-4">
                                    @if($supplier->postal_code)
                                    <div>
                                        <span class="text-sm font-medium text-gray-500">Postal Code:</span>
                                        <p class="text-gray-900">{{ $supplier->postal_code }}</p>
                                    </div>
                                    @endif
                                    <div>
                                        <span class="text-sm font-medium text-gray-500">Country:</span>
                                        <p class="text-gray-900">{{ $supplier->country }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Stats -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-6">
                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-box text-blue-600 text-xl"></i>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm font-medium text-blue-600">Products</p>
                                    <p class="text-lg font-semibold text-blue-900">{{ $supplier->products->count() }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-shopping-cart text-green-600 text-xl"></i>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm font-medium text-green-600">Total Purchases</p>
                                    <p class="text-lg font-semibold text-green-900">{{ $supplier->purchases->count() }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-clock text-yellow-600 text-xl"></i>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm font-medium text-yellow-600">Last Order</p>
                                    <p class="text-lg font-semibold text-yellow-900">{{ $supplier->purchases->last()->created_at ?? 'N/A' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Products Tab -->
                <div x-show="activeTab === 'products'" x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
                    <div class="bg-white border border-gray-200 rounded-lg">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <h3 class="text-lg font-semibold text-gray-900">Assigned Products</h3>
                        </div>
                        @if ($supplier->products->isEmpty())
                            <div class="px-6 py-8 text-center">
                                <i class="fas fa-box text-gray-400 text-4xl mb-4"></i>
                                <h4 class="text-lg font-medium text-gray-900 mb-2">No products assigned</h4>
                                <p class="text-gray-500 mb-4">This supplier doesn't have any products assigned yet.</p>
                            </div>
                        @else
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">#</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">HSN Code</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Stock Details</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @foreach ($supplier->products as $product)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                {{ $loop->iteration }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm font-medium text-gray-900">{{ $product->name }}</div>
                                                @if($product->description)
                                                <div class="text-sm text-gray-500">{{ Str::limit($product->description, 50) }}</div>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                {{ $product->hsn_code ?? 'N/A' }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                @if($product->stocks->count() > 0)
                                                    @foreach($product->stocks as $stock)
                                                        <div class="mb-1">
                                                            <span class="font-medium">{{ $stock->quantity }} {{ $stock->unit_type }}</span>
                                                            @if($stock->batch_code)
                                                                <span class="text-gray-500">({{ $stock->batch_code }})</span>
                                                            @endif
                                                            <br>
                                                            <span class="text-xs text-gray-500">Sold: {{ $stock->sold ?? 0 }}</span>
                                                        </div>
                                                    @endforeach
                                                @else
                                                    <span class="text-gray-500">No stock available</span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                <a href="{{ route('products.show', $product) }}" class="text-blue-600 hover:text-blue-900">View</a>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Transactions Tab -->
                <div x-show="activeTab === 'transactions'" x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
                    <div class="bg-white border border-gray-200 rounded-lg">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <h3 class="text-lg font-semibold text-gray-900">Purchase History</h3>
                        </div>
                        @if($transactions->isEmpty())
                            <div class="px-6 py-8 text-center">
                                <i class="fas fa-receipt text-gray-400 text-4xl mb-4"></i>
                                <h4 class="text-lg font-medium text-gray-900 mb-2">No purchases found</h4>
                                <p class="text-gray-500">Purchase history will appear here once transactions are made.</p>
                            </div>
                        @else
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">#</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Purchase ID</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Items</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Amount</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @foreach($transactions as $transaction)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                {{ $loop->iteration }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                                #{{ $transaction->id }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                {{ $transaction->created_at->format('M d, Y') }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                {{ $transaction->purchaseItems->count() }} item(s)
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                ₹{{ number_format($transaction->total ?? 0, 2) }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full 
                                                    {{ ($transaction->status ?? 'completed') === 'completed' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                                    {{ ucfirst($transaction->status ?? 'Completed') }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                <a href="{{ route('purchases.show', $transaction) }}" class="text-blue-600 hover:text-blue-900">View</a>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Delete Confirmation Modal -->
        <div x-show="showDeleteConfirm" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50"
            x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
            <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
                <div class="mt-3 text-center">
                    <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100">
                        <i class="fas fa-exclamation-triangle text-red-600"></i>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 mt-4">Delete Supplier</h3>
                    <div class="mt-2 px-7 py-3">
                        <p class="text-sm text-gray-500">
                            Are you sure you want to delete this supplier? This action cannot be undone.
                        </p>
                    </div>
                    <div class="items-center px-4 py-3">
                        <form action="{{ route('suppliers.destroy', $supplier) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                class="px-4 py-2 bg-red-500 text-white text-base font-medium rounded-md w-full shadow-sm hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-red-300">
                                Delete
                            </button>
                        </form>
                        <button @click="showDeleteConfirm = false"
                            class="mt-3 px-4 py-2 bg-white text-gray-700 text-base font-medium rounded-md w-full shadow-sm border border-gray-300 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-300">
                            Cancel
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    @push('scripts')
    <script>
        function supplierDetailManager() {
            return {
                activeTab: 'overview',
                showDeleteConfirm: false,
                
                init() {
                    this.bindKeyboardEvents();
                },

                bindKeyboardEvents() {
                    document.addEventListener('keydown', (e) => {
                        // Don't trigger shortcuts when typing in inputs
                        if (e.target.tagName === 'INPUT' || e.target.tagName === 'TEXTAREA' || e.target.tagName === 'SELECT') {
                            return;
                        }

                        // Edit supplier - E key or Ctrl+E
                        if ((e.key.toLowerCase() === 'e' && !e.ctrlKey && !e.altKey) || (e.ctrlKey && e.key === 'e')) {
                            e.preventDefault();
                            window.location.href = '{{ route('suppliers.edit', $supplier) }}';
                        }

                        // Show help - H key
                        if (e.key.toLowerCase() === 'h' && !e.ctrlKey && !e.altKey) {
                            e.preventDefault();
                            window.location.href = '{{ route('suppliers.help') }}';
                        }

                        // Back to list - Escape or Ctrl+B
                        if (e.key === 'Escape' || (e.ctrlKey && e.key === 'b')) {
                            e.preventDefault();
                            window.location.href = '{{ route('suppliers.index') }}';
                        }

                        // Delete supplier - Delete key
                        if (e.key === 'Delete' && !e.ctrlKey && !e.altKey) {
                            e.preventDefault();
                            this.showDeleteConfirm = true;
                        }

                        // Switch tabs with numbers (1: overview, 2: products, 3: transactions)
                        if (!e.ctrlKey && !e.altKey && e.key >= '1' && e.key <= '3') {
                            e.preventDefault();
                            const tabs = ['overview', 'products', 'transactions'];
                            const tabIndex = parseInt(e.key) - 1;
                            if (tabs[tabIndex]) {
                                this.activeTab = tabs[tabIndex];
                            }
                        }
                    });
                }
            }
        }
    </script>
    @endpush
</x-app-layout>
