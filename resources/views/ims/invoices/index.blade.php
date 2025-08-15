<x-app-layout>

    <x-slot name="title">
        {{ __('Invoice List') }} - {{ config('app.name', 'ATMS') }}
    </x-slot>

    <!-- Main Content Section -->
    <div class="py-6 mt-20 ml-4 sm:ml-64">
        <div class="w-full mx-auto max-w-7xl sm:px-6 lg:px-8">

            <x-bread-crumb-navigation />

            <div class="overflow-hidden bg-gray-800 rounded-lg shadow-xl">
                <div class="bg-gray-800 p-4 rounded-lg shadow-md">
                    <form method="GET" action="{{ route('invoices.index') }}" class="flex flex-wrap items-center gap-4">

                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="Search Invoice No or Company Name"
                            class="flex-1 min-w-[200px] px-3 py-2 bg-gray-700 text-white rounded border border-gray-600 focus:outline-none focus:ring-2 focus:ring-indigo-500" />

                        <input type="date" name="from" value="{{ request('from') }}"
                            class="px-3 py-2 bg-gray-700 text-white rounded border border-gray-600 focus:outline-none focus:ring-2 focus:ring-indigo-500" />

                        <input type="date" name="to" value="{{ request('to') }}"
                            class="px-3 py-2 bg-gray-700 text-white rounded border border-gray-600 focus:outline-none focus:ring-2 focus:ring-indigo-500" />

                        <button type="submit"
                            class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded font-semibold">Filter</button>
                        <a href="{{ route('invoices.index') }}"
                            class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded font-semibold">Reset</a>
                    </form>
                </div>
                <div class="p-4 overflow-x-auto">
                    @if ($invoices->isEmpty())
                        <div class="text-center text-gray-300">
                            {{ __('No invoices found.') }}
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
                                        onclick="sortTable(2)">
                                        Invoice No</th>
                                    <th class="px-6 py-4 border-b-2 border-gray-600">Company Name</th>
                                    <th class="px-6 py-4 border-b-2 border-gray-600">Sub Total</th>
                                    <th class="px-6 py-4 border-b-2 border-gray-600">Final Amount</th>
                                    <th class="px-6 py-4 border-b-2 border-gray-600 text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="text-sm text-gray-300 divide-y divide-gray-700" id="invoiceTable">
                                @foreach ($invoices as $invoice)
                                    <tr class="hover:bg-gray-700 transition duration-200">
                                        <td class="px-6 py-4">{{ $loop->iteration }}</td>
                                        <td class="px-6 py-4">{{ $invoice->invoice_date }}</td>
                                        <td class="px-6 py-4">{{ $invoice->invoice_no }}</td>
                                        <td class="px-6 py-4">{{ $invoice->customer->company_name }}</td>
                                        <td class="px-6 py-4">{{ number_format($invoice->sub_total, 2) }}</td>
                                        <td class="px-6 py-4">{{ number_format($invoice->total, 2) }}</td>
                                        <td class="px-6 py-4 flex justify-center gap-3">
                                            <button onclick="createEmailDraft('{{ $invoice->id }}')"
                                                class="text-green-400 hover:text-green-600 transition duration-300"
                                                title="Send Email">
                                                <i class="fas fa-envelope"></i>
                                            </button>
                                            <button onclick="generateDeliveryChallan('{{ $invoice->id }}')"
                                                class="text-purple-400 hover:text-purple-600 transition duration-300"
                                                title="Create Delivery Challan">
                                                <i class="fas fa-truck"></i>
                                            </button>
                                            <a href="{{ route('invoices.pdf', $invoice->id) }}"
                                                class="text-orange-400 hover:text-orange-600 transition duration-300"
                                                title="Download PDF" target="_blank">
                                                <i class="fas fa-file-pdf"></i>
                                            </a>
                                            <a href="{{ route('invoices.show', $invoice) }}"
                                                class="text-blue-400 hover:text-blue-600 transition duration-300"
                                                title="View">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('invoices.edit', $invoice) }}"
                                                class="text-yellow-400 hover:text-yellow-600 transition duration-300"
                                                title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('invoices.destroy', $invoice) }}" method="POST"
                                                class="inline">
                                                @csrf @method('DELETE')
                                                <button type="submit"
                                                    class="text-red-400 hover:text-red-600 transition duration-300"
                                                    title="Delete"
                                                    onclick="return confirm('Are you sure you want to delete this invoice?')">
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
            let table = document.getElementById("invoiceTable");
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

        // Email Draft Function
        function createEmailDraft(invoiceId) {
            const url = "{{ route('emails.draft.create') }}?document_type=invoice&document_id=" + invoiceId;
            window.location.href = url;
        }
    </script>

</x-app-layout>
