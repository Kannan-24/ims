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
                        <label class="block mb-2 font-bold">Customer Name:</label>
                        <input type="text" name="company_name" id="company_name" class="w-full px-4 py-2 border rounded-lg" value="{{ $customer->company_name }}" required>
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
                        <input type="text" name="zip_code" id="zip_code" class="w-full px-4 py-2 border rounded-lg" value="{{ $customer->zip_code }}" required>
                    </div>

                    <div class="mb-4">
                        <label class="block mb-2 font-bold">Country:</label>
                        <input type="text" name="country" id="country" class="w-full px-4 py-2 border rounded-lg" value="{{ $customer->country }}" required>
                    </div>

                    <div class="mb-4">
                        <label class="block mb-2 font-bold">GST Number:</label>
                        <input type="text" name="gst_number" id="gst_number" class="w-full px-4 py-2 border rounded-lg" value="{{ $customer->gst_number }}" required>
                    </div>

                    <div id="contact-persons-container">
                        @foreach ($customer->contactPersons as $index => $contactPerson)
                            <div class="mb-4 contact-person">
                                <label class="block mb-2 font-bold">Contact Person Name:</label>
                                <input type="text" name="contact_persons[{{ $index }}][name]" class="w-full px-4 py-2 border rounded-lg" value="{{ $contactPerson->name }}" required>

                                <label class="block mb-2 font-bold">Contact Person Phone:</label>
                                <input type="text" name="contact_persons[{{ $index }}][phone_no]" class="w-full px-4 py-2 border rounded-lg" value="{{ $contactPerson->phone_no }}" required>

                                <label class="block mb-2 font-bold">Contact Person Email:</label>
                                <input type="email" name="contact_persons[{{ $index }}][email]" class="w-full px-4 py-2 border rounded-lg" value="{{ $contactPerson->email }}" required>
                            </div>
                        @endforeach
                    </div>

                    <button type="button" id="add-contact-person" class="px-4 py-2 text-white bg-green-500 rounded-lg hover:bg-green-600">
                        Add Contact Person
                    </button>

                    <div class="flex justify-end mt-4">
                        <button type="submit" class="px-4 py-2 text-white bg-blue-500 rounded-lg hover:bg-blue-600">
                            Update Customer
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('add-contact-person').addEventListener('click', function () {
            const container = document.getElementById('contact-persons-container');
            const index = container.children.length;
            const contactPersonDiv = document.createElement('div');
            contactPersonDiv.classList.add('mb-4', 'contact-person');

            contactPersonDiv.innerHTML = `
                <label class="block mb-2 font-bold">Contact Person Name:</label>
                <input type="text" name="contact_persons[${index}][name]" class="w-full px-4 py-2 border rounded-lg" required>

                <label class="block mb-2 font-bold">Contact Person Phone:</label>
                <input type="text" name="contact_persons[${index}][phone_no]" class="w-full px-4 py-2 border rounded-lg" required>

                <label class="block mb-2 font-bold">Contact Person Email:</label>
                <input type="email" name="contact_persons[${index}][email]" class="w-full px-4 py-2 border rounded-lg" required>
            `;

            container.appendChild(contactPersonDiv);
        });
    </script>
</x-app-layout>