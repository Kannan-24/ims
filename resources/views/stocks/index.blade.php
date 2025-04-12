<x-app-layout>

    <x-slot name="title">
        {{ __('Stock List') }} - {{ config('app.name', 'ATMS') }}
    </x-slot>

    <!-- Main Content Section -->
    <div class="py-6 mt-20 ml-4 sm:ml-64">
        <div class="w-full mx-auto max-w-7xl sm:px-6 lg:px-8">

            <x-bread-crumb-navigation />

            <div class="overflow-hidden bg-gray-800 rounded-lg shadow-xl">
                <div class="p-6 overflow-x-auto">
                    <table class="min-w-full text-left border-collapse table-auto">
                        <thead>
                            <tr class="text-sm text-gray-300 bg-gray-700 uppercase tracking-wider">
                                <th class="px-6 py-4 border-b-2 border-gray-600 cursor-pointer" onclick="sortTable(0)">#</th>
                                <th class="px-6 py-4 border-b-2 border-gray-600 cursor-pointer" onclick="sortTable(1)">Product Name</th>
                                <th class="px-6 py-4 border-b-2 border-gray-600 cursor-pointer" onclick="sortTable(2)">Supplier</th>
                                <th class="px-6 py-4 border-b-2 border-gray-600">Unit Type</th>
                                <th class="px-6 py-4 border-b-2 border-gray-600">Total Quantity</th>
                                <th class="px-6 py-4 border-b-2 border-gray-600">Total Sold</th>
                                <th class="px-6 py-4 border-b-2 border-gray-600 text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="text-sm text-gray-300 divide-y divide-gray-700" id="stockTable">
                            @foreach ($stocks as $stock)
                                <tr class="hover:bg-gray-700 transition duration-200">
                                    <td class="px-6 py-4">{{ $loop->iteration }}</td>
                                    <td class="px-6 py-4">{{ $stock->product->name ?? 'N/A' }}</td>
                                    <td class="px-6 py-4">{{ $stock->supplier->name ?? 'N/A' }}</td>
                                    <td class="px-6 py-4">{{ $stock->unit_type }}</td>
                                    <td class="px-6 py-4">{{ $stock->total_quantity }}</td>
                                    <td class="px-6 py-4">{{ ($stock->total_quantity - $stock->total_sold) }}</td>
                                    <td class="px-6 py-4 flex justify-center gap-3">
                                        <a href="{{ route('stocks.show', $stock->product_id) }}"
                                            class="text-blue-400 hover:text-blue-600 transition duration-300"
                                            title="View">
                                            <i class="fas fa-eye"></i>
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
