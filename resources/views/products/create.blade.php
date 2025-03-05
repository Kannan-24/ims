<x-app-layout>
    <div class="py-6 mt-20 ml-4 sm:ml-64">
        <div class="w-full max-w-4xl px-6 mx-auto">
            <x-bread-crumb-navigation />

            <!-- Product Registration Form -->
            <div class="p-8 bg-white border border-gray-200 rounded-lg shadow-lg">
                <form action="{{ route('products.store') }}" method="POST">
                    @csrf

                    <div class="mb-4">
                        <label class="block mb-2 font-bold">Product Name:</label>
                        <input type="text" name="name" id="name" class="w-full px-4 py-2 border rounded-lg" required>
                    </div>

                    <div class="mb-4">
                        <label class="block mb-2 font-bold">Description:</label>
                        <textarea name="description" id="description" class="w-full px-4 py-2 border rounded-lg" required></textarea>
                    </div>

                    <div class="mb-4">
                        <label class="block mb-2 font-bold">HSN Code:</label>
                        <input type="text" name="hsn_code" id="hsn_code" class="w-full px-4 py-2 border rounded-lg" required>
                    </div>

                    <div class="mb-4">
                        <label class="block mb-2 font-bold">GST (%):</label>
                        <input type="number" step="0.01" name="gst" id="gst" class="w-full px-4 py-2 border rounded-lg" required>
                    </div>

                    <div class="mb-4">
                        <label class="block mb-2 font-bold">Supplier:</label>
                        <select name="supplier_id" id="supplier_id" class="w-full px-4 py-2 border rounded-lg" required>
                            <!-- Loop through suppliers to populate dropdown -->
                            @foreach($suppliers as $supplier)
                                <option value="{{ $supplier->id }}">{{ $supplier->supplier_name }} - {{ $supplier->state}}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="flex justify-end">
                        <button type="submit" class="px-4 py-2 text-white bg-blue-500 rounded-lg hover:bg-blue-600">
                            Create Product
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
