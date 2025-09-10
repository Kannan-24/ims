@php
    $title = 'Assign Supplier';
@endphp

<x-app-layout :title="$title">
    <div class="bg-white min-h-screen" x-data="assignSupplierManager()" x-init="init()">
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
                            <a href="{{ route('products.index') }}"
                                class="text-sm font-medium text-gray-700 hover:text-blue-600">
                                Products
                            </a>
                        </div>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                            <a href="{{ route('products.show', $product) }}"
                                class="text-sm font-medium text-gray-700 hover:text-blue-600">
                                {{ $product->name }}
                            </a>
                        </div>
                    </li>
                    <li aria-current="page">
                        <div class="flex items-center">
                            <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                            <span class="text-sm font-medium text-gray-500">Assign Supplier</span>
                        </div>
                    </li>
                </ol>
            </nav>
        </div>

        <!-- Header -->
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Assign Supplier</h1>
                    <p class="text-sm text-gray-600 mt-1">Link suppliers to <strong>{{ $product->name }}</strong></p>
                </div>
                <div class="flex items-center space-x-3">
                    <!-- Help Button -->
                    <button @click="showHelpModal = true"
                        class="inline-flex items-center px-3 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium rounded-lg transition-colors">
                        <i class="fas fa-question-circle w-4 h-4 mr-2"></i>
                        Help
                    </button>
                    <a href="{{ route('products.show', $product) }}"
                        class="inline-flex items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium rounded-lg transition-colors">
                        <i class="fas fa-arrow-left w-4 h-4 mr-2"></i>
                        Back to Product
                    </a>
                </div>
            </div>
        </div>

        <!-- Content -->
        <div class="p-6">
            <!-- Product Information Card -->
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center mr-4">
                        <i class="fas fa-box text-blue-600 text-xl"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-blue-900">{{ $product->name }}</h3>
                        <p class="text-sm text-blue-700">HSN: {{ $product->hsn_code }} • Unit: {{ $product->unit_type }}
                        </p>
                    </div>
                </div>
            </div>

            <form action="{{ route('products.assignSupplier', $product) }}" method="POST" id="assignForm"
                @submit.prevent="submitForm">
                @csrf

                <!-- Supplier Selection Card -->
                <div class="bg-white border border-gray-200 rounded-lg p-6 mb-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Select Supplier</h3>

                    @if ($suppliers->isEmpty())
                        <div class="text-center py-8">
                            <i class="fas fa-truck text-gray-400 text-4xl mb-4"></i>
                            <p class="text-gray-500 mb-4">No available suppliers found.</p>
                            <p class="text-sm text-gray-400">All suppliers are already assigned to this product.</p>
                        </div>
                    @else
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Choose Supplier <span class="text-red-500">*</span>
                                </label>
                                <select name="suppliers" id="suppliers" required
                                    @change="fetchSupplierDetails($event.target.value)"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                    <option value="">Select a supplier...</option>
                                    @foreach ($suppliers as $supplier)
                                        <option value="{{ $supplier->id }}">
                                            {{ $supplier->supplier_id }} - {{ $supplier->name }}
                                            ({{ $supplier->state }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('suppliers')
                                    <div class="mt-1 text-sm text-red-600">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Supplier Details Card -->
                <div x-show="showSupplierDetails" x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 transform scale-95"
                    x-transition:enter-end="opacity-100 transform scale-100"
                    class="bg-white border border-gray-200 rounded-lg p-6 mb-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">
                        <i class="fas fa-info-circle text-blue-600 mr-2"></i>
                        Supplier Details
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-500 mb-1">Company Name</label>
                                <p class="text-lg font-semibold text-gray-900" x-text="supplierDetails.name"></p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-500 mb-1">Contact Person</label>
                                <p class="text-gray-900" x-text="supplierDetails.contact_person"></p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-500 mb-1">GST Number</label>
                                <p class="text-gray-900" x-text="supplierDetails.gst"></p>
                            </div>
                        </div>
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-500 mb-1">Email</label>
                                <p class="text-gray-900" x-text="supplierDetails.email"></p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-500 mb-1">Phone</label>
                                <p class="text-gray-900" x-text="supplierDetails.phone"></p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-500 mb-1">Address</label>
                                <p class="text-gray-900" x-text="supplierDetails.address"></p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                @if (!$suppliers->isEmpty())
                    <div class="flex items-center justify-between">
                        <div class="text-sm text-gray-500">
                            <kbd class="px-2 py-1 bg-gray-100 rounded text-xs">Ctrl+S</kbd> to save •
                            <kbd class="px-2 py-1 bg-gray-100 rounded text-xs">Esc</kbd> to cancel
                        </div>
                        <div class="flex items-center space-x-3">
                            <a href="{{ route('products.show', $product) }}"
                                class="px-6 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 font-medium transition-colors">
                                Cancel
                            </a>
                            <button type="submit" :disabled="isSubmitting || !selectedSupplierId"
                                class="px-6 py-3 bg-blue-600 hover:bg-blue-700 disabled:bg-blue-400 text-white rounded-lg font-medium transition-colors inline-flex items-center">
                                <span x-show="!isSubmitting">
                                    <i class="fas fa-link mr-2"></i>
                                    Assign Supplier
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
                                    Assigning...
                                </span>
                            </button>
                        </div>
                    </div>
                @endif
            </form>
        </div>

        <!-- Help Modal -->
        <div x-show="showHelpModal" x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-white rounded-lg shadow-xl max-w-2xl w-full mx-4 max-h-[90vh] overflow-y-auto">
                <div class="flex items-center justify-between p-6 border-b border-gray-200">
                    <h2 class="text-xl font-bold text-gray-900">Assign Supplier Help</h2>
                    <button @click="showHelpModal = false" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>

                <div class="p-6 space-y-6">
                    <!-- Process Guide -->
                    <div class="border border-gray-200 rounded-lg p-4">
                        <h3 class="text-lg font-semibold text-gray-900 mb-3">
                            <i class="fas fa-clipboard-list text-blue-600 mr-2"></i>Assignment Process
                        </h3>
                        <div class="space-y-3 text-sm text-gray-700">
                            <div class="flex items-start">
                                <span
                                    class="inline-flex items-center justify-center w-6 h-6 bg-blue-100 text-blue-600 rounded-full text-xs font-medium mr-3 mt-0.5">1</span>
                                <div>
                                    <strong>Select Supplier:</strong> Choose from available suppliers in the dropdown
                                </div>
                            </div>
                            <div class="flex items-start">
                                <span
                                    class="inline-flex items-center justify-center w-6 h-6 bg-blue-100 text-blue-600 rounded-full text-xs font-medium mr-3 mt-0.5">2</span>
                                <div>
                                    <strong>Review Details:</strong> Verify supplier information and contact details
                                </div>
                            </div>
                            <div class="flex items-start">
                                <span
                                    class="inline-flex items-center justify-center w-6 h-6 bg-blue-100 text-blue-600 rounded-full text-xs font-medium mr-3 mt-0.5">3</span>
                                <div>
                                    <strong>Confirm Assignment:</strong> Click "Assign Supplier" to complete the linking
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Keyboard Shortcuts -->
                    <div class="border border-gray-200 rounded-lg p-4">
                        <h3 class="text-lg font-semibold text-gray-900 mb-3">
                            <i class="fas fa-keyboard text-green-600 mr-2"></i>Keyboard Shortcuts
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="space-y-2">
                                <div class="flex items-center justify-between">
                                    <span class="text-gray-700">Assign Supplier</span>
                                    <kbd class="px-2 py-1 bg-gray-100 border border-gray-300 rounded text-sm">Ctrl +
                                        S</kbd>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="text-gray-700">Cancel & Exit</span>
                                    <kbd class="px-2 py-1 bg-gray-100 border border-gray-300 rounded text-sm">Esc</kbd>
                                </div>
                            </div>
                            <div class="space-y-2">
                                <div class="flex items-center justify-between">
                                    <span class="text-gray-700">Show Help</span>
                                    <kbd class="px-2 py-1 bg-gray-100 border border-gray-300 rounded text-sm">H</kbd>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="text-gray-700">Focus Dropdown</span>
                                    <kbd class="px-2 py-1 bg-gray-100 border border-gray-300 rounded text-sm">Tab</kbd>
                                </div>
                            </div>
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
        function assignSupplierManager() {
            return {
                showHelpModal: false,
                showSupplierDetails: false,
                isSubmitting: false,
                selectedSupplierId: null,
                supplierDetails: {
                    name: '',
                    contact_person: '',
                    email: '',
                    phone: '',
                    address: '',
                    gst: ''
                },

                init() {
                    this.bindKeyboardEvents();
                },

                bindKeyboardEvents() {
                    document.addEventListener('keydown', (e) => {
                        if (e.ctrlKey && e.key === 's') {
                            e.preventDefault();
                            this.submitForm();
                        }

                        if (e.key.toLowerCase() === 'h' && !e.ctrlKey && !e.altKey) {
                            e.preventDefault();
                            this.showHelpModal = true;
                        }

                        if (e.key === 'Escape') {
                            e.preventDefault();
                            if (this.showHelpModal) {
                                this.showHelpModal = false;
                            } else if (confirm('Are you sure you want to cancel? All changes will be lost.')) {
                                window.location.href = '{{ route('products.show', $product) }}';
                            }
                        }
                    });
                },

                async fetchSupplierDetails(supplierId) {
                    if (!supplierId) {
                        this.showSupplierDetails = false;
                        this.selectedSupplierId = null;
                        return;
                    }

                    this.selectedSupplierId = supplierId;

                    try {
                        const response = await fetch(`/ims/suppliers/assign/${supplierId}`);
                        const data = await response.json();

                        if (data && data.supplier) {
                            this.supplierDetails = {
                                name: data.supplier.name || 'N/A',
                                contact_person: data.supplier.contact_person || 'N/A',
                                email: data.supplier.email || 'N/A',
                                phone: data.supplier.phone || 'N/A',
                                address: data.address ?
                                    `${data.address.address}, ${data.address.city}, ${data.address.state}, ${data.address.postal_code}, ${data.address.country}` :
                                    'N/A',
                                gst: data.gst || 'N/A'
                            };
                            this.showSupplierDetails = true;
                        }
                    } catch (error) {
                        console.error('Error fetching supplier details:', error);
                        alert('Error fetching supplier details. Please try again.');
                    }
                },

                submitForm() {
                    if (this.isSubmitting || !this.selectedSupplierId) return;

                    this.isSubmitting = true;
                    document.getElementById('assignForm').submit();
                }
            }
        }
    </script>
</x-app-layout>
