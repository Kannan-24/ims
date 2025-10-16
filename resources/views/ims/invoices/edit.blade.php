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
                    <p class="text-sm text-gray-600 mt-1">Modify invoice details and items</p>
                </div>
                <div class="flex items-center space-x-3">
                    <!-- Help Button -->
                    <button @click="showHelpModal = true"
                        class="inline-flex items-center px-3 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium rounded-lg transition-colors">
                        <i class="fas fa-question-circle w-4 h-4 mr-2"></i>
                        Help
                    </button>
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
            <div class="mx-6 mt-4 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="fas fa-exclamation-circle text-red-400"></i>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-red-800">
                            There were {{ $errors->count() }} error(s) with your submission:
                        </h3>
                        <div class="mt-2 text-sm text-red-700">
                            <ul class="list-disc pl-5 space-y-1">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
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
                                    <div class="mt-1 text-sm text-red-600" x-show="errors.invoice_no"
                                        x-text="errors.invoice_no"></div>
                                </div>

                                <!-- Order Number -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Order Number <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" name="order_no" required
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                        value="{{ old('order_no', $invoice->order_no) }}" x-model="orderNo">
                                    <div class="mt-1 text-sm text-red-600" x-show="errors.order_no"
                                        x-text="errors.order_no"></div>
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
                                    <div class="mt-1 text-sm text-red-600" x-show="errors.customer"
                                        x-text="errors.customer"></div>
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
                                        @php
                                            $selectedCustomer = $customers->firstWhere(
                                                'id',
                                                old('customer', $invoice->customer_id),
                                            );
                                        @endphp
                                        @if ($selectedCustomer)
                                            @foreach ($selectedCustomer->contactPersons ?? [] as $contactPerson)
                                                <option value="{{ $contactPerson->id }}"
                                                    {{ old('contact_person', $invoice->contactperson_id) == $contactPerson->id ? 'selected' : '' }}>
                                                    {{ $contactPerson->name }}
                                                </option>
                                            @endforeach
                                        @endif
                                        <template x-for="contact in contactPersons" :key="contact.id">
                                            <option :value="contact.id" x-text="contact.name"></option>
                                        </template>
                                    </select>
                                    <div class="mt-1 text-sm text-red-600" x-show="errors.contact_person"
                                        x-text="errors.contact_person"></div>
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
                                    <div class="mt-1 text-sm text-red-600" x-show="errors.invoice_date"
                                        x-text="errors.invoice_date"></div>
                                </div>

                                <!-- Order Date -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Order Date <span class="text-red-500">*</span>
                                    </label>
                                    <input type="date" name="order_date" required
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                        value="{{ old('order_date', $invoice->order_date) }}" x-model="orderDate">
                                    <div class="mt-1 text-sm text-red-600" x-show="errors.order_date"
                                        x-text="errors.order_date"></div>
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
                                <p class="text-gray-500 mb-4">Add products to generate invoice</p>
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
                                                Discount Amt</th>
                                            <th
                                                class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                                Taxable Amt</th>
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
                                                    <div class="text-sm font-medium text-gray-900"
                                                        x-text="product.name"></div>
                                                    <button type="button" @click="changeProduct(index)"
                                                        class="text-xs text-blue-600 hover:text-blue-800 mt-1">Change
                                                        Product</button>
                                                    <input type="hidden" :name="`products[${index}][product_id]`"
                                                        :value="product.id">
                                                    <input type="hidden" :name="`products[${index}][gst_percentage]`"
                                                        :value="product.gst_percentage">
                                                </td>
                                                <td class="px-4 py-4">
                                                    <input type="number" :name="`products[${index}][quantity]`"
                                                        x-model.number="product.quantity"
                                                        @input="calculateProductTotal(index)"
                                                        class="w-20 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                                        min="1">
                                                </td>
                                                <td class="px-4 py-4">
                                                    <input type="number" :name="`products[${index}][unit_price]`"
                                                        x-model.number="product.unit_price"
                                                        @input="calculateProductTotal(index)"
                                                        class="w-24 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                                        min="0" step="0.01">
                                                </td>
                                                <td class="px-4 py-4">
                                                    <input type="number"
                                                        :name="`products[${index}][discount_amount]`"
                                                        x-model.number="product.discount_amount"
                                                        @input="calculateProductTotal(index)"
                                                        class="w-24 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                                        min="0" step="0.01">
                                                </td>
                                                <td class="px-4 py-4">
                                                    <div class="text-sm font-medium text-gray-900">₹<span
                                                            x-text="product.taxable_amount"></span></div>
                                                    <input type="hidden" :name="`products[${index}][taxable_amount]`"
                                                        :value="product.taxable_amount">
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
                                                Discount Amt</th>
                                            <th
                                                class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                                Taxable Amt</th>
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
                                                    <div class="text-sm font-medium text-gray-900"
                                                        x-text="service.name"></div>
                                                    <button type="button" @click="changeService(index)"
                                                        class="text-xs text-blue-600 hover:text-blue-800 mt-1">Change
                                                        Service</button>
                                                    <input type="hidden" :name="`services[${index}][service_id]`"
                                                        :value="service.id">
                                                </td>
                                                <td class="px-4 py-4">
                                                    <input type="number" :name="`services[${index}][quantity]`"
                                                        x-model.number="service.quantity"
                                                        @input="calculateServiceTotal(index)"
                                                        class="w-20 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                                        min="1">
                                                </td>
                                                <td class="px-4 py-4">
                                                    <input type="number" :name="`services[${index}][unit_price]`"
                                                        x-model.number="service.unit_price"
                                                        @input="calculateServiceTotal(index)"
                                                        class="w-24 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                                        min="0" step="0.01">
                                                </td>
                                                <td class="px-4 py-4">
                                                    <input type="number"
                                                        :name="`services[${index}][discount_amount]`"
                                                        x-model.number="service.discount_amount"
                                                        @input="calculateServiceTotal(index)"
                                                        class="w-24 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                                        min="0" step="0.01">
                                                </td>
                                                <td class="px-4 py-4">
                                                    <div class="text-sm font-medium text-gray-900">₹<span
                                                            x-text="service.taxable_amount"></span></div>
                                                    <input type="hidden" :name="`services[${index}][taxable_amount]`"
                                                        :value="service.taxable_amount">
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
                                        <div class="flex justify-between" x-show="summary.product_discount > 0">
                                            <span class="text-red-600">Product Discount:</span>
                                            <span class="font-medium text-red-600">-₹<span
                                                    x-text="summary.product_discount"></span></span>
                                            <input type="hidden" name="product_discount"
                                                :value="summary.product_discount">
                                        </div>
                                        <div class="flex justify-between">
                                            <span class="text-gray-700">Product Taxable Amount:</span>
                                            <span class="font-medium">₹<span
                                                    x-text="summary.product_taxable_amount"></span></span>
                                            <input type="hidden" name="product_taxable_amount"
                                                :value="summary.product_taxable_amount">
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
                                        <div class="flex justify-between" x-show="summary.service_discount > 0">
                                            <span class="text-red-600">Service Discount:</span>
                                            <span class="font-medium text-red-600">-₹<span
                                                    x-text="summary.service_discount"></span></span>
                                            <input type="hidden" name="service_discount"
                                                :value="summary.service_discount">
                                        </div>
                                        <div class="flex justify-between">
                                            <span class="text-gray-700">Service Taxable Amount:</span>
                                            <span class="font-medium">₹<span
                                                    x-text="summary.service_taxable_amount"></span></span>
                                            <input type="hidden" name="service_taxable_amount"
                                                :value="summary.service_taxable_amount">
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
                                        <div class="flex justify-between text-lg" x-show="summary.grand_discount > 0">
                                            <span class="text-red-600">Total Discount:</span>
                                            <span class="font-semibold text-red-600">-₹<span
                                                    x-text="summary.grand_discount"></span></span>
                                            <input type="hidden" name="grand_discount"
                                                :value="summary.grand_discount">
                                        </div>
                                        <div class="flex justify-between text-lg">
                                            <span class="text-gray-700">Taxable Amount:</span>
                                            <span class="font-semibold">₹<span
                                                    x-text="summary.grand_taxable_amount"></span></span>
                                            <input type="hidden" name="grand_taxable_amount"
                                                :value="summary.grand_taxable_amount">
                                        </div>
                                        <div class="flex justify-between text-lg">
                                            <span class="text-gray-700">Grand GST Total:</span>
                                            <span class="font-semibold">₹<span
                                                    x-text="summary.grand_gst_total"></span></span>
                                            <input type="hidden" name="grand_gst_total"
                                                :value="summary.grand_gst_total">
                                        </div>
                                        <div class="flex justify-between text-lg">
                                            <span class="text-gray-700">Subtotal + GST:</span>
                                            <span class="font-semibold">₹<span
                                                    x-text="summary.grand_total"></span></span>
                                            <input type="hidden" name="grand_total" :value="summary.grand_total">
                                        </div>
                                        <div class="flex justify-between text-lg">
                                            <span class="text-gray-700">Courier Charges:</span>
                                            <div class="flex items-center">
                                                <span class="mr-2">₹</span>
                                                <input type="number" name="courier_charges"
                                                    x-model.number="courierCharges" @input="calculateFinalTotal()"
                                                    @change="calculateFinalTotal()"
                                                    class="w-32 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                                    min="0" step="0.01">
                                            </div>
                                        </div>
                                        <div
                                            class="flex justify-between border-t border-yellow-300 pt-4 text-2xl font-bold">
                                            <span class="text-gray-900">Final Total:</span>
                                            <span class="text-yellow-600">₹<span x-text="finalTotal"></span></span>
                                            <input type="hidden" name="final_total" :value="finalTotal">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="flex items-center justify-between mt-8 pt-6 border-t border-gray-200">
                    <div class="text-sm text-gray-500">
                        <kbd class="px-2 py-1 bg-gray-100 rounded text-xs">Ctrl+S</kbd> to save •
                        <kbd class="px-2 py-1 bg-gray-100 rounded text-xs">Esc</kbd> to cancel
                    </div>
                    <div class="flex items-center space-x-3">
                        <!-- Previous Button -->
                        <button type="button" @click="previousStep()" x-show="activeTab !== 'basic'"
                            class="px-6 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 font-medium transition-colors inline-flex items-center">
                            <i class="fas fa-arrow-left mr-2"></i>
                            Previous
                        </button>

                        <!-- Cancel Button -->
                        <a href="{{ route('invoices.index') }}"
                            class="px-6 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 font-medium transition-colors">
                            Cancel
                        </a>

                        <!-- Next Button -->
                        <button type="button" @click="nextStep()" x-show="activeTab !== 'summary'"
                            class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium transition-colors inline-flex items-center">
                            Next
                            <i class="fas fa-arrow-right ml-2"></i>
                        </button>

                        <!-- Update Button -->
                        <div x-show="activeTab === 'summary'">
                            <button type="submit" :disabled="isSubmitting"
                                class="px-6 py-3 bg-green-600 hover:bg-green-700 disabled:bg-green-400 text-white rounded-lg font-medium transition-colors inline-flex items-center">
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

        <!-- Help Modal -->
        <div x-show="showHelpModal" x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-white rounded-lg shadow-xl max-w-4xl w-full mx-4 max-h-[90vh] overflow-y-auto">
                <div class="flex items-center justify-between p-6 border-b border-gray-200">
                    <h2 class="text-xl font-bold text-gray-900">Edit Invoice Help</h2>
                    <button @click="showHelpModal = false" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>

                <div class="p-6 space-y-6">
                    <!-- Step Guide -->
                    <div class="border border-gray-200 rounded-lg p-4">
                        <h3 class="text-lg font-semibold text-gray-900 mb-3">
                            <i class="fas fa-list-ol text-blue-600 mr-2"></i>Editing Steps
                        </h3>
                        <div class="space-y-4">
                            <div class="flex items-start space-x-3">
                                <div
                                    class="w-6 h-6 bg-blue-600 text-white rounded-full flex items-center justify-center text-sm font-bold">
                                    1</div>
                                <div>
                                    <h4 class="font-medium text-gray-900">Review Invoice Information</h4>
                                    <p class="text-sm text-gray-600">Check and update invoice details, customer, and
                                        dates.</p>
                                </div>
                            </div>
                            <div class="flex items-start space-x-3">
                                <div
                                    class="w-6 h-6 bg-blue-600 text-white rounded-full flex items-center justify-center text-sm font-bold">
                                    2</div>
                                <div>
                                    <h4 class="font-medium text-gray-900">Modify Products</h4>
                                    <p class="text-sm text-gray-600">Add, remove, or update product quantities and
                                        prices.</p>
                                </div>
                            </div>
                            <div class="flex items-start space-x-3">
                                <div
                                    class="w-6 h-6 bg-blue-600 text-white rounded-full flex items-center justify-center text-sm font-bold">
                                    3</div>
                                <div>
                                    <h4 class="font-medium text-gray-900">Update Services</h4>
                                    <p class="text-sm text-gray-600">Modify services as needed for the invoice.</p>
                                </div>
                            </div>
                            <div class="flex items-start space-x-3">
                                <div
                                    class="w-6 h-6 bg-blue-600 text-white rounded-full flex items-center justify-center text-sm font-bold">
                                    4</div>
                                <div>
                                    <h4 class="font-medium text-gray-900">Review & Save</h4>
                                    <p class="text-sm text-gray-600">Check the summary and save changes.</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Additional Options -->
                    <div class="border border-gray-200 rounded-lg p-4">
                        <h3 class="text-lg font-semibold text-gray-900 mb-3">
                            <i class="fas fa-cog text-green-600 mr-2"></i>Additional Options
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm text-gray-700">
                            <div>• <strong>Change Products:</strong> Click "Change Product" to replace items</div>
                            <div>• <strong>Auto-calculations:</strong> Totals update automatically</div>
                            <div>• <strong>Stock Check:</strong> Available stock shown for products</div>
                            <div>• <strong>Save Progress:</strong> Use Ctrl+S to save anytime</div>
                        </div>
                    </div>
                </div>

                <div class="flex items-center justify-end px-6 py-4 bg-gray-50 border-t border-gray-200">
                    <button @click="showHelpModal = false"
                        class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                        Got it!
                    </button>
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
                courierCharges: 0,
                finalTotal: 0,
                errors: {},
                isSubmitting: false,
                showHelpModal: false,
                showProductModal: false,
                showServiceModal: false,
                productSearch: '',
                serviceSearch: '',
                customersData: {},
                currentEditingIndex: null,
                currentEditingType: null,
                summary: {
                    product_subtotal: 0,
                    product_discount: 0,
                    product_taxable_amount: 0,
                    product_total_cgst: 0,
                    product_total_sgst: 0,
                    product_total_igst: 0,
                    product_total: 0,
                    service_subtotal: 0,
                    service_discount: 0,
                    service_taxable_amount: 0,
                    service_total_cgst: 0,
                    service_total_sgst: 0,
                    service_total: 0,
                    grand_sub_total: 0,
                    grand_discount: 0,
                    grand_taxable_amount: 0,
                    grand_gst_total: 0,
                    grand_total: 0
                },

                init() {
                    console.log('Initializing invoice edit manager...');
                    this.bindKeyboardEvents();
                    this.setupCustomerData();
                    this.loadExistingData();
                    console.log('Invoice edit manager initialized');
                },

                setupCustomerData() {
                    try {
                        this.customersData = @json($customers->mapWithKeys(fn($customer) => [$customer->id => $customer->contactPersons]));
                        console.log('Customer data loaded:', this.customersData);
                    } catch (error) {
                        console.error('Error loading customer data:', error);
                        this.customersData = {};
                    }
                },

                loadExistingData() {
                    try {
                        // Clear arrays first to prevent duplication
                        this.products = [];
                        this.services = [];
                        console.log('Loading existing invoice data...');

                        // Load existing products from the invoice
                        @foreach ($invoice->items->where('type', 'product') as $item)
                            @if ($item->product)
                                const isIgst{{ $loop->index }} = {{ $item->product->is_igst ? 'true' : 'false' }};
                                const gstPercentage{{ $loop->index }} = {{ $item->product->gst_percentage }};

                                this.products.push({
                                    id: {{ $item->product_id }},
                                    name: `{{ addslashes($item->product->name) }}`,
                                    quantity: {{ $item->quantity }},
                                    unit_price: {{ $item->unit_price }},
                                    gst_percentage: gstPercentage{{ $loop->index }},
                                    is_igst: isIgst{{ $loop->index }},
                                    cgst_rate: isIgst{{ $loop->index }} ? 0 : (gstPercentage{{ $loop->index }} /
                                        2),
                                    sgst_rate: isIgst{{ $loop->index }} ? 0 : (gstPercentage{{ $loop->index }} /
                                        2),
                                    igst_rate: isIgst{{ $loop->index }} ? gstPercentage{{ $loop->index }} : 0,
                                    cgst_value: isIgst{{ $loop->index }} ? 0 : parseFloat(
                                        {{ number_format($item->cgst, 2, '.', '') }}),
                                    sgst_value: isIgst{{ $loop->index }} ? 0 : parseFloat(
                                        {{ number_format($item->sgst, 2, '.', '') }}),
                                    igst_value: isIgst{{ $loop->index }} ? parseFloat(
                                        {{ number_format($item->igst, 2, '.', '') }}) : 0,
                                    discount_amount: parseFloat(
                                        {{ number_format($item->discount_amount ?? 0, 2, '.', '') }}),
                                    taxable_amount: parseFloat(
                                        {{ number_format($item->taxable_amount ?? $item->quantity * $item->unit_price, 2, '.', '') }}
                                        ),
                                    total: parseFloat({{ number_format($item->total, 2, '.', '') }})
                                });
                            @endif
                        @endforeach

                        // Load existing services from the invoice
                        @foreach ($invoice->items->where('type', 'service') as $item)
                            @if ($item->service)
                                this.services.push({
                                    id: {{ $item->service_id }},
                                    name: `{{ addslashes($item->service->name) }}`,
                                    quantity: {{ $item->quantity }},
                                    unit_price: {{ $item->unit_price }},
                                    gst_percentage: {{ $item->service->gst_percentage }},
                                    discount_amount: parseFloat(
                                        {{ number_format($item->discount_amount ?? 0, 2, '.', '') }}),
                                    taxable_amount: parseFloat(
                                        {{ number_format($item->taxable_amount ?? $item->quantity * $item->unit_price, 2, '.', '') }}
                                        ),
                                    gst_total: parseFloat({{ number_format($item->gst, 2, '.', '') }}),
                                    total: parseFloat({{ number_format($item->total, 2, '.', '') }})
                                });
                            @endif
                        @endforeach

                        console.log('Products loaded:', this.products.length);
                        console.log('Services loaded:', this.services.length);

                        // Load courier charges and final total
                        this.courierCharges = parseFloat({{ number_format($invoice->courier_charges ?? 0, 2, '.', '') }});
                        this.finalTotal = parseFloat(
                            {{ number_format($invoice->grand_total ?? $invoice->total, 2, '.', '') }});

                        console.log('Loaded courier charges:', this.courierCharges);
                        console.log('Loaded final total:', this.finalTotal);

                        // Calculate initial summary after loading data
                        setTimeout(() => {
                            this.calculateSummary();
                            console.log('Initial summary calculated after data load');
                        }, 100);
                    } catch (error) {
                        console.error('Error loading existing data:', error);
                    }
                },

                bindKeyboardEvents() {
                    document.addEventListener('keydown', (e) => {
                        if (e.ctrlKey && e.key === 's') {
                            e.preventDefault();
                            this.submitForm();
                        }

                        if (e.key.toLowerCase() === 'h' && !e.ctrlKey && !e.altKey && !e.metaKey) {
                            const activeElement = document.activeElement;
                            if (activeElement.tagName !== 'INPUT' && activeElement.tagName !== 'TEXTAREA') {
                                e.preventDefault();
                                this.showHelpModal = true;
                            }
                        }

                        if (e.key === 'Escape') {
                            e.preventDefault();
                            if (this.showHelpModal) {
                                this.showHelpModal = false;
                            } else if (this.showProductModal) {
                                this.showProductModal = false;
                            } else if (this.showServiceModal) {
                                this.showServiceModal = false;
                            } else if (confirm('Are you sure you want to cancel? All changes will be lost.')) {
                                window.location.href = '{{ route('invoices.index') }}';
                            }
                        }
                    });
                },

                updateContactPersons() {
                    try {
                        this.contactPersons = this.customersData[this.selectedCustomer] || [];
                        const validContactIds = this.contactPersons.map(c => c.id.toString());
                        if (this.selectedContactPerson && !validContactIds.includes(this.selectedContactPerson
                                .toString())) {
                            this.selectedContactPerson = '';
                        }
                        console.log('Contact persons updated:', this.contactPersons.length);
                    } catch (error) {
                        console.error('Error updating contact persons:', error);
                        this.contactPersons = [];
                    }
                },

                nextStep() {
                    if (this.activeTab === 'basic') {
                        if (!this.selectedCustomer || !this.selectedContactPerson || !this.invoiceDate || !this.orderDate) {
                            alert('Please fill in all required fields before proceeding.');
                            return;
                        }
                        this.activeTab = 'products';
                    } else if (this.activeTab === 'products') {
                        this.activeTab = 'services';
                    } else if (this.activeTab === 'services') {
                        this.calculateSummary();
                        this.activeTab = 'summary';
                    }
                },

                previousStep() {
                    if (this.activeTab === 'summary') {
                        this.activeTab = 'services';
                    } else if (this.activeTab === 'services') {
                        this.activeTab = 'products';
                    } else if (this.activeTab === 'products') {
                        this.activeTab = 'basic';
                    }
                },

                changeProduct(index) {
                    this.currentEditingIndex = index;
                    this.currentEditingType = 'product';
                    this.showProductModal = true;
                },

                changeService(index) {
                    this.currentEditingIndex = index;
                    this.currentEditingType = 'service';
                    this.showServiceModal = true;
                },

                selectProduct(id, name, gstPercentage, isIgst) {
                    try {
                        if (this.currentEditingType === 'product' && this.currentEditingIndex !== null) {
                            // Update existing product
                            const product = this.products[this.currentEditingIndex];
                            product.id = id;
                            product.name = name;
                            product.gst_percentage = gstPercentage;
                            product.is_igst = isIgst;
                            product.cgst_rate = isIgst ? 0 : (gstPercentage / 2);
                            product.sgst_rate = isIgst ? 0 : (gstPercentage / 2);
                            product.igst_rate = isIgst ? gstPercentage : 0;

                            this.calculateProductTotal(this.currentEditingIndex);
                        } else {
                            // Add new product
                            const existingIndex = this.products.findIndex(p => p.id === id);
                            if (existingIndex !== -1) {
                                alert('Product already added. You can modify the quantity in the products tab.');
                                this.showProductModal = false;
                                return;
                            }

                            const product = {
                                id: id,
                                name: name,
                                quantity: 1,
                                unit_price: 0,
                                gst_percentage: gstPercentage,
                                is_igst: isIgst,
                                cgst_rate: isIgst ? 0 : (gstPercentage / 2),
                                sgst_rate: isIgst ? 0 : (gstPercentage / 2),
                                igst_rate: isIgst ? gstPercentage : 0,
                                cgst_value: 0,
                                sgst_value: 0,
                                igst_value: 0,
                                total: 0
                            };

                            this.products.push(product);
                            this.calculateProductTotal(this.products.length - 1);
                        }

                        this.showProductModal = false;
                        this.currentEditingIndex = null;
                        this.currentEditingType = null;
                        console.log('Product selected:', name);
                    } catch (error) {
                        console.error('Error selecting product:', error);
                    }
                },

                selectService(id, name, gstPercentage) {
                    try {
                        if (this.currentEditingType === 'service' && this.currentEditingIndex !== null) {
                            // Update existing service
                            const service = this.services[this.currentEditingIndex];
                            service.id = id;
                            service.name = name;
                            service.gst_percentage = gstPercentage;

                            this.calculateServiceTotal(this.currentEditingIndex);
                        } else {
                            // Add new service
                            const existingIndex = this.services.findIndex(s => s.id === id);
                            if (existingIndex !== -1) {
                                alert('Service already added. You can modify the quantity in the services tab.');
                                this.showServiceModal = false;
                                return;
                            }

                            const service = {
                                id: id,
                                name: name,
                                quantity: 1,
                                unit_price: 0,
                                gst_percentage: gstPercentage,
                                gst_total: 0,
                                total: 0
                            };

                            this.services.push(service);
                            this.calculateServiceTotal(this.services.length - 1);
                        }

                        this.showServiceModal = false;
                        this.currentEditingIndex = null;
                        this.currentEditingType = null;
                        console.log('Service selected:', name);
                    } catch (error) {
                        console.error('Error selecting service:', error);
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
                        const discountAmount = parseFloat(product.discount_amount) || 0;
                        const subtotal = quantity * unitPrice;

                        // Ensure discount doesn't exceed subtotal
                        product.discount_amount = Math.min(discountAmount, subtotal);
                        const validDiscountAmount = parseFloat(product.discount_amount);

                        // Calculate taxable amount after discount
                        const taxableAmount = subtotal - validDiscountAmount;
                        product.taxable_amount = parseFloat(taxableAmount.toFixed(2));

                        // Apply correct GST logic based on is_igst flag on taxable amount
                        if (product.is_igst) {
                            // IGST product - only IGST applies
                            product.cgst_value = 0;
                            product.sgst_value = 0;
                            product.igst_value = parseFloat(((taxableAmount * product.igst_rate) / 100).toFixed(2));
                        } else {
                            // CGST/SGST product - CGST and SGST apply, no IGST
                            product.cgst_value = parseFloat(((taxableAmount * product.cgst_rate) / 100).toFixed(2));
                            product.sgst_value = parseFloat(((taxableAmount * product.sgst_rate) / 100).toFixed(2));
                            product.igst_value = 0;
                        }

                        product.total = parseFloat((taxableAmount + product.cgst_value + product.sgst_value + product
                                .igst_value)
                            .toFixed(2));

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
                        const discountAmount = parseFloat(service.discount_amount) || 0;
                        const subtotal = quantity * unitPrice;

                        // Ensure discount doesn't exceed subtotal
                        service.discount_amount = Math.min(discountAmount, subtotal);
                        const validDiscountAmount = parseFloat(service.discount_amount);

                        // Calculate taxable amount after discount
                        const taxableAmount = subtotal - validDiscountAmount;
                        service.taxable_amount = parseFloat(taxableAmount.toFixed(2));

                        service.gst_total = parseFloat(((taxableAmount * service.gst_percentage) / 100).toFixed(2));
                        service.total = parseFloat((taxableAmount + service.gst_total).toFixed(2));

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
                            product_discount: 0,
                            product_taxable_amount: 0,
                            product_total_cgst: 0,
                            product_total_sgst: 0,
                            product_total_igst: 0,
                            product_total: 0,
                            service_subtotal: 0,
                            service_discount: 0,
                            service_taxable_amount: 0,
                            service_total_cgst: 0,
                            service_total_sgst: 0,
                            service_total: 0,
                            grand_sub_total: 0,
                            grand_discount: 0,
                            grand_taxable_amount: 0,
                            grand_gst_total: 0,
                            grand_total: 0
                        };

                        // Calculate product summary with discount and correct GST logic
                        this.products.forEach(product => {
                            const quantity = parseFloat(product.quantity) || 0;
                            const unitPrice = parseFloat(product.unit_price) || 0;
                            const discountAmount = parseFloat(product.discount_amount) || 0;
                            const subtotal = quantity * unitPrice;
                            const taxableAmount = subtotal - discountAmount;

                            this.summary.product_subtotal += subtotal;
                            this.summary.product_discount += discountAmount;
                            this.summary.product_taxable_amount += taxableAmount;

                            // Only add the applicable GST values based on product type
                            if (product.is_igst) {
                                this.summary.product_total_igst += parseFloat(product.igst_value) || 0;
                            } else {
                                this.summary.product_total_cgst += parseFloat(product.cgst_value) || 0;
                                this.summary.product_total_sgst += parseFloat(product.sgst_value) || 0;
                            }

                            this.summary.product_total += parseFloat(product.total) || 0;
                        });

                        // Calculate service summary (services are typically CGST/SGST)
                        this.services.forEach(service => {
                            const quantity = parseFloat(service.quantity) || 0;
                            const unitPrice = parseFloat(service.unit_price) || 0;
                            const discountAmount = parseFloat(service.discount_amount) || 0;
                            const subtotal = quantity * unitPrice;
                            const taxableAmount = subtotal - discountAmount;

                            this.summary.service_subtotal += subtotal;
                            this.summary.service_discount += discountAmount;
                            this.summary.service_taxable_amount += taxableAmount;
                            // For services, split GST equally between CGST and SGST
                            this.summary.service_total_cgst += (parseFloat(service.gst_total) || 0) / 2;
                            this.summary.service_total_sgst += (parseFloat(service.gst_total) || 0) / 2;
                            this.summary.service_total += parseFloat(service.total) || 0;
                        });

                        // Calculate grand totals
                        this.summary.grand_sub_total = this.summary.product_subtotal + this.summary.service_subtotal;
                        this.summary.grand_discount = this.summary.product_discount + this.summary.service_discount;
                        this.summary.grand_taxable_amount = this.summary.product_taxable_amount + this.summary
                            .service_taxable_amount;
                        this.summary.grand_gst_total = this.summary.product_total_cgst + this.summary.product_total_sgst +
                            this.summary.product_total_igst + this.summary.service_total_cgst + this.summary
                            .service_total_sgst;
                        this.summary.grand_total = this.summary.product_total + this.summary.service_total;

                        // Round all values to 2 decimal places
                        Object.keys(this.summary).forEach(key => {
                            this.summary[key] = parseFloat(this.summary[key].toFixed(2));
                        });

                        // Calculate final total with courier charges
                        this.calculateFinalTotal();
                    } catch (error) {
                        console.error('Error calculating summary:', error);
                    }
                },

                calculateFinalTotal() {
                    try {
                        const courierCharges = parseFloat(this.courierCharges) || 0;
                        this.finalTotal = parseFloat((this.summary.grand_total + courierCharges).toFixed(2));
                        console.log('Final total calculated:', {
                            courierCharges: courierCharges,
                            grandTotal: this.summary.grand_total,
                            finalTotal: this.finalTotal
                        });
                    } catch (error) {
                        console.error('Error calculating final total:', error);
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
                    if (this.isSubmitting) return;

                    try {
                        // Validation
                        if (!this.selectedCustomer || !this.selectedContactPerson || !this.invoiceDate || !this.orderDate) {
                            this.errors.basic = 'Please fill in all required basic information.';
                            this.activeTab = 'basic';
                            alert('Please fill in all required basic information.');
                            return;
                        }

                        if (!this.invoiceNo.trim() || !this.orderNo.trim()) {
                            this.errors.basic = 'Please fill in invoice number and order number.';
                            this.activeTab = 'basic';
                            alert('Please fill in invoice number and order number.');
                            return;
                        }

                        if (this.products.length === 0 && this.services.length === 0) {
                            this.errors.items = 'Please add at least one product or service.';
                            this.activeTab = 'products';
                            alert('Please add at least one product or service.');
                            return;
                        }

                        this.isSubmitting = true;
                        this.errors = {};

                        // Calculate final summary
                        this.calculateSummary();

                        console.log('Submitting invoice update form...');
                        document.getElementById('invoiceForm').submit();
                    } catch (error) {
                        console.error('Error submitting form:', error);
                        this.isSubmitting = false;
                    }
                }
            }
        }

        // Ensure Alpine.js is ready
        document.addEventListener('alpine:init', () => {
            console.log('Alpine.js initialized for invoice edit');
        });

        // Add any additional event listeners or initialization
        document.addEventListener('DOMContentLoaded', function() {
            console.log('Invoice edit page loaded');
        });
    </script>
</x-app-layout>
s
