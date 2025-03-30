<x-app-layout>
    <x-slot name="title">
        {{ __('Customer Details') }} - {{ config('app.name', 'ATMS') }}
    </x-slot>

    <div class="py-6 mt-20 ml-4 sm:ml-64">
        <div class="w-full mx-auto max-w-7xl sm:px-6 lg:px-8">
            <x-bread-crumb-navigation />

            <div class="p-8 bg-gray-800 border border-gray-700 rounded-lg shadow-lg relative">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-3xl font-bold text-gray-200">Customer Details</h2>
                    <div class="flex gap-2">
                        <a href="{{ route('customers.edit', $customer->id) }}"
                            class="flex items-center px-4 py-2 text-white bg-green-500 rounded-lg hover:bg-green-600 transition">
                            Edit
                        </a>
                        <form action="{{ route('customers.destroy', $customer->id) }}" method="POST" class="inline">
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
                    <p><strong>Customer Name:</strong> {{ $customer->company_name }}</p>
                    <p><strong>Address:</strong> {{ $customer->address }}</p>
                    <p><strong>City:</strong> {{ $customer->city }}</p>
                    <p><strong>State:</strong> {{ $customer->state }}</p>
                    <p><strong>ZIP Code:</strong> {{ $customer->zip_code }}</p>
                    <p><strong>Country:</strong> {{ $customer->country }}</p>
                    <p><strong>GST Number:</strong> {{ $customer->gst_number }}</p>
                </div>

                <div class="mt-8">
                    <h3 class="text-2xl font-bold text-gray-200 mb-4">Contact Persons</h3>
                
                    @if ($customer->contactPersons->isEmpty())
                        <p class="text-gray-400">No contact persons available for this customer.</p>
                    @else
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @foreach ($customer->contactPersons as $contact)
                                <div class="p-6 rounded-lg shadow-md bg-gray-700 border border-gray-600 text-gray-300 hover:bg-gray-600 transition">
                                    <p class="text-lg font-semibold">{{ $contact->name }}</p>
                                    <p class="text-sm mt-1"><strong>Phone:</strong> {{ $contact->phone_no }}</p>
                                    <p class="text-sm"><strong>Email:</strong> {{ $contact->email ?? 'N/A' }}</p>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
                
            </div>
        </div>
    </div>
</x-app-layout>
