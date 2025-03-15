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
                                <th class="px-6 py-4 border-b-2 border-gray-200 cursor-pointer" onclick="sortTable(0)">#
                                </th>
                                <th class="px-6 py-4 border-b-2 border-gray-200 cursor-pointer" onclick="sortTable(1)">
                                    Product Name</th>
                                <th class="px-6 py-4 border-b-2 border-gray-200">Supplier</th>
                                <th class="px-6 py-4 border-b-2 border-gray-200">Unit Type</th>
                                <th class="px-6 py-4 border-b-2 border-gray-200">Quantity</th>
                                <th class="px-6 py-4 border-b-2 border-gray-200">Sold</th>
                                <th class="px-6 py-4 border-b-2 border-gray-200">Batch Code</th>
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
                                    <td class="px-6 py-4 border-b border-gray-200">{{ $stock->quantity }}</td>
                                    <td class="px-6 py-4 border-b border-gray-200">{{ $stock->sold }}</td>
                                    <td class="px-6 py-4 border-b border-gray-200">{{ $stock->batch_code }}</td>
                                    <x-action-buttons :id="$stock->id" :model="'stocks'" />
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                </div>
            </div>
        </div>
    </div>

</x-app-layout>
