<x-app-layout>
    <div class="bg-white min-h-screen" x-data="customerCreateManager()" x-init="init()">
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
                            <span class="text-sm font-medium text-gray-500">Create Customer</span>
                        </div>
                    </li>
                </ol>
            </nav>
        </div>

        <!-- Header -->
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Create New Customer</h1>
                    <p class="text-sm text-gray-600 mt-1">Add customer information and contact details</p>
                </div>
                <div class="flex items-center space-x-3">
                    <!-- Help Button -->
                    <button @click="showHelpModal = true"
                        class="inline-flex items-center px-3 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium rounded-lg transition-colors">
                        <i class="fas fa-question-circle w-4 h-4 mr-2"></i>
                        Help
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
            <form action="{{ route('customers.store') }}" method="POST" id="customerForm" @submit.prevent="submitForm">
                @csrf

                <!-- Tab Navigation -->
                <div class="border-b border-gray-200 mb-6">
                    <nav class="-mb-px flex space-x-8">
                        <button type="button" @click="activeTab = 'info'"
                            :class="activeTab === 'info' ? 'border-blue-500 text-blue-600' :
                                'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                            class="py-2 px-1 border-b-2 font-medium text-sm">
                            <i class="fas fa-building mr-2"></i>
                            Customer Information
                        </button>
                        <button type="button" @click="activeTab = 'address'"
                            :class="activeTab === 'address' ? 'border-blue-500 text-blue-600' :
                                'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                            class="py-2 px-1 border-b-2 font-medium text-sm">
                            <i class="fas fa-map-marker-alt mr-2"></i>
                            Address Details
                        </button>
                        <button type="button" @click="activeTab = 'contacts'"
                            :class="activeTab === 'contacts' ? 'border-blue-500 text-blue-600' :
                                'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                            class="py-2 px-1 border-b-2 font-medium text-sm">
                            <i class="fas fa-users mr-2"></i>
                            Contact Persons
                            <span x-show="contactPersons.length > 0"
                                class="ml-1 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-blue-100 bg-blue-600 rounded-full"
                                x-text="contactPersons.length"></span>
                        </button>
                    </nav>
                </div>

                <!-- Tab Content -->
                <div class="space-y-6">
                    <!-- Customer Information Tab -->
                    <div x-show="activeTab === 'info'" x-transition:enter="transition ease-out duration-200"
                        x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
                        <div class="bg-white border border-gray-200 rounded-lg p-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Basic Information</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Company Name <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" name="company_name" required
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                        placeholder="Enter company name">
                                    <div class="mt-1 text-sm text-red-600" x-show="errors.company_name"
                                        x-text="errors.company_name"></div>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        GST Number
                                    </label>
                                    <input type="text" name="gst_number"
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                        placeholder="Enter GST number (optional)">
                                    <div class="mt-1 text-sm text-red-600" x-show="errors.gst_number"
                                        x-text="errors.gst_number"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Address Details Tab -->
                    <div x-show="activeTab === 'address'" x-transition:enter="transition ease-out duration-200"
                        x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
                        <div class="bg-white border border-gray-200 rounded-lg p-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Address Information</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                                <div class="md:col-span-2 lg:col-span-3">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Address <span class="text-red-500">*</span>
                                    </label>
                                    <textarea name="address" required rows="3"
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                        placeholder="Enter full address"></textarea>
                                    <div class="mt-1 text-sm text-red-600" x-show="errors.address"
                                        x-text="errors.address"></div>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        City <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" name="city" required
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                        placeholder="Enter city">
                                    <div class="mt-1 text-sm text-red-600" x-show="errors.city" x-text="errors.city">
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        State <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" name="state" required
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                        placeholder="Enter state">
                                    <div class="mt-1 text-sm text-red-600" x-show="errors.state"
                                        x-text="errors.state"></div>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        ZIP Code <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" name="zip_code" required
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                        placeholder="Enter ZIP code">
                                    <div class="mt-1 text-sm text-red-600" x-show="errors.zip_code"
                                        x-text="errors.zip_code"></div>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Country <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" name="country" required value="India"
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                        placeholder="Enter country">
                                    <div class="mt-1 text-sm text-red-600" x-show="errors.country"
                                        x-text="errors.country"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Contact Persons Tab -->
                    <div x-show="activeTab === 'contacts'" x-transition:enter="transition ease-out duration-200"
                        x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
                        <div class="bg-white border border-gray-200 rounded-lg p-6">
                            <div class="flex items-center justify-between mb-4">
                                <h3 class="text-lg font-semibold text-gray-900">Contact Persons</h3>
                                <button type="button" @click="addContactPerson()"
                                    class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-lg transition-colors">
                                    <i class="fas fa-plus w-4 h-4 mr-2"></i>
                                    Add Contact
                                </button>
                            </div>

                            <div x-show="contactPersons.length === 0" class="text-center py-8">
                                <i class="fas fa-user-plus text-gray-300 text-4xl mb-4"></i>
                                <h4 class="text-lg font-medium text-gray-900 mb-2">No contact persons added</h4>
                                <p class="text-gray-500 mb-4">Add at least one contact person for this customer</p>
                                <button type="button" @click="addContactPerson()"
                                    class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors">
                                    <i class="fas fa-plus w-4 h-4 mr-2"></i>
                                    Add First Contact
                                </button>
                            </div>

                            <div class="space-y-6">
                                <template x-for="(contact, index) in contactPersons" :key="index">
                                    <div class="border border-gray-200 rounded-lg p-4 bg-gray-50">
                                        <div class="flex items-center justify-between mb-4">
                                            <h4 class="text-md font-medium text-gray-900"
                                                x-text="`Contact Person ${index + 1}`"></h4>
                                            <button type="button" @click="removeContactPerson(index)"
                                                class="text-red-600 hover:text-red-800 transition-colors">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                                    Name <span class="text-red-500">*</span>
                                                </label>
                                                <input type="text" :name="`contact_persons[${index}][name]`"
                                                    x-model="contact.name" required
                                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                                    placeholder="Enter name">
                                            </div>
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                                    Designation
                                                </label>
                                                <input type="text" :name="`contact_persons[${index}][designation]`"
                                                    x-model="contact.designation"
                                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                                    placeholder="Enter designation">
                                            </div>
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                                    Phone <span class="text-red-500">*</span>
                                                </label>
                                                <input type="tel" :name="`contact_persons[${index}][phone_no]`"
                                                    x-model="contact.phone_no" required
                                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                                    placeholder="Enter phone number">
                                            </div>
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                                    Email <span class="text-red-500">*</span>
                                                </label>
                                                <input type="email" :name="`contact_persons[${index}][email]`"
                                                    x-model="contact.email" required
                                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                                    placeholder="Enter email address">
                                            </div>
                                        </div>
                                    </div>
                                </template>
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
                        <button type="button" @click="previousStep()" x-show="activeTab !== 'info'"
                            class="px-6 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 font-medium transition-colors inline-flex items-center">
                            <i class="fas fa-arrow-left mr-2"></i>
                            Previous
                        </button>

                        <!-- Cancel Button -->
                        <a href="{{ route('customers.index') }}"
                            class="px-6 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 font-medium transition-colors">
                            Cancel
                        </a>

                        <!-- Next Button -->
                        <button type="button" @click="nextStep()" x-show="activeTab !== 'contacts'"
                            class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium transition-colors inline-flex items-center">
                            Next
                            <i class="fas fa-arrow-right ml-2"></i>
                        </button>

                        <!-- Create Button -->
                        <div x-show="activeTab === 'contacts'">
                            <button type="submit" :disabled="isSubmitting"
                                class="px-6 py-3 bg-green-600 hover:bg-green-700 disabled:bg-green-400 text-white rounded-lg font-medium transition-colors inline-flex items-center">
                                <span x-show="!isSubmitting">
                                    <i class="fas fa-save mr-2"></i>
                                    Create Customer
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
                                    Creating...
                                </span>
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <!-- Help Modal -->
        <div x-show="showHelpModal" x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-white rounded-lg shadow-xl max-w-4xl w-full mx-4 max-h-[90vh] overflow-y-auto">
                <div class="flex items-center justify-between p-6 border-b border-gray-200">
                    <h2 class="text-xl font-bold text-gray-900">Create Customer Help</h2>
                    <button @click="closeHelpModal()" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>

                <div class="p-6 space-y-6">
                    <!-- Step Guide -->
                    <div class="border border-gray-200 rounded-lg p-4">
                        <h3 class="text-lg font-semibold text-gray-900 mb-3">
                            <i class="fas fa-list-ol text-blue-600 mr-2"></i>Step-by-Step Guide
                        </h3>
                        <div class="space-y-4">
                            <div class="flex items-start space-x-3">
                                <div
                                    class="w-6 h-6 bg-blue-600 text-white rounded-full flex items-center justify-center text-sm font-bold">
                                    1</div>
                                <div>
                                    <h4 class="font-medium text-gray-900">Company Information</h4>
                                    <p class="text-sm text-gray-600">Enter the customer's company name and GST number
                                        (optional).</p>
                                </div>
                            </div>
                            <div class="flex items-start space-x-3">
                                <div
                                    class="w-6 h-6 bg-blue-600 text-white rounded-full flex items-center justify-center text-sm font-bold">
                                    2</div>
                                <div>
                                    <h4 class="font-medium text-gray-900">Address Details</h4>
                                    <p class="text-sm text-gray-600">Provide complete address including city, state,
                                        ZIP code, and country.</p>
                                </div>
                            </div>
                            <div class="flex items-start space-x-3">
                                <div
                                    class="w-6 h-6 bg-blue-600 text-white rounded-full flex items-center justify-center text-sm font-bold">
                                    3</div>
                                <div>
                                    <h4 class="font-medium text-gray-900">Contact Persons</h4>
                                    <p class="text-sm text-gray-600">Add at least one contact person with name, phone,
                                        and email.</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Navigation Help -->
                    <div class="border border-gray-200 rounded-lg p-4">
                        <h3 class="text-lg font-semibold text-gray-900 mb-3">
                            <i class="fas fa-keyboard text-green-600 mr-2"></i>Navigation & Shortcuts
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="space-y-2">
                                <div class="flex items-center justify-between">
                                    <span class="text-gray-700">Previous Step</span>
                                    <button
                                        class="px-2 py-1 bg-gray-100 border border-gray-300 rounded text-sm">Previous</button>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="text-gray-700">Next Step</span>
                                    <button
                                        class="px-2 py-1 bg-gray-100 border border-gray-300 rounded text-sm">Next</button>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="text-gray-700">Save Customer</span>
                                    <kbd class="px-2 py-1 bg-gray-100 border border-gray-300 rounded text-sm">Ctrl +
                                        S</kbd>
                                </div>
                            </div>
                            <div class="space-y-2">
                                <div class="flex items-center justify-between">
                                    <span class="text-gray-700">Cancel & Exit</span>
                                    <kbd class="px-2 py-1 bg-gray-100 border border-gray-300 rounded text-sm">Esc</kbd>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="text-gray-700">Show Help</span>
                                    <kbd class="px-2 py-1 bg-gray-100 border border-gray-300 rounded text-sm">H</kbd>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="text-gray-700">Add Contact</span>
                                    <kbd class="px-2 py-1 bg-gray-100 border border-gray-300 rounded text-sm">+
                                        Button</kbd>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Field Requirements -->
                    <div class="border border-gray-200 rounded-lg p-4">
                        <h3 class="text-lg font-semibold text-gray-900 mb-3">
                            <i class="fas fa-exclamation-circle text-amber-600 mr-2"></i>Required Fields
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="space-y-2">
                                <div><strong>Company Information:</strong></div>
                                <ul class="text-sm text-gray-600 ml-4 space-y-1">
                                    <li>• Company Name</li>
                                    <li>• GST Number (Optional)</li>
                                </ul>
                                <div><strong>Address Details:</strong></div>
                                <ul class="text-sm text-gray-600 ml-4 space-y-1">
                                    <li>• Complete Address</li>
                                    <li>• City</li>
                                    <li>• State</li>
                                    <li>• ZIP Code</li>
                                    <li>• Country</li>
                                </ul>
                            </div>
                            <div class="space-y-2">
                                <div><strong>Contact Persons:</strong></div>
                                <ul class="text-sm text-gray-600 ml-4 space-y-1">
                                    <li>• At least one contact person</li>
                                    <li>• Contact Name</li>
                                    <li>• Phone Number</li>
                                    <li>• Email Address</li>
                                </ul>
                                <div class="bg-blue-50 border border-blue-200 rounded-lg p-3 mt-3">
                                    <p class="text-sm text-blue-800">
                                        <i class="fas fa-info-circle mr-1"></i>
                                        Fields marked with <span class="text-red-500">*</span> are required
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Tips & Best Practices -->
                    <div class="border border-gray-200 rounded-lg p-4">
                        <h3 class="text-lg font-semibold text-gray-900 mb-3">
                            <i class="fas fa-lightbulb text-yellow-600 mr-2"></i>Tips & Best Practices
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="space-y-2 text-sm text-gray-700">
                                <div>• <strong>GST Number:</strong> Include for business customers</div>
                                <div>• <strong>Multiple Contacts:</strong> Add different department contacts</div>
                                <div>• <strong>Phone Format:</strong> Include country/area codes</div>
                                <div>• <strong>Email Validation:</strong> Ensure emails are valid and unique</div>
                            </div>
                            <div class="space-y-2 text-sm text-gray-700">
                                <div>• <strong>Address:</strong> Be detailed for shipping purposes</div>
                                <div>• <strong>Navigation:</strong> Use tab or step buttons to move</div>
                                <div>• <strong>Validation:</strong> Complete each step before proceeding</div>
                                <div>• <strong>Save Often:</strong> Use Ctrl+S to save progress</div>
                            </div>
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
        </button>
    </div>
    </div>
    </div>
    </div>

    <script>
        function customerCreateManager() {
            return {
                activeTab: 'info',
                contactPersons: [],
                errors: {},
                isSubmitting: false,
                showHelpModal: false,

                init() {
                    this.bindKeyboardEvents();
                    // Add at least one contact person by default
                    this.addContactPerson();
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
                                this.closeHelpModal();
                            } else if (confirm('Are you sure you want to cancel? All changes will be lost.')) {
                                window.location.href = '{{ route('customers.index') }}';
                            }
                        }
                    });
                },

                nextStep() {
                    // Validate current step before moving to next
                    if (this.activeTab === 'info') {
                        const companyName = document.querySelector('input[name="company_name"]').value;
                        if (!companyName.trim()) {
                            alert('Please enter the company name before proceeding.');
                            document.querySelector('input[name="company_name"]').focus();
                            return;
                        }
                        this.activeTab = 'address';
                    } else if (this.activeTab === 'address') {
                        const address = document.querySelector('textarea[name="address"]').value;
                        const city = document.querySelector('input[name="city"]').value;
                        const state = document.querySelector('input[name="state"]').value;
                        const zipCode = document.querySelector('input[name="zip_code"]').value;
                        const country = document.querySelector('input[name="country"]').value;

                        if (!address.trim() || !city.trim() || !state.trim() || !zipCode.trim() || !country.trim()) {
                            alert('Please fill in all address fields before proceeding.');
                            return;
                        }
                        this.activeTab = 'contacts';
                    }
                },

                previousStep() {
                    if (this.activeTab === 'contacts') {
                        this.activeTab = 'address';
                    } else if (this.activeTab === 'address') {
                        this.activeTab = 'info';
                    }
                },

                addContactPerson() {
                    this.contactPersons.push({
                        name: '',
                        designation: '',
                        phone_no: '',
                        email: ''
                    });
                },

                removeContactPerson(index) {
                    if (confirm('Are you sure you want to remove this contact person?')) {
                        this.contactPersons.splice(index, 1);
                    }
                },

                closeHelpModal() {
                    this.showHelpModal = false;
                },

                submitForm() {
                    if (this.isSubmitting) return;

                    this.isSubmitting = true;
                    this.errors = {};

                    // Basic validation
                    const form = document.getElementById('customerForm');
                    const formData = new FormData(form);

                    // Check if at least one contact person is added
                    if (this.contactPersons.length === 0) {
                        this.errors.contacts = 'At least one contact person is required';
                        this.activeTab = 'contacts';
                        this.isSubmitting = false;
                        return;
                    }

                    // Submit the form
                    form.submit();
                }
            }
        }
    </script>
</x-app-layout>
