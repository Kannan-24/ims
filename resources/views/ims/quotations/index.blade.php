<x-app-layout>

    <x-slot name="title">
        {{ __('Quotation List') }} - {{ config('app.name', 'ATMS') }}
    </x-slot>

    <!-- Main Content Section -->
    <div class="py-6 mt-20 ml-4 sm:ml-64">
        <div class="w-full mx-auto max-w-7xl sm:px-6 lg:px-8">

            <x-bread-crumb-navigation />

            <div class="overflow-hidden bg-gray-800 rounded-lg shadow-xl">
                <div class="bg-gray-800 p-4 rounded-lg shadow-md">
                    <form method="GET" action="{{ route('quotations.index') }}"
                        class="flex flex-wrap items-center gap-4">

                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="Search Quotation Code or Company Name"
                            class="flex-1 min-w-[200px] px-3 py-2 bg-gray-700 text-white rounded border border-gray-600 focus:outline-none focus:ring-2 focus:ring-indigo-500" />

                        <input type="date" name="from" value="{{ request('from') }}"
                            class="px-3 py-2 bg-gray-700 text-white rounded border border-gray-600 focus:outline-none focus:ring-2 focus:ring-indigo-500" />

                        <input type="date" name="to" value="{{ request('to') }}"
                            class="px-3 py-2 bg-gray-700 text-white rounded border border-gray-600 focus:outline-none focus:ring-2 focus:ring-indigo-500" />

                        <button type="submit"
                            class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded font-semibold">Filter</button>
                        <a href="{{ route('quotations.index') }}"
                            class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded font-semibold">Reset</a>
                    </form>
                </div>
                <div class="p-4 overflow-x-auto">
                    @if ($quotations->isEmpty())
                        <div class="text-center text-gray-300">
                            {{ __('No quotations found.') }}
                        </div>
                    @else
                        <table class="min-w-full text-left border-collapse table-auto">
                            <thead>
                                <tr class="text-sm text-gray-300 bg-gray-700 uppercase tracking-wider">
                                    <th class="px-6 py-4 border-b-2 border-gray-600 cursor-pointer"
                                        onclick="sortTable(0)">#
                                    </th>
                                    <th class="px-6 py-4 border-b-2 border-gray-600 cursor-pointer"
                                        onclick="sortTable(1)">
                                        Date</th>
                                    <th class="px-6 py-4 border-b-2 border-gray-600 cursor-pointer"
                                        onclick="sortTable(1)">
                                        Quotation No</th>
                                    <th class="px-6 py-4 border-b-2 border-gray-600">Company Name</th>
                                    <th class="px-6 py-4 border-b-2 border-gray-600">Sub Total</th>
                                    <th class="px-6 py-4 border-b-2 border-gray-600">Final Amount</th>
                                    <th class="px-6 py-4 border-b-2 border-gray-600 text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="text-sm text-gray-300 divide-y divide-gray-700" id="quotationTable">
                                @foreach ($quotations as $quotation)
                                    <tr class="hover:bg-gray-700 transition duration-200">
                                        <td class="px-6 py-4">{{ $loop->iteration }}</td>
                                        <td class="px-6 py-4">{{ $quotation->quotation_date }}</td>
                                        <td class="px-6 py-4">{{ $quotation->quotation_code }}</td>
                                        <td class="px-6 py-4">{{ $quotation->customer->company_name }}</td>
                                        <td class="px-6 py-4">{{ number_format($quotation->sub_total, 2) }}</td>
                                        <td class="px-6 py-4">{{ number_format($quotation->total, 2) }}</td>
                                        <td class="px-6 py-4 flex justify-center gap-3">
                                            <a href="{{ route('quotations.pdf', $quotation->id) }}"
                                                class="text-green-400 hover:text-green-600 transition duration-300"
                                                title="Download PDF" target="_blank">
                                                <i class="fas fa-file-pdf"></i>
                                            </a>

                                            <form action="{{ route('quotations.convert-to-invoice', $quotation->id) }}"
                                                method="POST" class="inline">
                                                @csrf
                                                <button type="submit"
                                                    class="text-purple-400 hover:text-purple-600 transition duration-300"
                                                    title="Convert to Invoice"
                                                    onclick="return confirm('Are you sure you want to convert this quotation to an invoice? This action cannot be undone.')">
                                                    <i class="fas fa-file-invoice"></i>
                                                </button>
                                            </form>

                                            <a href="{{ route('quotations.show', $quotation) }}"
                                                class="text-blue-400 hover:text-blue-600 transition duration-300"
                                                title="View">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('quotations.edit', $quotation) }}"
                                                class="text-yellow-400 hover:text-yellow-600 transition duration-300"
                                                title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('quotations.destroy', $quotation) }}" method="POST"
                                                class="inline">
                                                @csrf @method('DELETE')
                                                <button type="submit"
                                                    class="text-red-400 hover:text-red-600 transition duration-300"
                                                    title="Delete"
                                                    onclick="return confirm('Are you sure you want to delete this quotation?')">
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
