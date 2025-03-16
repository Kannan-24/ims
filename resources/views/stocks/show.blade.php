<x-app-layout>

    <x-slot name="title">
        {{ __('Stock Details') }} - {{ config('app.name', 'ATMS') }}
    </x-slot>

    <div class="py-6 ml-4 sm:ml-64">
        <div class="w-full mx-auto max-w-7xl sm:px-6 lg:px-8">

            <x-bread-crumb-navigation />

            <div class="overflow-hidden bg-white rounded-lg shadow-xl">
                <div class="p-6 overflow-x-auto">

                    <h1 class="mb-4 text-2xl font-bold">Product Details</h1>
                    <p><strong>Product Name : </strong> {{ $stocks->first()->product->name ?? 'Stock Details' }}</p>
                    <p><strong>HSN Code : </strong> {{ $stocks->first()->product->hsn_code ?? 'N/A' }}</p>
                    <p><strong>Description : </strong> {{ $stocks->first()->product->description ?? 'N/A' }}</p>
                    

                    
                    <table class="min-w-full text-left border-collapse table-auto mt-5">
                        <thead>
                            <tr class="text-sm text-gray-600 bg-indigo-100">
                                <th class="px-6 py-4 border-b-2 border-gray-200">#</th>
                                <th class="px-6 py-4 border-b-2 border-gray-200">Supplier</th>
                                <th class="px-6 py-4 border-b-2 border-gray-200">Unit Type</th>
                                <th class="px-6 py-4 border-b-2 border-gray-200">Quantity</th>
                                <th class="px-6 py-4 border-b-2 border-gray-200">Sold</th>
                                <th class="px-6 py-4 border-b-2 border-gray-200">Batch Code</th>
                                <th class="px-6 py-4 border-b-2 border-gray-200">Date Added</th>
                            </tr>
                        </thead>
                        <tbody class="text-sm text-gray-700">
                            @foreach ($stocks as $stock)
                                <tr class="border-b hover:bg-indigo-50">
                                    <td class="px-6 py-4 border-b border-gray-200">{{ $loop->iteration }}</td>
                                    <td class="px-6 py-4 border-b border-gray-200">
                                        <a href="{{ route('suppliers.show', $stock->supplier->id) }}" class="text-indigo-600 hover:text-indigo-900">
                                            {{ $stock->supplier->name ?? 'N/A' }}
                                        </a>
                                    </td>
                                    <td class="px-6 py-4 border-b border-gray-200">{{ $stock->unit_type }}</td>
                                    <td class="px-6 py-4 border-b border-gray-200">{{ $stock->quantity }}</td>
                                    <td class="px-6 py-4 border-b border-gray-200">{{ $stock->sold }}</td>
                                    <td class="px-6 py-4 border-b border-gray-200">{{ $stock->batch_code }}</td>
                                    <td class="px-6 py-4 border-b border-gray-200">{{ $stock->created_at->format('d-m-Y') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                </div>
            </div>
        </div>
    </div>

</x-app-layout>
