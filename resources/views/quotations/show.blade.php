<x-app-layout>
    <x-slot name="title">
        {{ __('Purchase Details') }} - {{ config('app.name', 'ATMS') }}
    </x-slot>

    <div class="py-6 mt-20 sm:ml-64 px-6">
        <div class="max-w-5xl mx-auto">
            <x-bread-crumb-navigation />

            <div class="p-8 bg-white border border-gray-200 rounded-lg shadow-md">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-3xl font-bold text-gray-900">Purchase Details</h2>
                    <div class="flex gap-3">
                        <a href="{{ route('purchases.edit', $purchase->id) }}"
                            class="px-4 py-2 text-sm font-medium text-white bg-green-500 rounded-md hover:bg-green-600 transition">
                            Edit
                        </a>
                        <form action="{{ route('purchases.destroy', $purchase->id) }}" method="POST"
                            onsubmit="return confirm('Are you sure you want to delete this purchase?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                class="px-4 py-2 text-sm font-medium text-white bg-red-500 rounded-md hover:bg-red-600 transition">
                                Delete
                            </button>
                        </form>
                    </div>
                </div>

                <hr class="my-2 border-gray-200">

                <div>
                    <h3 class="text-xl font-semibold text-gray-800 mb-2">Purchase Details</h3>

                    <div class="text-lg text-gray-700 space-y-1">
                        <p><span class="font-semibold">Invoice No:</span> {{ $purchase->invoice_no }}</p>
                        <p><span class="font-semibold">Purchase Date:</span> {{ $purchase->invoice_date }}</p>
                    </div>

                    <h3 class="text-xl font-semibold text-gray-800 mt-2">Supplier Details</h3>

                    <table class="w-1/2 text-gray-700 text-lg">
                        <tbody>
                            <tr>
                                <td class="font-semibold">Name:</td>
                                <td>{{ $purchase->supplier->name }}</td>
                            </tr>
                            <tr>
                                <td class="font-semibold">Email:</td>
                                <td>{{ $purchase->supplier->email }}</td>
                            </tr>
                            <tr>
                                <td class="font-semibold">Phone:</td>
                                <td>{{ $purchase->supplier->phone_number }}</td>
                            </tr>
                            <tr>
                                <td class="font-semibold align-top">Address:</td>
                                <td>
                                    <div>
                                        {{ $purchase->supplier->address }},
                                        {{ $purchase->supplier->city }} -
                                        {{ $purchase->supplier->postal_code }},
                                        <br>
                                        {{ $purchase->supplier->state }},
                                        {{ $purchase->supplier->country }}
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>


                <hr class="my-4 border-gray-200">


                @if ($purchase->items->isEmpty())
                    <p class="text-gray-600">No products found for this purchase.</p>
                @else
                    <div class="overflow-x-auto">
                        <table class="min-w-full bg-white border border-gray-200 rounded-lg shadow-sm">
                            <thead class="bg-indigo-500 text-white text-lg">
                                <tr>
                                    <th class="px-6 py-4 text-left">#</th>
                                    <th class="px-6 py-4 text-left">Product Name</th>
                                    <th class="px-6 py-4 text-left">Quantity</th>
                                    <th class="px-6 py-4 text-left">Unit Price</th>
                                    <th class="px-6 py-4 text-left">CGST</th>
                                    <th class="px-6 py-4 text-left">SGST</th>
                                    <th class="px-6 py-4 text-left">IGST</th>
                                    <th class="px-6 py-4 text-left">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($purchase->items as $item)
                                    <tr class="border-t hover:bg-gray-50 text-lg">
                                        <td class="px-6 py-4">{{ $loop->iteration }}</td>
                                        <td class="px-6 py-4">{{ $item->product->name }}</td>
                                        <td class="px-6 py-4">{{ $item->quantity }}</td>
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
                <div class="mt-8 flex justify-start">
                    <table class="w-1/2 bg-white border border-gray-200 rounded-lg shadow-sm text-lg">
                        <tbody>
                            <tr class="border-t">
                                <td class="px-4 py-2 font-semibold text-gray-700">Subtotal:</td>
                                <td class="px-4 py-2 text-gray-700">₹{{ number_format($purchase->sub_total, 2) }}</td>
                            </tr>
                            <tr class="border-t">
                                <td class="px-4 py-2 font-semibold text-gray-700">CGST:</td>
                                <td class="px-4 py-2 text-gray-700">₹{{ number_format($purchase->cgst, 2) }}</td>
                            </tr>
                            <tr class="border-t">
                                <td class="px-4 py-2 font-semibold text-gray-700">SGST:</td>
                                <td class="px-4 py-2 text-gray-700">₹{{ number_format($purchase->sgst, 2) }}</td>
                            </tr>
                            <tr class="border-t">
                                <td class="px-4 py-2 font-semibold text-gray-700">IGST:</td>
                                <td class="px-4 py-2 text-gray-700">₹{{ number_format($purchase->igst, 2) }}</td>
                            </tr>
                            <tr class="border-t">
                                <td class="px-4 py-2 font-bold text-xl text-gray-700">Grand Total:</td>
                                <td class="px-4 py-2 font-bold text-xl text-gray-700">
                                    ₹{{ number_format($purchase->total, 2) }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
