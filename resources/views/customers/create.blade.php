<x-app-layout>
    <div class="py-6 mt-20 ml-4 sm:ml-64">
        <div class="w-full mx-auto max-w-7xl sm:px-6 lg:px-8">
            <x-bread-crumb-navigation />

            <h2 class="text-3xl font-bold text-gray-200 mb-6">Create Customer</h2>

            <form action="{{ route('customers.store') }}" method="POST">
                @csrf

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    <div>
                        <label class="block text-gray-300 font-semibold mb-2">Customer Name:</label>
                        <input type="text" name="company_name"
                            class="w-full px-4 py-3 border border-gray-700 bg-gray-800 text-gray-300 rounded-lg shadow-md focus:outline-none focus:ring-2 focus:ring-blue-500 transition"
                            required>
                    </div>

                    <div>
                        <label class="block text-gray-300 font-semibold mb-2">Address:</label>
                        <input type="text" name="address"
                            class="w-full px-4 py-3 border border-gray-700 bg-gray-800 text-gray-300 rounded-lg shadow-md focus:outline-none focus:ring-2 focus:ring-blue-500 transition"
                            required>
                    </div>

                    <div>
                        <label class="block text-gray-300 font-semibold mb-2">City:</label>
                        <input type="text" name="city"
                            class="w-full px-4 py-3 border border-gray-700 bg-gray-800 text-gray-300 rounded-lg shadow-md focus:outline-none focus:ring-2 focus:ring-blue-500 transition"
                            required>
                    </div>

                    <div>
                        <label class="block text-gray-300 font-semibold mb-2">State:</label>
                        <input type="text" name="state"
                            class="w-full px-4 py-3 border border-gray-700 bg-gray-800 text-gray-300 rounded-lg shadow-md focus:outline-none focus:ring-2 focus:ring-blue-500 transition"
                            required>
                    </div>

                    <div>
                        <label class="block text-gray-300 font-semibold mb-2">ZIP Code:</label>
                        <input type="text" name="zip_code"
                            class="w-full px-4 py-3 border border-gray-700 bg-gray-800 text-gray-300 rounded-lg shadow-md focus:outline-none focus:ring-2 focus:ring-blue-500 transition"
                            required>
                    </div>

                    <div>
                        <label class="block text-gray-300 font-semibold mb-2">Country:</label>
                        <input type="text" name="country"
                            class="w-full px-4 py-3 border border-gray-700 bg-gray-800 text-gray-300 rounded-lg shadow-md focus:outline-none focus:ring-2 focus:ring-blue-500 transition"
                            required>
                    </div>

                    <div>
                        <label class="block text-gray-300 font-semibold mb-2">GST Number:</label>
                        <input type="text" name="gst_number"
                            class="w-full px-4 py-3 mb-6 border border-gray-700 bg-gray-800 text-gray-300 rounded-lg shadow-md focus:outline-none focus:ring-2 focus:ring-blue-500 transition"
                            required>
                    </div>
                </div>

                <div id="contact-persons-container">
                </div>

                <button type="button" id="add-contact-person"
                    class="px-4 py-2 bg-green-500 hover:bg-green-600 text-white font-semibold rounded-lg shadow-md transition">
                    Add Contact Person
                </button>

                <div class="flex justify-end mt-4">
                    <button type="submit"
                        class="px-6 py-3 bg-blue-500 hover:bg-blue-600 text-white font-semibold rounded-lg shadow-md transition">
                        Submit
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.getElementById('add-contact-person').addEventListener('click', function() {
            const container = document.getElementById('contact-persons-container');
            const index = container.children.length;
            const contactPersonDiv = document.createElement('div');
            contactPersonDiv.classList.add('mb-6', 'contact-person');

            contactPersonDiv.innerHTML = `
                <h3 class="text-2xl font-bold text-gray-200 mb-4">Contact Person ${index + 1}</h3>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-gray-300 font-semibold mb-2">Contact Person Name:</label>
                        <input type="text" name="contact_persons[${index}][name]" class="w-full px-4 py-3 border border-gray-700 bg-gray-800 text-gray-300 rounded-lg shadow-md focus:outline-none focus:ring-2 focus:ring-blue-500 transition" required>
                    </div>
                    <div>
                        <label class="block text-gray-300 font-semibold mb-2">Contact Person Phone:</label>
                        <input type="text" name="contact_persons[${index}][phone_no]" class="w-full px-4 py-3 border border-gray-700 bg-gray-800 text-gray-300 rounded-lg shadow-md focus:outline-none focus:ring-2 focus:ring-blue-500 transition" required>
                    </div>
                    <div>
                        <label class="block text-gray-300 font-semibold mb-2">Contact Person Email:</label>
                        <input type="email" name="contact_persons[${index}][email]" class="w-full px-4 py-3 border border-gray-700 bg-gray-800 text-gray-300 rounded-lg shadow-md focus:outline-none focus:ring-2 focus:ring-blue-500 transition" required>
                    </div>
                </div>
                <div class="mt-4">
                    <button type="button" class="px-4 py-2 bg-red-500 hover:bg-red-600 text-white font-semibold rounded-lg shadow-md transition remove-contact-person">
                        Remove
                    </button>
                </div>
            `;

            container.appendChild(contactPersonDiv);
            attachRemoveEvent(contactPersonDiv.querySelector('.remove-contact-person'));
        });

        function attachRemoveEvent(button) {
            button.addEventListener('click', function() {
                button.parentElement.parentElement.remove();
            });
        }
    </script>
</x-app-layout>