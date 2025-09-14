<x-app-layout>
    <x-slot name="title">
        {{ __('Stock Management') }} - {{ config('app.name', 'IMS') }}
    </x-slot>

    <div class="bg-white" x-data="stockManager()" x-init="init()">
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
                            <span class="text-sm font-medium text-gray-500">Stock Management</span>
                        </div>
                    </li>
                </ol>
            </nav>
        </div>

        <!-- Header -->
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Stock Management</h1>
                    <p class="text-sm text-gray-600 mt-1">Manage inventory levels and stock entries</p>
                </div>
                <div class="flex items-center space-x-3">
                    <!-- Help Button -->
                    <a href="{{ route('stocks.help') }}"
                        class="inline-flex items-center px-3 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium rounded-lg transition-colors">
                        <i class="fas fa-question-circle w-4 h-4 mr-2"></i>
                        Help
                    </a>
                    <!-- New Stock Button -->
                    <a href="{{ route('stocks.create') }}"
                        class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors">
                        <i class="fas fa-plus w-4 h-4 mr-2"></i>
                        Add Stock Entry
                    </a>
                </div>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="px-6 py-4 bg-gray-50">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div class="bg-white rounded-lg shadow-sm p-4">
                    <div class="flex items-center">
                        <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center mr-3">
                            <i class="fas fa-boxes text-blue-600"></i>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-600">Total Stock Entries</p>
                            <p class="text-xl font-bold text-gray-900">{{ $stocks->total() }}</p>
                        </div>
                    </div>
                </div>
                <div class="bg-white rounded-lg shadow-sm p-4">
                    <div class="flex items-center">
                        <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center mr-3">
                            <i class="fas fa-chart-line text-green-600"></i>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-600">Total Quantity</p>
                            <p class="text-xl font-bold text-gray-900">{{ number_format($stocks->sum('quantity')) }}</p>
                        </div>
                    </div>
                </div>
                <div class="bg-white rounded-lg shadow-sm p-4">
                    <div class="flex items-center">
                        <div class="w-8 h-8 bg-yellow-100 rounded-lg flex items-center justify-center mr-3">
                            <i class="fas fa-shopping-cart text-yellow-600"></i>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-600">Total Sold</p>
                            <p class="text-xl font-bold text-gray-900">{{ number_format($stocks->sum('sold')) }}</p>
                        </div>
                    </div>
                </div>
                <div class="bg-white rounded-lg shadow-sm p-4">
                    <div class="flex items-center">
                        <div class="w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center mr-3">
                            <i class="fas fa-warehouse text-purple-600"></i>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-600">Available Stock</p>
                            <p class="text-xl font-bold text-gray-900">{{ number_format($stocks->sum('quantity') - $stocks->sum('sold')) }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Content -->
        <div class="p-6">
            <!-- Stock Table -->
            <div class="bg-white border border-gray-200 rounded-lg shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <div class="flex items-center justify-between">
                        <h2 class="text-lg font-semibold text-gray-900">Stock Inventory</h2>
                        <div class="flex items-center space-x-2">
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                    <i class="fas fa-search text-gray-400"></i>
                                </div>
                                <form method="GET" action="{{ route('stocks.index') }}" class="flex">
                                    <input type="text" name="search" value="{{ request('search') }}"
                                        placeholder="Search stocks..." id="searchInput"
                                        class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                    <button type="submit"
                                        class="ml-2 px-4 py-2 bg-blue-600 text-white rounded-lg text-sm hover:bg-blue-700">Search</button>
                                    @if (request('search'))
                                        <a href="{{ route('stocks.index') }}"
                                            class="ml-2 px-4 py-2 bg-gray-500 text-white rounded-lg text-sm hover:bg-gray-600">Reset</a>
                                    @endif
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Product</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Batch Code</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Supplier</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Entry Type</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Quantity</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Sold</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Available</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Price</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Date</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200" id="stockTableBody">
                            @forelse ($stocks as $stock)
                                <tr class="hover:bg-gray-50 stock-row" data-stock-id="{{ $stock->id }}"
                                    :class="selectedRowIndex === {{ $loop->index }} ? 'bg-blue-50 ring-2 ring-blue-500' : ''">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center mr-3">
                                                <i class="fas fa-box text-blue-600"></i>
                                            </div>
                                            <div>
                                                <div class="text-sm font-medium text-gray-900">
                                                    {{ $stock->product->name ?? 'N/A' }}
                                                </div>
                                                <div class="text-sm text-gray-500">
                                                    {{ $stock->product->sku ?? 'N/A' }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="font-mono text-sm bg-gray-100 px-2 py-1 rounded">
                                            {{ $stock->batch_code }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ $stock->supplier->name ?? 'N/A' }}</div>
                                        <div class="text-sm text-gray-500">{{ $stock->supplier->supplier_id ?? 'N/A' }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                            {{ $stock->entry_type === 'purchase' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800' }}">
                                            <i class="fas {{ $stock->entry_type === 'purchase' ? 'fa-shopping-cart' : 'fa-edit' }} mr-1"></i>
                                            {{ ucfirst($stock->entry_type) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ number_format($stock->quantity) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ number_format($stock->sold) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @php
                                            $available = $stock->quantity - $stock->sold;
                                        @endphp
                                        <span class="text-sm font-medium {{ $available <= 0 ? 'text-red-600' : ($available <= 10 ? 'text-yellow-600' : 'text-green-600') }}">
                                            {{ number_format($available) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        ₹{{ number_format($stock->price, 2) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $stock->created_at->format('M d, Y') }}
                                        <div class="text-xs text-gray-400">
                                            {{ $stock->created_at->format('h:i A') }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <div class="flex items-center justify-end space-x-2">
                                            <a href="{{ route('stocks.show', $stock) }}"
                                                class="text-blue-600 hover:text-blue-900 transition-colors"
                                                title="View Stock Details">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            @if($stock->entry_type !== 'purchase')
                                                <a href="{{ route('stocks.edit', $stock) }}"
                                                    class="text-indigo-600 hover:text-indigo-900 transition-colors"
                                                    title="Edit Stock">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                            @endif
                                            <form action="{{ route('stocks.destroy', $stock) }}" method="POST" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" 
                                                        class="text-red-600 hover:text-red-900 transition-colors"
                                                        title="Delete Stock"
                                                        onclick="return confirm('Are you sure you want to delete this stock entry?')">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="10" class="px-6 py-8 text-center">
                                        <div class="flex flex-col items-center">
                                            <i class="fas fa-box-open text-gray-400 text-4xl mb-4"></i>
                                            <h3 class="text-lg font-medium text-gray-900 mb-2">No stock entries found</h3>
                                            <p class="text-gray-500 mb-4">Get started by adding your first stock entry.</p>
                                            <a href="{{ route('stocks.create') }}"
                                                class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors">
                                                <i class="fas fa-plus w-4 h-4 mr-2"></i>
                                                Add Stock Entry
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if($stocks->hasPages())
                <div class="px-6 py-4 border-t border-gray-200">
                    {{ $stocks->links() }}
                </div>
                @endif
            </div>
        </div>

        <!-- Help Modal -->
        <div x-show="showHelpModal" x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-white rounded-lg shadow-xl max-w-4xl w-full mx-4 max-h-[90vh] overflow-y-auto">
                <div class="flex items-center justify-between p-6 border-b border-gray-200">
                    <h2 class="text-xl font-bold text-gray-900">Stock Management Help</h2>
                    <button @click="closeHelpModal()" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>

                <div class="p-6 space-y-6">
                    <!-- Keyboard Shortcuts -->
                    <div class="border border-gray-200 rounded-lg p-4">
                        <h3 class="text-lg font-semibold text-gray-900 mb-3">
                            <i class="fas fa-keyboard text-blue-600 mr-2"></i>Keyboard Shortcuts
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="space-y-2">
                                <div class="flex items-center justify-between">
                                    <span class="text-gray-700">New Stock</span>
                                    <kbd class="px-2 py-1 bg-gray-100 border border-gray-300 rounded text-sm">N</kbd>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="text-gray-700">Manual Entry</span>
                                    <kbd class="px-2 py-1 bg-gray-100 border border-gray-300 rounded text-sm">M</kbd>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="text-gray-700">Search</span>
                                    <kbd class="px-2 py-1 bg-gray-100 border border-gray-300 rounded text-sm">S</kbd>
                                </div>
                            </div>
                            <div class="space-y-2">
                                <div class="flex items-center justify-between">
                                    <span class="text-gray-700">Navigate Down</span>
                                    <kbd class="px-2 py-1 bg-gray-100 border border-gray-300 rounded text-sm">↓</kbd>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="text-gray-700">Navigate Up</span>
                                    <kbd class="px-2 py-1 bg-gray-100 border border-gray-300 rounded text-sm">↑</kbd>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="text-gray-700">View Details</span>
                                    <kbd class="px-2 py-1 bg-gray-100 border border-gray-300 rounded text-sm">Enter</kbd>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Features -->
                    <div class="border border-gray-200 rounded-lg p-4">
                        <h3 class="text-lg font-semibold text-gray-900 mb-3">
                            <i class="fas fa-cogs text-green-600 mr-2"></i>Features & Actions
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="space-y-3">
                                <div>
                                    <h4 class="font-medium text-gray-900">Stock Overview</h4>
                                    <p class="text-sm text-gray-600">View total products, quantities, sold items, and available stock.</p>
                                </div>
                                <div>
                                    <h4 class="font-medium text-gray-900">Manual Entry</h4>
                                    <p class="text-sm text-gray-600">Add stock manually without purchase orders.</p>
                                </div>
                            </div>
                            <div class="space-y-3">
                                <div>
                                    <h4 class="font-medium text-gray-900">Stock Status</h4>
                                    <p class="text-sm text-gray-600">Visual indicators for stock levels: In Stock, Low Stock, Critical.</p>
                                </div>
                                <div>
                                    <h4 class="font-medium text-gray-900">Product Details</h4>
                                    <p class="text-sm text-gray-600">Click on any row to view detailed stock information.</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Tips -->
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                        <h3 class="text-lg font-semibold text-blue-900 mb-3">
                            <i class="fas fa-lightbulb text-blue-600 mr-2"></i>Pro Tips
                        </h3>
                        <div class="space-y-2 text-sm text-blue-800">
                            <div>• Use keyboard shortcuts for faster navigation</div>
                            <div>• Monitor stock levels with color-coded status indicators</div>
                            <div>• Search by product name, supplier, or unit type</div>
                            <div>• Manual entries get automatic batch code generation</div>
                        </div>
                    </div>
                </div>

                <div class="flex items-center justify-end px-6 py-4 bg-gray-50 border-t border-gray-200">
                    <button @click="closeHelpModal()"
                        class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                        Got it!
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Floating Action Button for Mobile -->
    <div class="fixed bottom-6 right-6 md:hidden">
        <div class="flex flex-col space-y-2">
            <a href="{{ route('stocks.manual.create') }}"
                class="w-12 h-12 bg-green-600 hover:bg-green-700 text-white rounded-full shadow-lg flex items-center justify-center transition-colors">
                <i class="fas fa-plus text-sm"></i>
            </a>
            <a href="{{ route('stocks.create') }}"
                class="w-12 h-12 bg-blue-600 hover:bg-blue-700 text-white rounded-full shadow-lg flex items-center justify-center transition-colors">
                <i class="fas fa-boxes text-sm"></i>
            </a>
        </div>
    </div>

    <script>
        function stockManager() {
            return {
                showHelpModal: false,
                selectedRowIndex: -1,
                totalRows: 0,

                init() {
                    this.totalRows = document.querySelectorAll('.stock-row').length;
                    this.setupKeyboardShortcuts();
                },

                setupKeyboardShortcuts() {
                    document.addEventListener('keydown', (e) => {
                        // Ignore if user is typing in an input field
                        if (e.target.tagName === 'INPUT' || e.target.tagName === 'TEXTAREA') {
                            return;
                        }

                        // Handle Ctrl+N for new stock
                        if (e.ctrlKey && e.key.toLowerCase() === 'n') {
                            e.preventDefault();
                            window.location.href = '{{ route("stocks.create") }}';
                            return;
                        }

                        switch (e.key.toLowerCase()) {
                            case 'n':
                                e.preventDefault();
                                window.location.href = '{{ route("stocks.create") }}';
                                break;
                            case 'm':
                                e.preventDefault();
                                window.location.href = '{{ route("stocks.manual.create") }}';
                                break;
                            case 's':
                                e.preventDefault();
                                document.getElementById('searchInput').focus();
                                break;
                            case 'h':
                                e.preventDefault();
                                this.showHelpModal = true;
                                break;
                            case 'escape':
                                this.closeHelpModal();
                                break;
                            case 'arrowdown':
                                e.preventDefault();
                                this.navigateDown();
                                break;
                            case 'arrowup':
                                e.preventDefault();
                                this.navigateUp();
                                break;
                            case 'enter':
                                e.preventDefault();
                                this.viewSelectedStock();
                                break;
                        }
                    });
                },

                navigateDown() {
                    if (this.selectedRowIndex < this.totalRows - 1) {
                        this.selectedRowIndex++;
                        this.scrollToSelectedRow();
                    }
                },

                navigateUp() {
                    if (this.selectedRowIndex > 0) {
                        this.selectedRowIndex--;
                        this.scrollToSelectedRow();
                    }
                },

                scrollToSelectedRow() {
                    const selectedRow = document.querySelector(`.stock-row:nth-child(${this.selectedRowIndex + 1})`);
                    if (selectedRow) {
                        selectedRow.scrollIntoView({
                            behavior: 'smooth',
                            block: 'center'
                        });
                    }
                },

                viewSelectedStock() {
                    if (this.selectedRowIndex >= 0) {
                        const selectedRow = document.querySelector(`.stock-row:nth-child(${this.selectedRowIndex + 1})`);
                        if (selectedRow) {
                            const stockId = selectedRow.dataset.stockId;
                            window.location.href = `/stocks/${stockId}`;
                        }
                    }
                },

                closeHelpModal() {
                    this.showHelpModal = false;
                }
            }
        }
    </script>
</x-app-layout>
