<x-app-layout>
    <x-slot name="title">
        {{ __('Invoice List') }} - {{ config('app.name', 'SKM') }}
    </x-slot>

    <!-- Main Content Section -->
    <div class="py-6 mt-20 ml-4 sm:ml-64" x-data="invoiceList()">
        <div class="w-full mx-auto max-w-7xl sm:px-6 lg:px-8">
            <!-- Breadcrumb Navigation -->
            <nav class="flex items-center space-x-2 text-sm text-gray-600 mb-6">
                <a href="{{ route('dashboard') }}" class="hover:text-blue-600 transition-colors">
                    <i class="fas fa-home"></i> Dashboard
                </a>
                <i class="fas fa-chevron-right text-gray-400"></i>
                <span class="text-gray-800 font-medium">📄 Invoices</span>
            </nav>

            <!-- Header Card -->
            <div class="bg-white rounded-lg shadow-md border border-gray-200 p-6 mb-6">
                <div class="flex justify-between items-center">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-800 flex items-center">
                            📄 Invoice Management
                        </h1>
                        <p class="text-gray-600 mt-1">Manage and track all your invoices</p>
                    </div>
                    <div class="flex gap-3">
                        <button @click="showHelp = true"
                            class="flex items-center px-4 py-2 text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors shadow-md">
                            <i class="fas fa-question-circle mr-2"></i>Help
                        </button>
                        <a href="{{ route('invoices.create') }}"
                            class="flex items-center px-4 py-2 text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition-colors shadow-md">
                            <i class="fas fa-plus mr-2"></i>New Invoice
                        </a>
                    </div>
                </div>
            </div>

            <!-- Statistics Cards -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
                <div class="bg-white rounded-lg shadow-md border border-gray-200 p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
                                <i class="fas fa-file-invoice text-blue-600"></i>
                            </div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Total Invoices</p>
                            <p class="text-2xl font-bold text-gray-900">{{ $invoices->count() }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow-md border border-gray-200 p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center">
                                <i class="fas fa-rupee-sign text-green-600"></i>
                            </div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Total Value</p>
                            <p class="text-2xl font-bold text-gray-900">₹{{ number_format($invoices->sum('total'), 2) }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow-md border border-gray-200 p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-orange-100 rounded-lg flex items-center justify-center">
                                <i class="fas fa-calendar text-orange-600"></i>
                            </div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">This Month</p>
                            <p class="text-2xl font-bold text-gray-900">{{ $invoices->where('invoice_date', '>=', now()->startOfMonth())->count() }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow-md border border-gray-200 p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center">
                                <i class="fas fa-clock text-purple-600"></i>
                            </div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Recent</p>
                            <p class="text-2xl font-bold text-gray-900">{{ $invoices->where('created_at', '>=', now()->subDays(7))->count() }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Search and Filter Section -->
            <div class="bg-white rounded-lg shadow-md border border-gray-200 p-6 mb-6">
                <form method="GET" action="{{ route('invoices.index') }}" class="grid grid-cols-1 md:grid-cols-5 gap-4">
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Search</label>
                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="Search by Invoice No or Company Name"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">From Date</label>
                        <input type="date" name="from" value="{{ request('from') }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">To Date</label>
                        <input type="date" name="to" value="{{ request('to') }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div class="flex items-end gap-2">
                        <button type="submit"
                            class="flex-1 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-semibold transition-colors">
                            <i class="fas fa-search mr-2"></i>Filter
                        </button>
                        <a href="{{ route('invoices.index') }}"
                            class="px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-lg font-semibold transition-colors">
                            <i class="fas fa-undo mr-2"></i>Reset
                        </a>
                    </div>
                </form>
            </div>

            <!-- Invoices Table -->
            <div class="bg-white rounded-lg shadow-md border border-gray-200 overflow-hidden">
                @if ($invoices->isEmpty())
                    <div class="text-center py-12">
                        <i class="fas fa-file-invoice text-gray-400 text-6xl mb-4"></i>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">No invoices found</h3>
                        <p class="text-gray-600">Get started by creating your first invoice.</p>
                        <a href="{{ route('invoices.create') }}"
                            class="inline-flex items-center px-4 py-2 mt-4 text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition-colors">
                            <i class="fas fa-plus mr-2"></i>Create Invoice
                        </a>
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer" onclick="sortTable(0)">
                                        #
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer" onclick="sortTable(1)">
                                        Date
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer" onclick="sortTable(2)">
                                        Invoice No
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Company Name</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Sub Total</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Final Amount</th>
                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200" id="invoiceTable">
                                @foreach ($invoices as $invoice)
                                    <tr class="hover:bg-gray-50 transition-colors">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $loop->iteration }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $invoice->invoice_date }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $invoice->invoice_no }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $invoice->customer->company_name }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">₹{{ number_format($invoice->sub_total, 2) }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900">₹{{ number_format($invoice->total, 2) }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                            <div class="flex justify-center gap-2">
                                                <a href="{{ route('emails.create') }}?invoice_id={{ $invoice->id }}"
                                                    class="text-green-600 hover:text-green-900 transition-colors p-1 rounded"
                                                    title="Email Invoice">
                                                    <i class="fas fa-envelope"></i>
                                                </a>
                                                <button onclick="generateDeliveryChallan('{{ $invoice->id }}')"
                                                    class="text-purple-600 hover:text-purple-900 transition-colors p-1 rounded"
                                                    title="Create Delivery Challan">
                                                    <i class="fas fa-truck"></i>
                                                </button>
                                                <a href="{{ route('invoices.pdf', $invoice->id) }}"
                                                    class="text-blue-600 hover:text-blue-900 transition-colors p-1 rounded"
                                                    title="Download PDF" target="_blank">
                                                    <i class="fas fa-file-pdf"></i>
                                                </a>
                                                <a href="{{ route('invoices.show', $invoice) }}"
                                                    class="text-indigo-600 hover:text-indigo-900 transition-colors p-1 rounded"
                                                    title="View Details">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('invoices.edit', $invoice) }}"
                                                    class="text-yellow-600 hover:text-yellow-900 transition-colors p-1 rounded"
                                                    title="Edit Invoice">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form action="{{ route('invoices.destroy', $invoice) }}" method="POST"
                                                    class="inline">
                                                    @csrf @method('DELETE')
                                                    <button type="submit"
                                                        class="text-red-600 hover:text-red-900 transition-colors p-1 rounded"
                                                        title="Delete Invoice"
                                                        onclick="return confirm('Are you sure you want to delete this invoice?')">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>
            </div>

            <!-- Help Modal -->
            <div x-show="showHelp" x-cloak
                class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50"
                @click.away="showHelp = false">
                <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-md bg-white">
                    <div class="mt-3">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                                <i class="fas fa-question-circle text-blue-600 mr-2"></i>
                                Invoice Management Help
                            </h3>
                            <button @click="showHelp = false" class="text-gray-400 hover:text-gray-600">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                        <div class="space-y-4 text-gray-700">
                            <div>
                                <h4 class="font-semibold text-gray-900 mb-2">📄 Managing Invoices</h4>
                                <ul class="list-disc list-inside space-y-1 text-sm">
                                    <li>View all invoices with detailed information</li>
                                    <li>Search by invoice number or company name</li>
                                    <li>Filter invoices by date range</li>
                                    <li>Track invoice statistics and totals</li>
                                </ul>
                            </div>
                            <div>
                                <h4 class="font-semibold text-gray-900 mb-2">🔧 Available Actions</h4>
                                <ul class="list-disc list-inside space-y-1 text-sm">
                                    <li><i class="fas fa-envelope text-green-600"></i> Email Invoice - Send invoice via email</li>
                                    <li><i class="fas fa-truck text-purple-600"></i> Delivery Challan - Generate delivery challan</li>
                                    <li><i class="fas fa-file-pdf text-blue-600"></i> PDF - Download invoice as PDF</li>
                                    <li><i class="fas fa-eye text-indigo-600"></i> View - See detailed invoice information</li>
                                    <li><i class="fas fa-edit text-yellow-600"></i> Edit - Modify invoice details</li>
                                    <li><i class="fas fa-trash text-red-600"></i> Delete - Remove invoice (careful!)</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script>
        function invoiceList() {
            return {
                showHelp: false
            }
        }

        function sortTable(columnIndex) {
            let table = document.getElementById("invoiceTable");
            let rows = Array.from(table.rows);
            let isAscending = table.dataset.sortOrder === "asc";

            rows.sort((a, b) => {
                let aText = a.cells[columnIndex].innerText.trim();
                let bText = b.cells[columnIndex].innerText.trim();
                return isAscending ? aText.localeCompare(bText) : bText.localeCompare(aText);
            });

            table.innerHTML = "";
            rows.forEach(row => table.appendChild(row));

            table.dataset.sortOrder = isAscending ? "desc" : "asc";
        }

        function generateDeliveryChallan(invoiceId) {
            if (confirm('Generate delivery challan for this invoice?')) {
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
                            alert('Delivery challan generated successfully!');
                            if (confirm('Would you like to view the generated delivery challan?')) {
                                window.open(data.pdf_url, '_blank');
                            }
                        } else {
                            alert('Failed to generate delivery challan: ' + (data.message || 'Unknown error'));
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('An error occurred while generating delivery challan');
                    });
            }
        }
    </script>
</x-app-layout>
