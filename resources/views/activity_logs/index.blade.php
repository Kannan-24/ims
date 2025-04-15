<x-app-layout>

    <x-slot name="title">
        {{ __('Activity Logs') }} - {{ config('app.name', 'ATMS') }}
    </x-slot>

    <!-- Main Content Section -->
    <div class="py-6 mt-20 ml-4 sm:ml-64">
        <div class="w-full mx-auto max-w-7xl sm:px-6 lg:px-8">

            <x-bread-crumb-navigation />

            <div class="overflow-hidden bg-gray-800 rounded-lg shadow-xl">
                <div class="bg-gray-800 p-4 rounded-lg shadow-md">
                    <form method="GET" action="{{ route('activity-logs.index') }}"
                        class="flex flex-wrap items-center gap-4">

                        <input type="text" name="search" placeholder="Search Logs"
                            class="flex-1 min-w-[200px] px-3 py-2 bg-gray-700 text-white rounded border border-gray-600 focus:outline-none focus:ring-2 focus:ring-indigo-500" />

                        <button type="submit"
                            class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded font-semibold">Filter</button>
                        <a href="{{ route('activity-logs.index') }}"
                            class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded font-semibold">Reset</a>
                    </form>
                </div>
                <div class="p-4 overflow-x-auto">
                    @if ($logs->isEmpty())
                        <div class="text-center text-gray-300">
                            {{ __('No activity logs found.') }}
                        </div>
                    @else
                        <table class="min-w-full text-left border-collapse table-auto">
                            <thead>
                                <tr class="text-sm text-gray-300 bg-gray-700 uppercase tracking-wider">
                                    <th class="px-6 py-4 border-b-2 border-gray-600">#</th>
                                    <th class="px-6 py-4 border-b-2 border-gray-600">User</th>
                                    <th class="px-6 py-4 border-b-2 border-gray-600">User Type</th>
                                    <th class="px-6 py-4 border-b-2 border-gray-600">Action</th>
                                    <th class="px-6 py-4 border-b-2 border-gray-600">Date</th>
                                    <th class="px-6 py-4 border-b-2 border-gray-600">IP</th>
                                    <th class="px-6 py-4 border-b-2 border-gray-600 text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="text-sm text-gray-300 divide-y divide-gray-700">
                                @foreach ($logs as $log)
                                    <tr class="hover:bg-gray-700 transition duration-200">
                                        <td class="px-6 py-4">{{ $loop->iteration }}</td>
                                        <td class="px-6 py-4">{{ $log->user->name }}</td>
                                        <td class="px-6 py-4">{{ $log->user_type }}</td>
                                        <td class="px-6 py-4">{{ $log->action_type }}</td>
                                        <td class="px-6 py-4">{{ $log->created_at }}</td>
                                        <td class="px-6 py-4">{{ $log->ip_address }}</td>
                                        <td class="px-6 py-4 flex justify-center gap-3">
                                            <a href="{{ route('activity-logs.show', $log->id) }}"
                                                class="text-blue-400 hover:text-blue-600 transition duration-300"
                                                title="View">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <form action="{{ route('activity-logs.destroy', $log->id) }}"
                                                method="POST" onsubmit="return confirm('Delete this log?')">
                                                @csrf
                                                @method('DELETE')
                                                <button class="text-red-400 hover:text-red-600 transition duration-300"
                                                    title="Delete">
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
                <div class="mt-4">
                    {{ $logs->links() }}
                </div>
            </div>
        </div>
    </div>

</x-app-layout>
