<x-app-layout>

    <x-slot name="title">
        {{ __('Assign Supplier') }} - {{ config('app.name', 'ATMS') }}
    </x-slot>

    <!-- Main Content Section -->
    <div class="py-6 mt-20 ml-4 sm:ml-64">
        <div class="w-full max-w-4xl px-6 mx-auto">
            <x-bread-crumb-navigation />

            <!-- Form Container -->
            <div class="p-8 bg-white border border-gray-200 rounded-lg shadow-lg">
                <form action="{{ route('products.assignSupplier', $product->id) }}" method="POST">
                    @csrf

                    <!-- Supplier Selection -->
                    <div class="mb-4">
                        <label for="suppliers" class="block text-sm font-semibold text-gray-700">Supplier</label>
                        <select name="suppliers" id="suppliers"
                            class="w-full p-2 mt-1 transition duration-300 border border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
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
                        class="p-6 mt-3 bg-gradient-to-r from-gray-100 to-gray-200 border border-gray-300 rounded-lg shadow-lg hidden">
                        <h3 class="text-xl font-bold text-indigo-600">Supplier Details</h3>
                        <div class="mt-2">
                            <p class="text-lg"><strong>Name:</strong> <span id="supplier-name"
                                    class="text-gray-700"></span></p>
                            <p class="text-lg"><strong>Contact Person:</strong> <span id="supplier-contact-person"
                                    class="text-gray-700"></span></p>
                            <p class="text-lg"><strong>Email:</strong> <span id="supplier-email"
                                    class="text-gray-700"></span></p>
                            <p class="text-lg"><strong>Phone:</strong> <span id="supplier-phone"
                                    class="text-gray-700"></span></p>
                            <p class="text-lg"><strong>Address:</strong> <span id="supplier-address"
                                    class="text-gray-700"></span></p>
                            <p class="text-lg"><strong>GST:</strong> <span id="supplier-GST"
                                    class="text-gray-700"></span></p>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="flex justify-end mt-4">
                        <button type="submit"
                            class="px-4 py-2 text-lg font-semibold text-white transition duration-300 rounded-lg shadow-md bg-gradient-to-r from-indigo-500 to-blue-500 hover:from-indigo-600 hover:to-blue-600">
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
