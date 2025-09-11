<x-app-layout>
    <x-slot name="title">
        {{ __('Invoice Reports') }} - {{ config('app.name', 'IMS') }}
    </x-slot>

    <div class="bg-white min-h-screen" x-data="invoiceReports()">
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
                            <span class="text-sm font-medium text-gray-500">Invoice Reports</span>
                        </div>
                    </li>
                </ol>
            </nav>
        </div>

        <!-- Header -->
        <div class="px-6 py-6 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Invoice Reports</h1>
                    <p class="text-lg text-gray-600 mt-2">Monitor and analyze invoice data with comprehensive insights
                    </p>
                </div>
                <div class="flex items-center space-x-3">
                    <!-- Help Button -->
                    <button @click="showHelp = true"
                        class="inline-flex items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium rounded-lg transition-colors">
                        <i class="fas fa-question-circle w-4 h-4 mr-2"></i>
                        Help
                    </button>
                    <!-- Download Button -->
                    <button @click="showDownloadModal = true"
                        class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors">
                        <i class="fas fa-download w-4 h-4 mr-2"></i>
                        Download Report
                    </button>
                </div>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="px-6 py-6">
            <!-- Search and Filter Bar -->
            <div class="bg-white border border-gray-200 rounded-lg p-4 mb-6 shadow-sm">
                <div class="flex flex-col sm:flex-row gap-4">
                    <div class="flex-1">
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-search text-gray-400"></i>
                            </div>
                            <input type="text" x-model="searchTerm"
                                placeholder="Search invoices by number, customer, or amount..."
                                class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>
                    </div>
                    
                </div>
            </div>

            <!-- Invoice Table -->
            <div class="bg-white border border-gray-200 rounded-lg shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    @if ($invoices->isEmpty())
                        <div class="text-center py-12">
                            <div
                                class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                <i class="fas fa-file-invoice text-gray-400 text-3xl"></i>
                            </div>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">No invoices found</h3>
                            <p class="text-gray-600">There are no invoices to display at this time.</p>
                        </div>
                    @else
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        #
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Invoice Number
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Customer
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Invoice Date
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Action
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach ($invoices as $index => $invoice)
                                    <tr class="hover:bg-gray-50 transition-colors"
                                        x-show="invoiceMatchesSearch({{ json_encode([
                                            'invoice_no' => $invoice->invoice_no,
                                            'customer_name' => $invoice->customer->company_name ?? '',
                                            'amount' => number_format($invoice->total_amount, 2),
                                        ]) }})">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ $loop->iteration }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div
                                                    class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center mr-3">
                                                    <i class="fas fa-file-invoice text-blue-600 text-sm"></i>
                                                </div>
                                                <span
                                                    class="text-sm font-medium text-gray-900">{{ $invoice->invoice_no }}</span>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">
                                                {{ $invoice->customer->company_name ?? 'N/A' }}</div>
                                            @if ($invoice->customer && $invoice->customer->email)
                                                <div class="text-sm text-gray-500">{{ $invoice->customer->email }}
                                                </div>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ \Carbon\Carbon::parse($invoice->invoice_date)->format('d M Y') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <a href="{{ route('invoices.show', $invoice) }}"
                                                class="text-blue-600 hover:text-blue-900 mr-3">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('invoices.edit', $invoice) }}"
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

        <!-- Download Modal -->
        <div x-show="showDownloadModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
            class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50" style="display: none;">
            <div x-show="showDownloadModal" x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0 transform scale-95"
                x-transition:enter-end="opacity-100 transform scale-100" x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100 transform scale-100"
                x-transition:leave-end="opacity-0 transform scale-95"
                class="bg-white rounded-lg p-6 w-full max-w-md mx-4">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-900">Download Invoice Report</h3>
                    <button @click="showDownloadModal = false" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times"></i>
                    </button>
                </div>

                <form @submit.prevent="downloadReport()">
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Date Range</label>
                        <select x-model="downloadRange"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Select Range</option>
                            <option value="last_7_days">Last 7 Days</option>
                            <option value="last_15_days">Last 15 Days</option>
                            <option value="last_30_days">Last 30 Days</option>
                            <option value="custom">Custom Range</option>
                        </select>
                    </div>

                    <div x-show="downloadRange === 'custom'" class="mb-4">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Start Date</label>
                                <input type="date" x-model="startDate"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">End Date</label>
                                <input type="date" x-model="endDate"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            </div>
                        </div>
                    </div>

                    <div class="flex gap-3 justify-end">
                        <button type="button" @click="downloadReport('pdf')"
                            class="inline-flex items-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg transition-colors">
                            <i class="fas fa-file-pdf mr-2"></i>
                            PDF
                        </button>
                        <button type="button" @click="downloadReport('excel')"
                            class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-lg transition-colors">
                            <i class="fas fa-file-excel mr-2"></i>
                            Excel
                        </button>
                        <button type="button" @click="showDownloadModal = false"
                            class="inline-flex items-center px-4 py-2 bg-gray-300 hover:bg-gray-400 text-gray-700 text-sm font-medium rounded-lg transition-colors">
                            Cancel
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Help Modal -->
        <div x-show="showHelp" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
            class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50" style="display: none;">
            <div x-show="showHelp" x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0 transform scale-95"
                x-transition:enter-end="opacity-100 transform scale-100" x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100 transform scale-100"
                x-transition:leave-end="opacity-0 transform scale-95"
                class="bg-white rounded-lg p-6 w-full max-w-2xl mx-4 max-h-[80vh] overflow-y-auto">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-900">Invoice Reports Help</h3>
                    <button @click="showHelp = false" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times"></i>
                    </button>
                </div>

                <div class="space-y-6">
                    <div>
                        <h4 class="font-semibold text-gray-900 mb-2">Overview</h4>
                        <p class="text-gray-600">Invoice reports provide comprehensive insights into your billing data,
                            including invoice amounts, customer information, and invoice status tracking.</p>
                    </div>

                    <div>
                        <h4 class="font-semibold text-gray-900 mb-2">Statistics Cards</h4>
                        <ul class="space-y-2 text-gray-600">
                            <li>• <strong>Total Invoices:</strong> Count of all invoices in the system</li>
                            <li>• <strong>Total Amount:</strong> Sum of all invoice amounts</li>
                            <li>• <strong>Unique Customers:</strong> Number of distinct customers</li>
                            <li>• <strong>Average Value:</strong> Average invoice amount</li>
                        </ul>
                    </div>

                    <div>
                        <h4 class="font-semibold text-gray-900 mb-2">Status Indicators</h4>
                        <div class="space-y-2">
                            <div class="flex items-center">
                                <span
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 mr-3">
                                    Recent
                                </span>
                                <span class="text-gray-600">Invoices created within the last 7 days</span>
                            </div>
                            <div class="flex items-center">
                                <span
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 mr-3">
                                    Current
                                </span>
                                <span class="text-gray-600">Invoices created within the last 30 days</span>
                            </div>
                            <div class="flex items-center">
                                <span
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 mr-3">
                                    Archived
                                </span>
                                <span class="text-gray-600">Invoices older than 30 days</span>
                            </div>
                        </div>
                    </div>

                    <div>
                        <h4 class="font-semibold text-gray-900 mb-2">Keyboard Shortcuts</h4>
                        <div class="grid grid-cols-2 gap-4">
                            <div class="space-y-2">
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Focus Search</span>
                                    <kbd class="px-2 py-1 bg-gray-100 border border-gray-300 rounded text-xs">S</kbd>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Download Report</span>
                                    <kbd class="px-2 py-1 bg-gray-100 border border-gray-300 rounded text-xs">D</kbd>
                                </div>
                            </div>
                            <div class="space-y-2">
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Show Help</span>
                                    <kbd class="px-2 py-1 bg-gray-100 border border-gray-300 rounded text-xs">H</kbd>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Close Modal</span>
                                    <kbd class="px-2 py-1 bg-gray-100 border border-gray-300 rounded text-xs">Esc</kbd>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function invoiceReports() {
            return {
                searchTerm: '',
                showDownloadModal: false,
                showHelp: false,
                downloadRange: '',
                startDate: '',
                endDate: '',

                invoiceMatchesSearch(invoice) {
                    if (!this.searchTerm) return true;

                    const searchLower = this.searchTerm.toLowerCase();
                    return invoice.invoice_no.toLowerCase().includes(searchLower) ||
                        invoice.customer_name.toLowerCase().includes(searchLower) ||
                        invoice.amount.toLowerCase().includes(searchLower);
                },

                downloadReport(format) {
                    if (!this.downloadRange) {
                        alert('Please select a date range');
                        return;
                    }

                    if (this.downloadRange === 'custom' && (!this.startDate || !this.endDate)) {
                        alert('Please select both start and end dates');
                        return;
                    }

                    const form = document.createElement('form');
                    form.method = 'GET';
                    form.target = '_blank';

                    if (format === 'pdf') {
                        form.action = "{{ route('reports.invoices.pdf') }}";
                    } else {
                        form.action = "{{ route('reports.invoices.excel') }}";
                    }

                    // Add range parameter
                    const rangeInput = document.createElement('input');
                    rangeInput.type = 'hidden';
                    rangeInput.name = 'range';
                    rangeInput.value = this.downloadRange;
                    form.appendChild(rangeInput);

                    // Add custom dates if selected
                    if (this.downloadRange === 'custom') {
                        const startInput = document.createElement('input');
                        startInput.type = 'hidden';
                        startInput.name = 'start_date';
                        startInput.value = this.startDate;
                        form.appendChild(startInput);

                        const endInput = document.createElement('input');
                        endInput.type = 'hidden';
                        endInput.name = 'end_date';
                        endInput.value = this.endDate;
                        form.appendChild(endInput);
                    }

                    document.body.appendChild(form);
                    form.submit();
                    document.body.removeChild(form);

                    this.showDownloadModal = false;
                },

                init() {
                    // Keyboard shortcuts
                    document.addEventListener('keydown', (e) => {
                        if (e.target.tagName === 'INPUT' || e.target.tagName === 'TEXTAREA') return;

                        switch (e.key.toLowerCase()) {
                            case 's':
                                e.preventDefault();
                                document.querySelector('input[x-model="searchTerm"]').focus();
                                break;
                            case 'd':
                                e.preventDefault();
                                this.showDownloadModal = true;
                                break;
                            case 'h':
                                e.preventDefault();
                                this.showHelp = true;
                                break;
                            case 'escape':
                                this.showDownloadModal = false;
                                this.showHelp = false;
                                break;
                        }
                    });
                }
            }
        }
    </script>
</x-app-layout>
