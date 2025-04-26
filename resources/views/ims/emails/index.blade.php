<x-app-layout>

    <x-slot name="title">
        {{ __('Mail List') }} - {{ config('app.name', 'ATMS') }}
    </x-slot>

    <!-- Main Content Section -->
    <div class="py-6 mt-20 ml-4 sm:ml-64">
        <div class="w-full mx-auto max-w-7xl sm:px-6 lg:px-8">

            <!-- Breadcrumb Navigation -->
            <x-bread-crumb-navigation />

            <!-- Table Section -->
            <div class="overflow-hidden bg-gray-800 rounded-lg shadow-xl">
                <div class="bg-gray-800 p-4 rounded-lg shadow-md">
                    <form method="GET" action="{{ route('emails.index') }}" class="flex flex-wrap items-center gap-4">

                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="Search Mail Subject or Sender"
                            class="flex-1 min-w-[200px] px-3 py-2 bg-gray-700 text-white rounded border border-gray-600 focus:outline-none focus:ring-2 focus:ring-indigo-500" />

                        <button type="submit"
                            class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded font-semibold">Filter</button>
                        <a href="{{ route('emails.index') }}"
                            class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded font-semibold">Reset</a>
                    </form>
                </div>
                <div class="p-4 overflow-x-auto">
                    @if ($emails->isEmpty())
                        <div class="text-center text-gray-300">
                            {{ __('No mails found.') }}
                        </div>
                    @else
                        <table class="min-w-full text-left border-collapse table-auto">
                            <thead>
                                <tr class="text-sm text-gray-300 bg-gray-700 uppercase tracking-wider">
                                    <th class="px-6 py-4 border-b-2 border-gray-600 cursor-pointer"
                                        onclick="sortTable(0)">#
                                    </th>
                                    <th class="px-6 py-4 border-b-2 border-gray-600 cursor-pointer" onclick="sortTable(1)">
                                        To
                                    </th>
                                    <th class="px-6 py-4 border-b-2 border-gray-600 cursor-pointer"
                                        onclick="sortTable(2)">
                                        Subject</th>
                                    <th class="px-6 py-4 border-b-2 border-gray-600 cursor-pointer"
                                        onclick="sortTable(4)">
                                        Date</th>
                                    <th class="px-6 py-4 border-b-2 border-gray-600 text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="text-sm text-gray-300 divide-y divide-gray-700" id="emailTable">
                                @foreach ($emails as $email)
                                    <tr class="hover:bg-gray-700 transition duration-200">
                                        <td class="px-6 py-4">{{ $loop->iteration }}</td>
                                        <td class="px-6 py-4">
                                            @if ($email->to)
                                                @php
                                                    $recipients = explode(',', $email->to);
                                                @endphp
                                                @foreach ($recipients as $index => $recipient)
                                                    <span>{{ $index + 1 }}. {{ $recipient }}</span><br>
                                                @endforeach
                                            @else
                                                <span class="text-red-500">No recipient</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4">{{ $email->subject }}</td>
                                        <td class="px-6 py-4">{{ $email->created_at->format('d-m-Y') }}</td>
                                        <td class="px-6 py-4 flex justify-center gap-3">
                                            <a href="{{ route('emails.show', $email) }}"
                                                class="text-blue-400 hover:text-blue-600 transition duration-300"
                                                title="View">
                                                <i class="fas fa-eye"></i>
                                            </a>
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

</x-app-layout>
