<x-app-layout>
    <x-slot name="title">
        {{ __('Customer Details') }} - {{ config('app.name', 'ATMS') }}
    </x-slot>

    <div class="bg-white min-h-screen" x-data="customerShowManager()" x-init="init()">
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
                            <a href="{{ route('customers.index') }}"
                                class="text-sm font-medium text-gray-700 hover:text-blue-600">
                                Customers
                            </a>
                        </div>
                    </li>
                    <li aria-current="page">
                        <div class="flex items-center">
                            <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                            <span class="text-sm font-medium text-gray-500">{{ $customer->company_name }}</span>
                        </div>
                    </li>
                </ol>
            </nav>
        </div>

        <!-- Header -->
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Customer Details</h1>
                    <p class="text-sm text-gray-600 mt-1">View customer information and contact details</p>
                </div>
                <div class="flex items-center space-x-3">
                    <a href="{{ route('customers.edit', $customer) }}"
                        class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors">
                        <i class="fas fa-edit w-4 h-4 mr-2"></i>
                        Edit Customer
                    </a>
                    <button @click="deleteCustomer()"
                        class="inline-flex items-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg transition-colors">
                        <i class="fas fa-trash w-4 h-4 mr-2"></i>
                        Delete
                    </button>
                    <a href="{{ route('customers.index') }}"
                        class="inline-flex items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium rounded-lg transition-colors">
                        <i class="fas fa-arrow-left w-4 h-4 mr-2"></i>
                        Back to List
                    </a>
                </div>
            </div>
        </div>

        <!-- Content -->
        <div class="p-6">
            <!-- Customer Profile Card -->
            <div class="bg-white border border-gray-200 rounded-lg shadow-sm overflow-hidden mb-6">
                <div class="bg-gradient-to-r from-blue-500 to-blue-600 px-6 py-8 text-white">
                    <div class="flex items-center">
                        <div class="w-16 h-16 bg-white bg-opacity-20 rounded-xl flex items-center justify-center mr-4">
                            <i class="fas fa-building text-3xl"></i>
                        </div>
                        <div>
                            <h2 class="text-2xl font-bold">{{ $customer->company_name }}</h2>
                            <p class="text-blue-100 mt-1">Customer ID: {{ $customer->cid }}</p>
                            @if ($customer->gst_number)
                                <div class="mt-2">
                                    <span
                                        class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-white bg-opacity-20 text-white">
                                        <i class="fas fa-receipt mr-1"></i>
                                        GST: {{ $customer->gst_number }}
                                    </span>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                    <div class="flex items-center space-x-4">
                        <span class="text-sm font-medium text-gray-700">Quick Actions:</span>
                        <a href="{{ route('customers.edit', $customer) }}"
                            class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                            <i class="fas fa-edit mr-1"></i>Edit
                        </a>
                        <button @click="deleteCustomer()" class="text-red-600 hover:text-red-800 text-sm font-medium">
                            <i class="fas fa-trash mr-1"></i>Delete
                        </button>
                        <div class="text-gray-300">|</div>
                        <span class="text-xs text-gray-500">
                            <kbd class="px-1 py-0.5 bg-gray-200 rounded text-xs">E</kbd> to edit •
                            <kbd class="px-1 py-0.5 bg-gray-200 rounded text-xs">D</kbd> to delete •
                            <kbd class="px-1 py-0.5 bg-gray-200 rounded text-xs">Esc</kbd> to go back
                        </span>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Customer Information -->
                <div class="lg:col-span-2">
                    <div class="bg-white border border-gray-200 rounded-lg p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">
                            <i class="fas fa-info-circle mr-2 text-blue-500"></i>
                            Customer Information
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-500 mb-1">Company Name</label>
                                    <p class="text-gray-900 font-medium">{{ $customer->company_name }}</p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-500 mb-1">Customer ID</label>
                                    <p class="text-gray-900 font-mono">{{ $customer->cid }}</p>
                                </div>
                                @if ($customer->gst_number)
                                    <div>
                                        <label class="block text-sm font-medium text-gray-500 mb-1">GST Number</label>
                                        <p class="text-gray-900 font-mono">{{ $customer->gst_number }}</p>
                                    </div>
                                @endif
                            </div>
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-500 mb-1">City</label>
                                    <p class="text-gray-900">{{ $customer->city }}</p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-500 mb-1">State</label>
                                    <p class="text-gray-900">{{ $customer->state }}</p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-500 mb-1">Country</label>
                                    <p class="text-gray-900">{{ $customer->country }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="mt-6 pt-6 border-t border-gray-200">
                            <label class="block text-sm font-medium text-gray-500 mb-2">Address</label>
                            <p class="text-gray-900 leading-relaxed">{{ $customer->address }}</p>
                            <p class="text-gray-900 mt-1">{{ $customer->city }}, {{ $customer->state }}
                                {{ $customer->zip_code }}</p>
                        </div>
                    </div>
                </div>

                <!-- Summary Stats -->
                <div class="space-y-6">
                    <div class="bg-white border border-gray-200 rounded-lg p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">
                            <i class="fas fa-chart-bar mr-2 text-green-500"></i>
                            Quick Stats
                        </h3>
                        <div class="space-y-4">
                            <div class="flex items-center justify-between p-3 bg-blue-50 rounded-lg">
                                <div class="flex items-center">
                                    <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center mr-3">
                                        <i class="fas fa-users text-blue-600 text-sm"></i>
                                    </div>
                                    <span class="text-sm font-medium text-gray-700">Contact Persons</span>
                                </div>
                                <span
                                    class="text-lg font-bold text-blue-600">{{ $customer->contactPersons->count() }}</span>
                            </div>

                            @if ($customer->gst_number)
                                <div class="flex items-center justify-between p-3 bg-green-50 rounded-lg">
                                    <div class="flex items-center">
                                        <div
                                            class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center mr-3">
                                            <i class="fas fa-check-circle text-green-600 text-sm"></i>
                                        </div>
                                        <span class="text-sm font-medium text-gray-700">GST Status</span>
                                    </div>
                                    <span class="text-sm font-medium text-green-600">Registered</span>
                                </div>
                            @else
                                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                    <div class="flex items-center">
                                        <div
                                            class="w-8 h-8 bg-gray-100 rounded-lg flex items-center justify-center mr-3">
                                            <i class="fas fa-times-circle text-gray-600 text-sm"></i>
                                        </div>
                                        <span class="text-sm font-medium text-gray-700">GST Status</span>
                                    </div>
                                    <span class="text-sm font-medium text-gray-600">Not Registered</span>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Contact Persons -->
            <div class="mt-8">
                <div class="bg-white border border-gray-200 rounded-lg overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">
                            <i class="fas fa-address-book mr-2 text-purple-500"></i>
                            Contact Persons
                        </h3>
                    </div>

                    @if ($customer->contactPersons->isEmpty())
                        <div class="px-6 py-12 text-center">
                            <i class="fas fa-user-plus text-gray-300 text-4xl mb-4"></i>
                            <h4 class="text-lg font-medium text-gray-900 mb-2">No contact persons</h4>
                            <p class="text-gray-500 mb-4">This customer doesn't have any contact persons yet.</p>
                            <a href="{{ route('customers.edit', $customer) }}"
                                class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors">
                                <i class="fas fa-plus w-4 h-4 mr-2"></i>
                                Add Contact Person
                            </a>
                        </div>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            #</th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Name</th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Designation</th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Phone</th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Email</th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach ($customer->contactPersons as $contact)
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $loop->iteration }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex items-center">
                                                    <div
                                                        class="w-8 h-8 bg-purple-100 rounded-full flex items-center justify-center mr-3">
                                                        <i class="fas fa-user text-purple-600 text-sm"></i>
                                                    </div>
                                                    <div class="text-sm font-medium text-gray-900">
                                                        {{ $contact->name }}</div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm text-gray-900">
                                                    {{ $contact->designation ?? 'N/A' }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm text-gray-900">{{ $contact->phone_no }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm text-gray-900">{{ $contact->email ?? 'N/A' }}
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                <a href="tel:{{ $contact->phone_no }}"
                                                    class="text-green-600 hover:text-green-900 mr-3" title="Call">
                                                    <i class="fas fa-phone"></i>
                                                </a>
                                                @if ($contact->email)
                                                    <a href="mailto:{{ $contact->email }}"
                                                        class="text-blue-600 hover:text-blue-900" title="Send Email">
                                                        <i class="fas fa-envelope"></i>
                                                    </a>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Delete Confirmation Modal -->
        <div x-show="showDeleteModal" x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0" class="fixed inset-0 z-50 overflow-y-auto"
            @keydown.escape.window="closeDeleteModal()">

            <!-- Backdrop -->
            <div class="fixed inset-0 bg-gray-900 bg-opacity-50" @click="closeDeleteModal()"></div>

            <!-- Modal -->
            <div class="relative flex min-h-screen items-center justify-center p-4">
                <div x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                    x-transition:leave="transition ease-in duration-200"
                    x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
                    class="w-full max-w-md bg-white rounded-lg shadow-xl">

                    <!-- Header -->
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center mr-4">
                                <i class="fas fa-exclamation-triangle text-red-600 text-xl"></i>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900">Delete Customer</h3>
                                <p class="text-sm text-gray-600 mt-1">This action cannot be undone</p>
                            </div>
                        </div>
                    </div>

                    <!-- Content -->
                    <div class="px-6 py-4">
                        <p class="text-sm text-gray-700">
                            Are you sure you want to delete customer
                            <strong>{{ $customer->company_name }}</strong>
                            (ID: <strong>{{ $customer->cid }}</strong>)?
                        </p>
                        @if ($customer->contactPersons->count() > 0)
                            <p class="text-sm text-gray-600 mt-2">
                                This will also delete {{ $customer->contactPersons->count() }} associated contact
                                person(s).
                            </p>
                        @endif
                    </div>

                    <!-- Actions -->
                    <div class="flex items-center justify-end space-x-3 px-6 py-4 bg-gray-50 rounded-b-lg">
                        <button @click="closeDeleteModal()"
                            class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            Cancel
                        </button>
                        <button @click="confirmDelete()"
                            class="px-4 py-2 text-sm font-medium text-white bg-red-600 border border-transparent rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                            Delete Customer
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function customerShowManager() {
            return {
                showDeleteModal: false,

                init() {
                    this.bindKeyboardEvents();
                },

                bindKeyboardEvents() {
                    document.addEventListener('keydown', (e) => {
                        // Ignore keyboard shortcuts when typing in input fields
                        if (e.target.tagName === 'INPUT' || e.target.tagName === 'TEXTAREA') {
                            return;
                        }

                        switch (e.key.toLowerCase()) {
                            case 'e':
                                e.preventDefault();
                                window.location.href = '{{ route('customers.edit', $customer) }}';
                                break;
                            case 'd':
                                e.preventDefault();
                                this.deleteCustomer();
                                break;
                            case 'escape':
                                e.preventDefault();
                                if (this.showDeleteModal) {
                                    this.closeDeleteModal();
                                } else {
                                    window.location.href = '{{ route('customers.index') }}';
                                }
                                break;
                        }
                    });
                },

                deleteCustomer() {
                    this.showDeleteModal = true;
                },

                closeDeleteModal() {
                    this.showDeleteModal = false;
                },

                confirmDelete() {
                    // Create and submit a form to delete the customer
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = '{{ route('customers.destroy', $customer) }}';

                    const csrfToken = document.createElement('input');
                    csrfToken.type = 'hidden';
                    csrfToken.name = '_token';
                    csrfToken.value = '{{ csrf_token() }}';

                    const methodField = document.createElement('input');
                    methodField.type = 'hidden';
                    methodField.name = '_method';
                    methodField.value = 'DELETE';

                    form.appendChild(csrfToken);
                    form.appendChild(methodField);
                    document.body.appendChild(form);
                    form.submit();
                }
            }
        }
    </script>
</x-app-layout>
