<x-app-layout>
    <x-slot name="title">
        {{ __('Create Quotation') }} - {{ config('app.name', 'SKM') }}
    </x-slot>

    <div class="mt-20 ml-4 py-9 sm:ml-64 sm:me-4 lg:me-0">
        <div class="w-full mx-auto max-w-7xl sm:px-6 lg:px-8">

            <x-bread-crumb-navigation />

            <div class="bg-gray-800 p-6 rounded-lg shadow-md">
                <h2 class="text-3xl font-bold text-gray-200 mb-6">Create Quotation</h2>

                <form action="{{ route('quotations.store') }}" method="POST">
                    @csrf

                    <!-- Customer -->
                    <div class="mb-6">
                        <label for="customer" class="block text-gray-300 font-semibold mb-2">Customer:</label>
                        <select id="customer" name="customer"
                            class="w-full px-4 py-3 border border-gray-700 bg-gray-800 text-gray-300 rounded-lg shadow-md focus:outline-none focus:ring-2 focus:ring-blue-500 transition">
                            <option value="">Select Customer</option>
                            @foreach ($customers as $customer)
                                <option value="{{ $customer->id }}">{{ $customer->company_name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Contact Person -->
                    <div class="mb-6">
                        <label for="contact_person" class="block text-gray-300 font-semibold mb-2">Contact
                            Person:</label>
                        <select id="contact_person" name="contact_person"
                            class="w-full px-4 py-3 border border-gray-700 bg-gray-800 text-gray-300 rounded-lg shadow-md focus:outline-none focus:ring-2 focus:ring-blue-500 transition"
                            disabled>
                            <option value="">Select Contact Person</option>
                        </select>
                    </div>

                    <script>
                        document.addEventListener("DOMContentLoaded", function() {
                            const customerSelect = document.getElementById("customer");
                            const contactPersonSelect = document.getElementById("contact_person");

                            customerSelect.addEventListener("change", function() {
                                const customerId = this.value;

                                // Clear existing options in contact person dropdown
                                contactPersonSelect.innerHTML = '<option value="">Select Contact Person</option>';
                                contactPersonSelect.disabled = true;

                                if (customerId) {
                                    // Fetch contact persons for the selected customer
                                    const contactPersons = @json($customers->mapWithKeys(fn($customer) => [$customer->id => $customer->contactPersons]));

                                    if (contactPersons[customerId]) {
                                        contactPersons[customerId].forEach(contactPerson => {
                                            const option = document.createElement("option");
                                            option.value = contactPerson.id;
                                            option.textContent = contactPerson.name;
                                            contactPersonSelect.appendChild(option);
                                        });
                                        contactPersonSelect.disabled = false;
                                    }
                                }
                            });
                        });
                    </script>

                    <!-- Quotation Date & Quotation No -->
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="quotation_date" class="block text-gray-300 font-semibold mb-2">Quotation
                                Date:</label>
                            <input type="date" name="quotation_date" id="quotation_date"
                                value="{{ old('quotation_date') }}"
                                class="w-full px-4 py-3 border border-gray-700 bg-gray-800 text-gray-300 rounded-lg shadow-md focus:outline-none focus:ring-2 focus:ring-blue-500 transition"
                                required>
                        </div>
                    </div>

                    <!-- Terms and Condition -->
                    <div class="mb-6">
                        <label for="terms_condition" class="block text-gray-300 font-semibold mb-2">Terms and
                            condition:</label>
                        <textarea id="terms_condition" name="terms_condition" rows="4"
                            class="w-full px-4 py-3 border border-gray-700 bg-gray-800 text-gray-300 rounded-lg shadow-md focus:outline-none focus:ring-2 focus:ring-blue-500 transition"
                            placeholder="Enter terms and condition">{{ old('terms_condition') }}</textarea>
                    </div>

                    <!-- Product Table -->
                    <div class="mt-6">
                        <h3 class="text-2xl font-bold text-gray-200 mb-4">Quotation Items</h3>
                        <table
                            class="min-w-full text-left border-collapse table-auto bg-gray-800 text-gray-300 rounded-lg shadow-md">
                            <thead>
                                <tr class="text-sm text-gray-400 bg-gray-700">
                                    <th class="px-3 py-3 border-b border-gray-600">Product</th>
                                    <th class="px-3 py-3 border-b border-gray-600">Quantity</th>
                                    <th class="px-3 py-3 border-b border-gray-600">Unit Price</th>
                                    <th class="px-3 py-3 border-b border-gray-600">CGST</th>
                                    <th class="px-3 py-3 border-b border-gray-600">SGST</th>
                                    <th class="px-3 py-3 border-b border-gray-600">IGST</th>
                                    <th class="px-3 py-3 border-b border-gray-600">Total</th>
                                    <th class="px-3 py-3 border-b border-gray-600">Action</th>
                                </tr>
                            </thead>
                            <tbody class="text-sm text-gray-300" id="productTable">

                            </tbody>
                        </table>
                        <button type="button" id="addRow"
                            class="mt-4 bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg shadow-md transition">+
                            Add Product</button>
                    </div>

                    <!-- Services Table -->
                    <div class="mt-6">
                        <label class="flex items-center text-gray-300 font-semibold mb-4">
                            <input type="checkbox" id="toggleServiceSelection" class="mr-2">
                            Include Services
                        </label>
                        <div id="serviceSection" class="hidden">
                            <h3 class="text-2xl font-bold text-gray-200 mb-4">Service Items</h3>
                            <table
                                class="min-w-full text-left border-collapse table-auto bg-gray-800 text-gray-300 rounded-lg shadow-md">
                                <thead>
                                    <tr class="text-sm text-gray-400 bg-gray-700">
                                        <th class="px-3 py-3 border-b border-gray-600">Service</th>
                                        <th class="px-3 py-3 border-b border-gray-600">Quantity</th>
                                        <th class="px-3 py-3 border-b border-gray-600">Unit Price</th>
                                        <th class="px-3 py-3 border-b border-gray-600">GST %</th>
                                        <th class="px-3 py-3 border-b border-gray-600">GST Total</th>
                                        <th class="px-3 py-3 border-b border-gray-600">Total</th>
                                        <th class="px-3 py-3 border-b border-gray-600">Action</th>
                                    </tr>
                                </thead>
                                <tbody class="text-sm text-gray-300" id="serviceTable">

                                </tbody>
                            </table>
                            <button type="button" id="addServiceRow"
                                class="mt-4 bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg shadow-md transition">+
                                Add Service</button>
                        </div>
                    </div>

                    <script>
                        document.addEventListener("DOMContentLoaded", function() {
                            const toggleServiceCheckbox = document.getElementById("toggleServiceSelection");
                            const serviceSection = document.getElementById("serviceSection");
                            const serviceSummary = document.getElementById("serviceSummary");

                            toggleServiceCheckbox.addEventListener("change", function() {
                                if (this.checked) {
                                    serviceSection.classList.remove("hidden");
                                    serviceSummary.classList.remove("hidden");
                                } else {
                                    serviceSection.classList.add("hidden");
                                    serviceSummary.classList.add("hidden");
                                }
                            });
                        });
                    </script>

                    <!-- Product Summary Section -->
                    <div class="mt-6 bg-gray-800 p-6 rounded-2xl shadow-lg">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <!-- Product Summary -->
                            <div>
                                <h4 class="text-xl font-semibold text-blue-400 mb-4">Product Summary</h4>
                                <table class="w-full text-sm text-left text-gray-300">
                                    <tbody class="divide-y divide-gray-700">
                                        <tr>
                                            <td class="py-2 font-medium w-1/2">Product Subtotal</td>
                                            <td class="py-2"><input type="text" id="productSubtotal"
                                                    name="product_subtotal" value="{{ old('product_subtotal') }}"
                                                    class="w-full px-4 py-3 border border-gray-600 bg-gray-800 text-gray-300 rounded-lg shadow-md focus:outline-none focus:ring-2 focus:ring-blue-500 transition"
                                                    readonly></td>
                                        </tr>
                                        <tr>
                                            <td class="py-2 font-medium">Product CGST Total</td>
                                            <td class="py-2"><input type="text" id="productTotalCgst"
                                                    name="product_total_cgst" value="{{ old('product_total_cgst') }}"
                                                    class="w-full px-4 py-3 border border-gray-600 bg-gray-800 text-gray-300 rounded-lg shadow-md focus:outline-none focus:ring-2 focus:ring-blue-500 transition"
                                                    readonly></td>
                                        </tr>
                                        <tr>
                                            <td class="py-2 font-medium">Product SGST Total</td>
                                            <td class="py-2"><input type="text" id="productTotalSgst"
                                                    name="product_total_sgst" value="{{ old('product_total_sgst') }}"
                                                    class="w-full px-4 py-3 border border-gray-600 bg-gray-800 text-gray-300 rounded-lg shadow-md focus:outline-none focus:ring-2 focus:ring-blue-500 transition"
                                                    readonly></td>
                                        </tr>
                                        <tr>
                                            <td class="py-2 font-medium">Product IGST Total</td>
                                            <td class="py-2"><input type="text" id="productTotalIgst"
                                                    name="product_total_igst" value="{{ old('product_total_igst') }}"
                                                    class="w-full px-4 py-3 border border-gray-600 bg-gray-800 text-gray-300 rounded-lg shadow-md focus:outline-none focus:ring-2 focus:ring-blue-500 transition"
                                                    readonly></td>
                                        </tr>
                                        <tr class="border-t border-gray-700 font-bold text-white">
                                            <td class="py-3">Product Total</td>
                                            <td class="py-3"><input type="text" id="productTotal"
                                                    name="product_total" value="{{ old('product_total') }}"
                                                    class="w-full px-4 py-3 border border-gray-600 bg-gray-800 text-gray-300 rounded-lg shadow-md focus:outline-none focus:ring-2 focus:ring-blue-500 transition"
                                                    readonly></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <!-- Service Summary -->
                            <div id="serviceSummary" class="hidden">
                                <h4 class="text-xl font-semibold text-green-400 mb-4">Service Summary</h4>
                                <table class="w-full text-sm text-left text-gray-300">
                                    <tbody class="divide-y divide-gray-700">
                                        <tr>
                                            <td class="py-2 font-medium w-1/2">Service Subtotal</td>
                                            <td class="py-2"><input type="text" id="serviceSubtotal"
                                                    name="service_subtotal" value="{{ old('service_subtotal') }}"
                                                    class="w-full px-4 py-3 border border-gray-600 bg-gray-800 text-gray-300 rounded-lg shadow-md focus:outline-none focus:ring-2 focus:ring-blue-500 transition"
                                                    readonly></td>
                                        </tr>
                                        <tr>
                                            <td class="py-2 font-medium">Service CGST Total</td>
                                            <td class="py-2"><input type="text" id="serviceTotalCgst"
                                                    name="service_total_cgst" value="{{ old('service_total_cgst') }}"
                                                    class="w-full px-4 py-3 border border-gray-600 bg-gray-800 text-gray-300 rounded-lg shadow-md focus:outline-none focus:ring-2 focus:ring-blue-500 transition"
                                                    readonly></td>
                                        </tr>
                                        <tr>
                                            <td class="py-2 font-medium">Service SGST Total</td>
                                            <td class="py-2"><input type="text" id="serviceTotalSgst"
                                                    name="service_total_sgst" value="{{ old('service_total_sgst') }}"
                                                    class="w-full px-4 py-3 border border-gray-600 bg-gray-800 text-gray-300 rounded-lg shadow-md focus:outline-none focus:ring-2 focus:ring-blue-500 transition"
                                                    readonly></td>
                                        </tr>
                                        <tr class="border-t border-gray-700 font-bold text-white">
                                            <td class="py-3">Service Total</td>
                                            <td class="py-3"><input type="text" id="serviceTotal"
                                                    name="service_total" value="{{ old('service_total') }}"
                                                    class="w-full px-4 py-3 border border-gray-600 bg-gray-800 text-gray-300 rounded-lg shadow-md focus:outline-none focus:ring-2 focus:ring-blue-500 transition"
                                                    readonly></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Grand Totals -->
                        <div class="mt-10">
                            <h4 class="text-xl font-semibold text-yellow-400 mb-4">Grand Totals</h4>
                            <table class="w-full text-sm text-left text-gray-300">
                                <tbody class="divide-y divide-gray-700">
                                    <tr>
                                        <td class="py-2 font-medium w-1/4">Grand Sub Total</td>
                                        <td class="py-2"><input type="text" id="grandSubTotal"
                                                name="grand_sub_total" value="{{ old('grand_sub_total') }}"
                                                class="w-full px-4 py-3 border border-gray-600 bg-gray-800 text-gray-300 rounded-lg shadow-md focus:outline-none focus:ring-2 focus:ring-blue-500 transition"
                                                readonly></td>
                                    </tr>
                                    <tr>
                                        <td class="py-2 font-medium">Grand GST Total</td>
                                        <td class="py-2"><input type="text" id="grandGstTotal"
                                                name="grand_gst_total" value="{{ old('grand_gst_total') }}"
                                                class="w-full px-4 py-3 border border-gray-600 bg-gray-800 text-gray-300 rounded-lg shadow-md focus:outline-none focus:ring-2 focus:ring-blue-500 transition"
                                                readonly></td>
                                    </tr>
                                    <tr class="border-t border-gray-700 font-bold text-white text-lg">
                                        <td class="py-3">Grand Total</td>
                                        <td class="py-3"><input type="text" id="grandTotal" name="grand_total"
                                                value="{{ old('grand_total') }}"
                                                class="w-full px-4 py-3 border border-gray-600 bg-gray-800 text-gray-300 rounded-lg shadow-md focus:outline-none focus:ring-2 focus:ring-blue-500 transition"
                                                readonly></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Service Selection Modal -->
                    <div id="serviceModal"
                        class="fixed inset-0 bg-gray-900 bg-opacity-75 hidden flex items-center justify-center">
                        <div class="bg-gray-800 p-6 rounded-lg shadow-md w-1/2">
                            <h2 class="text-2xl font-bold text-gray-200 mb-6">Select Service</h2>

                            <!-- Search Bar -->
                            <input type="text" id="serviceSearch" placeholder="Search Service..."
                                class="w-full mb-4 px-4 py-3 border border-gray-600 bg-gray-800 text-gray-300 rounded-lg shadow-md focus:outline-none focus:ring-2 focus:ring-blue-500 transition">

                            <table
                                class="min-w-full text-left border-collapse table-auto bg-gray-800 text-gray-300 rounded-lg shadow-md">
                                <thead>
                                    <tr class="text-sm text-gray-400 bg-gray-700">
                                        <th class="px-6 py-4 border-b border-gray-600">Service Name</th>
                                        <th class="px-6 py-4 border-b border-gray-600">GST Percentage</th>
                                        <th class="px-6 py-4 border-b border-gray-600">Action</th>
                                    </tr>
                                </thead>
                                <tbody id="serviceTableBody" class="text-sm text-gray-300">
                                    @foreach ($services as $service)
                                        <tr data-id="{{ $service->id }}" class="service-row">
                                            <td class="px-6 py-4 border-b border-gray-600">{{ $service->name }}</td>
                                            <td class="px-6 py-4 border-b border-gray-600">
                                                {{ $service->gst_percentage }}%</td>
                                            <td class="px-6 py-4 border-b border-gray-600">
                                                <button type="button"
                                                    class="select-service px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white rounded-lg shadow-md transition"
                                                    data-id="{{ $service->id }}" data-name="{{ $service->name }}"
                                                    data-gst="{{ $service->gst_percentage }}">
                                                    Select
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>

                            <button type="button" id="closeServiceModal"
                                class="mt-4 px-6 py-3 bg-red-500 hover:bg-red-600 text-white font-semibold rounded-lg shadow-md transition">Close</button>
                        </div>
                    </div>

                    <div class="mt-6">
                        <button type="submit"
                            class="px-6 py-3 bg-green-500 hover:bg-green-600 text-white font-semibold rounded-lg shadow-md transition">Submit
                            Quotation</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Product Selection Modal -->
    <div id="productModal" class="fixed inset-0 bg-gray-900 bg-opacity-75 hidden flex items-center justify-center">
        <div class="bg-gray-800 p-6 rounded-lg shadow-md w-1/2">
            <h2 class="text-2xl font-bold text-gray-200 mb-6">Select Product</h2>

            <!-- Search Bar -->
            <input type="text" id="productSearch" placeholder="Search Product..."
                class="w-full mb-4 px-4 py-3 border border-gray-600 bg-gray-800 text-gray-300 rounded-lg shadow-md focus:outline-none focus:ring-2 focus:ring-blue-500 transition">

            <table
                class="min-w-full text-left border-collapse table-auto bg-gray-800 text-gray-300 rounded-lg shadow-md">
                <thead>
                    <tr class="text-sm text-gray-400 bg-gray-700">
                        <th class="px-6 py-4 border-b border-gray-600">Product Name</th>
                        <th class="px-6 py-4 border-b border-gray-600">HSN Code</th>
                        <th class="px-6 py-4 border-b border-gray-600">Stock</th>
                        <th class="px-6 py-4 border-b border-gray-600">GST Percentage</th>
                        <th class="px-6 py-4 border-b border-gray-600">Action</th>
                    </tr>
                </thead>
                <tbody id="productTableBody" class="text-sm text-gray-300">
                    @foreach ($products as $product)
                        <tr data-id="{{ $product->id }}" class="product-row">
                            <td class="px-6 py-4 border-b border-gray-600">{{ $product->name }}</td>
                            <td class="px-6 py-4 border-b border-gray-600">{{ $product->hsn_code }}</td>
                            <td class="px-6 py-4 border-b border-gray-600">
                                {{ $product->stock->sum('quantity') - $product->stock->sum('sold') }}</td>
                            <td class="px-6 py-4 border-b border-gray-600">{{ $product->gst_percentage }}%</td>
                            <td class="px-6 py-4 border-b border-gray-600">
                                <button type="button"
                                    class="select-product px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white rounded-lg shadow-md transition"
                                    data-id="{{ $product->id }}" data-name="{{ $product->name }}"
                                    data-gst="{{ $product->gst_percentage }}" data-isigst="{{ $product->is_igst }}">
                                    Select
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <button type="button" id="closeModal"
                class="mt-4 px-6 py-3 bg-red-500 hover:bg-red-600 text-white font-semibold rounded-lg shadow-md transition">Close</button>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const productTable = document.getElementById("productTable");
            const addRowBtn = document.getElementById("addRow");
            const productModal = document.getElementById("productModal");
            const closeModalBtn = document.getElementById("closeModal");
            const serviceTable = document.getElementById("serviceTable");
            const addServiceRowBtn = document.getElementById("addServiceRow");
            const serviceModal = document.getElementById("serviceModal");
            const closeServiceModalBtn = document.getElementById("closeServiceModal");
            let currentRow = null;
            let currentServiceRow = null;

            function filterProducts() {
                let searchValue = document.getElementById("productSearch").value.toUpperCase();
                let productRows = document.querySelectorAll(".product-row");

                productRows.forEach(row => {
                    let productName = row.querySelector("td").textContent.toUpperCase();
                    if (productName.indexOf(searchValue) > -1) {
                        row.style.display = "";
                    } else {
                        row.style.display = "none";
                    }
                });
            }

            document.getElementById("productSearch").addEventListener("input", filterProducts);

            function addProductRow() {
                var newIndex = productTable.rows.length;

                const row = document.createElement("tr");
                row.innerHTML = `
                <td class="p-2">
                    <button type="button" class="open-modal bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg shadow-md transition">Select Product</button>
                    <input type="hidden" name="products[${newIndex}][product_id]" class="product-id">
                    <input type="hidden" name="products[${newIndex}][gst_percentage]" class="gst-percentage" value="0">
                    <span class="product-name"></span>
                </td>
                <td class="p-2"><input type="number" name="products[${newIndex}][quantity]" class="quantity w-full px-4 py-3 border border-gray-700 bg-gray-800 text-gray-300 rounded-lg shadow-md focus:outline-none focus:ring-2 focus:ring-blue-500 transition" value="1" min="1" oninput="calculateRowTotal(this.closest('tr'))" onkeyup="calculateRowTotal(this.closest('tr'))"></td>
                <td class="p-2"><input type="number" name="products[${newIndex}][unit_price]" class="unit-price w-full px-4 py-3 border border-gray-700 bg-gray-800 text-gray-300 rounded-lg shadow-md focus:outline-none focus:ring-2 focus:ring-blue-500 transition" value="0" min="0" step="0.01" oninput="calculateRowTotal(this.closest('tr'))" onkeyup="calculateRowTotal(this.closest('tr'))"></td>
                <td class="p-2">
                    <div class="flex items-center gap-2">
                        <input type="text" name="products[${newIndex}][cgst]" class="cgst w-full px-4 py-3 border border-gray-700 bg-gray-800 text-gray-300 rounded-lg shadow-md focus:outline-none focus:ring-2 focus:ring-blue-500 transition" readonly>
                        <input type="text" name="products[${newIndex}][cgst_value]" class="cgst-value w-full px-4 py-3 border border-gray-700 bg-gray-800 text-gray-300 rounded-lg shadow-md focus:outline-none focus:ring-2 focus:ring-blue-500 transition" readonly>
                    </div>
                </td>
                <td class="p-2">
                    <div class="flex items-center gap-2">
                        <input type="text" name="products[${newIndex}][sgst]" class="sgst w-full px-4 py-3 border border-gray-700 bg-gray-800 text-gray-300 rounded-lg shadow-md focus:outline-none focus:ring-2 focus:ring-blue-500 transition" readonly>
                        <input type="text" name="products[${newIndex}][sgst_value]" class="sgst-value w-full px-4 py-3 border border-gray-700 bg-gray-800 text-gray-300 rounded-lg shadow-md focus:outline-none focus:ring-2 focus:ring-blue-500 transition" readonly>
                    </div>
                </td>
                <td class="p-2">
                    <div class="flex items-center gap-2">
                        <input type="text" name="products[${newIndex}][igst]" class="igst w-full px-4 py-3 border border-gray-700 bg-gray-800 text-gray-300 rounded-lg shadow-md focus:outline-none focus:ring-2 focus:ring-blue-500 transition" readonly>
                        <input type="text" name="products[${newIndex}][igst_value]" class="igst-value w-full px-4 py-3 border border-gray-700 bg-gray-800 text-gray-300 rounded-lg shadow-md focus:outline-none focus:ring-2 focus:ring-blue-500 transition" readonly>
                    </div>
                </td>
                <td class="p-2"><input type="text" name="products[${newIndex}][total]" class="total w-full px-4 py-3 border border-gray-700 bg-gray-800 text-gray-300 rounded-lg shadow-md focus:outline-none focus:ring-2 focus:ring-blue-500 transition" readonly></td>
                <td class="p-2">
                    <button type="button" class="remove-row bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg shadow-md transition">X</button>
                </td>`;

                productTable.appendChild(row);
                addEventListenersToRow(row);
                currentRow = row;
                productModal.classList.remove("hidden");
            }

            function addServiceRow() {
                var newIndex = serviceTable.rows.length;

                const row = document.createElement("tr");
                row.innerHTML = `
                <td class="p-2">
                    <button type="button" class="open-service-modal bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg shadow-md transition">Select Service</button>
                    <input type="hidden" name="services[${newIndex}][service_id]" class="service-id">
                    <span class="service-name"></span>
                </td>
                <td class="p-2"><input type="number" name="services[${newIndex}][quantity]" class="service-quantity w-full px-4 py-3 border border-gray-700 bg-gray-800 text-gray-300 rounded-lg shadow-md focus:outline-none focus:ring-2 focus:ring-blue-500 transition" value="1" min="1" oninput="calculateServiceRowTotal(this.closest('tr'))" onkeyup="calculateServiceRowTotal(this.closest('tr'))"></td>
                <td class="p-2"><input type="number" name="services[${newIndex}][unit_price]" class="service-unit-price w-full px-4 py-3 border border-gray-700 bg-gray-800 text-gray-300 rounded-lg shadow-md focus:outline-none focus:ring-2 focus:ring-blue-500 transition" value="0" min="0" step="0.01" oninput="calculateServiceRowTotal(this.closest('tr'))" onkeyup="calculateServiceRowTotal(this.closest('tr'))"></td>
                <td class="p-2"><input type="text" name="services[${newIndex}][gst_percentage]" class="service-gst-percentage w-full px-4 py-3 border border-gray-700 bg-gray-800 text-gray-300 rounded-lg shadow-md focus:outline-none focus:ring-2 focus:ring-blue-500 transition" readonly></td>
                <td class="p-2"><input type="text" name="services[${newIndex}][gst_total]" class="service-gst-total w-full px-4 py-3 border border-gray-700 bg-gray-800 text-gray-300 rounded-lg shadow-md focus:outline-none focus:ring-2 focus:ring-blue-500 transition" readonly></td>
                <td class="p-2"><input type="text" name="services[${newIndex}][total]" class="service-total w-full px-4 py-3 border border-gray-700 bg-gray-800 text-gray-300 rounded-lg shadow-md focus:outline-none focus:ring-2 focus:ring-blue-500 transition" readonly></td>
                <td class="p-2">
                    <button type="button" class="remove-service-row bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg shadow-md transition">X</button>
                </td>`;

                serviceTable.appendChild(row);
                addEventListenersToServiceRow(row);
                currentServiceRow = row;
                serviceModal.classList.remove("hidden");
            }

            function addEventListenersToRow(row) {
                let quantityInput = row.querySelector(".quantity");
                let unitPriceInput = row.querySelector(".unit-price");

                row.querySelector(".open-modal").addEventListener("click", function() {
                    currentRow = row;
                    productModal.classList.remove("hidden");
                });

                // Add multiple event listeners for real-time calculation
                ['input', 'keyup', 'change', 'paste'].forEach(eventType => {
                    quantityInput.addEventListener(eventType, function() {
                        setTimeout(() => calculateRowTotal(row), 0);
                    });

                    unitPriceInput.addEventListener(eventType, function() {
                        setTimeout(() => calculateRowTotal(row), 0);
                    });
                });

                // Also add oninput for immediate response
                quantityInput.oninput = function() {
                    calculateRowTotal(row);
                };

                unitPriceInput.oninput = function() {
                    calculateRowTotal(row);
                };

                row.querySelector(".remove-row").addEventListener("click", function() {
                    row.remove();
                    calculateSummary();
                });
            }

            function addEventListenersToServiceRow(row) {
                let quantityInput = row.querySelector(".service-quantity");
                let unitPriceInput = row.querySelector(".service-unit-price");

                row.querySelector(".open-service-modal").addEventListener("click", function() {
                    currentServiceRow = row;
                    serviceModal.classList.remove("hidden");
                });

                // Add multiple event listeners for real-time calculation
                ['input', 'keyup', 'change', 'paste'].forEach(eventType => {
                    quantityInput.addEventListener(eventType, function() {
                        setTimeout(() => calculateServiceRowTotal(row), 0);
                    });

                    unitPriceInput.addEventListener(eventType, function() {
                        setTimeout(() => calculateServiceRowTotal(row), 0);
                    });
                });

                // Also add oninput for immediate response
                quantityInput.oninput = function() {
                    calculateServiceRowTotal(row);
                };

                unitPriceInput.oninput = function() {
                    calculateServiceRowTotal(row);
                };

                row.querySelector(".remove-service-row").addEventListener("click", function() {
                    row.remove();
                    calculateSummary();
                });
            }

            function updateGSTValues(row) {
                let gstPercentage = parseFloat(row.querySelector(".gst-percentage").value) || 0;
                let isIgst = row.querySelector(".product-id").getAttribute("data-isigst") === "1";

                row.querySelector(".cgst").value = isIgst ? "0" : (gstPercentage / 2).toFixed(2);
                row.querySelector(".sgst").value = isIgst ? "0" : (gstPercentage / 2).toFixed(2);
                row.querySelector(".igst").value = isIgst ? gstPercentage.toFixed(2) : "0";
            }

            function calculateRowTotal(row) {
                // Ensure row is valid
                if (!row || !row.querySelector) return;

                let quantity = parseFloat(row.querySelector(".quantity")?.value) || 0;
                let unitPrice = parseFloat(row.querySelector(".unit-price")?.value) || 0;

                // Add visual feedback for calculation
                let totalField = row.querySelector(".total");
                if (totalField) {
                    totalField.style.backgroundColor = '#1f2937'; // Slight highlight during calculation
                    setTimeout(() => {
                        totalField.style.backgroundColor = '';
                    }, 200);
                }

                // Get GST percentages
                let cgst = parseFloat(row.querySelector(".cgst")?.value) || 0;
                let sgst = parseFloat(row.querySelector(".sgst")?.value) || 0;
                let igst = parseFloat(row.querySelector(".igst")?.value) || 0;

                // Calculate GST values
                let subtotal = quantity * unitPrice;
                let cgst_value = (subtotal * cgst) / 100;
                let sgst_value = (subtotal * sgst) / 100;
                let igst_value = (subtotal * igst) / 100;

                // Update GST value fields with proper formatting
                if (row.querySelector(".cgst-value")) row.querySelector(".cgst-value").value = cgst_value.toFixed(
                2);
                if (row.querySelector(".sgst-value")) row.querySelector(".sgst-value").value = sgst_value.toFixed(
                2);
                if (row.querySelector(".igst-value")) row.querySelector(".igst-value").value = igst_value.toFixed(
                2);

                // Calculate total
                let totalGst = cgst_value + sgst_value + igst_value;
                let grandTotal = subtotal + totalGst;

                // Update total field with currency formatting
                if (row.querySelector(".total")) {
                    row.querySelector(".total").value = grandTotal.toFixed(2);
                }

                // Trigger summary calculation with a small delay to batch multiple changes
                clearTimeout(window.summaryTimeout);
                window.summaryTimeout = setTimeout(calculateSummary, 50);
            }

            function calculateServiceRowTotal(row) {
                // Ensure row is valid
                if (!row || !row.querySelector) return;

                let quantity = parseFloat(row.querySelector(".service-quantity")?.value) || 0;
                let unitPrice = parseFloat(row.querySelector(".service-unit-price")?.value) || 0;
                let gstPercentage = parseFloat(row.querySelector(".service-gst-percentage")?.value) || 0;

                // Calculate values
                let subtotal = quantity * unitPrice;
                let gstTotal = (subtotal * gstPercentage) / 100;
                let total = subtotal + gstTotal;

                // Update fields
                if (row.querySelector(".service-gst-total")) {
                    row.querySelector(".service-gst-total").value = gstTotal.toFixed(2);
                }

                if (row.querySelector(".service-total")) {
                    row.querySelector(".service-total").value = total.toFixed(2);
                }

                // Trigger summary calculation with a small delay to batch multiple changes
                clearTimeout(window.summaryTimeout);
                window.summaryTimeout = setTimeout(calculateSummary, 50);
            }

            function calculateSummary() {
                // Add visual feedback for calculation in progress
                let grandTotalField = document.getElementById("grandTotal");
                if (grandTotalField) {
                    grandTotalField.style.backgroundColor = '#1f2937';
                    grandTotalField.style.borderColor = '#3b82f6';
                }

                let productSubtotal = 0,
                    productTotal = 0,
                    productTotalCgst = 0,
                    productTotalSgst = 0,
                    productTotalIgst = 0,
                    serviceSubtotal = 0,
                    serviceTotal = 0,
                    serviceTotalCgst = 0,
                    serviceTotalSgst = 0,
                    grandTotal = 0,
                    grandSubTotal = 0,
                    grandGstTotal = 0;

                // Calculate product summary
                document.querySelectorAll("#productTable tr").forEach(row => {
                    let rowTotal = parseFloat(row.querySelector(".total")?.value) || 0;
                    let cgst = parseFloat(row.querySelector(".cgst")?.value) || 0;
                    let sgst = parseFloat(row.querySelector(".sgst")?.value) || 0;
                    let igst = parseFloat(row.querySelector(".igst")?.value) || 0;

                    let baseAmount = rowTotal / (1 + (cgst + sgst + igst) / 100);
                    productSubtotal += baseAmount;
                    productTotal += rowTotal;
                    productTotalCgst += (baseAmount * cgst) / 100;
                    productTotalSgst += (baseAmount * sgst) / 100;
                    productTotalIgst += (baseAmount * igst) / 100;
                });

                // Calculate service summary
                document.querySelectorAll("#serviceTable tr").forEach(row => {
                    let rowTotal = parseFloat(row.querySelector(".service-total")?.value) || 0;
                    let gstPercentage = parseFloat(row.querySelector(".service-gst-percentage")?.value) ||
                    0;

                    let baseAmount = rowTotal / (1 + gstPercentage / 100);
                    serviceSubtotal += baseAmount;
                    serviceTotal += rowTotal;
                    serviceTotalCgst += (baseAmount * gstPercentage) / 200;
                    serviceTotalSgst += (baseAmount * gstPercentage) / 200;
                });

                // Calculate grand total
                grandGstTotal = productTotalCgst + productTotalSgst + productTotalIgst + serviceTotalCgst +
                    serviceTotalSgst;
                grandTotal = productTotal + serviceTotal;
                grandSubTotal = productSubtotal + serviceSubtotal;

                // Update product summary fields with smooth transitions
                const updateField = (fieldId, value) => {
                    let field = document.getElementById(fieldId);
                    if (field) {
                        field.value = value.toFixed(2);
                        field.style.backgroundColor = '#065f46';
                        setTimeout(() => {
                            field.style.backgroundColor = '';
                        }, 300);
                    }
                };

                updateField("productSubtotal", productSubtotal);
                updateField("productTotal", productTotal);
                updateField("productTotalCgst", productTotalCgst);
                updateField("productTotalSgst", productTotalSgst);
                updateField("productTotalIgst", productTotalIgst);

                // Update service summary fields
                updateField("serviceSubtotal", serviceSubtotal);
                updateField("serviceTotal", serviceTotal);
                updateField("serviceTotalCgst", serviceTotalCgst);
                updateField("serviceTotalSgst", serviceTotalSgst);

                // Update grand total fields with highlight
                updateField("grandTotal", grandTotal);
                updateField("grandSubTotal", grandSubTotal);
                updateField("grandGstTotal", grandGstTotal);

                // Reset visual feedback
                setTimeout(() => {
                    if (grandTotalField) {
                        grandTotalField.style.backgroundColor = '';
                        grandTotalField.style.borderColor = '';
                    }
                }, 500);
            }

            document.querySelectorAll(".select-product").forEach(button => {
                button.addEventListener("click", function() {
                    let productId = this.getAttribute("data-id");
                    let productName = this.getAttribute("data-name");
                    let gstPercentage = this.getAttribute("data-gst");
                    let isIgst = this.getAttribute("data-isigst");

                    currentRow.querySelector(".product-id").value = productId;
                    currentRow.querySelector(".product-id").setAttribute("data-isigst", isIgst);
                    currentRow.querySelector(".product-name").textContent = productName;
                    currentRow.querySelector(".gst-percentage").value = gstPercentage;

                    updateGSTValues(currentRow);
                    calculateRowTotal(currentRow);

                    productModal.classList.add("hidden");
                });
            });

            document.querySelectorAll(".select-service").forEach(button => {
                button.addEventListener("click", function() {
                    let serviceId = this.getAttribute("data-id");
                    let serviceName = this.getAttribute("data-name");
                    let gstPercentage = this.getAttribute("data-gst");

                    currentServiceRow.querySelector(".service-id").value = serviceId;
                    currentServiceRow.querySelector(".service-name").textContent = serviceName;
                    currentServiceRow.querySelector(".service-gst-percentage").value =
                    gstPercentage;

                    calculateServiceRowTotal(currentServiceRow);

                    serviceModal.classList.add("hidden");
                });
            });

            closeModalBtn.addEventListener("click", function() {
                productModal.classList.add("hidden");
            });

            closeServiceModalBtn.addEventListener("click", function() {
                serviceModal.classList.add("hidden");
            });

            addRowBtn.addEventListener("click", addProductRow);
            addServiceRowBtn.addEventListener("click", addServiceRow);
        });
    </script>
</x-app-layout>
