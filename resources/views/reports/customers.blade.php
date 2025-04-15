<x-app-layout>

    <x-slot name="title">
        {{ __('Customer Reports') }} - {{ config('app.name', 'IMS') }}
    </x-slot>

    <!-- Main Content Section -->
    <div class="py-6 mt-20 ml-4 sm:ml-64">
        <div class="w-full mx-auto max-w-7xl sm:px-6 lg:px-8">

            <x-bread-crumb-navigation />

            <div class="overflow-hidden bg-gray-800 rounded-lg shadow-xl">
                <div class="bg-gray-800 p-4 rounded-lg shadow-md">
                    <form method="GET" action="{{ route('reports.customers') }}"
                        class="flex flex-wrap items-center gap-4">
                        <input type="text" name="search" placeholder="Search...."
                            class="flex-1 min-w-[200px] px-3 py-2 bg-gray-700 text-white rounded border border-gray-600 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                            oninput="filterCustomers(this.value)" />
                        <a href="{{ route('reports.customer.excel') }}"
                            class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded font-semibold">Download
                            CSV</a>

                        <a href="{{ route('reports.customer.pdf') }}" target="_blank"
                            class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded font-semibold">Download
                            PDF</a>
                    </form>
                </div>
                <div class="p-4 overflow-x-auto">
                    @if ($customers->isEmpty())
                        <div class="text-center text-gray-300">
                            {{ __('No customers found.') }}
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
                                        Company Name</th>
                                    <th class="px-6 py-4 border-b-2 border-gray-600 cursor-pointer"
                                        onclick="sortTable(2)">
                                        Address</th>
                                    <th class="px-6 py-4 border-b-2 border-gray-600 cursor-pointer"
                                        onclick="sortTable(3)">
                                        City</th>
                                    <th class="px-6 py-4 border-b-2 border-gray-600 cursor-pointer"
                                        onclick="sortTable(4)">
                                        State</th>
                                    <th class="px-6 py-4 border-b-2 border-gray-600 cursor-pointer"
                                        onclick="sortTable(5)">
                                        Zip Code</th>
                                    <th class="px-6 py-4 border-b-2 border-gray-600 cursor-pointer"
                                        onclick="sortTable(6)">
                                        Country</th>
                                    <th class="px-6 py-4 border-b-2 border-gray-600 cursor-pointer"
                                        onclick="sortTable(7)">
                                        GST Number</th>
                                    <th class="px-6 py-4 border-b-2 border-gray-600 cursor-pointer"
                                        onclick="sortTable(8)">
                                        Contact Persons</th>
                                </tr>
                            </thead>
                            <tbody class="text-sm text-gray-300 divide-y divide-gray-700" id="customerTable">
                                @foreach ($customers as $index => $customer)
                                    <tr class="hover:bg-gray-700 transition duration-200">
                                        <td class="px-6 py-4">{{ $loop->iteration }}</td>
                                        <td class="px-6 py-4">{{ $customer->company_name }}</td>
                                        <td class="px-6 py-4">{{ $customer->address }}</td>
                                        <td class="px-6 py-4">{{ $customer->city }}</td>
                                        <td class="px-6 py-4">{{ $customer->state }}</td>
                                        <td class="px-6 py-4">{{ $customer->zip_code }}</td>
                                        <td class="px-6 py-4">{{ $customer->country }}</td>
                                        <td class="px-6 py-4">{{ $customer->gst_number }}</td>
                                        <td class="px-6 py-4">
                                            <ul class="list-disc list-inside">
                                                @foreach ($customer->contactPersons as $person)
                                                    <li>
                                                        <strong>{{ $person->name }}</strong><br>
                                                        {{ $person->email }}<br>
                                                        {{ $person->phone_no }}
                                                    </li>
                                                @endforeach
                                            </ul>
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
        function filterCustomers(query) {
            const rows = document.querySelectorAll('#customerTable tr');
            rows.forEach(row => {
                const name = row.cells[1]?.innerText.toLowerCase() || '';
                const email = row.cells[2]?.innerText.toLowerCase() || '';
                if (name.includes(query.toLowerCase()) || email.includes(query.toLowerCase())) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        }
    </script>

</x-app-layout>
