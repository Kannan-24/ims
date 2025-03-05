<x-app-layout>
    <x-slot name="title">
        {{ __('Customer Details') }} - {{ config('app.name', 'ATMS') }}
    </x-slot>

    <!-- Main Content Section -->
    <div class="py-6 mt-20 ml-4 sm:ml-64">
        <div class="w-full max-w-4xl px-6 mx-auto">
            <!-- Breadcrumb Navigation -->
            <x-bread-crumb-navigation />

            <!-- Customer Details Container -->
            <div class="p-8 bg-white border border-gray-200 rounded-lg shadow-lg">
                <div class="mb-4">
                    <h3 class="text-lg font-semibold">Customer Name:</h3>
                    <p>{{ $customer->name }}</p>
                </div>
                <div class="mb-4">
                    <h3 class="text-lg font-semibold">Contact Person:</h3>
                    <p>{{ $customer->contact_person }}</p>
                </div>
                <div class="mb-4">
                    <h3 class="text-lg font-semibold">Email:</h3>
                    <p>{{ $customer->email }}</p>
                </div>
                <div class="mb-4">
                    <h3 class="text-lg font-semibold">Phone:</h3>
                    <p>{{ $customer->phone }}</p>
                </div>
                <div class="mb-4">
                    <h3 class="text-lg font-semibold">Address:</h3>
                    <p>{{ $customer->address }}</p>
                </div>
                <div class="mb-4">
                    <h3 class="text-lg font-semibold">City:</h3>
                    <p>{{ $customer->city }}</p>
                </div>
                <div class="mb-4">
                    <h3 class="text-lg font-semibold">State:</h3>
                    <p>{{ $customer->state }}</p>
                </div>
                <div class="mb-4">
                    <h3 class="text-lg font-semibold">ZIP Code:</h3>
                    <p>{{ $customer->zip }}</p>
                </div>
                <div class="mb-4">
                    <h3 class="text-lg font-semibold">Country:</h3>
                    <p>{{ $customer->country }}</p>
                </div>
                <div class="mb-4">
                    <h3 class="text-lg font-semibold">GST Number:</h3>
                    <p>{{ $customer->gstno }}</p>
                </div>
                <div class="flex justify-end gap-4">
                    <a href="{{ route('customers.edit', $customer->id) }}">
                        <button class="px-4 py-2 text-white bg-red-500 rounded hover:bg-green-700">Edit</button>
                    </a>
                    <form action="{{ route('customers.destroy', $customer->id) }}" method="POST" class="inline">
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