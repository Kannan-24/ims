<x-app-layout>
    <x-slot name="title">
        {{ __('Create Purchase') }} - {{ config('app.name', 'SKM') }}
    </x-slot>

    <div class="mt-20 ml-4 py-9 sm:ml-64 sm:me-4 lg:me-0">
        <div class="w-full mx-auto max-w-7xl sm:px-6 lg:px-8">

            <div class="bg-white p-6 rounded shadow">
                <h2 class="text-2xl font-bold mb-4">Create Purchase</h2>

                <form action="{{ route('purchases.store') }}" method="POST">
                    @csrf

                    <!-- Supplier Selection -->
                    <div class="mb-4">
                        <label for="supplier_id" class="block text-gray-700 font-bold mb-2">Supplier:</label>
                        <select name="supplier_id" id="supplier_id" class="w-full border rounded px-3 py-2">
                            <option value="">Select Supplier</option>
                            @foreach ($suppliers as $supplier)
                                <option value="{{ $supplier->id }}"
                                    {{ old('supplier_id') == $supplier->id ? 'selected' : '' }}>
                                    {{ $supplier->supplier_name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Purchase Date & Invoice No -->
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="purchase_date" class="block text-gray-700 font-bold mb-2">Purchase Date:</label>
                            <input type="date" name="purchase_date" id="purchase_date" value="{{ old('purchase_date') }}" 
                                class="w-full border rounded px-3 py-2" required>
                        </div>
                        <div>
                            <label for="invoice_no" class="block text-gray-700 font-bold mb-2">Invoice No:</label>
                            <input type="text" name="invoice_no" id="invoice_no" value="{{ old('invoice_no') }}"
                                class="w-full border rounded px-3 py-2" required>
                        </div>
                    </div>

                    <!-- Product Table -->
                    <div class="mt-6">
                        <h3 class="text-xl font-bold mb-2">Purchase Items</h3>
                        <table class="w-full border border-gray-300">
                            <thead class="bg-gray-200">
                                <tr>
                                    <th class="p-2">Product</th>
                                    <th class="p-2">Quantity</th>
                                    <th class="p-2">Unit Price</th>
                                    <th class="p-2">CGST</th>
                                    <th class="p-2">SGST</th>
                                    <th class="p-2">IGST</th>
                                    <th class="p-2">Total</th>
                                    <th class="p-2">Action</th>
                                </tr>
                            </thead>
                            <tbody id="productTable">
                                @if(old('products'))
                                    @foreach(old('products') as $index => $product)
                                        <tr>
                                            <td>
                                                <select name="products[{{ $index }}][product_id]"
                                                    class="product-select w-full border rounded px-2 py-1">
                                                    <option value="">Select Product</option>
                                                    @foreach ($products as $product)
                                                        <option value="{{ $product->id }}"
                                                            data-gst="{{ $product->gst_percentage }}"
                                                            data-isigst="{{ $product->is_igst }}">
                                                            {{ $product->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                <input type="hidden" name="products[{{ $index }}][gst_percentage]"
                                                    class="gst-percentage" value="0">
                                            </td>
                                            <td><input type="number" name="products[{{ $index }}][quantity]"
                                                    class="quantity w-full border rounded px-2 py-1"
                                                    value="{{ $product['quantity'] }}" min="1"></td>
                                            <td><input type="number" name="products[{{ $index }}][unit_price]"
                                                    class="unit-price w-full border rounded px-2 py-1"
                                                    value="{{ $product['unit_price'] }}" min="0"></td>
                                            <td><input type="text" name="products[{{ $index }}][cgst]"
                                                    class="cgst w-full border rounded px-2 py-1" readonly></td>
                                            <td><input type="text" name="products[{{ $index }}][sgst]"
                                                    class="sgst w-full border rounded px-2 py-1" readonly></td>
                                            <td><input type="text" name="products[{{ $index }}][igst]"
                                                    class="igst w-full border rounded px-2 py-1" readonly></td>
                                            <td><input type="text" name="products[{{ $index }}][total]"
                                                    class="total w-full border rounded px-2 py-1" readonly></td>
                                            <td><button type="button"
                                                    class="remove-row bg-red-500 text-white px-2 py-1 rounded">X</button>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                        <button type="button" id="addRow" class="mt-4 bg-blue-500 text-white px-4 py-2 rounded">+
                            Add Product</button>
                    </div>

                    <!-- Summary Section -->
                    <div class="mt-6 bg-gray-100 p-4 rounded">
                        <h3 class="text-xl font-bold">Summary</h3>
                        <div class="grid grid-cols-3 gap-4">
                            <div>
                                <label class="block font-bold">Subtotal:</label>
                                <input type="text" id="subtotal" name="subtotal" value="{{ old('subtotal') }}"
                                    class="w-full border rounded px-2 py-1" readonly>
                            </div>
                            <div>
                                <label class="block font-bold">CGST Total:</label>
                                <input type="text" id="totalCgst" name="total_cgst" value="{{ old('total_cgst') }}"
                                    class="w-full border rounded px-2 py-1" readonly>
                            </div>
                            <div>
                                <label class="block font-bold">SGST Total:</label>
                                <input type="text" id="totalSgst" name="total_sgst" value="{{ old('total_sgst') }}"
                                    class="w-full border rounded px-2 py-1" readonly>
                            </div>
                            <div>
                                <label class="block font-bold">IGST Total:</label>
                                <input type="text" id="totalIgst" name="total_igst" value="{{ old('total_igst') }}"
                                    class="w-full border rounded px-2 py-1" readonly>
                            </div>
                            <div>
                                <label class="block font-bold">Grand Total:</label>
                                <input type="text" id="grandTotal" name="grand_total" value="{{ old('grand_total') }}"
                                    class="w-full border rounded px-2 py-1 font-bold" readonly>
                            </div>
                        </div>
                    </div>

                    <!-- Submit -->
                    <div class="mt-6">
                        <button type="submit" class="bg-green-500 text-white px-6 py-2 rounded">Submit
                            Purchase</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const productTable = document.getElementById("productTable");
            const addRowBtn = document.getElementById("addRow");

            function addProductRow() {
                var newIndex = productTable.rows.length;

                const row = document.createElement("tr");
                row.innerHTML = `
                    <td>
                        <select name="products[${newIndex}][product_id]" class="product-select w-full border rounded px-2 py-1">
                            <option value="">Select Product</option>
                            @foreach ($products as $product)
                                <option value="{{ $product->id }}" data-gst="{{ $product->gst_percentage }}" data-isigst="{{ $product->is_igst }}">
                                    {{ $product->name }}
                                </option>
                            @endforeach
                        </select>
                        <input type="hidden" name="products[${newIndex}][gst_percentage]" class="gst-percentage" value="0">
                    </td>
                    <td><input type="number" name="products[${newIndex}][quantity]" class="quantity w-full border rounded px-2 py-1" value="1" min="1"></td>
                    <td><input type="number" name="products[${newIndex}][unit_price]" class="unit-price w-full border rounded px-2 py-1" value="0" min="0"></td>
                    <td><input type="text" name="products[${newIndex}][cgst]" class="cgst w-full border rounded px-2 py-1" readonly></td>
                    <td><input type="text" name="products[${newIndex}][sgst]" class="sgst w-full border rounded px-2 py-1" readonly></td>
                    <td><input type="text" name="products[${newIndex}][igst]" class="igst w-full border rounded px-2 py-1" readonly></td>
                    <td><input type="text" name="products[${newIndex}][total]" class="total w-full border rounded px-2 py-1" readonly></td>
                    <td><button type="button" class="remove-row bg-red-500 text-white px-2 py-1 rounded">X</button></td>
                `;

                productTable.appendChild(row);
                addEventListenersToRow(row);
            }




            function addEventListenersToRow(row) {
                let productSelect = row.querySelector(".product-select");
                let quantityInput = row.querySelector(".quantity");
                let unitPriceInput = row.querySelector(".unit-price");

                productSelect.addEventListener("change", function() {
                    updateGSTValues(row);
                    calculateRowTotal(row);
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
                let selectedOption = row.querySelector(".product-select").options[row.querySelector(
                    ".product-select").selectedIndex];
                let gstPercentage = parseFloat(selectedOption.getAttribute("data-gst") || 0);
                let isIgst = selectedOption.getAttribute("data-isigst") === "1";

                row.querySelector(".cgst").value = isIgst ? "0" : (gstPercentage / 2).toFixed(2);
                row.querySelector(".sgst").value = isIgst ? "0" : (gstPercentage / 2).toFixed(2);
                row.querySelector(".igst").value = isIgst ? gstPercentage.toFixed(2) : "0";
            }

            function calculateRowTotal(row) {
                let quantity = parseFloat(row.querySelector(".quantity").value) || 0;
                let unitPrice = parseFloat(row.querySelector(".unit-price").value) || 0;

                let cgst = parseFloat(row.querySelector(".cgst").value) || 0;
                let sgst = parseFloat(row.querySelector(".sgst").value) || 0;
                let igst = parseFloat(row.querySelector(".igst").value) || 0;

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

            addRowBtn.addEventListener("click", addProductRow);
        });
    </script>

</x-app-layout>
