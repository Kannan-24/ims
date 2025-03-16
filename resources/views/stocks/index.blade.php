<x-app-layout>

    <x-slot name="title">
        {{ __('Stock List') }} - {{ config('app.name', 'ATMS') }}
    </x-slot>

    <!-- Main Content Section -->
    <div class="py-6 mt-20 ml-4 sm:ml-64">
        <div class="w-full mx-auto max-w-7xl sm:px-6 lg:px-8">

            <x-bread-crumb-navigation />

            <div class="overflow-hidden bg-white rounded-lg shadow-xl">
                <div class="p-6 overflow-x-auto">
                    <table class="min-w-full text-left border-collapse table-auto">
                        <thead>
                            <tr class="text-sm text-gray-600 bg-indigo-100">
                                <th class="px-6 py-4 border-b-2 border-gray-200">#</th>
                                <th class="px-6 py-4 border-b-2 border-gray-200">Product Name</th>
                                <th class="px-6 py-4 border-b-2 border-gray-200">Supplier</th>
                                <th class="px-6 py-4 border-b-2 border-gray-200">Unit Type</th>
                                <th class="px-6 py-4 border-b-2 border-gray-200">Total Quantity</th>
                                <th class="px-6 py-4 border-b-2 border-gray-200">Total Sold</th>
                                <th class="px-6 py-4 border-b-2 border-gray-200">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="text-sm text-gray-700" id="stockTable">
                            @foreach ($stocks as $stock)
                                <tr class="border-b hover:bg-indigo-50">
                                    <td class="px-6 py-4 border-b border-gray-200">{{ $loop->iteration }}</td>
                                    <td class="px-6 py-4 border-b border-gray-200">{{ $stock->product->name ?? 'N/A' }}</td>
                                    <td class="px-6 py-4 border-b border-gray-200">{{ $stock->supplier->name ?? 'N/A' }}</td>
                                    <td class="px-6 py-4 border-b border-gray-200">{{ $stock->unit_type }}</td>
                                    <td class="px-6 py-4 border-b border-gray-200">{{ $stock->total_quantity }}</td>
                                    <td class="px-6 py-4 border-b border-gray-200">{{ $stock->total_sold }}</td>
                                    <td class="px-6 py-4 border-b border-gray-200">
                                        <a href="{{ route('stocks.show', $stock->product_id) }}" 
                                           class="px-4 py-2 text-white bg-blue-500 rounded hover:bg-blue-700">
                                           Show
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                </div>
            </div>
        </div>
    </div>

</x-app-layout>
