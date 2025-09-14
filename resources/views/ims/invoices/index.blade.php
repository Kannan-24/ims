<x-app-layout>
    <x-slot name="title">
        {{ __('Invoice Management') }} - {{ config('app.name', 'IMS') }}
    </x-slot>

    <div class="bg-white min-h-screen" x-data="invoiceIndexManager()" x-init="init()">
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
                            <span class="text-sm font-medium text-gray-500">Invoices</span>
                        </div>
                    </li>
                </ol>
            </nav>
        </div>

        <!-- Header -->
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Invoice Management</h1>
                    <p class="text-sm text-gray-600 mt-1">Manage and track all your invoices</p>
                </div>
                <div class="flex items-center space-x-3">
                    <!-- Help Button -->
                    <button @click="showHelp = true"
                        class="inline-flex items-center px-3 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium rounded-lg transition-colors">
                        <i class="fas fa-question-circle w-4 h-4 mr-2"></i>
                        Help
                    </button>
                    <!-- New Invoice Button -->
                    <a href="{{ route('invoices.create') }}"
                       class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors">
                        <i class="fas fa-plus w-4 h-4 mr-2"></i>
                        New Invoice
                    </a>
                </div>
            </div>
        </div>

        <!-- Content -->
        <div class="p-6">
            <!-- Search and Filters -->
            <div class="bg-white border border-gray-200 rounded-lg shadow-sm mb-6">
                <div class="p-4">
                    <form method="GET" action="{{ route('invoices.index') }}" class="flex flex-wrap items-end gap-4">
                        <div class="flex-1 min-w-[260px]">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Search</label>
                            <input type="text" name="search" value="{{ request('search') }}"
                                   placeholder="Search by Invoice No or Company Name"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">From Date</label>
                            <input type="date" name="from" value="{{ request('from') }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">To Date</label>
                            <input type="date" name="to" value="{{ request('to') }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>
                        <div class="flex items-center space-x-2">
                            <button type="submit"
                                    class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium transition-colors">
                                <i class="fas fa-search mr-2"></i>Filter
                            </button>
                            <a href="{{ route('invoices.index') }}"
                               class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg font-medium transition-colors">
                                <i class="fas fa-times mr-2"></i>Reset
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Invoices Table -->
            <div class="bg-white border border-gray-200 rounded-lg shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <div class="flex items-center justify-between">
                        <h2 class="text-lg font-semibold text-gray-900">Invoices List</h2>
                        <div class="text-sm text-gray-500">
                            Total:
                            {{ ($invoices instanceof \Illuminate\Pagination\Paginator || $invoices instanceof \Illuminate\Pagination\LengthAwarePaginator) ? $invoices->total() : $invoices->count() }}
                            invoices
                        </div>
                    </div>
                </div>

                @if ($invoices->isEmpty())
                    <div class="text-center py-12">
                        <i class="fas fa-file-invoice text-gray-300 text-4xl mb-4"></i>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">No invoices found</h3>
                        <p class="text-gray-500 mb-6">Get started by creating your first invoice.</p>
                        <a href="{{ route('invoices.create') }}"
                           class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium">
                            <i class="fas fa-plus mr-2"></i>Create Invoice
                        </a>
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">#</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Invoice Details</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amounts</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach ($invoices as $invoice)
                                    <tr class="hover:bg-gray-50 transition-colors">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ ($invoices instanceof \Illuminate\Pagination\LengthAwarePaginator || $invoices instanceof \Illuminate\Pagination\Paginator)
                                                ? ($loop->iteration + ($invoices->currentPage() - 1) * $invoices->perPage())
                                                : $loop->iteration }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                                                    <i class="fas fa-file-invoice text-blue-600"></i>
                                                </div>
                                                <div class="ml-4">
                                                    <div class="text-sm font-medium text-gray-900">#{{ $invoice->invoice_no }}</div>
                                                    <div class="text-xs inline-flex px-2 py-0.5 rounded-full bg-gray-100 text-gray-700 mt-1">
                                                        {{ \Illuminate\Support\Carbon::parse($invoice->invoice_date)->format('d M Y') }}
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">{{ $invoice->customer->company_name }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-700">Sub Total: ₹{{ number_format($invoice->sub_total, 2) }}</div>
                                            <div class="text-sm font-semibold text-gray-900">Final: ₹{{ number_format($invoice->total, 2) }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <div class="flex items-center justify-end space-x-3">
                                                <a href="{{ route('emails.create') }}?invoice_id={{ $invoice->id }}"
                                                   class="text-green-600 hover:text-green-900 transition-colors"
                                                   title="Email Invoice">
                                                    <i class="fas fa-envelope"></i>
                                                </a>
                                                <button onclick="generateDeliveryChallan('{{ $invoice->id }}')"
                                                        class="text-purple-600 hover:text-purple-900 transition-colors"
                                                        title="Create Delivery Challan">
                                                    <i class="fas fa-truck"></i>
                                                </button>
                                                <a href="{{ route('invoices.pdf', $invoice->id) }}"
                                                   class="text-blue-600 hover:text-blue-900 transition-colors"
                                                   title="Download PDF" target="_blank">
                                                    <i class="fas fa-file-pdf"></i>
                                                </a>
                                                <a href="{{ route('invoices.show', $invoice) }}"
                                                   class="text-indigo-600 hover:text-indigo-900 transition-colors"
                                                   title="View Details">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('invoices.edit', $invoice) }}"
                                                   class="text-yellow-600 hover:text-yellow-900 transition-colors"
                                                   title="Edit Invoice">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form action="{{ route('invoices.destroy', $invoice) }}" method="POST" class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                            class="text-red-600 hover:text-red-900 transition-colors"
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
                    </div>

                    <!-- Pagination -->
                    @if (method_exists($invoices, 'hasPages') && $invoices->hasPages())
                        <div class="px-6 py-4 border-t border-gray-200">
                            {{ $invoices->links() }}
                        </div>
                    @endif
                @endif
            </div>
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
                        <h4 class="font-semibold text-gray-900 mb-2">Managing Invoices</h4>
                        <ul class="list-disc list-inside space-y-1 text-sm">
                            <li>View all invoices with details</li>
                            <li>Search by invoice number or company name</li>
                            <li>Filter by date range</li>
                            <li>Download PDF, email, and generate delivery challans</li>
                        </ul>
                    </div>
                    <div>
                        <h4 class="font-semibold text-gray-900 mb-2">Actions</h4>
                        <ul class="list-disc list-inside space-y-1 text-sm">
                            <li>Email Invoice</li>
                            <li>Create Delivery Challan</li>
                            <li>Download PDF</li>
                            <li>View, Edit, Delete</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script>
        function invoiceIndexManager() {
            return {
                showHelp: false,
                init() {}
            }
        }

        function generateDeliveryChallan(invoiceId) {
            if (confirm('Generate delivery challan for this invoice?')) {
                fetch('/ims/delivery-challans/generate', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({ invoice_id: invoiceId })
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
