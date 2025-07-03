<x-app-layout :permissions="$permissions">

    <x-slot name="title">
        {{ __('Create Role') }} - {{ config('app.name', 'IMS') }}
    </x-slot>

    <!-- Main Content Section -->
    <div class="py-6 mt-20 ml-4 sm:ml-64">
        <div class="w-full mx-auto max-w-7xl sm:px-6 lg:px-8">

            <!-- Breadcrumb Navigation -->
            <x-bread-crumb-navigation />

            <div class="overflow-hidden bg-gray-800 rounded-lg shadow-xl">
                <div class="bg-gray-800 p-4 rounded-lg shadow-md">
                    <h2 class="text-xl font-semibold text-white">Create New Role</h2>
                </div>
                
                <div class="p-6">
                    <form method="POST" action="{{ route('roles.store') }}">
                        @csrf
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Role Name -->
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-300 mb-2">
                                    Role Name *
                                </label>
                                <input type="text" name="name" id="name" value="{{ old('name') }}" required
                                    class="w-full px-3 py-2 bg-gray-700 text-white rounded border border-gray-600 focus:outline-none focus:ring-2 focus:ring-indigo-500 @error('name') border-red-500 @enderror" />
                                @error('name')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Description -->
                            <div>
                                <label for="description" class="block text-sm font-medium text-gray-300 mb-2">
                                    Description
                                </label>
                                <input type="text" name="description" id="description" value="{{ old('description') }}"
                                    class="w-full px-3 py-2 bg-gray-700 text-white rounded border border-gray-600 focus:outline-none focus:ring-2 focus:ring-indigo-500 @error('description') border-red-500 @enderror" />
                                @error('description')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Permissions -->
                        <div class="mt-6">
                            <label class="block text-sm font-medium text-gray-300 mb-4">
                                Permissions
                            </label>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                @foreach($permissions->groupBy(function($permission) {
                                    return explode('-', $permission->name)[1] ?? 'other';
                                }) as $module => $modulePermissions)
                                    <div class="bg-gray-700 p-4 rounded">
                                        <h4 class="text-white font-medium mb-3 capitalize">{{ $module }}</h4>
                                        @foreach($modulePermissions as $permission)
                                            <div class="flex items-center mb-2">
                                                <input type="checkbox" name="permissions[]" value="{{ $permission->id }}" 
                                                    id="permission_{{ $permission->id }}"
                                                    class="w-4 h-4 text-indigo-600 bg-gray-600 border-gray-500 rounded focus:ring-indigo-500"
                                                    {{ in_array($permission->id, old('permissions', [])) ? 'checked' : '' }}>
                                                <label for="permission_{{ $permission->id }}" class="ml-2 text-sm text-gray-300">
                                                    {{ ucfirst(str_replace('-', ' ', $permission->name)) }}
                                                </label>
                                            </div>
                                        @endforeach
                                    </div>
                                @endforeach
                            </div>
                            @error('permissions')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Submit Buttons -->
                        <div class="flex justify-end space-x-4 mt-8">
                            <a href="{{ route('roles.index') }}"
                                class="px-6 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded font-semibold">
                                Cancel
                            </a>
                            <button type="submit"
                                class="px-6 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded font-semibold">
                                Create Role
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>