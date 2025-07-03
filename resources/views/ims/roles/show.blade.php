<x-app-layout :role="$role">

    <x-slot name="title">
        {{ __('Role Details') }} - {{ config('app.name', 'IMS') }}
    </x-slot>

    <!-- Main Content Section -->
    <div class="py-6 mt-20 ml-4 sm:ml-64">
        <div class="w-full mx-auto max-w-7xl sm:px-6 lg:px-8">

            <!-- Breadcrumb Navigation -->
            <x-bread-crumb-navigation />

            <div class="overflow-hidden bg-gray-800 rounded-lg shadow-xl">
                <div class="bg-gray-800 p-4 rounded-lg shadow-md flex justify-between items-center">
                    <h2 class="text-xl font-semibold text-white">Role: {{ $role->name }}</h2>
                    <div class="flex space-x-2">
                        @can('edit-roles')
                            <a href="{{ route('roles.edit', $role) }}"
                                class="px-4 py-2 bg-yellow-600 hover:bg-yellow-700 text-white rounded font-semibold">
                                <i class="fa-solid fa-edit mr-2"></i>Edit
                            </a>
                        @endcan
                        <a href="{{ route('roles.index') }}"
                            class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded font-semibold">
                            Back to Roles
                        </a>
                    </div>
                </div>
                
                <div class="p-6">
                    <!-- Role Information -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                        <div class="bg-gray-700 p-4 rounded">
                            <h3 class="text-lg font-medium text-white mb-4">Role Information</h3>
                            <div class="space-y-3">
                                <div>
                                    <label class="block text-sm font-medium text-gray-300">Name</label>
                                    <p class="text-white">{{ $role->name }}</p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-300">Description</label>
                                    <p class="text-white">{{ $role->description ?? 'No description provided' }}</p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-300">Guard</label>
                                    <p class="text-white">{{ $role->guard_name }}</p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-300">Created</label>
                                    <p class="text-white">{{ $role->created_at->format('M d, Y h:i A') }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="bg-gray-700 p-4 rounded">
                            <h3 class="text-lg font-medium text-white mb-4">Statistics</h3>
                            <div class="space-y-3">
                                <div class="flex justify-between">
                                    <span class="text-gray-300">Total Permissions:</span>
                                    <span class="text-white font-semibold">{{ $role->permissions->count() }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-300">Assigned Users:</span>
                                    <span class="text-white font-semibold">{{ $role->users->count() }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-300">Last Updated:</span>
                                    <span class="text-white">{{ $role->updated_at->format('M d, Y') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Permissions -->
                    <div class="mb-8">
                        <h3 class="text-lg font-medium text-white mb-4">Permissions ({{ $role->permissions->count() }})</h3>
                        @if($role->permissions->count() > 0)
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                @foreach($role->permissions->groupBy(function($permission) {
                                    return explode('-', $permission->name)[1] ?? 'other';
                                }) as $module => $modulePermissions)
                                    <div class="bg-gray-700 p-4 rounded">
                                        <h4 class="text-white font-medium mb-3 capitalize">{{ $module }}</h4>
                                        <div class="space-y-2">
                                            @foreach($modulePermissions as $permission)
                                                <div class="flex items-center">
                                                    <i class="fa-solid fa-check text-green-500 mr-2"></i>
                                                    <span class="text-gray-300 text-sm">
                                                        {{ ucfirst(str_replace('-', ' ', $permission->name)) }}
                                                    </span>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-8 bg-gray-700 rounded">
                                <i class="fa-solid fa-shield-halved text-4xl text-gray-500 mb-4"></i>
                                <p class="text-gray-400">No permissions assigned to this role</p>
                            </div>
                        @endif
                    </div>

                    <!-- Assigned Users -->
                    <div>
                        <h3 class="text-lg font-medium text-white mb-4">Assigned Users ({{ $role->users->count() }})</h3>
                        @if($role->users->count() > 0)
                            <div class="overflow-x-auto">
                                <table class="w-full text-left text-gray-300">
                                    <thead class="bg-gray-700 text-gray-300 uppercase text-sm">
                                        <tr>
                                            <th class="px-6 py-3">Employee ID</th>
                                            <th class="px-6 py-3">Name</th>
                                            <th class="px-6 py-3">Email</th>
                                            <th class="px-6 py-3">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-gray-800">
                                        @foreach($role->users as $user)
                                            <tr class="border-b border-gray-700 hover:bg-gray-700">
                                                <td class="px-6 py-4">{{ $user->employee_id }}</td>
                                                <td class="px-6 py-4 font-medium">{{ $user->name }}</td>
                                                <td class="px-6 py-4">{{ $user->email }}</td>
                                                <td class="px-6 py-4">
                                                    <span class="px-2 py-1 bg-green-600 text-white text-xs rounded">
                                                        Active
                                                    </span>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-8 bg-gray-700 rounded">
                                <i class="fa-solid fa-users text-4xl text-gray-500 mb-4"></i>
                                <p class="text-gray-400">No users assigned to this role</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>