<x-app-layout>
    <x-slot name="title">
        {{ __('Stock Reports') }} - {{ config('app.name', 'IMS') }}
    </x-slot>

    <div class="bg-white min-h-screen" x-data="stockReportsManager()" x-init="init()">
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
                            <a href="{{ route('reports.index') }}"
                                class="text-sm font-medium text-gray-700 hover:text-blue-600">
                                Reports
                            </a>
                        </div>
                    </li>
                    <li aria-current="page">
                        <div class="flex items-center">
                            <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                            <span class="text-sm font-medium text-gray-500">Stock Reports</span>
                        </div>
                    </li>
                </ol>
            </nav>
        </div>

        <!-- Header -->
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Stock Reports</h1>
                    <p class="text-sm text-gray-600 mt-1">Monitor inventory levels and stock movements</p>
                </div>
                <div class="flex items-center space-x-3">
                    <!-- Help Button -->
                    <button @click="showHelpModal = true"
                        class="inline-flex items-center px-3 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium rounded-lg transition-colors">
                        <i class="fas fa-question-circle w-4 h-4 mr-2"></i>
                        Help
                    </button>
                    <!-- Back Button -->
                    <a href="{{ route('reports.index') }}"
                        class="inline-flex items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium rounded-lg transition-colors">
                        <i class="fas fa-arrow-left w-4 h-4 mr-2"></i>
                        Back to Reports
                    </a>
                </div>
            </div>
        </div>

        <!-- Content -->
        <div class="p-6">
            <!-- Statistics Summary -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
                <div class="bg-white border border-gray-200 rounded-lg p-6 shadow-sm">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-boxes text-blue-600 text-2xl"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500">Total Stock Items</p>
                            <p class="text-2xl font-bold text-gray-900">{{ $stocks->count() }}</p>
                        </div>
                    </div>
                </div>
                <div class="bg-white border border-gray-200 rounded-lg p-6 shadow-sm">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-cubes text-green-600 text-2xl"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500">Total Quantity</p>
                            <p class="text-2xl font-bold text-gray-900">{{ number_format($stocks->sum('quantity')) }}
                            </p>
                        </div>
                    </div>
                </div>
                <div class="bg-white border border-gray-200 rounded-lg p-6 shadow-sm">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-chart-line text-purple-600 text-2xl"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500">Total Sold</p>
                            <p class="text-2xl font-bold text-gray-900">{{ number_format($stocks->sum('sold')) }}</p>
                        </div>
                    </div>
                </div>
                <div class="bg-white border border-gray-200 rounded-lg p-6 shadow-sm">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-warehouse text-orange-600 text-2xl"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500">Available</p>
                            <p class="text-2xl font-bold text-gray-900">
                                {{ number_format($stocks->sum('quantity') - $stocks->sum('sold')) }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filters and Actions -->
            <div class="bg-white border border-gray-200 rounded-lg p-6 mb-6 shadow-sm">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">
                    <i class="fas fa-filter text-blue-600 mr-2"></i>
                    Filters & Export Options
                </h3>
                <div class="flex flex-wrap items-center gap-4">
                    <div class="flex-1 min-w-[300px]">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Search Stock</label>
                        <input type="text" x-model="searchQuery" @input="filterStocks()"
                            placeholder="Search by product name, supplier, or batch code..."
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>
                    <div class="flex items-end space-x-3">
                        <button @click="showDownloadModal = true"
                            class="inline-flex items-center px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white text-sm font-medium rounded-lg transition-colors">
                            <i class="fas fa-download w-4 h-4 mr-2"></i>
                            Download Report
                        </button>
                        <button @click="printReport()"
                            class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white text-sm font-medium rounded-lg transition-colors">
                            <i class="fas fa-print w-4 h-4 mr-2"></i>
                            Print
                        </button>
                    </div>
                </div>
            </div>

            <!-- Stock Table -->
            <div class="bg-white border border-gray-200 rounded-lg shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                    <h3 class="text-lg font-semibold text-gray-900">
                        <i class="fas fa-table text-blue-600 mr-2"></i>
                        Stock Inventory
                    </h3>
                </div>
                <div class="overflow-x-auto">
                    @if ($stocks->isEmpty())
                        <div class="px-6 py-8 text-center">
                            <i class="fas fa-boxes text-gray-400 text-4xl mb-4"></i>
                            <h4 class="text-lg font-medium text-gray-900 mb-2">No stock found</h4>
                            <p class="text-gray-500">No stock records match your search criteria.</p>
                        </div>
                    @else
                        <table class="min-w-full divide-y divide-gray-200" id="stockTable">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100"
                                        @click="sortTable(0)">
                                        <div class="flex items-center space-x-1">
                                            <span>#</span>
                                            <i class="fas fa-sort text-gray-400"></i>
                                        </div>
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100"
                                        @click="sortTable(1)">
                                        <div class="flex items-center space-x-1">
                                            <span>Product</span>
                                            <i class="fas fa-sort text-gray-400"></i>
                                        </div>
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100"
                                        @click="sortTable(2)">
                                        <div class="flex items-center space-x-1">
                                            <span>Supplier</span>
                                            <i class="fas fa-sort text-gray-400"></i>
                                        </div>
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Batch Code
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider text-right">
                                        Quantity
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider text-right">
                                        Sold
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider text-right">
                                        Available
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Status
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200" id="stockTableBody">
                                @foreach ($stocks as $index => $stock)
                                    @php
                                        $available = $stock->quantity - $stock->sold;
                                        $stockPercentage =
                                            $stock->quantity > 0 ? ($available / $stock->quantity) * 100 : 0;
                                    @endphp
                                    <tr class="hover:bg-gray-50 transition-colors">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                            {{ $index + 1 }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div>
                                                    <div class="text-sm font-medium text-gray-900">
                                                        {{ $stock->product?->name ?? 'N/A' }}</div>
                                                    @if ($stock->product?->sku)
                                                        <div class="text-sm text-gray-500">SKU:
                                                            {{ $stock->product->sku }}</div>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">{{ $stock->supplier?->name ?? 'N/A' }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">{{ $stock->batch_code }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right">
                                            <div class="text-sm font-semibold text-gray-900">
                                                {{ number_format($stock->quantity) }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right">
                                            <div class="text-sm font-semibold text-red-600">
                                                {{ number_format($stock->sold) }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right">
                                            <div class="text-sm font-semibold text-green-600">
                                                {{ number_format($available) }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if ($stockPercentage > 50)
                                                <span
                                                    class="inline-flex items-center px-2 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800">
                                                    <i class="fas fa-check-circle mr-1"></i>
                                                    In Stock
                                                </span>
                                            @elseif($stockPercentage > 20)
                                                <span
                                                    class="inline-flex items-center px-2 py-1 text-xs font-medium rounded-full bg-yellow-100 text-yellow-800">
                                                    <i class="fas fa-exclamation-triangle mr-1"></i>
                                                    Low Stock
                                                </span>
                                            @elseif($available > 0)
                                                <span
                                                    class="inline-flex items-center px-2 py-1 text-xs font-medium rounded-full bg-orange-100 text-orange-800">
                                                    <i class="fas fa-exclamation-circle mr-1"></i>
                                                    Critical
                                                </span>
                                            @else
                                                <span
                                                    class="inline-flex items-center px-2 py-1 text-xs font-medium rounded-full bg-red-100 text-red-800">
                                                    <i class="fas fa-times-circle mr-1"></i>
                                                    Out of Stock
                                                </span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>
            </div>
        </div>

        <!-- Download Modal -->
        <div x-show="showDownloadModal"
            class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50"
            x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
            <div class="relative top-20 mx-auto p-5 border w-full max-w-2xl shadow-lg rounded-md bg-white">
                <div class="mt-3">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-medium text-gray-900">Filter & Download Stock Report</h3>
                        <button @click="showDownloadModal = false" class="text-gray-400 hover:text-gray-600">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                    <form x-ref="downloadForm" method="GET">
                        <div class="space-y-4">
                            <!-- Supplier Selection -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Select Suppliers:</label>
                                <div
                                    class="grid grid-cols-2 gap-2 max-h-32 overflow-y-auto border border-gray-300 p-3 rounded-lg bg-gray-50">
                                    @foreach ($suppliers as $supplier)
                                        <label class="inline-flex items-center space-x-2">
                                            <input type="checkbox" name="supplier_ids[]" value="{{ $supplier->id }}"
                                                class="form-checkbox text-purple-600 bg-white border-gray-300 rounded focus:ring-purple-500">
                                            <span class="text-sm text-gray-700">{{ $supplier->name }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>

                            <!-- Product Selection -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Select Products:</label>
                                <div
                                    class="grid grid-cols-2 gap-2 max-h-32 overflow-y-auto border border-gray-300 p-3 rounded-lg bg-gray-50">
                                    @foreach ($products as $product)
                                        <label class="inline-flex items-center space-x-2">
                                            <input type="checkbox" name="product_ids[]" value="{{ $product->id }}"
                                                class="form-checkbox text-purple-600 bg-white border-gray-300 rounded focus:ring-purple-500">
                                            <span class="text-sm text-gray-700">{{ $product->name }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>

                            <!-- Date Range -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Date Range:</label>
                                <select x-model="selectedRange" @change="toggleCustomDates()" required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                    <option value="">Select Range</option>
                                    <option value="last_7_days">Last 7 Days</option>
                                    <option value="last_15_days">Last 15 Days</option>
                                    <option value="last_30_days">Last 30 Days</option>
                                    <option value="custom">Custom Range</option>
                                </select>
                            </div>

                            <!-- Custom Dates -->
                            <div x-show="selectedRange === 'custom'" class="flex gap-4">
                                <div class="flex-1">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Start Date</label>
                                    <input type="date" x-model="startDate"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" />
                                </div>
                                <div class="flex-1">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">End Date</label>
                                    <input type="date" x-model="endDate"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" />
                                </div>
                            </div>
                        </div>

                        <div class="flex justify-end gap-4 mt-6">
                            <button type="button" @click="submitDownload('pdf')"
                                class="inline-flex items-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg transition-colors">
                                <i class="fas fa-file-pdf w-4 h-4 mr-2"></i>
                                Download PDF
                            </button>
                            <button type="button" @click="submitDownload('excel')"
                                class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-lg transition-colors">
                                <i class="fas fa-file-excel w-4 h-4 mr-2"></i>
                                Download Excel
                            </button>
                            <button type="button" @click="showDownloadModal = false"
                                class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white text-sm font-medium rounded-lg transition-colors">
                                Cancel
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Help Modal -->
        <div x-show="showHelpModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50"
            x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
            <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
                <div class="mt-3">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-medium text-gray-900">Stock Reports Help</h3>
                        <button @click="showHelpModal = false" class="text-gray-400 hover:text-gray-600">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                    <div class="space-y-4 text-sm text-gray-600">
                        <div>
                            <h4 class="font-semibold text-gray-900 mb-2">Stock Status:</h4>
                            <ul class="space-y-1">
                                <li>• <span class="text-green-600 font-medium">In Stock:</span> >50% available</li>
                                <li>• <span class="text-yellow-600 font-medium">Low Stock:</span> 20-50% available</li>
                                <li>• <span class="text-orange-600 font-medium">Critical:</span>
                                    <20% available</li>
                                <li>• <span class="text-red-600 font-medium">Out of Stock:</span> 0% available</li>
                            </ul>
                        </div>
                        <div>
                            <h4 class="font-semibold text-gray-900 mb-2">Advanced Filters:</h4>
                            <ul class="space-y-1">
                                <li>• Select specific suppliers and products</li>
                                <li>• Filter by date ranges for accurate reporting</li>
                                <li>• Export with customized data sets</li>
                            </ul>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            function stockReportsManager() {
                return {
                    showHelpModal: false,
                    showDownloadModal: false,
                    searchQuery: '',
                    selectedRange: '',
                    startDate: '',
                    endDate: '',
                    sortDirection: 'asc',
                    sortColumn: null,

                    init() {
                        this.bindKeyboardEvents();
                    },

                    bindKeyboardEvents() {
                        document.addEventListener('keydown', (e) => {
                            if (e.target.tagName === 'INPUT' || e.target.tagName === 'TEXTAREA' || e.target.tagName ===
                                'SELECT') {
                                return;
                            }

                            if (e.key.toLowerCase() === 's' && !e.ctrlKey && !e.altKey) {
                                e.preventDefault();
                                document.querySelector('input[x-model="searchQuery"]').focus();
                            }

                            if (e.key.toLowerCase() === 'd' && !e.ctrlKey && !e.altKey) {
                                e.preventDefault();
                                this.showDownloadModal = true;
                            }

                            if (e.key.toLowerCase() === 'p' && !e.ctrlKey && !e.altKey) {
                                e.preventDefault();
                                this.printReport();
                            }

                            if (e.key.toLowerCase() === 'h' && !e.ctrlKey && !e.altKey) {
                                e.preventDefault();
                                this.showHelpModal = true;
                            }

                            if (e.key === 'Escape' && !this.showHelpModal && !this.showDownloadModal) {
                                e.preventDefault();
                                window.location.href = '{{ route('reports.index') }}';
                            }
                        });
                    },

                    filterStocks() {
                        const query = this.searchQuery.toLowerCase();
                        const rows = document.querySelectorAll('#stockTableBody tr');

                        rows.forEach(row => {
                            const text = row.textContent.toLowerCase();
                            row.style.display = text.includes(query) ? '' : 'none';
                        });
                    },

                    sortTable(columnIndex) {
                        const table = document.getElementById('stockTable');
                        const tbody = table.querySelector('tbody');
                        const rows = Array.from(tbody.querySelectorAll('tr'));

                        if (this.sortColumn === columnIndex) {
                            this.sortDirection = this.sortDirection === 'asc' ? 'desc' : 'asc';
                        } else {
                            this.sortDirection = 'asc';
                            this.sortColumn = columnIndex;
                        }

                        rows.sort((a, b) => {
                            const aText = a.cells[columnIndex].textContent.trim();
                            const bText = b.cells[columnIndex].textContent.trim();

                            if (this.sortDirection === 'asc') {
                                return aText.localeCompare(bText);
                            } else {
                                return bText.localeCompare(aText);
                            }
                        });

                        rows.forEach(row => tbody.appendChild(row));
                    },

                    submitDownload(type) {
                        const form = this.$refs.downloadForm;
                        const formData = new FormData(form);

                        formData.append('range', this.selectedRange);
                        if (this.selectedRange === 'custom') {
                            formData.append('start_date', this.startDate);
                            formData.append('end_date', this.endDate);
                        }

                        const action = type === 'pdf' ? '{{ route('reports.stocks.pdf') }}' :
                            '{{ route('reports.stocks.excel') }}';
                        const params = new URLSearchParams(formData).toString();

                        window.open(`${action}?${params}`, '_blank');
                        this.showDownloadModal = false;
                    },

                    printReport() {
                        window.print();
                    }
                }
            }
        </script>
    @endpush
</x-app-layout>
