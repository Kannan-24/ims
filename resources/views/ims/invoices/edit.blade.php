<x-app-layout>
    <x-slot name="title">
        {{ __('Edit Invoice') }} - {{ config('app.name', 'SKM') }}
    </x-slot>

    <div class="bg-white min-h-screen" x-data="invoiceEditManager()" x-init="init()">
        <!-- Breadcrumbs -->
        <div class="px-6 py-3 bg-gray-50 border-b border-gray-200">
            <nav class="flex" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-3">
                    <li class="inline-flex items-center">
                        <a href="{{ route('dashboard') }}"
                            class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-blue-600">
                            <i class="fas fa-home mr-2"></i>
                            Dashboard
                        </a>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                            <a href="{{ route('invoices.index') }}"
                                class="text-sm font-medium text-gray-700 hover:text-blue-600">
                                Invoices
                            </a>
                        </div>
                    </li>
                    <li aria-current="page">
                        <div class="flex items-center">
                            <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                            <span class="text-sm font-medium text-gray-500">Edit Invoice
                                #{{ $invoice->invoice_no }}</span>
                        </div>
                    </li>
                </ol>
            </nav>
        </div>

        <!-- Header -->
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Edit Invoice #{{ $invoice->invoice_no }}</h1>
                    <p class="text-sm text-gray-600 mt-1">Update invoice details, products and services</p>
                </div>
                <div class="flex items-center space-x-3">
                    <a href="{{ route('invoices.index') }}"
                        class="inline-flex items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium rounded-lg transition-colors">
                        <i class="fas fa-arrow-left w-4 h-4 mr-2"></i>
                        Back to List
                    </a>
                </div>
            </div>
        </div>

        <!-- Error Messages -->
        @if ($errors->any())
            <div class="mx-6 mt-6 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg">
                <div class="flex">
                    <i class="fas fa-exclamation-circle text-red-400 mr-3 mt-0.5"></i>
                    <div>
                        <h3 class="font-semibold mb-2">Please fix the following errors:</h3>
                        <ul class="list-disc list-inside space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endif

        <!-- Content -->
        <div class="p-6">
            <form action="{{ route('invoices.update', $invoice->id) }}" method="POST" id="invoiceForm"
                @submit.prevent="submitForm">
                @csrf
                @method('PUT')

                <!-- Tab Navigation -->
                <div class="border-b border-gray-200 mb-6">
                    <nav class="-mb-px flex space-x-8">
                        <button type="button" @click="activeTab = 'basic'"
                            :class="activeTab === 'basic' ? 'border-blue-500 text-blue-600' :
                                'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                            class="py-2 px-1 border-b-2 font-medium text-sm">
                            <i class="fas fa-file-invoice mr-2"></i>
                            Invoice Information
                        </button>
                        <button type="button" @click="activeTab = 'products'"
                            :class="activeTab === 'products' ? 'border-blue-500 text-blue-600' :
                                'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                            class="py-2 px-1 border-b-2 font-medium text-sm">
                            <i class="fas fa-box mr-2"></i>
                            Products
                            <span x-show="products.length > 0"
                                class="ml-1 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-blue-100 bg-blue-600 rounded-full"
                                x-text="products.length"></span>
                        </button>
                        <button type="button" @click="activeTab = 'services'"
                            :class="activeTab === 'services' ? 'border-blue-500 text-blue-600' :
                                'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                            class="py-2 px-1 border-b-2 font-medium text-sm">
                            <i class="fas fa-cogs mr-2"></i>
                            Services
                            <span x-show="services.length > 0"
                                class="ml-1 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-green-100 bg-green-600 rounded-full"
                                x-text="services.length"></span>
                        </button>
                        <button type="button" @click="activeTab = 'summary'"
                            :class="activeTab === 'summary' ? 'border-blue-500 text-blue-600' :
                                'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                            class="py-2 px-1 border-b-2 font-medium text-sm">
                            <i class="fas fa-calculator mr-2"></i>
                            Summary & Review
                        </button>
                    </nav>
                </div>

                <!-- Tab Content -->
                <div class="space-y-6">
                    <!-- Basic Information Tab -->
                    <div x-show="activeTab === 'basic'" x-transition:enter="transition ease-out duration-200"
                        x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
                        <div class="bg-white border border-gray-200 rounded-lg p-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Invoice & Customer Details</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Invoice Number -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Invoice Number <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" name="invoice_no" required
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                        value="{{ old('invoice_no', $invoice->invoice_no) }}" x-model="invoiceNo">
                                </div>

                                <!-- Order Number -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Order Number <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" name="order_no" required
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                        value="{{ old('order_no', $invoice->order_no) }}" x-model="orderNo">
                                </div>

                                <!-- Customer Selection -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Customer <span class="text-red-500">*</span>
                                    </label>
                                    <select name="customer" required
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                        x-model="selectedCustomer" @change="updateContactPersons()">
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
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Contact Person <span class="text-red-500">*</span>
                                    </label>
                                    <select name="contact_person" required
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                        x-model="selectedContactPerson" :disabled="!selectedCustomer">
                                        <option value="">Select Contact Person</option>
                                        <template x-for="contact in contactPersons" :key="contact.id">
                                            <option :value="contact.id"
                                                :selected="contact.id == selectedContactPerson" x-text="contact.name">
                                            </option>
                                        </template>
                                    </select>
                                </div>

                                <!-- Invoice Date -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Invoice Date <span class="text-red-500">*</span>
                                    </label>
                                    <input type="date" name="invoice_date" required
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                        value="{{ old('invoice_date', $invoice->invoice_date) }}"
                                        x-model="invoiceDate">
                                </div>

                                <!-- Order Date -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Order Date <span class="text-red-500">*</span>
                                    </label>
                                    <input type="date" name="order_date" required
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                        value="{{ old('order_date', $invoice->order_date) }}" x-model="orderDate">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Products Tab -->
                    <div x-show="activeTab === 'products'" x-transition:enter="transition ease-out duration-200"
                        x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
                        <div class="bg-white border border-gray-200 rounded-lg p-6">
                            <div class="flex items-center justify-between mb-4">
                                <h3 class="text-lg font-semibold text-gray-900">Product Items</h3>
                                <button type="button" @click="showProductModal = true"
                                    class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors">
                                    <i class="fas fa-plus w-4 h-4 mr-2"></i>
                                    Add Product
                                </button>
                            </div>

                            <div x-show="products.length === 0" class="text-center py-8">
                                <i class="fas fa-box text-gray-300 text-4xl mb-4"></i>
                                <h4 class="text-lg font-medium text-gray-900 mb-2">No products added</h4>
                                <p class="text-gray-500 mb-4">Add products to the invoice</p>
                                <button type="button" @click="showProductModal = true"
                                    class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors">
                                    <i class="fas fa-plus w-4 h-4 mr-2"></i>
                                    Add First Product
                                </button>
                            </div>

                            <!-- Products Table -->
                            <div x-show="products.length > 0" class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th
                                                class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                                Product</th>
                                            <th
                                                class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                                Qty</th>
                                            <th
                                                class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                                Unit Price</th>
                                            <th
                                                class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                                CGST</th>
                                            <th
                                                class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                                SGST</th>
                                            <th
                                                class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                                IGST</th>
                                            <th
                                                class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                                Total</th>
                                            <th
                                                class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                                Action</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        <template x-for="(product, index) in products" :key="index">
                                            <tr>
                                                <td class="px-4 py-4">
                                                    <div class="flex flex-col">
                                                        <span class="text-sm font-medium text-gray-900"
                                                            x-text="product.name"></span>
                                                        <button type="button" @click="changeProduct(index)"
                                                            class="text-xs text-blue-600 hover:text-blue-800 mt-1 text-left">
                                                            Change Product
                                                        </button>
                                                    </div>
                                                    <input type="hidden" :name="`products[${index}][product_id]`"
                                                        :value="product.id">
                                                    <input type="hidden" :name="`products[${index}][gst_percentage]`"
                                                        :value="product.gst_percentage">
                                                </td>
                                                <td class="px-4 py-4">
                                                    <input type="number" :name="`products[${index}][quantity]`"
                                                        x-model="product.quantity"
                                                        @input="calculateProductTotal(index)"
                                                        class="w-20 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                                        min="1">
                                                </td>
                                                <td class="px-4 py-4">
                                                    <input type="number" :name="`products[${index}][unit_price]`"
                                                        x-model="product.unit_price"
                                                        @input="calculateProductTotal(index)"
                                                        class="w-24 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                                        min="0" step="0.01">
                                                </td>
                                                <td class="px-4 py-4">
                                                    <div class="text-sm text-gray-900">
                                                        <span x-text="product.cgst_rate"></span>% = ₹<span
                                                            x-text="product.cgst_value"></span>
                                                    </div>
                                                    <input type="hidden" :name="`products[${index}][cgst]`"
                                                        :value="product.cgst_rate">
                                                    <input type="hidden" :name="`products[${index}][cgst_value]`"
                                                        :value="product.cgst_value">
                                                </td>
                                                <td class="px-4 py-4">
                                                    <div class="text-sm text-gray-900">
                                                        <span x-text="product.sgst_rate"></span>% = ₹<span
                                                            x-text="product.sgst_value"></span>
                                                    </div>
                                                    <input type="hidden" :name="`products[${index}][sgst]`"
                                                        :value="product.sgst_rate">
                                                    <input type="hidden" :name="`products[${index}][sgst_value]`"
                                                        :value="product.sgst_value">
                                                </td>
                                                <td class="px-4 py-4">
                                                    <div class="text-sm text-gray-900">
                                                        <span x-text="product.igst_rate"></span>% = ₹<span
                                                            x-text="product.igst_value"></span>
                                                    </div>
                                                    <input type="hidden" :name="`products[${index}][igst]`"
                                                        :value="product.igst_rate">
                                                    <input type="hidden" :name="`products[${index}][igst_value]`"
                                                        :value="product.igst_value">
                                                </td>
                                                <td class="px-4 py-4">
                                                    <div class="text-sm font-medium text-gray-900">₹<span
                                                            x-text="product.total"></span></div>
                                                    <input type="hidden" :name="`products[${index}][total]`"
                                                        :value="product.total">
                                                </td>
                                                <td class="px-4 py-4">
                                                    <button type="button" @click="removeProduct(index)"
                                                        class="text-red-600 hover:text-red-800 transition-colors">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        </template>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Services Tab -->
                    <div x-show="activeTab === 'services'" x-transition:enter="transition ease-out duration-200"
                        x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
                        <div class="bg-white border border-gray-200 rounded-lg p-6">
                            <div class="flex items-center justify-between mb-4">
                                <h3 class="text-lg font-semibold text-gray-900">Service Items</h3>
                                <button type="button" @click="showServiceModal = true"
                                    class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-lg transition-colors">
                                    <i class="fas fa-plus w-4 h-4 mr-2"></i>
                                    Add Service
                                </button>
                            </div>

                            <div x-show="services.length === 0" class="text-center py-8">
                                <i class="fas fa-cogs text-gray-300 text-4xl mb-4"></i>
                                <h4 class="text-lg font-medium text-gray-900 mb-2">No services added</h4>
                                <p class="text-gray-500 mb-4">Add services to include in invoice (optional)</p>
                                <button type="button" @click="showServiceModal = true"
                                    class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-lg transition-colors">
                                    <i class="fas fa-plus w-4 h-4 mr-2"></i>
                                    Add Service
                                </button>
                            </div>

                            <!-- Services Table -->
                            <div x-show="services.length > 0" class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th
                                                class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                                Service</th>
                                            <th
                                                class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                                Qty</th>
                                            <th
                                                class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                                Unit Price</th>
                                            <th
                                                class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                                GST %</th>
                                            <th
                                                class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                                GST Amount</th>
                                            <th
                                                class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                                Total</th>
                                            <th
                                                class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                                Action</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        <template x-for="(service, index) in services" :key="index">
                                            <tr>
                                                <td class="px-4 py-4">
                                                    <div class="flex flex-col">
                                                        <span class="text-sm font-medium text-gray-900"
                                                            x-text="service.name"></span>
                                                        <button type="button" @click="changeService(index)"
                                                            class="text-xs text-green-600 hover:text-green-800 mt-1 text-left">
                                                            Change Service
                                                        </button>
                                                    </div>
                                                    <input type="hidden" :name="`services[${index}][service_id]`"
                                                        :value="service.id">
                                                </td>
                                                <td class="px-4 py-4">
                                                    <input type="number" :name="`services[${index}][quantity]`"
                                                        x-model="service.quantity"
                                                        @input="calculateServiceTotal(index)"
                                                        class="w-20 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                                        min="1">
                                                </td>
                                                <td class="px-4 py-4">
                                                    <input type="number" :name="`services[${index}][unit_price]`"
                                                        x-model="service.unit_price"
                                                        @input="calculateServiceTotal(index)"
                                                        class="w-24 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                                        min="0" step="0.01">
                                                </td>
                                                <td class="px-4 py-4">
                                                    <div class="text-sm text-gray-900"
                                                        x-text="service.gst_percentage + '%'"></div>
                                                    <input type="hidden" :name="`services[${index}][gst_percentage]`"
                                                        :value="service.gst_percentage">
                                                </td>
                                                <td class="px-4 py-4">
                                                    <div class="text-sm text-gray-900">₹<span
                                                            x-text="service.gst_total"></span></div>
                                                    <input type="hidden" :name="`services[${index}][gst_total]`"
                                                        :value="service.gst_total">
                                                </td>
                                                <td class="px-4 py-4">
                                                    <div class="text-sm font-medium text-gray-900">₹<span
                                                            x-text="service.total"></span></div>
                                                    <input type="hidden" :name="`services[${index}][total]`"
                                                        :value="service.total">
                                                </td>
                                                <td class="px-4 py-4">
                                                    <button type="button" @click="removeService(index)"
                                                        class="text-red-600 hover:text-red-800 transition-colors">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        </template>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Summary Tab -->
                    <div x-show="activeTab === 'summary'" x-transition:enter="transition ease-out duration-200"
                        x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
                        <div class="bg-white border border-gray-200 rounded-lg p-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-6">Invoice Summary</h3>

                            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                                <!-- Product Summary -->
                                <div>
                                    <h4 class="text-md font-semibold text-blue-600 mb-4">Product Summary</h4>
                                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 space-y-3">
                                        <div class="flex justify-between">
                                            <span class="text-gray-700">Product Subtotal:</span>
                                            <span class="font-medium">₹<span
                                                    x-text="summary.product_subtotal"></span></span>
                                            <input type="hidden" name="product_subtotal"
                                                :value="summary.product_subtotal">
                                        </div>
                                        <div class="flex justify-between">
                                            <span class="text-gray-700">Product CGST:</span>
                                            <span class="font-medium">₹<span
                                                    x-text="summary.product_total_cgst"></span></span>
                                            <input type="hidden" name="product_total_cgst"
                                                :value="summary.product_total_cgst">
                                        </div>
                                        <div class="flex justify-between">
                                            <span class="text-gray-700">Product SGST:</span>
                                            <span class="font-medium">₹<span
                                                    x-text="summary.product_total_sgst"></span></span>
                                            <input type="hidden" name="product_total_sgst"
                                                :value="summary.product_total_sgst">
                                        </div>
                                        <div class="flex justify-between">
                                            <span class="text-gray-700">Product IGST:</span>
                                            <span class="font-medium">₹<span
                                                    x-text="summary.product_total_igst"></span></span>
                                            <input type="hidden" name="product_total_igst"
                                                :value="summary.product_total_igst">
                                        </div>
                                        <div
                                            class="flex justify-between border-t border-blue-300 pt-3 text-lg font-bold">
                                            <span class="text-gray-900">Product Total:</span>
                                            <span class="text-blue-600">₹<span
                                                    x-text="summary.product_total"></span></span>
                                            <input type="hidden" name="product_total"
                                                :value="summary.product_total">
                                        </div>
                                    </div>
                                </div>

                                <!-- Service Summary -->
                                <div x-show="services.length > 0">
                                    <h4 class="text-md font-semibold text-green-600 mb-4">Service Summary</h4>
                                    <div class="bg-green-50 border border-green-200 rounded-lg p-4 space-y-3">
                                        <div class="flex justify-between">
                                            <span class="text-gray-700">Service Subtotal:</span>
                                            <span class="font-medium">₹<span
                                                    x-text="summary.service_subtotal"></span></span>
                                            <input type="hidden" name="service_subtotal"
                                                :value="summary.service_subtotal">
                                        </div>
                                        <div class="flex justify-between">
                                            <span class="text-gray-700">Service CGST:</span>
                                            <span class="font-medium">₹<span
                                                    x-text="summary.service_total_cgst"></span></span>
                                            <input type="hidden" name="service_total_cgst"
                                                :value="summary.service_total_cgst">
                                        </div>
                                        <div class="flex justify-between">
                                            <span class="text-gray-700">Service SGST:</span>
                                            <span class="font-medium">₹<span
                                                    x-text="summary.service_total_sgst"></span></span>
                                            <input type="hidden" name="service_total_sgst"
                                                :value="summary.service_total_sgst">
                                        </div>
                                        <div
                                            class="flex justify-between border-t border-green-300 pt-3 text-lg font-bold">
                                            <span class="text-gray-900">Service Total:</span>
                                            <span class="text-green-600">₹<span
                                                    x-text="summary.service_total"></span></span>
                                            <input type="hidden" name="service_total"
                                                :value="summary.service_total">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Grand Total -->
                            <div class="mt-8">
                                <h4 class="text-lg font-semibold text-yellow-600 mb-4">Grand Total</h4>
                                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-6">
                                    <div class="space-y-4">
                                        <div class="flex justify-between text-lg">
                                            <span class="text-gray-700">Grand Subtotal:</span>
                                            <span class="font-semibold">₹<span
                                                    x-text="summary.grand_sub_total"></span></span>
                                            <input type="hidden" name="grand_sub_total"
                                                :value="summary.grand_sub_total">
                                        </div>
                                        <div class="flex justify-between text-lg">
                                            <span class="text-gray-700">Grand GST Total:</span>
                                            <span class="font-semibold">₹<span
                                                    x-text="summary.grand_gst_total"></span></span>
                                            <input type="hidden" name="grand_gst_total"
                                                :value="summary.grand_gst_total">
                                        </div>
                                        <div
                                            class="flex justify-between border-t border-yellow-300 pt-4 text-2xl font-bold">
                                            <span class="text-gray-900">Grand Total:</span>
                                            <span class="text-yellow-600">₹<span
                                                    x-text="summary.grand_total"></span></span>
                                            <input type="hidden" name="grand_total" :value="summary.grand_total">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="flex items-center justify-between pt-6 border-t border-gray-200 mt-6">
                    <!-- Left side - Previous button -->
                    <div>
                        <button type="button" @click="previousStep()" x-show="activeTab !== 'basic'"
                            class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">
                            <i class="fas fa-chevron-left mr-2"></i>
                            Previous
                        </button>
                    </div>

                    <!-- Right side - Next/Submit buttons -->
                    <div class="flex items-center space-x-3">
                        <a href="{{ route('invoices.index') }}"
                            class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">
                            Cancel
                        </a>

                        <!-- Next Button (shown when not on last tab) -->
                        <button type="button" @click="nextStep()" x-show="activeTab !== 'summary'"
                            class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                            Next
                            <i class="fas fa-chevron-right ml-2"></i>
                        </button>

                        <!-- Submit Button (shown only on last tab) -->
                        <button type="submit" x-show="activeTab === 'summary'" :disabled="isSubmitting"
                            class="px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors disabled:bg-green-400 inline-flex items-center">
                            <span x-show="!isSubmitting">
                                <i class="fas fa-save mr-2"></i>
                                Update Invoice
                            </span>
                            <span x-show="isSubmitting" class="inline-flex items-center">
                                <svg class="animate-spin -ml-1 mr-3 h-4 w-4 text-white"
                                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10"
                                        stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor"
                                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                    </path>
                                </svg>
                                Updating...
                            </span>
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Product Selection Modal -->
        <div x-show="showProductModal" x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-white rounded-lg shadow-xl max-w-4xl w-full mx-4 max-h-[90vh] overflow-y-auto">
                <div class="flex items-center justify-between p-6 border-b border-gray-200">
                    <h2 class="text-xl font-bold text-gray-900">Select Product</h2>
                    <button @click="showProductModal = false" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>

                <div class="p-6">
                    <!-- Search Bar -->
                    <input type="text" x-model="productSearch" @input="filterProducts()"
                        placeholder="Search products..."
                        class="w-full mb-4 px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">

                    <!-- Products Table -->
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Product
                                        Name</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">HSN
                                        Code</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Stock
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">GST %
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Action
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach ($products as $product)
                                    <tr class="product-row">
                                        <td class="px-6 py-4 text-sm text-gray-900">{{ $product->name }}</td>
                                        <td class="px-6 py-4 text-sm text-gray-900">{{ $product->hsn_code }}</td>
                                        <td class="px-6 py-4 text-sm text-gray-900">
                                            {{ $product->stock->sum('quantity') - $product->stock->sum('sold') }}
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-900">{{ $product->gst_percentage }}%
                                        </td>
                                        <td class="px-6 py-4">
                                            <button type="button"
                                                @click="selectProduct('{{ $product->id }}', '{{ $product->name }}', {{ $product->gst_percentage }}, {{ $product->is_igst }})"
                                                class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm rounded-lg transition-colors">
                                                Select
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Service Selection Modal -->
        <div x-show="showServiceModal" x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-white rounded-lg shadow-xl max-w-4xl w-full mx-4 max-h-[90vh] overflow-y-auto">
                <div class="flex items-center justify-between p-6 border-b border-gray-200">
                    <h2 class="text-xl font-bold text-gray-900">Select Service</h2>
                    <button @click="showServiceModal = false" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>

                <div class="p-6">
                    <!-- Search Bar -->
                    <input type="text" x-model="serviceSearch" @input="filterServices()"
                        placeholder="Search services..."
                        class="w-full mb-4 px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">

                    <!-- Services Table -->
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Service
                                        Name</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">GST %
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Action
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach ($services as $service)
                                    <tr class="service-row">
                                        <td class="px-6 py-4 text-sm text-gray-900">{{ $service->name }}</td>
                                        <td class="px-6 py-4 text-sm text-gray-900">{{ $service->gst_percentage }}%
                                        </td>
                                        <td class="px-6 py-4">
                                            <button type="button"
                                                @click="selectService('{{ $service->id }}', '{{ $service->name }}', {{ $service->gst_percentage }})"
                                                class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm rounded-lg transition-colors">
                                                Select
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function invoiceEditManager() {
            return {
                activeTab: 'basic',
                invoiceNo: '{{ old('invoice_no', $invoice->invoice_no) }}',
                orderNo: '{{ old('order_no', $invoice->order_no) }}',
                selectedCustomer: '{{ old('customer', $invoice->customer_id) }}',
                selectedContactPerson: '{{ old('contact_person', $invoice->contactperson_id) }}',
                contactPersons: [],
                invoiceDate: '{{ old('invoice_date', $invoice->invoice_date) }}',
                orderDate: '{{ old('order_date', $invoice->order_date) }}',
                products: [],
                services: [],
                isSubmitting: false,
                showProductModal: false,
                showServiceModal: false,
                productSearch: '',
                serviceSearch: '',
                customersData: {},
                currentProductIndex: null,
                currentServiceIndex: null,
                summary: {
                    product_subtotal: 0,
                    product_total_cgst: 0,
                    product_total_sgst: 0,
                    product_total_igst: 0,
                    product_total: 0,
                    service_subtotal: 0,
                    service_total_cgst: 0,
                    service_total_sgst: 0,
                    service_total: 0,
                    grand_sub_total: 0,
                    grand_gst_total: 0,
                    grand_total: 0
                },

                init() {
                    console.log('Initializing invoice edit manager...');
                    console.log('Current User:', 'Kannan-24');
                    console.log('Current DateTime (UTC):', '2025-10-19 13:46:05');

                    // Clear arrays to prevent duplication
                    this.products = [];
                    this.services = [];

                    this.setupCustomerData();
                    this.loadExistingProducts();
                    this.loadExistingServices();
                    this.updateContactPersons();
                    console.log('Invoice edit manager initialized successfully');
                },

                setupCustomerData() {
                    try {
                        this.customersData = @json($customers->mapWithKeys(fn($customer) => [$customer->id => $customer->contactPersons]));
                        console.log('Customer data loaded:', Object.keys(this.customersData).length, 'customers');
                    } catch (error) {
                        console.error('Error loading customer data:', error);
                        this.customersData = {};
                    }
                },

                loadExistingProducts() {
                    try {
                        const existingProducts = @json($invoice->items->where('type', 'product')->values());

                        console.log('Raw existing products data:', existingProducts);

                        // Check if products already loaded to prevent duplication
                        if (this.products.length > 0) {
                            console.log('Products already loaded, skipping...');
                            return;
                        }

                        existingProducts.forEach((item, index) => {
                            console.log(`Processing product item ${index}:`, item);

                            const product = {
                                id: item.product_id,
                                name: item.product ? item.product.name : 'N/A',
                                quantity: parseFloat(item.quantity) || 0,
                                unit_price: parseFloat(item.unit_price) || 0,
                                gst_percentage: item.product ? parseFloat(item.product.gst_percentage) || 0 : 0,
                                is_igst: item.product ? parseInt(item.product.is_igst) || 0 : 0,
                                cgst_rate: item.product ? (item.product.is_igst ? 0 : (parseFloat(item.product
                                    .gst_percentage) || 0) / 2) : 0,
                                sgst_rate: item.product ? (item.product.is_igst ? 0 : (parseFloat(item.product
                                    .gst_percentage) || 0) / 2) : 0,
                                igst_rate: item.product ? (item.product.is_igst ? (parseFloat(item.product
                                    .gst_percentage) || 0) : 0) : 0,
                                cgst_value: parseFloat(item.cgst) || 0,
                                sgst_value: parseFloat(item.sgst) || 0,
                                igst_value: parseFloat(item.igst) || 0,
                                total: parseFloat(item.total) || 0
                            };

                            console.log('Created product object:', product);
                            this.products.push(product);
                        });

                        console.log('Loaded', this.products.length, 'existing products');
                        console.log('Final products array:', this.products);
                        // Calculate summary after loading
                        setTimeout(() => {
                            this.calculateSummary();
                        }, 50);
                    } catch (error) {
                        console.error('Error loading existing products:', error);
                    }
                },

                loadExistingServices() {
                    try {
                        const existingServices = @json($invoice->items->where('type', 'service')->values());

                        console.log('Raw existing services data:', existingServices);

                        // Check if services already loaded to prevent duplication
                        if (this.services.length > 0) {
                            console.log('Services already loaded, skipping...');
                            return;
                        }

                        existingServices.forEach((item, index) => {
                            console.log(`Processing service item ${index}:`, item);

                            const service = {
                                id: item.service_id,
                                name: item.service ? item.service.name : 'N/A',
                                quantity: parseFloat(item.quantity) || 0,
                                unit_price: parseFloat(item.unit_price) || 0,
                                gst_percentage: item.service ? parseFloat(item.service.gst_percentage) || 0 : 0,
                                gst_total: (parseFloat(item.cgst) || 0) + (parseFloat(item.sgst) || 0),
                                total: parseFloat(item.total) || 0
                            };

                            console.log('Created service object:', service);
                            this.services.push(service);
                        });

                        console.log('Loaded', this.services.length, 'existing services');
                        console.log('Final services array:', this.services);
                        // Calculate summary after loading
                        setTimeout(() => {
                            this.calculateSummary();
                        }, 50);
                    } catch (error) {
                        console.error('Error loading existing services:', error);
                    }
                },

                updateContactPersons() {
                    try {
                        const currentContactPerson = this.selectedContactPerson;
                        this.contactPersons = this.customersData[this.selectedCustomer] || [];

                        // Preserve selected contact person if it exists for the new customer
                        if (currentContactPerson && this.contactPersons.find(cp => cp.id == currentContactPerson)) {
                            this.selectedContactPerson = currentContactPerson;
                        } else {
                            // Reset contact person if not available for new customer
                            this.selectedContactPerson = '';
                        }

                        console.log('Contact persons updated:', this.contactPersons.length, 'contacts available');
                        console.log('Selected contact person:', this.selectedContactPerson);
                    } catch (error) {
                        console.error('Error updating contact persons:', error);
                        this.contactPersons = [];
                        this.selectedContactPerson = '';
                    }
                },

                nextStep() {
                    if (this.activeTab === 'basic') {
                        if (!this.validateBasicInfo()) {
                            return;
                        }
                        this.activeTab = 'products';
                    } else if (this.activeTab === 'products') {
                        if (this.products.length === 0) {
                            if (!confirm('No products added. Continue to services?')) {
                                return;
                            }
                        }
                        this.activeTab = 'services';
                    } else if (this.activeTab === 'services') {
                        this.calculateSummary();
                        this.activeTab = 'summary';
                    }
                    console.log('Navigated to tab:', this.activeTab);
                },

                previousStep() {
                    if (this.activeTab === 'summary') {
                        this.activeTab = 'services';
                    } else if (this.activeTab === 'services') {
                        this.activeTab = 'products';
                    } else if (this.activeTab === 'products') {
                        this.activeTab = 'basic';
                    }
                    console.log('Navigated back to tab:', this.activeTab);
                },

                validateBasicInfo() {
                    if (!this.selectedCustomer || !this.selectedContactPerson || !this.invoiceDate || !this.orderDate) {
                        alert('Please fill in all required fields before proceeding.');
                        return false;
                    }
                    if (!this.invoiceNo || !this.orderNo) {
                        alert('Invoice number and order number are required.');
                        return false;
                    }
                    return true;
                },

                changeProduct(index) {
                    this.currentProductIndex = index;
                    this.showProductModal = true;
                    console.log('Changing product at index:', index);
                },

                selectProduct(id, name, gstPercentage, isIgst) {
                    try {
                        if (this.currentProductIndex !== null) {
                            // Changing existing product
                            const product = this.products[this.currentProductIndex];
                            product.id = id;
                            product.name = name;
                            product.gst_percentage = parseFloat(gstPercentage) || 0;
                            product.is_igst = parseInt(isIgst) || 0;
                            product.cgst_rate = product.is_igst ? 0 : (product.gst_percentage / 2);
                            product.sgst_rate = product.is_igst ? 0 : (product.gst_percentage / 2);
                            product.igst_rate = product.is_igst ? product.gst_percentage : 0;

                            this.calculateProductTotal(this.currentProductIndex);
                            console.log('Product changed:', name);
                            this.currentProductIndex = null;
                        } else {
                            // Adding new product
                            const gstPercent = parseFloat(gstPercentage) || 0;
                            const isIgstProduct = parseInt(isIgst) || 0;

                            const product = {
                                id: id,
                                name: name,
                                quantity: 1,
                                unit_price: 0,
                                gst_percentage: gstPercent,
                                is_igst: isIgstProduct,
                                cgst_rate: isIgstProduct ? 0 : (gstPercent / 2),
                                sgst_rate: isIgstProduct ? 0 : (gstPercent / 2),
                                igst_rate: isIgstProduct ? gstPercent : 0,
                                cgst_value: 0,
                                sgst_value: 0,
                                igst_value: 0,
                                total: 0
                            };

                            this.products.push(product);
                            this.calculateProductTotal(this.products.length - 1);
                            console.log('Product added:', name);
                        }

                        this.showProductModal = false;
                        this.productSearch = '';
                    } catch (error) {
                        console.error('Error selecting product:', error);
                        alert('Error adding product. Please try again.');
                    }
                },

                changeService(index) {
                    this.currentServiceIndex = index;
                    this.showServiceModal = true;
                    console.log('Changing service at index:', index);
                },

                selectService(id, name, gstPercentage) {
                    try {
                        if (this.currentServiceIndex !== null) {
                            // Changing existing service
                            const service = this.services[this.currentServiceIndex];
                            service.id = id;
                            service.name = name;
                            service.gst_percentage = parseFloat(gstPercentage) || 0;

                            this.calculateServiceTotal(this.currentServiceIndex);
                            console.log('Service changed:', name);
                            this.currentServiceIndex = null;
                        } else {
                            // Adding new service
                            const service = {
                                id: id,
                                name: name,
                                quantity: 1,
                                unit_price: 0,
                                gst_percentage: parseFloat(gstPercentage) || 0,
                                gst_total: 0,
                                total: 0
                            };

                            this.services.push(service);
                            this.calculateServiceTotal(this.services.length - 1);
                            console.log('Service added:', name);
                        }

                        this.showServiceModal = false;
                        this.serviceSearch = '';
                    } catch (error) {
                        console.error('Error selecting service:', error);
                        alert('Error adding service. Please try again.');
                    }
                },

                removeProduct(index) {
                    if (confirm('Are you sure you want to remove this product?')) {
                        const removedProduct = this.products[index];
                        this.products.splice(index, 1);
                        this.calculateSummary();
                        console.log('Product removed:', removedProduct.name);
                    }
                },

                removeService(index) {
                    if (confirm('Are you sure you want to remove this service?')) {
                        const removedService = this.services[index];
                        this.services.splice(index, 1);
                        this.calculateSummary();
                        console.log('Service removed:', removedService.name);
                    }
                },

                calculateProductTotal(index) {
                    try {
                        const product = this.products[index];
                        if (!product) return;

                        const quantity = parseFloat(product.quantity) || 0;
                        const unitPrice = parseFloat(product.unit_price) || 0;
                        const subtotal = quantity * unitPrice;

                        if (product.is_igst) {
                            // IGST product - only IGST applies
                            product.cgst_value = 0;
                            product.sgst_value = 0;
                            product.igst_value = parseFloat(((subtotal * product.igst_rate) / 100).toFixed(2));
                        } else {
                            // CGST/SGST product - CGST and SGST apply, no IGST
                            product.cgst_value = parseFloat(((subtotal * product.cgst_rate) / 100).toFixed(2));
                            product.sgst_value = parseFloat(((subtotal * product.sgst_rate) / 100).toFixed(2));
                            product.igst_value = 0;
                        }

                        product.total = parseFloat((subtotal + product.cgst_value + product.sgst_value + product.igst_value)
                            .toFixed(2));

                        // Immediate summary calculation for better responsiveness
                        this.calculateSummary();
                    } catch (error) {
                        console.error('Error calculating product total:', error);
                    }
                },

                calculateServiceTotal(index) {
                    try {
                        const service = this.services[index];
                        if (!service) return;

                        const quantity = parseFloat(service.quantity) || 0;
                        const unitPrice = parseFloat(service.unit_price) || 0;
                        const subtotal = quantity * unitPrice;

                        service.gst_total = parseFloat(((subtotal * service.gst_percentage) / 100).toFixed(2));
                        service.total = parseFloat((subtotal + service.gst_total).toFixed(2));

                        // Immediate summary calculation for better responsiveness
                        this.calculateSummary();
                    } catch (error) {
                        console.error('Error calculating service total:', error);
                    }
                },

                calculateSummary() {
                    try {
                        // Reset summary
                        this.summary = {
                            product_subtotal: 0,
                            product_total_cgst: 0,
                            product_total_sgst: 0,
                            product_total_igst: 0,
                            product_total: 0,
                            service_subtotal: 0,
                            service_total_cgst: 0,
                            service_total_sgst: 0,
                            service_total: 0,
                            grand_sub_total: 0,
                            grand_gst_total: 0,
                            grand_total: 0
                        };

                        // Calculate product summary
                        this.products.forEach(product => {
                            const quantity = parseFloat(product.quantity) || 0;
                            const unitPrice = parseFloat(product.unit_price) || 0;
                            const subtotal = quantity * unitPrice;

                            this.summary.product_subtotal += subtotal;
                            this.summary.product_total_cgst += parseFloat(product.cgst_value) || 0;
                            this.summary.product_total_sgst += parseFloat(product.sgst_value) || 0;
                            this.summary.product_total_igst += parseFloat(product.igst_value) || 0;
                            this.summary.product_total += parseFloat(product.total) || 0;
                        });

                        // Calculate service summary
                        this.services.forEach(service => {
                            const quantity = parseFloat(service.quantity) || 0;
                            const unitPrice = parseFloat(service.unit_price) || 0;
                            const subtotal = quantity * unitPrice;

                            this.summary.service_subtotal += subtotal;
                            // For services, split GST equally between CGST and SGST
                            this.summary.service_total_cgst += (parseFloat(service.gst_total) || 0) / 2;
                            this.summary.service_total_sgst += (parseFloat(service.gst_total) || 0) / 2;
                            this.summary.service_total += parseFloat(service.total) || 0;
                        });

                        // Calculate grand totals
                        this.summary.grand_sub_total = this.summary.product_subtotal + this.summary.service_subtotal;
                        this.summary.grand_gst_total = this.summary.product_total_cgst + this.summary.product_total_sgst +
                            this.summary.product_total_igst + this.summary.service_total_cgst + this.summary
                            .service_total_sgst;
                        this.summary.grand_total = this.summary.product_total + this.summary.service_total;

                        // Round all values to 2 decimal places
                        Object.keys(this.summary).forEach(key => {
                            this.summary[key] = parseFloat(this.summary[key].toFixed(2));
                        });

                        console.log('Summary calculated - Grand Total:', this.summary.grand_total);
                    } catch (error) {
                        console.error('Error calculating summary:', error);
                    }
                },

                filterProducts() {
                    try {
                        const searchTerm = this.productSearch.toLowerCase();
                        document.querySelectorAll('.product-row').forEach(row => {
                            const productName = row.cells[0].textContent.toLowerCase();
                            row.style.display = productName.includes(searchTerm) ? '' : 'none';
                        });
                    } catch (error) {
                        console.error('Error filtering products:', error);
                    }
                },

                filterServices() {
                    try {
                        const searchTerm = this.serviceSearch.toLowerCase();
                        document.querySelectorAll('.service-row').forEach(row => {
                            const serviceName = row.cells[0].textContent.toLowerCase();
                            row.style.display = serviceName.includes(searchTerm) ? '' : 'none';
                        });
                    } catch (error) {
                        console.error('Error filtering services:', error);
                    }
                },

                submitForm() {
                    if (this.isSubmitting) {
                        console.log('Form submission already in progress...');
                        return;
                    }

                    try {
                        // Final validation
                        if (!this.validateBasicInfo()) {
                            this.activeTab = 'basic';
                            return;
                        }

                        if (this.products.length === 0 && this.services.length === 0) {
                            this.activeTab = 'products';
                            alert('Please add at least one product or service.');
                            return;
                        }

                        // Set submitting state
                        this.isSubmitting = true;

                        // Final calculation
                        this.calculateSummary();

                        console.log('Submitting invoice update...');
                        console.log('Products:', this.products.length);
                        console.log('Services:', this.services.length);
                        console.log('Grand Total:', this.summary.grand_total);
                        console.log('Updated by:', 'Kannan-24');
                        console.log('Updated at (UTC):', '2025-10-19 13:46:05');

                        // Submit the form
                        document.getElementById('invoiceForm').submit();
                    } catch (error) {
                        console.error('Error submitting form:', error);
                        this.isSubmitting = false;
                        alert('An error occurred while submitting the form. Please try again.');
                    }
                }
            }
        }

        // Initialize Alpine.js
        document.addEventListener('alpine:init', () => {
            console.log('Alpine.js initialized for invoice edit page');
            console.log('System ready - User: Kannan-24');
        });

        // Page load event
        document.addEventListener('DOMContentLoaded', function() {
            console.log('Invoice edit page loaded successfully');
            console.log('Timestamp (UTC): 2025-10-19 13:46:05');
        });
    </script>

</x-app-layout>
