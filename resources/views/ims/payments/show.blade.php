<x-app-layout>
    <x-slot name="title">
        Payment Details - {{ config('app.name', 'ATMS') }}
    </x-slot>

    <div class="bg-white min-h-screen" x-data="{ activeTab: 'overview' }">
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
                            <a href="{{ route('payments.index') }}"
                                class="text-sm font-medium text-gray-700 hover:text-blue-600">
                                Payments
                            </a>
                        </div>
                    </li>
                    <li aria-current="page">
                        <div class="flex items-center">
                            <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                            <span class="text-sm font-medium text-gray-500">Payment #{{ $payment->id }}</span>
                        </div>
                    </li>
                </ol>
            </nav>
        </div>

        <!-- Header -->
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Payment Details</h1>
                    <p class="text-sm text-gray-600 mt-1">View payment summary, invoice and payment items</p>
                </div>
                <div class="flex items-center space-x-3">
                    <a href="#"
                        class="inline-flex items-center px-3 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium rounded-lg transition-colors">
                        <i class="fas fa-question-circle w-4 h-4 mr-2"></i>
                        Help
                    </a>

                    @if ($payment->status !== 'paid')
                        <a href="{{ route('payments.create', $payment->id) }}"
                            class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors">
                            <i class="fas fa-plus w-4 h-4 mr-2"></i>
                            Add Payment
                        </a>
                    @endif

                    <a href="{{ route('payments.index') }}"
                        class="inline-flex items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium rounded-lg transition-colors">
                        <i class="fas fa-arrow-left w-4 h-4 mr-2"></i>
                        Back to List
                    </a>
                </div>
            </div>
        </div>

        <!-- Content -->
        <div class="p-6">
            <!-- Payment Profile Card -->
            <div class="bg-white border border-gray-200 rounded-lg shadow-sm overflow-hidden mb-6">
                <div class="bg-gradient-to-r from-gray-800 to-gray-700 px-6 py-8 text-white">
                    <div class="flex items-center">
                        <div class="w-16 h-16 bg-white bg-opacity-10 rounded-xl flex items-center justify-center mr-4">
                            <i class="fas fa-receipt text-3xl"></i>
                        </div>
                        <div>
                            <h2 class="text-2xl font-bold">Invoice: {{ $payment->invoice->invoice_no ?? 'N/A' }}</h2>
                            <p class="text-gray-200 mt-1">Invoice Date: {{ \Carbon\Carbon::parse($payment->invoice->invoice_date)->format('d-m-Y') ?? 'N/A' }}</p>
                            <div class="mt-2">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-white bg-opacity-10 text-white">
                                    Status:
                                    <span class="ml-2 {{ $payment->status === 'paid' ? 'text-green-300' : ($payment->status === 'unpaid' ? 'text-red-300' : 'text-yellow-300') }}">
                                        {{ ucfirst($payment->status) }}
                                    </span>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-4">
                            <span class="text-sm font-medium text-gray-700">Quick Actions:</span>
                            <a href="{{ route('invoices.show', $payment->invoice->id ?? '#') }}"
                                class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                <i class="fas fa-file-alt mr-1"></i> View Invoice
                            </a>
                        </div>
                        <div class="text-sm text-gray-700">
                            <span class="font-medium">Total:</span> ₹{{ number_format($payment->total_amount, 2) }}
                            <span class="mx-2">|</span>
                            <span class="font-medium">Paid:</span> ₹{{ number_format($payment->paid_amount, 2) }}
                            <span class="mx-2">|</span>
                            <span class="font-medium">Pending:</span> ₹{{ number_format($payment->pending_amount, 2) }}
                        </div>
                    </div>
                </div>

                <!-- Details Grid -->
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="md:col-span-2">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Customer & Invoice</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-500 mb-1">Customer</label>
                                    <p class="text-sm text-gray-900">
                                        <a href="#"
                                           class="text-blue-600 hover:underline">{{ $payment->invoice->customer->company_name ?? 'N/A' }}</a>
                                    </p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-500 mb-1">GST Number</label>
                                    <p class="text-sm text-gray-900">{{ $payment->invoice->customer->gst_number ?? 'N/A' }}</p>
                                </div>
                                <div class="md:col-span-2">
                                    <label class="block text-sm font-medium text-gray-500 mb-1">Contact Person</label>
                                    @php
                                        $contact = $payment->invoice->customer->contactPersons->firstWhere('id', $payment->invoice->contactperson_id);
                                    @endphp
                                    @if($contact)
                                        <p class="text-sm text-gray-900">{{ $contact->name }} — {{ $contact->phone_no }}</p>
                                    @else
                                        <p class="text-sm text-gray-900">N/A</p>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Status & Dates</h3>
                            <div class="space-y-3">
                                <div class="flex justify-between items-center">
                                    <span class="text-sm font-medium text-gray-500">Invoice Date</span>
                                    <span class="text-sm text-gray-900">{{ \Carbon\Carbon::parse($payment->invoice->invoice_date)->format('d-m-Y') ?? 'N/A' }}</span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-sm font-medium text-gray-500">Last Payment</span>
                                    <span class="text-sm text-gray-900">
                                        @if($payment->items->isNotEmpty())
                                            {{ \Carbon\Carbon::parse($payment->items->last()->payment_date)->format('d-m-Y') }}
                                        @else
                                            N/A
                                        @endif
                                    </span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-sm font-medium text-gray-500">Created</span>
                                    <span class="text-sm text-gray-900">{{ \Carbon\Carbon::parse($payment->created_at)->diffForHumans() }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tabs -->
            <div class="bg-white border border-gray-200 rounded-lg shadow-sm overflow-hidden">
                <div class="px-6 border-b border-gray-200">
                    <nav class="-mb-px flex space-x-8">
                        <button @click="activeTab = 'overview'"
                            :class="activeTab === 'overview' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                            class="py-4 px-1 border-b-2 font-medium text-sm">
                            <i class="fas fa-info-circle mr-2"></i> Overview
                        </button>
                        <button @click="activeTab = 'items'"
                            :class="activeTab === 'items' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                            class="py-4 px-1 border-b-2 font-medium text-sm">
                            <i class="fas fa-list mr-2"></i> Payment Items
                        </button>
                        <button @click="activeTab = 'invoice'"
                            :class="activeTab === 'invoice' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                            class="py-4 px-1 border-b-2 font-medium text-sm">
                            <i class="fas fa-file-invoice mr-2"></i> Invoice
                        </button>
                    </nav>
                </div>

                <div class="p-6">
                    <!-- Overview Tab -->
                    <div x-show="activeTab === 'overview'" x-transition>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="bg-white border border-gray-200 rounded-lg p-6">
                                <h3 class="text-lg font-semibold text-gray-900 mb-4">Payment Summary</h3>
                                <div class="space-y-4">
                                    <div class="flex justify-between">
                                        <span class="text-sm text-gray-500">Total Amount</span>
                                        <span class="text-sm font-medium text-gray-900">₹{{ number_format($payment->total_amount, 2) }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-sm text-gray-500">Paid Amount</span>
                                        <span class="text-sm font-medium text-gray-900">₹{{ number_format($payment->paid_amount, 2) }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-sm text-gray-500">Pending Amount</span>
                                        <span class="text-sm font-medium text-gray-900">₹{{ number_format($payment->pending_amount, 2) }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-sm text-gray-500">Status</span>
                                        <span class="text-sm font-medium {{ $payment->status === 'paid' ? 'text-green-600' : ($payment->status === 'unpaid' ? 'text-red-600' : 'text-yellow-600') }}">
                                            {{ ucfirst($payment->status) }}
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <div class="bg-white border border-gray-200 rounded-lg p-6">
                                <h3 class="text-lg font-semibold text-gray-900 mb-4">Customer Contact</h3>
                                <div class="space-y-3">
                                    <p class="text-sm text-gray-900 font-medium">{{ $payment->invoice->customer->company_name ?? 'N/A' }}</p>
                                    <p class="text-sm text-gray-700">{{ $payment->invoice->customer->gst_number ?? 'GST: N/A' }}</p>
                                    @if($contact)
                                        <p class="text-sm text-gray-700">Contact: {{ $contact->name }} — {{ $contact->phone_no }}</p>
                                    @endif
                                    <a href="{{ route('invoices.show', $payment->invoice->id ?? '#') }}" class="text-sm text-blue-600 hover:underline">View Invoice Details</a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Items Tab -->
                    <div x-show="activeTab === 'items'" x-transition>
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-semibold text-gray-900">Payment Items</h3>
                            @if ($payment->status !== 'paid')
                                <a href="{{ route('payments.create', $payment->id) }}"
                                    class="inline-flex items-center px-3 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors">
                                    <i class="fas fa-plus mr-2"></i> Add Item
                                </a>
                            @endif
                        </div>

                        @if ($payment->items->isEmpty())
                            <p class="text-center text-gray-500">No payment items found.</p>
                        @else
                            <div class="overflow-x-auto">
                                <table class="min-w-full mt-4 text-left border-collapse table-auto">
                                    <thead>
                                        <tr class="text-sm text-gray-500 bg-gray-50 uppercase tracking-wider">
                                            <th class="px-6 py-3 border-b">#</th>
                                            <th class="px-6 py-3 border-b">Amount</th>
                                            <th class="px-6 py-3 border-b">Payment Date</th>
                                            <th class="px-6 py-3 border-b">Method</th>
                                            <th class="px-6 py-3 border-b">Reference</th>
                                            <th class="px-6 py-3 border-b text-center">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody class="text-sm text-gray-700 divide-y">
                                        @foreach ($payment->items as $item)
                                            <tr class="hover:bg-gray-50">
                                                <td class="px-6 py-4">{{ $loop->iteration }}</td>
                                                <td class="px-6 py-4">₹{{ number_format($item->amount, 2) }}</td>
                                                <td class="px-6 py-4">{{ \Carbon\Carbon::parse($item->payment_date)->format('d-m-Y') }}</td>
                                                <td class="px-6 py-4">{{ ucfirst($item->payment_method) }}</td>
                                                <td class="px-6 py-4">{{ $item->reference_number ?? 'N/A' }}</td>
                                                <td class="px-6 py-4 text-center">
                                                    <a href="{{ route('payments.edit', $item->id) }}"
                                                        class="text-yellow-500 hover:text-yellow-700 mr-3" title="Edit">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <form action="{{ route('payments.destroy', $item->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Delete this payment item?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="text-red-500 hover:text-red-700" title="Delete">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>

                    <!-- Invoice Tab -->
                    <div x-show="activeTab === 'invoice'" x-transition>
                        <div class="bg-white border border-gray-200 rounded-lg p-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Invoice Details</h3>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-500 mb-1">Invoice No</label>
                                    <p class="text-sm text-gray-900">
                                        <a href="{{ route('invoices.show', $payment->invoice->id ?? '#') }}" class="text-blue-600 hover:underline">
                                            {{ $payment->invoice->invoice_no ?? 'N/A' }}
                                        </a>
                                    </p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-500 mb-1">Invoice Date</label>
                                    <p class="text-sm text-gray-900">{{ \Carbon\Carbon::parse($payment->invoice->invoice_date)->format('d-m-Y') ?? 'N/A' }}</p>
                                </div>
                                <div class="md:col-span-2">
                                    <label class="block text-sm font-medium text-gray-500 mb-1">Billing Address</label>
                                    <p class="text-sm text-gray-900">{{ $payment->invoice->customer->company_name ?? 'N/A' }}</p>
                                    <p class="text-sm text-gray-700">{{ $payment->invoice->billing_address ?? '-' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div> <!-- end tabs -->
        </div>
    </div>

    <script>
        // No extra JS required — Alpine handles tab state
    </script>
</x-app-layout>
