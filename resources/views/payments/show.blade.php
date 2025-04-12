<x-app-layout>
    <x-slot name="title">
        Payment Details - {{ config('app.name', 'ATMS') }}
    </x-slot>

    <div class="py-6 mt-20 ml-4 sm:ml-64">
        <div class="w-full mx-auto max-w-7xl sm:px-6 lg:px-8">
            <x-bread-crumb-navigation />

            <div class="bg-gray-800 text-gray-300 p-6 rounded-lg shadow-xl">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-3xl font-bold text-gray-200">Payment Details</h2>
                    <div class="flex gap-2">
                        <a href="{{ route('payments.index') }}"
                            class="flex items-center px-4 py-2 text-white bg-gray-700 rounded-lg hover:bg-gray-600 transition">
                            Back
                        </a>
                        @if ($payment->status !== 'paid')
                            <a href="{{ route('payments.create', $payment->id) }}"
                                class="flex items-center px-4 py-2 text-white bg-blue-500 rounded-lg hover:bg-blue-600 transition">
                                Add Payment
                            </a>
                        @endif
                    </div>
                </div>

                <hr class="my-6 border-gray-600">
                
                <table class="w-auto text-left table-auto mt-2">
                    <tr>
                        <th class="  py-2">Invoice No</th>
                        <td class="px-4 py-2">
                            <a href="{{ route('invoices.show', $payment->invoice->id) }}"
                                class="text-blue-400 hover:text-blue-600 transition duration-300">
                                {{ $payment->invoice->invoice_no ?? 'N/A' }}
                            </a>
                        </td>
                    </tr>
                    <tr>
                        <th class="  py-2">Customer Name</th>
                        <td class="px-4 py-2">{{ $payment->invoice->customer->company_name ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th class="  py-2">GST Number</th>
                        <td class="px-4 py-2">{{ $payment->invoice->customer->gst_number ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th class="  py-2">Contact Person</th>
                        <td class="px-4 py-2">
                            @foreach ($payment->invoice->customer->contactPersons as $contactPerson)
                                @if ($contactPerson->id == $payment->invoice->contactperson_id)
                                    <div>
                                        <p> {{ $contactPerson->name }}</p>
                                        <p> {{ $contactPerson->phone_no }}</p>
                                    </div>
                                @endif
                            @endforeach
                        </td>
                    </tr>
                    <tr>
                        <th class="  py-2">Total Amount</th>
                        <td class="px-4 py-2">₹{{ number_format($payment->total_amount, 2) }}</td>
                    </tr>
                    <tr>
                        <th class="  py-2">Paid Amount</th>
                        <td class="px-4 py-2">₹{{ number_format($payment->paid_amount, 2) }}</td>
                    </tr>
                    <tr>
                        <th class="  py-2">Pending Amount</th>
                        <td class="px-4 py-2">₹{{ number_format($payment->pending_amount, 2) }}</td>
                    </tr>
                    <tr>
                        <th class="  py-2">Status</th>
                        <td class="px-4 py-2">
                            <span
                                class="{{ $payment->status === 'paid' ? 'text-green-400' : ($payment->status === 'unpaid' ? 'text-red-400' : 'text-yellow-400') }}">
                                {{ $payment->status }}
                            </span>
                        </td>
                    </tr>
                </table>

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
                                        <td class="px-6 py-4">
                                            {{ \Carbon\Carbon::parse($item->payment_date)->format('d-m-Y') }}</td>
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
                                            <form action="{{ route('payments.destroy', $item->id) }}" method="POST"
                                                class="inline-block">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="text-red-400 hover:text-red-600 transition duration-300"
                                                    title="Delete"
                                                    onclick="return confirm('Are you sure you want to delete this payment item?');">
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

                
            </div>
        </div>
    </div>
</x-app-layout>
