<x-app-layout :roles="$roles">

    <x-slot name="title">
        {{ __('Role Management') }} - {{ config('app.name', 'IMS') }}
    </x-slot>

    <!-- Main Content Section -->
    <div class="py-6 mt-20 ml-4 sm:ml-64">
        <div class="w-full mx-auto max-w-7xl sm:px-6 lg:px-8">

            <!-- Breadcrumb Navigation -->
            <x-bread-crumb-navigation />

            <div class="overflow-hidden bg-gray-800 rounded-lg shadow-xl">
                <div class="bg-gray-800 p-4 rounded-lg shadow-md flex justify-between items-center">
                    <h2 class="text-xl font-semibold text-white">Roles</h2>
                    @can('create-roles')
                        <a href="{{ route('roles.create') }}"
                            class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded font-semibold">
                            <i class="fa-solid fa-plus mr-2"></i>Add Role
                        </a>
                    @endcan
                </div>
                
                <div class="p-4 overflow-x-auto">
                    @if (session('success'))
                        <div class="mb-4 p-4 bg-green-600 text-white rounded">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="mb-4 p-4 bg-red-600 text-white rounded">
                            {{ session('error') }}
                        </div>
                    @endif

                    @if ($roles->isEmpty())
                        <div class="text-center py-8">
                            <i class="fa-solid fa-users text-4xl text-gray-500 mb-4"></i>
                            <p class="text-gray-400 text-lg">No roles found</p>
                        </div>
                    @else
                        <div class="overflow-x-auto">
                            <table class="w-full text-left text-gray-300">
                                <thead class="bg-gray-700 text-gray-300 uppercase text-sm">
                                    <tr>
                                        <th class="px-6 py-3">#</th>
                                        <th class="px-6 py-3">Name</th>
                                        <th class="px-6 py-3">Description</th>
                                        <th class="px-6 py-3">Permissions</th>
                                        <th class="px-6 py-3">Users</th>
                                        <th class="px-6 py-3">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-gray-800">
                                    @foreach ($roles as $index => $role)
                                        <tr class="border-b border-gray-700 hover:bg-gray-700">
                                            <td class="px-6 py-4">{{ $index + 1 }}</td>
                                            <td class="px-6 py-4 font-medium">{{ $role->name }}</td>
                                            <td class="px-6 py-4">{{ $role->description ?? 'No description' }}</td>
                                            <td class="px-6 py-4">
                                                <span class="px-2 py-1 bg-blue-600 text-white text-xs rounded">
                                                    {{ $role->permissions->count() }} permissions
                                                </span>
                                            </td>
                                            <td class="px-6 py-4">
                                                <span class="px-2 py-1 bg-green-600 text-white text-xs rounded">
                                                    {{ $role->users->count() }} users
                                                </span>
                                            </td>
                                            <td class="px-6 py-4">
                                                <div class="flex space-x-2">
                                                    @can('view-roles')
                                                        <a href="{{ route('roles.show', $role) }}"
                                                            class="px-3 py-1 bg-blue-600 hover:bg-blue-700 text-white rounded text-sm">
                                                            <i class="fa-solid fa-eye"></i>
                                                        </a>
                                                    @endcan
                                                    @can('edit-roles')
                                                        <a href="{{ route('roles.edit', $role) }}"
                                                            class="px-3 py-1 bg-yellow-600 hover:bg-yellow-700 text-white rounded text-sm">
                                                            <i class="fa-solid fa-edit"></i>
                                                        </a>
                                                    @endcan
                                                    @can('delete-roles')
                                                        @if($role->users->count() == 0)
                                                            <form method="POST" action="{{ route('roles.destroy', $role) }}" class="inline"
                                                                onsubmit="return confirm('Are you sure you want to delete this role?')">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit"
                                                                    class="px-3 py-1 bg-red-600 hover:bg-red-700 text-white rounded text-sm">
                                                                    <i class="fa-solid fa-trash"></i>
                                                                </button>
                                                            </form>
                                                        @else
                                                            <span class="px-3 py-1 bg-gray-500 text-white rounded text-sm cursor-not-allowed"
                                                                title="Cannot delete role with assigned users">
                                                                <i class="fa-solid fa-trash"></i>
                                                            </span>
                                                        @endif
                                                    @endcan
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>