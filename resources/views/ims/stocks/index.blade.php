<x-app-layout>
    <x-slot name="title">
        {{ __('Stock Management') }} - {{ config('app.name', 'IMS') }}
    </x-slot>

    <div class="bg-white" x-data="stockManager()" x-init="init()">
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
                            <span class="text-sm font-medium text-gray-500">Stock Management</span>
                        </div>
                    </li>
                </ol>
            </nav>
        </div>

        <!-- Header -->
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Stock Management</h1>
                    <p class="text-sm text-gray-600 mt-1">Manage inventory levels and stock entries</p>
                </div>
                <div class="flex items-center space-x-3">
                    <!-- New Stock Button -->
                    @if (!$stocks->isEmpty())
                        <a href="{{ route('stocks.create') }}"
                            class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors">
                            <i class="fas fa-plus w-4 h-4 mr-2"></i>
                            Add Stock Entry
                        </a>
                    @endif
                </div>
            </div>
        </div>

        <!-- Content -->
        <div class="p-6">
            <!-- Stock Table -->
            <div class="bg-white border border-gray-200 rounded-lg shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <div class="flex items-center justify-between">
                        <h2 class="text-lg font-semibold text-gray-900">Stock Inventory</h2>
                        <div class="flex items-center space-x-2">
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                    <i class="fas fa-search text-gray-400"></i>
                                </div>
                                <form method="GET" action="{{ route('stocks.index') }}" class="flex">
                                    <input type="text" name="search" value="{{ request('search') }}"
                                        placeholder="Search stocks..." id="searchInput"
                                        class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                    <button type="submit"
                                        class="ml-2 px-4 py-2 bg-blue-600 text-white rounded-lg text-sm hover:bg-blue-700">Search</button>
                                    @if (request('search'))
                                        <a href="{{ route('stocks.index') }}"
                                            class="ml-2 px-4 py-2 bg-gray-500 text-white rounded-lg text-sm hover:bg-gray-600">Reset</a>
                                    @endif
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Product</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Batch Code & Entry Type</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Supplier</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Quantity</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Sold</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Date</th>
                                <th
                                    class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200" id="stockTableBody">
                            @forelse ($stocks as $stock)
                                <tr class="hover:bg-gray-50 stock-row" data-stock-id="{{ $stock->id }}">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div
                                                class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center mr-3">
                                                <i class="fas fa-box text-blue-600"></i>
                                            </div>
                                            <div>
                                                <div class="text-sm font-medium text-gray-900">
                                                    {{ $stock->product->name ?? 'N/A' }}
                                                </div>
                                                <div class="text-sm text-gray-500">
                                                    {{ $stock->product->hsn_code ?? 'N/A' }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div>
                                                <div class="text-sm font-medium text-gray-900">
                                                    {{ $stock->batch_code ?? 'N/A' }}
                                                </div>
                                                <div class="text-sm text-gray-500 mt-1">
                                                    <span
                                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $stock->entry_type === 'purchase' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800' }}">
                                                        <i
                                                            class="fas {{ $stock->entry_type === 'purchase' ? 'fa-shopping-cart' : 'fa-edit' }} mr-1"></i>
                                                        {{ ucfirst($stock->entry_type) }}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ $stock->supplier->name ?? 'N/A' }}</div>
                                        <div class="text-sm text-gray-500">
                                            {{ $stock->supplier->contact_person ?? 'N/A' }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ number_format($stock->quantity) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ number_format($stock->sold) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $stock->created_at->format('M d, Y') }}
                                        <div class="text-xs text-gray-400">
                                            {{ $stock->created_at->format('h:i A') }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <div class="flex items-center justify-end space-x-2">
                                            <a href="{{ route('stocks.show', $stock) }}"
                                                class="text-blue-600 hover:text-blue-900 transition-colors"
                                                title="View Stock Details">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            @if ($stock->entry_type !== 'purchase')
                                                <a href="{{ route('stocks.edit', $stock) }}"
                                                    class="text-indigo-600 hover:text-indigo-900 transition-colors"
                                                    title="Edit Stock">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                            @endif
                                            <form action="{{ route('stocks.destroy', $stock) }}" method="POST"
                                                class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="text-red-600 hover:text-red-900 transition-colors"
                                                    title="Delete Stock"
                                                    onclick="return confirm('Are you sure you want to delete this stock entry?')">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="10" class="px-6 py-8 text-center">
                                        <div class="flex flex-col items-center">
                                            <i class="fas fa-box-open text-gray-400 text-4xl mb-4"></i>
                                            <h3 class="text-lg font-medium text-gray-900 mb-2">No stock entries found
                                            </h3>
                                            <p class="text-gray-500 mb-4">Get started by adding your first stock entry.
                                            </p>
                                            <a href="{{ route('stocks.create') }}"
                                                class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors">
                                                <i class="fas fa-plus w-4 h-4 mr-2"></i>
                                                Add Stock Entry
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if ($stocks->hasPages())
                    <div class="px-6 py-4 border-t border-gray-200">
                        {{ $stocks->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Floating Action Button for Mobile -->
    <div class="fixed bottom-6 right-6 md:hidden">
        <div class="flex flex-col space-y-2">
            <a href="{{ route('stocks.manual.create') }}"
                class="w-12 h-12 bg-green-600 hover:bg-green-700 text-white rounded-full shadow-lg flex items-center justify-center transition-colors">
                <i class="fas fa-plus text-sm"></i>
            </a>
            <a href="{{ route('stocks.create') }}"
                class="w-12 h-12 bg-blue-600 hover:bg-blue-700 text-white rounded-full shadow-lg flex items-center justify-center transition-colors">
                <i class="fas fa-boxes text-sm"></i>
            </a>
        </div>
    </div>

    <script>
        function stockManager() {
            return {
                selectedRowIndex: null,
                init() {
                    const rows = document.querySelectorAll('.stock-row');
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
