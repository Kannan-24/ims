<x-app-layout>
    <x-slot name="title">
        {{ __('Payment List') }} - {{ config('app.name', 'ATMS') }}
    </x-slot>

    <div class="bg-white min-h-screen" x-data="paymentIndexManager()" x-init="init()">
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
                            <span class="text-sm font-medium text-gray-500">Payments</span>
                        </div>
                    </li>
                </ol>
            </nav>
        </div>

        <!-- Header -->
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Payments</h1>
                    <p class="text-sm text-gray-600 mt-1">List of invoices and their payment statuses</p>
                </div>
                <div class="flex items-center space-x-3">
                    <a href="#"
                       class="inline-flex items-center px-3 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium rounded-lg transition-colors">
                        <i class="fas fa-question-circle w-4 h-4 mr-2"></i>
                        Help
                    </a>
                </div>
            </div>
        </div>

        <!-- Content -->
        <div class="p-6">
            <!-- Search & Filters -->
            <div class="bg-white border border-gray-200 rounded-lg shadow-sm mb-6">
                <div class="p-4">
                    <form method="GET" action="{{ route('payments.index') }}" class="flex flex-wrap items-center gap-4">
                        <div class="flex-1 min-w-[300px]">
                            <input type="text" name="search" value="{{ request('search') }}"
                                   placeholder="Search Invoice No or Company Name"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>

                        <input type="date" name="from" value="{{ request('from') }}"
                               class="px-3 py-2 border border-gray-300 rounded-lg">

                        <input type="date" name="to" value="{{ request('to') }}"
                               class="px-3 py-2 border border-gray-300 rounded-lg">

                        <div class="flex items-center space-x-2">
                            <button type="submit"
                                    class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium transition-colors">
                                <i class="fas fa-search mr-2"></i>Filter
                            </button>
                            <a href="{{ route('payments.index') }}"
                               class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg font-medium transition-colors">
                                <i class="fas fa-times mr-2"></i>Reset
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Payments Table -->
            <div class="bg-white border border-gray-200 rounded-lg shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <div class="flex items-center justify-between">
                        <h2 class="text-lg font-semibold text-gray-900">Payments List</h2>
                        <div class="text-sm text-gray-500">
                            Total: {{ $payments->total() }} payments
                        </div>
                    </div>
                </div>

                @if ($payments->isEmpty())
                    <div class="text-center py-12">
                        <i class="fas fa-receipt text-gray-300 text-4xl mb-4"></i>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">No payments found</h3>
                        <p class="text-gray-500 mb-6">Try adjusting your filters or create a new payment.</p>
                        <a href="{{ route('payments.create') }}"
                           class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium">
                            <i class="fas fa-plus mr-2"></i>Add Payment
                        </a>
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">#</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Invoice</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Paid</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Pending</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach ($payments as $payment)
                                    <tr class="hover:bg-gray-50 transition-colors">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $loop->iteration + ($payments->currentPage() - 1) * $payments->perPage() }}
                                        </td>

                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="w-10 h-10 bg-gradient-to-r from-blue-500 to-purple-600 rounded-full flex items-center justify-center">
                                                    <span class="text-white font-semibold text-sm">
                                                        {{ strtoupper(substr(optional($payment->invoice)->invoice_no ?? 'NA', 0, 2)) }}
                                                    </span>
                                                </div>
                                                <div class="ml-4">
                                                    <div class="text-sm font-medium text-gray-900">
                                                        {{ optional($payment->invoice)->invoice_no ?? 'N/A' }}
                                                    </div>
                                                    <div class="text-sm text-gray-500">Invoice ID: {{ $payment->invoice_id ?? 'N/A' }}</div>
                                                </div>
                                            </div>
                                        </td>

                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ optional(optional($payment->invoice)->invoice_date) ? \Carbon\Carbon::parse($payment->invoice->invoice_date)->format('d-m-Y') : 'N/A' }}
                                        </td>

                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ optional(optional($payment->invoice)->customer)->company_name ?? 'N/A' }}
                                            <div class="text-sm text-gray-500">{{ optional(optional($payment->invoice)->customer)->contact_person ?? '' }}</div>
                                        </td>

                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-right text-gray-900">
                                            ₹{{ number_format($payment->total_amount ?? 0, 2) }}
                                        </td>

                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-right text-gray-900">
                                            ₹{{ number_format($payment->paid_amount ?? 0, 2) }}
                                        </td>

                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-right text-gray-900">
                                            ₹{{ number_format($payment->pending_amount ?? 0, 2) }}
                                        </td>

                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @php
                                                $status = $payment->status ?? 'unknown';
                                                $badge = $status === 'paid' ? 'bg-green-100 text-green-800' : ($status === 'unpaid' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800');
                                            @endphp
                                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $badge }}">
                                                {{ ucfirst($status) }}
                                            </span>
                                        </td>

                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <div class="flex items-center justify-end space-x-3">
                                                <a href="{{ route('payments.show', $payment) }}" class="text-blue-600 hover:text-blue-900" title="View">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    @if($payments->hasPages())
                        <div class="px-6 py-4 border-t border-gray-200">
                            {{ $payments->links() }}
                        </div>
                    @endif
                @endif
            </div>
        </div>
    </div>

    <script>
        function paymentIndexManager() {
            return {
                init() {
                    // placeholder for future interactions
                }
            }
        }
    </script>
</x-app-layout>
