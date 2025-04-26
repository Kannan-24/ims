<x-app-layout>

    <x-slot name="title">
        {{ __('Product List') }} - {{ config('app.name', 'ATMS') }}
    </x-slot>

    <!-- Main Content Section -->
    <div class="py-6 mt-20 ml-4 sm:ml-64">
        <div class="w-full mx-auto max-w-7xl sm:px-6 lg:px-8">

            <x-bread-crumb-navigation />

            <div class="overflow-hidden bg-gray-800 rounded-lg shadow-xl">
                <div class="bg-gray-800 p-4 rounded-lg shadow-md">
                    <form method="GET" action="{{ route('products.index') }}"
                        class="flex flex-wrap items-center gap-4">

                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="Search Product Name or HSN Code"
                            class="flex-1 min-w-[200px] px-3 py-2 bg-gray-700 text-white rounded border border-gray-600 focus:outline-none focus:ring-2 focus:ring-indigo-500" />

                        <button type="submit"
                            class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded font-semibold">Filter</button>
                        <a href="{{ route('products.index') }}"
                            class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded font-semibold">Reset</a>
                    </form>
                </div>
                <div class="p-4 overflow-x-auto">
                    @if ($products->isEmpty())
                        <div class="text-center text-gray-300">
                            {{ __('No products found.') }}
                        </div>
                    @else
                        <table class="min-w-full text-left border-collapse table-auto">
                            <thead>
                                <tr class="text-sm text-gray-300 bg-gray-700 uppercase tracking-wider">
                                    <th class="px-6 py-4 border-b-2 border-gray-600 cursor-pointer" onclick="sortTable(0)">#
                                    </th>
                                    <th class="px-6 py-4 border-b-2 border-gray-600 cursor-pointer" onclick="sortTable(1)">
                                        Name</th>
                                    <th class="px-6 py-4 border-b-2 border-gray-600">Unit Type</th>
                                    <th class="px-6 py-4 border-b-2 border-gray-600 cursor-pointer" onclick="sortTable(2)">
                                        HSN Code</th>
                                    <th class="px-6 py-4 border-b-2 border-gray-600 text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="text-sm text-gray-300 divide-y divide-gray-700" id="productTable">
                                @foreach ($products as $product)
                                    <tr class="hover:bg-gray-700 transition duration-200">
                                        <td class="px-6 py-4">{{ $loop->iteration }}</td>
                                        <td class="px-6 py-4">{{ $product->name }}</td>
                                        <td class="px-6 py-4">{{ $product->unit_type }}</td>
                                        <td class="px-6 py-4">{{ $product->hsn_code }}</td>
                                        <td class="px-6 py-4 flex justify-center gap-3">
                                            <a href="{{ route('products.show', $product) }}"
                                                class="text-blue-400 hover:text-blue-600 transition duration-300"
                                                title="View">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('products.edit', $product) }}"
                                                class="text-yellow-400 hover:text-yellow-600 transition duration-300"
                                                title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('products.destroy', $product) }}" method="POST"
                                                class="inline">
                                                @csrf @method('DELETE')
                                                <button type="submit"
                                                    class="text-red-400 hover:text-red-600 transition duration-300"
                                                    title="Delete"
                                                    onclick="return confirm('Are you sure you want to delete this product?')">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
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

</x-app-layout>
