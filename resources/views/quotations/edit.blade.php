<x-app-layout>
    <x-slot name="title">
        {{ __('Edit Quotation') }} - {{ config('app.name', 'SKM') }}
    </x-slot>

    <div class="mt-20 ml-4 py-9 sm:ml-64 sm:me-4 lg:me-0">
        <div class="w-full mx-auto max-w-7xl sm:px-6 lg:px-8">

            <x-bread-crumb-navigation />
            
            <div class="bg-gray-800 p-6 rounded-lg shadow-md">
                <h2 class="text-3xl font-bold text-gray-200 mb-6">Edit Quotation</h2>

                <form action="{{ route('quotations.update', $quotation->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <!-- Customer Selection -->
                    <div class="mb-6">
                        <label for="customer_id" class="block text-gray-300 font-semibold mb-2">Customer:</label>
                        <select name="customer_id" id="customer_id" class="w-full px-4 py-3 border border-gray-700 bg-gray-800 text-gray-300 rounded-lg shadow-md focus:outline-none focus:ring-2 focus:ring-blue-500 transition">
                            <option value="">Select Customer</option>
                            @foreach ($customers as $customer)
                                <option value="{{ $customer->id }}"
                                    {{ old('customer_id', $quotation->customer_id) == $customer->id ? 'selected' : '' }}>
                                    {{ $customer->company_name }} - {{ $customer->state }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Quotation Date & Quotation No -->
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="quotation_code" class="block text-gray-300 font-semibold mb-2">Quotation No:</label>
                            <input type="text" name="quotation_code" id="quotation_code" value="{{ old('quotation_code', $quotation->quotation_code) }}"
                                class="w-full px-4 py-3 border border-gray-700 bg-gray-800 text-gray-300 rounded-lg shadow-md focus:outline-none focus:ring-2 focus:ring-blue-500 transition" required>
                        </div>
                        <div>
                            <label for="quotation_date" class="block text-gray-300 font-semibold mb-2">Quotation Date:</label>
                            <input type="date" name="quotation_date" id="quotation_date"
                                value="{{ old('quotation_date', $quotation->quotation_date) }}" class="w-full px-4 py-3 border border-gray-700 bg-gray-800 text-gray-300 rounded-lg shadow-md focus:outline-none focus:ring-2 focus:ring-blue-500 transition" required>
                        </div>
                    </div>

                    <!-- Terms and Condition -->
                    <div class="mb-6">
                        <label for="terms_condition" class="block text-gray-300 font-semibold mb-2">Terms and condition:</label>
                        <textarea id="terms_condition" name="terms_condition" rows="4"
                            class="w-full px-4 py-3 border border-gray-700 bg-gray-800 text-gray-300 rounded-lg shadow-md focus:outline-none focus:ring-2 focus:ring-blue-500 transition"
                            placeholder="Enter terms and condition">{{ old('terms_condition', $quotation->terms_condition) }}</textarea>
                    </div>
                                        
                    <!-- Product Table -->
                    <div class="mt-6">
                        <h3 class="text-2xl font-bold text-gray-200 mb-4">Quotation Items</h3>
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
                                @foreach ($quotation->items as $index => $item)
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
                    <!-- Services Table -->
                    <div class="mt-6">
                        <h3 class="text-2xl font-bold text-gray-200 mb-4">Service Items</h3>
                        <table class="min-w-full text-left border-collapse table-auto bg-gray-800 text-gray-300 rounded-lg shadow-md">
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
                                @foreach ($quotation->services as $index => $service)
                                    <tr>
                                        <td>
                                            <input type="hidden" name="services[{{ $index }}][service_id]" value="{{ $service->service_id }}">
                                            <span>{{ $service->service->name }}</span>
                                        </td>
                                        <td><input type="number" name="services[{{ $index }}][quantity]" value="{{ $service->quantity }}" class="quantity"></td>
                                        <td><input type="number" name="services[{{ $index }}][unit_price]" value="{{ $service->unit_price }}" class="unit-price"></td>
                                        <td><input type="number" name="services[{{ $index }}][gst_percentage]" value="{{ $service->gst_percentage }}" class="gst-percentage"></td>
                                        <td><input type="number" name="services[{{ $index }}][gst_total]" value="{{ $service->gst_total }}" class="gst-total"></td>
                                        <td><input type="number" name="services[{{ $index }}][total]" value="{{ $service->total }}" class="total"></td>
                                        <td><button type="button" class="remove-row">Remove</button></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <button type="button" id="addServiceRow" class="mt-4 bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg shadow-md transition">+ Add Service</button>
                    </div>

                    <!-- Summary Section -->
                    <div class="mt-6 bg-gray-700 p-4 rounded-lg shadow-md">
                        <h3 class="text-2xl font-bold text-gray-200 mb-4">Summary</h3>
                        <div class="grid grid-cols-3 gap-4">
                            <div>
                                <label class="block text-gray-300 font-semibold mb-2">Subtotal:</label>
                                <input type="text" id="subtotal" name="sub_total" value="{{ old('subtotal', $quotation->sub_total) }}"
                                    class="w-full px-4 py-3 border border-gray-600 bg-gray-800 text-gray-300 rounded-lg shadow-md focus:outline-none focus:ring-2 focus:ring-blue-500 transition" readonly>
                            </div>
                            <div>
                                <label class="block text-gray-300 font-semibold mb-2">CGST Total:</label>
                                <input type="text" id="totalCgst" name="total_cgst" value="{{ old('total_cgst', $quotation->total_cgst) }}"
                                    class="w-full px-4 py-3 border border-gray-600 bg-gray-800 text-gray-300 rounded-lg shadow-md focus:outline-none focus:ring-2 focus:ring-blue-500 transition" readonly>
                            </div>
                            <div>
                                <label class="block text-gray-300 font-semibold mb-2">SGST Total:</label>
                                <input type="text" id="totalSgst" name="total_sgst" value="{{ old('total_sgst', $quotation->total_sgst) }}"
                                    class="w-full px-4 py-3 border border-gray-600 bg-gray-800 text-gray-300 rounded-lg shadow-md focus:outline-none focus:ring-2 focus:ring-blue-500 transition" readonly>
                            </div>
                            <div>
                                <label class="block text-gray-300 font-semibold mb-2">IGST Total:</label>
                                <input type="text" id="totalIgst" name="total_igst" value="{{ old('total_igst', $quotation->total_igst) }}"
                                    class="w-full px-4 py-3 border border-gray-600 bg-gray-800 text-gray-300 rounded-lg shadow-md focus:outline-none focus:ring-2 focus:ring-blue-500 transition" readonly>
                            </div>
                            <!-- GST Total -->
                            <div>
                                <label class="block text-gray-300 font-semibold mb-2">GST Total:</label>
                                <input type="text" id="totalGst" name="total_gst" value="{{ old('total_gst', $quotation->total_gst) }}"
                                    class="w-full px-4 py-3 border border-gray-600 bg-gray-800 text-gray-300 rounded-lg shadow-md focus:outline-none focus:ring-2 focus:ring-blue-500 transition" readonly>
                            </div>
                            <div>
                                <label class="block text-gray-300 font-semibold mb-2">Grand Total:</label>
                                <input type="text" id="grandTotal" name="total"
                                    value="{{ old('grand_total', $quotation->grand_total) }}" class="w-full px-4 py-3 border border-gray-600 bg-gray-800 text-gray-300 rounded-lg shadow-md font-bold focus:outline-none focus:ring-2 focus:ring-blue-500 transition" readonly>
                            </div>
                        </div>
                    </div>

                    <div class="mt-6">
                        <button type="submit" class="px-6 py-3 bg-green-500 hover:bg-green-600 text-white font-semibold rounded-lg shadow-md transition">Update Quotation</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const productTable = document.getElementById("productTable");
            const addRowBtn = document.getElementById("addRow");
            const serviceTable = document.getElementById("serviceTable");
            const addServiceRowBtn = document.getElementById("addServiceRow");
            let currentRow = null;

            function addProductRow() {
                const newIndex = productTable.rows.length;
                const row = document.createElement("tr");
                row.innerHTML = `
                    <td class="p-2">
                        <button type="button" class="open-modal bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg shadow-md transition">Select Product</button>
                        <input type="hidden" name="products[${newIndex}][product_id]" class="product-id">
                        <span class="product-name"></span>
                    </td>
                    <td class="p-2"><input type="number" name="products[${newIndex}][quantity]" class="quantity w-full px-4 py-3 border border-gray-700 bg-gray-800 text-gray-300 rounded-lg shadow-md focus:outline-none focus:ring-2 focus:ring-blue-500 transition" value="1" min="1"></td>
                    <td class="p-2"><input type="number" name="products[${newIndex}][unit_price]" class="unit-price w-full px-4 py-3 border border-gray-700 bg-gray-800 text-gray-300 rounded-lg shadow-md focus:outline-none focus:ring-2 focus:ring-blue-500 transition" value="0" min="0"></td>
                    <td class="p-2"><input type="text" name="products[${newIndex}][total]" class="total w-full px-4 py-3 border border-gray-700 bg-gray-800 text-gray-300 rounded-lg shadow-md focus:outline-none focus:ring-2 focus:ring-blue-500 transition" readonly></td>
                    <td class="p-2">
                        <button type="button" class="remove-row bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg shadow-md transition">X</button>
                    </td>`;
                productTable.appendChild(row);
                addEventListenersToRow(row);
            }

            function addServiceRow() {
                const newIndex = serviceTable.rows.length;
                const row = document.createElement("tr");
                row.innerHTML = `
                    <td class="p-2">
                        <button type="button" class="open-service-modal bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg shadow-md transition">Select Service</button>
                        <input type="hidden" name="services[${newIndex}][service_id]" class="service-id">
                        <span class="service-name"></span>
                    </td>
                    <td class="p-2"><input type="number" name="services[${newIndex}][quantity]" class="service-quantity w-full px-4 py-3 border border-gray-700 bg-gray-800 text-gray-300 rounded-lg shadow-md focus:outline-none focus:ring-2 focus:ring-blue-500 transition" value="1" min="1"></td>
                    <td class="p-2"><input type="number" name="services[${newIndex}][unit_price]" class="service-unit-price w-full px-4 py-3 border border-gray-700 bg-gray-800 text-gray-300 rounded-lg shadow-md focus:outline-none focus:ring-2 focus:ring-blue-500 transition" value="0" min="0"></td>
                    <td class="p-2"><input type="text" name="services[${newIndex}][total]" class="service-total w-full px-4 py-3 border border-gray-700 bg-gray-800 text-gray-300 rounded-lg shadow-md focus:outline-none focus:ring-2 focus:ring-blue-500 transition" readonly></td>
                    <td class="p-2">
                        <button type="button" class="remove-service-row bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg shadow-md transition">X</button>
                    </td>`;
                serviceTable.appendChild(row);
                addEventListenersToServiceRow(row);
            }

            function addEventListenersToRow(row) {
                const quantityInput = row.querySelector(".quantity");
                const unitPriceInput = row.querySelector(".unit-price");

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

            function addEventListenersToServiceRow(row) {
                const quantityInput = row.querySelector(".service-quantity");
                const unitPriceInput = row.querySelector(".service-unit-price");

                quantityInput.addEventListener("input", function() {
                    calculateServiceRowTotal(row);
                });

                unitPriceInput.addEventListener("input", function() {
                    calculateServiceRowTotal(row);
                });

                row.querySelector(".remove-service-row").addEventListener("click", function() {
                    row.remove();
                    calculateSummary();
                });
            }

            function calculateRowTotal(row) {
                const quantity = parseFloat(row.querySelector(".quantity").value) || 0;
                const unitPrice = parseFloat(row.querySelector(".unit-price").value) || 0;
                const total = quantity * unitPrice;
                row.querySelector(".total").value = total.toFixed(2);
                calculateSummary();
            }

            function calculateServiceRowTotal(row) {
                const quantity = parseFloat(row.querySelector(".service-quantity").value) || 0;
                const unitPrice = parseFloat(row.querySelector(".service-unit-price").value) || 0;
                const total = quantity * unitPrice;
                row.querySelector(".service-total").value = total.toFixed(2);
                calculateSummary();
            }

            function calculateSummary() {
                let subtotal = 0;
                let grandTotal = 0;

                document.querySelectorAll("#productTable .total").forEach(input => {
                    subtotal += parseFloat(input.value) || 0;
                });

                document.querySelectorAll("#serviceTable .service-total").forEach(input => {
                    subtotal += parseFloat(input.value) || 0;
                });

                grandTotal = subtotal;

                document.getElementById("subtotal").value = subtotal.toFixed(2);
                document.getElementById("grandTotal").value = grandTotal.toFixed(2);
            }

            addRowBtn.addEventListener("click", addProductRow);
            addServiceRowBtn.addEventListener("click", addServiceRow);
        });
    </script>
