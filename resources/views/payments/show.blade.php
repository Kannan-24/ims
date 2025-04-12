<x-app-layout>
    <x-slot name="title">
        Payment Details - {{ config('app.name', 'ATMS') }}
    </x-slot>

    <div class="py-6 mt-20 ml-4 sm:ml-64">
        <div class="w-full mx-auto max-w-7xl sm:px-6 lg:px-8">
            <x-bread-crumb-navigation />

            <h2 class="text-3xl font-bold text-gray-200 mb-6">Payment Details</h2>

            <div class="bg-gray-800 text-gray-300 p-6 rounded-lg shadow-xl">
                <div class="mb-4">
                    <h3 class="text-2xl font-semibold text-gray-100">Invoice Details</h3>
                    <p><strong>Invoice No:</strong> {{ $payment->invoice->invoice_no ?? 'N/A' }}</p>
                    <p><strong>Customer Name:</strong> {{ $payment->invoice->customer->company_name ?? 'N/A' }}</p>
                    <p><strong>Total Amount:</strong> ₹{{ number_format($payment->total_amount, 2) }}</p>
                    <p><strong>Paid Amount:</strong> ₹{{ number_format($payment->paid_amount, 2) }}</p>
                    <p><strong>Pending Amount:</strong> ₹{{ number_format($payment->pending_amount, 2) }}</p>
                    <p><strong>Status:</strong> 
                        @if ($payment->pending_amount <= 0)
                            <span class="text-green-400">Paid</span>
                        @elseif ($payment->pending_amount > 0 && $payment->paid_amount > 0)
                            <span class="text-yellow-400">Partial</span>
                        @else
                            <span class="text-red-400">Unpaid</span>
                        @endif
                    </p>
                </div>

                <div class="mt-6">
                    <h3 class="text-2xl font-semibold text-gray-100">Payment Items</h3>

                    @if ($payment->items->isEmpty())
                        <p class="text-center text-gray-300">No payment items found.</p>
                    @else
                        <table class="min-w-full mt-4 text-left border-collapse table-auto">
                            <thead>
                                <tr class="text-sm text-gray-300 bg-gray-700 uppercase tracking-wider">
                                    <th class="px-6 py-4 border-b-2 border-gray-600">#</th>
                                    <th class="px-6 py-4 border-b-2 border-gray-600">Amount</th>
                                    <th class="px-6 py-4 border-b-2 border-gray-600">Payment Date</th>
                                    <th class="px-6 py-4 border-b-2 border-gray-600">Payment Method</th>
                                    <th class="px-6 py-4 border-b-2 border-gray-600">Reference Number</th>
                                    <th class="px-6 py-4 border-b-2 border-gray-600 text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="text-sm text-gray-300 divide-y divide-gray-700">
                                @foreach ($payment->items as $item)
                                    <tr class="hover:bg-gray-700 transition duration-200">
                                        <td class="px-6 py-4">{{ $loop->iteration }}</td>
                                        <td class="px-6 py-4">₹{{ number_format($item->amount, 2) }}</td>
                                        <td class="px-6 py-4">{{ \Carbon\Carbon::parse($item->payment_date)->format('d-m-Y') }}</td>
                                        <td class="px-6 py-4">{{ ucfirst($item->payment_method) }}</td>
                                        <td class="px-6 py-4">{{ $item->reference_number ?? 'N/A' }}</td>
                                        <td class="px-6 py-4 text-center">
                                            <!-- Edit Button for each payment item -->
                                            <a href="{{ route('payments.edit', $item->id) }}"
                                                class="text-yellow-400 hover:text-yellow-600 transition duration-300"
                                                title="Edit">
                                                <i class="fas fa-edit"></i> 
                                            </a>

                                            <!-- Delete Button for each payment item -->
                                            <form action="{{ route('payments.destroy', $item->id) }}" method="POST" class="inline-block">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-400 hover:text-red-600 transition duration-300" title="Delete" onclick="return confirm('Are you sure you want to delete this payment item?');">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>

                @if ($payment->pending_amount > 0)
                    <div class="mt-6">
                        <a href="{{ route('payments.create', $payment->id) }}"
                           class="inline-flex items-center px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white font-semibold rounded-lg shadow-md transition">
                            Add Payment Item
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
