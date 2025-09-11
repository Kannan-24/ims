<x-app-layout>
    <x-slot name="title">
        {{ __('Customer Reports') }} - {{ config('app.name', 'IMS') }}
    </x-slot>

    <div class="bg-white min-h-screen" x-data="customerReportsManager()" x-init="init()">
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
                            <span class="text-sm font-medium text-gray-500">Customer Reports</span>
                        </div>
                    </li>
                </ol>
            </nav>
        </div>

        <!-- Header -->
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Customer Reports</h1>
                    <p class="text-sm text-gray-600 mt-1">Generate and export comprehensive customer reports</p>
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
            <!-- Filters and Actions -->
            <div class="bg-white border border-gray-200 rounded-lg p-6 mb-6 shadow-sm">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">
                    <i class="fas fa-filter text-blue-600 mr-2"></i>
                    Filters & Export Options
                </h3>
                <div class="flex flex-wrap items-center gap-4">
                    <div class="flex-1 min-w-[300px]">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Search Customers</label>
                        <input type="text" x-model="searchQuery" @input="filterCustomers()"
                            placeholder="Search by company name, email, or contact person..."
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>
                    <div class="flex items-end space-x-3">
                        <a href="{{ route('reports.customer.excel') }}"
                            class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-lg transition-colors">
                            <i class="fas fa-file-excel w-4 h-4 mr-2"></i>
                            Export Excel
                        </a>
                        <a href="{{ route('reports.customer.pdf') }}" target="_blank"
                            class="inline-flex items-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg transition-colors">
                            <i class="fas fa-file-pdf w-4 h-4 mr-2"></i>
                            Export PDF
                        </a>
                        <button @click="printReport()"
                            class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white text-sm font-medium rounded-lg transition-colors">
                            <i class="fas fa-print w-4 h-4 mr-2"></i>
                            Print
                        </button>
                    </div>
                </div>
            </div>

            <!-- Customer Table -->
            <div class="bg-white border border-gray-200 rounded-lg shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                    <h3 class="text-lg font-semibold text-gray-900">
                        <i class="fas fa-table text-blue-600 mr-2"></i>
                        Customer Directory
                    </h3>
                </div>
                <div class="overflow-x-auto">
                    @if ($customers->isEmpty())
                        <div class="px-6 py-8 text-center">
                            <i class="fas fa-users text-gray-400 text-4xl mb-4"></i>
                            <h4 class="text-lg font-medium text-gray-900 mb-2">No customers found</h4>
                            <p class="text-gray-500">No customer records match your search criteria.</p>
                        </div>
                    @else
                        <table class="min-w-full divide-y divide-gray-200" id="customerTable">
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
                                            <span>Company Name</span>
                                            <i class="fas fa-sort text-gray-400"></i>
                                        </div>
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Address
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        GST Details
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Actions
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200" id="customerTableBody">
                                @foreach ($customers as $index => $customer)
                                    <tr class="hover:bg-gray-50 transition-colors">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                            {{ $index + 1 }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">{{ $customer->company_name }}
                                            </div>
                                            @if ($customer->contactPersons->count() > 0)
                                                <div class="text-sm text-gray-500">
                                                    Primary: {{ $customer->contactPersons->first()->name }}
                                                </div>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="text-sm text-gray-900">
                                                {{ $customer->address }}
                                            </div>
                                            <div class="text-sm text-gray-500">
                                                {{ $customer->city }}, {{ $customer->state }}
                                                {{ $customer->zip_code }}
                                            </div>
                                            <div class="text-sm text-gray-500">
                                                {{ $customer->country }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if ($customer->gst_number)
                                                <span
                                                    class="inline-flex items-center px-2 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800">
                                                    {{ $customer->gst_number }}
                                                </span>
                                            @else
                                                <span
                                                    class="inline-flex items-center px-2 py-1 text-xs font-medium rounded-full bg-gray-100 text-gray-800">
                                                    No GST
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <a href="{{ route('customers.show', $customer) }}"
                                                class="text-blue-600 hover:text-blue-900 mr-3">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('customers.edit', $customer) }}"
                                                class="text-indigo-600 hover:text-indigo-900">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif
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
                        <h3 class="text-lg font-medium text-gray-900">Customer Reports Help</h3>
                        <button @click="showHelpModal = false" class="text-gray-400 hover:text-gray-600">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                    <div class="space-y-4 text-sm text-gray-600">
                        <div>
                            <h4 class="font-semibold text-gray-900 mb-2">Search & Filter:</h4>
                            <ul class="space-y-1">
                                <li>• Use the search box to find customers by name, email, or contact</li>
                                <li>• Click column headers to sort data</li>
                                <li>• Results update automatically as you type</li>
                            </ul>
                        </div>
                        <div>
                            <h4 class="font-semibold text-gray-900 mb-2">Export Options:</h4>
                            <ul class="space-y-1">
                                <li>• Export Excel: Download as spreadsheet for analysis</li>
                                <li>• Export PDF: Generate printable customer directory</li>
                                <li>• Print: Quick print current view</li>
                            </ul>
                        </div>
                        <div>
                            <h4 class="font-semibold text-gray-900 mb-2">Keyboard Shortcuts:</h4>
                            <div class="space-y-2">
                                <div class="flex justify-between">
                                    <span>Search</span>
                                    <kbd class="px-2 py-1 bg-gray-100 border border-gray-300 rounded text-xs">S</kbd>
                                </div>
                                <div class="flex justify-between">
                                    <span>Export Excel</span>
                                    <kbd class="px-2 py-1 bg-gray-100 border border-gray-300 rounded text-xs">E</kbd>
                                </div>
                                <div class="flex justify-between">
                                    <span>Export PDF</span>
                                    <kbd class="px-2 py-1 bg-gray-100 border border-gray-300 rounded text-xs">P</kbd>
                                </div>
                                <div class="flex justify-between">
                                    <span>Back to Reports</span>
                                    <kbd class="px-2 py-1 bg-gray-100 border border-gray-300 rounded text-xs">Esc</kbd>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            function customerReportsManager() {
                return {
                    showHelpModal: false,
                    searchQuery: '',
                    sortDirection: 'asc',
                    sortColumn: null,

                    init() {
                        this.bindKeyboardEvents();
                    },

                    bindKeyboardEvents() {
                        document.addEventListener('keydown', (e) => {
                            // Don't trigger shortcuts when typing in inputs
                            if (e.target.tagName === 'INPUT' || e.target.tagName === 'TEXTAREA' || e.target.tagName ===
                                'SELECT') {
                                return;
                            }

                            // Search focus - S key
                            if (e.key.toLowerCase() === 's' && !e.ctrlKey && !e.altKey) {
                                e.preventDefault();
                                document.querySelector('input[x-model="searchQuery"]').focus();
                            }

                            // Export Excel - E key
                            if (e.key.toLowerCase() === 'e' && !e.ctrlKey && !e.altKey) {
                                e.preventDefault();
                                window.location.href = '{{ route('reports.customer.excel') }}';
                            }

                            // Export PDF - P key
                            if (e.key.toLowerCase() === 'p' && !e.ctrlKey && !e.altKey) {
                                e.preventDefault();
                                window.open('{{ route('reports.customer.pdf') }}', '_blank');
                            }

                            // Show help - H key
                            if (e.key.toLowerCase() === 'h' && !e.ctrlKey && !e.altKey) {
                                e.preventDefault();
                                this.showHelpModal = true;
                            }

                            // Back to reports - Escape key
                            if (e.key === 'Escape' && !this.showHelpModal) {
                                e.preventDefault();
                                window.location.href = '{{ route('reports.index') }}';
                            }
                        });
                    },

                    filterCustomers() {
                        const query = this.searchQuery.toLowerCase();
                        const rows = document.querySelectorAll('#customerTableBody tr');

                        rows.forEach(row => {
                            const text = row.textContent.toLowerCase();
                            row.style.display = text.includes(query) ? '' : 'none';
                        });
                    },

                    sortTable(columnIndex) {
                        const table = document.getElementById('customerTable');
                        const tbody = table.querySelector('tbody');
                        const rows = Array.from(tbody.querySelectorAll('tr'));

                        // Toggle sort direction
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

                    printReport() {
                        window.print();
                    }
                }
            }
        </script>
    @endpush
</x-app-layout>
