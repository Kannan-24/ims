<x-app-layout>
    <x-slot name="title">
        {{ __('Payment List') }} - {{ config('app.name', 'ATMS') }}
    </x-slot>

    <!-- Main Content Section -->
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
                                    <th class="px-6 py-4 border-b-2 border-gray-600 cursor-pointer"
                                        onclick="sortTable(0)">#</th>
                                    <th class="px-6 py-4 border-b-2 border-gray-600">Invoice No</th>
                                    <th class="px-6 py-4 border-b-2 border-gray-600">Customer Name</th>
                                    <th class="px-6 py-4 border-b-2 border-gray-600">Amount</th>
                                    <th class="px-6 py-4 border-b-2 border-gray-600">Invoice Date</th>
                                    <th class="px-6 py-4 border-b-2 border-gray-600">Status</th>
                                    <th class="px-6 py-4 border-b-2 border-gray-600 text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="text-sm text-gray-300 divide-y divide-gray-700" id="paymentTable">
                                @foreach ($payments as $payment)
                                    <tr class="hover:bg-gray-700 transition duration-200">
                                        <td class="px-6 py-4">{{ $loop->iteration }}</td>
                                        <td class="px-6 py-4">{{ $payment->invoice->invoice_no ?? '-' }}</td>
                                        <td class="px-6 py-4">{{ $payment->invoice->customer->company_name ?? '-' }}</td>
                                        <td class="px-6 py-4">₹{{ number_format($payment->amount, 2) }}</td>
                                        <td class="px-6 py-4">{{ $payment->invoice->invoice_date ?? '-' }}</td>
                                        <td class="px-6 py-4">
                                            @if ($payment->status === 'paid')
                                                <span
                                                    class="px-2 py-1 text-xs font-semibold text-green-800 bg-green-200 rounded">
                                                    {{ __('Paid') }}
                                                </span>
                                            @else
                                                <span
                                                    class="px-2 py-1 text-xs font-semibold text-red-800 bg-red-200 rounded">
                                                    {{ __('Not Paid') }}
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 flex justify-center gap-3">
                                            <a href="{{ route('payments.show', $payment) }}"
                                                class="text-blue-400 hover:text-blue-600 transition duration-300"
                                                title="View">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('payments.edit', $payment) }}"
                                                class="text-yellow-400 hover:text-yellow-600 transition duration-300"
                                                title="Edit">
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
    </div>
</x-app-layout>
