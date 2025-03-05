<x-app-layout>
    <div class="py-6 mt-20 ml-4 sm:ml-64">
        <div class="w-full max-w-4xl px-6 mx-auto">
            <x-bread-crumb-navigation />

            <!-- Supplier Edit Form -->
            <div class="p-8 bg-white border border-gray-200 rounded-lg shadow-lg">
                <form action="{{ route('suppliers.update', $supplier->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-4">
                        <label class="block mb-2 font-bold">Supplier ID:</label>
                        <input type="text" name="supplier_id" id="supplier_id" class="w-full px-4 py-2 border rounded-lg" value="{{ $supplier->supplier_id }}" required>
                    </div>

                    <div class="mb-4">
                        <label class="block mb-2 font-bold">Supplier Name:</label>
                        <input type="text" name="supplier_name" id="supplier_name" class="w-full px-4 py-2 border rounded-lg" value="{{ $supplier->supplier_name }}" required>
                    </div>

                    <div class="mb-4">
                        <label class="block mb-2 font-bold">Contact Person:</label>
                        <input type="text" name="contact_person" id="contact_person" class="w-full px-4 py-2 border rounded-lg" value="{{ $supplier->contact_person }}" required>
                    </div>

                    <div class="mb-4">
                        <label class="block mb-2 font-bold">Email:</label>
                        <input type="email" name="email" id="email" class="w-full px-4 py-2 border rounded-lg" value="{{ $supplier->email }}" required>
                    </div>

                    <div class="mb-4">
                        <label class="block mb-2 font-bold">Phone Number:</label>
                        <input type="text" name="phone_number" id="phone_number" class="w-full px-4 py-2 border rounded-lg" value="{{ $supplier->phone_number }}" required>
                    </div>

                    <div class="mb-4">
                        <label class="block mb-2 font-bold">Address:</label>
                        <input type="text" name="address" id="address" class="w-full px-4 py-2 border rounded-lg" value="{{ $supplier->address }}" required>
                    </div>

                    <div class="mb-4">
                        <label class="block mb-2 font-bold">City:</label>
                        <input type="text" name="city" id="city" class="w-full px-4 py-2 border rounded-lg" value="{{ $supplier->city }}" required>
                    </div>

                    <div class="mb-4">
                        <label class="block mb-2 font-bold">State:</label>
                        <input type="text" name="state" id="state" class="w-full px-4 py-2 border rounded-lg" value="{{ $supplier->state }}" required>
                    </div>

                    <div class="mb-4">
                        <label class="block mb-2 font-bold">Postal Code:</label>
                        <input type="text" name="postal_code" id="postal_code" class="w-full px-4 py-2 border rounded-lg" value="{{ $supplier->postal_code }}" required>
                    </div>

                    <div class="mb-4">
                        <label class="block mb-2 font-bold">Country:</label>
                        <input type="text" name="country" id="country" class="w-full px-4 py-2 border rounded-lg" value="{{ $supplier->country }}" required>
                    </div>

                    <div class="mb-4">
                        <label class="block mb-2 font-bold">GST Number:</label>
                        <input type="text" name="gst" id="gst" class="w-full px-4 py-2 border rounded-lg" value="{{ $supplier->gst }}" required>
                    </div>

                    <div class="flex justify-end">
                        <button type="submit" class="px-4 py-2 text-white bg-blue-500 rounded-lg hover:bg-blue-600">
                            Update Supplier
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>