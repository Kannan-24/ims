<x-app-layout :permission="$permission">

    <x-slot name="title">
        {{ __('Permission Details') }} - {{ config('app.name', 'IMS') }}
    </x-slot>

    <!-- Main Content Section -->
    <div class="py-6 mt-20 ml-4 sm:ml-64">
        <div class="w-full mx-auto max-w-7xl sm:px-6 lg:px-8">

            <!-- Breadcrumb Navigation -->
            <x-bread-crumb-navigation />

            <div class="overflow-hidden bg-gray-800 rounded-lg shadow-xl">
                <div class="bg-gray-800 p-4 rounded-lg shadow-md flex justify-between items-center">
                    <h2 class="text-xl font-semibold text-white">Permission: {{ $permission->name }}</h2>
                    <div class="flex space-x-2">
                        @can('edit-permissions')
                            <a href="{{ route('permissions.edit', $permission) }}"
                                class="px-4 py-2 bg-yellow-600 hover:bg-yellow-700 text-white rounded font-semibold">
                                <i class="fa-solid fa-edit mr-2"></i>Edit
                            </a>
                        @endcan
                        <a href="{{ route('permissions.index') }}"
                            class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded font-semibold">
                            Back to Permissions
                        </a>
                    </div>
                </div>
                
                <div class="p-6">
                    <!-- Permission Information -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                        <div class="bg-gray-700 p-4 rounded">
                            <h3 class="text-lg font-medium text-white mb-4">Permission Information</h3>
                            <div class="space-y-3">
                                <div>
                                    <label class="block text-sm font-medium text-gray-300">Name</label>
                                    <p class="text-white font-mono text-sm">{{ $permission->name }}</p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-300">Description</label>
                                    <p class="text-white">{{ $permission->description ?? 'No description provided' }}</p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-300">Module</label>
                                    <span class="px-2 py-1 bg-purple-600 text-white text-sm rounded capitalize">
                                        {{ explode('-', $permission->name)[1] ?? 'Other' }}
                                    </span>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-300">Action</label>
                                    <span class="px-2 py-1 bg-green-600 text-white text-sm rounded capitalize">
                                        {{ explode('-', $permission->name)[0] ?? 'Unknown' }}
                                    </span>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-300">Guard</label>
                                    <p class="text-white">{{ $permission->guard_name }}</p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-300">Created</label>
                                    <p class="text-white">{{ $permission->created_at->format('M d, Y h:i A') }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="bg-gray-700 p-4 rounded">
                            <h3 class="text-lg font-medium text-white mb-4">Statistics</h3>
                            <div class="space-y-3">
                                <div class="flex justify-between">
                                    <span class="text-gray-300">Assigned Roles:</span>
                                    <span class="text-white font-semibold">{{ $permission->roles->count() }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-300">Direct Users:</span>
                                    <span class="text-white font-semibold">{{ $permission->users->count() }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-300">Last Updated:</span>
                                    <span class="text-white">{{ $permission->updated_at->format('M d, Y') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Assigned Roles -->
                    <div>
                        <h3 class="text-lg font-medium text-white mb-4">Assigned Roles ({{ $permission->roles->count() }})</h3>
                        @if($permission->roles->count() > 0)
                            <div class="overflow-x-auto">
                                <table class="w-full text-left text-gray-300">
                                    <thead class="bg-gray-700 text-gray-300 uppercase text-sm">
                                        <tr>
                                            <th class="px-6 py-3">Role Name</th>
                                            <th class="px-6 py-3">Description</th>
                                            <th class="px-6 py-3">Users</th>
                                            <th class="px-6 py-3">Total Permissions</th>
                                            <th class="px-6 py-3">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-gray-800">
                                        @foreach($permission->roles as $role)
                                            <tr class="border-b border-gray-700 hover:bg-gray-700">
                                                <td class="px-6 py-4 font-medium">{{ $role->name }}</td>
                                                <td class="px-6 py-4">{{ $role->description ?? 'No description' }}</td>
                                                <td class="px-6 py-4">
                                                    <span class="px-2 py-1 bg-green-600 text-white text-xs rounded">
                                                        {{ $role->users->count() }} users
                                                    </span>
                                                </td>
                                                <td class="px-6 py-4">
                                                    <span class="px-2 py-1 bg-blue-600 text-white text-xs rounded">
                                                        {{ $role->permissions->count() }} permissions
                                                    </span>
                                                </td>
                                                <td class="px-6 py-4">
                                                    @can('view-roles')
                                                        <a href="{{ route('roles.show', $role) }}"
                                                            class="px-3 py-1 bg-blue-600 hover:bg-blue-700 text-white rounded text-sm">
                                                            <i class="fa-solid fa-eye"></i> View
                                                        </a>
                                                    @endcan
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-8 bg-gray-700 rounded">
                                <i class="fa-solid fa-user-shield text-4xl text-gray-500 mb-4"></i>
                                <p class="text-gray-400">This permission is not assigned to any roles</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>