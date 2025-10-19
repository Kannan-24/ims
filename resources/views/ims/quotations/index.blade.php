<x-app-layout>
    <x-slot name="title">
        {{ __('Quotation Management') }} - {{ config('app.name', 'IMS') }}
    </x-slot>

    <div class="bg-white min-h-screen" x-data="quotationIndexManager()" x-init="init()">
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
                            <span class="text-sm font-medium text-gray-500">Quotations</span>
                        </div>
                    </li>
                </ol>
            </nav>
        </div>

        <!-- Header -->
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Quotation Management</h1>
                    <p class="text-sm text-gray-600 mt-1">Manage and track all your quotations</p>
                </div>
                <div class="flex items-center space-x-3">
                    <!-- New Quotation Button -->
                    @if (!$quotations->isEmpty())
                        <a href="{{ route('quotations.create') }}"
                            class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors">
                            <i class="fas fa-plus w-4 h-4 mr-2"></i>
                            New Quotation
                        </a>
                    @endif
                </div>
            </div>
        </div>

        <!-- Content -->
        <div class="p-6">
            <!-- Search and Filters -->
            <div class="bg-white border border-gray-200 rounded-lg shadow-sm mb-6">
                <div class="p-4">
                    <form method="GET" action="{{ route('quotations.index') }}"
                        class="flex flex-wrap items-end gap-4">
                        <div class="flex-1 min-w-[260px]">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Search</label>
                            <input type="text" name="search" value="{{ request('search') }}"
                                placeholder="Search by Quotation Code or Company Name"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">From Date</label>
                            <input type="date" name="from" value="{{ request('from') }}"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">To Date</label>
                            <input type="date" name="to" value="{{ request('to') }}"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>
                        <div class="flex items-center space-x-2">
                            <button type="submit"
                                class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium transition-colors">
                                <i class="fas fa-search mr-2"></i>Filter
                            </button>
                            <a href="{{ route('quotations.index') }}"
                                class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg font-medium transition-colors">
                                <i class="fas fa-times mr-2"></i>Reset
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Quotations Table -->
            <div class="bg-white border border-gray-200 rounded-lg shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <div class="flex items-center justify-between">
                        <h2 class="text-lg font-semibold text-gray-900">Quotations List</h2>
                        <div class="text-sm text-gray-500">
                            Total:
                            {{ $quotations instanceof \Illuminate\Pagination\Paginator || $quotations instanceof \Illuminate\Pagination\LengthAwarePaginator ? $quotations->total() : $quotations->count() }}
                            quotations
                        </div>
                    </div>
                </div>

                @if ($quotations->isEmpty())
                    <div class="text-center py-12">
                        <i class="fas fa-file-alt text-gray-300 text-4xl mb-4"></i>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">No quotations found</h3>
                        <p class="text-gray-500 mb-6">Get started by creating your first quotation.</p>
                        <a href="{{ route('quotations.create') }}"
                            class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium">
                            <i class="fas fa-plus mr-2"></i>Create Quotation
                        </a>
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        #</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Quotation Details</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Customer</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Amounts</th>
                                    <th
                                        class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach ($quotations as $quotation)
                                    <tr class="hover:bg-gray-50 transition-colors">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $quotations instanceof \Illuminate\Pagination\LengthAwarePaginator ||
                                            $quotations instanceof \Illuminate\Pagination\Paginator
                                                ? $loop->iteration + ($quotations->currentPage() - 1) * $quotations->perPage()
                                                : $loop->iteration }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div
                                                    class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                                                    <i class="fas fa-file-alt text-blue-600"></i>
                                                </div>
                                                <div class="ml-4">
                                                    <div class="text-sm font-medium text-gray-900">
                                                        {{ $quotation->quotation_code }}</div>
                                                    <div
                                                        class="text-xs inline-flex px-2 py-0.5 rounded-full bg-gray-100 text-gray-700 mt-1">
                                                        {{ \Illuminate\Support\Carbon::parse($quotation->quotation_date)->format('d M Y') }}
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">{{ $quotation->customer->company_name }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-semibold text-gray-900">
                                                ₹{{ number_format($quotation->total, 2) }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <div class="flex items-center justify-end space-x-3">
                                                <a href="{{ route('emails.create') }}?quotation_id={{ $quotation->id }}"
                                                    class="text-green-600 hover:text-green-900 transition-colors"
                                                    title="Email Quotation">
                                                    <i class="fas fa-envelope"></i>
                                                </a>
                                                <a href="{{ route('quotations.pdf', $quotation->id) }}"
                                                    class="text-blue-600 hover:text-blue-900 transition-colors"
                                                    title="Download PDF" target="_blank">
                                                    <i class="fas fa-file-pdf"></i>
                                                </a>
                                                <a href="{{ route('quotations.show', $quotation) }}"
                                                    class="text-indigo-600 hover:text-indigo-900 transition-colors"
                                                    title="View Details">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('quotations.edit', $quotation) }}"
                                                    class="text-yellow-600 hover:text-yellow-900 transition-colors"
                                                    title="Edit Quotation">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form action="{{ route('quotations.destroy', $quotation) }}"
                                                    method="POST" class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                        class="text-red-600 hover:text-red-900 transition-colors"
                                                        title="Delete Quotation"
                                                        onclick="return confirm('Are you sure you want to delete this quotation?')">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    @if (method_exists($quotations, 'hasPages') && $quotations->hasPages())
                        <div class="px-6 py-4 border-t border-gray-200">
                            {{ $quotations->links() }}
                        </div>
                    @endif
                @endif
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script>
        function quotationIndexManager() {
            return {
                init() {}
            }
        }
    </script>
</x-app-layout>
