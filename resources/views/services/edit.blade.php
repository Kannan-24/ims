<x-app-layout>
    <div class="py-6 mt-20 ml-4 sm:ml-64">
        <div class="w-full max-w-4xl px-6 mx-auto">
            <x-bread-crumb-navigation />

            <!-- Service Edit Form -->
            <div class="p-8 bg-white border border-gray-200 rounded-lg shadow-lg">
                <form action="{{ route('services.update', $service->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-4">
                        <label class="block mb-2 font-bold">Service Name:</label>
                        <input type="text" name="name" id="name" class="w-full px-4 py-2 border rounded-lg" value="{{ $service->name }}" required>
                    </div>

                    <div class="mb-4">
                        <label class="block mb-2 font-bold">Description:</label>
                        <textarea name="description" id="description" class="w-full px-4 py-2 border rounded-lg" required>{{ $service->description }}</textarea>
                    </div>

                    <div class="mb-4">
                        <label class="block mb-2 font-bold">HSN Code:</label>
                        <input type="text" name="hsn_code" id="hsn_code" class="w-full px-4 py-2 border rounded-lg" value="{{ $service->hsn_code }}" required>
                    </div>

                    <div class="mb-4">
                        <label class="block mb-2 font-bold">GST (%):</label>
                        <input type="number" step="0.01" name="gst_percentage" id="gst_percentage" class="w-full px-4 py-2 border rounded-lg" value="{{ $service->gst_percentage }}" required>
                    </div>

                    <div class="flex justify-end">
                        <button type="submit" class="px-4 py-2 text-white bg-blue-500 rounded-lg hover:bg-blue-600">
                            Update Service
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>