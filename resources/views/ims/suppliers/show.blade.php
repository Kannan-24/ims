<x-app-layout>
    <x-slot name="title">
        {{ __('Supplier Details') }} - {{ config('app.name', 'ATMS') }}
    </x-slot>

    <div class="py-6 mt-20 ml-4 sm:ml-64">
        <div class="w-full mx-auto max-w-7xl sm:px-6 lg:px-8">
            <x-bread-crumb-navigation />

            <div class="p-8 bg-gray-800 border border-gray-700 rounded-lg shadow-lg relative">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-3xl font-bold text-gray-200">Supplier Details</h2>
                    <div class="flex gap-2">
                        <a href="{{ route('suppliers.edit', $supplier->id) }}"
                            class="flex items-center px-4 py-2 text-white bg-green-500 rounded-lg hover:bg-green-600 transition">
                            Edit
                        </a>
                        <form action="{{ route('suppliers.destroy', $supplier->id) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                class="flex items-center px-4 py-2 text-white bg-red-500 rounded-lg hover:bg-red-600 transition">
                                Delete
                            </button>
                        </form>
                    </div>
                </div>

                <hr class="my-6 border-gray-600">

                <div class="space-y-4 text-gray-300">
                    <p><strong>Supplier Name:</strong> {{ $supplier->name }}</p>
                    <p><strong>Contact Person:</strong> {{ $supplier->contact_person }}</p>
                    <p><strong>Email:</strong> {{ $supplier->email }}</p>
                    <p><strong>Phone:</strong> {{ $supplier->phone_number }}</p>
                    <p><strong>Address:</strong> {{ $supplier->address }}</p>
                    <p><strong>City:</strong> {{ $supplier->city }}</p>
                    <p><strong>State:</strong> {{ $supplier->state }}</p>
                    <p><strong>Postal Code:</strong> {{ $supplier->postal_code }}</p>
                    <p><strong>Country:</strong> {{ $supplier->country }}</p>
                    <p><strong>GST Number:</strong> {{ $supplier->gst }}</p>
                </div>

                <div class="mt-8">
                    <h3 class="text-2xl font-bold text-gray-200 mb-4">Assigned Products</h3>

                    @if ($supplier->products->isEmpty())
                        <p class="text-gray-400">No products assigned to this supplier.</p>
                    @else
                        <ol class="list-decimal pl-5 text-gray-300">
                            @foreach ($supplier->products as $product)
                                <li>
                                    <a href="{{ route('products.show', $product->id) }}"
                                        class="text-blue-400 hover:underline">
                                        {{ $product->name }} - {{ $product->hsn_code }}
                                    </a>
                                </li>
                            @endforeach
                        </ol>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
