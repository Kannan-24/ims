<x-app-layout>
    <x-slot name="title">
        {{ __('Stock Details') }} - {{ config('app.name', 'ATMS') }}
    </x-slot>

    <!-- Main Content Section -->
    <div class="py-6 mt-20 ml-4 sm:ml-64">
        <div class="w-full max-w-4xl px-6 mx-auto">
            <!-- Breadcrumb Navigation -->
            <x-bread-crumb-navigation />

            <!-- Stock Details Container -->
            <div class="p-8 bg-white border border-gray-200 rounded-lg shadow-lg relative">
                <!-- Header Section -->
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-xl font-bold text-gray-700">Stock Details</h2>
                    <div class="flex gap-2">
                        <a href="{{ route('stocks.edit', $stocks->id) }}"
                            class="flex items-center px-4 py-2 text-white bg-green-500 rounded-lg hover:bg-green-600 transition">
                            Edit
                        </a>
                        <form action="{{ route('stocks.destroy', $stocks->id) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                class="flex items-center px-4 py-2 text-white bg-red-500 rounded-lg hover:bg-red-600 transition">
                                Delete
                            </button>
                        </form>
                    </div>
                </div>

                <hr class="my-6 border-gray-300">

                <!-- Stock Info -->
                <div class="space-y-2 text-gray-600">
                    <p><strong>Product Name:</strong>
                        <a href="{{ route('products.show', $stocks->product->id) }}"
                            class="text-blue-500 hover:underline">
                            {{ $stocks->product->name ?? 'N/A' }}
                        </a>
                    </p>
                    <p><strong>Product HSN Code:</strong> {{ $stocks->product->hsn_code ?? 'N/A' }}</p>
                    <p><strong>Unit Type:</strong> {{ $stocks->unit_type }}</p>
                    <p><strong>Quantity:</strong> {{ $stocks->quantity }}</p>
                    <p><strong>Sold:</strong> {{ $stocks->sold }}</p>
                    <p><strong>Batch Code:</strong> {{ $stocks->batch_code }}</p>

                    <!-- Supplier Details Card -->
                    <h3 class="text-lg font-semibold text-gray-700 mb-2">Supplier Details</h3>
                    <a href="{{ route('suppliers.show', $stocks->supplier->id) }}">
                        <div class="p-4 bg-gray-100 border border-gray-200 rounded-lg shadow-md">
                            <p><strong>Supplier Name:</strong>
                                {{ $stocks->supplier->name ?? 'N/A' }}

                            </p>
                            <p><strong>State:</strong> {{ $stocks->supplier->state ?? 'N/A' }}</p>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
