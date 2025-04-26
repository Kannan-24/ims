<x-app-layout>

    <x-slot name="title">
        {{ __('Invoice Reports') }} - {{ config('app.name', 'IMS') }}
    </x-slot>

    <div class="py-6 mt-20 ml-4 sm:ml-64">
        <div class="w-full mx-auto max-w-7xl sm:px-6 lg:px-8">
            <x-bread-crumb-navigation />

            <!-- Filters -->
            <div class="bg-gray-800 p-4 rounded-lg shadow-md mb-6">
                <form method="GET" action="{{ route('reports.invoices') }}" class="flex flex-wrap items-center gap-4">
                    <input type="text" name="search" placeholder="Search...."
                        class="flex-1 min-w-[200px] px-3 py-2 bg-gray-700 text-white rounded border border-gray-600"
                        oninput="filterTable()" />

                    <button type="button" onclick="openDownloadModal()"
                        class="px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white rounded font-semibold">
                        Download Report
                    </button>
                </form>
            </div>

            <!-- Invoice Table -->
            <div class="overflow-hidden bg-gray-800 rounded-lg shadow-xl">
                <div class="p-4 overflow-x-auto">
                    @if ($invoices->isEmpty())
                        <div class="text-center text-gray-300">
                            {{ __('No invoices found.') }}
                        </div>
                    @else
                        <table class="min-w-full text-left border-collapse table-auto">
                            <thead>
                                <tr class="text-sm text-gray-300 bg-gray-700 uppercase tracking-wider">
                                    <th class="px-6 py-4 border-b-2 border-gray-600">#</th>
                                    <th class="px-6 py-4 border-b-2 border-gray-600">Invoice Number</th>
                                    <th class="px-6 py-4 border-b-2 border-gray-600">Customer</th>
                                    <th class="px-6 py-4 border-b-2 border-gray-600">Invoice Date</th>
                                    <th class="px-6 py-4 border-b-2 border-gray-600">Amount</th>
                                </tr>
                            </thead>
                            <tbody class="text-sm text-gray-300 divide-y divide-gray-700">
                                @foreach ($invoices as $index => $invoice)
                                    <tr class="hover:bg-gray-700 transition duration-200">
                                        <td class="px-6 py-4">{{ $loop->iteration }}</td>
                                        <td class="px-6 py-4">{{ $invoice->invoice_no }}</td>
                                        <td class="px-6 py-4">{{ $invoice->customer->company_name }}</td>
                                        <td class="px-6 py-4">{{ $invoice->invoice_date }}</td>
                                        <td class="px-6 py-4">{{ number_format($invoice->total_amount, 2) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div id="downloadModal" class="fixed inset-0 bg-black bg-opacity-80 hidden flex justify-center items-center z-50">
        <div class="bg-gray-800 rounded-lg p-6 w-full max-w-md space-y-4 text-gray-300">
            <h2 class="text-lg font-bold text-white text-center">Select Date Range for Download</h2>
            <form id="downloadForm" method="GET">
                <select name="range" id="downloadRange" required
                    class="w-full px-3 py-2 rounded border border-gray-600 bg-gray-700 text-white">
                    <option value="">Select Range</option>
                    <option value="last_7_days">Last 7 Days</option>
                    <option value="last_15_days">Last 15 Days</option>
                    <option value="last_30_days">Last 30 Days</option>
                    <option value="custom">Custom Range</option>
                </select>

                <div class="flex gap-4 mt-4" id="downloadCustomDates" style="display: none;">
                    <input type="date" name="start_date"
                        class="flex-1 px-3 py-2 border border-gray-600 rounded bg-gray-700 text-white" />
                    <input type="date" name="end_date"
                        class="flex-1 px-3 py-2 border border-gray-600 rounded bg-gray-700 text-white" />
                </div>

                <div class="flex justify-end gap-4 mt-6">
                    <button type="button" onclick="submitDownload('pdf')" target="_blank"
                        class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded">Download PDF</button>
                    <button type="button" onclick="submitDownload('excel')"
                        class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded">Download CSV</button>
                    <button type="button" onclick="closeDownloadModal()"
                        class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded">Cancel</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function filterTable() {
            const searchInput = document.querySelector('input[name="search"]').value.toLowerCase();
            const rows = document.querySelectorAll('tbody tr');

            rows.forEach(row => {
                const cells = row.querySelectorAll('td');
                const rowText = Array.from(cells).map(cell => cell.textContent.toLowerCase()).join(' ');
                row.style.display = rowText.includes(searchInput) ? '' : 'none';
            });
        }

        function openDownloadModal() {
            document.getElementById("downloadModal").classList.remove("hidden");
            document.body.classList.add("overflow-hidden");
        }

        function closeDownloadModal() {
            document.getElementById("downloadModal").classList.add("hidden");
            document.body.classList.remove("overflow-hidden");
        }

        document.getElementById('downloadRange').addEventListener('change', function() {
            const customDateDiv = document.getElementById("downloadCustomDates");
            customDateDiv.style.display = this.value === "custom" ? "flex" : "none";
        });

        function submitDownload(type) {
            const form = document.getElementById("downloadForm");
            let action = type === 'pdf' ? "{{ route('reports.invoices.pdf') }}" : "{{ route('reports.invoices.excel') }}";
            form.action = action;
            form.target = "_blank";
            form.submit();
            closeDownloadModal();
        }
    </script>
</x-app-layout>
