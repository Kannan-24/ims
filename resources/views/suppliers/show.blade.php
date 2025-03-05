<x-app-layout>
    <x-slot name="title">
        {{ __('Supplier Details') }} - {{ config('app.name', 'ATMS') }}
    </x-slot>

    <!-- Main Content Section -->
    <div class="py-6 mt-20 ml-4 sm:ml-64">
        <div class="w-full max-w-4xl px-6 mx-auto">
            <!-- Breadcrumb Navigation -->
            <x-bread-crumb-navigation />

            <!-- Supplier Details Container -->
            <div class="p-8 bg-white border border-gray-200 rounded-lg shadow-lg">
                <div class="mb-4">
                    <h3 class="text-lg font-semibold">Supplier Name:</h3>
                    <p>{{ $supplier->supplier_name }}</p>
                </div>
                <div class="mb-4">
                    <h3 class="text-lg font-semibold">Contact Person:</h3>
                    <p>{{ $supplier->contact_person }}</p>
                </div>
                <div class="mb-4">
                    <h3 class="text-lg font-semibold">Email:</h3>
                    <p>{{ $supplier->email }}</p>
                </div>
                <div class="mb-4">
                    <h3 class="text-lg font-semibold">Phone:</h3>
                    <p>{{ $supplier->phone_number }}</p>
                </div>
                <div class="mb-4">
                    <h3 class="text-lg font-semibold">Address:</h3>
                    <p>{{ $supplier->address }}</p>
                </div>
                <div class="mb-4">
                    <h3 class="text-lg font-semibold">City:</h3>
                    <p>{{ $supplier->city }}</p>
                </div>
                <div class="mb-4">
                    <h3 class="text-lg font-semibold">State:</h3>
                    <p>{{ $supplier->state }}</p>
                </div>
                <div class="mb-4">
                    <h3 class="text-lg font-semibold">Postal Code:</h3>
                    <p>{{ $supplier->postal_code }}</p>
                </div>
                <div class="mb-4">
                    <h3 class="text-lg font-semibold">Country:</h3>
                    <p>{{ $supplier->country }}</p>
                </div>
                <div class="mb-4">
                    <h3 class="text-lg font-semibold">GST Number:</h3>
                    <p>{{ $supplier->gst }}</p>
                </div>
                <div class="flex justify-end gap-4">
                    <a href="{{ route('suppliers.edit', $supplier->id) }}">
                        <button class="px-4 py-2 text-white bg-red-500 rounded hover:bg-green-700">Edit</button>
                    </a>
                    <form action="{{ route('suppliers.destroy', $supplier->id) }}" method="POST" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                            class="px-4 py-2 text-white bg-red-500 rounded hover:bg-red-700">Delete</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>