@php
    $title = 'Service Management';
@endphp

<x-app-layout>
    <x-slot name="title">
        {{ __('Service Management') }} - {{ config('app.name', 'IMS') }}
    </x-slot>

    <div class="bg-white" x-data="serviceManager()" x-init="init()">
        <!-- Breadcrumbs -->
        <div class="px-6 py-3 bg-gray-50 border-b border-gray-200">
            <nav class="flex" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-3">
                    <li class="inline-flex items-center">
                        <a href="{{ route('dashboard') }}"
                            class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-blue-600">
                            <i class="fas fa-home mr-2"></i>
                            Dashboard
                        </a>
                    </li>
                    <li aria-current="page">
                        <div class="flex items-center">
                            <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                            <span class="text-sm font-medium text-gray-500">Services</span>
                        </div>
                    </li>
                </ol>
            </nav>
        </div>

        <!-- Header -->
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Service Management</h1>
                    <p class="text-sm text-gray-600 mt-1">Manage your service catalog and details</p>
                </div>
                <div class="flex items-center space-x-3">
                    @if (!$services->isEmpty())
                        <a href="{{ route('services.create') }}"
                            class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors">
                            <i class="fas fa-plus w-4 h-4 mr-2"></i>
                            New Service
                        </a>
                    @endif
                </div>
            </div>
        </div>

        <!-- Content -->
        <div class="p-6">
            <!-- Services Table -->
            <div class="bg-white border border-gray-200 rounded-lg shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <div class="flex items-center justify-between">
                        <h2 class="text-lg font-semibold text-gray-900">Service Directory</h2>
                        <div class="flex items-center space-x-2">
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                    <i class="fas fa-search text-gray-400"></i>
                                </div>
                                <form method="GET" action="{{ route('services.index') }}" class="flex">
                                    <input type="text" name="search" value="{{ request('search') }}"
                                        placeholder="Search Service Name or HSN Code..." id="searchInput"
                                        class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                    <button type="submit"
                                        class="ml-2 px-4 py-2 bg-blue-600 text-white rounded-lg text-sm hover:bg-blue-700">Search</button>
                                    @if (request('search'))
                                        <a href="{{ route('services.index') }}"
                                            class="ml-2 px-4 py-2 bg-gray-500 text-white rounded-lg text-sm hover:bg-gray-600">Reset</a>
                                    @endif
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    @if ($services->isEmpty())
                        <div class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center justify-center">
                                <i class="fas fa-concierge-bell text-gray-300 text-4xl mb-4"></i>
                                <h3 class="text-lg font-medium text-gray-900 mb-2">No services found</h3>
                                <p class="text-sm text-gray-500 mb-4">
                                    @if (request('search'))
                                        Try adjusting your search terms.
                                    @else
                                        Get started by adding your first service.
                                    @endif
                                </p>
                                <a href="{{ route('services.create') }}"
                                    class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors">
                                    <i class="fas fa-plus w-4 h-4 mr-2"></i>
                                    Add Your First Service
                                </a>
                            </div>
                        </div>
                    @else
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        #</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Service Info</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        HSN Code</th>
                                    <th
                                        class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200" id="serviceTableBody">
                                @foreach ($services as $service)
                                    <tr class="hover:bg-gray-50 service-row" data-service-id="{{ $service->id }}"
                                        data-service-name="{{ $service->name }}"
                                        :class="selectedRowIndex === {{ $loop->index }} ? 'bg-blue-50 ring-2 ring-blue-500' :
                                            ''">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $loop->iteration }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div
                                                    class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center mr-3">
                                                    <i class="fas fa-concierge-bell text-blue-600"></i>
                                                </div>
                                                <div>
                                                    <div class="text-sm font-medium text-gray-900">{{ $service->name }}
                                                    </div>
                                                    <div class="text-sm text-gray-500">
                                                        {{ Str::limit($service->description ?? 'No description', 50) }}
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                {{ $service->hsn_code }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <div class="flex items-center justify-end space-x-2">
                                                <a href="{{ route('services.show', $service) }}"
                                                    class="text-blue-600 hover:text-blue-900 transition-colors"
                                                    title="View Service">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('services.edit', $service) }}"
                                                    class="text-green-600 hover:text-green-900 transition-colors"
                                                    title="Edit Service">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form method="POST"
                                                    action="{{ route('services.destroy', $service) }}"
                                                    onsubmit="return confirm('Are you sure you want to delete the service \'{{ $service->name }}\'?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                        class="text-red-600 hover:text-red-900 transition-colors"
                                                        title="Delete Service">
                                                        <i class="fas fa-trash-alt"></i>
                                                    </button>
                                                </form>
                                            </div>
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

    <script>

        function serviceManager() {
            return {
                selectedRowIndex: null,
                init() {
                    const rows = document.querySelectorAll('.service-row');
                    rows.forEach((row, index) => {
                        row.addEventListener('click', () => {
                            this.selectedRowIndex = index;
                        });
                    });
                },
            };
        }
    </script>
</x-app-layout>
