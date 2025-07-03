<x-app-layout>

    <x-slot name="title">
        {{ __('Create Permission') }} - {{ config('app.name', 'IMS') }}
    </x-slot>

    <!-- Main Content Section -->
    <div class="py-6 mt-20 ml-4 sm:ml-64">
        <div class="w-full mx-auto max-w-7xl sm:px-6 lg:px-8">

            <!-- Breadcrumb Navigation -->
            <x-bread-crumb-navigation />

            <div class="overflow-hidden bg-gray-800 rounded-lg shadow-xl">
                <div class="bg-gray-800 p-4 rounded-lg shadow-md">
                    <h2 class="text-xl font-semibold text-white">Create New Permission</h2>
                </div>
                
                <div class="p-6">
                    <form method="POST" action="{{ route('permissions.store') }}">
                        @csrf
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Permission Name -->
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-300 mb-2">
                                    Permission Name *
                                </label>
                                <input type="text" name="name" id="name" value="{{ old('name') }}" required
                                    placeholder="e.g., create-products, view-reports"
                                    class="w-full px-3 py-2 bg-gray-700 text-white rounded border border-gray-600 focus:outline-none focus:ring-2 focus:ring-indigo-500 @error('name') border-red-500 @enderror" />
                                @error('name')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                                <p class="text-gray-400 text-xs mt-1">Use format: action-module (e.g., create-users, view-reports)</p>
                            </div>

                            <!-- Description -->
                            <div>
                                <label for="description" class="block text-sm font-medium text-gray-300 mb-2">
                                    Description
                                </label>
                                <input type="text" name="description" id="description" value="{{ old('description') }}"
                                    placeholder="Brief description of the permission"
                                    class="w-full px-3 py-2 bg-gray-700 text-white rounded border border-gray-600 focus:outline-none focus:ring-2 focus:ring-indigo-500 @error('description') border-red-500 @enderror" />
                                @error('description')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Permission Examples -->
                        <div class="mt-6 p-4 bg-gray-700 rounded">
                            <h4 class="text-white font-medium mb-3">Common Permission Examples:</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-2 text-sm">
                                <div class="text-gray-300"><code>view-dashboard</code> - Can view dashboard</div>
                                <div class="text-gray-300"><code>create-users</code> - Can create users</div>
                                <div class="text-gray-300"><code>edit-products</code> - Can edit products</div>
                                <div class="text-gray-300"><code>delete-customers</code> - Can delete customers</div>
                                <div class="text-gray-300"><code>view-reports</code> - Can view reports</div>
                                <div class="text-gray-300"><code>manage-system</code> - Can manage system</div>
                            </div>
                        </div>

                        <!-- Submit Buttons -->
                        <div class="flex justify-end space-x-4 mt-8">
                            <a href="{{ route('permissions.index') }}"
                                class="px-6 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded font-semibold">
                                Cancel
                            </a>
                            <button type="submit"
                                class="px-6 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded font-semibold">
                                Create Permission
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>