<x-app-layout>
    <div class="py-6 mt-20 ml-4 sm:ml-64">
        <div class="w-full mx-auto max-w-7xl sm:px-6 lg:px-8">
            <x-bread-crumb-navigation />

            <h2 class="text-3xl font-bold text-gray-200 mb-6">Edit Service</h2>

            <form action="{{ route('services.update', $service->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-6">
                    <label class="block text-gray-300 font-semibold mb-2">Service Name:</label>
                    <input type="text" name="name" id="name" class="w-full px-4 py-3 border border-gray-700 bg-gray-800 text-gray-300 rounded-lg shadow-md focus:outline-none focus:ring-2 focus:ring-blue-500 transition"
                        value="{{ $service->name }}" required>
                </div>

                <div class="mb-6">
                    <label class="block text-gray-300 font-semibold mb-2">Description:</label>
                    <textarea name="description" id="description" class="w-full px-4 py-3 border border-gray-700 bg-gray-800 text-gray-300 rounded-lg shadow-md focus:outline-none focus:ring-2 focus:ring-blue-500 transition" required>{{ $service->description }}</textarea>
                </div>

                <div class="mb-6">
                    <label class="block text-gray-300 font-semibold mb-2">HSN Code:</label>
                    <input type="text" name="hsn_code" id="hsn_code" class="w-full px-4 py-3 border border-gray-700 bg-gray-800 text-gray-300 rounded-lg shadow-md focus:outline-none focus:ring-2 focus:ring-blue-500 transition"
                        value="{{ $service->hsn_code }}" required>
                </div>

                <div class="mb-6">
                    <label class="block text-gray-300 font-semibold mb-2">GST (%):</label>
                    <input type="number" step="0.01" name="gst_percentage" id="gst_percentage" class="w-full px-4 py-3 border border-gray-700 bg-gray-800 text-gray-300 rounded-lg shadow-md focus:outline-none focus:ring-2 focus:ring-blue-500 transition"
                        value="{{ $service->gst_percentage }}" required>
                </div>

                <div class="flex justify-end">
                    <button type="submit" class="px-6 py-3 bg-blue-500 hover:bg-blue-600 text-white font-semibold rounded-lg shadow-md transition">
                        Update Service
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>