<x-app-layout>
    <div class="py-6 mt-20 ml-4 sm:ml-64">
        <div class="w-full mx-auto max-w-7xl sm:px-6 lg:px-8">
            <x-bread-crumb-navigation />

            <h2 class="text-3xl font-bold text-gray-200 mb-6">Create Supplier</h2>

            <form action="{{ route('suppliers.store') }}" method="POST">
                @csrf

                <div class="mb-6">
                    <label class="block text-gray-300 font-semibold mb-2">Supplier Name:</label>
                    <input type="text" name="supplier_name" class="w-full px-4 py-3 border border-gray-700 bg-gray-800 text-gray-300 rounded-lg shadow-md focus:outline-none focus:ring-2 focus:ring-blue-500 transition" required>
                </div>

                <div class="mb-6">
                    <label class="block text-gray-300 font-semibold mb-2">Contact Person:</label>
                    <input type="text" name="contact_person" class="w-full px-4 py-3 border border-gray-700 bg-gray-800 text-gray-300 rounded-lg shadow-md focus:outline-none focus:ring-2 focus:ring-blue-500 transition" required>
                </div>

                <div class="mb-6">
                    <label class="block text-gray-300 font-semibold mb-2">Phone Number:</label>
                    <input type="text" name="phone_number" class="w-full px-4 py-3 border border-gray-700 bg-gray-800 text-gray-300 rounded-lg shadow-md focus:outline-none focus:ring-2 focus:ring-blue-500 transition" required>
                </div>

                <div class="mb-6">
                    <label class="block text-gray-300 font-semibold mb-2">Email:</label>
                    <input type="email" name="email" class="w-full px-4 py-3 border border-gray-700 bg-gray-800 text-gray-300 rounded-lg shadow-md focus:outline-none focus:ring-2 focus:ring-blue-500 transition" required>
                </div>

                <div class="mb-6">
                    <label class="block text-gray-300 font-semibold mb-2">Address:</label>
                    <input type="text" name="address" class="w-full px-4 py-3 border border-gray-700 bg-gray-800 text-gray-300 rounded-lg shadow-md focus:outline-none focus:ring-2 focus:ring-blue-500 transition" required>
                </div>

                <div class="mb-6">
                    <label class="block text-gray-300 font-semibold mb-2">City:</label>
                    <input type="text" name="city" class="w-full px-4 py-3 border border-gray-700 bg-gray-800 text-gray-300 rounded-lg shadow-md focus:outline-none focus:ring-2 focus:ring-blue-500 transition" required>
                </div>

                <div class="mb-6">
                    <label class="block text-gray-300 font-semibold mb-2">State:</label>
                    <input type="text" name="state" class="w-full px-4 py-3 border border-gray-700 bg-gray-800 text-gray-300 rounded-lg shadow-md focus:outline-none focus:ring-2 focus:ring-blue-500 transition" required>
                </div>

                <div class="mb-6">
                    <label class="block text-gray-300 font-semibold mb-2">Country:</label>
                    <input type="text" name="country" class="w-full px-4 py-3 border border-gray-700 bg-gray-800 text-gray-300 rounded-lg shadow-md focus:outline-none focus:ring-2 focus:ring-blue-500 transition" required>
                </div>

                <div class="mb-6">
                    <label class="block text-gray-300 font-semibold mb-2">Postal Code:</label>
                    <input type="text" name="postal_code" class="w-full px-4 py-3 border border-gray-700 bg-gray-800 text-gray-300 rounded-lg shadow-md focus:outline-none focus:ring-2 focus:ring-blue-500 transition" required>
                </div>

                <div class="mb-6">
                    <label class="block text-gray-300 font-semibold mb-2">GST Number:</label>
                    <input type="text" name="gst" class="w-full px-4 py-3 border border-gray-700 bg-gray-800 text-gray-300 rounded-lg shadow-md focus:outline-none focus:ring-2 focus:ring-blue-500 transition" required>
                </div>

                <div class="flex justify-end">
                    <button type="submit" class="px-6 py-3 bg-blue-500 hover:bg-blue-600 text-white font-semibold rounded-lg shadow-md transition">
                        Create Supplier
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>