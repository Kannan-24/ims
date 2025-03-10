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
            <div class="p-8 bg-white border border-gray-200 rounded-lg shadow-lg relative">
                <!-- Header Section -->
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-xl font-bold text-gray-700">Customer Details</h2>
                    <div class="flex gap-2">
                        <a href="{{ route('customers.edit', $customer->id) }}" class="flex items-center px-4 py-2 text-white bg-green-500 rounded-lg hover:bg-green-600 transition">
                            Edit
                        </a>
                        <form action="{{ route('customers.destroy', $customer->id) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="flex items-center px-4 py-2 text-white bg-red-500 rounded-lg hover:bg-red-600 transition">
                                Delete
                            </button>
                        </form>
                    </div>
                </div>

                <hr class="my-6 border-gray-300">

                <!-- Customer Info -->
                <div class="space-y-2 text-gray-600">
                    <p><strong>Customer Name:</strong> {{ $customer->company_name }}</p>
                    <p><strong>Address:</strong> {{ $customer->address }}</p>
                    <p><strong>City:</strong> {{ $customer->city }}</p>
                    <p><strong>State:</strong> {{ $customer->state }}</p>
                    <p><strong>ZIP Code:</strong> {{ $customer->zip_code }}</p>
                    <p><strong>Country:</strong> {{ $customer->country }}</p>
                    <p><strong>GST Number:</strong> {{ $customer->gst_number }}</p>
                </div>

                <!-- Contact Persons Section -->
                @if (!$customer->contactPersons->isEmpty())
                    <h2 class="text-xl font-bold text-gray-700 mt-6">Contact Persons</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                        @foreach ($customer->contactPersons as $contact)
                            <div class="p-6 rounded-lg shadow-md transition-all transform hover:scale-105 bg-gradient-to-r from-blue-400 to-purple-500 text-white">
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
</x-app-layout>
