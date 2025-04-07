<x-app-layout>
    <x-slot name="title">
        {{ __('Edit Invoice') }} - {{ config('app.name', 'SKM') }}
    </x-slot>

    <div class="mt-20 ml-4 py-9 sm:ml-64 sm:me-4 lg:me-0">
        <div class="w-full mx-auto max-w-7xl sm:px-6 lg:px-8">

            <x-bread-crumb-navigation />

            <div class="bg-gray-800 p-6 rounded-lg shadow-md">
                <h2 class="text-3xl font-bold text-gray-200 mb-6">Edit Invoice</h2>

                <form action="{{ route('invoices.update', $invoice->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <!-- Order No -->
                    <div class="mb-6">
                        <label for="order_no" class="block text-gray-300 font-semibold mb-2">Order No:</label>
                        <input type="text" name="order_no" id="order_no"
                            value="{{ old('order_no', $invoice->order_no) }}"
                            class="w-full px-4 py-3 border border-gray-700 bg-gray-800 text-gray-300 rounded-lg shadow-md focus:outline-none focus:ring-2 focus:ring-blue-500 transition" required>
                    </div>

                    <!-- Customer -->
                    <div class="mb-6">
                        <label for="customer" class="block text-gray-300 font-semibold mb-2">Customer:</label>
                        <select id="customer" name="customer" class="w-full px-4 py-3 border border-gray-700 bg-gray-800 text-gray-300 rounded-lg shadow-md focus:outline-none focus:ring-2 focus:ring-blue-500 transition">
                            <option value="">Select Customer</option>
                            @foreach ($customers as $customer)
                                <option value="{{ $customer->id }}" 
                                    {{ old('customer', $invoice->customer_id) == $customer->id ? 'selected' : '' }}>
                                    {{ $customer->company_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Contact Person -->
                    <div class="mb-6">
                        <label for="contact_person" class="block text-gray-300 font-semibold mb-2">Contact Person:</label>
                        <select id="contact_person" name="contact_person" class="w-full px-4 py-3 border border-gray-700 bg-gray-800 text-gray-300 rounded-lg shadow-md focus:outline-none focus:ring-2 focus:ring-blue-500 transition" 
                            {{ old('customer', $invoice->customer_id) ? '' : 'disabled' }}>
                            <option value="">Select Contact Person</option>
                            @if (old('customer', $invoice->customer_id))
                                @foreach ($customers->firstWhere('id', old('customer', $invoice->customer_id))->contactPersons as $contactPerson)
                                    <option value="{{ $contactPerson->id }}" 
                                        {{ old('contact_person', $invoice->contact_person_id) == $contactPerson->id ? 'selected' : '' }}>
                                        {{ $contactPerson->name }}
                                    </option>
                                @endforeach
                            @endif
                        </select>
                    </div>

                    <script>
                        document.addEventListener("DOMContentLoaded", function () {
                            const customerSelect = document.getElementById("customer");
                            const contactPersonSelect = document.getElementById("contact_person");

                            customerSelect.addEventListener("change", function () {
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

                    <!-- Invoice Date & Invoice No -->
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="invoice_no" class="block text-gray-300 font-semibold mb-2">Invoice No:</label>
                            <input type="text" name="invoice_no" id="invoice_no"
                                value="{{ old('invoice_no', $invoice->invoice_no) }}"
                                class="w-full px-4 py-3 border border-gray-700 bg-gray-800 text-gray-300 rounded-lg shadow-md focus:outline-none focus:ring-2 focus:ring-blue-500 transition" required>
                        </div>
                        <div>
                            <label for="invoice_date" class="block text-gray-300 font-semibold mb-2">Invoice Date:</label>
                            <input type="date" name="invoice_date" id="invoice_date"
                                value="{{ old('invoice_date', $invoice->invoice_date) }}"
                                class="w-full px-4 py-3 border border-gray-700 bg-gray-800 text-gray-300 rounded-lg shadow-md focus:outline-none focus:ring-2 focus:ring-blue-500 transition" required>
                        </div>
                        <div>
                            <label for="order_date" class="block text-gray-300 font-semibold mb-2">Order Date:</label>
                            <input type="date" name="order_date" id="order_date"
                                value="{{ old('order_date', $invoice->order_date) }}"
                                class="w-full px-4 py-3 border border-gray-700 bg-gray-800 text-gray-300 rounded-lg shadow-md focus:outline-none focus:ring-2 focus:ring-blue-500 transition">
                        </div>
                    </div>

                    <!-- Product Table -->
                    <div class="mt-6">
                        <h3 class="text-2xl font-bold text-gray-200 mb-4">Invoice Items</h3>
                        <table class="min-w-full text-left border-collapse table-auto bg-gray-800 text-gray-300 rounded-lg shadow-md">
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
                                @foreach ($invoice->items as $index => $item)
                                    <tr>
                                        <td>
                                            <button type="button"
                                                class="open-modal bg-blue-500 text-white px-4 py-2 rounded hidden">Select
                                                Product</button>
                                            <input type="hidden" name="products[{{ $index }}][product_id]"
                                                class="product-id" value="{{ $item->product_id }}">
                                            <input type="hidden" name="products[{{ $index }}][gst_percentage]"
                                                class="gst-percentage" value="{{ $item->gst_percentage }}">
                                            <span class="product-name">{{ $item->product->name }}</span>
                                        </td>
                                        <td class="p-1"><input type="number"
                                                name="products[{{ $index }}][quantity]"
                                                class="quantity w-full px-4 py-3 border border-gray-700 bg-gray-800 text-gray-300 rounded-lg shadow-md focus:outline-none focus:ring-2 focus:ring-blue-500 transition"
                                                value="{{ $item->quantity }}" min="1"></td>
                                        <td class="p-1"><input type="number"
                                                name="products[{{ $index }}][unit_price]"
                                                class="unit-price w-full px-4 py-3 border border-gray-700 bg-gray-800 text-gray-300 rounded-lg shadow-md focus:outline-none focus:ring-2 focus:ring-blue-500 transition"
                                                value="{{ $item->unit_price }}" min="0"></td>
                                        <td class="p-2">
                                            <div class="flex items-center gap-2">
                                                <input type="text" name="products[{{ $index }}][cgst]"
                                                    class="cgst w-full px-4 py-3 border border-gray-700 bg-gray-800 text-gray-300 rounded-lg shadow-md focus:outline-none focus:ring-2 focus:ring-blue-500 transition"
                                                    value="{{ $item->cgst }}" readonly>
                                                <input type="text" name="products[{{ $index }}][cgst_value]"
                                                    class="cgst-value w-full px-4 py-3 border border-gray-700 bg-gray-800 text-gray-300 rounded-lg shadow-md focus:outline-none focus:ring-2 focus:ring-blue-500 transition"
                                                    value="{{ $item->cgst_value }}" readonly>
                                            </div>
                                        </td>
                                        <td class="p-2">
                                            <div class="flex items-center gap-2">
                                                <input type="text" name="products[{{ $index }}][sgst]"
                                                    class="sgst w-full px-4 py-3 border border-gray-700 bg-gray-800 text-gray-300 rounded-lg shadow-md focus:outline-none focus:ring-2 focus:ring-blue-500 transition"
                                                    value="{{ $item->sgst }}" readonly>
                                                <input type="text" name="products[{{ $index }}][sgst_value]"
                                                    class="sgst-value w-full px-4 py-3 border border-gray-700 bg-gray-800 text-gray-300 rounded-lg shadow-md focus:outline-none focus:ring-2 focus:ring-blue-500 transition"
                                                    value="{{ $item->sgst_value }}" readonly>
                                            </div>
                                        </td>
                                        <td class="p-2">
                                            <div class="flex items-center gap-2">
                                                <input type="text" name="products[{{ $index }}][igst]"
                                                    class="igst w-full px-4 py-3 border border-gray-700 bg-gray-800 text-gray-300 rounded-lg shadow-md focus:outline-none focus:ring-2 focus:ring-blue-500 transition"
                                                    value="{{ $item->igst }}" readonly>
                                                <input type="text" name="products[{{ $index }}][igst_value]"
                                                    class="igst-value w-full px-4 py-3 border border-gray-700 bg-gray-800 text-gray-300 rounded-lg shadow-md focus:outline-none focus:ring-2 focus:ring-blue-500 transition"
                                                    value="{{ $item->igst_value }}" readonly>
                                            </div>
                                        </td>
                                        <td class="p-2"><input type="text"
                                                name="products[{{ $index }}][total]"
                                                class="total w-full px-4 py-3 border border-gray-700 bg-gray-800 text-gray-300 rounded-lg shadow-md focus:outline-none focus:ring-2 focus:ring-blue-500 transition"
                                                value="{{ $item->total }}" readonly></td>
                                        <td class="p-2">
                                            <button type="button"
                                                class="remove-row bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg shadow-md transition">X</button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <button type="button" id="addRow" class="mt-4 bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg shadow-md transition">+ Add Product</button>
                    </div>

                    <!-- Summary Section -->
                    <div class="mt-6 bg-gray-700 p-4 rounded-lg shadow-md">
                        <h3 class="text-2xl font-bold text-gray-200 mb-4">Summary</h3>
                        <div class="grid grid-cols-3 gap-4">
                            <div>
                                <label class="block text-gray-300 font-semibold mb-2">Subtotal:</label>
                                <input type="text" id="subtotal" name="subtotal"
                                    value="{{ old('subtotal', $invoice->sub_total) }}"
                                    class="w-full px-4 py-3 border border-gray-600 bg-gray-800 text-gray-300 rounded-lg shadow-md focus:outline-none focus:ring-2 focus:ring-blue-500 transition" readonly>
                            </div>
                            <div>
                                <label class="block text-gray-300 font-semibold mb-2">CGST Total:</label>
                                <input type="text" id="totalCgst" name="total_cgst"
                                    value="{{ old('total_cgst', $invoice->total_cgst) }}"
                                    class="w-full px-4 py-3 border border-gray-600 bg-gray-800 text-gray-300 rounded-lg shadow-md focus:outline-none focus:ring-2 focus:ring-blue-500 transition" readonly>
                            </div>
                            <div>
                                <label class="block text-gray-300 font-semibold mb-2">SGST Total:</label>
                                <input type="text" id="totalSgst" name="total_sgst"
                                    value="{{ old('total_sgst', $invoice->total_sgst) }}"
                                    class="w-full px-4 py-3 border border-gray-600 bg-gray-800 text-gray-300 rounded-lg shadow-md focus:outline-none focus:ring-2 focus:ring-blue-500 transition" readonly>
                            </div>
                            <div>
                                <label class="block text-gray-300 font-semibold mb-2">IGST Total:</label>
                                <input type="text" id="totalIgst" name="total_igst"
                                    value="{{ old('total_igst', $invoice->total_igst) }}"
                                    class="w-full px-4 py-3 border border-gray-600 bg-gray-800 text-gray-300 rounded-lg shadow-md focus:outline-none focus:ring-2 focus:ring-blue-500 transition" readonly>
                            </div>
                            <div>
                                <label class="block text-gray-300 font-semibold mb-2">Grand Total:</label>
                                <input type="text" id="grandTotal" name="grand_total"
                                    value="{{ old('grand_total', $invoice->grand_total) }}"
                                    class="w-full px-4 py-3 border border-gray-600 bg-gray-800 text-gray-300 rounded-lg shadow-md font-bold focus:outline-none focus:ring-2 focus:ring-blue-500 transition" readonly>
                            </div>
                        </div>
                    </div>

                    <div class="mt-6">
                        <button type="submit" class="px-6 py-3 bg-green-500 hover:bg-green-600 text-white font-semibold rounded-lg shadow-md transition">Update Invoice</button>
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

                <table class="min-w-full text-left border-collapse table-auto bg-gray-800 text-gray-300 rounded-lg shadow-md">
                    <thead>
                        <tr class="text-sm text-gray-400 bg-gray-700">
                            <th class="px-6 py-4 border-b border-gray-600">Product Name</th>    
                            <th class="px-6 py-4 border-b border-gray-600">Description</th>
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
                                <td class="px-6 py-4 border-b border-gray-600">{{ $product->description }}</td>
                                <td class="px-6 py-4 border-b border-gray-600">{{ $product->hsn_code }}</td>
                                <td class="px-6 py-4 border-b border-gray-600">{{ ($product->stock->first()->quantity ?? 0) - ($product->stock->first()->sold ?? 0) }}</td>
                                <td class="px-6 py-4 border-b border-gray-600">{{ $product->gst_percentage }}%</td>
                                <td class="px-6 py-4 border-b border-gray-600">
                                    <button type="button" class="select-product px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white rounded-lg shadow-md transition"
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
            let currentRow = null;

            // Add event listeners to existing rows on page load
            document.querySelectorAll("#productTable tr").forEach(row => {
                addEventListenersToRow(row);
            });

            // Calculate summary on page load
            calculateSummary();

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
                <td class="p-2"><input type="number" name="products[${newIndex}][quantity]" class="quantity w-full px-4 py-3 border border-gray-700 bg-gray-800 text-gray-300 rounded-lg shadow-md focus:outline-none focus:ring-2 focus:ring-blue-500 transition" value="1" min="1"></td>
                <td class="p-2"><input type="number" name="products[${newIndex}][unit_price]" class="unit-price w-full px-4 py-3 border border-gray-700 bg-gray-800 text-gray-300 rounded-lg shadow-md focus:outline-none focus:ring-2 focus:ring-blue-500 transition" value="0" min="0"></td>
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

            function addEventListenersToRow(row) {
                let quantityInput = row.querySelector(".quantity");
                let unitPriceInput = row.querySelector(".unit-price");

                row.querySelector(".open-modal").addEventListener("click", function() {
                    currentRow = row;
                    productModal.classList.remove("hidden");
                });

                quantityInput.addEventListener("input", function() {
                    calculateRowTotal(row);
                });

                unitPriceInput.addEventListener("input", function() {
                    calculateRowTotal(row);
                });

                row.querySelector(".remove-row").addEventListener("click", function() {
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
                let quantity = parseFloat(row.querySelector(".quantity").value) || 0;
                let unitPrice = parseFloat(row.querySelector(".unit-price").value) || 0;

                let cgst = parseFloat(row.querySelector(".cgst").value) || 0;
                let cgst_value = (quantity * unitPrice * cgst) / 100;
                row.querySelector(".cgst-value").value = cgst_value.toFixed(2);
                let sgst = parseFloat(row.querySelector(".sgst").value) || 0;
                let sgst_value = (quantity * unitPrice * sgst) / 100;
                row.querySelector(".sgst-value").value = sgst_value.toFixed(2);
                let igst = parseFloat(row.querySelector(".igst").value) || 0;
                let igst_value = (quantity * unitPrice * igst) / 100;
                row.querySelector(".igst-value").value = igst_value.toFixed(2);

                let subTotal = (quantity * unitPrice).toFixed(2) || 0;
                let totalGst = ((cgst + sgst + igst) / 100) * subTotal;
                let grandTotal = parseFloat(subTotal) + totalGst;

                row.querySelector(".total").value = grandTotal.toFixed(2);

                calculateSummary();
            }

            function calculateSummary() {
                let subtotal = 0,
                    totalCgst = 0,
                    totalSgst = 0,
                    totalIgst = 0,
                    grandTotal = 0;

                document.querySelectorAll("#productTable tr").forEach(row => {
                    let rowTotal = parseFloat(row.querySelector(".total").value) || 0;
                    let cgst = parseFloat(row.querySelector(".cgst").value) || 0;
                    let sgst = parseFloat(row.querySelector(".sgst").value) || 0;
                    let igst = parseFloat(row.querySelector(".igst").value) || 0;

                    let baseAmount = rowTotal / (1 + (cgst + sgst + igst) / 100);
                    subtotal += baseAmount;
                    totalCgst += (baseAmount * cgst) / 100;
                    totalSgst += (baseAmount * sgst) / 100;
                    totalIgst += (baseAmount * igst) / 100;
                });

                grandTotal = subtotal + totalCgst + totalSgst + totalIgst;

                document.getElementById("subtotal").value = subtotal.toFixed(2);
                document.getElementById("totalCgst").value = totalCgst.toFixed(2);
                document.getElementById("totalSgst").value = totalSgst.toFixed(2);
                document.getElementById("totalIgst").value = totalIgst.toFixed(2);
                document.getElementById("grandTotal").value = grandTotal.toFixed(2);
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

            closeModalBtn.addEventListener("click", function() {
                productModal.classList.add("hidden");
            });

            addRowBtn.addEventListener("click", addProductRow);

            productTable.addEventListener("click", function(event) {
                if (event.target.classList.contains("remove-row")) {
                    event.target.closest("tr").remove();
                    calculateSummary();
                }
            });
        });
    </script>
</x-app-layout>
