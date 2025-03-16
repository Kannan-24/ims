<x-app-layout>
    <x-slot name="title">
        {{ __('Edit Purchase') }} - {{ config('app.name', 'SKM') }}
    </x-slot>

    <div class="mt-20 ml-4 py-9 sm:ml-64 sm:me-4 lg:me-0">
        <div class="w-full mx-auto max-w-7xl sm:px-6 lg:px-8">

            <div class="bg-white p-6 rounded shadow">
                <h2 class="text-2xl font-bold mb-4">Edit Purchase</h2>

                <form action="{{ route('purchases.update', $purchase->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <!-- Supplier Selection -->
                    <div class="mb-4">
                        <label for="supplier_id" class="block text-gray-700 font-bold mb-2">Supplier:</label>
                        <select name="supplier_id" id="supplier_id" class="w-full border rounded px-3 py-2">
                            <option value="">Select Supplier</option>
                            @foreach ($suppliers as $supplier)
                                <option value="{{ $supplier->id }}"
                                    {{ old('supplier_id', $purchase->supplier_id) == $supplier->id ? 'selected' : '' }}>
                                    {{ $supplier->supplier_id }} - {{ $supplier->name }} - {{ $supplier->state }}
                                </option>
                                @endforeach
                            @endif
                        </select>
                    </div>

                    <!-- Purchase Date & Invoice No -->
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="invoice_no" class="block text-gray-700 font-bold mb-2">Invoice No:</label>
                            <input type="text" name="invoice_no" id="invoice_no"
                                value="{{ old('invoice_no', $purchase->invoice_no) }}"
                                class="w-full border rounded px-3 py-2" required>
                        </div>
                        <div>
                            <label for="purchase_date" class="block text-gray-700 font-bold mb-2">Purchase Date:</label>
                            <input type="date" name="purchase_date" id="purchase_date"
                                value="{{ old('purchase_date', $purchase->purchase_date) }}"
                                class="w-full border rounded px-3 py-2" required>
                        </div>
                    </div>

                    <!-- Product Table -->
                    <div class="mt-6">
                        <h3 class="text-xl font-bold mb-2">Purchase Items</h3>
                        <table class="min-w-full text-left border-collapse table-auto">
                            <thead>
                                <tr class="text-sm text-gray-600 bg-indigo-100">
                                    <th class="px-3 py-3 w-52 border-b-2 border-gray-200">Product</th>
                                    <th class="px-3 py-3 w-24 border-b-2 border-gray-200">Quantity</th>
                                    <th class="px-3 py-3 w-28 border-b-2 border-gray-200">Unit Price</th>
                                    <th class="px-3 py-3 w-40 border-b-2 border-gray-200">CGST</th>
                                    <th class="px-3 py-3 w-36 border-b-2 border-gray-200">SGST</th>
                                    <th class="px-3 py-3 w-40 border-b-2 border-gray-200">IGST</th>
                                    <th class="px-3 py-3 w-40 border-b-2 border-gray-200">Total</th>
                                    <th class="px-3 py-3 w-10 border-b-2 border-gray-200">Action</th>
                                </tr>
                            </thead>
                                @if ($purchase->products)
                                    @foreach ($purchase->products as $index => $product)
                                @foreach ($purchase->products as $index => $product)
                                    <tr>
                                        <td class="">
                                            <button type="button"
                                                class="open-modal bg-blue-500 text-white px-4 py-2 rounded hidden">Select
                                                Product</button>
                                            <input type="hidden" name="products[{{ $index }}][product_id]"
                                                class="product-id" value="{{ $product->id }}">
                                            <input type="hidden" name="products[{{ $index }}][gst_percentage]"
                                                class="gst-percentage" value="{{ $product->gst_percentage }}">
                                            <span class="product-name">{{ $product->name }}</span>
                                        </td>
                                        <td class="p-1"><input type="number"
                                                name="products[{{ $index }}][quantity]"
                                                class="quantity w-24 border rounded px-3 py-2 text-center"
                                                value="{{ $product->pivot->quantity }}" min="1"></td>
                                        <td class="p-1"><input type="number"
                                                name="products[{{ $index }}][unit_price]"
                                                class="unit-price w-28 border rounded px-3 py-2 text-center"
                                                value="{{ $product->pivot->unit_price }}" min="0"></td>
                                        <td class="p-2">
                                            <div class="flex items-center gap-2">
                                                <input type="text" name="products[{{ $index }}][cgst]"
                                                    class="cgst w-12 border rounded px-3 py-2 bg-gray-200 text-center"
                                                    value="{{ $product->pivot->cgst }}" readonly>
                                                <input type="text" name="products[{{ $index }}][cgst_value]"
                                                    class="cgst-value w-24 border rounded px-3 py-2 bg-gray-200 text-center"
                                                    value="{{ $product->pivot->cgst_value }}" readonly>
                                            </div>
                                        </td>
                                        <td class="p-2">
                                            <div class="flex items-center gap-2">
                                                <input type="text" name="products[{{ $index }}][sgst]"
                                                    class="sgst w-12 border rounded px-3 py-2 bg-gray-200 text-center"
                                                    value="{{ $product->pivot->sgst }}" readonly>
                                                <input type="text" name="products[{{ $index }}][sgst_value]"
                                                    class="sgst-value w-24 border rounded px-3 py-2 bg-gray-200 text-center"
                                                    value="{{ $product->pivot->sgst_value }}" readonly>
                                            </div>
                                        </td>
                                        <td class="p-2">
                                            <div class="flex items-center gap-2">
                                                <input type="text" name="products[{{ $index }}][igst]"
                                                    class="igst w-16 border rounded px-3 py-2 bg-gray-200 text-center"
                                                    value="{{ $product->pivot->igst }}" readonly>
                                                <input type="text" name="products[{{ $index }}][igst_value]"
                                                    class="igst-value w-24 border rounded px-3 py-2 bg-gray-200 text-center"
                                                    value="{{ $product->pivot->igst_value }}" readonly>
                                            </div>
                                        </td>
                                        <td class="p-2"><input type="text"
                                                name="products[{{ $index }}][total]"
                                                class="total w-40 border rounded px-3 py-2 bg-gray-200 text-center"
                                                value="{{ $product->pivot->total }}" readonly></td>
                                        <td class="p-2">
                                            <button type="button"
                                                class="remove-row bg-red-600 text-white px-3 py-1 rounded hover:bg-red-700">X</button>
                                        </td>
                                    </tr>
                                @endforeach
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
                                <input type="text" id="subtotal" name="subtotal"
                                    value="{{ old('subtotal', $purchase->subtotal) }}"
                                    class="w-full border rounded px-2 py-1" readonly>
                            </div>
                            <div>
                                <label class="block font-bold">CGST Total:</label>
                                <input type="text" id="totalCgst" name="total_cgst"
                                    value="{{ old('total_cgst', $purchase->total_cgst) }}"
                                    class="w-full border rounded px-2 py-1" readonly>
                            </div>
                            <div>
                                <label class="block font-bold">SGST Total:</label>
                                <input type="text" id="totalSgst" name="total_sgst"
                                    value="{{ old('total_sgst', $purchase->total_sgst) }}"
                                    class="w-full border rounded px-2 py-1" readonly>
                            </div>
                            <div>
                                <label class="block font-bold">IGST Total:</label>
                                <input type="text" id="totalIgst" name="total_igst"
                                    value="{{ old('total_igst', $purchase->total_igst) }}"
                                    class="w-full border rounded px-2 py-1" readonly>
                            </div>
                            <div>
                                <label class="block font-bold">Grand Total:</label>
                                <input type="text" id="grandTotal" name="grand_total"
                                    value="{{ old('grand_total', $purchase->grand_total) }}"
                                    class="w-full border rounded px-2 py-1 font-bold" readonly>
                            </div>
                        </div>
                    </div>

                    <!-- Submit -->
                    <div class="mt-6">
                        <button type="submit" class="bg-green-500 text-white px-6 py-2 rounded">Update
                            Purchase</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Product Selection Modal -->
    <div id="productModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center"
        style="display: none;">
        <div class="bg-white p-6 rounded shadow-lg w-1/2">
            <h2 class="text-2xl font-bold mb-4">Select Product</h2>

            <!-- Search Bar -->
            <input type="text" id="productSearch" placeholder="Search Product..."
                class="w-full mb-4 px-3 py-2 border rounded">

            <table class="min-w-full text-left border-collapse table-auto">
                <thead>
                    <tr class="text-sm text-gray-600 bg-indigo-100">
                        <th class="px-6 py-4 border-b-2 border-gray-200">Product Name</th>
                        <th class="px-6 py-4 border-b-2 border-gray-200">GST Percentage</th>
                        <th class="px-6 py-4 border-b-2 border-gray-200">Action</th>
                    </tr>
                </thead>
                <tbody id="productTableBody" class="text-sm text-gray-700">
                    @if ($products)
                        @foreach ($products as $product)
                            <tr data-id="{{ $product->id }}" class="product-row">
                                <td class="px-6 py-4 border-b border-gray-200">{{ $product->name }}</td>
                                <td class="px-6 py-4 border-b border-gray-200">{{ $product->gst_percentage }}%</td>
                                <td class="px-6 py-4 border-b border-gray-200">
                                    <button type="button"
                                        class="select-product bg-blue-500 text-white px-4 py-2 rounded"
                                        data-id="{{ $product->id }}" data-name="{{ $product->name }}"
                                        data-gst="{{ $product->gst_percentage }}"
                                        data-isigst="{{ $product->is_igst }}">
                                        Select
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>

            <button type="button" id="closeModal"
                class="mt-4 bg-red-500 text-white px-4 py-2 rounded">Close</button>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const productTable = document.getElementById("productTable");
            const productModal = document.getElementById("productModal");
            const closeModalBtn = document.getElementById("closeModal");
            let currentRow = null;

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

            document.querySelectorAll("#productTable tr").forEach(row => {
                addEventListenersToRow(row);
            });
        });
    </script>

</x-app-layout>
