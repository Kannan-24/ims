<x-app-layout>
    <x-slot name="title">
        {{ __('Customer Managemnet') }} - {{ config('app.name', 'IMS') }}
    </x-slot>

    <div class="bg-white" x-data="customerManager()" x-init="init()">
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
                    <li aria-current="page">
                        <div class="flex items-center">
                            <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                            <span class="text-sm font-medium text-gray-500">Customers</span>
                        </div>
                    </li>
                </ol>
            </nav>
        </div>

        <!-- Header -->
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Customer Management</h1>
                    <p class="text-sm text-gray-600 mt-1">Manage customer information and contact details</p>
                </div>
                <div class="flex items-center space-x-3">
                    <!-- Help Button -->
                    <button @click="showHelpModal = true"
                        class="inline-flex items-center px-3 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium rounded-lg transition-colors">
                        <i class="fas fa-question-circle w-4 h-4 mr-2"></i>
                        Help
                    </button>
                    <!-- Add Customer Button -->
                    <a href="{{ route('customers.create') }}"
                        class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors">
                        <i class="fas fa-plus w-4 h-4 mr-2"></i>
                        New Customer
                    </a>
                </div>
            </div>
        </div>

        <!-- Content -->
        <div class="p-6">
            <!-- Customers Table -->
            <div class="bg-white border border-gray-200 rounded-lg shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <div class="flex items-center justify-between">
                        <h2 class="text-lg font-semibold text-gray-900">Customer Directory</h2>
                        <div class="flex items-center space-x-2">
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                    <i class="fas fa-search text-gray-400"></i>
                                </div>
                                <form method="GET" action="{{ route('customers.index') }}" class="flex">
                                    <input type="text" name="search" value="{{ request('search') }}"
                                        placeholder="Search customers..." id="searchInput"
                                        class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                    <button type="submit"
                                        class="ml-2 px-4 py-2 bg-blue-600 text-white rounded-lg text-sm hover:bg-blue-700">Search</button>
                                    @if (request('search'))
                                        <a href="{{ route('customers.index') }}"
                                            class="ml-2 px-4 py-2 bg-gray-500 text-white rounded-lg text-sm hover:bg-gray-600">Reset</a>
                                    @endif
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    #</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Customer Info</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Contact Person</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Location</th>
                               
                                <th
                                    class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200" id="customerTableBody">
                            @forelse ($customers as $customer)
                                <tr class="hover:bg-gray-50 customer-row" data-customer-id="{{ $customer->id }}"
                                    data-customer-cid="{{ $customer->cid }}"
                                    data-customer-name="{{ $customer->company_name }}"
                                    :class="selectedRowIndex === {{ $loop->index }} ? 'bg-blue-50 ring-2 ring-blue-500' : ''">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $loop->iteration }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div
                                                class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center mr-3">
                                                <i class="fas fa-building text-blue-600"></i>
                                            </div>
                                            <div>
                                                <div class="text-sm font-medium text-gray-900">
                                                    {{ $customer->company_name }}</div>
                                                <div class="text-sm text-gray-500">ID: {{ $customer->cid }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if ($customer->contactPersons->first())
                                            <div class="text-sm text-gray-900">
                                                {{ $customer->contactPersons->first()->name }}</div>
                                            <div class="text-sm text-gray-500">
                                                {{ $customer->contactPersons->first()->phone_no }}</div>
                                        @else
                                            <span class="text-sm text-gray-400">No contact</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ $customer->city }}</div>
                                        <div class="text-sm text-gray-500">{{ $customer->state }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <div class="flex items-center justify-end space-x-2">
                                            <a href="{{ route('customers.show', $customer) }}"
                                                class="text-blue-600 hover:text-blue-900 transition-colors"
                                                title="View Customer">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('customers.edit', $customer) }}"
                                                class="text-green-600 hover:text-green-900 transition-colors"
                                                title="Edit Customer">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <button
                                                @click="deleteCustomer({{ $customer->id }}, '{{ $customer->cid }}', '{{ addslashes($customer->company_name) }}')"
                                                class="text-red-600 hover:text-red-900 transition-colors"
                                                title="Delete Customer">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-12 text-center">
                                        <div class="flex flex-col items-center justify-center">
                                            <i class="fas fa-users text-gray-300 text-4xl mb-4"></i>
                                            <h3 class="text-lg font-medium text-gray-900 mb-2">No customers found</h3>
                                            <p class="text-sm text-gray-500 mb-4">
                                                @if (request('search'))
                                                    Try adjusting your search terms.
                                                @else
                                                    Get started by adding your first customer.
                                                @endif
                                            </p>
                                            <a href="{{ route('customers.create') }}"
                                                class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors">
                                                <i class="fas fa-plus w-4 h-4 mr-2"></i>
                                                Add Your First Customer
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
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
                            <strong x-text="customerToDelete.name"></strong>
                            (ID: <strong x-text="customerToDelete.cid"></strong>)?
                        </p>
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
            <div x-show="showHelpModal" x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
                <div class="bg-white rounded-lg shadow-xl max-w-4xl w-full mx-4 max-h-[90vh] overflow-y-auto">
                    <div class="flex items-center justify-between p-6 border-b border-gray-200">
                        <h2 class="text-xl font-bold text-gray-900">Customer Management Help</h2>
                        <button @click="closeHelpModal()" class="text-gray-400 hover:text-gray-600">
                            <i class="fas fa-times text-xl"></i>
                        </button>
                    </div>

                    <div class="p-6 space-y-6">
                        <!-- Navigation Help -->
                        <div class="border border-gray-200 rounded-lg p-4">
                            <h3 class="text-lg font-semibold text-gray-900 mb-3">
                                <i class="fas fa-keyboard text-blue-600 mr-2"></i>Keyboard Shortcuts
                            </h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="space-y-2">
                                    <div class="flex items-center justify-between">
                                        <span class="text-gray-700">New Customer</span>
                                        <kbd
                                            class="px-2 py-1 bg-gray-100 border border-gray-300 rounded text-sm">N</kbd>
                                    </div>
                                    <div class="flex items-center justify-between">
                                        <span class="text-gray-700">Search</span>
                                        <kbd
                                            class="px-2 py-1 bg-gray-100 border border-gray-300 rounded text-sm">S</kbd>
                                    </div>
                                    <div class="flex items-center justify-between">
                                        <span class="text-gray-700">Navigate Down</span>
                                        <kbd
                                            class="px-2 py-1 bg-gray-100 border border-gray-300 rounded text-sm">↓</kbd>
                                    </div>
                                </div>
                                <div class="space-y-2">
                                    <div class="flex items-center justify-between">
                                        <span class="text-gray-700">Navigate Up</span>
                                        <kbd
                                            class="px-2 py-1 bg-gray-100 border border-gray-300 rounded text-sm">↑</kbd>
                                    </div>
                                    <div class="flex items-center justify-between">
                                        <span class="text-gray-700">View Selected</span>
                                        <kbd
                                            class="px-2 py-1 bg-gray-100 border border-gray-300 rounded text-sm">Enter</kbd>
                                    </div>
                                    <div class="flex items-center justify-between">
                                        <span class="text-gray-700">Delete Selected</span>
                                        <kbd
                                            class="px-2 py-1 bg-gray-100 border border-gray-300 rounded text-sm">Delete</kbd>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Features Help -->
                        <div class="border border-gray-200 rounded-lg p-4">
                            <h3 class="text-lg font-semibold text-gray-900 mb-3">
                                <i class="fas fa-cogs text-green-600 mr-2"></i>Features & Actions
                            </h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="space-y-3">
                                    <div>
                                        <h4 class="font-medium text-gray-900">Search & Filter</h4>
                                        <p class="text-sm text-gray-600">Use the search box to find customers by name,
                                            company,
                                            city, or contact information.</p>
                                    </div>
                                    <div>
                                        <h4 class="font-medium text-gray-900">Quick Stats</h4>
                                        <p class="text-sm text-gray-600">View total customers, contacts, cities, and
                                            GST-registered customers at a glance.</p>
                                    </div>
                                </div>
                                <div class="space-y-3">
                                    <div>
                                        <h4 class="font-medium text-gray-900">Customer Actions</h4>
                                        <p class="text-sm text-gray-600">Click on any customer row to view details, or
                                            use
                                            action buttons to edit or delete.</p>
                                    </div>
                                    <div>
                                        <h4 class="font-medium text-gray-900">Bulk Operations</h4>
                                        <p class="text-sm text-gray-600">Use keyboard navigation to quickly move
                                            through the
                                            customer list.</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Data Understanding -->
                        <div class="border border-gray-200 rounded-lg p-4">
                            <h3 class="text-lg font-semibold text-gray-900 mb-3">
                                <i class="fas fa-info-circle text-purple-600 mr-2"></i>Data Overview
                            </h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="space-y-2">
                                    <div><strong>Customer ID:</strong> Unique identifier for each customer</div>
                                    <div><strong>Company:</strong> Business name and primary contact</div>
                                    <div><strong>Location:</strong> City and state information</div>
                                </div>
                                <div class="space-y-2">
                                    <div><strong>Contacts:</strong> Number of contact persons per customer</div>
                                    <div><strong>GST Number:</strong> Tax registration information</div>
                                    <div><strong>Created:</strong> Registration date and time</div>
                                </div>
                            </div>
                        </div>

                        <!-- Navigation Help -->
                        <div class="border border-gray-200 rounded-lg p-4">
                            <h3 class="text-lg font-semibold text-gray-900 mb-3">
                                <i class="fas fa-route text-amber-600 mr-2"></i>Page Navigation
                            </h3>
                            <div class="space-y-2">
                                <div><strong>Dashboard:</strong> Return to main dashboard</div>
                                <div><strong>New Customer:</strong> Create a new customer record</div>
                                <div><strong>View Customer:</strong> Click any row to view customer details</div>
                                <div><strong>Edit Customer:</strong> Use edit button or double-click to modify</div>
                                <div><strong>Delete Customer:</strong> Use delete button with confirmation</div>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center justify-end px-6 py-4 bg-gray-50 border-t border-gray-200">
                        <button @click="closeHelpModal()"
                            class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                            Got it!
                        </button>
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
                    <h2 class="text-xl font-bold text-gray-900">Customer Management Help</h2>
                    <button @click="closeHelpModal()" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>

                <div class="p-6 space-y-6">
                    <!-- Navigation Help -->
                    <div class="border border-gray-200 rounded-lg p-4">
                        <h3 class="text-lg font-semibold text-gray-900 mb-3">
                            <i class="fas fa-keyboard text-blue-600 mr-2"></i>Keyboard Shortcuts
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="space-y-2">
                                <div class="flex items-center justify-between">
                                    <span class="text-gray-700">New Customer</span>
                                    <kbd class="px-2 py-1 bg-gray-100 border border-gray-300 rounded text-sm">N</kbd>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="text-gray-700">Search</span>
                                    <kbd class="px-2 py-1 bg-gray-100 border border-gray-300 rounded text-sm">S</kbd>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="text-gray-700">Navigate Down</span>
                                    <kbd class="px-2 py-1 bg-gray-100 border border-gray-300 rounded text-sm">↓</kbd>
                                </div>
                            </div>
                            <div class="space-y-2">
                                <div class="flex items-center justify-between">
                                    <span class="text-gray-700">Navigate Up</span>
                                    <kbd class="px-2 py-1 bg-gray-100 border border-gray-300 rounded text-sm">↑</kbd>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="text-gray-700">View Customer</span>
                                    <kbd
                                        class="px-2 py-1 bg-gray-100 border border-gray-300 rounded text-sm">Enter</kbd>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="text-gray-700">Help</span>
                                    <kbd class="px-2 py-1 bg-gray-100 border border-gray-300 rounded text-sm">H</kbd>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Actions Help -->
                    <div class="border border-gray-200 rounded-lg p-4">
                        <h3 class="text-lg font-semibold text-gray-900 mb-3">
                            <i class="fas fa-tasks text-green-600 mr-2"></i>Quick Actions
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="space-y-2 text-sm text-gray-700">
                                <div><strong>View Customer:</strong> Click on customer name or use Enter on selected row
                                </div>
                                <div><strong>Edit Customer:</strong> Click edit button or select and press E</div>
                                <div><strong>Delete Customer:</strong> Click delete button with confirmation</div>
                            </div>
                            <div class="space-y-2 text-sm text-gray-700">
                                <div><strong>Search:</strong> Use search box or press S to focus</div>
                                <div><strong>New Customer:</strong> Click '+' button or press N</div>
                                <div><strong>Navigation:</strong> Use arrow keys to navigate table rows</div>
                            </div>
                        </div>
                    </div>

                    <!-- Table Navigation -->
                    <div class="border border-gray-200 rounded-lg p-4">
                        <h3 class="text-lg font-semibold text-gray-900 mb-3">
                            <i class="fas fa-table text-purple-600 mr-2"></i>Table Features
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="space-y-2 text-sm text-gray-700">
                                <div><strong>Sorting:</strong> Click on column headers to sort</div>
                                <div><strong>Filtering:</strong> Use search box to filter customers</div>
                                <div><strong>Row Selection:</strong> Rows highlight on keyboard navigation</div>
                            </div>
                            <div class="space-y-2 text-sm text-gray-700">
                                <div><strong>Customer Info:</strong> ID, Company, City, GST, Contacts</div>
                                <div><strong>Quick Actions:</strong> View, Edit, Delete buttons</div>
                                <div><strong>Contact Count:</strong> Shows number of contact persons</div>
                            </div>
                        </div>
                    </div>

                    <!-- Tips -->
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                        <h3 class="text-lg font-semibold text-blue-900 mb-3">
                            <i class="fas fa-lightbulb text-blue-600 mr-2"></i>Pro Tips
                        </h3>
                        <div class="space-y-2 text-sm text-blue-800">
                            <div>• Use keyboard shortcuts for faster navigation</div>
                            <div>• Search by company name, city, CID, or contact person name</div>
                            <div>• Press Esc to close dialogs and help</div>
                            <div>• Table rows show selection state for keyboard navigation</div>
                        </div>
                    </div>
                </div>

                <div class="flex items-center justify-end px-6 py-4 bg-gray-50 border-t border-gray-200">
                    <button @click="closeHelpModal()"
                        class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                        Got it!
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Floating Action Button for Mobile -->
    <div class="fixed bottom-6 right-6 md:hidden">
        <a href="{{ route('customers.create') }}"
            class="w-14 h-14 bg-blue-600 hover:bg-blue-700 text-white rounded-full shadow-lg flex items-center justify-center transition-colors">
            <i class="fas fa-plus text-xl"></i>
        </a>
    </div>

    <script>
        function customerManager() {
            return {
                selectedRowIndex: -1,
                showDeleteModal: false,
                showHelpModal: false,
                customerToDelete: {
                    id: null,
                    name: '',
                    cid: ''
                },

                init() {
                    this.bindKeyboardEvents();
                },

                bindKeyboardEvents() {
                    document.addEventListener('keydown', (e) => {
                        // Ignore keyboard shortcuts when typing in input fields
                        if (e.target.tagName === 'INPUT' || e.target.tagName === 'TEXTAREA') {
                            return;
                        }

                        const rows = document.querySelectorAll('.customer-row');

                        switch (e.key.toLowerCase()) {
                            case 'n':
                                e.preventDefault();
                                window.location.href = '{{ route('customers.create') }}';
                                break;
                            case 's':
                                e.preventDefault();
                                document.getElementById('searchInput').focus();
                                break;
                            case 'h':
                                e.preventDefault();
                                this.showHelpModal = true;
                                break;
                            case 'escape':
                                e.preventDefault();
                                if (this.showHelpModal) {
                                    this.closeHelpModal();
                                } else if (this.showDeleteModal) {
                                    this.closeDeleteModal();
                                }
                                break;
                            case 'arrowdown':
                                e.preventDefault();
                                if (this.selectedRowIndex < rows.length - 1) {
                                    this.selectedRowIndex++;
                                    this.scrollToRow(rows[this.selectedRowIndex]);
                                }
                                break;
                            case 'arrowup':
                                e.preventDefault();
                                if (this.selectedRowIndex > 0) {
                                    this.selectedRowIndex--;
                                    this.scrollToRow(rows[this.selectedRowIndex]);
                                }
                                break;
                            case 'enter':
                                e.preventDefault();
                                if (this.selectedRowIndex >= 0 && rows[this.selectedRowIndex]) {
                                    const customerId = rows[this.selectedRowIndex].dataset.customerId;
                                    window.location.href = `/customers/${customerId}`;
                                }
                                break;
                            case 'e':
                                e.preventDefault();
                                if (this.selectedRowIndex >= 0 && rows[this.selectedRowIndex]) {
                                    const customerId = rows[this.selectedRowIndex].dataset.customerId;
                                    window.location.href = `/customers/${customerId}/edit`;
                                }
                                break;
                            case 'd':
                                e.preventDefault();
                                if (this.selectedRowIndex >= 0 && rows[this.selectedRowIndex]) {
                                    const row = rows[this.selectedRowIndex];
                                    this.deleteCustomer(
                                        row.dataset.customerId,
                                        row.dataset.customerCid,
                                        row.dataset.customerName
                                    );
                                }
                                break;
                        }
                    });
                },

                scrollToRow(row) {
                    row.scrollIntoView({
                        behavior: 'smooth',
                        block: 'center'
                    });
                },

                deleteCustomer(id, cid, name) {
                    this.customerToDelete = {
                        id,
                        cid,
                        name
                    };
                    this.showDeleteModal = true;
                },

                closeDeleteModal() {
                    this.showDeleteModal = false;
                    this.customerToDelete = {
                        id: null,
                        name: '',
                        cid: ''
                    };
                },

                closeHelpModal() {
                    this.showHelpModal = false;
                },

                confirmDelete() {
                    // Create and submit a form to delete the customer
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = `/customers/${this.customerToDelete.id}`;

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
