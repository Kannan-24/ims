<x-app-layout>

    <x-slot name="title">
        {{ __('Quotation List') }} - {{ config('app.name', 'ATMS') }}
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
                                <th class="px-6 py-4 border-b-2 border-gray-600 cursor-pointer" onclick="sortTable(1)">Quotation No</th>
                                <th class="px-6 py-4 border-b-2 border-gray-600 cursor-pointer" onclick="sortTable(2)">Client</th>
                                <th class="px-6 py-4 border-b-2 border-gray-600">Sub Total</th>
                                <th class="px-6 py-4 border-b-2 border-gray-600">Discount (%)</th>
                                <th class="px-6 py-4 border-b-2 border-gray-600">Final Amount</th>
                                <th class="px-6 py-4 border-b-2 border-gray-600 text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="text-sm text-gray-300 divide-y divide-gray-700" id="quotationTable">
                            @foreach ($quotations as $quotation)
                                <tr class="hover:bg-gray-700 transition duration-200">
                                    <td class="px-6 py-4">{{ $loop->iteration }}</td>
                                    <td class="px-6 py-4">{{ $quotation->quotation_no }}</td>
                                    <td class="px-6 py-4">{{ $quotation->client->name }}</td>
                                    <td class="px-6 py-4">{{ number_format($quotation->sub_total, 2) }}</td>
                                    <td class="px-6 py-4">{{ number_format($quotation->discount, 2) }}</td>
                                    <td class="px-6 py-4">{{ number_format($quotation->final_total, 2) }}</td>
                                    <td class="px-6 py-4 flex justify-center gap-3">
                                        <a href="{{ route('quotations.show', $quotation) }}" class="px-3 py-1 text-blue-400 bg-gray-800 hover:bg-gray-600 rounded-md shadow-sm transition duration-300">View</a>
                                        <a href="{{ route('quotations.edit', $quotation) }}" class="px-3 py-1 text-yellow-400 bg-gray-800 hover:bg-gray-600 rounded-md shadow-sm transition duration-300">Edit</a>
                                        <form action="{{ route('quotations.destroy', $quotation) }}" method="POST" class="inline">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="px-3 py-1 text-red-400 bg-gray-800 hover:bg-gray-600 rounded-md shadow-sm transition duration-300" onclick="return confirm('Are you sure you want to delete this quotation?')">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Sorting Functionality -->
    <script>
        function sortTable(columnIndex) {
            let table = document.getElementById("quotationTable");
            let rows = Array.from(table.rows);
            let isAscending = table.dataset.sortOrder === "asc";

            rows.sort((a, b) => {
                let aText = a.cells[columnIndex].innerText.trim();
                let bText = b.cells[columnIndex].innerText.trim();
                return isAscending ? aText.localeCompare(bText) : bText.localeCompare(aText);
            });

            table.innerHTML = "";
            rows.forEach(row => table.appendChild(row));

            table.dataset.sortOrder = isAscending ? "desc" : "asc";
        }
    </script>

</x-app-layout>
