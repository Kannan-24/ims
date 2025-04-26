<x-app-layout>

    <x-slot name="title">
        {{ __('Stock Details') }} - {{ config('app.name', 'ATMS') }}
    </x-slot>

    <div class="py-6 mt-20 ml-4 sm:ml-64">
        <div class="w-full mx-auto max-w-7xl sm:px-6 lg:px-8">

            <x-bread-crumb-navigation />

            <div class="p-8 bg-gray-800 border border-gray-700 rounded-lg shadow-lg relative">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-3xl font-bold text-gray-200">Product Details</h2>
                </div>

                <hr class="my-6 border-gray-600">

                <div class="space-y-4 text-gray-300">
                    <p><strong>Product Name:</strong> {{ $stocks->first()->product->name ?? 'Stock Details' }}</p>
                    <p><strong>HSN Code:</strong> {{ $stocks->first()->product->hsn_code ?? 'N/A' }}</p>
                    <p><strong>Description:</strong> {{ $stocks->first()->product->description ?? 'N/A' }}</p>
                </div>

                <div class="mt-8">
                    <h3 class="text-2xl font-bold text-gray-200 mb-4">Stock Details</h3>

                    <table class="min-w-full text-left border-collapse table-auto mt-5 text-gray-300">
                        <thead>
                            <tr class="text-sm text-gray-200 bg-gray-700">
                                <th class="px-6 py-4 border-b-2 border-gray-600">#</th>
                                <th class="px-6 py-4 border-b-2 border-gray-600">Supplier</th>
                                <th class="px-6 py-4 border-b-2 border-gray-600">Unit Type</th>
                                <th class="px-6 py-4 border-b-2 border-gray-600">Quantity</th>
                                <th class="px-6 py-4 border-b-2 border-gray-600">Sold</th>
                                <th class="px-6 py-4 border-b-2 border-gray-600">Batch Code</th>
                                <th class="px-6 py-4 border-b-2 border-gray-600">Date Added</th>
                            </tr>
                        </thead>
                        <tbody class="text-sm text-gray-400">
                            @php
                                $remainingSold = 0;
                            @endphp
                            @foreach ($stocks as $stock)
                                @php
                                    $currentSold = $stock->sold + $remainingSold;
                                    $remainingSold = 0;

                                    if ($currentSold > $stock->quantity) {
                                        $remainingSold = $currentSold - $stock->quantity;
                                        $currentSold = $stock->quantity;
                                    }
                                @endphp
                                <tr class="border-b border-gray-600 hover:bg-gray-700">
                                    <td class="px-6 py-4">{{ $loop->iteration }}</td>
                                    <td class="px-6 py-4">
                                        <a href="{{ route('suppliers.show', $stock->supplier->id) }}" class="text-blue-400 hover:underline">
                                            {{ $stock->supplier->name ?? 'N/A' }}
                                        </a>
                                    </td>
                                    <td class="px-6 py-4">{{ $stock->unit_type }}</td>
                                    <td class="px-6 py-4">{{ $stock->quantity }}</td>
                                    <td class="px-6 py-4">{{ $currentSold }}</td>
                                    <td class="px-6 py-4">{{ $stock->batch_code }}</td>
                                    <td class="px-6 py-4">{{ $stock->created_at->format('d-m-Y') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

</x-app-layout>
