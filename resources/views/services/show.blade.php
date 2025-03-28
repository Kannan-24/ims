<x-app-layout>
    <x-slot name="title">
        {{ __('Service Details') }} - {{ config('app.name', 'ATMS') }}
    </x-slot>

    <div class="py-6 mt-20 ml-4 sm:ml-64">
        <div class="w-full mx-auto max-w-7xl sm:px-6 lg:px-8">
            <x-bread-crumb-navigation />

            <div class="p-8 bg-gray-800 border border-gray-700 rounded-lg shadow-lg relative">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-3xl font-bold text-gray-200">Service Details</h2>
                    <div class="flex gap-2">
                        <a href="{{ route('services.edit', $service->id) }}"
                            class="flex items-center px-4 py-2 text-white bg-green-500 rounded-lg hover:bg-green-600 transition">
                            Edit
                        </a>
                        <form action="{{ route('services.destroy', $service->id) }}" method="POST" class="inline">
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
                    <p><strong>Service Name:</strong> {{ $service->name }}</p>
                    <p><strong>Description:</strong> {{ $service->description }}</p>
                    <p><strong>HSN Code:</strong> {{ $service->hsn_code }}</p>
                    <p><strong>GST Percentage:</strong> {{ $service->gst_percentage }}%</p>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
