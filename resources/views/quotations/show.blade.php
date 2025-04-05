<x-app-layout>
    <x-slot name="title">
        {{ __('Quotation Details') }} - {{ config('app.name', 'ATMS') }}
    </x-slot>

    <div class="py-6 mt-20 ml-4 sm:ml-64">
        <div class="w-full mx-auto max-w-7xl sm:px-6 lg:px-8">
            <x-bread-crumb-navigation />

            <div class="p-8 bg-gray-800 border border-gray-700 rounded-lg shadow-lg relative">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-3xl font-bold text-gray-200">Quotation Details</h2>
                    <div class="flex gap-2">
                        <a href="{{ route('quotations.edit', $quotation->id) }}"
                            class="flex items-center px-4 py-2 text-white bg-green-500 rounded-lg hover:bg-green-600 transition">
                            Edit
                        </a>
                        <form action="{{ route('quotations.destroy', $quotation->id) }}" method="POST"
                            onsubmit="return confirm('Are you sure you want to delete this quotation?');" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                class="flex items-center px-4 py-2 text-white bg-red-500 rounded-lg hover:bg-red-600 transition">
                                Delete
                            </button>
                        </form>
                    </div>
                </div>

                <hr class="my-6 border-gray-600">

                <div class="space-y-4 text-gray-300">
                    <p><strong>Quotation Code:</strong> {{ $quotation->quotation_code }}</p>
                    <p><strong>Quotation Date:</strong> {{ $quotation->quotation_date }}</p>
                </div>

                <h3 class="text-2xl font-bold text-gray-200 mb-4 mt-8">Customer Details</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach ($quotation->customer->contactPersons as $contactPerson)
                    <div class="p-6 rounded-lg shadow-md bg-gray-700 border border-gray-600 text-gray-300 hover:bg-gray-600 transition">
                        <p class="text-lg font-semibold">{{ $contactPerson->name }}</p>
                        <p class="text-sm mt-1"><strong>Phone:</strong> {{ $contactPerson->phone_no }}</p>
                        <p class="text-sm"><strong>Email:</strong> {{ $contactPerson->email ?? 'N/A' }}</p>
                        <p><strong>Address:</strong> {{ $quotation->customer->address }},
                                {{ $quotation->customer->city }} - {{ $quotation->customer->zip_code }},
                                {{ $quotation->customer->state }}, {{ $quotation->customer->country }}</p>
                    </div>
                    @endforeach
                </div>

                <hr class="my-6 border-gray-600">

                @if ($quotation->items->isEmpty())
                    <p class="text-gray-400">No products found for this quotation.</p>
                @else
                    <div class="overflow-x-auto mt-8">
                        <table class="min-w-full bg-gray-800 border border-gray-700 rounded-lg shadow-sm">
                            <thead class="bg-gray-700 text-gray-300">
                                <tr>
                                    <th class="px-6 py-4 text-left">#</th>
                                    <th class="px-6 py-4 text-left">Product Name</th>
                                    <th class="px-6 py-4 text-left">Quantity</th>
                                    <th class="px-6 py-4 text-left">Unit Type</th>
                                    <th class="px-6 py-4 text-left">Unit Price</th>
                                    <th class="px-6 py-4 text-left">CGST</th>
                                    <th class="px-6 py-4 text-left">SGST</th>
                                    <th class="px-6 py-4 text-left">IGST</th>
                                    <th class="px-6 py-4 text-left">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($quotation->items as $item)
                                    <tr class="border-t border-gray-700 hover:bg-gray-700 text-gray-300">
                                        <td class="px-6 py-4">{{ $loop->iteration }}</td>
                                        <td class="px-6 py-4">{{ $item->product->name }}</td>
                                        <td class="px-6 py-4">{{ $item->quantity }}</td>
                                        <td class="px-6 py-4">{{ $item->product->unit_type }}</td>
                                        <td class="px-6 py-4">₹{{ number_format($item->unit_price, 2) }}</td>
                                        <td class="px-6 py-4">₹{{ number_format($item->cgst, 2) }}</td>
                                        <td class="px-6 py-4">₹{{ number_format($item->sgst, 2) }}</td>
                                        <td class="px-6 py-4">₹{{ number_format($item->igst, 2) }}</td>
                                        <td class="px-6 py-4">₹{{ number_format($item->total, 2) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif

                <div class="mt-8">
                    <h3 class="text-2xl font-bold text-gray-200 mb-4">Summary</h3>
                    <table class="w-full bg-gray-800 border border-gray-700 rounded-lg shadow-sm text-gray-300">
                        <tbody>
                            <tr class="border-t border-gray-700">
                                <td class="px-4 py-2 font-semibold">Subtotal:</td>
                                <td class="px-4 py-2">₹{{ number_format($quotation->sub_total, 2) }}</td>
                            </tr>
                            <tr class="border-t border-gray-700">
                                <td class="px-4 py-2 font-semibold">CGST:</td>
                                <td class="px-4 py-2">₹{{ number_format($quotation->cgst, 2) }}</td>
                            </tr>
                            <tr class="border-t border-gray-700">
                                <td class="px-4 py-2 font-semibold">SGST:</td>
                                <td class="px-4 py-2">₹{{ number_format($quotation->sgst, 2) }}</td>
                            </tr>
                            <tr class="border-t border-gray-700">
                                <td class="px-4 py-2 font-semibold">IGST:</td>
                                <td class="px-4 py-2">₹{{ number_format($quotation->igst, 2) }}</td>
                            </tr>
                            <tr class="border-t border-gray-700">
                                <td class="px-4 py-2 font-bold text-xl">Grand Total:</td>
                                <td class="px-4 py-2 font-bold text-xl">₹{{ number_format($quotation->total, 2) }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
