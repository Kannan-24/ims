<x-app-layout>
    <div class="py-6 mt-20 ml-4 sm:ml-64">
        <div class="w-full mx-auto max-w-7xl sm:px-6 lg:px-8">
            <x-bread-crumb-navigation />

            <h2 class="text-3xl font-bold text-gray-200 mb-6">Create Customer</h2>

            <form action="{{ route('customers.store') }}" method="POST">
                @csrf

                <div class="mb-6">
                    <label class="block text-gray-300 font-semibold mb-2">Customer Name:</label>
                    <input type="text" name="company_name" class="w-full px-4 py-3 border border-gray-700 bg-gray-800 text-gray-300 rounded-lg shadow-md focus:outline-none focus:ring-2 focus:ring-blue-500 transition" required>
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
                    <label class="block text-gray-300 font-semibold mb-2">ZIP Code:</label>
                    <input type="text" name="zip_code" class="w-full px-4 py-3 border border-gray-700 bg-gray-800 text-gray-300 rounded-lg shadow-md focus:outline-none focus:ring-2 focus:ring-blue-500 transition" required>
                </div>

                <div class="mb-6">
                    <label class="block text-gray-300 font-semibold mb-2">Country:</label>
                    <input type="text" name="country" class="w-full px-4 py-3 border border-gray-700 bg-gray-800 text-gray-300 rounded-lg shadow-md focus:outline-none focus:ring-2 focus:ring-blue-500 transition" required>
                </div>

                <div class="mb-6">
                    <label class="block text-gray-300 font-semibold mb-2">GST Number:</label>
                    <input type="text" name="gst_number" class="w-full px-4 py-3 border border-gray-700 bg-gray-800 text-gray-300 rounded-lg shadow-md focus:outline-none focus:ring-2 focus:ring-blue-500 transition" required>
                </div>

                <div id="contact-persons-container">

                </div>

                <button type="button" id="add-contact-person" class="px-6 py-3 bg-green-500 hover:bg-green-600 text-white font-semibold rounded-lg shadow-md transition">
                    Add Contact Person
                </button>

                <div class="flex justify-end">
                    <button type="submit" class="px-6 py-3 bg-blue-500 hover:bg-blue-600 text-white font-semibold rounded-lg shadow-md transition">
                        Submit
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.getElementById('add-contact-person').addEventListener('click', function () {
            const container = document.getElementById('contact-persons-container');
            const index = container.children.length;
            const contactPersonDiv = document.createElement('div');
            contactPersonDiv.classList.add('mb-4', 'contact-person');

            contactPersonDiv.innerHTML = `
                <label class="block text-gray-300 font-semibold mb-2">Contact Person Name:</label>
                <input type="text" name="contact_persons[${index}][name]" class="w-full px-4 py-3 border border-gray-700 bg-gray-800 text-gray-300 rounded-lg shadow-md focus:outline-none focus:ring-2 focus:ring-blue-500 transition" required>

                <label class="block text-gray-300 font-semibold mb-2">Contact Person Phone:</label>
                <input type="text" name="contact_persons[${index}][phone_no]" class="w-full px-4 py-3 border border-gray-700 bg-gray-800 text-gray-300 rounded-lg shadow-md focus:outline-none focus:ring-2 focus:ring-blue-500 transition" required>

                <label class="block text-gray-300 font-semibold mb-2">Contact Person Email:</label>
                <input type="email" name="contact_persons[${index}][email]" class="w-full px-4 py-3 border border-gray-700 bg-gray-800 text-gray-300 rounded-lg shadow-md focus:outline-none focus:ring-2 focus:ring-blue-500 transition" required>
            `;

            container.appendChild(contactPersonDiv);
        });
    </script>
</x-app-layout>