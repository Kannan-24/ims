<x-app-layout>
    <x-slot name="title">
        {{ __('Payment List') }} - {{ config('app.name', 'ATMS') }}
    </x-slot>

    <div class="py-6 mt-20 ml-4 sm:ml-64">
        <div class="w-full mx-auto max-w-7xl sm:px-6 lg:px-8">
            <x-bread-crumb-navigation />

            <div class="overflow-hidden bg-gray-800 rounded-lg shadow-xl">
                <div class="p-6 overflow-x-auto">
                    @if ($payments->isEmpty())
                        <div class="text-center text-gray-300">
                            {{ __('No payments found.') }}
                        </div>
                    @else
                        <table class="min-w-full text-left border-collapse table-auto">
                            <thead>
                                <tr class="text-sm text-gray-300 bg-gray-700 uppercase tracking-wider">
                                    <th class="px-6 py-4 border-b-2 border-gray-600">#</th>
                                    <th class="px-6 py-4 border-b-2 border-gray-600">Invoice No</th>
                                    <th class="px-6 py-4 border-b-2 border-gray-600">Customer</th>
                                    <th class="px-6 py-4 border-b-2 border-gray-600">Total Amount</th>
                                    <th class="px-6 py-4 border-b-2 border-gray-600">Paid Amount</th>
                                    <th class="px-6 py-4 border-b-2 border-gray-600">Pending Amount</th>
                                    <th class="px-6 py-4 border-b-2 border-gray-600">Payment Status</th>
                                    <th class="px-6 py-4 border-b-2 border-gray-600 text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="text-sm text-gray-300 divide-y divide-gray-700">
                                @foreach ($payments as $payment)
                                    <tr class="hover:bg-gray-700 transition duration-200">
                                        <td class="px-6 py-4">{{ $loop->iteration }}</td>
                                        <td class="px-6 py-4">{{ $payment->invoice->invoice_no ?? 'N/A' }}</td>
                                        <td class="px-6 py-4">{{ $payment->invoice->customer->company_name ?? 'N/A' }}
                                        </td>
                                        <td class="px-6 py-4">₹{{ number_format($payment->total_amount, 2) }}</td>
                                        <td class="px-6 py-4">₹{{ number_format($payment->paid_amount, 2) }}</td>
                                        <td class="px-6 py-4">₹{{ number_format($payment->pending_amount, 2) }}</td>
                                        <td class="px-6 py-4">
                                            <span
                                                class="{{ $payment->status === 'paid' ? 'text-green-400' : ($payment->status === 'unpaid' ? 'text-red-400' : 'text-yellow-400') }}">
                                                {{ $payment->status }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 flex justify-center gap-3">
                                            <a href="{{ route('payments.show', $payment) }}"
                                                class="text-blue-400 hover:text-blue-600 transition duration-300"
                                                title="View">
                                                <i class="fas fa-eye"></i>
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
    </div>
</x-app-layout>
