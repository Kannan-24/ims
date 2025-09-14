<x-app-layout>
    <x-slot name="title">
        📇 Contact Book - {{ config('app.name', 'SKM') }}
    </x-slot>

    <div class="bg-white min-h-screen" x-data="contactBookApp()">
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
                            <span class="text-sm font-medium text-gray-500">📇 Contact Book</span>
                        </div>
                    </li>
                </ol>
            </nav>
        </div>

        <!-- Header -->
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 flex items-center">
                        <span class="text-2xl mr-2">📇</span>
                        Contact Book
                    </h1>
                    <p class="text-sm text-gray-600 mt-1">Unified contacts from customers, suppliers, and contact persons</p>
                </div>
                <div class="flex items-center space-x-3">
                    <button @click="exportSelected()" 
                            :disabled="selectedContacts.length === 0"
                            class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 disabled:bg-gray-400 disabled:cursor-not-allowed text-white text-sm font-medium rounded-lg transition-colors">
                        <i class="fas fa-envelope w-4 h-4 mr-2"></i>
                        Export to Email (<span x-text="selectedContacts.length"></span>)
                    </button>
                    <button @click="selectAll()" 
                            class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors">
                        <i class="fas fa-check-double w-4 h-4 mr-2"></i>
                        Select All
                    </button>
                    <button @click="clearSelection()" 
                            class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white text-sm font-medium rounded-lg transition-colors">
                        <i class="fas fa-times w-4 h-4 mr-2"></i>
                        Clear
                    </button>
                </div>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
                <div class="bg-white border border-gray-200 rounded-lg p-6">
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                            <span class="text-xl">📇</span>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Total Contacts</p>
                            <p class="text-2xl font-bold text-gray-900">{{ $stats['total'] }}</p>
                        </div>
                    </div>
                </div>
                <div class="bg-white border border-gray-200 rounded-lg p-6">
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                            <span class="text-xl">🏢</span>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Customers</p>
                            <p class="text-2xl font-bold text-gray-900">{{ $stats['customers'] }}</p>
                        </div>
                    </div>
                </div>
                <div class="bg-white border border-gray-200 rounded-lg p-6">
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                            <span class="text-xl">🏭</span>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Suppliers</p>
                            <p class="text-2xl font-bold text-gray-900">{{ $stats['suppliers'] }}</p>
                        </div>
                    </div>
                </div>
                <div class="bg-white border border-gray-200 rounded-lg p-6">
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center">
                            <span class="text-xl">👤</span>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Contact Persons</p>
                            <p class="text-2xl font-bold text-gray-900">{{ $stats['contact_persons'] }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Search and Filters -->
            <div class="bg-white border border-gray-200 rounded-lg p-6 mb-6">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between space-y-4 md:space-y-0">
                    <div class="flex-1 max-w-md">
                        <div class="relative">
                            <input type="text" 
                                   x-model="searchTerm"
                                   @input="debounceSearch()"
                                   placeholder="Search contacts by name or email..."
                                   class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-search text-gray-400"></i>
                            </div>
                        </div>
                    </div>
                    <div class="flex items-center space-x-3">
                        <select x-model="filterType" @change="applyFilters()"
                                class="border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="all">All Types</option>
                            <option value="Customer">🏢 Customers</option>
                            <option value="Supplier">🏭 Suppliers</option>
                            <option value="Contact Person">👤 Contact Persons</option>
                        </select>
                        <button @click="clearFilters()" 
                                class="inline-flex items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium rounded-lg transition-colors">
                            <i class="fas fa-times mr-2"></i>
                            Clear Filters
                        </button>
                    </div>
                </div>
            </div>

            <!-- Contacts Table -->
            <div class="bg-white border border-gray-200 rounded-lg shadow-sm overflow-hidden">
                <!-- Table Header -->
                <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-gray-800 flex items-center">
                            <span class="text-xl mr-2">📋</span>
                            Contact Directory
                        </h3>
                        <div class="text-sm text-gray-600">
                            <span x-text="filteredContacts.length"></span> contacts found
                        </div>
                    </div>
                </div>

                <!-- Loading State -->
                <div x-show="isLoading" class="p-8 text-center">
                    <div class="inline-flex items-center">
                        <i class="fas fa-spinner fa-spin mr-2"></i>
                        Loading contacts...
                    </div>
                </div>

                <!-- Table Content -->
                <div x-show="!isLoading" class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    <input type="checkbox" 
                                           @change="toggleSelectAll($event.target.checked)"
                                           class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Contact
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Type
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Email
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Phone
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Location
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Actions
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <template x-for="contact in paginatedContacts" :key="contact.id">
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <input type="checkbox" 
                                               :value="contact.id"
                                               @change="toggleContact(contact.id, $event.target.checked)"
                                               :checked="selectedContacts.includes(contact.id)"
                                               class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-10 w-10">
                                                <div class="h-10 w-10 bg-gray-100 rounded-full flex items-center justify-center">
                                                    <span x-text="contact.type_icon" class="text-lg"></span>
                                                </div>
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900" x-text="contact.name"></div>
                                                <div x-show="contact.parent" class="text-sm text-gray-500">
                                                    <span x-text="contact.parent"></span>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium"
                                              :class="{
                                                  'bg-green-100 text-green-800': contact.type === 'Customer',
                                                  'bg-purple-100 text-purple-800': contact.type === 'Supplier',
                                                  'bg-blue-100 text-blue-800': contact.type === 'Contact Person'
                                              }">
                                            <span x-text="contact.type_icon" class="mr-1"></span>
                                            <span x-text="contact.type"></span>
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        <div x-show="contact.email">
                                            <a :href="'mailto:' + contact.email" 
                                               class="text-blue-600 hover:text-blue-800"
                                               x-text="contact.email"></a>
                                        </div>
                                        <div x-show="!contact.email" class="text-gray-400 italic">
                                            No email
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        <div x-show="contact.phone">
                                            <a :href="'tel:' + contact.phone" 
                                               class="text-blue-600 hover:text-blue-800"
                                               x-text="contact.phone"></a>
                                        </div>
                                        <div x-show="!contact.phone" class="text-gray-400 italic">
                                            No phone
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        <span x-text="contact.location || 'N/A'"></span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <div class="flex items-center space-x-2">
                                            <button @click="copyEmail(contact)" 
                                                    :disabled="!contact.email"
                                                    class="text-blue-600 hover:text-blue-900 disabled:text-gray-400 disabled:cursor-not-allowed"
                                                    title="Copy Email">
                                                <i class="fas fa-copy"></i>
                                            </button>
                                            <button @click="sendEmail(contact)" 
                                                    :disabled="!contact.email"
                                                    class="text-green-600 hover:text-green-900 disabled:text-gray-400 disabled:cursor-not-allowed"
                                                    title="Send Email">
                                                <i class="fas fa-envelope"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            </template>
                        </tbody>
                    </table>

                    <!-- Empty State -->
                    <div x-show="filteredContacts.length === 0 && !isLoading" class="text-center py-12">
                        <div class="text-gray-400 text-4xl mb-4">📇</div>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">No contacts found</h3>
                        <p class="text-gray-500">
                            Try adjusting your search criteria or filters.
                        </p>
                    </div>
                </div>

                <!-- Pagination -->
                <div x-show="totalPages > 1" class="px-6 py-4 bg-gray-50 border-t border-gray-200">
                    <div class="flex items-center justify-between">
                        <div class="text-sm text-gray-700">
                            Showing page <span x-text="currentPage"></span> of <span x-text="totalPages"></span>
                        </div>
                        <div class="flex items-center space-x-2">
                            <button @click="changePage(currentPage - 1)" 
                                    :disabled="currentPage === 1"
                                    class="px-3 py-1 border border-gray-300 rounded text-sm disabled:opacity-50 disabled:cursor-not-allowed hover:bg-gray-100">
                                Previous
                            </button>
                            <template x-for="page in pageNumbers" :key="page">
                                <button @click="changePage(page)" 
                                        :class="page === currentPage ? 'bg-blue-600 text-white' : 'bg-white text-gray-700 hover:bg-gray-100'"
                                        class="px-3 py-1 border border-gray-300 rounded text-sm"
                                        x-text="page">
                                </button>
                            </template>
                            <button @click="changePage(currentPage + 1)" 
                                    :disabled="currentPage === totalPages"
                                    class="px-3 py-1 border border-gray-300 rounded text-sm disabled:opacity-50 disabled:cursor-not-allowed hover:bg-gray-100">
                                Next
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Toast Notifications -->
        <div id="toast-container" class="fixed top-4 right-4 z-50"></div>

        <!-- Export Modal -->
        <div x-show="showExportModal" 
             x-transition
             class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50"
             @click.self="showExportModal = false">
            <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
                <div class="mt-3">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Export Contacts to Email</h3>
                    <div class="mb-4">
                        <p class="text-sm text-gray-600 mb-2">
                            <span x-text="exportData.count"></span> unique email addresses selected:
                        </p>
                        <div class="bg-gray-50 p-3 rounded-lg max-h-32 overflow-y-auto">
                            <div class="text-sm text-gray-700" x-text="exportData.formatted_emails"></div>
                        </div>
                    </div>
                    <div class="flex justify-end space-x-3">
                        <button @click="showExportModal = false" 
                                class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400">
                            Cancel
                        </button>
                        <a :href="'{{ route("emails.create") }}?recipients=' + encodeURIComponent(exportData.formatted_emails)"
                           class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                            Open Email Composer
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script>
        function contactBookApp() {
            return {
                contacts: @json($paginatedContacts),
                allContacts: @json($paginatedContacts),
                filteredContacts: @json($paginatedContacts),
                paginatedContacts: @json($paginatedContacts),
                selectedContacts: [],
                searchTerm: '',
                filterType: 'all',
                isLoading: false,
                showExportModal: false,
                exportData: {},
                searchTimeout: null,
                currentPage: {{ $currentPage }},
                totalPages: {{ $totalPages }},
                perPage: 25,

                init() {
                    this.filteredContacts = this.allContacts;
                    this.updatePagination();
                },

                get pageNumbers() {
                    const pages = [];
                    const start = Math.max(1, this.currentPage - 2);
                    const end = Math.min(this.totalPages, this.currentPage + 2);
                    
                    for (let i = start; i <= end; i++) {
                        pages.push(i);
                    }
                    return pages;
                },

                debounceSearch() {
                    clearTimeout(this.searchTimeout);
                    this.searchTimeout = setTimeout(() => {
                        this.applyFilters();
                    }, 300);
                },

                async applyFilters() {
                    this.isLoading = true;
                    
                    try {
                        const response = await fetch('/ims/contact-book/contacts?' + new URLSearchParams({
                            search: this.searchTerm,
                            type: this.filterType
                        }));
                        
                        const data = await response.json();
                        this.filteredContacts = data.contacts;
                        this.currentPage = 1;
                        this.updatePagination();
                    } catch (error) {
                        console.error('Error fetching contacts:', error);
                        this.showToast('error', 'Failed to filter contacts');
                    } finally {
                        this.isLoading = false;
                    }
                },

                updatePagination() {
                    this.totalPages = Math.ceil(this.filteredContacts.length / this.perPage);
                    const start = (this.currentPage - 1) * this.perPage;
                    const end = start + this.perPage;
                    this.paginatedContacts = this.filteredContacts.slice(start, end);
                },

                changePage(page) {
                    if (page >= 1 && page <= this.totalPages) {
                        this.currentPage = page;
                        this.updatePagination();
                    }
                },

                clearFilters() {
                    this.searchTerm = '';
                    this.filterType = 'all';
                    this.filteredContacts = this.allContacts;
                    this.currentPage = 1;
                    this.updatePagination();
                },

                toggleContact(contactId, checked) {
                    if (checked) {
                        if (!this.selectedContacts.includes(contactId)) {
                            this.selectedContacts.push(contactId);
                        }
                    } else {
                        this.selectedContacts = this.selectedContacts.filter(id => id !== contactId);
                    }
                },

                toggleSelectAll(checked) {
                    if (checked) {
                        this.selectedContacts = [...new Set([...this.selectedContacts, ...this.paginatedContacts.map(c => c.id)])];
                    } else {
                        const currentPageIds = this.paginatedContacts.map(c => c.id);
                        this.selectedContacts = this.selectedContacts.filter(id => !currentPageIds.includes(id));
                    }
                },

                selectAll() {
                    this.selectedContacts = this.filteredContacts.map(c => c.id);
                },

                clearSelection() {
                    this.selectedContacts = [];
                },

                async exportSelected() {
                    if (this.selectedContacts.length === 0) {
                        this.showToast('warning', 'Please select contacts to export');
                        return;
                    }

                    try {
                        const response = await fetch('/ims/contact-book/export-to-email', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            },
                            body: JSON.stringify({
                                contact_ids: this.selectedContacts
                            })
                        });

                        const data = await response.json();
                        
                        if (response.ok) {
                            this.exportData = data;
                            this.showExportModal = true;
                        } else {
                            this.showToast('error', data.error || 'Failed to export contacts');
                        }
                    } catch (error) {
                        console.error('Error exporting contacts:', error);
                        this.showToast('error', 'An error occurred while exporting');
                    }
                },

                copyEmail(contact) {
                    if (!contact.email) return;
                    
                    navigator.clipboard.writeText(contact.email).then(() => {
                        this.showToast('success', `Copied ${contact.email} to clipboard`);
                    }).catch(() => {
                        this.showToast('error', 'Failed to copy email');
                    });
                },

                sendEmail(contact) {
                    if (!contact.email) return;
                    
                    window.location.href = `{{ route('emails.create') }}?recipients=${encodeURIComponent(contact.email)}`;
                },

                showToast(type, message) {
                    const toast = document.createElement('div');
                    toast.className = `mb-4 p-4 rounded-lg shadow-lg transition-all duration-300 transform translate-x-0 ${
                        type === 'success' ? 'bg-green-100 border border-green-200 text-green-800' :
                        type === 'error' ? 'bg-red-100 border border-red-200 text-red-800' :
                        type === 'warning' ? 'bg-yellow-100 border border-yellow-200 text-yellow-800' :
                        'bg-blue-100 border border-blue-200 text-blue-800'
                    }`;
                    
                    toast.innerHTML = `
                        <div class="flex items-center">
                            <i class="fas ${type === 'success' ? 'fa-check-circle' : type === 'error' ? 'fa-exclamation-circle' : type === 'warning' ? 'fa-exclamation-triangle' : 'fa-info-circle'} mr-3"></i>
                            <span>${message}</span>
                            <button onclick="this.parentElement.parentElement.remove()" class="ml-auto text-lg hover:opacity-70">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    `;

                    document.getElementById('toast-container').appendChild(toast);

                    setTimeout(() => {
                        if (toast.parentNode) {
                            toast.remove();
                        }
                    }, 5000);
                }
            }
        }
    </script>
</x-app-layout>
