<x-app-layout>
    <div class="py-6 mt-20 ml-4 sm:ml-64">
        <div class="w-full mx-auto max-w-7xl sm:px-6 lg:px-8">
            <x-bread-crumb-navigation />

            <h2 class="text-3xl font-bold text-gray-200 mb-6">Edit Customer</h2>

            <form action="{{ route('customers.update', $customer->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    <div>
                        <label class="block text-gray-300 font-semibold mb-2">Customer ID:</label>
                        <input type="text" name="customer_id"
                            class="w-full px-4 py-3 border border-gray-700 bg-gray-800 text-gray-300 rounded-lg shadow-md focus:outline-none focus:ring-2 focus:ring-blue-500 transition"
                            value="{{ $customer->cid }}" readonly>
                    </div>

                    <div>
                        <label class="block text-gray-300 font-semibold mb-2">Customer Name:</label>
                        <input type="text" name="company_name"
                            class="w-full px-4 py-3 border border-gray-700 bg-gray-800 text-gray-300 rounded-lg shadow-md focus:outline-none focus:ring-2 focus:ring-blue-500 transition"
                            value="{{ $customer->company_name }}" required>
                    </div>

                    <div>
                        <label class="block text-gray-300 font-semibold mb-2">Address:</label>
                        <input type="text" name="address"
                            class="w-full px-4 py-3 border border-gray-700 bg-gray-800 text-gray-300 rounded-lg shadow-md focus:outline-none focus:ring-2 focus:ring-blue-500 transition"
                            value="{{ $customer->address }}" required>
                    </div>

                    <div>
                        <label class="block text-gray-300 font-semibold mb-2">City:</label>
                        <input type="text" name="city"
                            class="w-full px-4 py-3 border border-gray-700 bg-gray-800 text-gray-300 rounded-lg shadow-md focus:outline-none focus:ring-2 focus:ring-blue-500 transition"
                            value="{{ $customer->city }}" required>
                    </div>

                    <div>
                        <label class="block text-gray-300 font-semibold mb-2">State:</label>
                        <input type="text" name="state"
                            class="w-full px-4 py-3 border border-gray-700 bg-gray-800 text-gray-300 rounded-lg shadow-md focus:outline-none focus:ring-2 focus:ring-blue-500 transition"
                            value="{{ $customer->state }}" required>
                    </div>

                    <div>
                        <label class="block text-gray-300 font-semibold mb-2">ZIP Code:</label>
                        <input type="text" name="zip_code"
                            class="w-full px-4 py-3 border border-gray-700 bg-gray-800 text-gray-300 rounded-lg shadow-md focus:outline-none focus:ring-2 focus:ring-blue-500 transition"
                            value="{{ $customer->zip_code }}" required>
                    </div>

                    <div>
                        <label class="block text-gray-300 font-semibold mb-2">Country:</label>
                        <input type="text" name="country"
                            class="w-full px-4 py-3 border border-gray-700 bg-gray-800 text-gray-300 rounded-lg shadow-md focus:outline-none focus:ring-2 focus:ring-blue-500 transition"
                            value="{{ $customer->country }}" required>
                    </div>

                    <div>
                        <label class="block text-gray-300 font-semibold mb-2">GST Number:</label>
                        <div class="relative">
                            <input type="text" 
                                   name="gst_number" 
                                   id="gst_number"
                                   class="w-full px-4 py-3 border border-gray-700 bg-gray-800 text-gray-300 rounded-lg shadow-md focus:outline-none focus:ring-2 focus:ring-blue-500 transition"
                                   placeholder="Enter 15-digit GST number"
                                   maxlength="15"
                                   style="text-transform: uppercase;"
                                   value="{{ $customer->gst_number }}"
                                   required>
                            <div id="gst-loading" class="absolute right-3 top-3 hidden">
                                <div class="animate-spin rounded-full h-6 w-6 border-b-2 border-blue-500"></div>
                            </div>
                        </div>
                        <div id="gst-message" class="mt-2 text-sm hidden"></div>
                        <div id="auto-fill-message" class="mt-2 text-sm text-green-400 hidden">
                            ✓ Company details auto-filled from GST portal
                        </div>
                    </div>

                    <!-- Hidden fields for GST-related data -->
                    <input type="hidden" name="pan_number" id="pan_number" value="{{ $customer->pan_number }}">
                    <input type="hidden" name="gst_status" id="gst_status" value="{{ $customer->gst_status ?? 'Active' }}">
                    <input type="hidden" name="business_type" id="business_type" value="{{ $customer->business_type }}">
                    <input type="hidden" name="gst_registration_date" id="gst_registration_date" value="{{ $customer->gst_registration_date }}">
                    <input type="hidden" name="customer_id" id="customer_id" value="{{ $customer->id }}">
                </div>

                <div id="contact-persons-container">
                    @foreach ($customer->contactPersons as $index => $contactPerson)
                        <div class="mb-4 contact-person mt-6">
                            <h3 class="text-2xl font-bold text-gray-200 mb-4">Contact Person {{ $index + 1 }}</h3>
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                                <div>
                                    <label class="block text-gray-300 font-semibold mb-2">Contact Person Name:</label>
                                    <input type="text" name="contact_persons[{{ $index }}][name]"
                                        class="w-full px-4 py-3 border border-gray-700 bg-gray-800 text-gray-300 rounded-lg shadow-md focus:outline-none focus:ring-2 focus:ring-blue-500 transition"
                                        value="{{ $contactPerson->name }}" required>
                                </div>
                                <div>
                                    <label class="block text-gray-300 font-semibold mb-2">Contact Person Phone:</label>
                                    <input type="text" name="contact_persons[{{ $index }}][phone_no]"
                                        class="w-full px-4 py-3 border border-gray-700 bg-gray-800 text-gray-300 rounded-lg shadow-md focus:outline-none focus:ring-2 focus:ring-blue-500 transition"
                                        value="{{ $contactPerson->phone_no }}" required>
                                </div>
                                <div>
                                    <label class="block text-gray-300 font-semibold mb-2">Contact Person Email:</label>
                                    <input type="email" name="contact_persons[{{ $index }}][email]"
                                        class="w-full px-4 py-3 border border-gray-700 bg-gray-800 text-gray-300 rounded-lg shadow-md focus:outline-none focus:ring-2 focus:ring-blue-500 transition"
                                        value="{{ $contactPerson->email }}" required>
                                </div>
                            </div>
                            <div class="mt-4">
                                <button type="button" class="px-4 py-2 bg-red-500 hover:bg-red-600 text-white font-semibold rounded-lg shadow-md transition remove-contact-person">
                                    Remove
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>
                <button type="button" id="add-contact-person"
                    class="px-4 py-2 bg-green-500 hover:bg-green-600 text-white font-semibold rounded-lg shadow-md transition">
                    Add Contact Person
                </button>


                <div class="flex justify-end mt-6">
                    <button type="submit"
                        class="px-6 py-3 bg-blue-500 hover:bg-blue-600 text-white font-semibold rounded-lg shadow-md transition">
                        Update Customer
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
                button.parentElement.remove();
            });
        }

        document.querySelectorAll('.remove-contact-person').forEach(button => {
            attachRemoveEvent(button);
        });

        // GST Validation and Auto-fill functionality for edit form
        let gstValidationTimeout;
        const gstInput = document.getElementById('gst_number');
        const gstMessage = document.getElementById('gst-message');
        const gstLoading = document.getElementById('gst-loading');
        const autoFillMessage = document.getElementById('auto-fill-message');
        const originalGstNumber = gstInput.value;

        gstInput.addEventListener('input', function() {
            const gstNumber = this.value.toUpperCase();
            this.value = gstNumber;
            
            // Clear previous timeout
            clearTimeout(gstValidationTimeout);
            
            // Reset messages
            gstMessage.classList.add('hidden');
            autoFillMessage.classList.add('hidden');
            
            // Only validate if GST number changed from original
            if (gstNumber.length === 15 && gstNumber !== originalGstNumber) {
                // Show loading
                gstLoading.classList.remove('hidden');
                
                // Debounce API call
                gstValidationTimeout = setTimeout(() => {
                    validateAndAutoFillGst(gstNumber);
                }, 500);
            } else if (gstNumber.length > 0 && gstNumber.length !== 15) {
                showGstMessage('GST number must be 15 characters long', 'error');
            }
        });

        async function validateAndAutoFillGst(gstNumber) {
            try {
                const customerId = document.getElementById('customer_id').value;
                
                // First validate format and check for duplicates
                const validationResponse = await fetch('{{ route("customers.validateGst") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ 
                        gst_number: gstNumber,
                        customer_id: customerId
                    })
                });

                const validationData = await validationResponse.json();

                if (!validationData.valid) {
                    gstLoading.classList.add('hidden');
                    showGstMessage(validationData.message, 'error');
                    return;
                }

                // If validation passes, get GST details for auto-fill
                const detailsResponse = await fetch('{{ route("customers.getGstDetails") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ 
                        gst_number: gstNumber 
                    })
                });

                const detailsData = await detailsResponse.json();
                gstLoading.classList.add('hidden');

                if (detailsData.success) {
                    // Auto-fill form fields
                    autoFillForm(detailsData.data);
                    showGstMessage('GST number is valid and active', 'success');
                    autoFillMessage.classList.remove('hidden');
                } else {
                    showGstMessage(detailsData.message, 'warning');
                }

            } catch (error) {
                gstLoading.classList.add('hidden');
                showGstMessage('Error validating GST number. Please try again.', 'error');
                console.error('GST validation error:', error);
            }
        }

        function autoFillForm(data) {
            // Auto-fill company details
            if (data.company_name) {
                document.querySelector('input[name="company_name"]').value = data.company_name;
            }
            if (data.address) {
                document.querySelector('input[name="address"]').value = data.address;
            }
            if (data.city) {
                document.querySelector('input[name="city"]').value = data.city;
            }
            if (data.state) {
                document.querySelector('input[name="state"]').value = data.state;
            }

            // Fill hidden fields
            document.getElementById('pan_number').value = data.pan_number || '';
            document.getElementById('gst_status').value = data.gst_status || 'Active';
            document.getElementById('business_type').value = data.business_type || '';
            document.getElementById('gst_registration_date').value = data.gst_registration_date || '';
        }

        function showGstMessage(message, type) {
            gstMessage.textContent = message;
            gstMessage.classList.remove('hidden', 'text-green-400', 'text-red-400', 'text-yellow-400');
            
            switch(type) {
                case 'success':
                    gstMessage.classList.add('text-green-400');
                    break;
                case 'error':
                    gstMessage.classList.add('text-red-400');
                    break;
                case 'warning':
                    gstMessage.classList.add('text-yellow-400');
                    break;
            }
        }
    </script>
</x-app-layout>
