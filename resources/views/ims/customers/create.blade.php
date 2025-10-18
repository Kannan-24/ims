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
                            <div class="flex items-center justify-end mb-4">
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
                <div class="flex items-center justify-between pt-6 border-t border-gray-200">
                    <!-- Left side - Previous button -->
                    <div>
                        <button type="button" @click="previousStep()" x-show="activeTab !== 'info'"
                            class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">
                            <i class="fas fa-chevron-left mr-2"></i>
                            Previous
                        </button>
                    </div>

                    <!-- Right side - Next/Submit buttons -->
                    <div class="flex items-center space-x-3">
                        <a href="{{ route('customers.index') }}"
                            class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">
                            Cancel
                        </a>

                        <!-- Next Button (shown when not on last tab) -->
                        <button type="button" @click="nextStep()" x-show="activeTab !== 'contacts'"
                            class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                            Next
                            <i class="fas fa-chevron-right ml-2"></i>
                        </button>

                        <!-- Submit Button (shown only on last tab) -->
                        <button type="submit" x-show="activeTab === 'contacts'" :disabled="isSubmitting"
                            class="px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 disabled:bg-green-400 transition-colors">
                            <i class="fas fa-save mr-2"></i>
                            Create Customer
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
    function customerCreateManager() {
        return {
            activeTab: 'info',
            // Start with a single empty contact person
            contactPersons: [{
                name: '',
                designation: '',
                phone_no: '',
                email: ''
            }],
            errors: {},
            isSubmitting: false,

            init() {
                // Initialization logic if needed
            },

            nextStep() {
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

            submitForm() {
                if (this.isSubmitting) return;

                this.isSubmitting = true;
                this.errors = {};

                const form = document.getElementById('customerForm');
                const formData = new FormData(form);

                if (this.contactPersons.length === 0) {
                    this.errors.contacts = 'At least one contact person is required';
                    this.activeTab = 'contacts';
                    this.isSubmitting = false;
                    return;
                }

                form.submit();
            }
        }
    }
</script>
</x-app-layout>
