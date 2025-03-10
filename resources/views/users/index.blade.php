<x-app-layout :users="$users">

    <x-slot name="title">
        {{ __('User List') }} - {{ config('app.name', 'ATMS') }}
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
                                <th class="px-6 py-4 border-b-2 border-gray-200 cursor-pointer" onclick="sortTable(0)">#</th>
                                <th class="px-6 py-4 border-b-2 border-gray-200 cursor-pointer" onclick="sortTable(1)">Employee_id</th>
                                <th class="px-6 py-4 border-b-2 border-gray-200 cursor-pointer" onclick="sortTable(2)">Name</th>
                                <th class="px-6 py-4 border-b-2 border-gray-200">Email</th>
                                <th class="px-6 py-4 border-b-2 border-gray-200">Phone</th>
                                <th class="px-6 py-4 border-b-2 border-gray-200">Role</th>
                                <th class="px-6 py-4 border-b-2 border-gray-200">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="text-sm text-gray-700" id="userTable">
                            @foreach ($users as $user)
                                <tr class="border-b hover:bg-indigo-50">
                                    <td class="px-6 py-4 border-b border-gray-200">{{ $loop->iteration }}</td>
                                    <td class="px-6 py-4 border-b border-gray-200">{{ $user->employee_id }}</td>
                                    <td class="px-6 py-4 border-b border-gray-200">{{ $user->name }}</td>
                                    <td class="px-6 py-4 border-b border-gray-200">{{ $user->email }}</td>
                                    <td class="px-6 py-4 border-b border-gray-200">{{ $user->phone }}</td>
                                    <td class="px-6 py-4 border-b border-gray-200">{{ $user->role }}</td>
                                    <x-action-buttons :id="$user->id" :model="'users'" />
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                </div>
            </div>
        </div>
    </div>

</x-app-layout>
