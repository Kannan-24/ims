<x-app-layout>

    <x-slot name="title">
        {{ __('Assign Supplier') }} - {{ config('app.name', 'ATMS') }}
    </x-slot>

    <!-- Main Content Section -->
    <div class="py-6 mt-20 ml-4 sm:ml-64">
        <div class="w-full mx-auto max-w-7xl sm:px-6 lg:px-8">
            <x-bread-crumb-navigation />

            <h2 class="text-3xl font-bold text-gray-200 mb-6">Assign Supplier</h2>

            <!-- Form Container -->
            <div class="p-8 bg-gray-800 border border-gray-700 rounded-lg shadow-lg">
                <form action="{{ route('products.assignSupplier', $product->id) }}" method="POST">
                    @csrf

                    <!-- Supplier Selection -->
                    <div class="mb-6">
                        <label for="suppliers" class="block text-gray-300 font-semibold mb-2">Supplier</label>
                        <select name="suppliers" id="suppliers"
                            class="w-full px-4 py-3 border border-gray-700 bg-gray-800 text-gray-300 rounded-lg shadow-md focus:outline-none focus:ring-2 focus:ring-blue-500 transition"
                            required onchange="fetchSupplierDetails(this.value)">
                            <option value="">Select a supplier</option>
                            @foreach ($suppliers as $supplier)
                                <option value="{{ $supplier->id }}">{{ $supplier->supplier_id }} -
                                    {{ $supplier->supplier_name }} - {{ $supplier->state }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Supplier Details -->
                    <div id="supplier-details"
                        class="p-6 mt-3 bg-gray-700 border border-gray-600 rounded-lg shadow-lg hidden">
                        <h3 class="text-xl font-bold text-blue-400">Supplier Details</h3>
                        <div class="mt-2">
                            <p class="text-lg text-gray-300"><strong>Name:</strong> <span id="supplier-name"
                                    class="text-gray-300"></span></p>
                            <p class="text-lg text-gray-300"><strong>Contact Person:</strong> <span id="supplier-contact-person"
                                    class="text-gray-300"></span></p>
                            <p class="text-lg text-gray-300"><strong>Email:</strong> <span id="supplier-email"
                                    class="text-gray-300"></span></p>
                            <p class="text-lg text-gray-300"><strong>Phone:</strong> <span id="supplier-phone"
                                    class="text-gray-300"></span></p>
                            <p class="text-lg text-gray-300"><strong>Address:</strong> <span id="supplier-address"
                                    class="text-gray-300"></span></p>
                            <p class="text-lg text-gray-300"><strong>GST:</strong> <span id="supplier-GST"
                                    class="text-gray-300"></span></p>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="flex justify-end mt-6">
                        <button type="submit"
                            class="px-6 py-3 bg-blue-500 hover:bg-blue-600 text-white font-semibold rounded-lg shadow-md transition">
                            Assign Supplier
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function fetchSupplierDetails(supplierId) {
            if (!supplierId) {
                document.getElementById('supplier-details').classList.add('hidden');
                return;
            }

            fetch(`/suppliers/assign/${supplierId}`)
                .then(response => response.json())
                .then(data => {
                    document.getElementById('supplier-name').textContent = data.supplier.name;
                    document.getElementById('supplier-contact-person').textContent = data.supplier.contact_person;
                    document.getElementById('supplier-email').textContent = data.supplier.email;
                    document.getElementById('supplier-phone').textContent = data.supplier.phone;
                    document.getElementById('supplier-address').textContent =
                        `${data.address.address}, ${data.address.city}, ${data.address.state}, ${data.address.postal_code}, ${data.address.country}`;
                    document.getElementById('supplier-GST').textContent = data.gst;

                    document.getElementById('supplier-details').classList.remove('hidden');
                })
                .catch(error => console.error('Error fetching supplier details:', error));
        }
    </script>

</x-app-layout>
