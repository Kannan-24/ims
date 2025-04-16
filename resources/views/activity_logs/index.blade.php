<x-app-layout>
    <x-slot name="title">
        {{ __('Activity Logs') }} - {{ config('app.name', 'ATMS') }}
    </x-slot>

    <div class="py-6 mt-20 ml-4 sm:ml-64">
        <div class="w-full mx-auto max-w-7xl sm:px-6 lg:px-8 ">
            <x-bread-crumb-navigation />

            {{-- Search & Filter --}}
            <div class="overflow-hidden bg-gray-800 rounded-lg shadow-xl p-6">

                <div class="overflow-hidden bg-gray-800 rounded-lg shadow-xl mb-4">
                    <div class="">
                        <form method="GET" action="{{ route('activity-logs.index') }}"
                            class="flex flex-wrap items-center gap-4">
                            @if (request('module'))
                                <input type="hidden" name="module" value="{{ request('module') }}">
                            @endif
                            <input type="text" name="search" value="{{ request('search') }}"
                                placeholder="Search Logs"
                                class="flex-1 min-w-[200px] px-3 py-2 bg-gray-700 text-white rounded border border-gray-600 focus:outline-none focus:ring-2 focus:ring-indigo-500" />
                            <button type="submit"
                                class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded font-semibold">
                                Filter
                            </button>
                            <a href="{{ route('activity-logs.index') }}"
                                class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded font-semibold">
                                Reset
                            </a>
                        </form>
                    </div>
                </div>

                <!-- Card Grid for Modules -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mt-8 mb-6">
                    <!-- Show All Logs -->
                    <a href="{{ route('activity-logs.index') }}"
                        class="px-6 py-4 rounded-xl shadow-md transition duration-300 
                        {{ request('module') ? 'bg-gray-700 hover:bg-gray-600 text-white' : 'bg-indigo-600 hover:bg-indigo-500 text-white' }}">
                        <div class="text-lg font-bold">All Activity Logs</div>
                        <div class="text-sm text-indigo-200">View full activity history</div>
                    </a>

                    @foreach ($modules as $mod)
                        <a href="{{ route('activity-logs.index', ['module' => $mod->module]) }}"
                            class="px-6 py-4 rounded-xl shadow-md transition duration-300 
                            {{ request('module') === $mod->module ? 'bg-indigo-600 hover:bg-indigo-500 text-white' : 'bg-gray-700 hover:bg-gray-600 text-white' }}">
                            <div class="text-lg font-bold">{{ ucfirst($mod->module) }}</div>
                            <div class="text-sm text-gray-300">Logs: {{ $mod->count }}</div>
                        </a>
                    @endforeach
                </div>

                <hr class="border-gray-600 my-0">

                <div class="flex items-center justify-between mt-4 mb-4">
                    @if (request('module'))
                        <div class="text-white text-xl font-bold">
                            Logs for Module: <span class="text-indigo-400">{{ request('module') }}</span>
                        </div>
                        <a href="{{ route('activity-logs.index') }}"
                            class="text-sm bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded">
                            Clear Filter
                        </a>
                    @endif
                </div>

                @if ($logs->isNotEmpty())
                    <!-- Logs Table -->
                    <div class="overflow-x-auto bg-gray-800 rounded-lg shadow">
                        <table class="min-w-full text-left border-collapse table-auto">
                            <thead>
                                <tr class="text-sm text-gray-300 bg-gray-700 uppercase tracking-wider">
                                    <th class="px-6 py-4 border-b-2 border-gray-600">#</th>
                                    <th class="px-6 py-4 border-b-2 border-gray-600">User</th>
                                    <th class="px-6 py-4 border-b-2 border-gray-600">User Type</th>
                                    <th class="px-6 py-4 border-b-2 border-gray-600">Action</th>
                                    <th class="px-6 py-4 border-b-2 border-gray-600">Date</th>
                                    <th class="px-6 py-4 border-b-2 border-gray-600 text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="text-sm text-gray-300 divide-y divide-gray-700">
                                @foreach ($logs as $log)
                                    <tr class="hover:bg-gray-700 transition duration-200">
                                        <td class="px-6 py-4">{{ $loop->iteration }}</td>
                                        <td class="px-6 py-4">{{ $log->user->name ?? '-' }}</td>
                                        <td class="px-6 py-4">{{ $log->user_type }}</td>
                                        <td class="px-6 py-4">{{ $log->action_type }}</td>
                                        <td class="px-6 py-4">{{ $log->created_at }}</td>
                                        <td class="px-6 py-4 flex justify-center gap-3">
                                            <a href="{{ route('activity-logs.show', $log->id) }}"
                                                class="text-blue-400 hover:text-blue-600" title="View">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <form action="{{ route('activity-logs.destroy', $log->id) }}"
                                                method="POST" onsubmit="return confirm('Delete this log?')">
                                                @csrf
                                                @method('DELETE')
                                                <button class="text-red-400 hover:text-red-600" title="Delete">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        <!-- Pagination -->
                        <div class="mt-4 px-4 pb-4">
                            {{ $logs->appends(request()->input())->links() }}
                        </div>
                    </div>
                @else
                    <div class="text-center text-gray-400 mt-10">
                        No activity logs found.
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
