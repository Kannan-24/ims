<x-app-layout>
    <div class="py-6 mt-20 ml-4 sm:ml-64">
        <div class="w-full max-w-4xl px-6 mx-auto">
            <x-bread-crumb-navigation />

            <!-- Customer Edit Form -->
            <div class="p-8 bg-white border border-gray-200 rounded-lg shadow-lg">
                <form action="{{ route('customers.update', $customer->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-4">
                        <label class="block mb-2 font-bold">Customer ID:</label>
                        <input type="text" name="cid" id="cid" class="w-full px-4 py-2 border rounded-lg" value="{{ $customer->cid }}" required>
                    </div>

                    <div class="mb-4">
                        <label class="block mb-2 font-bold">Customer Name:</label>
                        <input type="text" name="name" id="name" class="w-full px-4 py-2 border rounded-lg" value="{{ $customer->name }}" required>
                    </div>

                    <div class="mb-4">
                        <label class="block mb-2 font-bold">Contact Person:</label>
                        <input type="text" name="contact_person" id="contact_person" class="w-full px-4 py-2 border rounded-lg" value="{{ $customer->contact_person }}" required>
                    </div>

                    <div class="mb-4">
                        <label class="block mb-2 font-bold">Email:</label>
                        <input type="email" name="email" id="email" class="w-full px-4 py-2 border rounded-lg" value="{{ $customer->email }}" required>
                    </div>

                    <div class="mb-4">
                        <label class="block mb-2 font-bold">Phone Number:</label>
                        <input type="text" name="phone" id="phone" class="w-full px-4 py-2 border rounded-lg" value="{{ $customer->phone }}" required>
                    </div>

                    <div class="mb-4">
                        <label class="block mb-2 font-bold">Address:</label>
                        <input type="text" name="address" id="address" class="w-full px-4 py-2 border rounded-lg" value="{{ $customer->address }}" required>
                    </div>

                    <div class="mb-4">
                        <label class="block mb-2 font-bold">City:</label>
                        <input type="text" name="city" id="city" class="w-full px-4 py-2 border rounded-lg" value="{{ $customer->city }}" required>
                    </div>

                    <div class="mb-4">
                        <label class="block mb-2 font-bold">State:</label>
                        <input type="text" name="state" id="state" class="w-full px-4 py-2 border rounded-lg" value="{{ $customer->state }}" required>
                    </div>

                    <div class="mb-4">
                        <label class="block mb-2 font-bold">ZIP Code:</label>
                        <input type="text" name="zip" id="zip" class="w-full px-4 py-2 border rounded-lg" value="{{ $customer->zip }}" required>
                    </div>

                    <div class="mb-4">
                        <label class="block mb-2 font-bold">Country:</label>
                        <input type="text" name="country" id="country" class="w-full px-4 py-2 border rounded-lg" value="{{ $customer->country }}" required>
                    </div>

                    <div class="mb-4">
                        <label class="block mb-2 font-bold">GST Number:</label>
                        <input type="text" name="gstno" id="gstno" class="w-full px-4 py-2 border rounded-lg" value="{{ $customer->gstno }}" required>
                    </div>

                    <div class="flex justify-end">
                        <button type="submit" class="px-4 py-2 text-white bg-blue-500 rounded-lg hover:bg-blue-600">
                            Update Customer
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>